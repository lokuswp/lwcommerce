<?php

namespace LokusWP\Commerce;

use LSD\Migration\DB_LWCommerce_Order_Meta;

if (!defined('WPTEST')) {
    defined('ABSPATH') or die("Direct access to files is prohibited");
}

class Activator {
	public static function activate() {

		// Create Custom Table LWCommerce Order Meta
		require LWC_PATH . 'src/includes/modules/database/class-db-orders.php';
        $db_reports_meta = new DB_LWCommerce_Order_Meta();
        $db_reports_meta->create_table();


	}
}