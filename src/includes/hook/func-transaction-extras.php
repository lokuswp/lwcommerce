<?php

add_filter( 'lokuswp/transaction/extras/data', 'lwp_transaction_extras', 10, 2 );
function lwp_transaction_extras( $extras ) {
	$extras      = lwp_filter_allowed_array( $extras, '_extras_' );
	$extras_data = [];

	if ( empty($extras) ) {
		return [];
	}

	foreach ( $extras as $key => $value ) {


		if ( strpos( $key, '_extras_' ) !== false ) { // Has extras in metadata
			$extras_name = str_replace( '_extras_', '', $key );

			if ( has_filter( "lokuswp/transaction/extras/data/{$extras_name}" ) ) {
				$processed_extras    = apply_filters( "lokuswp/transaction/extras/data/{$extras_name}", $value );
				$extras_data[ $key ] = $processed_extras;
			}
		}
	}

	return $extras_data;
}