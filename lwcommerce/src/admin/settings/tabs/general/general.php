<?php
/*****************************************
 * Free Shipping
 * Shipping Method for Free Shipping without condition
 *
 * @since 0.1.0
 *****************************************
 */

$default_template = 'Hi, Saya sudah pesan
ID Pesanan : *#{{order_id}}*

*Detail Pesanan*
{{summary}}

*Pembayaran*
{{payment}}

Tolong segera diproses ya min,
{{order_link}}

ini bukti pembayarannya';
$checkout_template  = lwp_get_settings( 'lwcommerce', 'general', 'checkout_template' );
$checkout_template = empty( $checkout_template ) ? $default_template : $checkout_template;
?>


<style>
    #settings .form-input,
    #settings .btn {
        max-width: 500px;
        display: block;
    }
</style>
<section id="settings" class="form-horizontal">
    <form>

        <!-- General -->
        <div class="form-group">
            <div class="col-3 col-sm-12">
                <label class="form-label" for="checkout_template"><?php _e( 'Whatsapp Checkout Template', 'lwcommerce' ); ?></label>
            </div>
            <div class="col-9 col-sm-12">
                <textarea class="form-input" name="checkout_template" placeholder="<?= $default_template; ?>"
                          rows="12"><?php echo $checkout_template; ?></textarea>
            </div>
        </div>

        <br>
        <button class="btn btn-primary w-120" id="lokuswp_admin_settings_save" app="lwcommerce" option="general" ><?php _e( 'Save', 'lwcommerce' ); ?></button>
    </form>
</section>
