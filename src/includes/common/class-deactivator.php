<?php
namespace LokusWP\Commerce;

if (!defined('WPTEST')) {
    defined('ABSPATH') or die("Direct access to files is prohibited");
}

use LSD\Migration\DB_Carts;

class Deactivator
{
    public static function deactivate()
    {
        // require LWPBB_PATH . 'src/includes/core/modules/database/class-db-carts.php';
        // $db_carts = new DB_Carts;
        // $db_carts->drop_table();
    }
}