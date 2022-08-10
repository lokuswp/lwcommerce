<?php

add_action( "lokuswp/transaction/save", "lwc_pro_create_transaction", 10, 2 );
function lwc_pro_create_transaction( $trx_id, $trx_data ) {
	$inv_number = 'INV/' . lwp_current_date( 'ymd' ) . '/' .
	              lwp_number_to_roman( lwp_current_date( 'y' ) ) . '/' .
	              lwp_number_to_roman( lwp_current_date( 'n' ) ) . '/' .
	              str_pad( $trx_id, 7, '0', STR_PAD_LEFT
	              );
	lwc_update_order_meta( $trx_id, "_billing_invoice", $inv_number );
}