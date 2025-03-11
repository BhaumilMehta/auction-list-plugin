<?php 

// Register custom post type
function auction_list_register_post_type() {
    register_post_type('auction_list', [
        'labels' => [
            'name' => __('Auction Lists', 'auction-list'),
            'singular_name' => __('Auction List', 'auction-list'),
        ],
        'public' => true,
        'has_archive' => true,
        'show_in_menu' => true,
        'supports' => ['title' , 'thumbnail'],
    ]);
}
add_action('init', 'auction_list_register_post_type');


function auction_list_register_admin_page() {
    add_submenu_page(
        'edit.php?post_type=auction_list',
        __('Auction Listing Shortcode', 'auction-list'),
        __('Shortcode', 'auction-list'),
        'manage_options',
        'auction-listing-shortcode',
        'auction_list_shortcode_page_callback'
    );
    add_submenu_page(
        'edit.php?post_type=auction_list',
        'Auction UI Settings',
        'UI Settings',
        'manage_options',
        'auction-ui-settings',
        'auction_list_ui_settings_page',
        'dashicons-admin-customizer',
    );
}
add_action('admin_menu', 'auction_list_register_admin_page');

function auction_list_shortcode_page_callback() {
    ?>
    <div class="wrap">
        <h1><?php _e('Auction Listing Shortcode', 'auction-list'); ?></h1>
        <p><?php _e('Use the following shortcode to display the auction list:', 'auction-list'); ?></p>
        <code>[auction-main-list]</code>

        <div style="margin-top:20px;">
            <button id="import-auctions" class="button button-primary">
                <?php _e('Import Auctions', 'auction-list'); ?>
            </button>
            <span id="import-status" style="margin-left: 10px;"></span>
        </div>
    </div>

    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('#import-auctions').on('click', function() {
                var button = $(this);
                var status = $('#import-status');
                
                button.prop('disabled', true);
                status.text('<?php _e("Importing auctions...", "auction-list"); ?>');

                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'import_auction_list'
                    },
                    success: function(response) {
                        status.text('<?php _e("Import completed!", "auction-list"); ?>');
                        button.prop('disabled', false);
                    },
                    error: function() {
                        status.text('<?php _e("Import failed. Please try again.", "auction-list"); ?>');
                        button.prop('disabled', false);
                    }
                });
            });
        });
    </script>
    <?php
}

add_action('wp_ajax_import_auction_list', 'import_auction_list');

function auction_list_ui_settings_page() {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['auction_ui_settings'])) {
        update_option('auction_ui_border', sanitize_text_field($_POST['auction_ui_border']));
        update_option('auction_ui_border_radius', sanitize_text_field($_POST['auction_ui_border_radius']));
        update_option('auction_ui_bg_color', sanitize_text_field($_POST['auction_ui_bg_color']));
        update_option('auction_ui_btn_color', sanitize_text_field($_POST['auction_ui_btn_color']));
        update_option('auction_ui_btn_hover_color', sanitize_text_field($_POST['auction_ui_btn_hover_color']));
        update_option('auction_ui_title', sanitize_text_field($_POST['auction_ui_title']));
        update_option('auction_ui_title_street', sanitize_text_field($_POST['auction_ui_title_street']));
        update_option('auction_ui_button_width', sanitize_text_field($_POST['auction_ui_button_width']));
        update_option('auction_ui_bg_opacity', sanitize_text_field($_POST['auction_ui_bg_opacity']));

        echo '<div class="updated"><p>Settings saved.</p></div>';
    }

    $border = get_option('auction_ui_border', '2px solid #5d86b1');
    $border_radius = get_option('auction_ui_border_radius', '10px');
    $bg_color = get_option('auction_ui_bg_color', '#e9ecf1');
    $btn_color = get_option('auction_ui_btn_color', '#ec5f2a');
    $btn_hover_color = get_option('auction_ui_btn_hover_color', '#ec5027');
    $auction_ui_title = get_option('auction_ui_title', '#000');
    $auction_ui_title_street = get_option('auction_ui_title_street', '#5d6065');
    $auction_ui_button_width = get_option('auction_ui_button_width', '100%');
    $auction_ui_bg_opacity = get_option('auction_ui_bg_opacity', '100%');
    ?>

    <div class="wrap">
        
    <form method="post">
        <table class="form-table">
            <tr><th colspan="2"><h1> <strong> Auction UI Settings</strong></h1></th></tr>
            
            <tr>
                <th><label for="auction_ui_border">Auction Box Border:</label></th>
                <td><input type="text" name="auction_ui_border" id="auction_ui_border" value="<?php echo esc_attr($border); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label for="auction_ui_border_radius">Box Border Radius:</label></th>
                <td><input type="text" name="auction_ui_border_radius" id="auction_ui_border_radius" value="<?php echo esc_attr($border_radius); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th><label for="auction_ui_bg_color">Auction Background Color:</label></th>
                <td>
                    <input type="color" name="auction_ui_bg_color" id="auction_ui_bg_color" value="<?php echo esc_attr($bg_color); ?>" class="color-picker-field">
                </td>
            </tr>
            <tr>
                <th><label for="auction_ui_bg_opacity">Auction Background Transparency:</label></th>
                <td>
                    <input type="range" name="auction_ui_bg_opacity" id="auction_ui_bg_opacity" min="0" max="100" value="<?php echo esc_attr($auction_ui_bg_opacity); ?>">
                    <span id="opacity_value"><?php echo esc_attr($auction_ui_bg_opacity); ?>%</span>
                </td>
            </tr>

            <tr>
                <th colspan="2"><h1> <strong> Auction Title Settings</strong></h1></th>
            </tr>
            <tr>
                <th><label for="auction_ui_title">Auction Title Color:</label></th>
                <td><input type="color" name="auction_ui_title" id="auction_ui_title" value="<?php echo esc_attr($auction_ui_title); ?>" class="color-picker-field"></td>
            </tr>
            <tr>
                <th><label for="auction_ui_title_street">Auction Street Text Color:</label></th>
                <td><input type="color" name="auction_ui_title_street" id="auction_ui_title_street" value="<?php echo esc_attr($auction_ui_title_street); ?>" class="color-picker-field"></td>
            </tr>

            <tr>
                <th colspan="2"><h1> <strong> Bid Now Button Settings</strong></h1></th>
            </tr>
            <tr>
                <th><label for="auction_ui_btn_color">Bid Now Button Color:</label></th>
                <td><input type="color" name="auction_ui_btn_color" id="auction_ui_btn_color" value="<?php echo esc_attr($btn_color); ?>" class="color-picker-field"></td>
            </tr>
            <tr>
                <th><label for="auction_ui_btn_hover_color">Bid Now Button Hover Color:</label></th>
                <td><input type="color" name="auction_ui_btn_hover_color" id="auction_ui_btn_hover_color" value="<?php echo esc_attr($btn_hover_color); ?>" class="color-picker-field"></td>
            </tr>
            <tr>
                <th><label for="auction_ui_button_width">Button Width (Percentage):</label></th>
                <td><input type="text" name="auction_ui_button_width" id="auction_ui_button_width" value="<?php echo esc_attr($auction_ui_button_width); ?>" class="regular-text"></td>
            </tr>
        </table>
        
        <br>
        <input type="submit" name="auction_ui_settings" value="Save Settings" class="button-primary">
    </form>
    </div>

    <?php
}




