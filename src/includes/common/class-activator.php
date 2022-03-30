<?php

namespace LokusWP\Commerce;

<<<<<<< HEAD
if ( ! defined( 'WPTEST' ) ) {
	defined( 'ABSPATH' ) or die( "Direct access to files is prohibited" );
=======
use LSD\Migration\DB_LWCommerce_Order_Meta;

if (!defined('WPTEST')) {
    defined('ABSPATH') or die("Direct access to files is prohibited");
>>>>>>> f68f48b3ac9e1297b08cbd13494260d899fdf9da
}

class Activator {
	public static function activate() {

<<<<<<< HEAD
=======
		// Create Custom Table LWCommerce Order Meta
		require LWC_PATH . 'src/includes/modules/database/class-db-orders.php';
        $db_reports_meta = new DB_LWCommerce_Order_Meta();
        $db_reports_meta->create_table();


>>>>>>> f68f48b3ac9e1297b08cbd13494260d899fdf9da
	}
}