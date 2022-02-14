<?php

/**
 * Display tabs in LSDDonation admin settings
 * 
 * @since 3.0.0
 */

use LokusWP\Admin\Tabs;


if (!defined('ABSPATH')) {
    exit;
}


/**
 * Display tabs based on query string
 * default display is instituion
 * 
 * @since 4.0.0
 */
if (isset($_GET["tab"])) {
    if (htmlentities($_GET["tab"], ENT_QUOTES) == "settings") {
        $active_tab = "settings";
    } else {
        $active_tab = htmlentities($_GET["tab"], ENT_QUOTES);
    }
} else {
    $active_tab = "settings";
}


// if ($_GET["page"] == "lwpcommerce" ) {
//     require_once 'onboarding/onboarding.php';
// }else{



    /**
     * Default Admin Tabs
     */
    Tabs::add('lwpcommerce', 'extensions', __('Extensions', 'lwpcommerce'), function () {
        require_once 'tabs/extensions.php';
    });
?>
    <style>
        .notice{
            display: none;
        }
    </style>
    <div class="wrap lwpcommerce-admin">

        <?php $tab_lists = Tabs::list("lwpcommerce"); ?>

        <div class="column col-12 col-sm-12 px-0">
            <div class="user-avatar">
                <figure class="avatar mr-2"><img src="http://2.gravatar.com/avatar/e43a042ed65693a74a1de21be9eed014?s=64&d=mm&r=g" alt="Avatar LG"></figure>
            </div>
            <ul class="tab tab-primary">

                <?php foreach ((array) $tab_lists as $key => $title) : ?>
                    <li class="tab-item <?php echo $active_tab == $key ? 'active' : ''; ?>">
                        <a href="?page=lwpcommerce&tab=<?php esc_attr_e($key); ?>"><?php echo esc_attr($title); ?></a>
                    </li>
                <?php endforeach; ?>
    
            </ul>
        </div>

        <style>

            .user-avatar{
                float:right;
                margin: 4px;
            }
            .lwpcommerce-admin li {
                margin-bottom: 0;
            }
        </style>


        <article class="tab-content">
   
            <?php
            // VULN :: Local/Remote File Inclusion
            // @link https://ismailtasdelen.medium.com/remote-local-file-inclusion-94f4403f24a7

            if (isset($_GET["tab"])) {
                $tabs_query = sanitize_text_field(htmlentities($_GET["tab"], ENT_QUOTES));

                if ($tab_lists) {

                    // Request not Available on List -> Call License Section
                    if (!array_key_exists($tabs_query, (array) $tab_lists) && $tabs_query != 'app') {
                        require_once 'tabs/extensions.php';
                    }

                    foreach ((array) $tab_lists as $key => $item) {
                        if ($tabs_query == $key || $active_tab == $key) {

                            // Called Using Registered Hook Only, Preventing Injection From Query String
                            if (has_action("lwpcommerce/admin/tabs/{$key}")) {
                                do_action("lwpcommerce/admin/tabs/{$key}");
                            }
                        } else if ($tabs_query == 'app') {
                            require_once 'tabs/app.php';
                        }
                    }
                } else if ($tabs_query == 'app') {
                    require_once 'tabs/app.php';
                }
            }

            // else { //Fallback
            // if ( License::correct()  ) {
            //     require_once 'tabs/settings.php';
            // } else {
            //     require_once 'tabs/app.php';
            // }
            // }
            ?>
        </article>
    </div>

<?php // } ?>