<?php if ( ! has_action( "lwcommerce/admin/shipping/integration" ) ) : ?>

    <div class="empty">
        <div class="empty-icon"><i class="icon icon-3x icon-cross"></i></div>
        <p class="empty-title h5"><?php _e( 'No integration yet', 'lwcommerce' ); ?></p>
        <p class="empty-subtitle"><?php _e( 'Please Install Addon Shipping for More Shipping Methods', 'lwcommerce' ); ?></p>
    </div>

<?php else : ?>
    <form>
		<?php do_action( "lwcommerce/admin/shipping/integration" ) ?>

        <button class="btn btn-primary" id="lokuswp_admin_context_save" style="width:120px" app="lwcommerce">Save</button>
    </form>
<?php endif; ?>