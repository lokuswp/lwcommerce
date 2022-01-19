<?php

/**
 * Template : Settings
 * for Admin LWPCommerce
 * 
 */
?>
<section id="settings" class="form-horizontal">

    <div class="tab-nested">
        <input type="radio" name="tab" id="tab1" checked="checked" />
        <label class="tab-item" for="tab1"><?php _e("Store", "lwpcommerce"); ?></label>

        <input type="radio" name="tab" id="tab2" />
        <label class="tab-item" for="tab2"><?php _e("Appearance", "lwpcommerce"); ?></label>

        <div class="tab-body-component">
            <div id="tab-body-1" class="tab-body">
                <?php require_once LWPC_PATH . 'src/admin/settings/tabs/settings/store.php'; ?>
            </div>
            <div id="tab-body-2" class="tab-body">
                <?php require_once LWPC_PATH . 'src/admin/settings/tabs/settings/appearance.php'; ?>
            </div>
        </div>
    </div>

</section>

<style>
    #tab1:checked~.tab-body-component #tab-body-1,
    #tab2:checked~.tab-body-component #tab-body-2 {
        position: relative;
        top: 0;
        opacity: 1
    }
</style>