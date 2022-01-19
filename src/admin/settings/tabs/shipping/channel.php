<!-- Skelton UI -->

<div class="container columns col-gapless header">
    <div class="column col-3"><?php _e('Name', 'lokuswp'); ?></div>
    <div class="column col-2 text-center"><?php _e('Zone', 'lokuswp'); ?></div>
    <div class="column col-2 text-center"><?php _e('Status', 'lokuswp'); ?></div>
    <div class="column col-2 text-center"><?php _e('Type', 'lokuswp'); ?></div>
    <div class="column col text-right"><?php _e('Action', 'lokuswp'); ?></div>
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
                        <div class="column col-2 method text-center">

                            <?php foreach ($shipping_data->zone as $key => $zone) : ?>
                                <span class="label label-rounded label-primary"><?php esc_attr_e(ucfirst($zone)); ?></span>
                            <?php endforeach; ?>
                        </div>


                        <!-- Status -->
                        <div class="column col-2" style="display: flex; justify-content: center">
                            <div class="form-group">

                                <label class="form-switch">
                                    <input type="checkbox" id="<?php echo $shipping_id; ?>" <?php echo ($shipping_obj->get_status() == 'on') ? 'checked' : ''; ?>>
                                    <i class="form-icon"></i> <?php _e('Active', 'lokuswp'); ?>
                                </label>

                            </div>
                        </div>

                        <!-- Jenis -->
                        <div class="column col-2 method  text-center">
                            <h6><?php esc_attr_e(ucfirst($shipping_data->type)) ?></h6>
                        </div>

                        <!-- Manage Button -->
                        <div class="column text-right">
                            <button class="btn lwp-payment-manage" id="<?php echo $shipping_id; ?>">
                                <?php _e('Manage', 'lokuswp'); ?>
                            </button>
                        </div>

                    </div>

                    <!-- Services -->
                    <div class="services-bar" style="width:100%;border-top:1px solid #ddd;padding-top:8px;">
                        <?php _e('Services', 'lokuswp'); ?> :
                        <?php foreach ($shipping_data->package as $key => $package) : ?>

                            <input type="checkbox" id="<?php esc_attr_e($key) ?>" class="lwpc_shipping_package_status" value="<?php esc_attr_e($key) ?>" <?= ($package === 'on') ? 'checked' : '' ?> data-action="<?= $shipping_id ?>">
                            <label for="<?php esc_attr_e($key) ?>"><?php esc_attr_e($key) ?></label>

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
                            padding: 0 12px 8px;
                        }
                    </style>
                </li>

            <?php endif; ?>
        <?php endforeach; ?>
    </ul>

<?php endif; ?>