<?php
/*
 * Plugin Name: Widgets Bitcoin Plugin
 * Plugin URI: https://banana.international/wordpress/plugin/widget-bitcoin
 * Description: Widgets with bitcoin quotes
 * Version: 1.1.0
 * Author: Banana
 * Author URI: https://banana.international/
 * License: GPLv2 or later
 */

require_once plugin_dir_path(__FILE__) . 'includes/bananawb-functions.php';

add_action( 'wp_head', 'bananawb_css_set_prices' );

function bananawb_css_set_prices() {
    wp_enqueue_style('style-prices', plugins_url('assets/css/style-prices.css', __FILE__));
    wp_enqueue_style('style-widget', plugins_url('assets/css/style-widget.css', __FILE__));
}