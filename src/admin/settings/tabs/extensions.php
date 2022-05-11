<?php

use LokusWP\Plugin\License;

?>

<br>
<div class="column col-12">
    <div class="filter">
        <input class="filter-tag" id="tag-0" type="radio" name="filter-radio" checked="" hidden="">
        <input class="filter-tag" id="tag-1" type="radio" name="filter-radio" hidden="">
        <input class="filter-tag" id="tag-2" type="radio" name="filter-radio" hidden="">
        <input class="filter-tag" id="tag-3" type="radio" name="filter-radio" hidden="">
        <input class="filter-tag" id="tag-4" type="radio" name="filter-radio" hidden="">
        <div class="filter-nav">
            <label class="chip" for="tag-0">All</label>
            <label class="chip" for="tag-1">Advanced</label>
            <!-- <label class="chip" for="tag-2">Payments</label>
            <label class="chip" for="tag-3">Utility</label>
            <label class="chip" for="tag-4">Advanced</label> -->
        </div>
        <div class="filter-body columns">

            <div class="column col-4 filter-item" data-tag="tag-1">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title text-bold">Pro</div>
                        <div class="card-subtitle text-gray">Variant Produk, Kupon</div>
						<?php if ( License::correct( 'lwcommerce-pro' ) === 'dev' && empty( License::get( 'lwcommerce-pro' ) ) ) : ?>
                            <span class="label label-primary"><?php _e( "Development site won't get plugin updates automatically", 'lwcommerce' ); ?></span>
						<?php elseif ( License::get( 'lwcommerce-pro' ) ) : ?>
                            <span class="label label-success"><?php _e( "Activated", 'lwcommerce' ); ?></span>
						<?php else : ?>

                            <small><?php echo 'v' . LWC_VERSION; ?> - <?php _e( 'Input your license key', 'lwcommerce' ); ?> </small>
                            <input autocomplete="off" style="margin-top:5px;" class="form-input lwc-license-key" type="text" placeholder="License Key">
                            <span style="color: red;font-size: 12px; display: none" id="error-message"></span>
                            <button class="btn btn-block my-2 bg-success lwc-license-register" style="border:none;" data-slug="lwcommerce-pro"><?php _e( 'Insert',
									'lwcommerce' ); ?></button>
						<?php endif; ?>
                    </div>
                </div>
            </div>

            <!--
			<div class="column col-4 filter-item" data-tag="tag-1">
				<div class="card">
					<div class="card-header">
						<div class="card-title text-bold">Dine In</div>
						<div class="card-subtitle text-gray">Self Service FnB Order</div>
					</div>
				</div>
			</div>


			<div class="column col-4 filter-item" data-tag="tag-1">
				<div class="card">
					<div class="card-header">
						<div class="card-title text-bold">Elementor Widget</div>
						<div class="card-subtitle text-gray">Ecommerce Desginer NoCode</div>
					</div>
				</div>
			</div>
			-->

        </div>
    </div>
</div>

<style>
    .card {
        padding: 0;

    }

    .filter-tag {
        display: none !important;
    }
</style>