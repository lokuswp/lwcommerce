<!-- Tab : Shipping -->
<div id="lwpcommerce-shipping" class="swiper-slide">

    <form class="full-height">

        <?php
        $cart = isset($_COOKIE['lokuswp_cart']) ? json_decode(stripslashes($_COOKIE['lokuswp_cart'])) : array();

        $digital_shipping = false;
        $physical_shipping = false;

        if (isset($cart->items)) {
            foreach ($cart->items as $key => $item) {
                if (get_post_meta($item->post_id, '_product_type', true) == 'digital') {
                    $digital_shipping = true;
                }

                if (get_post_meta($item->post_id, '_product_type', true) == 'physical') {
                    $physical_shipping = true;
                }
            }
        }
        ?>

        <?php if ($digital_shipping) : ?>
            <h6 style="margin-bottom:12px;" class="text-primary"><?php _e('Digital Shipping', 'lwpcommerce'); ?></h6>

            <div class="row">
                <div class="col-sm-6 col-xs-12 gutter swiper-no-swiping">

                    <div class="lokuswp-form-group">
                        <div class="item-radio">
                            <input type="radio" name="email_courier" id="" checked>
                            <label class="svg-wrapper">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                    <polyline points="22,6 12,13 2,6"></polyline>
                                </svg>
                                <span><?php _e("Email", 'lwpcommerce'); ?></span>
                            </label>
                        </div>
                    </div>

                </div>
            </div>
        <?php endif; ?>

        <br>

        <?php if ($physical_shipping) : ?>
            <h6 style="margin-bottom:12px;" class="text-primary"><?php _e('Physical Shipping', 'lwpcommerce'); ?></h6>

            <?php
            $state_selected = lwpc_get_settings('store', 'state', 'intval');
            $get_states = lwp_get_remote_json(get_rest_url() . 'lwpcommerce/v1/rajaongkir/province', [], 'lokuswp_states', WEEK_IN_SECONDS);
            $states = $get_states->data ?? [];

            // $city_selected = lwpc_get_settings('store', 'city', 'intval');
            $city_selected = null;
            $get_cities = lwp_get_remote_json(get_rest_url() . 'lwpcommerce/v1/rajaongkir/city?province=' . $state_selected, [], 'lokuswp_cities_' . $state_selected, WEEK_IN_SECONDS);
            $cities = $get_cities->data ?? [];
            ?>
            <input type="text" id="country" value="ID" class="hidden">

            <div class="row">
                <div class="col-xs-12 col-sm-6 gutter">
                    <div class="form-group">
                        <select class="form-control custom-select swiper-no-swiping shipping-reset" id="states">
                            <option value=""><?php _e("Choose Province", 'lwpcommerce'); ?></option>
                            <?php foreach ($states as $key => $state) : ?>
                                <option value="<?php echo $state->province_id; ?>" <?php echo ($state->province_id == $state_selected) ? 'selected' : ''; ?>><?php echo $state->province; ?></option>
                            <?php
                            endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6 gutter">
                    <div class="form-group">

                        <select class="form-control custom-select swiper-no-swiping shipping-reset" id="cities">
                            <option value=""><?php _e("Choose City", 'lwpcommerce'); ?></option>
                            <?php foreach ($cities as $key => $city) : ?>
                                <option value="<?php echo $city->city_id; ?>" <?php echo ($city->city_id == $city_selected) ? 'selected' : ''; ?>><?php echo $city->type . ' ' . $city->city_name; ?></option>
                            <?php endforeach; ?>
                        </select>

                    </div>
                </div>

                <div class="col-xs-12 gutter">
                    <div class="form-group">
                        <textarea id="shipping_address" class="form-control swiper-no-swiping" placeholder="Alamat"></textarea>
                    </div>
                </div>
            </div>

            <!-- Shipping Services -->
            <section id="lwpcommerce-shipping-services"></section>

        <?php endif; ?>

    </form>

    <div class="bottom">
        <button id="verify-shipping" class="lwp-btn lokus-btn btn-primary btn-block swiper-no-swiping">
            <div class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" style="margin-top:-4px;" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shield">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                </svg>
            </div>
            <?php _e('Continue', 'lokuswp'); ?>
        </button>
    </div>

</div>

<script id="struct-shipping-services" type="x-template">
    <div class="row">
        {{#shippingChannel}}
            <div class="col-xs-12 col-sm-6 swiper-no-swiping gutter">
                <div class="lsdp-form-group">
                    <div class="item-radio" >
                        <input type="radio" name="shipping_channel" id="{{service}}" title="{{short_name}}" service="{{service}}" cost="{{cost}}" >
                        <label for="{{service}}}">
                            <img src="{{logo}}" alt="{{short_name}}">
                            <h6>{{short_name}} - {{service}}</h6>
                            <p>{{#currencyFormat}}{{cost}}{{/currencyFormat}}</p>
                        </label>
                    </div>
                </div>
            </div>
        {{/shippingChannel}}
    </div>
</script>