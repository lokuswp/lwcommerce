<?php

class Admin_Notice_Backbone {
	public function __construct() {
		add_action( 'admin_notices', [ $this, 'admin_notice_check_backbone' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'wp_ajax_lwcommerce_download_backbone', [ $this, 'download_backbone' ] );
	}

	public function admin_notice_check_backbone() {
		?>
        <div class="notice notice-warning is-dismissible">
            <p><?php _e( 'It seems like plugin LokusWP (Backbone) not installed or deleted.', 'lwcommerce' ); ?></p>
            <p><a href="#" id="admin-notice-download-backbone"><?php _e( 'Click here', 'lwcommerce' ); ?></a> <?php _e( 'to install the plugin', 'lwcommerce' ); ?></p>
        </div>
		<?php
	}

	public function enqueue_scripts() {
		// Admin js
		wp_enqueue_script( 'admin-lwcommerce', LWC_URL . 'src/admin/assets/js/admin.js', array(
			'jquery',
		), LWC_VERSION, false );
		wp_localize_script( 'admin-lwcommerce', 'lwc_admin_all', array(
			'admin_url'  => admin_url(),
			'ajax_url'   => admin_url( 'admin-ajax.php' ),
			'ajax_nonce' => wp_create_nonce( 'lwc_admin_all_nonce' ),
		) );
	}

	public function download_backbone() {
		require_once LWC_PATH . "src/includes/helper/Utils.php";
		\Utils::download_backbone();
	}
}

new Admin_Notice_Backbone();