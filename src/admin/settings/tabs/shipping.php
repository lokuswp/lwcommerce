<?php

use LokusWP\Commerce\Shipping;

if ( ! defined( 'WPTEST' ) ) {
	defined( 'ABSPATH' ) or die( "Direct access to files is prohibited" );
}

class Shipping_Admin {

	public function __construct() {
		?>
        <section id="shipping" class="form-horizontal">

            <div class="tab-nested">
                <input type="radio" name="tab" id="tab1" checked="checked"/>
                <label class="tab-item" for="tab1"><?php _e( "Channel", "lwcommerce" ); ?></label>

                <input type="radio" name="tab" id="tab2"/>
                <label class="tab-item" for="tab2"><?php _e( "Integration", "lwcommerce" ); ?></label>

                <!-- <input type="radio" name="tab" id="tab3"/>
                <label class="tab-item" for="tab3"><?php _e( "RajaOngkir", "lwcommerce" ); ?></label> -->

                <div class="tab-body-component">
                    <div id="tab-body-1" class="tab-body">
						<?php require_once 'shipping/channel.php'; ?>
                    </div>

                    <div id="tab-body-2" class="tab-body">
						<?php require_once 'shipping/integration.php'; ?>
                    </div>

                    <div id="tab-body-3" class="tab-body">
						<?php require_once 'shipping/rajaongkir.php'; ?>
                    </div>
                </div>
            </div>

        </section>

        <style>
            #tab1:checked ~ .tab-body-component #tab-body-1,
            #tab2:checked ~ .tab-body-component #tab-body-2,
            #tab3:checked ~ .tab-body-component #tab-body-3 {
                position: relative;
                top: 0;
                opacity: 1
            }
        </style>

		<?php
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