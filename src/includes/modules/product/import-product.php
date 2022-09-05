<?php

namespace LokusWP\Commerce\Modules\Product;

if ( ! defined( 'WPTEST' ) ) {
	defined( 'ABSPATH' ) or die( "Direct access to files is prohibited" );
}

class Import_Product {
	public function __construct() {
		add_action( 'manage_posts_extra_tablenav', [ $this, 'button_import' ] );
	}

	public function button_import( $where ) {
		global $post;
		wp_enqueue_script( 'import-product' );
		wp_enqueue_style( 'wp-jquery-ui-dialog' );
		if ( $where === 'bottom' ) {
			return false;
		}
		if ( $post->post_type !== 'product' ) {
			return false;
		}
		?>
        <style>
            .import {
                display: flex !important;
                align-items: center;
                gap: .5rem;
                width: 100%;
            }

            .body-dialog-import {
                display: flex;
                flex-direction: column;
            }

            .import-group {
                display: flex;
                flex-direction: row;
                gap: .5rem
            }

            .input-file {
                border-style: dashed !important;
            }

            .input-file input {
                display: none;
            }
        </style>

        <button class="button">
            <div class="import">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="height: 1rem; width: 1rem">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 8.25H7.5a2.25 2.25 0 00-2.25 2.25v9a2.25 2.25 0 002.25 2.25h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25H15m0-3l-3-3m0 0l-3 3m3-3V15"/>
                </svg>

				<?php _e( 'Import Product', 'lwcommerce' ) ?>
            </div>
        </button>

        <div id="dialog" title="<?php _e( 'Import Product Here', 'lwcommerce' ) ?>">
            <div class="body-dialog-import">
                <div class="import-group">
                    <a href="https://docs.google.com/spreadsheets/d/1-Y_28drqpOkgtZ9Y3jSPA-vf_WdEMKerEG0HlK1081Q/export?gid=0&format=xlsx" target="_blank" class="button">Download Template Import</a>
                    <label for="csv-upload" class="input-file button">
                        <span>Select file</span>
                        <input id="csv-upload" type="file" name="backup" accept=".csv"/>
                    </label>
                </div>
                <p><?php _e( 'file format must be', 'lwcommerce' ) ?> <strong>.csv</strong></p>
            </div>
        </div>
		<?php
	}
}