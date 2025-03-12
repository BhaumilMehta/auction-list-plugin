<?php
/**
 * Plugin Name: Auction List Plugin with API Integration
 * Description: A plugin to create an Auction List post type with dynamic fields and API integration for data fetching.
 * Version: 1.1
 * Author: Dynamic Dreamz
 */

if (!defined('ABSPATH')) {
    exit;
}

define('AUCTION_LIST_PLUGIN_URL', plugin_dir_url(__FILE__));

// Auction Import Process
include('import-auction/import-auction.php');

// To create a custom Functions
include('inc/dd-functions.php');


// To create a custom Post type
include('inc/posttype.php');

// To create a meta box and store data from API as per backend setting
include('inc/post-metabox-store-data.php');

// Enque Script
include('inc/script-enque.php');

// Create a custom shortcode
include('inc/auction-shortcode.php');



