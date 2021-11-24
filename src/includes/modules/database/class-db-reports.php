<?php

namespace LWPC\Migration;

require LWPC_PATH.'src/includes/libraries/php/db-migrate/dbcore/bootstrap.php';

class DB_Rpoerts extends Migration
{
    protected static $table_name;

    public function __construct()
    {
        static::$table_name    = 'lokuswp_transactions';
        $this->table_meta_name = static::wpdb()->prefix.'lokuswp_transaction_meta';
        $this->version         = '0.1';
        $this->create_table();
        $this->create_table_meta();
    }

    public function create_table()
    {
        require_once ABSPATH.'wp-admin/includes/upgrade.php';

        $sql = "CREATE TABLE ".static::get_table_name()." (
            cart_id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            user_id bigint(20) UNSIGNED NOT NULL,
            post_id bigint(20) UNSIGNED NOT NULL,
            quantity bigint(20) UNSIGNED NOT NULL DEFAULT '1',
            status varchar(30) NOT NULL,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (cart_id)
        ) CHARACTER SET utf8 COLLATE utf8_general_ci;";

        dbDelta($sql);
        update_option(static::get_table_name().'_db_version', $this->version);
    }

    public function create_table_meta()
    {
        require_once ABSPATH.'wp-admin/includes/upgrade.php';

        $sql = "CREATE TABLE ".$this->table_meta_name." (
            cart_id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            user_id bigint(20) UNSIGNED NOT NULL,
            post_id bigint(20) UNSIGNED NOT NULL,
            quantity bigint(20) UNSIGNED NOT NULL DEFAULT '1',
            status varchar(30) NOT NULL,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (cart_id)
        ) CHARACTER SET utf8 COLLATE utf8_general_ci;";

        dbDelta($sql);
        update_option($this->table_meta_name.'_db_version', $this->version);
    }
}
