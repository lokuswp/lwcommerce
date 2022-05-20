<?php

namespace LokusWP\Commerce\Admin;

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
		$settings = get_option( 'lwcommerce_store' );
		if ( empty( $settings ) ) {
			$merge = $sanitize;
		} else {
			$merge = array_merge( $settings, $sanitize );
		}

		// Update New Settings
		update_option( 'lwcommerce_store', $merge );
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

		global $wpdb;

		// Table name
		$table_cart                  = $wpdb->prefix . "lokuswp_carts";
		$table_transaction           = $wpdb->prefix . "lokuswp_transactions";
		$table_transaction_meta      = $wpdb->prefix . "lokuswp_transactionmeta";
		$table_lwcommerce_order_meta = $wpdb->prefix . "lwcommerce_ordermeta";
		$table_post                  = $wpdb->prefix . "posts";

		// Request
		$request = $_GET;

		$date_filter  = $request['dateFilter'];
		$order_filter = $request['orderFilter'];

		// Columns
		$columns = array(
			0 => 'transaction_id',
			1 => 'name',
			2 => 'phone',
			3 => 'email',
			4 => 'order_status',
			5 => 'shipping_type',
			6 => 'shipping_status',
			7 => 'service',
			8 => 'status',
			9 => 'raw_total',
		);

		// Datatable Filters
		$column = $columns[ $request['order'][0]['column'] ];
		$offset = $request['start'];
		$length = $request['length'];
		$length = $length == '-1' ? '18446744073709551615' : $length;
		$order  = $request['order'][0]['dir'];

		// Query variable
		$sql_where = "";

		// Search all columns
		if ( ! empty( $request['search']['value'] ) ) {

			$sql_where .= "HAVING ";

			foreach ( $columns as $column ) {

				$sql_where .= $column . " LIKE '%" . sanitize_text_field( $request['search']['value'] ) . "%' OR ";
			}

			$sql_where = substr( $sql_where, 0, - 3 );
		}


		if ( $order_filter !== 'all' ) {
			$sql_where .= ( ! empty( $sql_where ) ) ? " AND " : "HAVING ";

			$arrStatusPayment = [ 'unpaid', 'paid', 'cancelled' ];
			$arrStatusOrder   = [ 'pending', 'processing', 'shipping', 'completed' ];

			if ( in_array( $order_filter, $arrStatusPayment ) && ! in_array( $order_filter, $arrStatusOrder ) ) {
				$sql_where .= "status = '" . sanitize_text_field( $order_filter ) . "'";
			} elseif ( in_array( $order_filter, $arrStatusOrder ) && ! in_array( $order_filter, $arrStatusPayment ) ) {
				$sql_where .= "status_processing = '" . sanitize_text_field( $order_filter ) . "'";
			}
		}

		// Date filter
		if ( $date_filter !== 'all' ) {
			$sql_where .= ( ! empty( $sql_where ) ) ? " AND " : "HAVING ";

			$range = explode( '/', $date_filter );

			if ( count( $range ) === 2 ) {
				$sql_where .= "DATE(created_at) BETWEEN '" . sanitize_text_field( $range[0] ) . "' AND '" . sanitize_text_field( $range[1] ) . "' ";
			}

			switch ( $date_filter ) {
				case 'today':
					$sql_where .= "DATE(created_at) = CURDATE() ";
					break;
				case 'yesterday':
					$sql_where .= "DATE(created_at) = SUBDATE(CURDATE(), 1) ";
					break;
				case 'last 7 day':
					$sql_where .= "DATE(created_at) >= NOW() + INTERVAL -7 DAY AND DATE(created_at) <  NOW() + INTERVAL  0 DAY ";
					break;
				case 'this month':
					$sql_where .= "MONTH(created_at) = MONTH(NOW()) ";
					break;
			}
		}

		// Total Records in the datatable
		$total_table_records   = "SELECT count(*) as count FROM {$table_transaction}";
		$total_fetched_records = $wpdb->get_results( $total_table_records, OBJECT );
		$total_records         = $total_fetched_records[0]->count;

		// Total Records Search
		$total_table_records_search   = "SELECT count(*) as count FROM $table_transaction $sql_where";
		$total_fetched_records_search = $wpdb->get_results( $total_table_records_search, OBJECT );
		$total_records_search         = $total_fetched_records_search[0]->count;

		// Query
		$total_results = $wpdb->get_results(
			"SELECT tt.transaction_id, tt.total, tt.status, tt.note, tt.created_at, tt.payment_id, tt.updated_at, tt.currency, tt.country, tt.status, tt.total as raw_total,
						MAX(CASE WHEN ttm.meta_key = '_user_field_name' THEN ttm.meta_value ELSE 0 END) name,
						MAX(CASE WHEN ttm.meta_key = '_user_field_phone' THEN ttm.meta_value ELSE 0 END) phone,
						MAX(CASE WHEN ttm.meta_key = '_user_field_email' THEN ttm.meta_value ELSE 0 END) email,
       					MAX(CASE WHEN ttm.meta_key = '_user_field_address' THEN ttm.meta_value ELSE 0 END) address,
       					MAX(CASE WHEN ttm.meta_key = '_extras_coupon' THEN ttm.meta_value ELSE 0 END) coupon,
						MAX(CASE WHEN tlcom.meta_key = '_billing_invoice' THEN tlcom.meta_value ELSE 0 END) invoice,
						MAX(CASE WHEN tlcom.meta_key = '_order_status' THEN tlcom.meta_value ELSE 0 END) order_status,
						MAX(CASE WHEN tlcom.meta_key = '_shipping_type' THEN tlcom.meta_value ELSE 0 END) shipping_type,
					    MAX(CASE WHEN tlcom.meta_key = '_shipping_status' THEN tlcom.meta_value ELSE 0 END) shipping_status,
						TRIM('\"' FROM SUBSTRING_INDEX(SUBSTRING_INDEX(max(case when tlcom.meta_key = 'shipping' then tlcom.meta_value else 0 end),';',2),':',-1)) AS courier,
						TRIM('\"' FROM SUBSTRING_INDEX(SUBSTRING_INDEX(max(case when tlcom.meta_key = 'shipping' then tlcom.meta_value else 0 end),';',4),':',-1)) AS service,
						TRIM('\"' FROM SUBSTRING_INDEX(SUBSTRING_INDEX(max(case when tlcom.meta_key = 'shipping' then tlcom.meta_value else 0 end),';',6),':',-1)) AS destination
					FROM $table_transaction AS tt
					JOIN $table_transaction_meta AS ttm 
					ON tt.transaction_id=ttm.transaction_id
					LEFT JOIN $table_lwcommerce_order_meta AS tlcom
					ON tt.transaction_id=tlcom.lwcommerce_order_id
					GROUP BY tt.transaction_id $sql_where
					ORDER BY $column $order 
					LIMIT $offset, $length"
		);

		if ( ! empty( $total_results ) ) {

			$data = $total_results;

			foreach ( $total_results as $key => $row ) {

				//==================== Total ====================//
				$data[ $key ]->total = lwp_currency_format( true, abs( $row->total ) );

				//==================== product ====================//
				$data[ $key ]->product = $wpdb->get_results(
					"select jj.ID, jj.post_title, jj.quantity , jj.note
							from $table_transaction as tr
    						join (
								select tp.ID, tp.post_title, tc.cart_uuid, tc.quantity, tc.note from $table_cart as tc
								join $table_post as tp on tc.post_id=tp.ID
							) as jj
							on tr.cart_uuid=jj.cart_uuid where transaction_id='$row->transaction_id'"
				);

				//==================== add image & price to product ====================//
				foreach ( $data[ $key ]->product as $index => $value ) {
					$data[ $key ]->product[ $index ]->image       = get_the_post_thumbnail_url( $value->ID, 'thumbnail' );
					$data[ $key ]->product[ $index ]->price       = lwp_currency_format( true, get_post_meta( $value->ID, '_unit_price', true ) );
					$data[ $key ]->product[ $index ]->price_promo = get_post_meta( $value->ID, '_price_promo', true ) ? lwp_currency_format( true,
						get_post_meta( $value->ID, '_price_promo', true ) ) : null;
				}
			}

			$json_data = array(
				"draw"            => intval( $request['draw'] ),
				"recordsTotal"    => intval( $total_records ),
				"recordsFiltered" => intval( $total_records_search ),
				"data"            => $data,
				"searchQuery"     => $request['search']['value'] ?? null,
				"ordersFilter"    => $order_filter,
				"dateFilter"      => $date_filter,
			);
		} else {
			$json_data = array(
				"data" => array()
			);
		}
		echo json_encode( $json_data );

		wp_die();
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

		$order_id = sanitize_key( $_POST['order_id'] );
		$action   = sanitize_text_field( $_POST['action_type'] );

		if ( $action === 'pending' ) {
			if ( has_action( 'lokuswp/admin/order/action' ) ) {
				do_action( 'lokuswp/admin/order/action', $order_id );
			}
			lwc_update_order_meta( $order_id, '_order_status', 'shipped' );
		}
		if ( $action === 'shipped' ) {
			lwc_update_order_meta( $order_id, '_order_status', 'completed' );
		}
		if ( $action === 'completed' ) {
			lwc_update_order_meta( $order_id, '_order_status', 'refunded' );
		}

		wp_send_json_success( 'success' );
	}

	public function follow_up_whatsapp() {
		if ( ! check_ajax_referer( 'lwc_admin_nonce', 'security' ) ) {
			wp_send_json_error( 'Invalid security token sent.' );
		}

		$data_order = $_POST['data_order'];
		$phone      = lwp_sanitize_phone( sanitize_text_field( $_POST['phone_number'] ), $data_order['country'] );

		$template = apply_filters( 'lokuswp/order/followup/template', $data_order );
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

		$order_id = sanitize_key( $_POST['order_id'] );

		// delete transaction and the meta
		global $wpdb;

		$table_transaction     = $wpdb->prefix . 'lokuswp_transactions';
		$table_transactionmeta = $wpdb->prefix . 'lokuswp_transactionmeta';
		$table_lwc_ordermeta   = $wpdb->prefix . 'lwcommerce_ordermeta';

		$wpdb->query( 'START TRANSACTION' );

		$transaction      = $wpdb->query( $wpdb->prepare( "DELETE FROM $table_transaction WHERE transaction_id = %d", $order_id ) );
		$transaction_meta = $wpdb->query( $wpdb->prepare( "DELETE FROM $table_transactionmeta WHERE transaction_id = %d", $order_id ) );
		$order_meta       = $wpdb->query( $wpdb->prepare( "DELETE FROM $table_lwc_ordermeta WHERE lwcommerce_order_id = %d", $order_id ) );

		if ( $transaction && $transaction_meta && $order_meta ) {
			$wpdb->query( 'COMMIT' );
			wp_send_json_success( 'success' );
		} else {
			$wpdb->query( 'ROLLBACK' );
			wp_send_json_error( 'error' );
		}
	}
}

new AJAX();