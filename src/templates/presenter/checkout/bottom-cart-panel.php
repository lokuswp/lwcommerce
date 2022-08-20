<div class="floating-bottom">
    <div id="lwcommerce-bottom-cart-panel">
        <div class="lwp-cart-action row">
            <div class="col-xs-8">
                <h5 style="margin:0 0 2px 0;font-size:16px;"><?php _e("Cart", "lokuswp"); ?></h5>
                <lwp-cart-total></lwp-cart-total>
            </div>

            <div class="col-xs-4 end-xs" style="margin-top:4px;">
                <a style="font-weight:600"
                   href="<?php echo get_permalink(lwp_get_settings('lokuswp', 'settings', 'checkout_page')); ?>"
                   class="lokus-btn btn-block btn-primary" id="lwp-checkout" data-cy="go-checkout">
                    <?php _e("Checkout", "lokuswp"); ?>
                </a>
            </div>
        </div>
    </div>

</div>

<style>
    .floating-bottom{
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        display: none;
        z-index: 9999;
    }

    #lwcommerce-bottom-cart-panel{
        max-width: 480px;
        margin: 0 auto;
        background: #fff;
        padding: 12px 16px;
        border-radius: 8px 8px 0 0;
        border: 1px solid #E5E8EB;
    }

    #lwcommerce-bottom-cart-panel a{
        cursor: pointer;
    }
</style>