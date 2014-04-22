<?php

/**
 * Plugin Name: InkMember Trial
 * Plugin URI: http://www.inkthemes.com
 * Description: InkMemeber plugin provides you the option to protect your content and sell them to the audience once you get paid. You can use shortcodes in the plugin to protect your content, you can create multiple membership levels, you can set different payment options, etc. You will be able to receive the payment via PayPal directly from your website. This plugin is fully compatible with any of the WordPress Themes. .
 * Author: InkThemes
 * Author URI: http://www.inkthemes.com
 * Version: 1.0
 */
define('IM_VERSION', '1.0');
define('IM_SLUG', 'inkmember');
define('IM_MEMBER_ID', 'membership');
define('IM_PLUGIN_PATH', WP_PLUGIN_URL . '/' . str_replace(basename(__FILE__), "", plugin_basename(__FILE__)));

include_once dirname(__FILE__) . '/inc/im-db.php';
include_once dirname(__FILE__) . '/inc/im-core.php';
include_once dirname(__FILE__) . '/inc/user-auth.php';
include_once dirname(__FILE__) . '/inc/metabox.php';
include_once dirname(__FILE__) . '/inc/shortcode/tinyMCE.php';
include_once dirname(__FILE__) . '/inc/shortcode/shortcode.php';
include_once dirname(__FILE__) . '/inc/gateway/paypal/paypal.php';
include_once dirname(__FILE__) . '/inc/gateway/paypal/process.php';
include_once dirname(__FILE__) . '/inc/gateway/paypal/paypal-ipn.php';

function im_admin_style() {
    wp_register_style('im-admin-style', plugins_url('/css/im-admin-style.css', __FILE__));
    wp_enqueue_style('im-admin-style');
}

add_action('admin_print_styles', 'im_admin_style');

function im_front_style() {
    wp_enqueue_style('im-front-style', plugins_url('/css/front-style.css', __FILE__), '', '', 'all');
    wp_enqueue_script('jquery');
}

add_action('wp_enqueue_scripts', 'im_front_style');

function im_admin_script() {
    wp_register_script('im-admin-script', plugins_url('/js/im-admin.js', __FILE__));
    wp_register_script('im-edit-script', plugins_url('/js/im-edit.js', __FILE__));
    wp_enqueue_script('im-tooltip', plugins_url('/js/jquery.tipsy.js', __FILE__));
    wp_enqueue_script('im-admin-script');
    wp_enqueue_script('im-edit-script');
}

add_action('admin_print_scripts', 'im_admin_script');

function im_front_scripts() {
    if (!is_admin()) {
        wp_enqueue_script('im-litebox', plugins_url('/js/jquery.lightbox_me.js', __FILE__));
        wp_enqueue_script('im-scripts', plugins_url('/js/script.js', __FILE__));
    }
}
add_action('wp_enqueue_scripts', 'im_front_scripts');

define('IM_PRICING_PAGE', get_option('im_pricing_page'));
define('IM_LOGING_PAGE', get_option('im_order_sign_page'));


