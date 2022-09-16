<?php
/*****************************************
 * Shipping Tab Content
 *
 * @since 0.1.0
 *****************************************
 */
?>
<div id="lwcommerce-shipping" class="swiper-slide">

    <h6 style="margin-bottom:12px;" class="text-primary"><?= __( "Choose Shipping", "lwcommerce" ); ?></h6>
    <form class="full-height">
		<?php
		$cart = isset( $_COOKIE['lokuswp_cart'] ) ? json_decode( stripslashes( $_COOKIE['lokuswp_cart'] ) ) : array();

		$digital_shipping  = false;
		$physical_shipping = false;

		if ( isset( $cart->items ) ) {
			foreach ( $cart->items as $key => $item ) {
				if ( get_post_meta( $item->post_id, '_product_type', true ) == 'digital' ) {
					$digital_shipping = true;
				}

				if ( get_post_meta( $item->post_id, '_product_type', true ) == 'physical' ) {
					$physical_shipping = true;
				}
			}
		}
		?>

		<?php if ( $physical_shipping ) :
			$shipping_carriers = lwp_get_option( 'shipping_manager' );
			?>

            <style>
                #address-field .form-group {
                    display: inline-flex;
                    width: 100%;
                }

                #shipping-type {
                    flex-wrap: nowrap;
                }

                .warp-hide {
                    display: none;
                }

                .warp-show {
                    display: flex;
                }

                .center {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                }
            </style>

            <!-- Shipping Type -->
            <div class="row" id="shipping-type">

				<?php if ( $shipping_carriers['pickup'] == "on" ) : ?>
                    <div class="swiper-no-swiping gutter" style="width: 100%">
                        <div class="lwp-form-group">
                            <div class="item-radio">
                                <input type="radio"
                                       name="shipping_type"
                                       id="pickup"
                                       title="pickup"
                                       service="reguler"
                                       cost="0" checked>
                                <label for="pickup">
                                    <div class="row center">
                                        <img src="<?= LWC_URL . 'src/admin/assets/images/pickup.png' ?>" alt="takeaway">
                                        <h6>Pickup</h6>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
				<?php endif; ?>

				<?php if ( $shipping_carriers['rajaongkir-jne'] == "on" ) : ?>
                    <div class="swiper-no-swiping gutter" style="width: 100%">
                        <div class="lwp-form-group">
                            <div class="item-radio">
                                <input type="radio"
                                       name="shipping_type"
                                       id="shipping">
                                <label for="shipping">
                                    <div class="row center">
                                        <img src="<?= LWC_URL . 'src/public/assets/images/shipping.jpg' ?>" alt="takeaway">
                                        <h6 style="margin-bottom:0;line-height:normal;margin-top:0;">Delivery</h6>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
				<?php endif; ?>

            </div>

            <div class="row warp-show swiper-no-swiping" id="pickup-time">
                <div class="col-xs-12 gutter">
                    <h5>Pilih waktu ambil pesanan</h5>
                </div>

                <!--20 minutes-->
                <div class="col-xs-4 gutter">
                    <div class="lwp-form-group">
                        <div class="item-radio">
                            <input type="radio"
                                   name="time_pickup"
                                   id="20min"
                                   title="20 minutes"
                                   checked>
                            <label for="20min">
                                <div class="row center">
                                    <h6>20 min</h6>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <!--1 hour-->
                <div class="col-xs-4 gutter">
                    <div class="lwp-form-group">
                        <div class="item-radio">
                            <input type="radio"
                                   name="time_pickup"
                                   id="1hour"
                                   title="1 hour"
                            >
                            <label for="1hour">
                                <div class="row center">
                                    <h6>1 jam</h6>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <!--3 hour-->
                <div class="col-xs-4 gutter">
                    <div class="lwp-form-group">
                        <div class="item-radio">
                            <input type="radio"
                                   name="time_pickup"
                                   id="3hour"
                                   title="3 hour"
                            >
                            <label for="3hour">
                                <div class="row center">
                                    <h6>3 jam</h6>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 gutter">
                    <p>Setelah pembayaran berhasil dan terverifikasi</p>
                </div>

            </div>

            <input type="hidden" id="user-address">

            <div class="row warp-hide" id="address-field">

				<?php do_action( "lwcommerce/shipping/delivery/header" ) ?>

                <div class="col-xs-12 gutter">
                    <span>Alamat</span>
                </div>

                <!-- State -->
                <div class="col-xs-12 col-sm-6 gutter">
                    <div class="form-group">
                        <label for="states"></label>
                        <select class="form-control custom-select swiper-no-swiping shipping-reset" id="states">
                            <option value=""><?php _e( "Choose Province", 'lwcommerce' ); ?></option>
                        </select>
                    </div>
                </div>

                <!-- City -->
                <div class="col-xs-12 col-sm-6 gutter">
                    <div class="form-group">
                        <label for="cities"></label>
                        <select class="form-control custom-select swiper-no-swiping shipping-reset" id="cities">
                            <option value=""><?php _e( "Choose City", 'lwcommerce' ); ?></option>
                        </select>
                    </div>
                </div>

                <!-- Address -->
                <div class="col-xs-12 gutter">
                    <div class="form-group">
                        <label for="shipping_address"></label>
                        <textarea id="shipping_address" class="form-control swiper-no-swiping"
                                  placeholder="<?php _e( "Address", "lwcommerce" ); ?>"></textarea>
                    </div>
                </div>
            </div>

            <!-- Shipping Services -->
            <section id="lwcommerce-shipping-services"></section>

			<?php do_action( "lwcommerce/shipping/delivery/footer" ) ?>

		<?php endif; ?>
    </form>

    <div class="bottom">
        <button id="lwc-verify-shipping" class="lwp-btn lokus-btn btn-primary btn-block swiper-no-swiping">
            <div class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" style="margin-top:-4px;" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" class="feather feather-shield">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                </svg>
            </div>
			<?php _e( 'Continue', 'lokuswp' ); ?>
        </button>
    </div>

</div>
<!-- Template Shipping -->
<script id="struct-shipping-services" type="x-template">
    <div class="row">
        {{#shippingServices}}
        <div class="col-xs-12 col-sm-12 swiper-no-swiping gutter">
            <div class="lwp-form-group">
                <div class="item-radio">
                    <input type="radio"
                           name="shipping_channel"
                           id="{{id}}"
                           title="{{name}}"
                           service="{{service}}"
                           cost="{{cost}}">
                    <label for="{{id}}}">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="img" style="padding-right: 12px;height: 50px;">
                                    <img src="{{logoURL}}" alt="{{name}}">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <h6 style="margin-bottom:0;line-height:normal;">{{name}} - {{service}}</h6>
                                <p>{{#description}}{{description}}{{/description}}</p>
                            </div>
                            <div class="col-sm-3" style="text-align: center">
                                <p style="padding:8px">{{#currencyFormat}}{{cost}}{{/currencyFormat}}</p>
                            </div>
                        </div>
                    </label>
                </div>
            </div>
        </div>
        {{/shippingServices}}
    </div>
</script>