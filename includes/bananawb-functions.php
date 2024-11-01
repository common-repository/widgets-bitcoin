<?php

require_once plugin_dir_path(__FILE__) . 'bananawb-widget-prices.php'; // Quotes Widget

/*
 * Add new menu into admin
 */
 
// Hook action 'admin_menu', run 'bananawb_Add_admin_link()'
// add_action( 'admin_menu', 'bananawb_Add_admin_link' );
 
// Add new menu item into admin
function bananawb_Add_admin_link()
{
 add_menu_page(
 'Bitcoin Widgets',
 'Bitcoin Widgets',
 'manage_options',
 'widget-bitcoin/includes/bananawb-acp-page.php'
 );
}
