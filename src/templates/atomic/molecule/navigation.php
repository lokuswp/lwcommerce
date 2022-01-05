<div class="lwp-navigate row">
    <div class="col-xs-2">
        <div class="svg-wrapper">
            <img src="<?php echo plugins_url('/src/assets/svg/arrow-prev.svg', LOKUSWP_BASE); ?>" alt="prev">
        </div>
    </div>
    <div class="col-xs-8 center-sm middle-sm d-flex"><?php the_title(); ?></div>
    <div class="col-xs-2 end-sm d-flex">
        <a href="<?php echo get_permalink(lwp_get_settings('settings', 'cart_page' )); ?>">
            <div class="troli-icon-wrapper"></div>
        </a>
    </div>
</div>