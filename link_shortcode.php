<?php
function rt_link_front_scripts()
{
    if (!rt_link_is_endpoint()) {
        return;
    }
    wp_enqueue_style('rt-link-google-fonts', '//fonts.googleapis.com/css?family=Reem+Kufi|Roboto:300');
    wp_enqueue_style('rt-link-fontawesome-css', 'https://use.fontawesome.com/releases/v5.12.0/css/all.css');
    wp_enqueue_style('rt-link-bio-reset-css', plugin_dir_url(__FILE__) . 'template/css/reset.css');
    wp_enqueue_style('rt-link-bio-styles-css', plugin_dir_url(__FILE__) . 'template/css/styles.css');
    wp_enqueue_style('rt-link-color-css', plugin_dir_url(__FILE__) . 'template/css/themes/' . get_option('rt_multi_link_setting_color') . '.css');
}

add_action('wp_enqueue_scripts', 'rt_link_front_scripts');

add_filter('template_include', 'rt_link_bio_template');

function rt_link_bio_template($template)
{
    if (!rt_link_is_endpoint()) {
        return $template;
    }
    $page_link_id = get_option('rt_multi_link_setting_page');

    if (is_page($page_link_id)) {
        $template = plugin_dir_path(__FILE__) . 'template/link-bio-template.php';
    }
    return $template;
}

function rt_link_remove_all_styles()
{
    global $wp_styles;
    $wp_styles->queue = ['rt-link-google-fonts', 'rt-link-fontawesome-css', 'rt-link-bio-reset-css', 'rt-link-bio-styles-css', 'rt-link-color-css'];
}

function rt_link_is_endpoint()
{
    global $wp_query;
    $slug = get_option('rt_multi_link_setting_page');
    $link_endpoint = get_post_field('post_name', $slug);
    if (isset($wp_query->query_vars['name'])) {
        return $link_endpoint === $wp_query->query_vars['name'];
    }
    return false;
}

function rt_link_remove_head()
{
    if (true === rt_link_is_endpoint()) {
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wlwmanifest_link');
        remove_action('wp_head', 'wp_shortlink_wp_head');
        remove_action('wp_head', 'rest_output_link_wp_head', 10);
        remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);
        remove_action('template_redirect', 'rest_output_link_header', 11, 0);
        add_filter('the_generator', function () {
            return '';
        });
        add_filter('show_admin_bar', '__return_false');
        add_action('wp_print_styles', 'rt_link_remove_all_styles', 100);
    }
}

add_action('wp', 'rt_link_remove_head');


