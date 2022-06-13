<?php

namespace LokusWP\Commerce\Modules\Order;

use LokusWP\Modules\Datatable\DT_Query;

class Datatable_Order extends DT_Query {
	public function __construct() {
		parent::__construct();
	}

	public function filter_search( $sql_where ) {
		if ( ! empty( $this->request->search ) ) {

			$sql_where .= "HAVING ";

			foreach ( $this->columns as $column ) {

				$sql_where .= $column . " LIKE '%" . sanitize_text_field( $this->request->search ) . "%' OR ";
			}

			$sql_where = substr( $sql_where, 0, - 3 );
		}

		return $sql_where;
	}

	public function inject( string $sql_where ): string {
		$sql_where = $this->filter_order( $sql_where );
		$sql_where = $this->filter_date( $sql_where );

		return $sql_where;
	}

	public function filter_order( $sql_where ) {
		$order_filter = strtolower( sanitize_text_field( $this->request->base['orderFilter'] ) );

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

		return $sql_where;
	}

	public function filter_date( $sql_where ) {
		$date_filter = strtolower( sanitize_text_field( $this->request->base['dateFilter'] ) );

		$range = explode( '/', $date_filter );

		if ( count( $range ) === 2 ) {
			$sql_where .= "DATE(created_at) BETWEEN '" . sanitize_text_field( $range[0] ) . "' AND '" . sanitize_text_field( $range[1] ) . "' ";
		}

		switch ( $date_filter ) {
			case 'today':
				$sql_where .= ( ! empty( $sql_where ) ) ? " AND " : "HAVING ";

				$sql_where .= "DATE(created_at) = CURDATE() ";
				break;
			case 'yesterday':
				$sql_where .= ( ! empty( $sql_where ) ) ? " AND " : "HAVING ";

				$sql_where .= "DATE(created_at) = SUBDATE(CURDATE(), 1) ";
				break;
			case 'last 7 day':
				$sql_where .= ( ! empty( $sql_where ) ) ? " AND " : "HAVING ";

				$sql_where .= "DATE(created_at) >= NOW() + INTERVAL -7 DAY AND DATE(created_at) <  NOW() + INTERVAL  0 DAY ";
				break;
			case 'this month':
				$sql_where .= ( ! empty( $sql_where ) ) ? " AND " : "HAVING ";

				$sql_where .= "MONTH(created_at) = MONTH(NOW()) ";
				break;
		}

		return $sql_where;
	}
}