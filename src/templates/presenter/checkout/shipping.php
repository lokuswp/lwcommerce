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

                /*.warp-show {*/
                /*    display: block;*/
                /*}*/

                .center {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                }

                #pickup-time h6 {
                    margin: 0;
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
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="1.5" stroke="currentColor" class="w-6 h-6"
                                             style="width: 22px;margin-right: 12px;">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
                                        </svg>
                                        <h6 style="margin-bottom:0;line-height:normal;margin-top:0;padding:6px 0;"><?= __( "Self Pickup", "lwcommerce" ); ?></h6>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
				<?php endif; ?>

				<?php //if ( $shipping_carriers['rajaongkir-jne'] == "on" ) : ?>
                    <div class="swiper-no-swiping gutter" style="width: 100%">
                        <div class="lwp-form-group">
                            <div class="item-radio">
                                <input type="radio"
                                       name="shipping_type"
                                       id="shipping">
                                <label for="shipping">
                                    <div class="row center">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"   style="width: 22px;margin-right: 12px;" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                                        </svg>

                                        <h6 style="margin-bottom:0;line-height:normal;margin-top:0;padding:6px 0;">
                                            Delivery</h6>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
				<?php // endif; ?>

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
                                    <h6>20 menit</h6>
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
                    <h6 style="margin-bottom:12px;" class="text-primary"><?= __( "Address", "lwcommerce" ); ?></h6>
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
            <section id="lwcommerce-shipping-services" class="wrap-hide"></section>

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
                                <h6 style="margin-top:4px;margin-bottom:4px;line-height:normal;">{{name}} -
                                    {{service}}</h6>
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