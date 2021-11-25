<?php

namespace LokaWP\Commerce;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use LSD\Migration\DB_Reports;

class Activator {
	public static function activate() {
		// Call The File
		require LWPC_PATH . 'src/includes/modules/database/class-db-reports.php';

		// Create Table lwpcommers_reports
		$db_options = new DB_Reports;
		$db_options->create_table();
	}
}
