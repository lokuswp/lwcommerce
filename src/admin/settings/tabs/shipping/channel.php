<!-- <div class="container-shimmer">
			  <div class="content-shimmer">
				<div class="form-shimmer shimmer"></div>
				<div class="form-shimmer shimmer"></div>
				<div class="form-shimmer shimmer"></div>
				<div class="form-shimmer shimmer"></div>
			  </div>
			</div> -->

<div class="container columns col-gapless header">
    <div class="column col-2"><?php _e('Channel', 'lokuswp'); ?></div>
    <div class="column col-2 text-center"><?php _e('Zone', 'lokuswp'); ?></div>
    <div class="column col-2 text-center"><?php _e('Services', 'lokuswp'); ?></div>
    <div class="column col-2 text-center"><?php _e('Status', 'lokuswp'); ?></div>
    <div class="column col-2 text-center"><?php _e('Jenis', 'lokuswp'); ?></div>
    <div class="column col text-right"><?php _e('Manage', 'lokuswp'); ?></div>
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

                <li class="draggable" draggable="true">
                    <div class="columns col-gapless">

                        <!-- Method -->
                        <div class="column col-2 method" style="margin-bottom: -8px; display: flex; align-items: center">

                            <img src="<?php echo esc_url($shipping_data->logo); ?>" alt="<?= $shipping_data->name ?>" height="40" width="100">


                            <h6 style="padding: 0px 10px 0;">
                                <?php esc_attr_e($shipping_data->name); ?>
                            </h6>
                        </div>

                        <!-- Zone -->
                        <div class="column col-2 method text-center">
                            <h6><?php esc_attr_e(ucfirst(implode(', ', $shipping_data->zone))) ?></h6>
                        </div>

                        <!-- Services -->
                        <div class="column col-2" style="display: grid;align-content: center;align-items: center;font-weight: bold;">
                            <?php foreach ($shipping_data->package as $key => $package) : ?>
                                <div>
                                    <input type="checkbox" id="<?php esc_attr_e($key) ?>" class="lwpc_shipping_package_status" value="<?php esc_attr_e($key) ?>" <?= ($package === 'on') ? 'checked' : '' ?> data-action="<?= $shipping_id ?>">
                                    <label for="<?php esc_attr_e($key) ?>"><?php esc_attr_e($key) ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Status -->
                        <div class="column col-2" style="display: flex; justify-content: center">
                            <div class="form-group">

                                <label class="form-switch">
                                    <input type="checkbox" id="<?php echo $shipping_id; ?>" <?php echo ($shipping_obj->get_status() == 'on') ? 'checked' : ''; ?>>
                                    <i class="form-icon"></i> <?php _e('Aktif', 'lokuswp'); ?>
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
                                <?php _e('Kelola', 'lokuswp'); ?>
                            </button>
                        </div>

                    </div>
                </li>

            <?php endif; ?>
        <?php endforeach; ?>
    </ul>

<?php endif; ?>