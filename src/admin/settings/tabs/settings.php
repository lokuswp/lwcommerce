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
    Tabs::add_nested("lwpcommerce", "settings", "store", __("Store", "lwpcommerce"), function () {
        require_once LWPC_PATH . 'src/admin/settings/tabs/settings/store.php';
    });

    Tabs::add_nested("lwpcommerce", "settings", "appearance", __("Appearance", "lwpcommerce"), function () {
        require_once LWPC_PATH . 'src/admin/settings/tabs/settings/appearance.php';
    });

    Tabs::list_nested_render("lwpcommerce", "settings");
    ?>
</section>
