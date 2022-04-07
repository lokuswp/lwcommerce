<?php

namespace LokusWP\Commerce\Modules\Plugin;

use LokusWP\Utils\Logger;
use stdClass;

class Updater {

	protected string $plugin_slug = 'lwcommerce'; /* ---CHANGE THIS--- */
	protected string $plugin_file = LWC_BASE; /* ---CHANGE THIS--- */
	protected string $plugin_host = 'https://digitalcraft.id/api/v1/product/plugin/update/'; /* ---CHANGE THIS--- */
	protected string $plugin_version = LWC_VERSION; /* ---CHANGE THIS--- */

	public function __construct() {
		global $pagenow;

		add_filter( 'plugin_row_meta', [ $this, 'plugin_row' ], 10, 3 );
		add_action( 'in_plugin_update_message-' . $this->plugin_slug . '/' . $this->plugin_slug . '.php', [
			$this,
			'plugin_update_message'
		], 10, 2 );
		add_filter( 'plugins_api', array( $this, 'plugin_info' ), 9999, 3 );
		add_action( 'upgrader_process_complete', array( $this, 'plugin_destroy_update' ), 10, 2 );

		// Only Run Checking Update in Plugins Page
		if ( $pagenow == 'plugins.php' ) {
			if ( isset( $_GET['manual-check'] ) && $_GET['manual-check'] == $this->plugin_slug ) {
				delete_transient( $this->plugin_slug . '_update' );
				delete_transient( $this->plugin_slug . '_update_check' );
				$this->check_update();

				Logger::info( "[Plugin][Updater] Manually Checking Update Triggered" );
			} else {
				$this->check_update();

				Logger::info( "[Plugin][Updater] Automatically Checking Update  Triggered" );
			}
		}
	}

	public function check_update() {
		add_filter( 'site_transient_update_plugins', array( $this, 'plugin_updater_logic' ) );
		add_filter( 'transient_update_plugins', array( $this, 'plugin_updater_logic' ) );
	}

	public function plugin_info( $res, $action, object $args ) {
		if ( 'plugin_information' !== $action ) {
			return $res;
		}

		if ( get_transient( $this->plugin_slug . '_update' ) == 'failed_get_update' ) {
			return $res;
		}

		if ( $this->plugin_slug !== $args->slug ) {
			return $res;
		}

		// Source of Data
		$transient = (object) get_transient( $this->plugin_slug . '_update' );

		if ( ! is_wp_error( $transient ) ) {

			if ( ! isset( $args->slug ) ) {
				$args->slug = null;
			}

			if ( $args->slug == $this->plugin_slug ) {
				$res                 = new stdClass();
				$res->name           = $transient->name;
				$res->slug           = $transient->slug;
				$res->version        = $transient->version;
				$res->tested         = $transient->tested;
				$res->requires       = $transient->requires;
				$res->author         = $transient->author;
				$res->author_profile = $transient->author_profile;
				if ( isset( $transient->download_url ) ) {
					$res->download_link = $transient->download_link;
					$res->trunk         = $transient->download_link;
				}
				$res->last_updated = $transient->last_updated;
				$sections          = $transient->sections;
				$res->sections     = array(
					'description'  => $sections->description, // description tab
					'installation' => $sections->installation, // installation tab
					'changelog'    => $sections->changelog
				);
				$banners           = $transient->banners;
				$res->banners      = array(
					'low'  => $banners->low,
					'high' => $banners->high
				);
			}

		}

		return $res;
	}

	public function plugin_updater_host( $transient ) {
		if ( ! is_object( $transient ) ) {
			return $transient;
		}

		// Identity
		// $domain = lwp_clean_http( get_site_url() ); -> Send User Domain when Request

		// Empty Transient and Remote
		if ( ! get_transient( $this->plugin_slug . '_update' ) ) {

			// Remote GET
			$remote = null;
			$server = $this->plugin_host . $this->plugin_slug;
			$response = wp_remote_get(
				$server,
				array(
					'timeout' => 30,
					'headers' => array(
						'Accept' => 'application/json',
					)
				)
			);

			if ( ! is_wp_error( $response ) ) {
				$remote = json_decode( wp_remote_retrieve_body( $response ), false ) ?? null;
			} else {
				// Failed to get remote
				Logger::info( "[Plugin][Updates] Failed to get update, check your CURL " );
				set_transient( $this->plugin_slug . '_update', 'failed_get_update', 300 ); // Waiting 5 minutes
			}

			//Get Response Body
			if ( ! is_wp_error( $response ) && isset( $remote->data ) && $remote->data->slug == $this->plugin_slug ) {
				set_transient( $this->plugin_slug . '_update', $remote->data, 60 * 60 * 6 ); // 6 hours cache
				Logger::info( "[Plugin][Updates] Successful Get Plugin Data Update " );
				return true;
			} else {
				set_transient( $this->plugin_slug . '_update', 'failed_get_update', 60 * 3 ); // 3 minutes
			}
		}

		return false;
	}

	public function plugin_updater_logic( $transient ) {

		// Get new update from remote
		$this->plugin_updater_host( $transient );

		// Transient Process
		$remote = (object) get_transient( $this->plugin_slug . '_update' );

		// Display Update Notice
		$remote_version = isset( $remote->version ) ?? null;
		if ( ! is_wp_error( $remote ) && version_compare( $this->plugin_version, $remote_version, '<' ) ) {
			$res              = new stdClass();
			$res->slug        = $remote->slug;
			$res->plugin      = $this->plugin_slug . '/' . $this->plugin_slug . '.php';
			$res->new_version = $remote_version;
			$res->tested      = $remote->tested;
			if ( isset( $remote->download_url ) ) {
				$res->package = $remote->download_url;
			}
			// TODO : ERROR
			$transient->response[ $res->plugin ] = $res;
		}

		return $transient;
	}

	public function plugin_destroy_update( $upgrader_object, $options ) {
		if ( $options['action'] == 'update' && $options['type'] === 'plugin' ) {
			delete_transient( $this->plugin_slug . '_update' );
			delete_transient( $this->plugin_slug . '_update_check' );
		}

		return $upgrader_object;
	}

	public function plugin_update_message( $data ) {
		if ( empty( $data['package'] ) ) {
			printf( __( 'Please download in %s Member Area %s to update', 'lwcommerce' ), '<a href="https://member.lokuswp.id/" target="_blank">', '</a>' );
		}
		//printf( lwp_transient_timeout( $this->plugin_slug . '_update' ) );
	}

	public function plugin_row( $links, $plugin_file ) {
		if ( strpos( $plugin_file, basename( $this->plugin_file ) ) ) {
			$links[] = '<a id="' . $this->plugin_slug . '" href="' . admin_url( 'plugins.php?manual-check=' . $this->plugin_slug ) . '">' . __( 'Check Update Manually', 'lwcommerce' ) . '</a>';
		}

		return $links;
	}
}