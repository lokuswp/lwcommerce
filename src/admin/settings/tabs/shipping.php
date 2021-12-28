<?php

use LokusWP\Commerce\Shipping;

/*********************************************/
/* Displaying Payments Menu Registered
/* wp-admin -> LSDDonation -> Payments
/********************************************/

if ( ! defined('WPTEST')) {
    defined('ABSPATH') or die("Direct access to files is prohibited");
}

class Shipping_Admin
{

    public function __construct()
    {
        /**
         * Hook before payment method
         * @undocs
         */
        do_action("lwpcommerce/admin/shipping/before");
        ?>

        <section id="lwp-backbone-shipping">

            Tab : Settings | Zone | RajaOngkir | RajaOngkirPro

            <!-- <div class="container-shimmer">
			  <div class="content-shimmer">
				<div class="form-shimmer shimmer"></div>
				<div class="form-shimmer shimmer"></div>
				<div class="form-shimmer shimmer"></div>
				<div class="form-shimmer shimmer"></div>
			  </div>
			</div> -->

            <div class="container columns col-gapless header">
                <div class="column col-2"><?php _e('Channel', 'lwpbackbone'); ?></div>
                <div class="column col-2 text-center"><?php _e('Zone', 'lwpbackbone'); ?></div>
                <div class="column col-2 text-center"><?php _e('Paket', 'lwpbackbone'); ?></div>
                <div class="column col-2 text-center"><?php _e('Status', 'lwpbackbone'); ?></div>
                <div class="column col-2 text-center"><?php _e('Jenis', 'lwpbackbone'); ?></div>
                <div class="column col text-right"><?php _e('Manage', 'lwpbackbone'); ?></div>
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

                                    <!-- Paket -->
                                    <div class="column col-2" style="display: grid;align-content: center;align-items: center;font-weight: bold">
                                        <?php foreach ($shipping_data->package as $key => $package) : ?>
                                            <div>
                                                <input type="checkbox" id="<?php esc_attr_e($key) ?>" class="lwpc_shipping_package_status"
                                                       value="<?php esc_attr_e($key) ?>" <?= ($package === 'on') ? 'checked' : '' ?>
                                                       data-action="<?= $shipping_id ?>">
                                                <label for="<?php esc_attr_e($key) ?>"><?php esc_attr_e($key) ?></label>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>

                                    <!-- Status -->
                                    <div class="column col-2" style="display: flex; justify-content: center">
                                        <div class="form-group">

                                            <label class="form-switch">
                                                <input type="checkbox" id="<?php echo $shipping_id; ?>"
                                                    <?php echo ($shipping_obj->get_status() == 'on') ? 'checked' : ''; ?>>
                                                <i class="form-icon"></i> <?php _e('Aktif', 'lwpbackbone'); ?>
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
                                            <?php _e('Kelola', 'lwpbackbone'); ?>
                                        </button>
                                    </div>

                                </div>
                            </li>

                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>

            <?php endif; ?>
        </section>

        <?php
        /**
         * Hook after payment method
         * @undocs
         */
        do_action("lwpbackbone/admin/payment/after");
    }
}

new Shipping_Admin();
?>

<!-- Panel Editor -->
<style>
    .pane {
        position: fixed;
        right: 0;
        z-index: 9999;
        height: 96%;
        width: 400px;
        display: none;
        top: 28px;
    }

    .panel-style {
        height: 100%;
        background: #fff;
        margin-right: -10px;
    }

    .header {
        padding: 15px 15px 0;
    }

    .methods {
        margin: 0;
    }

    .methods li {
        list-style: none;
    }

    .draggable {
        padding: 10px 15px;
        background: #fff;
        cursor: grab;
        border: 1px solid #ddd;
    }

    .dragging {
        border: 2px dashed #000;
        opacity: 1;
    }

    .method small {
        display: block;
    }

    .confirmation span {
        padding: 5px 10px;
    }

    /* @source :: https://codepen.io/arsh-shaikh/pen/QWdXWoX */
    .shimmer {
        position: relative;
        background: #f6f7f8;
        background-image: linear-gradient(to right, #f6f7f8 0%, #f2f4f7 10%, #f0f0f2 20%, #f2f4f7 30%, #f6f7f8 40%, #f6f7f8 100%);
        background-repeat: no-repeat;
        background-size: 800px 200px;
    }

    @-webkit-keyframes shimmer {
        0% {
            background-position: -400px 0;
        }

        100% {
            background-position: 400px 0;
        }
    }

    .container-shimmer {
        width: 100%;
        height: 280px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .content-shimmer {
        flex: 1;
        width: 100%;
        padding: 0.5rem 1rem 0 1rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        align-items: flex-end;
    }

    .form-shimmer {
        width: 100%;
        height: 44px;
        margin-bottom: 12px;
    }

    .panel .panel-body {
        width: 100%;
        overflow-x: auto;
        height: 77vh;
        margin-bottom: 12px;
    }

    .panel .panel-header {
        padding-bottom: 40px;
    }

    .panel .panel-footer {
        padding: 5px 15px;
    }
</style>

<!-- Panel Editor on Manage Click -->
<div class="column pane">
    <div id="payment-editor" class="panel panel-style">

        <!-- <div class="container-shimmer">
		  <div class="content-shimmer">
			<div class="form-shimmer shimmer"></div>
			<div class="form-shimmer shimmer"></div>
			<div class="form-shimmer shimmer"></div>
			<div class="form-shimmer shimmer"></div>
		  </div>
		</div> -->

    </div>
</div>

<!-- Draggable AJAX Sender -->
<script>
    function lsddSaveSortedPayments(shipping) {
        var formData = new FormData();
        formData.append('action', 'lsdd_admin_payment_sorting');
        formData.append('security', lsdd_admin.ajax_nonce);

        for (var i = 0; i < shipping.length; i++)
            formData.append('shipping[' + i + ']', shipping[i]);
        var xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function () {
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
                //console.log(xmlHttp.responseText);
            }
        }
        xmlHttp.open("post", ajaxurl);
        xmlHttp.send(formData);
    }

    const draggables = document.querySelectorAll('.draggable')
    const containers = document.querySelectorAll('.methods')

    draggables.forEach(draggable => {
        draggable.addEventListener('dragstart', (event) => {
            draggable.classList.add('dragging')
        })

        draggable.addEventListener('dragend', (event) => {
            draggable.classList.remove('dragging')
            // sending to reoder payment

            var idx = 0
            var sorted = []
            document.querySelectorAll('li.draggable .lsdd-payment-manage').forEach(instance => {
                sorted[idx] = instance.getAttribute('data-instance')
                idx++
            })
            lsddSaveSortedPayments(sorted)

        })
    })

    containers.forEach(container => {
        container.addEventListener('dragover', e => {
            e.preventDefault()

            const afterElement = getDragAfterElement(container, e.clientY)
            const draggable = document.querySelector('.dragging')
            if (afterElement == null) {
                container.appendChild(draggable)
            } else {
                container.insertBefore(draggable, afterElement)
            }
        })
    })

    function getDragAfterElement(container, y) {
        const draggableElements = [...container.querySelectorAll('.draggable:not(.dragging)')]

        return draggableElements.reduce((closest, child) => {
            const box = child.getBoundingClientRect()
            const offset = y - box.top - box.height / 2
            if (offset < 0 && offset > closest.offset) {
                return {
                    offset: offset,
                    element: child
                }
            } else {
                return closest
            }
        }, {
            offset: Number.NEGATIVE_INFINITY
        }).element
    }
</script>