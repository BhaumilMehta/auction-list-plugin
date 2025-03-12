
<?php 
// Enqueue custom JS
function auction_list_enqueue_scripts($hook_suffix) {

    global $post_type;
    if ($post_type === 'auction_list' && in_array($hook_suffix, ['post-new.php', 'post.php'])) {
        wp_enqueue_script('auction-list-custom-js' , AUCTION_LIST_PLUGIN_URL.'assets/admin/js/custom.js',['jquery'], time() ,true );
    }
}
add_action('admin_enqueue_scripts', 'auction_list_enqueue_scripts');

function auction_list_enqueue_frontend_scripts() {
    if (!wp_style_is('slick-css', 'enqueued') && !wp_style_is('slick', 'enqueued')) {
        wp_enqueue_style('slick-css', AUCTION_LIST_PLUGIN_URL . 'assets/frontend/css/slick.css', array(), time());
    }

    wp_enqueue_style('auction-list-frontend-css', AUCTION_LIST_PLUGIN_URL . 'assets/frontend/css/style.css', array(), time());

    if (!wp_script_is('slick-js', 'enqueued') && !wp_script_is('slick', 'enqueued')) {
        wp_enqueue_script('slick-js', AUCTION_LIST_PLUGIN_URL . 'assets/frontend/js/slick.min.js', array('jquery'), time(), true);
    }

    wp_enqueue_script('auction-list-custom-frontend-js', AUCTION_LIST_PLUGIN_URL . 'assets/frontend/js/custom.js', array('jquery'), time(), true);
}

add_action('wp_enqueue_scripts', 'auction_list_enqueue_frontend_scripts');
