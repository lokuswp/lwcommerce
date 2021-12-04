<?php

use LSDDonation\Payments;

/*********************************************/
/* Displaying Payments Menu Registered
/* wp-admin -> LSDDonation -> Payments
/********************************************/

if (!defined('ABSPATH')) {
  exit;
}

class Payments_Admin
{
  public function __construct()
  {
?>
    <section id="payments">

      <div class="container columns col-gapless header">
        <div class="column col-6"><?php _e('Metode', 'lsddonation'); ?></div>
        <div class="column col-2"><?php _e('Status', 'lsddonation'); ?></div>
        <div class="column col-2"><?php _e('Konfirmasi', 'lsddonation'); ?></div>
        <div class="column col-2 text-right"><?php _e('Aksi', 'lsddonation'); ?></div>
      </div>

      <?php
      // $payment_sorted = (array) lsdd_payment_sorted();
      // $payment_settings = lsdd_payment_settings();
      $payment_sorted  = [];
      ?>
      <pre><?php //update_option( 'lsdd_payment_sorted', null ); ?></pre>
      
      <?php if ($payment_sorted) : $cache = array(); ?>
        <ul class="methods" id="draggable">
          <?php foreach ($payment_sorted as $payment_id) : $obj = null; ?>

            <?php
            //  Key template class empty @support old structured
            if (isset($payment_settings[$payment_id]) && !array_key_exists("template_class", $payment_settings[$payment_id])) {
              unset($payment_settings[$payment_id]);
              update_option('lsdd_payment_settings', $payment_settings);
              continue;
            }

            // FIXME :: Remove Replaced Array 4.2.0
            if( array_search('e-money', $payment_settings['static_qr'] ) ){
              $payment_settings['static_qr']['group'] = 'e_money';
              update_option('lsdd_payment_settings', $payment_settings);
            }

            $obj = lsdd_payment_cache_object($payment_settings, $payment_id, $cache);

            if ($obj == 'continue') {
              continue;
            }

            $item = $payment_settings[$payment_id];
            ?>

            <li class="draggable" draggable="true">
              <div class="columns col-gapless">

                <!-- Method -->
                <div class="column col-6 method" style="margin-bottom: -8px;">
                  <?php if (isset($item['logo']) && $item['logo'] != '') : ?>
                    <img class="lsdp-float-left" src="<?php echo $item['logo']; ?>" alt="<?php echo $item['name']; ?>" style="height:40px;">
                  <?php endif; ?>

                  <h6 class="lsdp-float-left" style="padding: 0px 10px 0;">
                    <?php echo isset($item['name']) ? $item['name'] : ''; ?>
                    <small>ID : <?php echo $payment_id; ?></small>
                  </h6>
                </div>

                <!-- Status -->
                <div class="column col-2 lsdd-payment-status">
                  <div class="form-group">

                    <label class="form-switch">
                      <input type="checkbox" id="<?php echo $payment_id . '_status'; ?>" <?php echo ($obj->get_status($payment_id) == 'on') ? 'checked' : ''; ?>>
                      <i class="form-icon"></i> <?php _e('Aktif', 'lsddonation'); ?>
                    </label>
                  </div>
                </div>

                <!-- Confirmation Type -->
                <div class="column col-2 confirmation">
                  <?php if ($payment_settings[$payment_id]['confirmation'] == 'manual') : ?>
                    <span class="label label-secondary"><?php _e('Manual', 'lsddonation'); ?></span>
                  <?php else : ?>
                    <span class="label label-success"><?php _e('Otomatis', 'lsddonation'); ?></span>
                  <?php endif; ?>
                </div>

                <!-- Manage Button -->
                <div class="column col-2 text-right">
                  <button class="btn lsdd-payment-manage" id="<?php echo $payment_id; ?>" data-instance="<?php echo $payment_id; ?>"><?php _e('Kelola', 'lsddonation'); ?></button>
                </div>

              </div>
            </li>

            <?php // AJAX Manage // $obj->manage(); 
            ?>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </section>

    <button class="btn btn-primary mt-2 float-right" id="lsdd-payment-transferbank-create">
      <?php _e('Buat Metode Pembayaran Transfer Bank', 'lsddonation'); ?>
    </button>
<?php
  }
}
new Payments_Admin();
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

  #container-shimmer {
    width: 100%;
    height: 280px;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  #content-shimmer {
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
    height: 80vh;
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

    <div id="container-shimmer">
      <div id="content-shimmer">
        <div class="form-shimmer shimmer"></div>
        <div class="form-shimmer shimmer"></div>
        <div class="form-shimmer shimmer"></div>
        <div class="form-shimmer shimmer"></div>
      </div>
    </div>

  </div>
</div>

<!-- Draggable AJAX Sender -->
<script>
  function lsddSaveSortedPayments(payments) {
    var formData = new FormData();
    formData.append('action', 'lsdd_admin_payment_sorting');
    formData.append('security', lsdd_admin.ajax_nonce);

    for (var i = 0; i < payments.length; i++)
      formData.append('payments[' + i + ']', payments[i]);
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function() {
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