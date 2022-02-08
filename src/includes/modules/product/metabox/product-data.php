<section class="subbox subbox-info">
    <header>
        <h3 class="subbox-title"><?php _e("Product Data", 'lwpcommerce'); ?></h3>
    </header>
    <div class="content">

        <div id="tab-product-info" class="tab-group">

            <div class="tab-nav">
                <a class="tab-menu active" href="#" data-root="tab-product-info" data-target="tab-content-price"><?php _e("Price", 'lwpcommerce'); ?></a>
                <a class="tab-menu" href="#" data-root="tab-product-info" data-target="tab-content-stock"><?php _e("Stock", 'lwpcommerce'); ?></a>
            </div>
            <div class="tab-content">
                <div id="tab-content-price" class="tab-entry tab-content-price active">
                    <div class="row">

                        <div class="form-group col-6">
                            <label for="lokuswp-price-normal"><?php _e("Normal Price", 'lwpcommerce'); ?>
                                <span class="asterix">*</span>
                                <span class="description text-muted">(<?php _e("required", 'lwpcommerce'); ?>)</span>
                            </label>
                            <div class="form-group-body has-tooltip">
                                <input id="lokuswp-price-normal" name="lokuswp_price_normal" type="text" class="form-control full" placeholder="100.000" value=<?= $args['price_normal'] ?>>
                                <a href="#" class="info-popup-toggler tooltip" toggle="tooltip" data-placement="top" title="Silahkan cek di [https://google.com](Google)"></a>
                            </div> <!-- .dform-group-body -->
                        </div> <!-- .form-group -->

                        <div class="form-group col-6">
                            <label for="lokuswp-price-promo"><?php _e("Discount Price", 'lwpcommerce'); ?>
                                <span class="asterix">*</span>
                                <span class="description text-muted">(<?php _e("required", 'lwpcommerce'); ?>)</span>
                            </label>
                            <div class="form-group-body has-tooltip">
                                <input id="lokuswp-price-promo" name="lokuswp_price_promo" type="text" class="form-control full" placeholder="50.000" value=<?= $args['price_discount'] ?>>
                                <a href="#" class="info-popup-toggler tooltip" toggle="tooltip" data-placement="top" title="Silahkan cek di [https://google.com](Google)"></a>
                            </div> <!-- .dform-group-body -->
                        </div> <!-- .form-group -->

                    </div> <!-- .row -->

                    <?php do_action("lwpcommerce/product/data/price/after", $args); ?>

                </div> <!-- .tab-content-harga -->
                <div id="tab-content-stock" class="tab-entry tab-content-stock">

                    <?php do_action("lwpcommerce/product/data/stock/before", $args); ?>

                    <div class="row">

                        <div class="form-group col-12">
                            <label for="lokuswp-product-sku"><?php _e("Stock Keeping Unit (SKU)", 'lwpcommerce'); ?>
                                <span class="description text-muted">(<?php _e("optional", 'lwpcommerce'); ?>)</span>
                            </label>
                            <div class="form-group-body has-input-action">
                                <input type="text" name="lokuswp_product_sku" id="lokuswp-product-sku" class="form-control full" placeholder="<?php _e("SKU Code", 'lwpcommerce'); ?>">

                                <a href="#" id="btn-generate-sku" class="input-action btn"><?php _e("Generate", 'lwpcommerce'); ?></a>

                            </div> <!-- .dform-group-body -->
                        </div> <!-- .form-group -->

                        <div class="form-group col-6">
                            <label for="lokuswp-product-availability"><?php _e("Product Availability", 'lwpcommerce'); ?>
                                <span class="asterix">*</span>
                            </label>
                            <div class="form-group-body">
                                <select id="lokuswp-product-availability" name="lokuswp_product_availability" class="form-select full">
                                    <option><?php _e("In Stock", 'lwpcommerce'); ?></option>
                                    <!-- <option>Pre Order</option> -->
                                </select>
                            </div> <!-- .dform-group-body -->
                        </div> <!-- .form-group -->

                        <div class="form-group col-6">
                            <label for="lokuswp-product-stock"><?php _e("Stock Quantity", 'lwpcommerce'); ?>
                                <span class="asterix">*</span>
                            </label>
                            <div class="form-group-body">
                                <input id="lokuswp-product-stock" name="lokuswp_product_stock" type="number" class="form-control full" placeholder="100">
                            </div> <!-- .dform-group-body -->
                        </div> <!-- .form-group -->

                    </div> <!-- .row -->

                    <?php do_action("lwpcommerce/product/data/stock/after", $args); ?>

                </div> <!-- .tab-content-harga -->
            </div>
        </div>
    </div>
</section>