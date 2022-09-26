<?php

namespace LokusWP\Commerce\Modules\Product;

if ( ! defined( 'WPTEST' ) ) {
	defined( 'ABSPATH' ) or die( "Direct access to files is prohibited" );
}

class Metabox_Product {
	public function __construct() {
		add_filter( 'add_meta_boxes', [ $this, 'metabox_register' ], 0 );
		add_filter( 'admin_post_thumbnail_html', [ $this, 'thumbnail_recommendation' ], 10, 3 );

		add_action( 'save_post', [ $this, 'metabox_save' ] );
		add_action( 'new_to_publish', [ $this, 'metabox_save' ] );
	}

	/**
	 * Add Thumbnail Recommendation Size in Product
	 *
	 * Thanks to jeremyescott
	 * @return html
	 * @link https://stackoverflow.com/questions/30817906/wordpress-add-description-below-to-featured-image
	 *
	 * @since 0.5.0
	 * @author jeremyescott
	 */
	public function thumbnail_recommendation( $content, $post_id, $thumbnail_id ) {
		if ( 'product' !== get_post_type( $post_id ) ) {
			return $content;
		}
		$caption = '<p>' . esc_html__( 'Recommended image size: ', 'lwcommerce' ) . '<strong>800x800px</strong></p>';

		return $content . $caption;
	}

	public function metabox_register() {
		add_meta_box(
			'product-metabox',
			__( 'Product Data', 'lwcommerce' ),
			[ $this, 'metabox_product_data' ],
			'product',
			'normal',
			'high'
		);
	}

	public function metabox_product_format() {
		global $post;
		?>

        <div class="tabs-component">
            <input type="radio" name="tab" id="tab1" checked="checked"/>
            <label class="tab" for="tab1"><?php esc_attr_e( 'Digital', 'lwcommerce' ); ?></label>

            <input type="radio" name="tab" id="tab2"/>
            <label class="tab" for="tab2"><?php esc_attr_e( 'Physical', 'lwcommerce' ); ?></label>

            <div class="tab-body-component">
                <div id="tab-body-1" class="tab-body">
                    <!-- Hookable :: Extending for Upload via DropBox -->
					<?php if ( has_action( "lwcommerce/product/digital/upload" ) ) : ?>
						<?php do_action( 'lwcommerce/product/digital/upload' ); ?>
					<?php else : ?>
                        <br>
                        <label for="digital_file" style="margin-left:10px;"><?php esc_attr_e( 'File', 'lwcommerce' ); ?>
                            : </label>
                        <input type="text" class="form-input" style="width:50%" name="digital_file_url"
                               placeholder="http://dropbox.com/file.zip"
                               value="<?php echo get_post_meta( $post->ID, '_digital_file_url', true ); ?>">

                        <label for="digital_file_version"><?php esc_attr_e( 'Versi', 'lwcommerce' ); ?> :</label>
                        <input type="text" class="form-input" name="digital_file_version" placeholder="1.0.0"
                               value="<?php echo get_post_meta( $post->ID, '_digital_file_version', true ); ?>">


					<?php endif; ?>

                    <!-- Hookable :: Extending for More Information Digital -->
					<?php do_action( 'lwcommerce/product/digital' ); ?>
                </div>

                <div id="tab-body-2" class="tab-body">
                    <div class="pane-metabox">
                        <label for="physical_weight">
							<?php esc_attr_e( 'Berat', 'lwcommerce' ); ?> /g :
                        </label>
                        <input type="text" class="form-input currency" name="physical_weight" placeholder="50"
                               value="<?php echo get_post_meta( $post->ID, '_physical_weight', true ); ?>">
                        <label for="physical_volume"
                               style="margin-left:10px"><?php esc_attr_e( 'Volume', 'lwcommerce' ); ?> /cm : </label>
                        <input type="text" class="form-input currency" name="physical_volume" placeholder="300"
                               value="<?php echo get_post_meta( $post->ID, '_physical_volume', true ); ?>">
                    </div>
					<?php $shipping_type = empty( get_post_meta( $post->ID, '_shipping_type', true ) ) ? 'digital' : get_post_meta( $post->ID, '_shipping_type', true ); ?>
                    <script>
                        jQuery('input[value="<?php echo esc_attr( $shipping_type ); ?>"]').prop("checked", true);
                    </script>
                </div>
            </div>
        </div>

        <style>
            .tabs-component input[type=radio] {
                display: none !important;
            }

            .tabs-component [type=radio]:checked + label.tab,
            .tabs-component [type=radio]:not(:checked) + label.tab {
                padding-left: 0;
            }

            .tabs-component label.tab {
                display: inline-block;
                cursor: pointer;
                padding: 10px 20px !important;
                text-align: center;
            }

            .tabs-component label.tab:after,
            .tabs-component label.tab:before {
                display: none;
            }

            .tabs-component label.tab:last-of-type {
                border-bottom: none
            }

            .tabs-component label.tab:hover {
                background: #eee
            }

            .tabs-component input[type=radio]:checked + label.tab {
                border-bottom: 3px solid #000;
                margin: 0;
                margin-bottom: 2px;
            }

            .tabs-component .tab-body {
                position: absolute;
                opacity: 0;
                padding: 20px 0;
            }

            .tab-body-component {
                border-top: #ddd 3px solid;
                margin-top: -5px;
                position: initial
            }

            #tab1:checked ~ .tab-body-component #tab-body-1,
            #tab2:checked ~ .tab-body-component #tab-body-2 {
                position: relative;
                top: 0;
                opacity: 1
            }
        </style>

		<?php
	}

	public function metabox_product_data() {
		global $post;
		wp_nonce_field( basename( __FILE__ ), 'lwc_admin_nonce' );

		// Product Data
		$unit_price  = get_post_meta( $post->ID, '_unit_price', true ) == null ? null : lwp_currency_format( false, lwc_get_unit_price( $post->ID ) );
		$price_promo = get_post_meta( $post->ID, '_promo_price', true ) == null ? null : lwp_currency_format( false, lwc_get_promo_price( $post->ID ) );

        $btn_cart_link  = get_post_meta( $post->ID, '_btn_cart_link', true ) == null ? null : esc_attr(get_post_meta( $post->ID, '_btn_cart_link', true ));
        $btn_cart_text = get_post_meta( $post->ID, '_btn_cart_text', true ) == null ? __( 'Add to Cart', 'lwcommerce' ) : esc_attr( get_post_meta( $post->ID, '_btn_cart_text', true ) );

		$sku_code   = get_post_meta( $post->ID, '_sku_code', true ) == null ? null : esc_attr( get_post_meta( $post->ID, '_sku_code', true ) );
		$stock      = get_post_meta( $post->ID, '_stock', true ) == null ? null : abs( get_post_meta( $post->ID, '_stock', true ) );
		$stock_unit = get_post_meta( $post->ID, '_stock_unit', true ) == null ? null : esc_attr( get_post_meta( $post->ID, '_stock_unit', true ) );

		$stock_type = get_post_meta( $post->ID, '_stock_type', true ) == null ? null : esc_attr( get_post_meta( $post->ID, '_stock_type', true ) );

		$manufacture_time      = get_post_meta( $post->ID, '_manufacture_time', true ) == null ? null : esc_attr( get_post_meta( $post->ID, '_manufacture_time', true ) );
		$manufacture_time_unit = get_post_meta( $post->ID, '_manufacture_time_unit', true ) == null ? null : esc_attr( get_post_meta( $post->ID, '_manufacture_time_unit', true ) );


		$product_data_args = [
			'post_id'               => $post->ID,
			'unit_price'            => $unit_price,
			'price_promo'           => $price_promo,
            '_btn_cart_link'        => $btn_cart_link,
            '_btn_cart_text'        => $btn_cart_text,
			'stock_type'            => $stock_type,
			'sku_code'              => $sku_code,
			'stock'                 => $stock,
			'stock_unit'            => $stock_unit,
			'manufacture_time'      => $manufacture_time,
			'manufacture_time_unit' => $manufacture_time_unit,
		];

		$metabox_data = __DIR__ . '/metabox/product-data.php';
		load_template( $metabox_data, true, $product_data_args );


		// Product Type
		$product_type = get_post_meta( $post->ID, '_product_type', true ) == null ? null : esc_attr( get_post_meta( $post->ID, '_product_type', true ) );

		// Digital
		$attachment_link    = get_post_meta( $post->ID, '_attachment_link', true ) == null ? null : esc_url( get_post_meta( $post->ID, '_attachment_link', true ) );
		$attachment_version = get_post_meta( $post->ID, '_attachment_version', true ) == null ? null : esc_attr( get_post_meta( $post->ID, '_attachment_version', true ) );

		// Physical
		$weight = get_post_meta( $post->ID, '_weight', true ) == null ? null : intval( get_post_meta( $post->ID, '_weight', true ) );
		$length = get_post_meta( $post->ID, '_length', true ) == null ? null : intval( get_post_meta( $post->ID, '_length', true ) );
		$width  = get_post_meta( $post->ID, '_width', true ) == null ? null : intval( get_post_meta( $post->ID, '_width', true ) );
		$height = get_post_meta( $post->ID, '_height', true ) == null ? null : intval( get_post_meta( $post->ID, '_height', true ) );

		$product_type_args = [
			'product_type'       => $product_type,
			'attachment_link'    => $attachment_link,
			'attachment_version' => $attachment_version,
			'weight'             => $weight,
			'length'             => $length,
			'width'              => $width,
			'height'             => $height,
		];
		$metabox_type      = __DIR__ . '/metabox/product-type.php';
		load_template( $metabox_type, true, $product_type_args );
	}

	public function metabox_save( $post_id ) {
		if ( ! isset( $_POST['lwc_admin_nonce'] ) || ! wp_verify_nonce( $_POST['lwc_admin_nonce'], basename( __FILE__ ) ) ) {
			return 'Nonce not Verified';
		}

		if ( wp_is_post_autosave( $post_id ) ) // Check AutoSave
		{
			return 'autosave';
		}

		if ( wp_is_post_revision( $post_id ) ) // Check Revision
		{
			return 'revision';
		}

		if ( 'product' == $_POST['post_type'] ) // Checking Posttype
		{
			if ( ! current_user_can( 'edit_page', $post_id ) ) {  // Check Permission Role
				return 'cannot edit page';
			}
		} elseif ( ! current_user_can( 'edit_post', $post_id ) ) { // Check Permission Role
			return 'cannot edit post';
		}

		// Pricing
		update_post_meta( $post_id, '_unit_price', empty( $_POST['_unit_price'] ) ? 0 : lwp_currency_to_number( $_POST['_unit_price'] ) );
		update_post_meta( $post_id, '_promo_price', empty( $_POST['_promo_price'] ) ? null : lwp_currency_to_number( $_POST['_promo_price'] ) );

        update_post_meta( $post_id, '_btn_cart_text', empty( $_POST['_btn_cart_text'] ) ? 0 : sanitize_text_field( $_POST['_btn_cart_text'] ) );
        update_post_meta( $post_id, '_btn_cart_link', empty( $_POST['_btn_cart_link'] ) ? null : sanitize_text_field( $_POST['_btn_cart_link'] ) );

		// Stock
		update_post_meta( $post_id, '_sku_code', empty( $_POST['_sku_code'] ) ? null : sanitize_text_field( $_POST['_sku_code'] ) );
		update_post_meta( $post_id, '_stock', empty( $_POST['_stock'] ) ? 0 : abs( $_POST['_stock'] ) );
		update_post_meta( $post_id, '_stock_unit', empty( $_POST['_stock_unit'] ) ? "pcs" : sanitize_text_field( $_POST['_stock_unit'] ) );

		update_post_meta( $post_id, '_stock_type', empty( $_POST['_stock_type'] ) ? "ready" : sanitize_text_field( $_POST['_stock_type'] ) );
		update_post_meta( $post_id, '_manufacture_time', empty( $_POST['_manufacture_time'] ) ? "0" : sanitize_text_field( $_POST['_manufacture_time'] ) );
		update_post_meta( $post_id, '_manufacture_time_unit', empty( $_POST['_manufacture_time_unit'] ) ? "minutes" : sanitize_text_field( $_POST['_manufacture_time_unit'] ) );

		// Product Type
		update_post_meta( $post_id, '_product_type', empty( $_POST['_product_type'] ) ? "physical" : sanitize_text_field( $_POST['_product_type'] ) );

		// Digital Property
		update_post_meta( $post_id, '_attachment_link', sanitize_text_field( $_POST['_attachment_link'] ) );
		update_post_meta( $post_id, '_attachment_version', sanitize_text_field( $_POST['_attachment_version'] ) );

		// Physical Property
		update_post_meta( $post_id, '_weight', empty( $_POST['_weight'] ) ? 0 : abs( $_POST['_weight'] ) );
		update_post_meta( $post_id, '_length', empty( $_POST['_length'] ) ? 0 : abs( $_POST['_length'] ) );
		update_post_meta( $post_id, '_width', empty( $_POST['_width'] ) ? 0 : abs( $_POST['_width'] ) );
		update_post_meta( $post_id, '_height', empty( $_POST['_height'] ) ? 0 : abs( $_POST['_height'] ) );
//		update_post_meta( $post_id, '_volume', abs( $_POST['_length'] ) * abs( $_POST['_width'] ) * abs( $_POST['_height'] ) );

		// Do Action
		do_action( 'lwcommerce/product/data/save', $post_id, $_POST );
	}
}