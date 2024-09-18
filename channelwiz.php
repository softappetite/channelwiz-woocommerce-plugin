<?php
/**
 * Plugin Name:       ChannelWiz Multichannel Connect
 * Plugin URI:        https://channelwiz.ndomitabl.com/channelwiz-multichannel-connect
 * Description:       A complete integration for Shopify,Amazon, eBay,Tiktokshop, Etsy & Walmart marketplaces
 * Version:           1.0.0
 * Requires at least: 6.1
 * Requires PHP:      8.1
 * Author:            Channel Wiz
 * Author URI:        https://ndomitabl.com
 * License:           GPLv2
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.html
 * WC requires at least: 8.0.0
 * WC tested up to:      8.0.2
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function channelwiz_menu() {
    add_menu_page(
        'Channel Wiz', 
        'Channel Wiz', 
        'manage_options', 
        'channel-wiz-multichannel-connect', 
        'custom_menu_page_content', 
        '', 
        56
    );
}
add_action('admin_menu', 'channelwiz_menu');