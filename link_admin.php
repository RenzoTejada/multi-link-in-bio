<?php
if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

/************************* ADMIN PAGE **********************************
 ***********************************************************************/

add_action('admin_menu', 'rt_link_register_admin_page');

function rt_link_register_admin_page()
{
    add_menu_page('Multi Link in Bio', __('Multi Link in Bio', 'link-bio'), 'administrator', 'page_link', '', 'dashicons-admin-links', 20);
    add_submenu_page('page_link', __('Settings', 'link-bio'), __('Link in Bio', 'link-bio'), 'manage_options', 'link_settings', 'rt_link_submenu_settings_callback');
    remove_submenu_page('page_link', 'page_link');
}

function rt_link_submenu_settings_callback()
{
    if (current_user_can('manage_options')) {
        ?>
        <div class="wrap woocommerce">
            <div style="background-color:#87b43e;">
            </div>
            <h1><?php _e('Multi Link in Bio', 'link-bio') ?></h1>
            <hr>

            <h2 class="nav-tab-wrapper">
                <a href="?page=link_settings&tab=setting" class="nav-tab <?php
                if ((!isset($_REQUEST['tab'])) || ($_REQUEST['tab'] == "setting")) {
                    print " nav-tab-active";
                } ?>"><?php _e('Settings', 'link-bio') ?></a>
                <a href="?page=link_settings&tab=links" class="nav-tab <?php
                if (($_REQUEST['tab'] == "links")) {
                    print " nav-tab-active";
                } ?>"><?php _e('Links', 'link-bio') ?></a>
                <a href="?page=link_settings&tab=tracking" class="nav-tab <?php
                if (($_REQUEST['tab'] == "tracking")) {
                    print " nav-tab-active";
                } ?>"><?php _e('Tracking', 'link-bio') ?></a>
            </h2>
            <?php
            switch ($_REQUEST['tab']) {
                case 'links':
                    rt_link_submenu_settings_links();
                    break;
                case 'tracking':
                    rt_link_submenu_settings_tracking();
                    break;
                case 'setting':
                default:
                    rt_link_submenu_settings_general();
                    break;
            }
            ?>
        </div>
        <?php
    }
}

function rt_link_submenu_settings_general()
{
    if (isset($_POST["btn_guardar_setting"]) && check_admin_referer('nonce_guardar_setting', 'field_nonce_guardar_setting')) {
        $page_link_id = get_option('link_setting_page');
        $page_link_title = get_option('link_setting_title');
        $page_link_subtitle = get_option('link_setting_subtitle');
        $page_link_color = get_option('link_setting_color');
        if ($page_link_id) {
            update_option('link_setting_page', sanitize_text_field($_POST["page_link"]));
        } else {
            add_option('link_setting_page', sanitize_text_field($_POST["page_link"]));
            $page_link_id = get_option('link_setting_page');
            if (!$page_link_id) {
                update_option('link_setting_page', sanitize_text_field($_POST["page_link"]));
            }
        }
        if ($page_link_title) {
            update_option('link_setting_title', sanitize_text_field($_POST["title_link"]));
        } else {
            add_option('link_setting_title', sanitize_text_field($_POST["title_link"]));
            $page_link_title = get_option('link_setting_title');
            if (!$page_link_title) {
                update_option('link_setting_title', sanitize_text_field($_POST["title_link"]));
            }
        }
        if ($page_link_subtitle) {
            update_option('link_setting_subtitle', sanitize_text_field($_POST["subtitle_link"]));
        } else {
            add_option('link_setting_subtitle', sanitize_text_field($_POST["subtitle_link"]));
            $page_link_subtitle = get_option('link_setting_subtitle');
            if (!$page_link_subtitle) {
                update_option('link_setting_subtitle', sanitize_text_field($_POST["subtitle_link"]));
            }
        }
        if ($page_link_color) {
            update_option('link_setting_color', sanitize_text_field($_POST["color_link"]));
        } else {
            add_option('link_setting_color', sanitize_text_field($_POST["color_link"]));
            $page_link_color = get_option('link_setting_color');
            if (!$page_link_color) {
                update_option('link_setting_color', sanitize_text_field($_POST["color_link"]));
            }
        }
    }

    $page_link_id = get_option('link_setting_page');
    $page_link_title = get_option('link_setting_title');
    $page_link_subtitle = get_option('link_setting_subtitle');
    $page_link_color = get_option('link_setting_color');
    ?>
    <h2><?php _e('General setting', 'link-bio') ?></h2>
    <form method="post" id="form_ajuste_link" action="" novalidate="novalidate">
        <table class="form-table">
            <tbody>
            <tr>
                <th scope="row"><label for="start_of_week"><?php _e('Choose page', 'link-bio') ?></label></th>
                <td>
                    <?php
                    $args = array(
                        'sort_order' => 'asc',
                        'sort_column' => 'post_title',
                        'hierarchical' => 1,
                        'exclude' => '',
                        'include' => '',
                        'meta_key' => '',
                        'meta_value' => '',
                        'authors' => '',
                        'child_of' => 0,
                        'parent' => -1,
                        'exclude_tree' => '',
                        'number' => '',
                        'offset' => 0,
                        'post_type' => 'page',
                        'post_status' => 'publish'
                    );
                    $all_page = get_pages($args); ?>
                    <select name="page_link" id="page_link">
                        <option value=""><?php _e('Select page', 'link-bio') ?></option>
                        <?php foreach ($all_page as $page) {
                            $selected = ($page->ID == $page_link_id) ? 'selected' : ''; ?>
                            <option value="<?php echo esc_html($page->ID); ?>" <?php echo esc_html($selected); ?>><?php echo esc_html($page->post_title); ?></option>
                            <?php
                        } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label><?php _e('Title', 'link-bio') ?></label></th>
                <td>
                    <input name="title_link" type="text" id="title_link"
                           value="<?php echo esc_html($page_link_title); ?>"
                           class="regular-text ltr">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label><?php _e('Sub Title', 'link-bio') ?></label></th>
                <td>
                    <input name="subtitle_link" type="text" id="subtitle_link"
                           value="<?php echo esc_html($page_link_subtitle); ?>"
                           class="regular-text ltr">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label><?php _e('Color Template', 'link-bio') ?></label></th>
                <td>
                    <?php
                    $colors = array(
                        'indigo-white' => 'Indigo White',
                        'green-white' => 'Green White',
                        'red-white' => 'Red White',
                        'grey-white' => 'Grey White',
                        'white-indigo' => 'White Indigo',
                        'white-blue' => 'White Blue',
                        'white-grey' => 'White Grey',
                        'white-red' => 'White Red',
                        'yellow-black' => 'Yellow Black',
                    );
                    ?>
                    <select name="color_link" id="color_link">
                        <option value=""><?php _e('Select Color', 'link-bio') ?></option>
                        <?php foreach ($colors as $key => $color) {
                            $selected = ($key == $page_link_color) ? 'selected' : ''; ?>
                            <option value="<?php echo esc_html($key); ?>" <?php echo esc_html($selected); ?>><?php echo esc_html($color); ?></option>
                            <?php
                        } ?>
                    </select>
                </td>
            </tr>
            </tbody>
        </table>
        <?php wp_nonce_field('nonce_guardar_setting', 'field_nonce_guardar_setting'); ?>
        <p class="submit">
            <?php
            $attributes = array('id' => 'btn_guardar_setting');
            submit_button(__('Save changes', 'link-bio'), 'button button-primary', 'btn_guardar_setting', true, $attributes); ?>
        </p>
    </form>
    <?php
}

function rt_link_submenu_settings_links()
{
    if (isset($_POST["btn_guardar_links"]) && check_admin_referer('nonce_guardar_links', 'field_nonce_guardar_links')) {

        $links_rs = array(
            'fb', 'ig', 'twitter', 'dev', 'github', 'stackoverflow', 'linkedin', 'medium', 'behance', 'codepen', 'wp',
            'web', 'wa', 'email'
        );

        foreach ($links_rs as $link) {
            if (get_option('link_' . $link)) {
                update_option("link_" . $link, sanitize_text_field($_POST["link_" . $link]));
            } else {
                add_option("link_" . $link, sanitize_text_field($_POST["link_" . $link]));
                if (!get_option('link_' . $link)) {
                    update_option("link_" . $link, sanitize_text_field($_POST["link_" . $link]));
                }
            }
        }
    }
    ?>
    <h2><?php _e('Links Sociales', 'link-bio') ?></h2>
    <form method="post" id="form_links" action="" novalidate="novalidate">
        <table class="form-table">
            <tbody>
            <tr>
                <th scope="row">
                    <label><?php _e('Facebook', 'link-bio') ?></label>
                </th>
                <td>
                    <input name="link_fb" type="text" id="link_fb" value="<?php echo esc_html(get_option('link_fb')) ?>"
                           class="regular-text ltr">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label><?php _e('Instagram', 'link-bio') ?></label>
                </th>
                <td>
                    <input name="link_ig" type="text" id="link_ig" value="<?php echo esc_html(get_option('link_ig')) ?>"
                           class="regular-text ltr">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label><?php _e('Twitter', 'link-bio') ?></label>
                </th>
                <td>
                    <input name="link_twitter" type="text" id="link_twitter"
                           value="<?php echo esc_html(get_option('link_twitter')) ?>"
                           class="regular-text ltr">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label><?php _e('DEV', 'link-bio') ?></label>
                </th>
                <td>
                    <input name="link_dev" type="text" id="link_dev"
                           value="<?php echo esc_html(get_option('link_dev')) ?>"
                           class="regular-text ltr">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label><?php _e('GitHub', 'link-bio') ?></label>
                </th>
                <td>
                    <input name="link_github" type="text" id="link_github"
                           value="<?php echo esc_html(get_option('link_github')) ?>"
                           class="regular-text ltr">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label><?php _e('StackOverflow', 'link-bio') ?></label>
                </th>
                <td>
                    <input name="link_stackoverflow" type="text" id="link_stackoverflow"
                           value="<?php echo esc_html(get_option('link_stackoverflow')) ?>"
                           class="regular-text ltr">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label><?php _e('LinkedIn', 'link-bio') ?></label>
                </th>
                <td>
                    <input name="link_linkedin" type="text" id="link_linkedin"
                           value="<?php echo esc_html(get_option('link_linkedin')) ?>"
                           class="regular-text ltr">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label><?php _e('Medium', 'link-bio') ?></label>
                </th>
                <td>
                    <input name="fb_link" type="text" id="link_medium"
                           value="<?php echo esc_html(get_option('link_medium')) ?>"
                           class="regular-text ltr">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label><?php _e('Behance', 'link-bio') ?></label>
                </th>
                <td>
                    <input name="link_behance" type="text" id="link_behance"
                           value="<?php echo esc_html(get_option('link_behance')) ?>"
                           class="regular-text ltr">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label><?php _e('CodePen', 'link-bio') ?></label>
                </th>
                <td>
                    <input name="link_codepen" type="text" id="link_codepen"
                           value="<?php echo esc_html(get_option('link_codepen')) ?>"
                           class="regular-text ltr">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label><?php _e('WordPress', 'link-bio') ?></label>
                </th>
                <td>
                    <input name="link_wp" type="text" id="link_wp" value="<?php echo esc_html(get_option('link_wp')) ?>"
                           class="regular-text ltr">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label><?php _e('Web', 'link-bio') ?></label>
                </th>
                <td>
                    <input name="link_web" type="text" id="link_web"
                           value="<?php echo esc_html(get_option('link_web')) ?>"
                           class="regular-text ltr">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label><?php _e('WhatsApp', 'link-bio') ?></label>
                </th>
                <td>
                    <input name="link_wa" type="text" id="link_wa" value="<?php echo esc_html(get_option('link_wa')) ?>"
                           class="regular-text ltr">
                    <p class="description"
                       id="tagline-description"><?php _e('How to create WhatsApp link', 'link-bio') ?> <a
                                href="https://faq.whatsapp.com/en/android/26000030/"
                                target="_blank"><?php _e('here', 'link-bio') ?></a></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label><?php _e('Email', 'link-bio') ?></label>
                </th>
                <td>
                    <input name="link_email" type="text" id="link_email"
                           value="<?php echo esc_html(get_option('link_email')) ?>"
                           class="regular-text ltr">
                </td>
            </tr>
            </tbody>
        </table>
        <?php wp_nonce_field('nonce_guardar_links', 'field_nonce_guardar_links'); ?>
        <p class="submit">
            <?php
            $attributes = array('id' => 'btn_guardar_links');
            submit_button(__('Save changes', 'link-bio'), 'button button-primary', 'btn_guardar_links', true, $attributes); ?>
        </p>
    </form>
    <?php
}

function rt_link_submenu_settings_tracking()
{
    if (isset($_POST["btn_guardar_tracking"]) && check_admin_referer('nonce_guardar_tracking', 'field_nonce_guardar_tracking')) {
        $trackings = array(
            'ga', 'gtm', 'fbp',
        );

        foreach ($trackings as $code) {
            if (get_option('link_' . $code)) {
                update_option("link_" . $code, sanitize_text_field($_POST["link_" . $code]));
            } else {
                add_option("link_" . $code, sanitize_text_field($_POST["link_" . $code]));
                if (!get_option('link_' . $code)) {
                    update_option("link_" . $code, sanitize_text_field($_POST["link_" . $code]));
                }
            }
        }
    }
    ?>
    <h2><?php _e('Tracking', 'link-bio') ?></h2>
    <h4><?php _e('Google Analytics | Google Tag Manager | Facebook Pixel', 'link-bio') ?></h4>
    <form method="post" id="form_links" action="" novalidate="novalidate">
        <table class="form-table">
            <tbody>
            <tr>
                <th scope="row">
                    <label><?php _e('Google Analytics ID', 'link-bio') ?></label>
                </th>
                <td>
                    <input name="link_ga" type="text" id="link_ga" value="<?php echo esc_html(get_option('link_ga')) ?>"
                           class="regular-text ltr">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label><?php _e('Google Tag Manager', 'link-bio') ?></label>
                </th>
                <td>
                    <input name="link_gtm" type="text" id="link_gtm"
                           value="<?php echo esc_html(get_option('link_gtm')) ?>"
                           class="regular-text ltr">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label><?php _e('Facebook Pixel', 'link-bio') ?></label>
                </th>
                <td>
                    <input name="link_fbp" type="text" id="link_fbp"
                           value="<?php echo esc_html(get_option('link_fbp')) ?>"
                           class="regular-text ltr">
                </td>
            </tr>
            </tbody>
        </table>
        <?php wp_nonce_field('nonce_guardar_tracking', 'field_nonce_guardar_tracking'); ?>
        <p class="submit">
            <?php
            $attributes = array('id' => 'btn_guardar_tracking');
            submit_button(__('Save changes', 'link-bio'), 'button button-primary', 'btn_guardar_tracking', true, $attributes); ?>
        </p>
    </form>
    <?php
}