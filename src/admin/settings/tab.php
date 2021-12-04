<?php
/**
 * Display tabs in LSDDonation admin settings
 * 
 * @since 3.0.0
 */
// use LSDDonation\License;

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
?>

<div class="wrap lwpcommerce-admin">

    <?php $tab_lists = lwpc_tab_lists(); ?>
    
    <div class="column col-12 col-sm-12 px-0">
        <ul class="tab tab-primary">

            <?php //if ( $tab_lists && License::correct() ): // Display all tabs only license valid ?>
                <?php foreach ( (array) $tab_lists as $key => $title): ?>
                    <li class="tab-item <?php if ($active_tab == $key) {echo 'active';}?>">
                        <a href="?page=lwpcommerce&tab=<?php esc_attr_e($key);?>"><?php echo esc_attr($title); ?></a>
                    </li>
                <?php endforeach;?>
            <?php //endif;?>


        </ul>
    </div>


    <article class="tab-content">
        <?php
        // VULN :: Local/Remote File Inclusion
        // @link https://ismailtasdelen.medium.com/remote-local-file-inclusion-94f4403f24a7

        if (isset($_GET["tab"])) {
            $tabs_query = sanitize_text_field( htmlentities($_GET["tab"], ENT_QUOTES) );

            if ($tab_lists) {

                // Request not Available on List -> Call License Section
                if(!array_key_exists( $tabs_query, (array) $tab_lists ) && $tabs_query != 'app' ){
                    require_once 'tabs/system.php';
                }

                foreach ((array) $tab_lists as $key => $item) {
                    if ($tabs_query == $key || $active_tab == $key) {

                        // Called Using Registered Hook Only, Preventing Injection From Query String
                        if( has_action("lwpcommerce/admin/tabs/{$key}") ){
                            do_action("lwpcommerce/admin/tabs/{$key}");
                        }

                    } else if ($tabs_query == 'app') {
                        require_once 'tabs/app.php';
                    } else if ($tabs_query == 'system') {
                        require_once 'tabs/system.php';
                    }
                }
                
            } else if ($tabs_query == 'app') {
                require_once 'tabs/app.php';
            } else if ($tabs_query == 'system') {
                require_once 'tabs/system.php';
            }
        } else { //Fallback
            // if ( License::correct()  ) {
            //     require_once 'tabs/settings.php';
            // } else {
            //     require_once 'tabs/app.php';
            // }
        }
        ?>
    </article>
</div>