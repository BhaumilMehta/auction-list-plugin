<?php 

function run_auction_import_on_activation() {
    import_auction_list();
    reset_auction_import_cron();
}
register_activation_hook(__FILE__, 'run_auction_import_on_activation');

function schedule_auction_import() {
    if (!wp_next_scheduled('import_auction_list_cron')) {
        wp_schedule_event(time(), 'two_hours', 'import_auction_list_cron');
    }
}
add_action('wp', 'schedule_auction_import');

function custom_cron_schedules($schedules) {
    $schedules['two_hours'] = array(
        'interval' => 2 * 60 * 60, // 2 hours
        'display'  => __('Every Two Hours', 'auction-list')
    );
    return $schedules;
}
add_filter('cron_schedules', 'custom_cron_schedules');


add_action('init', 'import_auction_list');

function import_auction_list() {
    $curl = curl_init();
    
    $key_x = get_option('key_x');
    $key_y = get_option('key_y');
    $key_z = get_option('key_z');

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://localauctions.com/elasticsearch/search',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{"z":"'.$key_z.'","y":"'.$key_y.'","x":"'.$key_x.'"}',
        CURLOPT_HTTPHEADER => array(
            'Accept: application/json, text/javascript, */*; q=0.01',
            'Content-Type: application/json',
        ),
    ));

    $response = curl_exec($curl);
    $error = curl_error($curl);
    $error_no = curl_errno($curl);
    curl_close($curl);

    if (!$response || $error_no) {
        error_log("Auction Import Failed: " . $error);
        return;
    }

    $data = json_decode($response, true);       

    
    if (!isset($data['hits']['hits']) || !is_array($data['hits']['hits'])) {
        return;
    }

    $fetched_auction_ids = [];

    foreach ($data['hits']['hits'] as $auction) {
        if (!isset($auction['_source'])) {
            continue;
        }

        $source = $auction['_source'];
        $title = sanitize_text_field($source['title_text'] ?? 'Untitled Auction');
        $auction_id = sanitize_text_field($source['auction_id_number'] ?? '');

        if (!$auction_id) {
            continue;
        }

        $fetched_auction_ids[] = $auction_id;

        $existing_post_id = get_posts([
            'post_type'  => 'auction_list',
            'meta_key'   => '_auction_id',
            'meta_value' => $auction_id,
            'numberposts' => 1,
            'fields' => 'ids'
        ]);

        $post_data = [
            'post_title'   => $title,
            'post_status'  => 'publish',
            'post_type'    => 'auction_list',
        ];

        if ($existing_post_id) {
            $post_data['ID'] = $existing_post_id[0];
            wp_update_post($post_data);
            $post_id = $existing_post_id[0];
        } else {
            $post_id = wp_insert_post($post_data);
        }
        

        if ($post_id) {
            update_post_meta($post_id, '_auction_list_option', 'id');
            update_post_meta($post_id, 'auction_list_option', 'id');
            update_post_meta($post_id, 'auction_list_id', $auction_id);
            update_post_meta($post_id, '_auction_list_id', $auction_id);
            update_post_meta($post_id, 'end_date_text_text', sanitize_text_field($source['end_date_text_text'] ?? ''));
            update_post_meta($post_id, 'auction_title_sub_text', sanitize_text_field($source['auction_title_sub_text'] ?? ''));
            update_post_meta($post_id, 'street_address_text', sanitize_text_field($source['street_address_text'] ?? ''));
            update_post_meta($post_id, 'url_text', esc_url($source['url_text'] ?? ''));
            update_post_meta($post_id, '_auction_id', $auction_id);
            update_post_meta($post_id, '_auction_url', esc_url($source['url_text'] ?? ''));
            update_post_meta($post_id, '_auction_city', sanitize_text_field($source['city_text'] ?? ''));
            update_post_meta($post_id, '_auction_state', sanitize_text_field($source['state_text'] ?? ''));
            update_post_meta($post_id, '_auction_address', sanitize_text_field($source['street_address_text'] ?? ''));

        }

        if (isset($auction_id)) {
        
            $id_value = sanitize_text_field($auction_id);
            update_post_meta($post_id, '_auction_list_id', $id_value);
    
            $response = wp_remote_post('https://online.localauctions.com/api/getitems', [
                'body' => [
                    'auction_id' => $id_value,
                ],
            ]);
          
            if (is_wp_error($response)) {
                update_post_meta($post_id, '_auction_list_data', __('Error fetching data', 'auction-list'));
            } else {
                $data = wp_remote_retrieve_body($response);
                $decoded_data = json_decode($data, true);
    
            
                if (json_last_error() === JSON_ERROR_NONE) { 
                    if (isset($decoded_data['items']) && is_array($decoded_data['items'])) {
                        
                        update_post_meta($post_id, '_auction_list_data_total_count', maybe_serialize($decoded_data['total']));
                        update_post_meta($post_id, '_auction_list_data', maybe_serialize($decoded_data['items']));
                    } else {
                        update_post_meta($post_id, '_auction_list_data_total_count', __('No items found in the data.', 'auction-list'));
                        update_post_meta($post_id, '_auction_list_data', __('No items found in the data.', 'auction-list'));
                    }
                } else {
                    update_post_meta($post_id, '_auction_list_data', __('Error decoding JSON data.', 'auction-list'));
                }
            }
        } 

        
        clean_post_cache($post_id);
    }

    $existing_auctions = get_posts([
        'post_type'      => 'auction_list',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'fields'         => 'ids',
    ]);

    foreach ($existing_auctions as $auction_id) {
        $stored_auction_id = get_post_meta($auction_id, '_auction_id', true);
        $auction_option = get_post_meta($auction_id, '_auction_list_option', true);

        if ($auction_option === 'id' && !in_array($stored_auction_id, $fetched_auction_ids)) {
            wp_trash_post($auction_id);
            clean_post_cache($auction_id);
        }
    }
}

function reset_auction_import_cron() {
    wp_clear_scheduled_hook('import_auction_list_cron');
    schedule_auction_import();
}
