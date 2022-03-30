<?php

namespace LokusWP\Commerce;

if ( ! defined( 'WPTEST' ) ) {
	defined( 'ABSPATH' ) or die( "Direct access to files is prohibited" );
}

<<<<<<< HEAD
class Deactivator {
	public static function deactivate() {
	}
=======
use LSD\Migration\DB_Carts;
use LSD\Migration\DB_LWCommerce_Order_Meta;

class Deactivator
{
    public static function deactivate()
    {
        require LWC_PATH . 'src/includes/modules/database/class-db-orders.php';

        $db_reports_meta = new DB_LWCommerce_Order_Meta();
        $db_reports_meta->drop_table();
    }
>>>>>>> f68f48b3ac9e1297b08cbd13494260d899fdf9da
}