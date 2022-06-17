<?php

namespace LokusWP\Commerce;

class Order {
	public function __construct() {
	}

	public static function set_status( $trx_id, $status = "completed", $notification = true ) {
		$status = sanitize_key( $status );

		$status_list               = [];
		$status_list['pending']    = 'pending';
		$status_list['processing'] = 'processing';
		$status_list['cancelled']  = 'cancelled';
		$status_list['shipped']    = 'shipped';
		$status_list['completed']  = 'completed';

		if ( isset( $status_list[ $status ] ) ) {
			$status = $status_list[ $status ];

			// Update Order Status
			lwc_update_order_meta( $trx_id, "_order_status", $status );

			// Set Notification
			if ( $notification ) {
				\as_schedule_single_action( strtotime( '+3 seconds' ), 'lokuswp_notification', array( $trx_id . '-' . $status ), "lwcommerce" );
			}

			// Another Action for Order Status
			switch ( $status ) {
				case 'pending':
					break;
				case 'processing':
					break;
				case 'completed':
					self::set_completed( $trx_id );
					break;
			}
		}
	}

	public static function set_completed( $trx_id ) {

		// Update Paid_at Column
		lwp_transaction_update_column( $trx_id, "paid_at", lwp_current_date() );

	}

}

