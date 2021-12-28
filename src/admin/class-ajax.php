<?php

namespace LokusWP\Commerce\Admin;

class AJAX
{
    public function __construct()
    {
        add_action('wp_ajax_lwpc_store_settings_save', [$this, 'store_settings_save']);
        add_action('wp_ajax_lwpc_shipping_package_status', [$this, 'shipping_package_status']);
    }

    public function store_settings_save()
    {
        if ( ! check_ajax_referer('lwpc_admin_nonce', 'security')) {
            wp_send_json_error('Invalid security token sent.');
        }

        // stripslash data
        $_REQUEST = array_map('stripslashes_deep', $_REQUEST);
        $data     = $_REQUEST['settings'];

        // Parser to Array
        $stack = array();
        parse_str(html_entity_decode($data), $stack);

        // Sanitizing
        $allowed_html = wp_kses_allowed_html('post');
        $sanitize     = array();
        foreach ($stack as $key => $item) {

            if ($key == 'store_address') {
                // Sanitize Textarea
                $item = wp_kses($item, $allowed_html);
            } else {
                // Sanitize Textfield
                $item = sanitize_text_field($item);
            }
            $sanitize[$key] = $item; //restructure
        }

        // Merge Exist Settings
        $settings = get_option('lwpcommerce_store');
        if (empty($settings)) {
            $merge = $sanitize;
        } else {
            $merge = array_merge($settings, $sanitize);
        }

        // Update New Settings
        update_option('lwpcommerce_store', $merge);
        echo 'action_success';

        wp_die();
    }

    public function shipping_package_status()
    {
        if ( ! check_ajax_referer('lwpc_admin_nonce', 'security')) {
            wp_send_json_error('Invalid security token sent.');
        }

        $package_id = $_REQUEST['package_id'];
        $package    = $_REQUEST['status'];

        $shipping_data = (object) lwp_get_option($package_id);

        foreach ($shipping_data->package as $key => $value) {
            if ($key === $package && $value === 'on') {
                $shipping_data->package[$key] = 'off';
            } elseif ($key === $package && $value === 'off') {
                $shipping_data->package[$key] = 'on';
            }
        }

        $update_option = lwp_update_option($package_id, $shipping_data);

        if ($update_option) {
            wp_send_json_success('action_success');
        } else {
            wp_send_json_error('action_failed');
        }
    }
}

new AJAX();