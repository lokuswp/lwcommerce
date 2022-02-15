<!-- Skelton UI -->

<div class="container columns col-gapless header">
    <div class="column col-3"><?php _e('Name', 'lwpcommerce'); ?></div>
    <div class="column col-2 text-center">
        <?php //_e('Zone', 'lwpcommerce'); ?>
    </div>
    <div class="column col-2 text-center"><?php _e('Status', 'lwpcommerce'); ?></div>
    <div class="column col-2 text-center"><?php _e('Type', 'lwpcommerce'); ?></div>
    <div class="column col text-right"><?php _e('Action', 'lwpcommerce'); ?></div>
</div>

<?php
$shipping_active = lwp_get_option("shipping_active");
?>

<?php if ($shipping_active) : ?>

    <ul class="methods" id="draggable">

        <?php
        foreach ($shipping_active as $shipping_id) :

            $shipping_data = (object) lwp_get_option($shipping_id);
            $shipping_id = esc_attr($shipping_data->id);
            $shipping_class = esc_attr($shipping_data->payment_class);

            if (class_exists($shipping_class)) : $shipping_obj = new $shipping_class; ?>

                <li class="shipping-channel" draggable="true" style="vertical-align:middle">
                    <div class="services columns col-gapless">

                        <!-- Method -->
                        <div class="column col-3 method" style="margin-bottom: -8px; display: flex; align-items: center">

                            <img src="<?php echo esc_url($shipping_data->logo); ?>" alt="<?= $shipping_data->name ?>" style="max-height:40px">
                            <h6 style="padding: 0px 10px 0;">
                                <?php esc_attr_e($shipping_data->name); ?>
                            </h6>
                        </div>

                        <!-- Zone -->
                        <div class="column col-2 method text-center" style="padding-top: 8px;">

                            <!-- <?php foreach ($shipping_data->zone as $key => $zone) : ?>
                                <span class="label label-rounded label-primary"><?php esc_attr_e(ucfirst($zone)); ?></span>
                            <?php endforeach; ?> -->
                        </div>


                        <!-- Status -->
                        <div class="column col-2" style="display: flex; justify-content: center">
                            <div class="form-group">

                                <label class="form-switch">
                                    <input type="checkbox" id="<?php echo $shipping_id; ?>" <?php echo ($shipping_obj->get_status() == 'on') ? 'checked' : ''; ?>>
                                    <i class="form-icon"></i> <?php _e('Active', 'lwpcommerce'); ?>
                                </label>

                            </div>
                        </div>

                        <!-- Jenis -->
                        <div class="column col-2 method  text-center">
                            <h6 style="padding-top: 8px;"><?php esc_attr_e(ucfirst($shipping_data->type)) ?></h6>
                        </div>

                        <!-- Manage Button -->
                        <div class="column text-right">
                            <button class="btn lwp-payment-manage" id="<?php echo $shipping_id; ?>" disabled>
                                <?php _e('Manage', 'lwpcommerce'); ?>
                            </button>
                        </div>

                    </div>

                    <!-- Services -->
                    <div class="services-bar" style="width:100%;border-top:1px solid #ddd;">
                        <?php _e('Services', 'lwpcommerce'); ?> :
                        <?php foreach ($shipping_data->package as $key => $package) : ?>

                            <label class="form-checkbox">
                                <input type="checkbox" checked=""><i class="form-icon"></i><?php esc_attr_e(ucfirst($key)) ?>
                            </label>

                            <!-- <input type="checkbox" id="<?php esc_attr_e($key) ?>" class="lwpc_shipping_package_status" value="<?php esc_attr_e($key) ?>" <?= ($package === 'on') ? 'checked' : '' ?> data-action="<?= $shipping_id ?>"> -->


                        <?php endforeach; ?>
                    </div>

                    <style>
                        .services-bar input[type="checkbox"] {
                            margin: 0 2px 0;
                            ;
                            width: 14px !important;
                        }

                        .shipping-channel {
                            /* padding:12px; */
                            border-radius: 4px;
                            border: 1px solid #ddd;
                        }

                        .shipping-channel .services {
                            padding: 12px;
                        }


                        .shipping-channel .services-bar {
                            padding: 0 12px 0;
                        }

                        .label.label-primary {
                            padding: 3px 17px;
                        }

                        .form-checkbox{
                            display: inline-block;
                            margin-left: 4px;
                        }
                    </style>
                </li>

            <?php endif; ?>
        <?php endforeach; ?>
    </ul>

<?php endif; ?>