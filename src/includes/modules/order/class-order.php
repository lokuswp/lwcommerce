<?php

namespace LokusWP\Commerce;

class Order {
	public function __construct() {
	}

	public static function set_status( $trx_id, $status = "completed", $notification = true ) {

		$statues               = [];
		$statues['pending']    = 'pending';
		$statues['processing'] = 'processing';
		$statues['cancelled']  = 'cancelled';
		$statues['shipping']   = 'shipping';
		$statues['completed']  = 'completed';

		$status = sanitize_key( $status );
		if ( isset( $statues[ $status ] ) ) {
			$status = $statues[ $status ];

			// Set Order Status
			lwc_update_order_meta( $trx_id, "_order_status", $status );

			// Set Notification
			if ( $notification ) {
				as_schedule_single_action( strtotime( '+3 seconds' ), 'lokuswp_notification', array( $trx_id . '-' . $status ), "lwcommerce" );
			}

		}

	}

}

