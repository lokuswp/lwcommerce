<?php
namespace LokusWP\Commerce;

// Exit if accessed directly
if (!defined('WPTEST')) {
	defined('ABSPATH') or die("Direct access to files is prohibited");
}

class i18n
{
	public function boot()
	{
		load_plugin_textdomain('lwcommerce', false, LOKUSWP_PATH . '/languages/');
	}
}
add_action('plugins_loaded', [i18n, 'boot']);
