<?php

$btn_cart_link     = get_post_meta( get_the_ID(), '_btn_cart_link', true ) == null ? null : esc_attr( get_post_meta( get_the_ID(), '_btn_cart_link', true ) );
$btn_cart_text     = empty( get_post_meta( get_the_ID(), '_btn_cart_text', true ) ) ? __( 'Add to Cart', 'lwcommerce' ) : esc_attr( get_post_meta( get_the_ID(), '_btn_cart_text', true ) );
$is_variant_exists = false;

if ( function_exists( 'lwc_pro_is_product_has_variant' ) ) {
	$is_variant_exists = lwc_pro_is_product_has_variant( get_the_ID() );
}
?>

<div class="product-action">
	<?php if ( $btn_cart_link && $options['catalog_mode'] != "on" ) : ?>
        <a href="<?= $btn_cart_link ?>" target="_blank"
           class="lokus-btn btn-primary btn-block"><?= $btn_cart_text ?></a>
	<?php elseif ( $is_variant_exists && ! is_singular( 'product' ) && $options['catalog_mode'] != "on" ) : ?>
        <a href="<?= get_permalink(); ?>" target="_blank"
           class="lokus-btn btn-primary btn-block"><?= $btn_cart_text ? $btn_cart_text : __( "Choose Variant", "lwcommerce" ); ?></a>
	<?php elseif ( $options['catalog_mode'] != "on" ) : ?>
        <button class="lokus-btn btn-primary btn-block lwc-add-to-cart" product-id="<?php echo get_the_ID(); ?>"
                price="<?= lwc_get_price( get_the_ID() ) ?>"
                is-variant-exists="<?= $is_variant_exists ?>"><?= $btn_cart_text ?></button>
        <div class="lokuswp-stepper lwp-hidden" product-id="<?php echo get_the_ID(); ?>">
            <button type="button" class="minus" data-qty-action="minus">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="feather feather-minus">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
            </button>
            <input min="1" max="2" type="number" value="1" class="val-qty-<?php echo get_the_ID(); ?>">
            <button type="button" class="plus" data-qty-action="plus">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="feather feather-plus">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
            </button>
        </div>
	<?php endif; ?>

	<?php if ( $options['whatsapp_button'] == "on" && ! is_singular( "product" ) ) : ?>
        <a href="https://api.whatsapp.com/send/?phone=<?= $options['whatsapp']; ?>&text=<?= __( "Saya tertarik dengan", "lwcommerce" ) . ' ' . htmlentities( get_the_title() ) . ' ' . __( "apa masih ada ?", "lwcommerce" ); ?>&type=phone_number&app_absent=0"
           target="_blank" id="lokuswp-verify-form"
           class="lwp-btn lokus-btn btn-primary btn-block swiper-no-swiping"
           style="color:#fff;margin-top:8px;position:relative;background:#25d366;border:none;font-weight:500;">
            <div class="icon">
                <svg class="svg-icon"
                     style="width: 24px; height: 24px;vertical-align:middle;margin-top: -8px; fill: currentColor;overflow: hidden;"
                     viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg">
                    <path d="M309.461333 789.077333l30.890667 18.048A339.328 339.328 0 0 0 512 853.333333a341.333333 341.333333 0 1 0-341.333333-341.333333 339.2 339.2 0 0 0 46.250666 171.690667l18.005334 30.890666-27.861334 102.442667 102.4-27.946667zM85.504 938.666667l57.685333-211.968A424.704 424.704 0 0 1 85.333333 512C85.333333 276.352 276.352 85.333333 512 85.333333s426.666667 191.018667 426.666667 426.666667-191.018667 426.666667-426.666667 426.666667a424.704 424.704 0 0 1-214.613333-57.813334L85.504 938.666667zM358.016 311.808c5.717333-0.426667 11.477333-0.426667 17.194667-0.170667 2.304 0.170667 4.608 0.426667 6.912 0.682667 6.784 0.768 14.250667 4.906667 16.768 10.624 12.714667 28.842667 25.088 57.898667 37.034666 87.04 2.645333 6.485333 1.066667 14.805333-3.968 22.912a186.88 186.88 0 0 1-11.221333 15.872c-4.821333 6.186667-15.189333 17.536-15.189333 17.536s-4.224 5.034667-2.602667 11.306667c0.597333 2.389333 2.56 5.845333 4.352 8.746666l2.517333 4.053334c10.922667 18.218667 25.6 36.693333 43.52 54.101333 5.12 4.949333 10.112 10.026667 15.488 14.762667 19.968 17.621333 42.581333 32 66.986667 42.666666l0.213333 0.085334c3.626667 1.578667 5.461333 2.432 10.752 4.693333 2.645333 1.109333 5.376 2.090667 8.149334 2.816a14.933333 14.933333 0 0 0 15.658666-5.546667c30.890667-37.418667 33.706667-39.850667 33.962667-39.850666v0.085333a20.565333 20.565333 0 0 1 16.128-5.418667c2.56 0.170667 5.162667 0.64 7.552 1.706667 22.656 10.368 59.733333 26.538667 59.733333 26.538667l24.832 11.136c4.181333 2.005333 7.978667 6.741333 8.106667 11.306666 0.170667 2.858667 0.426667 7.466667-0.554667 15.914667-1.365333 11.050667-4.693333 24.32-8.021333 31.274667a49.28 49.28 0 0 1-8.96 12.885333 101.461333 101.461333 0 0 1-14.08 12.288 158.293333 158.293333 0 0 1-5.333333 3.84 214.357333 214.357333 0 0 1-16.341334 9.386667 84.906667 84.906667 0 0 1-35.541333 9.813333c-7.893333 0.426667-15.786667 1.024-23.722667 0.597333-0.341333 0-24.234667-3.712-24.234666-3.712a403.114667 403.114667 0 0 1-163.84-87.296c-9.642667-8.490667-18.56-17.621333-27.690667-26.709333-37.973333-37.76-66.645333-78.506667-84.053333-116.992A148.053333 148.053333 0 0 1 294.4 410.453333a116.437333 116.437333 0 0 1 24.064-71.68c3.114667-4.010667 6.058667-8.192 11.136-13.013333 5.418667-5.12 8.832-7.850667 12.544-9.728a41.002667 41.002667 0 0 1 15.829333-4.266667z"/>
                </svg>
            </div>
			<?= __( "WhatsApp", "lwcommerce" ); ?>
        </a>
	<?php endif; ?>
</div>

<style>


    .lwp-hidden {
        display: none;
    }

    .add-cart {
        display: block;
        margin: 0 auto;
    }

    .btn-block {
        width: 100%;
    }

    .lwc-listing .col-sm-6 {
        margin-bottom: 12px;
    }

</style>

