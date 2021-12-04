<?php

?>

<section id="settings" class="form-horizontal">
    <form>

        <?php
        // $payment_page = lsdd_get_settings( 'general_settings', 'payment_page', 'abs' );
        // $terms_page = lsdd_get_settings( 'general_settings', 'terms_page', 'abs' );
        // $popup_item = lsdd_get_settings( 'general_settings', 'popup_item', 'abs', 5);

        // $report_permission = lsdd_get_settings( 'general_settings', 'report_permission', 'array' );
        // $payment_instruction = lsdd_get_settings( 'general_settings', 'payment_instruction', 'esc_attr' );
        // $payment_confirmation = lsdd_get_settings( 'general_settings', 'payment_confirmation', 'esc_url' );
        // $currency = strtolower(lsdd_get_currency());
        // $countries = i18n::get_countries();
        // $query_page = new WP_Query(array('posts_per_page' => -1, 'post_type' => 'page', 'post_status' => 'publish'));
        
        $currencies = array(
            'IDR' => array(
                'ticker' => 'Rp ',
                'unit' => 'Rupiah',
                'country' => 'ID',
                'eg' => 'Rp 10.000'
            ),
            'USD' => array(
                'ticker' => '$',
                'unit' => 'Dollar',
                'country' => 'US',
                'eg' => '$5'
            ),
            'VEX' => array(
                'ticker' => 'VEX',
                'unit' => 'Vex',
                'country' => 'WW',
                'eg' => '1 VEX'
            ),
        );
        $base_currency = 'LOKI';
        ?>

        <!-- Base Currency -->
        <div class="form-group">
            <div class="col-3 col-sm-12">
                <label class="form-label" for="currency"><?php _e('Base Currency', 'lwpbackbone'); ?></label>
            </div>
            <div class="col-5 col-sm-12">
                <select class="form-select" name="currency">
                    <?php foreach ($currencies as $currency_id => $currency) : ?>
                        <option value="<?php echo $currency_id; ?>" <?php echo ($currency_id == $base_currency) ? 'selected' : ''; ?>>
                            <?php echo $currency['eg']; ?> ( <?php echo $currency['unit']; ?> )
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <button class="btn btn-primary" id="lwpbb_admin_save_settings" style="width:120px"><?php _e('Save', 'lwpbackbone'); ?></button>
    </form>
</section>