<?php

/**
 * Plugin Name: AffiliateWP - Woocommerce MyAccount Integration
 * Plugin URI: 
 * Description: Adds a custom affiliate tab and AffiliateWP content to the Woocommerce My Account area
 * Version: 1.0
 * Author: 'Your Name here'
 * Author URI: 
 */

// Protect against direct plugin access
if ( !defined( 'ABSPATH' ) ) exit;

// Add the rewrite endpoint so we don't run into a 404 error on the new My Account tab
add_action( 'init', 'my_account_new_endpoints' );

function my_account_new_endpoints() {
    add_rewrite_endpoint( 'affiliate', EP_ROOT | EP_PAGES );
}

// If Affiliate WP is enabled and the current user is an affiliate add the new affiliate area tab/link to the "My Account" menu 
function affilatewp_woocommerce_my_account_menu( $items ) {
    if ( function_exists( 'affwp_is_affiliate' ) && affwp_is_affiliate() ) {
        $items['affiliate'] = 'Affiliate Area';
    }
    return $items;
}
add_filter( 'woocommerce_account_menu_items', 'affilatewp_woocommerce_my_account_menu' );

// Render the Affiliate WP Content within the new tab using the appropraite Affiliate WP shortcodes if the class is available
function affilatewp_woocommerce_tab_content() {
    if ( ! class_exists( 'Affiliate_WP_Shortcodes' ) ) {
        return;
    }
    $shortcode = new Affiliate_WP_Shortcodes;
    echo $shortcode->affiliate_area( $atts, $content = null );
}
add_action( 'woocommerce_account_affiliate_endpoint', 'affilatewp_woocommerce_tab_content' );

// Ensure the Affiliate WP tab works correctly
function affilatewp_woocommerce_filter_affiliate_tabs( $url, $page_id, $tab ) {
    return esc_url_raw( add_query_arg( 'tab', $tab ) );
}
add_filter( 'affwp_affiliate_area_page_url', 'affilatewp_woocommerce_filter_affiliate_tabs', 10, 3 );
