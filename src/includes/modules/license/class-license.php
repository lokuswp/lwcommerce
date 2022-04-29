<?php

namespace LokusWP\Commerce\Modules;

class License {
	protected string $api = 'https://digitalcraft.id/api/v1/license';

	public function save() {
		if ( ! check_ajax_referer( 'lwc_admin_nonce', 'security' ) ) {
			wp_send_json_error( 'Invalid security token sent.' );
		}

		$slug        = sanitize_text_field( $_POST['slug'] );
		$license_key = isset( $_POST['license_key'] ) ? sanitize_text_field( $_POST['license_key'] ) : null;
		$domain      = parse_url( get_site_url() )['host'];

		// send request to api server
		$response = $this->check_license( $slug, $license_key, $domain );

		// save to wp options
		$licenses          = get_option( 'lwcommerce_licenses', [] );
		$licenses[ $slug ] = [
			'license_key' => $license_key,
			'domain'      => $domain,
			'expired'     => $response->data->expire_at,
			'status'      => $response->data->status,
		];
		update_option( 'lwcommerce_licenses', $licenses );

		wp_send_json( $response );
	}

	private function check_license( $slug, $license_key, $domain ) {
		$response = wp_remote_get( $this->api . "/{$license_key}" );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$code     = wp_remote_retrieve_response_code( $response );
		$response = json_decode( $response['body'] );

		if ( $code === 500 ) {
			return [
				'success' => 'false',
				'message' => 'Something went wrong. Please try again later.',
			];
		}

		if ( ! $response->success ) {
			return $response;
		}

		if ( $slug !== $response->data->slug ) {
			return [
				'success' => 'false',
				'message' => 'Invalid slug.'
			];
		}

		$domains = array_merge( $response->data->domains, $response->data->subdomains );

		if ( ! in_array( $domain, $domains ) ) {
			return [
				'success' => 'false',
				'message' => 'This license is not valid for this domain.',
			];
		}

		return $response;
	}
}

add_action( 'wp_ajax_lwc_license_save', [ new License, 'save' ] );