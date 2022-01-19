<?php

namespace LokusWP\Commerce\Admin;

class AJAX {
	public function __construct() {
		add_action( 'wp_ajax_lwpc_store_settings_save', [ $this, 'store_settings_save' ] );
		add_action( 'wp_ajax_lwpc_shipping_package_status', [ $this, 'shipping_package_status' ] );

		add_action( 'wp_ajax_lwpc_get_orders', [ $this, 'get_orders' ] );
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

	public function get_orders() {
		if ( ! check_ajax_referer( 'lwpc_admin_nonce', 'security' ) ) {
			wp_send_json_error( 'Invalid security token sent.' );
		}

		global $wpdb;

		// Table name
		$table_cart        = $wpdb->prefix . "lokuswp_carts";
		$table_transaction = $wpdb->prefix . "lokuswp_transactions";
		$table_user        = $wpdb->prefix . "users";
		$table_post        = $wpdb->prefix . "posts";
		$table_post_meta   = $wpdb->prefix . "postmeta";

		// Request
		$request = $_GET;

		// Columns
		$columns = array(
			0 => 'transaction_id',
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

			$sql_where .= "WHERE ";

			foreach ( $columns as $column ) {

				$sql_where .= $column . " LIKE '%" . sanitize_text_field( $request['search']['value'] ) . "%' OR ";
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
			"SELECT * FROM $table_transaction $sql_where ORDER BY $column $order LIMIT $offset, $length"
		);

		if ( ! empty( $total_results ) ) {

			$data = $total_results;

			foreach ( $total_results as $key => $row ) {

				//==================== name ====================//
				$data[ $key ]->name = lwp_get_transaction_meta( $row->transaction_id, 'billing_name', true );

				//==================== phone ====================//
				$data[ $key ]->phone = lwp_get_transaction_meta( $row->transaction_id, 'billing_phone', true );

				//==================== email ====================//
				$data[ $key ]->email = lwp_get_transaction_meta( $row->transaction_id, 'billing_email', true );

				//==================== address ====================//
				$data[ $key ]->address = lwp_get_transaction_meta( $row->transaction_id, 'billing_address' );

				//==================== shipping ====================//
				$data[ $key ]->shipping = lwpc_get_order_meta( $row->transaction_id, 'shipping' );

				//==================== Total ====================//
				$data[ $key ]->total = lwpbb_set_currency_format( true, abs( $row->total ) );

				//==================== Status ====================//
				$data[ $key ]->status_processing = lwpc_get_order_meta( $row->transaction_id, 'status_processing', true );

				//==================== Date & Time ====================//
				$data[ $key ]->created_at = date( "j-m-Y H:i", strtotime( $row->created_at ) );

				//==================== product ====================//
				$data[ $key ]->product = $wpdb->get_results(
					"select jj.ID, jj.post_title, jj.quantity 
							from $table_transaction as tr
    						join (
								select tp.ID, tp.post_title, tc.cart_hash, tc.quantity from $table_cart as tc
								join $table_post as tp on tc.post_id=tp.ID
							) as jj
							on tr.cart_hash=jj.cart_hash where transaction_id='$row->transaction_id'"
				);

				//==================== add image & price to product ====================//
				foreach ( $data[ $key ]->product as $index => $value ) {
					$data[ $key ]->product[ $index ]->image          = wp_get_attachment_image_src( get_post_thumbnail_id( $value->ID ) );
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
}

new AJAX();