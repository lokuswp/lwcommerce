<?php
$apikey = lwc_get_settings( 'shipping', 'apikey' ) ?? '';
?>
<section id="settings" class="form-horizontal">
    <form>

        <!-- Name -->
        <div class="form-group">
            <div class="col-3 col-sm-12">
                <label class="form-label" for="name"><?php _e( 'Api Key', 'lwcommerce' ); ?></label>
            </div>
            <div class="col-5 col-sm-12">
                <input type="text" class="form-input" name="apikey" placeholder="80aa49704fc30a939124a83188agd625" value="<?= esc_attr( $apikey ) ?>"/>
            </div>
        </div>

        <button class="btn btn-primary w-120" id="lwpc-setting-shipping-save"><?php _e( 'Save', 'lwcommerce' ); ?></button>
    </form>
</section>