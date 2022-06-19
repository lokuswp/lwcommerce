<?php
/*****************************************
 * Shipping Tab Content
 *
 * @since 0.1.0
 *****************************************
 */
?>
<div id="lwcommerce-shipping" class="swiper-slide">
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

		<?php if ( $physical_shipping ) : ?>

            <input type="hidden" id="user-address">

            <div class="row">

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
        <div class="col-xs-12 col-sm-6 swiper-no-swiping gutter mb-2">
            <div class="lwp-form-group">
                <div class="item-radio">
                    <input type="radio"
                           name="shipping_channel"
                           id="{{id}}"
                           title="{{name}}"
                           service="{{service}}"
                           cost="{{cost}}">
                    <label for="{{id}}}">
                        <img src="{{logoURL}}" alt="{{name}}">
                        <h6>{{name}} - {{service}}</h6>
                        <p>{{#currencyFormat}}{{cost}}{{/currencyFormat}}</p>
                        <p>{{#description}}{{description}}{{/description}}</p>
                    </label>
                </div>
            </div>
        </div>
        {{/shippingServices}}
    </div>
</script>