<?php 

// Add meta box
function auction_list_add_meta_box() {
    add_meta_box(
        'auction_list_meta_box',
        __('Auction List Options', 'auction-list'),
        'auction_list_meta_box_callback',
        'auction_list',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'auction_list_add_meta_box');

// Meta box callback
function auction_list_meta_box_callback($post) {
    $selected_option = get_post_meta($post->ID, '_auction_list_option', true);
    $id_value = get_post_meta($post->ID, '_auction_list_id', true);
    $url_value = get_post_meta($post->ID, '_auction_list_url', true);

    wp_nonce_field('auction_list_save_meta_box', 'auction_list_meta_box_nonce');
    ?>
    <p>
        <label for="auction_list_option"><?php _e('Select Data Source:', 'auction-list'); ?></label>
        <select id="auction_list_option" name="auction_list_option">
            <option value="" ><?php _e('Get Data From', 'auction-list'); ?></option>
            <option value="id" <?php selected($selected_option, 'id'); ?>><?php _e('Data from local ID', 'auction-list'); ?></option>
            <option value="url" <?php selected($selected_option, 'url'); ?>><?php _e('Data from URL', 'auction-list'); ?></option>
        </select>
    </p>
    <p id="auction_list_id_field" style="display: <?php echo ($selected_option === 'id') ? 'block' : 'none'; ?>;">
        <label for="auction_list_id"><?php _e('Enter ID:', 'auction-list'); ?></label>
        <input type="text" id="auction_list_id" name="auction_list_id" value="<?php echo esc_attr($id_value); ?>">
    </p>
    <p id="auction_list_url_field" style="display: <?php echo ($selected_option === 'url') ? 'none' : 'none'; ?>;">
        <label for="auction_list_url"><?php _e('Enter URL:', 'auction-list'); ?></label>
        <input type="url" id="auction_list_url" name="auction_list_url" value="https://bidnow.auctionaz.com/api/feed/all">
    </p>
    <?php
}

// Save meta box data and fetch remote data
function auction_list_save_meta_box($post_id) {
    if (!isset($_POST['auction_list_meta_box_nonce']) ||
        !wp_verify_nonce($_POST['auction_list_meta_box_nonce'], 'auction_list_save_meta_box')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $selected_option = sanitize_text_field($_POST['auction_list_option']);
    update_post_meta($post_id, '_auction_list_option', $selected_option);

    if ($selected_option === 'id' && isset($_POST['auction_list_id'])) {
        
        $id_value = sanitize_text_field($_POST['auction_list_id']);
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
    } elseif ($selected_option === 'url' && isset($_POST['auction_list_url'])) {
        $url_value = esc_url_raw($_POST['auction_list_url']);
        update_post_meta($post_id, '_auction_list_url', $url_value);

        // Fetch data from URL
        $response = wp_remote_get($url_value);
        
        if (is_wp_error($response)) {
            update_post_meta($post_id, '_auction_list_data', __('Error fetching data', 'auction-list'));
        } else {
            $data = wp_remote_retrieve_body($response);

            $decoded_data = json_decode($data, true);

            if (json_last_error() === JSON_ERROR_NONE) {

                if (isset($decoded_data['active']['results']) && is_array($decoded_data['active']['results'])) {
                    $active_data = $decoded_data['active']['results'];
    
                    update_post_meta($post_id, '_auction_list_data', maybe_serialize($active_data));
                }else{

                    update_post_meta($post_id, '_auction_list_data', __('No active results found in the data.', 'auction-list'));
                }
            
                if(isset($decoded_data['complete']['results']) && is_array($decoded_data['complete']['results'])){
                    $complete_data = $decoded_data['complete']['results'];
    
                    update_post_meta($post_id, '_auction_list_data_complete', maybe_serialize($complete_data));
                }else{
                    update_post_meta($post_id, '_auction_list_data_complete', __('No active results found in the data.', 'auction-list'));
                }
            } else {
                update_post_meta($post_id, '_auction_list_data', __('Error decoding JSON data.', 'auction-list'));
            }
        }
    }
}
add_action('save_post', 'auction_list_save_meta_box');