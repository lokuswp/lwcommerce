<!-- Tab : Shipping -->
<div id="lwpcommerce-shipping" class="swiper-slide">

    <form class="full-height">

        <h6 style="margin-bottom:12px;" class="text-primary"><?php _e('Digital Shipping Channel', 'lokuswp'); ?></h6>

        <div class="row">
            <div class="col-sm-6 col-xs-12 gutter swiper-no-swiping">

                <div class="lsdp-form-group">
                    <div class="item-radio">
                        <input type="radio" name="email_courier" id="" checked>
                        <label for="email">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                <polyline points="22,6 12,13 2,6"></polyline>
                            </svg>
                            <h6>Email</h6>
                        </label>
                    </div>
                </div>

            </div>
            <!-- <div class="col-sm-6 col-xs-12 gutter swiper-no-swiping">

                <div class="lsdp-form-group">
                    <div class="item-radio">
                        <input type="radio" name="whatsapp_courier" id="" checked>
                        <label for="email">
                            <img style="width:26px;" src="https://senderpad.com/wp-content/uploads/2021/11/senderpad.png" alt="">
                            <h6>Whatsapp</h6>
                        </label>
                    </div>
                </div>

            </div> -->
        </div>

        <h6 style="margin-bottom:12px;" class="text-primary"><?php _e('Physical Shipping Channel', 'lokuswp'); ?></h6>

        <?php
        $state_selected = 3;
        $request = wp_remote_get('http://lokuswp.local/wp-json/lokuswp/v1/rajaongkir/province');

        if (is_wp_error($request)) {
            return false; // Bail early
        }

        $body = wp_remote_retrieve_body($request);
        $states = json_decode($body)->data;

        $request2 = wp_remote_get('http://lokuswp.local/wp-json/lokuswp/v1/rajaongkir/city?province=3');

        if (is_wp_error($request2)) {
            return false; // Bail early
        }

        $body = wp_remote_retrieve_body($request2);
        $cities = json_decode($body)->data;


        ?>
        <input type="text" id="country" value="ID" class="hidden">

        <div class="row">
            <div class="col-sm-6 gutter">

                <div class="form-group">
                    <select class="form-control custom-select swiper-no-swiping shipping-reset" id="states">
                        <?php foreach ($states as $key => $state) : ?>
                            <option value="<?php echo $state->province_id; ?>" <?php echo ($state->province_id == $state_selected) ? 'selected' : ''; ?>><?php echo $state->province; ?></option>
                        <?php
                        endforeach; ?>
                    </select>
                </div>

            </div>
            <div class="col-sm-6 gutter">
                <div class="form-group">

                    <select class="form-control custom-select swiper-no-swiping shipping-reset" id="cities">
                        <option value=""><?php _e("Pilih Kota", 'lokuswp'); ?></option>
                        <?php foreach ($cities as $key => $city) : ?>
                            <option value="<?php echo $city->city_id; ?>"><?php echo $city->type . ' ' . $city->city_name; ?></option>
                        <?php endforeach; ?>
                    </select>

                </div>
            </div>
            <!-- RajaOngkir Pro -->
            <!-- <div class="col-sm-12 gutter">

                <div class="form-group">
                    <select name="" class="form-control custom-select">
                        <option>Pasar Kemis</option>
                    </select>
                </div>

                <div class="form-group">
                    <textarea id="shipping_address" class="form-control swiper-no-swiping" placeholder="Alamat"></textarea>
                </div>

            </div> -->
        </div>

        <!-- Shipping Options -->
        <section id="lwpcommerce-shipping-channel">
        </section>

    </form>

    <div class="bottom">
        <button class="lwp-btn lsdc-btn btn-primary btn-block swiper-no-swiping">
            <div class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" style="margin-top:-4px;" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shield">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                </svg>
            </div>
            <?php _e('Continue', 'lokuswp'); ?>
        </button>
    </div>

</div>




<script id="struct-shipping-channel" type="x-template">
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