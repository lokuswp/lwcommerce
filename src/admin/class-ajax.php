<?php

namespace LokusWP\Commerce\Admin;

class AJAX {
	public function __construct() {
		add_action( 'wp_ajax_lwpc_store_settings_save', [ $this, 'store_settings_save' ] );
		add_action( 'wp_ajax_lwpc_shipping_package_status', [ $this, 'shipping_package_status' ] );

		add_action( 'wp_ajax_lwpc_shipping_settings_save', [ $this, 'shipping_settings_save' ] );

		// Orders
		add_action( 'wp_ajax_lwpc_get_orders', [ $this, 'get_orders' ] );
		add_action( 'wp_ajax_lwpc_process_order', [ $this, 'process_order' ] );
		add_action( 'wp_ajax_lwpc_update_resi', [ $this, 'update_resi' ] );

		// Statistic
		add_action( 'wp_ajax_lwpc_orders_chart', [ $this, 'orders_chart' ] );
	}

	public function store_settings_save() {
		if ( ! check_ajax_referer( 'lwpc_admin_nonce', 'security' ) ) {
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
		$settings = get_option( 'lwpcommerce_store' );
		if ( empty( $settings ) ) {
			$merge = $sanitize;
		} else {
			$merge = array_merge( $settings, $sanitize );
		}

		// Update New Settings
		update_option( 'lwpcommerce_store', $merge );
		echo 'action_success';

		wp_die();
	}

	public function shipping_package_status() {
		if ( ! check_ajax_referer( 'lwpc_admin_nonce', 'security' ) ) {
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
		if ( ! check_ajax_referer( 'lwpc_admin_nonce', 'security' ) ) {
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
		$settings = get_option( 'lwpcommerce_shipping' );
		if ( empty( $settings ) ) {
			$merge = $sanitize;
		} else {
			$merge = array_merge( $settings, $sanitize );
		}

		// Update New Settings
		update_option( 'lwpcommerce_shipping', $merge );
		echo 'action_success';

		wp_die();
	}

	public function get_orders() {
		if ( ! check_ajax_referer( 'lwpc_admin_nonce', 'security' ) ) {
			wp_send_json_error( 'Invalid security token sent.' );
		}

		global $wpdb;

		// Table name
		$table_cart                   = $wpdb->prefix . "lokuswp_carts";
		$table_transaction            = $wpdb->prefix . "lokuswp_transactions";
		$table_transaction_meta       = $wpdb->prefix . "lokuswp_transactionmeta";
		$table_lwpcommerce_order_meta = $wpdb->prefix . "lwpcommerce_ordermeta";
		$table_post                   = $wpdb->prefix . "posts";

		// Request
		$request = $_GET;

		// Columns
		$columns = array(
			0  => 'transaction_id',
			1  => 'total',
			2  => 'status',
			3  => 'note',
			4  => 'created_at',
			5  => 'updated_at',
			6  => 'name',
			7  => 'phone',
			8  => 'email',
			9  => 'status_processing',
			10 => 'courier',
			11 => 'service',
			12 => 'no_resi',
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

				$sql_where .= $column . " = '" . sanitize_text_field( $request['search']['value'] ) . "' OR ";
			}

			$sql_where = substr( $sql_where, 0, - 3 );
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
			"SELECT tt.transaction_id, tt.total, tt.status, tt.note, tt.created_at, tt.updated_at,
						MAX(CASE WHEN ttm.meta_key = 'billing_name' THEN ttm.meta_value ELSE 0 END) name,
						MAX(CASE WHEN ttm.meta_key = 'billing_phone' THEN ttm.meta_value ELSE 0 END) phone,
						MAX(CASE WHEN ttm.meta_key = 'billing_email' THEN ttm.meta_value ELSE 0 END) email,
						MAX(CASE WHEN tlcom.meta_key = 'status_processing' THEN tlcom.meta_value ELSE 0 END) status_processing,
						MAX(CASE WHEN tlcom.meta_key = 'no_resi' THEN tlcom.meta_value ELSE 0 END) no_resi,
						TRIM('\"' FROM SUBSTRING_INDEX(SUBSTRING_INDEX(max(case when tlcom.meta_key = 'shipping' then tlcom.meta_value else 0 end),';',2),':',-1)) AS courier,
						TRIM('\"' FROM SUBSTRING_INDEX(SUBSTRING_INDEX(max(case when tlcom.meta_key = 'shipping' then tlcom.meta_value else 0 end),';',4),':',-1)) AS service,
						TRIM('\"' FROM SUBSTRING_INDEX(SUBSTRING_INDEX(max(case when tlcom.meta_key = 'shipping' then tlcom.meta_value else 0 end),';',6),':',-1)) AS destination
					FROM $table_transaction AS tt
					JOIN $table_transaction_meta AS ttm 
					ON tt.transaction_id=ttm.transaction_id
					JOIN $table_lwpcommerce_order_meta AS tlcom
					ON tt.transaction_id=tlcom.lwpcommerce_order_id 
					GROUP BY tt.transaction_id $sql_where
					ORDER BY $column $order 
					LIMIT $offset, $length"
		);

		if ( ! empty( $total_results ) ) {

			$data = $total_results;

			foreach ( $total_results as $key => $row ) {

				//==================== address ====================//
				$data[ $key ]->address = lwp_get_transaction_meta( $row->transaction_id, 'billing_address' );

				//==================== Total ====================//
				$data[ $key ]->total = lwpbb_set_currency_format( true, abs( $row->total ) );

				//==================== product ====================//
				$data[ $key ]->product = $wpdb->get_results(
					"select jj.ID, jj.post_title, jj.quantity , jj.note
							from $table_transaction as tr
    						join (
								select tp.ID, tp.post_title, tc.cart_hash, tc.quantity, tc.note from $table_cart as tc
								join $table_post as tp on tc.post_id=tp.ID
							) as jj
							on tr.cart_hash=jj.cart_hash where transaction_id='$row->transaction_id'"
				);

				//==================== add image & price to product ====================//
				foreach ( $data[ $key ]->product as $index => $value ) {
					$data[ $key ]->product[ $index ]->image          = get_the_post_thumbnail_url( $value->ID, 'thumbnail' );
					$data[ $key ]->product[ $index ]->price          = lwpbb_set_currency_format( true, get_post_meta( $value->ID, '_price_normal', true ) );
					$data[ $key ]->product[ $index ]->price_discount = get_post_meta( $value->ID, '_price_discount', true ) ? lwpbb_set_currency_format( true,
						get_post_meta( $value->ID, '_price_discount', true ) ) : null;
				}
			}

			$json_data = array(
				"draw"            => intval( $request['draw'] ),
				"recordsTotal"    => intval( $total_records ),
				"recordsFiltered" => intval( $total_records_search ),
				"data"            => $data
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
		if ( ! check_ajax_referer( 'lwpc_admin_nonce', 'security' ) ) {
			wp_send_json_error( 'Invalid security token sent.' );
		}

		$transaction_id = $_POST['transaction_id'];
		$status         = $_POST['status'];

		if ( ! empty( $transaction_id ) ) {
			$transaction_id = sanitize_text_field( $transaction_id );
			$status         = sanitize_text_field( $status );

			$transaction_id = lwpc_update_order_meta( $transaction_id, 'status_processing', $status );

			if ( $transaction_id ) {
				wp_send_json_success( 'Successfully updated.' );
			} else {
				wp_send_json_error( 'Failed to update.' );
			}
		} else {
			wp_send_json_error( 'Invalid transaction id.' );
		}
	}

	public function update_resi() {
		if ( ! check_ajax_referer( 'lwpc_admin_nonce', 'security' ) ) {
			wp_send_json_error( 'Invalid security token sent.' );
		}

		$transaction_id = $_POST['transaction_id'];
		$no_resi        = $_POST['resi'];

		if ( ! empty( $transaction_id ) ) {
			$transaction_id = sanitize_text_field( $transaction_id );
			$no_resi        = sanitize_text_field( $no_resi );

			lwpc_update_order_meta( $transaction_id, 'no_resi', $no_resi );
			$status = lwpc_update_order_meta( $transaction_id, 'status_processing', 'shipping' );

			if ( $status ) {
				wp_send_json_success( 'Successfully updated.' );
			} else {
				wp_send_json_error( 'Failed to update.' );
			}
		} else {
			wp_send_json_error( 'Invalid transaction id.' );
		}
	}

	public function orders_chart() {
		if ( ! check_ajax_referer( 'lwpc_admin_nonce', 'security' ) ) {
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

		return wp_send_json( [ [ $data_date, $data_total, $data_phone ], [ $data_total_yesterday, $data_phone_yesterday ] ] );
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
}

new AJAX();