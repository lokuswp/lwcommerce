<?php

/**
 * Template :: Transaction
 * 
 * Displaying Transaction Interface for User
 * Allow to Extendable
 * This is template just hanger for attach transaction component
 * 
 * @since 0.5 - BETA
 */

?>

<section id="lokuswp-transaction">

    <input type="hidden" id="transaction-nonce" value="<?php echo wp_create_nonce('lokuwp-transaction-nonce'); ?>" />

    <!-- Transaction Tabs -->
    <div class="transaction-tabs">

        <!-- Tab Navigation -->
        <div class="swiper-container swiper-tabs-nav">
            <div class="swiper-wrapper">

                <?php 
                /**
                 * Hanger for Hooking Header Tab
                 */
                do_action("lwpcommerce/customer/tab/header"); 
                ?>

            </div>
        </div>

        <!-- Tab Content -->
        <div class="swiper-container swiper-tabs-content">
            <div class="swiper-wrapper">

                <?php 
                /**
                 * Hanger for Hooking Content Tab 
                 */
                do_action("lwpcommerce/customer/tab/content"); 
                ?>

            </div>
        </div>

    </div>

</section>

<style>
    .hidden{
        display: none !important;
    }
</style>

<script>
    /**
     * @block Transaction
     * Swiper Transaction
     */
    var swiperTabsContent = null;
    var swiperTabsNav = null;

    jQuery(window).on("load", function() {

        swiperTabsNav = new Swiper('.swiper-tabs-nav', {
            spaceBetween: 0,
            slidesPerView: 'auto',
            loop: false,
            centeredSlides: false,
            // loopedSlides: 5,
            autoHeight: false,
            resistanceRatio: 0,
            watchOverflow: true,
            watchSlidesVisibility: true,
            watchSlidesProgress: true,

        });

        // Swiper Content
        swiperTabsContent = new Swiper('.swiper-tabs-content', {
            spaceBetween: 0,
            loop: false,
            autoHeight: true,
            longSwipes: true,
            resistanceRatio: 0, // Disable First and Last Swiper
            watchOverflow: true,
            loopedSlides: 5,
            thumbs: {
                swiper: swiperTabsNav,
            },
            paginationClickable: false,
        });
        // swiperTabsNav.update();

        // jQuery(document).on('click', '.lwp-toggle-collapse, .shipping-reset, input[name="physical_courier"]', function(e) {
        //     swiperTabsContent.updateAutoHeight();
        // });

    });

    // function lsdc_checkout_nextslide(position = 1) {
    //     swiperTabsContent.slideTo(position);
    // }
</script>

