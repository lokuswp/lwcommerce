<?php
get_header();
wp_enqueue_style("lwp-grid");
?>

<div class="lwpcommerce lwp-container">
    <div class="lwp-navigate row">
        <div class="col-xs-2">
            <div class="svg-wrapper">
                <img src="<?php echo plugins_url('/src/assets/svg/arrow-prev.svg', LWPBB_BASE); ?>" alt="prev">
            </div>
        </div>
        <div class="col-xs-8"></div>
        <div class="col-xs-2 end-sm d-flex troli-icon-wrapper"></div>
    </div>

    <div class="lwp-product row">
        <div class="col-xs-12 col-sm-12">
            <?php the_post_thumbnail(); ?>
        </div>
        <div class="col-xs-12 col-sm-12 row gutter" style="margin-top:8px;">
            <div class="col-xs-9">
                <?php the_title(); ?>
                <?php lwpc_get_price_html(); ?>
            </div>
            <div class="col-xs-3 end-sm">
                <?php lwpc_add_to_cart_html(); ?>
                <?php lwpc_get_stock_html(); ?>
            </div>
        </div>
        <div class="col-sm-12 gutter">
            <?php the_content(); ?>
        </div>
    </div>
</div>

<style>
    .lwp-container {
        /* max-width: 960px; */
        max-width: 420px;
        margin: 0 auto;
    }


</style>

<?php get_footer(); ?>