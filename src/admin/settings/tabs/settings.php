<?php

/**
 * Template : Settings
 * for Admin LWCommerce
 * 
 */

use LokusWP\Admin\Tabs;
?>

<section id="settings" class="form-horizontal">
    <?php


    Tabs::add_nested("lwcommerce", "settings", "templates", __("Templates", "lwcommerce"), function () {
	    require_once LWC_PATH . 'src/admin/settings/tabs/general/general.php';
    });


    Tabs::add_nested("lwcommerce", "settings", "options", __("Options", "lwcommerce"), function () {
	    require_once LWC_PATH . 'src/admin/settings/tabs/general/appearance.php';
    });

    Tabs::add_nested("lwcommerce", "settings", "store", __("Store", "lwcommerce"), function () {
	    require_once LWC_PATH . 'src/admin/settings/tabs/general/store.php';
    });


    Tabs::list_nested_render("lwcommerce", "settings");
    ?>
</section>
