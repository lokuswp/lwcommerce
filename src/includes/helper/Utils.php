<?php

class Utils {
	/*****************************************
	 * Downloading LokusWP Backbone
	 * The Latest Version from Repository
	 *
	 * @return string
	 * @since 0.1.0
	 ***************************************
	 */
	public static function download_backbone() {
		$server = "https://digitalcraft.id/api/v1/product/plugin/update/lokuswp";
		$remote = wp_remote_get( $server,
			array(
				'timeout' => 30,
				'headers' => array(
					'Accept' => 'application/json',
				)
			)
		);

		// Checking Error
		if ( is_wp_error( $remote ) ) {
			return $remote->get_error_message();
		}

		$remote = json_decode( $remote['body'] );
		$result = $remote->data;

		// Only Download when Remote have Download URL and Plugin not Exist in folder
		if ( ! file_exists( WP_PLUGIN_DIR . "/lokuswp/lokuswp.php" ) && isset( $result->download_url ) ) {

			// Downloading Plugin
			( new Utils )->download_plugin( $result->download_url, "lokuswp" );
		} else {
			// Run Setup Wizard
			self::activate_plugin( "lokuswp" );
			echo "success_download_dependency";
		}

		wp_die();
	}

	private static function activate_plugin( $plugin_slug ): void {
		if ( ! is_plugin_active( "$plugin_slug/$plugin_slug.php" ) ) {
			$activated = activate_plugin( WP_PLUGIN_DIR . "/$plugin_slug/$plugin_slug.php", '', false, true );
			if ( is_wp_error( $activated ) ) {
				wp_send_json( "asdasd" );
				new \WP_Error( "failed_activate_plugin", "Plugin activation failed! Please activate manual the plugin." );
			}
		}
	}

	/*****************************************
	 * Download File via URL
	 * Using WordPress Function to Download and Unzipping File
	 *
	 * @param  string  $download_url
	 * @param  string  $plugin_slug
	 *
	 * @return Exception|void
	 * @since 0.1.0
	 ***************************************
	 */
	public function download_plugin( string $download_url, string $plugin_slug ) {

		// Download URL
		if ( ! file_exists( WP_PLUGIN_DIR . "/$plugin_slug/$plugin_slug.php" ) ) {

			// Defined WP File System
			WP_Filesystem();

			// Try Downloading File form url, Network Failed Test : Passed
			try {
				$tmp_file = download_url( $download_url, 300 );
				if ( is_wp_error( $tmp_file ) ) {
					throw new Exception( 'Could download file file' );
				}

				//ray( $plugin_slug );

				if ( ! copy( $tmp_file, WP_PLUGIN_DIR . '/' . $plugin_slug . '.zip' ) ) {
					throw new Exception( 'Could not copy file' );
				};
				unlink( $tmp_file ); // Delete Temp File

				// Unzip File in wp-content/plugins/plugin-name.zip to folder plugin-name/
				$unzip = unzip_file( WP_PLUGIN_DIR . '/' . $plugin_slug . '.zip', WP_PLUGIN_DIR );
				if ( is_wp_error( $unzip ) ) {
					throw new Exception( "Failed to Unzip File" );
				}

				// Delete downloaded file
				unlink( WP_PLUGIN_DIR . '/' . $plugin_slug . '.zip' ); // Delete zip file
				if ( file_exists( WP_PLUGIN_DIR . '/' . $plugin_slug . '.zip' ) ) {
					return new Exception( "Can't delete the file, because the file doesn't exist or can't be found." );
				}

				// Run Setup Wizard
				$this->activate_plugin( "lokuswp" );
				echo "success_download_dependency";
			} catch ( \Exception $e ) {
				die ( 'File did downloade: ' . $e->getMessage() );
			}

		} else { // Plugin Exist

			// Check Plugin Active Status
			$this->activate_plugin( $plugin_slug );

		}
	}
}