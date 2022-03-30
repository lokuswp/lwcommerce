<?php

namespace LokusWP\Commerce\Modules\Plugin;

//use LokusWP\Utils\Logger;
use stdClass;


class Updater {

	protected string $plugin_slug = 'lwcommerce'; /* ---CHANGE THIS--- */
	protected string $plugin_file = LWC_BASE; /* ---CHANGE THIS--- */
	protected string $plugin_host = 'https://dash.lsdplugins.com/route/lsd/v1/'; /* ---CHANGE THIS--- */
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

		if ( $pagenow == 'plugins.php' ) {
			$this->check_manually();
		}

		 $this->check_automatically();
	}

	public function check_automatically() {
		$this->check_update();

//		Logger::info( "[Plugin][Updater] Automatically Checking Update Triggered", "lwcommerce");
	}

	public function check_manually() {
		global $pagenow;

		if ( isset( $_GET['manual-check'] ) && $_GET['manual-check'] == $this->plugin_slug && $pagenow == 'plugins.php' ) {
			$this->check_update();
//			add_action( 'upgrader_process_complete', array( $this, 'plugin_destroy_update' ), 10, 2 );

//			Logger::info( "[Plugin][Updater] Manually Checking Update Triggered" );
		}
	}

	public function check_update() {
		add_filter( 'site_transient_update_plugins', array( $this, 'plugin_add_update' ) );
		add_filter( 'transient_update_plugins', array( $this, 'plugin_add_update' ) );
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
			$server = "https://12d91b96-91d2-4e7e-982e-e2189462d57f.mock.pstmn.io/api/v1/product/plugin/update/" . $this->plugin_slug;
			$remote = wp_remote_get(
				$server,
				array(
					'timeout' => 30,
					'headers' => array(
						'Accept' => 'application/json',
					)
				)
			);

			if ( ! is_wp_error( $remote ) ) {
				$remote = json_decode( $remote['body'] );
			} else {
				// Failed to get remote
//				Logger::info( "[Plugin][Updates] Failed to get update, check your CURL " );
				set_transient( $this->plugin_slug . '_update', 'failed_get_update', 300 ); // Waiting 5 minutes
			}

			//Get Response Body
			if ( ! is_wp_error( $remote ) && isset( $remote->slug ) && $remote->slug == $this->plugin_slug ) {
				set_transient( $this->plugin_slug . '_update', $remote, 60 * 60 * 6 ); // 6 hours cache

				return true;
			} else {
				set_transient( $this->plugin_slug . '_update', 'failed_get_update', 60 * 3 ); // 3 minutes
			}
		}

		return false;
	}

	public function plugin_add_update( $transient ) {

		// Get new update from remote
		$this->plugin_updater_host( $transient );

		// Transient Process
		$remote = (object) get_transient( $this->plugin_slug . '_update' );

//		Logger::info( (array) $remote, "lwcommerce");

		// Display Update Notice
		$remote_version = $remote->version ?? "0.0.1";
		if ( ! is_wp_error( $remote ) && version_compare( $this->plugin_version, $remote_version, '<' ) ) {
			$res              = new stdClass();
			$res->slug        = $remote->slug;
			$res->plugin      = $this->plugin_slug . '/' . $this->plugin_slug . '.php';
			$res->new_version = $remote_version;
			$res->tested      = $remote->tested;
			if ( isset( $remote->download_link ) ) {
				$res->package = $remote->download_link;
			}
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
