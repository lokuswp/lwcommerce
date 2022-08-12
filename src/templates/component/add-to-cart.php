<?php

$btn_cart_link  = get_post_meta( get_the_ID(), '_btn_cart_link', true ) == null ? null : esc_attr(get_post_meta( get_the_ID(), '_btn_cart_link', true ));
$btn_cart_text = empty(get_post_meta( get_the_ID(), '_btn_cart_text', true )) ? __( 'Add to Cart', 'lwcommerce' ) : esc_attr( get_post_meta( get_the_ID(), '_btn_cart_text', true ) );

?>

<div class="product-action">
    <?php if( $btn_cart_link ) : ?>
        <a href="<?= $btn_cart_link ?>" target="_blank" class="lokus-btn btn-primary btn-block"><?= $btn_cart_text ?></a>
    <?php else : ?>
    <button class="lokus-btn btn-primary btn-block lwc-add-to-cart" product-id="<?php echo get_the_ID(); ?>" price="<?= lwc_get_price( get_the_ID() )?>"><?= $btn_cart_text ?></button>
    <div class="lokuswp-stepper lwp-hidden" product-id="<?php echo get_the_ID(); ?>">
        <button type="button" class="minus" data-qty-action="minus">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-minus">
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
        </button>
        <input min="1" max="2" type="number" value="1" class="val-qty-<?php echo get_the_ID(); ?>">
        <button type="button" class="plus" data-qty-action="plus">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
        </button>
    </div>
    <?php endif; ?>
</div>

<style>

    button.lokus-btn{
        font-weight: normal;
    }

    .lwp-hidden{
        display: none;
    }

    .add-cart{
        display: block;
        margin: 0 auto;
    }

    .btn-block{
        width: 100%;
    }

    .lwc-listing .col-sm-6{
        margin-bottom: 12px;
    }

</style>

