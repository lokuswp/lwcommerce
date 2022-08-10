<?php
/**
 * Set dynamic settings with sanitize
 *
 * @param string $option
 * @param string $item
 * @param [type] $value
 * @param string $sanitize
 *
 * @return false|void
 */
function lwc_set_settings( string $option, string $item, $value, string $sanitize = 'sanitize_text_field' ) {
	$settings = get_option( 'lwcommerce_' . $option );

	$whitelist = [
		'sanitize_text_field',
		'sanitize_option',
		'sanitize_key',
		'abs',
		'esc_url_raw',
		'intval',
		'floatval',
		'absint',
		'sanitize_email'
	];
	if ( ! in_array( $sanitize, $whitelist ) ) {
		return false;
	}

	$row          = empty( $settings ) ? array() : $settings;
	$row[ $item ] = call_user_func( $sanitize, $value );
	update_option( 'lwcommerce_' . $option, $row );
}

