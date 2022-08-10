<style>
    .right-clear {
        padding-right: 0 !important;
    }
</style>

<section class="subbox subbox-info">

    <header>
        <h3 class="subbox-title"><?php _e( "Product Data", 'lwcommerce' ); ?></h3>
    </header>

    <div class="content">
        <div id="tab-product-info" class="tab-group">

            <div class="tab-nav">
                <a class="tab-menu active" href="#" data-root="tab-product-info"
                   data-target="tab-content-price"><?php _e( "Price", 'lwcommerce' ); ?></a>

                <a class="tab-menu" href="#" data-root="tab-product-info"
                   data-target="tab-content-stock"><?php _e( "Stock", 'lwcommerce' ); ?></a>
            </div>

            <div class="tab-content">

                <!-- Pricing -->
                <div id="tab-content-price" class="tab-entry tab-content-price active">
                    <div class="row">

						<?php do_action( "lwcommerce/product/data/price/before", $args ); ?>

                        <div class="form-group col-6">
                            <label for="lokuswp-price-normal"><?php _e( "Normal Price", 'lwcommerce' ); ?>
                                <span class="asterix">*</span>
                                <span class="description text-muted">(<?php _e( "required", 'lwcommerce' ); ?>)</span>
                            </label>
                            <div class="form-group-body has-tooltip">
                                <input id="lokuswp-price-normal" name="_unit_price" type="text"
                                       class="form-control full currency-format" placeholder="100.000"
                                       value="<?= $args['unit_price'] ?>">
                                <a href="#" class="info-popup-toggler tooltip" toggle="tooltip" data-placement="top"
                                   title="Silahkan cek di [https://google.com](Google)"></a>
                            </div>
                        </div>

                        <div class="form-group col-6">
                            <label for="lokuswp-price-promo"><?php _e( "Promo Price", 'lwcommerce' ); ?>
                                <span class="description text-muted">(<?php _e( "optional", 'lwcommerce' ); ?>)</span>
                            </label>
                            <div class="form-group-body has-tooltip">
                                <input id="lokuswp-price-promo" name="_price_promo" type="text"
                                       class="form-control full currency-format" placeholder="50.000"
                                       value="<?= $args['price_promo'] ?>">
                                <a href="#" class="info-popup-toggler tooltip" toggle="tooltip" data-placement="top"
                                   title="Silahkan cek di [https://google.com](Google)"></a>
                            </div>
                        </div>

						<?php do_action( "lwcommerce/product/data/price/after", $args ); ?>

                    </div>
                </div>

                <!-- Stock -->
                <div id="tab-content-stock" class="tab-entry tab-content-stock">
                    <div class="row">

						<?php do_action( "lwcommerce/product/data/stock/before", $args ); ?>

                        <div class="form-group col-12">
                            <label for="lokuswp-product-sku"><?php _e( "Stock Keeping Unit (SKU)", 'lwcommerce' ); ?>
                                <span class="description text-muted">(<?php _e( "optional", 'lwcommerce' ); ?>)</span>
                            </label>
                            <div class="form-group-body has-input-action">
                                <input type="text" name="_sku_code" id="lokuswp-product-sku" class="form-control full"
                                       value="<?= $args['sku_code'] ?>"
                                       placeholder="MRK-PRD-M-MRH-001">
                                <!-- <a href="#" id="btn-generate-sku" class="input-action btn"><?php //_e( "Generate", 'lwcommerce' ); ?></a> -->
                            </div>
                        </div>

                        <div class="row nowrap col-12 right-clear">

                            <div class="form-group col-6 right-clear">
                                <label for="lokuswp-product-stock"><?php _e( "Quantity", 'lwcommerce' ); ?>
                                    <span class="asterix">*</span>
                                </label>
                                <div class="form-group-body">
                                    <input id="lokuswp-product-stock" name="_stock" type="number"
                                           class="form-control full" value="<?= $args['stock'] ?>" placeholder="100">
                                </div>
                            </div>

                            <div class="form-group col-6 right-clear">
                                <label for="lokuswp-product-stock"><?php _e( "Unit", 'lwcommerce' ); ?>
                                    <span class="asterix">*</span>
                                </label>
                                <div class="form-group-body">
                                    <input id="lokuswp-product-unit" name="_stock_unit" type="text"
                                           class="form-control full" value="<?= $args['stock_unit'] ?>"
                                           placeholder="pcs">
                                </div>
                            </div>

                        </div>

                        <div class="form-group col-12">

                            <label for="lokuswp-product-stock-availability"><?php _e( "Availability", 'lwcommerce' ); ?>
                                <span class="asterix">*</span>
                            </label>

                            <div class="form-group-body">
                                <select class="form-select full" id="lokuswp-stock-type" name="_stock_type">
                                    <option value="ready"><?php _e( "Ready Stock", 'lwcommerce' ); ?></option>
                                    <option value="preorder"><?php _e( "Pre Order", 'lwcommerce' ); ?></option>
                                </select>

                                <script>
                                    // Auto Select Based on value
                                    document.addEventListener('DOMContentLoaded', function (event) {
                                        document.querySelector("#lokuswp-stock-type option[value='<?= $args['stock_type'] ?>']").setAttribute('selected', true);
                                        var fieldStockType = document.getElementById("lokuswp-stock-type");
                                        window.lmtb.setStockType(fieldStockType);
                                    });
                                </script>
                            </div>

                            <div class="tab-content">

                                <div id="tab-content-ready-stock"
                                     class="tab-entry stock-type-tab-entry">

                                </div> <!-- In stock -->

                                <div id="tab-content-preorder-stock"
                                     class="tab-entry stock-type-tab-entry">

                                    <!-- Pre Order -->
                                    <div class="row nowrap col-12 right-clear">
                                        <div class="form-group col-6 right-clear clean-child clean-child-left">
                                            <label for="lokuswp-product-stock"><?php _e( "Waktu Pembuatan", 'lwcommerce' ); ?>
                                                <span class="asterix">*</span>
                                            </label>
                                            <div class="form-group-body">
                                                <input id="lokuswp-stock-manufacture-time"
                                                       name="_manufacture_time"
                                                       type="number"
                                                       class="form-control full"
                                                       value="<?= $args['manufacture_time'] ?>"
                                                       placeholder="10">
                                            </div>
                                        </div>

                                        <div class="form-group col-6 right-clear clean-child">
                                            <label for="lokuswp-stock-manufacture-time-unit"><?php _e( "Unit", 'lwcommerce' ); ?>
                                                <span class="asterix">*</span>
                                            </label>
                                            <div class="form-group-body">
                                                <select id="lokuswp-stock-manufacture-time-unit"
                                                        name="_manufacture_time_unit"
                                                        class="form-select full">
                                                    <option value="minutes"><?php _e( "Menit", 'lwcommerce' ); ?></option>
                                                    <option value="hours"><?php _e( "Jam", 'lwcommerce' ); ?></option>
                                                    <option value="days"><?php _e( "Hari", 'lwcommerce' ); ?></option>
                                                </select>

                                                <script>
                                                    // Auto Select Based on value
                                                    document.querySelector("#lokuswp-stock-manufacture-time-unit option[value='<?php echo $args['manufacture_time_unit'] ?>']").setAttribute('selected', true);
                                                </script>
                                            </div>
                                        </div>
                                    </div>

                                </div> <!-- Pre Order -->

                            </div> <!-- .tab-content -->
                        </div>


                    </div> <!-- .row -->

					<?php do_action( "lwcommerce/product/data/stock/after", $args ); ?>

                </div> <!-- .tab-content-harga -->

            </div>

        </div>
    </div>

</section>