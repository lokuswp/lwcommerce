<?php

/**
 * Template : Settings
 * for Admin LWPCommerce
 * 
 */

use LokusWP\Admin\Tabs;
?>

<section id="settings" class="form-horizontal">
    <?php
    Tabs::add_nested("lwcommerce", "settings", "store", __("Store", "lwcommerce"), function () {
        require_once LWPC_PATH . 'src/admin/settings/tabs/settings/store.php';
    });

    Tabs::add_nested("lwcommerce", "settings", "appearance", __("Appearance", "lwcommerce"), function () {
        require_once LWPC_PATH . 'src/admin/settings/tabs/settings/appearance.php';
    });

    Tabs::list_nested_render("lwcommerce", "settings");
    ?>
</section>
