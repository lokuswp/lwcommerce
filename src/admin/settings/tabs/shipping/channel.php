<!-- Skelton UI -->

<div class="container columns col-gapless header">
    <div class="column col-3"><?php _e('Name', 'lwcommerce'); ?></div>
    <div class="column col-2 text-center">
        <?php //_e('Zone', 'lwcommerce'); 
        ?>
    </div>
    <div class="column col-2 text-center"><?php _e('Status', 'lwcommerce'); ?></div>
    <div class="column col-2 text-center"><?php _e('Type', 'lwcommerce'); ?></div>
    <!-- <div class="column col text-right"><?php _e('Action', 'lwcommerce'); ?></div> -->
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
                                    <i class="form-icon"></i> <?php _e('Active', 'lwcommerce'); ?>
                                </label>

                            </div>
                        </div>

                        <!-- Jenis -->
                        <div class="column col-2 method  text-center">
                            <h6 style="padding-top: 8px;"><?php esc_attr_e(ucfirst($shipping_data->type)) ?></h6>
                        </div>

                        <!-- Manage Button -->
                        <!-- <div class="column text-right">
                            <button class="btn lwpc-shipping-manager" id="<?php echo $shipping_id; ?>">
                                <?php _e('Manage', 'lwcommerce'); ?>
                            </button>
                        </div> -->

                    </div>

                    <!-- Services -->
                    <div class="services-bar" style="width:100%;border-top:1px solid #ddd;">
                        <?php _e('Services', 'lwcommerce'); ?> :
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

                        .form-checkbox {
                            display: inline-block;
                            margin-left: 4px;
                        }
                    </style>
                </li>

            <?php endif; ?>
        <?php endforeach; ?>
    </ul>

<?php endif; ?>


<!-- Panel Editor on Manage Click -->
<div class="sidebar-panel">
    <div id="lwpc-shipping-manager-editor" class="column panel panel-style">

        <div class="panel-header text-center">
            <div class="panel-title h5 mt-10 float-left"><?php _e('Pengiriman Digital via Email', 'lokuswp'); ?></div>
            <div class="panel-close float-right"><i class="icon icon-cross"></i></div>
        </div>

        <div class="panel-body">
        <!-- <div class="container-shimmer">
            <div class="content-shimmer">
                <div class="form-shimmer shimmer"></div>
                <div class="form-shimmer shimmer"></div>
                <div class="form-shimmer shimmer"></div>
                <div class="form-shimmer shimmer"></div>
            </div>
        </div> -->

        <style>
            /* Action Tab */
            #tab-log:checked~.tab-body-wrapper #tab-body-log,
            #tab-delivered:checked~.tab-body-wrapper #tab-body-delivered,
            #tab-settings:checked~.tab-body-wrapper #tab-body-settings {
                position: relative;
                top: 0;
                opacity: 1;
            }

            .tab-body-wrapper .table-log th {
                display: inline-block;
            }

            .tab-body-wrapper .table-log tr {
                margin-bottom: 0;
            }

            .tab-body-wrapper .table-log tbody tr td {
                display: inline-block;
                padding: 10px;
            }

            .tab-body-wrapper .table-log.table td,
            .tab-body-wrapper .table-log.table th {
                border-bottom: 0;
            }
        </style>

        <div class="tabs-wrapper">
            <input type="radio" name="shipping" id="tab-log" checked="checked" />
            <label class="tab" for="tab-log"><?php _e('Log', 'lwcommerce'); ?></label>

            <input type="radio" name="shipping" id="tab-delivered" />
            <label class="tab" for="tab-delivered"><?php _e('Delivered', 'lwcommerce'); ?></label>

            <!-- <input type="radio" name="shipping" id="tab-settings" />
			<label class="tab" for="tab-settings"><?php _e('Settings', 'lwcommerce'); ?></label> -->

            <div class="tab-body-wrapper">

                <!------------ Tab : Log ------------>
                <div id="tab-body-log" class="tab-body">

                    <div class="divider" data-content="Test Email Sent"></div>
                    <div class="input-group" style="width:50%;">
                        <input id="lwcommerce_email_test" style="margin-top:3px;" class="form-input input-md" type="email" placeholder="email@gmail.com">
                        <button id="lwcommerce_email_sendtest" style="margin-top:3px;" class="btn btn-primary input-group-btn"><?php _e('Test Email', 'lwcommerce'); ?></button>
                    </div>
                    <br>

                    <table class="table-log table table-striped table-hover">
                        <tbody>

                        </tbody>
                    </table>
                </div>

                <!------------ Tab : On Unpaid ------------>
                <div id="tab-body-delivered" class="tab-body">

                    <div class="columns col-gapless">
                     
                        <!-- Email Preview -->
                        <div class="column col-12">
                        <br>
                            <!-- Name -->
                            <div class="form-group">
                                <div class="col-3 col-sm-12">
                                    <label class="form-label" for="subject"><?php _e('Subject', 'lwcommerce'); ?></label>
                                </div>
                                <div class="col-5 col-sm-12">
                                    <input type="text" class="form-input" name="subject" placeholder="<?php _e( "Digital Product Delivery", "lwcommerce" ); ?>" value="" />
                                </div>
                            </div>

                            <br>

                            <!-- TODO :: Using Gutenberg Block -->
                            <div id="lwcommerce-email-editor-unpaid" class="penplate">
                                <?php
                                // Personalize Template Exist ?
                                // if (file_exists(LOKUSWP_STORAGE . '/status-unpaid-' . $this->country . '.html')) {
                                // 	require_once LOKUSWP_STORAGE . '/status-unpaid-' . $this->country . '.html';
                                // } else {
                                require_once LWC_PATH . 'src/templates/emails/shipping-digital-download.html'; // Load Default
                                // }
                                ?>
                            </div>
                        </div>

                    </div>
                </div>

                <!------------ Tab : Settings ------------>
                <div id="tab-body-settings" class="tab-body">
                    <form class="form-horizontal" block="settings">

                        <!-- Sender -->
                        <div class="form-group">
                            <div class="col-3 col-sm-12">
                                <label class="form-label" for="country"><?php _e('Sender', 'lwcommerce'); ?></label>
                            </div>
                            <div class="col-9 col-sm-12">
                                <input class="form-input" type="text" name="sender" placeholder="LokusWP" style="width:320px" value="<?php echo $this->sender; ?>">
                            </div>
                        </div>

                        <!-- Sender Email -->
                        <div class="form-group">
                            <div class="col-3 col-sm-12">
                                <label class="form-label" for="country"><?php _e('Sender Email', 'lwcommerce'); ?></label>
                            </div>
                            <div class="col-9 col-sm-12">
                                <input class="form-input" type="email" name="sender_email" placeholder="noreply@lwcommerce.com" style="width:320px" value="<?php echo $this->sender_email; ?>">
                            </div>
                        </div>

                        <button class="btn btn-primary lwcommerce_admin_option_save" option="lwcommerce_notification_email" style="width:120px">
                            <?php _e('Save', 'lwcommerce'); ?>
                        </button>
                    </form>
                </div>

            </div>
        </div>
        </div>

    </div>
</div>

<style>
    .sidebar-panel {
        position: fixed;
        right: 0;
        z-index: 9999;
        height: 96%;
        width: 720px;
        display: none;
        top: 28px;
    }

    .panel-style {
        height: 100%;
        background: #fff;
        margin-right: -10px;
    }

    .panel .panel-body {
        width: 100%;
        overflow-x: auto;
        height: 77vh;
        margin-bottom: 12px;
    }

    .panel .panel-header {
        padding-bottom: 20px !important;
    }

    .panel .panel-footer {
        padding: 5px 15px;
    }
</style>