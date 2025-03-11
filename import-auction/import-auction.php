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


add_action('import_auction_list_cron', 'import_auction_list');

function import_auction_list() {
    $curl = curl_init();
    
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://localauctions.com/elasticsearch/search',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{"z":"zY1L2m1a58kbSM9K9S6QyPPK8XOVilg6okf+cDlAdSMPJ6pU8GXpb0AEt9NQa6Jvgco4B/nYEvk1W3npKtlRFaMNS1j/0RFi/pwRqBe9SyPV31uqjw+Y4W2zl9nEqqASTM4KDSQwwg5q1msJyHHwOF86ykRRpe2Gl3CcCBU1WWJcPbq0TxHDz9pjiUTO/Y5RGwPmAasddYjbyYRcG1BL2ZKvcw/xousijlJx/R22TZZ2y/6DjN6VY8Fv5daUoSaJ7eAoOHz5INX9rtSNmotyNgHq4JA6oPM8oDrEXLocgUJKuCwC2d8qnR0B3vBNQhbW3GrE3yTe+PT6kHFV6kXoooOIXZMR+qb+PIISDaKjdBpPK6m9IxhP75LEYHysjwvxYa91f0WdRy4U+c6/cMZbv9QYqJUkScThoTJb2vH06wEbYx3wrdBIqyXm6Xc0O4XDt6kr3lIfwHyODpzKpaYxe3KOXY1K9sLZ5OTuDl5NZijT5AVCxACKLb26LLgfgR6JAG0njl8STZGeiOf69OE2sCozdrgSlZiJSOkRul04lfr5jqBo4WQA+eSxFdkGKFBtRkG/Ng90VprRDnHINdFDg2/0qgh6hkq6+Y6G1bIQR1mEWkHV+OjELFDPlgWn6Myfs6n0QHnm22v05+GG9AxHibnzi/8xKmKrHBT43Nmf0VDbbVx+5hrKhJBcXTNoPZrToMt7MMK37PD12jEx7vVaKctpP+N79fBKF864kYnNAmRKZ/p/k9bPDcuhBjd5HbtM","y":"4UXlXTfj2krMjQzslpBtyg==","x":"0ODL0L4HLXyzFTULm5TEaBJkJCWtuHp1KisKSiePxi0="}',
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
