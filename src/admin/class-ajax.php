<?php

namespace LokusWP\Commerce\Admin;

use LokusWP\Commerce\Modules\Order\Datatable_Order;
use LokusWP\Commerce\Modules\Order\LWC_Order;
use LokusWP\Commerce\Order;


class AJAX {
	public function __construct() {
		add_action( 'wp_ajax_lwc_store_settings_save', [ $this, 'store_settings_save' ] );

		add_action( 'wp_ajax_lwc_shipping_package_status', [ $this, 'shipping_package_status' ] );
		add_action( 'wp_ajax_lwc_change_payment_status', [ $this, 'change_payment_status' ] );

		add_action( 'wp_ajax_lwc_shipping_settings_save', [ $this, 'shipping_settings_save' ] );

		// Shipping
		add_action( 'wp_ajax_lwc_admin_shipping_status', [ $this, 'admin_shipping_status' ] );

		// Orders
		add_action( 'wp_ajax_lwc_get_orders', [ $this, 'get_orders' ] );
		add_action( 'wp_ajax_lwc_process_order', [ $this, 'process_order' ] );
		add_action( 'wp_ajax_lwc_update_resi', [ $this, 'update_resi' ] );
		add_action( 'wp_ajax_lwc_order_action', [ $this, 'order_action' ] );
		add_action( 'wp_ajax_lwc_delete_order', [ $this, 'delete_order' ] );
		add_action( 'wp_ajax_lwc_export_order', [ $this, 'export_order' ] );

		// Follow-Up WhatsApp
		add_action( 'wp_ajax_lwc_follow_up_whatsapp', [ $this, 'follow_up_whatsapp' ] );

		// Statistic
		add_action( 'wp_ajax_lwc_orders_chart', [ $this, 'orders_chart' ] );
	}

	public function admin_shipping_status() {
		if ( ! check_ajax_referer( 'lwc_admin_nonce', 'security' ) ) {
			wp_send_json_error( 'Invalid security token sent.' );
		}

		// Toggle Payment Method
		$shipping_id = sanitize_key( $_REQUEST['id'] );
		$state       = sanitize_key( $_REQUEST['state'] );

		$shipping_status = lwp_get_option( 'shipping_manager' ) ?? array();
		if ( ! is_array( $shipping_status ) ) {
			$shipping_status = array();
		}

		$shipping_status[ $shipping_id ] = $state;
		lwp_update_option( 'shipping_manager', $shipping_status );
		echo 'action_success';

		wp_die();
	}


	public function store_settings_save() {
		if ( ! check_ajax_referer( 'lwc_admin_nonce', 'security' ) ) {
			wp_send_json_error( 'Invalid security token sent.' );
		}

		// stripslash data
		$_REQUEST = array_map( 'stripslashes_deep', $_REQUEST );
		$data     = $_REQUEST['settings'];

		// Parser to Array
		$stack = array();
		parse_str( html_entity_decode( $data ), $stack );

		// Sanitizing
		$allowed_html = wp_kses_allowed_html( 'post' );
		$sanitize     = array();
		foreach ( $stack as $key => $item ) {

			if ( $key == 'store_address' ) {
				// Sanitize Textarea
				$item = wp_kses( $item, $allowed_html );
			} else {
				// Sanitize Textfield
				$item = sanitize_text_field( $item );
			}
			$sanitize[ $key ] = $item; //restructure
		}

		// Merge Exist Settings
		$settings = lwp_get_option( 'lwcommerce_store' );
		if ( empty( $settings ) ) {
			$merge = $sanitize;
		} else {
			$merge = array_merge( $settings, $sanitize );
		}

		// Update New Settings
		lwp_update_option( 'lwcommerce_store', $merge );
		update_option( 'lwcommerce_was_installed', true );
		echo 'action_success';

		wp_die();
	}

	public function shipping_package_status() {
		if ( ! check_ajax_referer( 'lwc_admin_nonce', 'security' ) ) {
			wp_send_json_error( 'Invalid security token sent.' );
		}

		$package_id = $_REQUEST['package_id'];
		$package    = $_REQUEST['status'];

		$shipping_data = (object) lwp_get_option( $package_id );

		foreach ( $shipping_data->package as $key => $value ) {
			if ( $key === $package && $value === 'on' ) {
				$shipping_data->package[ $key ] = 'off';
			} elseif ( $key === $package && $value === 'off' ) {
				$shipping_data->package[ $key ] = 'on';
			}
		}

		$update_option = lwp_update_option( $package_id, $shipping_data );

		if ( $update_option ) {
			wp_send_json_success( 'action_success' );
		} else {
			wp_send_json_error( 'action_failed' );
		}
	}

	public function shipping_settings_save() {
		if ( ! check_ajax_referer( 'lwc_admin_nonce', 'security' ) ) {
			wp_send_json_error( 'Invalid security token sent.' );
		}

		// stripslash data
		$_REQUEST = array_map( 'stripslashes_deep', $_REQUEST );
		$data     = $_REQUEST['settings'];


		// Parser to Array
		$stack = array();
		parse_str( html_entity_decode( $data ), $stack );

		// Sanitizing
		$sanitize = array();
		foreach ( $stack as $key => $item ) {

			// Sanitize Textfield
			$item             = sanitize_text_field( $item );
			$sanitize[ $key ] = $item; //restructure
		}

		// Merge Exist Settings
		$settings = get_option( 'lwcommerce_shipping' );
		if ( empty( $settings ) ) {
			$merge = $sanitize;
		} else {
			$merge = array_merge( $settings, $sanitize );
		}

		// Update New Settings
		update_option( 'lwcommerce_shipping', $merge );

		echo 'action_success';

		wp_die();
	}

	public function update_resi() {
		if ( ! check_ajax_referer( 'lwc_admin_nonce', 'security' ) ) {
			wp_send_json_error( 'Invalid security token sent.' );
		}

		$transaction_id = $_POST['transaction_id'];
		$no_resi        = $_POST['resi'];

		if ( ! empty( $transaction_id ) ) {
			$transaction_id = sanitize_text_field( $transaction_id );
			$no_resi        = sanitize_text_field( $no_resi );

			lwc_update_order_meta( $transaction_id, 'no_resi', $no_resi );
			$status = lwc_update_order_meta( $transaction_id, 'status_processing', 'shipping' );

			if ( $status ) {
				wp_send_json_success( 'Successfully updated.' );
			} else {
				wp_send_json_error( 'Failed to update.' );
			}
		} else {
			wp_send_json_error( 'Invalid transaction id.' );
		}
	}

	public function get_orders() {
		if ( ! check_ajax_referer( 'lwc_admin_nonce', 'security' ) ) {
			wp_send_json_error( 'Invalid security token sent.' );
		}

		$order = LWC_Order::get_order( new Datatable_Order() );

		wp_send_json( $order );
	}

	public function process_order() {
		if ( ! check_ajax_referer( 'lwc_admin_nonce', 'security' ) ) {
			wp_send_json_error( 'Invalid security token sent.' );
		}

		$transaction_id = $_POST['transaction_id'];
		$status         = $_POST['status'];

		if ( ! empty( $transaction_id ) ) {
			$transaction_id = sanitize_text_field( $transaction_id );
			$status         = sanitize_text_field( $status );

			$transaction_id = lwc_update_order_meta( $transaction_id, 'status_processing', $status );

			if ( $transaction_id ) {
				wp_send_json_success( 'Successfully updated.' );
			} else {
				wp_send_json_error( 'Failed to update.' );
			}
		} else {
			wp_send_json_error( 'Invalid transaction id.' );
		}
	}

	public function orders_chart() {
		if ( ! check_ajax_referer( 'lwc_admin_nonce', 'security' ) ) {
			wp_send_json_error( 'Invalid security token sent.' );
		}

		$orders = $_POST['orders'];

		$data_date            = [];
		$data_total           = [];
		$data_phone           = [];
		$data_total_yesterday = [];
		$data_phone_yesterday = [];
		$year                 = date( 'Y' );
		$month                = date( 'm' );
		$day                  = date( 'd' );
		switch ( $orders ) {
			case 'alltime':
				$data = $this->get_all_orders();
				foreach ( $data as $item ) {
					$data_date[ lwp_date_format( $item->created_at, 'd M Y' ) ][] = $item;
					$data_total[]                                                 = $item->total;
					$data_phone[]                                                 = lwp_get_transaction_meta( $item->transaction_id, 'billing_phone' );
				}
				foreach ( $data_date as $k => $v ) {
					$data_date[ $k ] = count( $v ); // count data per date
				}
				break;
			case 'day':
				$day_yesterday  = date( 'd', strtotime( '-1 day' ) );
				$data           = $this->select_date( 'transaction_id, created_at, total', $year, $month, $day );
				$data_yesterday = $this->select_date( 'transaction_id, created_at, total', $year, $month, $day_yesterday );
				foreach ( $data as $item ) {
					$data_date[ lwp_date_format( $item->created_at, 'H:i' ) ][] = $item;
					$data_total[]                                               = $item->total;
					$data_phone[]                                               = lwp_get_transaction_meta( $item->transaction_id, 'billing_phone' );
				}
				foreach ( $data_date as $k => $v ) {
					$data_date[ $k ] = count( $v ); // count data per date
				}
				break;
			case 'week':
				$data           = $this->select_week( 'transaction_id, created_at, total', 'now' );
				$data_yesterday = $this->select_week( 'transaction_id, created_at, total', 'yesterday' );
				foreach ( $data as $item ) {
					$data_date[ lwp_date_format( $item->created_at, 'l' ) ][] = $item;
					$data_total[]                                             = $item->total;
					$data_phone[]                                             = lwp_get_transaction_meta( $item->transaction_id, 'billing_phone' );
				}
				foreach ( $data_date as $k => $v ) {
					$data_date[ $k ] = count( $v ); // count data per date
				}
				break;
			case 'month':
				$month_yesterday = date( 'm', strtotime( '-1 month' ) );
				$data            = $this->select_date( 'transaction_id, created_at, total', $year, $month );
				$data_yesterday  = $this->select_date( 'transaction_id, created_at, total', $year, $month_yesterday );
				foreach ( $data as $item ) {
					$data_date[ lwp_date_format( $item->created_at, 'd M' ) ][] = $item;
					$data_total[]                                               = $item->total;
					$data_phone[]                                               = lwp_get_transaction_meta( $item->transaction_id, 'billing_phone' );
				}
				foreach ( $data_date as $k => $v ) {
					$data_date[ $k ] = count( $v ); // count data per date
				}
				break;
			case 'year':
				$year_yesterday = date( 'Y', strtotime( '-1 year' ) );
				$data           = $this->select_date( 'transaction_id, created_at, total', $year );
				$data_yesterday = $this->select_date( 'transaction_id, created_at, total', $year_yesterday );
				foreach ( $data as $item ) {
					$data_date[ lwp_date_format( $item->created_at, 'd M Y' ) ][] = $item;
					$data_total[]                                                 = $item->total;
					$data_phone[]                                                 = lwp_get_transaction_meta( $item->transaction_id, 'billing_phone' );
				}
				foreach ( $data_date as $k => $v ) {
					$data_date[ $k ] = count( $v ); // count data per date
				}
				uksort( $data_date, function ( $a1, $a2 ) {
					$time1 = strtotime( $a1 );
					$time2 = strtotime( $a2 );

					return $time1 - $time2;
				} );
				break;
		}
		foreach ( $data_yesterday as $item ) {
			$data_total_yesterday[] = $item->total;
			$data_phone_yesterday[] = $item->phone;
		}

		return wp_send_json( [
			[ $data_date, $data_total, $data_phone ],
			[ $data_total_yesterday, $data_phone_yesterday ]
		] );
	}

	public function get_all_orders() {
		global $wpdb;
		$sql = "SELECT transaction_id, created_at, total FROM {$wpdb->prefix}lokuswp_transactions";

		return $wpdb->get_results( $sql );
	}

	public function select_date( $column, $year = null, $month = null, $day = null ) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'lokuswp_transactions';
		if ( $year && $month && $day ) {
			$query = $wpdb->get_results( "SELECT $column FROM $table_name WHERE Year(created_at) = $year AND Month(created_at) = $month AND Day(created_at) = $day" );
		} elseif ( $year && $month ) {
			$query = $wpdb->get_results( "SELECT $column FROM $table_name WHERE Year(created_at) = $year AND Month(created_at) = $month" );
		} elseif ( $year ) {
			$query = $wpdb->get_results( "SELECT $column FROM $table_name WHERE Year(created_at) = $year" );
		}

		return $query;
	}

	public function select_week( $column, $case = 'now' ) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'lokuswp_transactions';
		switch ( $case ) {
			case 'now':
				$query = $wpdb->get_results( "SELECT $column FROM $table_name WHERE yearweek(DATE(created_at), 1) = yearweek(curdate(),1)" );
				break;
			case 'yesterday':
				$query = $wpdb->get_results( "SELECT $column FROM $table_name WHERE yearweek(DATE(created_at), 1) = yearweek(curdate() - INTERVAL 1 WEEK)" );
				break;
		}

		return $query;
	}

	public function change_payment_status() {
		if ( ! check_ajax_referer( 'lwc_admin_nonce', 'security' ) ) {
			wp_send_json_error( 'Invalid security token sent.' );
		}

		$transaction_id = sanitize_text_field( $_POST['transaction_id'] );
		$status         = sanitize_text_field( $_POST['status'] );

		global $wpdb;
		$table_transaction = $wpdb->prefix . 'lokuswp_transactions';
		$sql               = "UPDATE $table_transaction SET status = '$status' WHERE transaction_id = $transaction_id";

		$result = $wpdb->query( $sql );

		if ( $status === 'Paid' && $result ) {
			if ( has_filter( 'lwcommerce/growthprice/pusher/manual' ) ) {
				apply_filters( 'lwcommerce/growthprice/pusher/manual', $transaction_id );
			}
		}

		return wp_send_json_success( 'success' );
	}

	public function order_action() {
		if ( ! check_ajax_referer( 'lwc_admin_nonce', 'security' ) ) {
			wp_send_json_error( 'Invalid security token sent.' );
		}

		$order_id      = sanitize_key( $_POST['order_id'] );
		$action        = sanitize_text_field( $_POST['action_type'] );
		$shipping_type = sanitize_text_field( $_POST['shipping_type'] );

		if ( $shipping_type !== 'digital' ) {
			if ( $action === 'pending' ) {
				if ( has_action( 'lokuswp/admin/order/action' ) ) {
					do_action( 'lokuswp/admin/order/action', $order_id );
				}
				Order::set_status( $order_id, 'processing' );
			}
			if ( $action === 'processing' ) {
				Order::set_status( $order_id, 'shipped' );
			}
			if ( $action === 'shipped' ) {
				Order::set_status( $order_id, 'completed' );
			}
			if ( $action === 'completed' ) {
//			Order::set_status( $order_id, 'completed' );
//			lwc_update_order_meta( $order_id, '_order_status', 'refunded' );
			}
		} else {
			if ( $action === 'pending' ) {
				if ( has_action( 'lokuswp/admin/order/action' ) ) {
					do_action( 'lokuswp/admin/order/action', $order_id );
				}
				Order::set_status( $order_id, 'completed' );
				lwc_update_order_meta( $order_id, '_order_status', 'completed' );
				lwp_transaction_update_column( $order_id, 'status', 'paid' );
			}
		}

		wp_send_json_success( 'success' );
	}

	public function follow_up_whatsapp() {
		if ( ! check_ajax_referer( 'lwc_admin_nonce', 'security' ) ) {
			wp_send_json_error( 'Invalid security token sent.' );
		}

		$data_order = $_POST['data_order'];
		$phone      = lwp_sanitize_phone( sanitize_text_field( $_POST['phone_number'] ), $data_order['country'] );

		$template = apply_filters( 'lwcommerce/order/followup/template', $data_order );
		wp_send_json( [
			'status'  => 'success',
			'message' => urlencode( $template ),
			'phone'   => $phone,
		] );
	}

	public function delete_order() {
		if ( ! check_ajax_referer( 'lwc_admin_nonce', 'security' ) ) {
			wp_send_json_error( 'Invalid security token sent.' );
		}

		$order = LWC_Order::delete_order( sanitize_key( $_POST['order_id'] ) );

		if ( $order ) {
			wp_send_json_success( 'success' );
		}

		wp_send_json_error( 'error' );
	}

	public function export_order() {
		if ( ! check_ajax_referer( 'lwc_admin_nonce', 'security' ) ) {
			wp_send_json_error( 'Invalid security token sent.' );
		}

		$combined_data = LWC_Order::get_data_for_export();

		// get header
		$header = array_keys( $combined_data[0] );

		$target_dir = wp_upload_dir()['basedir'] . '/lwcommerce.csv';

		$make_csv = LWC_Order::make_csv( $header, $target_dir, $combined_data );

		if ( is_wp_error( $make_csv ) ) {
			wp_send_json_error( $make_csv->get_error_message() );
		}

		wp_send_json_success( wp_upload_dir()['baseurl'] . '/lwcommerce.csv' );
	}
}

new AJAX();