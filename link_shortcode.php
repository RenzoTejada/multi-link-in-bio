<?php
add_filter('template_include', 'rt_link_bio_template');

// Page template filter callback
function rt_link_bio_template($template)
{
    $page_link_id = get_option('link_setting_page');

    if (is_page($page_link_id)) {
        $template = WP_PLUGIN_DIR . '/multi-link-in-bio/template/link-bio-template.php';
    }
    return $template;
}