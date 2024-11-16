<?php

/**
 *
 * @link              https://renzotejada.com/
 * @package           Multi Link in Bio
 *
 * @wordpress-plugin
 * Plugin Name:       Multi Link in Bio
 * Plugin URI:        https://renzotejada.com/multi-link-in-bio-para-wordpress/
 * Description:       links can be added that redirect the user to a company's external communication channels, which can be other social networks or websites.
 * Version:           0.1.4
 * Author:            Renzo Tejada
 * Author URI:        https://renzotejada.com/
 * License:           GNU General Public License v3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       link-bio
 * Domain Path:       /language
 * WC tested up to:   9.4.1
 * WC requires at least: 2.6
 */
if (!defined('ABSPATH')) {
    exit;
}

$plugin_link_bio_version = get_file_data(__FILE__, array('Version' => 'Version'), false);

define('Version_RT_Link_Bio', $plugin_link_bio_version['Version']);

add_action( 'before_woocommerce_init', function() {
    if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
    }
} );

function rt_link_bio_load_textdomain()
{
    load_plugin_textdomain('link-bio', false, basename(dirname(__FILE__)) . '/language/');
}

add_action('init', 'rt_link_bio_load_textdomain');


function rt_link_add_plugin_page_settings_link($links)
{
    $links2[] = '<a href="' . admin_url('admin.php?page=link_settings') . '">' . __('Settings', 'link-bio') . '</a>';
    $links = array_merge($links2, $links);
    return $links;
}

add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'rt_link_add_plugin_page_settings_link');

function rt_links_plugin_row_meta( $links, $file )
{
    if ( 'multi-link-in-bio/multi-link-in-bio.php' !== $file ) {
        return $links;
    }

    $row_meta = array(
        'contact' => '<a target="_blank" href="' . esc_url( 'https://renzotejada.com/contacto/' ) . '">' . esc_html__( 'Contact', 'link-bio' ) . '</a>',
        'support' => '<a target="_blank" href="' . esc_url( 'https://wordpress.org/support/plugin/multi-link-in-bio/' ) . '">' . esc_html__( 'Support', 'link-bio' ) . '</a>',
    );

    return array_merge( $links, $row_meta );
}

// Agrega link plugins
add_filter( 'plugin_row_meta', 'rt_links_plugin_row_meta', 10, 2 );

/*
 * ADMIN
 */
require dirname(__FILE__) . "/link_admin.php";

/*
 * SHORTCODE
 */
require dirname(__FILE__) . "/link_shortcode.php";

