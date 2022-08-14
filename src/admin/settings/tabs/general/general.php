<?php
/*****************************************
 * Free Shipping
 * Shipping Method for Free Shipping without condition
 *
 * @since 0.1.0
 *****************************************
 */

$default_checkout_template = 'Hi, Saya sudah pesan
ID Pesanan : *#{{order_id}}*

*Detail Pesanan*
{{summary}}

*Pembayaran*
{{payment}}

Tolong segera diproses ya min,
{{order_link}}

ini bukti pembayarannya';

$default_followup_template = 'Hi *{{name}}*

Kami ingin mengingatkan terkait pesanan Anda
Yang masih belum diselesaikan
ID Pesanan : *#{{order_id}}*

*Detail Pesanan* :
{{summary}}

*Pembayaran* :
{{payment}}

_Jika ada yang ingin ditanyakan,_
_silahkan balas pesan ini_

Terimakasih
*{{brand_name}}*
';

$whatsapp_checkout_template  = lwp_get_settings( 'lwcommerce', 'general', 'checkout_template' );
$whatsapp_checkout_template = empty( $whatsapp_checkout_template ) ? $default_checkout_template : $whatsapp_checkout_template;

$whatsapp_followup_template  = lwp_get_settings( 'lwcommerce', 'general', 'followup_template' );
$whatsapp_followup_template = empty( $whatsapp_followup_template ) ? $default_followup_template : $whatsapp_followup_template;
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
                <textarea class="form-input" name="checkout_template" placeholder="<?= $default_checkout_template; ?>"
                          rows="12"><?php echo $whatsapp_checkout_template; ?></textarea>
            </div>
        </div>

        <!-- Follow UP -->
        <div class="form-group">
            <div class="col-3 col-sm-12">
                <label class="form-label" for="followup_template"><?php _e( 'Whatsapp Follow Up Template', 'lwcommerce' ); ?></label>
            </div>
            <div class="col-9 col-sm-12">
                <textarea class="form-input" name="followup_template" placeholder="<?= $default_followup_template; ?>"
                          rows="12"><?php echo $whatsapp_followup_template; ?></textarea>
            </div>
        </div>

        <br>
        <button class="btn btn-primary w-120" id="lokuswp_admin_settings_save" app="lwcommerce" option="general" ><?php _e( 'Save', 'lwcommerce' ); ?></button>
    </form>
</section>
