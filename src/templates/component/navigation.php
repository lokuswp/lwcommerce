<div class="lwp-navigate row">
    <div class="col-xs-2">
        <div class="svg-wrapper">
            <img src="<?php echo plugins_url( '/src/assets/svg/arrow-prev.svg', LOKUSWP_BASE ); ?>" alt="prev">
        </div>
    </div>

    <div class="col-xs-8 center-xs middle-xs d-flex">
		<?php if ( ! is_singular() ) : ?>
			<?php the_title(); ?>
		<?php endif; ?>
    </div>

    <div class="col-xs-2 end-xs d-flex">
        <lwp-cart-icon></lwp-cart-icon>
    </div>
</div>