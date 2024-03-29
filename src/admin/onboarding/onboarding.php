<?php

/*****************************************
 * Template Screen :: Onboarding
 * Display UI Onboarding to User
 *
 * @since 0.1.0
 ***************************************
 */
// Check User Logged In
if ( ! is_user_logged_in() ) {
	wp_redirect( get_site_url() );
	exit;
}
?>

<style>
    .empty {
        background: #fff !important;
        padding-bottom: 18px;
    }

    .center {
        text-align: center;
        margin: 60px auto 30px;
    }

    .w-120 {
        min-width: 120px;
    }

    .step-to-integration {
        float: right;
        margin-top: -44px;
    }

    .tile.tile-centered {
        border: 1px solid #ddd;
        padding: 12px;
        margin: 8px auto;
    }

    .tile-icon {
        padding: 7px 14px;
    }

    .hidden {
        display: none !important;
    }
</style>

<div class="columns col-12">

    <div class="col-6" style="margin: 30px auto;">
        <header class="navbar">
            <section class="navbar-section">
                <!--<a href="#" class="btn btn-link">LokusWP</a>-->
                <!--<a href="#" class="btn btn-link">Showcase</a>-->
            </section>
            <section class="navbar-center">
                <img src="<?php echo LWC_URL . '/src/admin/assets/images/lwcommerce.png' ?>" alt="lwcommerce"
                     width="60" style="display: block">
            </section>
            <section class="navbar-section">
                <!--<a href="#" class="btn btn-link">Community</a>-->
                <!--<a href="#" class="btn btn-link">Guide</a>-->
            </section>
        </header>
        <h3 style="display: block;text-align: center;font-size: 20px;margin-top: 14px;font-weight: 600;">Setup LWCommerce</h3>
        <br><br>

        <ul class="step">
            <li class="step-item">
                <a href="#" class="tooltip"
                   data-tooltip="<?php _e( "Dependency", "lwcommerce" ); ?>"><?php _e( "Dependency", "lwcommerce" ); ?></a>
            </li>
            <li class="step-item ">
                <a href="#" class="tooltip"
                   data-tooltip="<?php _e( "Store", "lwcommerce" ); ?>"><?php _e( "Store", "lwcommerce" ); ?></a>
            </li>
            <li class="step-item active">
                <a href="#" class="tooltip"
                   data-tooltip="<?php _e( "Guide", "lwcommerce" ); ?>"><?php _e( "Guide", "lwcommerce" ); ?></a>
            </li>
        </ul>
        <br>

        <div id="dependency-step">
            <div class="empty">
                <br>
                <div class="empty-icon">
                    <img src="<?php echo LWC_URL . '/src/admin/assets/images/lokuswp.png' ?>" alt="lokuswp"
                         width="60px">
                    <div class="loading loading-lg" style="margin-top: -55px;"></div>
                </div>

                <p class="empty-title h5"><?php _e( "Downloading LokusWP Backbone...", "lwcommerce" ); ?></p>
                <p class="empty-subtitle"><?php _e( "Please wait a moment until this process is complete", "lwcommerce" ); ?></p>
            </div>
        </div>

        <div id="store-step" class="hidden">
            <div class="column col-12 col-sm-12 px-0" id="manage-store">
                <button class="btn w-120 step-to-integration hidden"><?php _e( 'Continue', 'lwcommerce' ); ?></button>
            </div>
        </div>

        <div id="integration-step" class="hidden">
            <iframe width="660" height="390" src="https://www.youtube.com/embed/xvvs5zTUXlw" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>

<!--            <div id="dripsender" class="tile tile-centered">-->
<!--                <div class="tile-icon">-->
<!--                    <div class="example-tile-icon">-->
<!--                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none"-->
<!--                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"-->
<!--                             class="feather feather-credit-card">-->
<!--                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>-->
<!--                            <line x1="1" y1="10" x2="23" y2="10"></line>-->
<!--                        </svg>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="tile-content">-->
<!--                    <div class="tile-title">Whatsapp Notification - DripSender</div>-->
<!--                    <small class="tile-subtitle text-gray">Whatsapp Gateway</small>-->
<!--                </div>-->
<!--                <div class="tile-action">-->
<!--                    <a class="btn btn-link"-->
<!--                       href="--><?php //echo get_admin_url() . 'admin.php?page=lokuswp&tab=marketplace'; ?><!--">-->
<!--                        <i class="icon icon-time"></i>-->
<!--						--><?php //_e( "Install", "lwcommerce" ); ?>
<!--                    </a>-->
<!--                </div>-->
<!--            </div>-->
<!---->
<!--            <div id="fonnte" class="tile tile-centered">-->
<!--                <div class="tile-icon">-->
<!--                    <div class="example-tile-icon">-->
<!--                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none"-->
<!--                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"-->
<!--                             class="feather feather-credit-card">-->
<!--                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>-->
<!--                            <line x1="1" y1="10" x2="23" y2="10"></line>-->
<!--                        </svg>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="tile-content">-->
<!--                    <div class="tile-title">Whatsapp Notification - Fonnte</div>-->
<!--                    <small class="tile-subtitle text-gray">Whatsapp Gateway</small>-->
<!--                </div>-->
<!--                <div class="tile-action">-->
<!--                    <a class="btn btn-link"-->
<!--                       href="--><?php //echo get_admin_url() . 'admin.php?page=lokuswp&tab=marketplace'; ?><!--">-->
<!--                        <i class="icon icon-time"></i>-->
<!--						--><?php //_e( "Install", "lwcommerce" ); ?>
<!--                    </a>-->
<!--                </div>-->
<!--            </div>-->

            <div class="center">
                <a href="<?php echo get_admin_url() . 'post-new.php?post_type=product'; ?>"
                   class="btn btn-primary step-to-complete">
					<?php _e( "Add New Product", "lwcommerce" ); ?>
                </a>
                <a href="<?php echo get_site_url() . '/products/'; ?>" class="btn">
					<?php _e( "See Product Listing", "lwcommerce" ); ?>
                </a>
            </div>

        </div>
    </div>
</div>