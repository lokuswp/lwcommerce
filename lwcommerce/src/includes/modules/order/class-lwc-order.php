<?php

namespace LokusWP\Commerce\Modules\Order;

use LokusWP\Modules\Datatable\Datatable;
use WP_Error;

class LWC_Order {

	public static function get_order( Datatable $datatable ) {
		$columns = [
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
		];

		$fields = [
			'tt.transaction_id',
			'tt.total',
			'tt.status',
			'tt.note',
			'tt.created_at',
			'tt.payment_id',
			'tt.updated_at',
			'tt.currency',
			'tt.country',
			'tt.status',
			'tt.total as raw_total',
			"MAX(CASE WHEN ttm.meta_key = '_user_field_name' THEN ttm.meta_value ELSE 0 END) name",
			"MAX(CASE WHEN ttm.meta_key = '_user_field_phone' THEN ttm.meta_value ELSE 0 END) phone",
			"MAX(CASE WHEN ttm.meta_key = '_user_field_email' THEN ttm.meta_value ELSE 0 END) email",
			"MAX(CASE WHEN ttm.meta_key = '_user_field_address' THEN ttm.meta_value ELSE 0 END) address",
			"MAX(CASE WHEN ttm.meta_key = '_extras_coupon' THEN ttm.meta_value ELSE 0 END) coupon",
			"MAX(CASE WHEN tlcom.meta_key = '_billing_invoice' THEN tlcom.meta_value ELSE 0 END) invoice",
			"MAX(CASE WHEN tlcom.meta_key = '_order_status' THEN tlcom.meta_value ELSE 0 END) order_status",
			"MAX(CASE WHEN tlcom.meta_key = '_shipping_type' THEN tlcom.meta_value ELSE 0 END) shipping_type",
			"MAX(CASE WHEN tlcom.meta_key = '_shipping_status' THEN tlcom.meta_value ELSE 0 END) shipping_status",
			"TRIM('\"' FROM SUBSTRING_INDEX(SUBSTRING_INDEX(max(case when ttm.meta_key = '_extras_shipping' then ttm.meta_value else 0 end),';',2),':',-1)) AS service",
			"TRIM('\"' FROM SUBSTRING_INDEX(SUBSTRING_INDEX(max(case when ttm.meta_key = '_extras_shipping' then ttm.meta_value else 0 end),';',4),':',-1)) AS courier",
			"TRIM('\"' FROM SUBSTRING_INDEX(SUBSTRING_INDEX(max(case when ttm.meta_key = '_extras_shipping' then ttm.meta_value else 0 end),';',6),':',-1)) AS destination"
		];

		$data = $datatable
			->set( $_GET, $columns )
			->select( $fields, 'lokuswp_transactions AS tt' )
			->join( 'lokuswp_transactionmeta AS ttm', 'tt.transaction_id = ttm.transaction_id' )
			->join( 'lwcommerce_ordermeta AS tlcom', 'tt.transaction_id = tlcom.lwcommerce_order_id' )
			->group_by( 'tt.transaction_id' )
			->order_by( 'tt.created_at', 'DESC' )
			->get();

		if ( is_wp_error( $data ) ) {
			return $data->get_error_messages();
		}

		if ( ! empty( $data ) ) {
			global $wpdb;

			$table_transaction = $wpdb->prefix . 'lokuswp_transactions';
			$table_post        = $wpdb->prefix . 'posts';
			$table_cart        = $wpdb->prefix . 'lokuswp_carts';

			foreach ( $data['data'] as $value ) {
				//==================== Total ====================//
				$value->total = lwp_currency_format( true, abs( $value->total ) );

				//==================== product ====================//
				$value->product = $wpdb->get_results(
					"select jj.ID, jj.post_title, jj.quantity , jj.note
							from $table_transaction as tr
    						join (
								select tp.ID, tp.post_title, tc.cart_uuid, tc.quantity, tc.note from $table_cart as tc
								join $table_post as tp on tc.post_id=tp.ID
							) as jj
							on tr.cart_uuid=jj.cart_uuid where transaction_id='$value->transaction_id'"
				);

				//==================== add image & price to product ====================//
				foreach ( $value->product as $product ) {
					$product->image       = get_the_post_thumbnail_url( $product->ID, 'thumbnail' );
					$product->price       = lwp_currency_format( true, get_post_meta( $product->ID, '_unit_price', true ) );
					$product->price_promo = get_post_meta( $product->ID, '_price_promo', true ) ? lwp_currency_format( true,
						get_post_meta( $product->ID, '_price_promo', true ) ) : null;
				}

				//==================== Payment Logo ====================//
				$payment            = (object) lwp_get_option( "payment-{$value->payment_id}" );
				$value->payment_url = $payment->logo_url;
			}

			if ( isset( $datatable->get_request_field()->base ) ) {
				$data['ordersFilter'] = $datatable->get_request_field()->base['orderFilter'];
				$data['dateFilter']   = $datatable->get_request_field()->base['dateFilter'];
			}

		}

		return $data;
	}

	public static function delete_order( $order_id ): bool {
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

			return true;
		} else {
			$wpdb->query( 'ROLLBACK' );

			return false;
		}
	}

	public static function get_data_for_export() {
		global $wpdb;
		$table_transactions = $wpdb->prefix . "lokuswp_transactions";
		$table_meta         = $wpdb->prefix . "lokuswp_transactionmeta";
		$table_meta_order   = $wpdb->prefix . "lwcommerce_ordermeta";
		$table_cart         = $wpdb->prefix . "lokuswp_carts";
		$table_post         = $wpdb->prefix . "posts";

		// get data
		$table_transaction = $wpdb->get_results( "SELECT transaction_id, status as payment_status, currency, country, payment_id FROM $table_transactions", ARRAY_A );
		$table_meta        = $wpdb->get_results( "SELECT * FROM $table_meta", ARRAY_A );
		$table_meta_order  = $wpdb->get_results( "SELECT * FROM $table_meta_order", ARRAY_A );

		// merge data transaction with meta
		foreach ( $table_transaction as $key => $value ) {
			foreach ( $table_meta as $value_meta ) {
				if ( $value['transaction_id'] === $value_meta['transaction_id'] ) {
					$table_transaction[ $key ][ $value_meta['meta_key'] ] = $value_meta['meta_value'];
				}
			}
			foreach ( $table_meta_order as $value_meta ) {
				if ( $value['transaction_id'] === $value_meta['lwcommerce_order_id'] ) {
					$table_transaction[ $key ][ $value_meta['meta_key'] ] = $value_meta['meta_value'];
				}
			}


			$transaction_id = $value['transaction_id'];

			$products = $wpdb->get_results( "select tc.quantity, tp.post_title from $table_transactions as tt join $table_cart as tc on tc.cart_uuid=tt.cart_uuid join $table_post as tp on tc.post_id=tp.ID where tt.transaction_id=$transaction_id",
				ARRAY_A );

			$product_title      = [];
			$product_item_count = 0;
			foreach ( $products as $product ) {
				$product_title[]    = $product['post_title'] . "({$product['quantity']})";
				$product_item_count += $product['quantity'];
			}
			$table_transaction[ $key ]['product_title']      = implode( '|', $product_title );
			$table_transaction[ $key ]['product_item_count'] = $product_item_count;
		}

		// remove cart_uuid <- will re-create in export function
		foreach ( $table_transaction as $key => $value ) {
			unset( $table_transaction[ $key ]['cart_uuid'] );
			unset( $table_transaction[ $key ]['_snapshot_extras'] );
			unset( $table_transaction[ $key ]['_snapshot_items'] );
			unset( $table_transaction[ $key ]['_billing_invoice'] );
			unset( $table_transaction[ $key ]['_shipping_type'] );
			unset( $table_transaction[ $key ]['_shipping_status'] );
			unset( $table_transaction[ $key ]['_order_id'] );

			$table_transaction[ $key ]['name']         = $table_transaction[ $key ]['_user_field_name'];
			$table_transaction[ $key ]['phone']        = $table_transaction[ $key ]['_user_field_phone'];
			$table_transaction[ $key ]['email']        = $table_transaction[ $key ]['_user_field_email'];
			$table_transaction[ $key ]['order_status'] = $table_transaction[ $key ]['_order_status'];
			unset( $table_transaction[ $key ]['_user_field_name'] );
			unset( $table_transaction[ $key ]['_user_field_phone'] );
			unset( $table_transaction[ $key ]['_user_field_email'] );
			unset( $table_transaction[ $key ]['_order_status'] );
		}

		return $table_transaction;
	}

	public static function make_csv( $header, $target_dir, $data ) {
		// create file
		$file = fopen( $target_dir, 'w' );
		if ( ! $file ) {
			return new WP_Error( 'filed_create_file', __( 'Failed creating file', 'lwdonation' ) );
		}

		// write csv
		fputcsv( $file, $header );
		foreach ( $data as $row ) {
			$content = array_values( $row );
			fputcsv( $file, $content );
		}

		// close file
		fclose( $file );

		return true;
	}
}