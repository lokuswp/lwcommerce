<?php

/**
 * Add Property to Object
 *
 * @source Hook Source lokuswp/src/includes/module/notification/channels/class-notification-email.php | prepare_data() | on line 79
 */
add_filter( "lokuswp/notification/email/data", "lwc_notification_email_data", 10, 2 );
function lwc_notification_email_data( $notification, $trx_id ) {

	$notification->status = lwc_get_order_meta( $trx_id, '_order_status', true );
	$notification->path   = LWC_PATH . 'src/templates/emails/';

	return $notification;
}