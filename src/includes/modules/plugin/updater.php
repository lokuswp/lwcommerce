<?php

namespace LokusWP\Commerce\Modules\Plugin;

use LokusWP\Utils\Logger;
use Parsedown;
use stdClass;

class Updater {

	protected string $plugin_file = LWC_BASE; /* ---CHANGE THIS--- */
	protected string $plugin_slug = 'lwcommerce'; /* ---CHANGE THIS--- */
	protected string $plugin_host = 'https://api.github.com/repos/lokuswp/lwcommerce/releases/latest'; /* ---CHANGE THIS--- */
	protected string $plugin_version = LWC_VERSION; /* ---CHANGE THIS--- */

	public function __construct() {
		global $pagenow;

		add_filter( 'network_admin_plugin_action_links', [ $this, 'plugin_row' ], 10, 4 );
		add_filter( 'plugin_action_links', [ $this, 'plugin_row' ], 10, 4 );
		add_action( 'in_plugin_update_message-' . $this->plugin_slug . '/' . $this->plugin_slug . '.php', [ $this, 'plugin_update_message' ], 10, 2 );
		add_filter( 'plugins_api', array( $this, 'plugin_info' ), 9999, 3 );
		add_action( 'upgrader_process_complete', array( $this, 'plugin_destroy_update' ), 10, 2 );

		add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'plugin_updater_logic' ) );

		// Only Run Checking Update in Plugins Page
		if ( $pagenow == 'plugins.php' ) {


			if ( isset( $_GET['manual-check'] ) && $_GET['manual-check'] == $this->plugin_slug ) {
                add_action( 'site_transient_update_plugins', array( $this, 'plugin_updater_logic' ) );
				delete_transient( $this->plugin_slug . '_update' );
				delete_transient( $this->plugin_slug . '_update_check' );
			}
		}

        raydebugger("fasfa");
	}

	public function plugin_info( $res, $action, object $args ) {
		if ( 'plugin_information' !== $action ) {
			return $res;
		}

		$transient_update = get_transient( $this->plugin_slug . '_update' );

		if ( $transient_update == 'failed_get_update' ) {
			return $res;
		}

		if ( $this->plugin_slug !== $args->slug ) {
			return $res;
		}

		// Source of Data
		$remote = (object) $transient_update;

		if ( ! is_wp_error( $remote ) ) {
			if ( ! isset( $args->slug ) ) {
				$args->slug = null;
			}

			if ( $args->slug == $this->plugin_slug ) {

				// parsedown (Markdown to html)
				require_once LWC_PATH . 'src/includes/libraries/php/Parsedown.php';
				$parsedown = new Parsedown();
				$assets    = isset($remote->assets) ? current( $remote->assets ) : null;

				if( $assets ){
					$res                 = new stdClass();
					$res->name           = $remote->name;
					$res->slug           = $this->plugin_slug;
					$res->version        = $remote->tag_name;
					$res->tested         = '6.0';
					$res->requires       = '5.8';
					$res->author         = "<a href='https://lokuswp.id'>LWCommerce</a>";
					$res->author_profile = "https://lokuswp.id";
					if ( isset( $assets->browser_download_url ) ) {
						$res->download_url = $assets->browser_download_url;
						$res->trunk        = $assets->browser_download_url;
					}
					$res->sections = array(
						'description'  => 'Plugin Toko Online WordPress dari LokusWP', // description tab
						'installation' => 'Upload File, dan Aktifkan Plugin', // installation tab
						'changelog'    => $parsedown->text( $remote->body ), // changelog tab
					);
					$res->banners  = array(
						'low'  => 'https://lokuswp.id/wp-content/uploads/2022/04/lwcommerce-banner-772x250-1.jpg',
						'high' => 'https://lokuswp.id/wp-content/uploads/2022/04/lwcommerce-banner-772x250-1.jpg'
					);
				}

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

		// Available Transient
		if ( get_transient( $this->plugin_slug . '_update' ) ) {
			return false;
		}

		// Remote GET
		$server   = $this->plugin_host;
		$response = wp_remote_get(
			$server,
			array(
				'timeout' => 30,
				'headers' => array(
					'Accept' => 'application/json',
				)
			)
		);

		$rate_limit = wp_remote_retrieve_header( $response, 'X-RateLimit-Remaining' );

		if ( $rate_limit == 0 ) {
			//			Logger::info( "[Plugin][Updates] Github limit exceeded " );
			set_transient( $this->plugin_slug . '_update', 'failed_get_update', 600 ); // 10 minutes

			return $transient;
		}

		if ( is_wp_error( $response ) ) {
			// Failed to get remote
//			Logger::info( "[Plugin][Updates] Failed to get update, check your CURL " );
			set_transient( $this->plugin_slug . '_update', 'failed_get_update', 300 ); // Waiting 5 minutes

			return false;
		}

		$remote = json_decode( wp_remote_retrieve_body( $response ), false ) ?? null;

		if ( ! isset( $remote ) ) {
			set_transient( $this->plugin_slug . '_update', 'failed_get_update', 60 * 3 ); // 3 minutes

			return false;
		}

		//Get Response Body
		set_transient( $this->plugin_slug . '_update', $remote, 60 * 60 * 6 ); // 6 hours cache
//		Logger::info( "[Plugin][Updates] Successful Get Plugin Data Update " );

		return true;
	}

	public function plugin_updater_logic( $transient ) {

		// Get new update from remote
		$this->plugin_updater_host( $transient );

		// Transient Process
		$remote = (object) get_transient( $this->plugin_slug . '_update' );

		// failed update handler
		if ( isset( $remote->scalar ) && $remote->scalar === 'failed_get_update' ) {
			return $transient;
		}

		// update not found handler
		if ( isset( $remote->message ) && $remote->message === 'Not Found' ) {
			return $transient;
		}

		// Display Update Notice
		$remote_version = $remote->tag_name ?? null;
		$remote_version = str_replace( 'v', '', $remote_version );
		$assets    = isset($remote->assets) ? current( $remote->assets ) : null;

		if ( $assets && ! is_wp_error( $remote ) && version_compare( $this->plugin_version, $remote_version, '<' ) ) {
			$res              = new stdClass();
			$res->slug        = $this->plugin_slug;
			$res->plugin      = $this->plugin_file;
			$res->new_version = $remote_version;
			$res->tested      = '6.0.2';
			if ( isset( $assets->browser_download_url ) ) {
				$res->package = $assets->browser_download_url;
			}
			// TODO : ERROR
			$transient->response[ $this->plugin_file ] = $res;
        }else{
            $transient->response[$this->plugin_file] = null;
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
			printf( ' ' . __( 'Please download in %s Member Area %s to update', 'lwcommerce' ), '<a href="https://member.lokuswp.id/" target="_blank">', '</a>' );
		}
	}

	public function plugin_row( $links_array, $plugin_file_name, $plugin_data, $status ) {
		if ( strpos( $plugin_file_name, basename( $this->plugin_file ) ) ) {
			$links_array[] = '<a id="' . $this->plugin_slug . '" href="' . admin_url( 'plugins.php?manual-check=' . $this->plugin_slug ) . '">' . __( 'Check Update', 'lokuswp' ) . '</a>';
		}

		return $links_array;
	}
}

new Updater();