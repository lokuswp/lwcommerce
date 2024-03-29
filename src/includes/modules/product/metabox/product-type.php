<section id="subbox-product-type" class="subbox subbox-type">

    <header>
        <h3 class="subbox-title"><?php _e( "Product Type", 'lwcommerce' ); ?></h3>
    </header>

    <div class="content">

        <div id="tab-product-type" class="tab-group tab-product-type">
            <div class="form-group">

                <label for="lokuswp-product-type"><?php _e( "Choose Product Type", 'lwcommerce' ); ?>
                    <span class="asterix">*</span>
                </label>

                <div class="form-group-body">
                    <select class="form-select full" id="lokuswp-product-type" name="_product_type">
                        <option value="digital"><?php _e( "Digital Product", 'lwcommerce' ); ?></option>
                        <option value="physical"><?php _e( "Physical Product", 'lwcommerce' ); ?></option>
                    </select>

                    <script>
                        document.querySelector("#lokuswp-product-type option[value='<?php echo $args['product_type'] ?>']").setAttribute('selected', true);
                    </script>
                </div>

            </div>

            <div class="tab-content">

                <div id="tab-content-physical-product"
                     class="tab-entry product-type-tab-entry tab-content-physical-product active">

                    <div class="row">
                        <div class="col-6">
                            <h4><?php _e( "Product Weight", 'lwcommerce' ); ?></h4>

                            <div class="form-group">
                                <label for="lokuswp-product-weight"><?php _e( "in Grams", 'lwcommerce' ); ?></label>
                                <div class="form-group-body">
                                    <input type="text" id="lokuswp-product-weight" class="form-control full"
                                           name="_weight" value="<?= $args['weight'] ?>" placeholder="1">
                                </div>
                            </div>
                        </div>

                        <div class="col-6">
                            <h4><?php _e( "Item Volume in Centimeter", 'lwcommerce' ); ?></h4>

                            <div class="row nowrap">

                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="lokuswp-product-length">
											<?php _e( "Length", 'lwcommerce' ); ?>
                                            <span class="desc text-muted">(<?php _e( "optional", 'lwcommerce' ); ?>)</span>
                                        </label>

                                        <div class="form-group-body">
                                            <input type="text" id="lokuswp-product-length" class="form-control full"
                                                   name="_length" value="<?= $args['length'] ?>" placeholder="1">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="lokuswp-product-width">
											<?php _e( "Width", 'lwcommerce' ); ?>
                                            <span class="desc text-muted">(<?php _e( "optional", 'lwcommerce' ); ?>)</span>
                                        </label>
                                        <div class="form-group-body">
                                            <input type="text" id="lokuswp-product-width" class="form-control full"
                                                   name="_width" value="<?= $args['width'] ?>" placeholder="1">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="lokuswp-product-height">
											<?php _e( "Height", 'lwcommerce' ); ?>
                                            <span class="desc text-muted">(<?php _e( "optional", 'lwcommerce' ); ?>)</span>
                                        </label>
                                        <div class="form-group-body">
                                            <input type="text" id="lokuswp-product-height" class="form-control full"
                                                   name="_height" value="<?= $args['height'] ?>" placeholder="1">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div> <!-- .tab-content-physical-product -->

                <div id="tab-content-digital-product"
                     class="tab-entry product-type-tab-entry tab-content-digital-product">

                    <!--                    <div class="action-group" style="display:none">-->
                    <!--                        <h4>--><?php //_e( "Upload to Media", 'lwcommerce' ); ?><!--</h4>-->
                    <!--                        <div class="instruction">Kamu bisa menggunakan cara Upload manual menggunakan database Wordpress-->
                    <!--                            kamu atau juga melalui platform Upload File terpisah sepert Dropbox, Google Drive atauapun-->
                    <!--                            Media Fire melalui Field dibawah ini-->
                    <!--                        </div>-->
                    <!--                        <div class="action">-->
                    <!--                            <input type="hidden" name="product_attachment_url" id="product-attachment-url">-->
                    <!--                            <input type="hidden" name="product_attachment_id" id="product-attachment-id">-->
                    <!--                            <button id="btn-product-attachment"-->
                    <!--                                    class="button btn-product-attachment btn-upload">-->
					<?php //_e( "Upload File", 'lwcommerce' ); ?><!--</button>-->
                    <!--                        </div> -->
                    <!--                    </div> -->

                    <div class="form-group">
                        <label for="lokuswp-product-attachment-link"><?php _e( "Attachment Link", 'lwcommerce' ); ?>
                            <span class="asterix">*</span>
                        </label>
                        <div class="form-group-body has-tooltip">
                            <input id="lokuswp-product-attachment-link" name="_attachment_link"
                                   value="<?= $args['attachment_link'] ?>" type="text"
                                   class="form-control full img-pre-url"
                                   placeholder="https://www.mediafire.com/123456789">
                            <a href="#" class="info-popup-toggler" toggle="tooltip" data-placement="top"
                               data-message="Silahkan cek di [https://google.com](Google)"></a>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="lokuswp-product-version"><?php _e( "Attachment Version", 'lwcommerce' ); ?>
                            <span class="asterix">*</span>
                        </label>
                        <div class="form-group-body">
                            <input id="lokuswp-product-version" name="_attachment_version"
                                   value="<?= $args['attachment_version'] ?>" type="text" class="form-control full"
                                   placeholder="v1.0.0">
                        </div>
                    </div>

                </div> <!-- .tab-content-digital-product -->

            </div> <!-- .tab-content -->

        </div> <!-- tab-product-type -->
    </div>
</section>