<?php

namespace LokusWP\Commerce\Shipping;

class Rajaongkir {
	protected string $api_key = '80aa49704fc30a939124a831882dea72';
	protected string $api_url = 'https://api.rajaongkir.com/starter/';

	protected string $courier;
	protected string $origin;
	protected string $destination;
	protected string $weight;
	protected string $service;
	protected array $service_allowed;

	private static ?RajaOngkir $instance = null;

	public static function get_instance(): ?RajaOngkir {
		if ( self::$instance == null ) {
			self::$instance = new RajaOngkir();
		}

		return self::$instance;
	}

	/**
	 * @param  string  $courier
	 */
	public function set_courier( string $courier ): void {
		$this->courier = $courier;
	}

	/**
	 * @param  string  $origin
	 */
	public function set_origin( string $origin ): void {
		$this->origin = $origin;
	}

	/**
	 * @param  string  $destination
	 */
	public function set_destination( string $destination ): void {
		$this->destination = $destination;
	}

	/**
	 * @param  string  $weight
	 */
	public function set_weight( string $weight ): void {
		$this->weight = $weight == 0 ? 1 : $weight;
	}

	/**
	 * @param  string  $service
	 */
	public function set_service( string $service ): void {
		$this->service = $service;
	}

	/**
	 * @param  array  $service_allowed
	 */
	public function set_service_allowed( array $service_allowed ): void {
		$this->service_allowed = $service_allowed;
	}

	public function get() {
		$rajaongkir_data = $this->get_data();

		if ( empty( $this->service_allowed ) && ! empty( $this->service ) ) {
			return $this->get_cost( [
				'rajaongkir_data' => $rajaongkir_data,
				'service'         => $this->service,
				'origin'          => $this->origin,
				'destination'     => $this->destination,
				'weight'          => $this->weight,
				'shipping_name'   => $this->courier,
			] );
		}

		$cost_data = [];

		foreach ( $this->service_allowed as $value ) {
			$check_index = in_array( $value, array_column( $rajaongkir_data, 'service' ) );
			if ( $check_index === false ) {
				continue;
			}

			$cost_data[] = $this->get_cost( [
				'rajaongkir_data' => $rajaongkir_data,
				'service'         => $value,
				'origin'          => $this->origin,
				'destination'     => $this->destination,
				'weight'          => $this->weight,
				'shipping_name'   => $this->courier,
			] );
		}

		return $cost_data;
	}

	private function get_data() {
		$header = [
			'content-type' => 'application/json',
			'key'          => $this->api_key,
		];

		$body = [
			'origin'      => abs( $this->origin ),
			'destination' => abs( $this->destination ),
			'weight'      => abs( $this->weight ),
			'courier'     => strtolower( sanitize_key( $this->courier ) ),
		];

		$options = [
			'body'    => wp_json_encode( $body ),
			'headers' => $header,
		];

		$request = wp_remote_post( "{$this->api_url}cost", $options );

		if ( is_wp_error( $request ) ) {
			return false;
		}

		$response = json_decode( wp_remote_retrieve_body( $request ) );

		return $response->rajaongkir->results[0]->costs;
	}

	private function get_cost( array $data ) {
		$destination_cost = get_transient( $this->courier . '_cost' );

		$cost = $destination_cost["{$data['origin']}_to_{$data['destination']}_with_{$data['service']}_weight_{$data['weight']}"] ?? false;

		if ( ! empty( $cost['cost'] ) ) {
			return $cost;
		}

		$index = array_search( $data['service'], array_column( $data['rajaongkir_data'], 'service' ) );

		$cost            = $data['rajaongkir_data'][ $index ]->cost[0]->value;
		$estimation_date = $data['rajaongkir_data'][ $index ]->cost[0]->etd;

		// Push new destination to cache
		$destination_cost["{$data['origin']}_to_{$data['destination']}_with_{$data['service']}_weight_{$data['weight']}"] = [
			'cost'    => $cost,
			'etd'     => $estimation_date,
			'service' => $data['service']
		];

		set_transient( $data['shipping_name'] . '_cost', $destination_cost, DAY_IN_SECONDS * 3 ); // 3 day expiration

		if ( $cost ) {
			return [
				'cost'    => $cost,
				'etd'     => $estimation_date,
				'service' => $data['service']
			];
		}

		return false;
	}
}