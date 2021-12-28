<!-- Tab : Shipping -->
<div id="shipping" class="swiper-slide">

    <form id="lwpbackbone-shipping" class="full-height">
        <h6 style="margin-bottom:12px;" class="text-primary">Pengiriman Digital</h6>

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
            <div class="col-sm-6 col-xs-12 gutter swiper-no-swiping">

                <div class="lsdp-form-group">
                    <div class="item-radio">
                        <input type="radio" name="whatsapp_courier" id="" checked>
                        <label for="email">
                            <img style="width:26px;" src="https://senderpad.com/wp-content/uploads/2021/11/senderpad.png" alt="">
                            <h6>Whatsapp</h6>
                        </label>
                    </div>
                </div>

            </div>
        </div>

        <h6 style="margin-bottom:12px;" class="text-primary">Pengiriman Fisik</h6>

        <?php
        $states = json_decode(file_get_contents(LWPBB_PATH . 'src/assets/cache/ID-states.json'));
        $cities = json_decode(file_get_contents(LWPBB_PATH . 'src//assets/cache/ID-cities.json'));
        $state_selected = 1;
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
                        <option value=""><?php _e("Pilih Kota", 'lwpbackbone'); ?></option>
                        <?php foreach ($cities as $key => $city) : ?>
                            <?php if ($city->province_id == $state_selected) : ?>
                                <option value="<?php echo $city->city_id; ?>"><?php echo $city->type . ' ' . $city->city_name; ?></option>
                            <?php
                            endif; ?>
                        <?php
                        endforeach; ?>
                    </select>

                </div>
            </div>
            <div class="col-sm-12 gutter">

                <div class="form-group">
                    <select name="" class="form-control custom-select">
                        <option>Pasar Kemis</option>
                    </select>
                </div>

                <div class="form-group">
                    <textarea id="shipping_address" class="form-control swiper-no-swiping" placeholder="Alamat"></textarea>
                </div>

            </div>
        </div> -->


        <!-- Shipping Options -->
        <div class="row">
            <div class="col-xs-12 col-sm-6 swiper-no-swiping gutter">

                <div class="lsdp-form-group">
                    <div class="item-radio">
                        <input type="radio" name="physical_courier">
                        <label for="jne">
                            <img src="<?php echo plugins_url('/src/assets/courier/jne.png', LWP_BASE); ?>" alt="JNE">
                            <h6>JNE OKE ( 1 - 2 Hari )</h6>
                            <p>Rp15.000</p>
                        </label>
                    </div>
                </div>

            </div>

            <div class="col-xs-12 col-sm-6 swiper-no-swiping gutter">

                <div class="lsdp-form-group">
                    <div class="item-radio">
                        <input type="radio" name="physical_courier" checked>
                        <label for="jne">
                            <img src="<?php echo plugins_url('/src/assets/courier/jne.png', LWP_BASE); ?>" alt="JNE">
                            <h6>JNE REG ( 3 - 4 Hari )</h6>
                            <p>Rp10.000</p>
                        </label>
                    </div>
                </div>

            </div>
        </div>

        <div class="bottom">
            <button class="lwp-btn lsdc-btn btn-primary btn-block swiper-no-swiping">
                <div class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" style="margin-top:-4px;" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shield">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    </svg>
                </div>
                <?php _e('Continue', 'lwpbackbone'); ?>
            </button>
        </div>
    </form>
</div>