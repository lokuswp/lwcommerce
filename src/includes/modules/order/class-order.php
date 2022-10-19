<?php

namespace LokusWP\Commerce;

class Order {
	public function __construct() {
	}

	public static function set_status( $trx_id, $status = "completed", $notification = true ) {
		$status = sanitize_key( $status );

		$listed               = [];
		$listed['pending']    = 'pending';
		$listed['paid']       = 'paid';
		$listed['processing'] = 'processing';
		$listed['pickup']     = 'pickup';
		$listed['cancelled']  = 'cancelled';
		$listed['shipped']    = 'shipped';
		$listed['completed']  = 'completed';

		if ( isset( $listed[ $status ] ) ) {
			$status = $listed[ $status ];

			// Update Order Status
			lwc_update_order_meta( $trx_id, "_order_status", $status );

			// Set Notification
			if ( $notification ) {
				\as_schedule_single_action( strtotime( '+1 seconds' ), 'lokuswp_notification', array( $trx_id . '-' . $status ), "lwcommerce" );
			}

			// Hook For Triggering Action when Order Change Status
			error_log( "Order Action :: " . $trx_id );
			error_log( "Order Status :: " . $status );
			do_action( "lwcommerce/order/action/{$status}", $trx_id );

			// Another Action for Order Status
			switch ( $status ) {
				case 'pending':
					break;

				case 'completed':
					self::set_paid( $trx_id );
					break;

				case 'paid':
					self::set_paid( $trx_id );
					break;
			}
		}
	}

	public static function set_paid( $trx_id ) {

		// Update Paid_at Column
		lwp_update_transaction_meta( $trx_id, "paid_at", lwp_current_date() );
		lwp_update_transaction_meta( $trx_id, "status", 'paid' );

	}

}

