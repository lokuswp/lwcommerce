<div class="product-action">
    <button class="lwpc-addtocart" product-id="<?php echo get_the_ID(); ?>">Tambah</button>
    <div class="lwp-stepper lwp-hidden" product-id="<?php echo get_the_ID(); ?>">
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
</div>

<style>
    .lwp-hidden{
        display: none;
    }

    .add-troli{
        display: block;
        margin: 0 auto;
    }

</style>

