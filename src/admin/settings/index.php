<?php

use LokusWP\Admin\Tabs;


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Display tabs based on query string
 * default display is instituion
 *
 * @since 4.0.0
 */
if ( isset( $_GET["tab"] ) ) {
	if ( htmlentities( $_GET["tab"], ENT_QUOTES ) == "settings" ) {
		$active_tab = "settings";
	} else {
		$active_tab = htmlentities( $_GET["tab"], ENT_QUOTES );
	}
} else {
	$active_tab = "settings";
}

do_action( "lwcommerce/wp-admin/settings" );
// if ($_GET["page"] == "lwcommerce" ) {
//     require_once 'onboarding/onboarding.php';
// }else{
//Tabs::add( 'lwcommerce', 'addons', __( 'Addons', 'lwcommerce' ), function () {
//	require_once 'settings/tabs/addons.php';
//} );

?>
    <style>
        .notice {
            display: none;
        }
    </style>
    <div class="wrap lwcommerce-admin">

		<?php $tab_lists = Tabs::list( "lwcommerce" ); ?>

        <div class="column col-12 col-sm-12 px-0">
            <!--            <div class="user-avatar">-->
            <!--                <figure class="avatar mr-2"><img src="http://2.gravatar.com/avatar/e43a042ed65693a74a1de21be9eed014?s=64&d=mm&r=g" alt="Avatar LG"></figure>-->
            <!--            </div>-->
            <ul class="tab tab-primary">

				<?php foreach ( (array) $tab_lists as $key => $title ) : ?>
                    <li class="tab-item <?php echo $active_tab == $key ? 'active' : ''; ?>">
                        <a href="?page=lwcommerce&tab=<?php esc_attr_e( $key ); ?>"><?php echo esc_attr( $title ); ?></a>
                    </li>
				<?php endforeach; ?>

            </ul>
        </div>

        <style>

            .user-avatar {
                float: right;
                margin: 4px;
            }

            .lwcommerce-admin li {
                margin-bottom: 0;
            }
        </style>


        <article class="tab-content">

			<?php
			// VULN :: Local/Remote File Inclusion
			// @link https://ismailtasdelen.medium.com/remote-local-file-inclusion-94f4403f24a7

			if ( isset( $_GET["tab"] ) ) {
				$tabs_query = sanitize_text_field( htmlentities( $_GET["tab"], ENT_QUOTES ) );

				if ( $tab_lists ) {

					// Request not Available on List -> Call License Section
					if ( ! array_key_exists( $tabs_query, (array) $tab_lists ) && $tabs_query != 'app' ) {
						//require_once 'tabs/addons.php';
					}

					foreach ( (array) $tab_lists as $key => $item ) {
						if ( $tabs_query == $key || $active_tab == $key ) {

							// Called Using Registered Hook Only, Preventing Injection From Query String
							if ( has_action( "lwcommerce/admin/tabs/{$key}" ) ) {
								do_action( "lwcommerce/admin/tabs/{$key}" );
							}
						} else if ( $tabs_query == 'addons' ) {
							// 'tabs/addons.php';
						}
					}
				} else if ( $tabs_query == 'app' ) {
					//	require_once 'tabs/addons.php';
				}
			} else { //Fallback
				require_once 'tabs/settings.php';
			}
			?>
        </article>
    </div>

<?php // } ?>