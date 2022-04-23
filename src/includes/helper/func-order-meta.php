<?php
/**
 * Get Order Meta Data
 *
 * @param $id
 * @param $meta_key
 * @param $single
 *
 * @return array|false|mixed
 */
function lwc_get_order_meta( $id, $meta_key, $single = true ) {
	return get_metadata( 'lwcommerce_order', $id, $meta_key, $single );
}

/**
 * Update Order Meta Data
 *
 * @param $id
 * @param $meta_key
 * @param $value
 *
 * @return bool|int
 */
function lwc_update_order_meta( $id, $meta_key, $value = '' ) {
	return update_metadata( 'lwcommerce_order', $id, $meta_key, $value );
}

/**
 * Add Order Meta Data
 *
 * @param $id
 * @param $meta_key
 * @param $value
 *
 * @return false|int
 */
function lwc_add_order_meta( $id, $meta_key, $value = '' ) {
	return add_metadata( 'lwcommerce_order', $id, $meta_key, $value );
}

/**
 * Delete Order Meta Data
 *
 * @param $id
 * @param $meta_key
 *
 * @return bool
 */
function lwc_delete_order_meta( $id, $meta_key = '' ) {
	return delete_metadata( 'lwcommerce_order', $id, $meta_key );
}