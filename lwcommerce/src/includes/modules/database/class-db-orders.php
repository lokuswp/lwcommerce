<?php

namespace LSD\Migration;

require LOKUSWP_PATH . 'src/includes/libraries/php/db-migrate/dbcore/bootstrap.php';

class DB_LWCommerce_Order_Meta extends Migration {
	protected static $table_name;

	public function __construct() {
		static::$table_name = 'lwcommerce_ordermeta';
		$this->version      = '0.1';
		$this->create_table();
	}

	public function create_table() {
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		$sql = "CREATE TABLE " . static::get_table_name() . " (
			meta_id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			lwcommerce_order_id bigint(20) UNSIGNED NOT NULL,
			meta_key varchar(255) NOT NULL,
			meta_value longtext NOT NULL,
			PRIMARY KEY (meta_id)
		) CHARACTER SET utf8 COLLATE utf8_general_ci;";

		dbDelta( $sql );
		update_option( static::get_table_name() . '_db_version', $this->version );
	}
}