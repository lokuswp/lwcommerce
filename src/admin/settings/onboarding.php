<?php
// Check User Logged In
if(!is_user_logged_in()){
    wp_redirect(get_site_url());
    exit;
}
require_once LWC_PATH . 'src/includes/helper/func-helper.php';
?>
<div class="columns col-12">


    <div class="col-6" style="margin: 30px auto;">
        <header class="navbar">
            <section class="navbar-section">
                <a href="#" class="btn btn-link">LokusWP</a>
                <a href="#" class="btn btn-link">Showcase</a>
            </section>
            <section class="navbar-center">
                <img src="<?php echo LWC_URL . '/src/admin/assets/lwcommerce.png'?>" alt="lwcommerce" width="30px">
            </section>
            <section class="navbar-section">
                <a href="#" class="btn btn-link">Community</a>
                <a href="#" class="btn btn-link">Guide</a>
            </section>
        </header>

        <br> <br>
        <ul class="step">
            <li class="step-item">
                <a href="#" class="tooltip" data-tooltip="Dependency">Dependency</a>
            </li>
            <li class="step-item ">
                <a href="#" class="tooltip" data-tooltip="Store">Store</a>
            </li>
            <li class="step-item active">
                <a href="#" class="tooltip" data-tooltip="Integration">Integration</a>
            </li>
        </ul>

        <br>

        <div id="dependency-step">
            <div class="empty">
                <br>
                <div class="empty-icon">
                    <img src="<?php echo LWC_URL . '/src/admin/assets/lokuswp.png'?>" alt="lokuswp" width="60px">
                    <div class="loading loading-lg" style="margin-top: -55px;"></div>
                </div>

                <p class="empty-title h5"><?php _e( "LokusWP Backbone Downloading...", "lwcommerce" ); ?></p>
                <p class="empty-subtitle"><?php _e( "Please wait a moment until this process is complete", "lwcommerce" ); ?></p>

            </div>
        </div>

        <div id="store-step" class="hidden">
            <div class="column col-12 col-sm-12 px-0">
            <?php require_once LWC_PATH . 'src/admin/settings/tabs/settings/store.php'; ?>
                <button class="btn w-120 step-to-integration"><?php _e('Continue', 'lwcommerce'); ?></button>
            </div>
        </div>

        <div id="integration-step" class="hidden">

            <div class="empty">
            <p class="empty-title h5"><?php _e( "Integration", "lwcommerce" ); ?></p>
            <p class="empty-subtitle"><?php _e( "You may need this extension for add new ability", "lwcommerce" ); ?></p>
            </div>

            <div class="tile tile-centered">
                <div class="tile-icon">
                    <div class="example-tile-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-credit-card"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
                    </div>
                </div>
                <div class="tile-content">
                    <div class="tile-title">OY Indonesia - E Wallet</div>
                    <small class="tile-subtitle text-gray">Payment Gateway · Free · 22 Mar 2022</small>
                </div>
                <div class="tile-action">
                    <button class="btn btn-link">
                        <i class="icon icon-download"></i>
                    </button>
                </div>
            </div>

            <div class="center">
                <a href="<?php echo get_admin_url() . 'post-new.php?post_type=product'; ?>" class="btn btn-primary step-to-complete">
                    <?php _e( "Add New Product", "lwcommerce" ); ?>
                </a>
                <a href="https://panduan.lokuswp.com/" class="btn">
		            <?php _e( "Read Guide", "lwcommerce" ); ?>
                </a>
            </div>

        </div>
    </div>

</div>

<style>

    .empty {
        background: #fff !important;
        padding-bottom: 18px;
    }

    .center{
        text-align: center;
        margin: 60px auto 30px;
    }

    .w-120{
        min-width: 120px;
    }
    .step-to-integration {
        float: right;
        margin-top: -44px;
    }
</style>


<script>
    (function($) {
        'use strict';

        $(document).ready(function() {

                 $.ajax({
                    url: lwc_admin.ajax_url,
                    method: 'POST',
                    data: {
                        action: 'lwcommerce_download_plugin',
                        security: lwc_admin.ajax_nonce,
                    },
                    success: (res) => {
                        if (res == "ajax_success") {
                            $('#store-step').removeClass('hidden');
                            $('#dependency-step').addClass('hidden');
                        }else  if (res == "ajax_lwcommerce") {
                            window.location.href = lwc_admin.admin_url;
                        } else {
                            alert('please check your internet connection!')
                        }
                    },
                }).fail(() => alert('please check your internet connection!'));



            $('.step-to-integration').on('click', function() {
                $('#integration-step').removeClass('hidden');
                $('#store-step').addClass('hidden');
            });

        });
    })(jQuery)
</script>
