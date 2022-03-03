<?php

namespace LokusWP\Commerce;

use LSD\Migration\DB_LWPCommerce_Order_Meta;

if (!defined('WPTEST')) {
    defined('ABSPATH') or die("Direct access to files is prohibited");
}

class Activator {
	public static function activate() {
		// Call The File
		 require LWC_PATH . 'src/includes/modules/database/class-db-orders.php';

		// // Create Table lwpcommers_reports
		// $db_options = new DB_Reports;
		// $db_options->create_table();

        $db_reports_meta = new DB_LWPCommerce_Order_Meta();
        $db_reports_meta->create_table();
	}
}