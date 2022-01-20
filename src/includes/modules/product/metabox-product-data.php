<?php

namespace LokusWP\Commerce\Modules\Product;

if (!defined('WPTEST')) {
    defined('ABSPATH') or die("Direct access to files is prohibited");
}

/**
 * Metabox : Product Data
 * For Manage Product Data
 * - Price
 * - Stock
 * - Type
 */
class Metabox_Product_Data
{
    public function __construct()
    {
        add_filter('add_meta_boxes', [$this, 'metabox_register'], 0);
        add_action('save_post', [$this, 'metabox_save']);
        add_action('new_to_publish', [$this, 'metabox_save']);
    }

    protected function js_inject()
    {
?>
        <script>
            // jQuery(document).ready(function() {
            //     jQuery('body.post-type-product #postimagediv .inside').append('<p class="recommended" id="donation-recommended">Recommended image size 392 x 210px</p>');
            // });

            // jQuery(document).on('change', 'input[name="product_type"]', function() {
            //     jQuery('body.post-type-product #postimagediv .recommended').hide()
            //     if (jQuery('input[name="product_type"]:checked').val()) {
            //         jQuery('body.post-type-product #postimagediv #' + jQuery('input[name="product_type"]:checked').val().trim() + '-recommended').show();
            //     }
            // });
        </script>
    <?php
    }

    public function metabox_register()
    {
        add_meta_box(
            'product-data',
            __('Product Data', 'lwpcommerce'),
            [$this, 'metabox_product_data'],
            'product',
            'normal',
            'high'
        );

        add_meta_box(
            'product-format',
            __('Product Format', 'lwpcommerce'),
            [$this, 'metabox_product_format'],
            'product',
            'normal',
            'high'
        );

        $this->js_inject();
    }

    public function metabox_product_format()
    {
        global $post;
     ?>

                    <!-- Digital -->
                    <input name="shipping_tabs" value="digital" id="digital" type="radio" />
                    <label class="label" for="digital">
                        <?php esc_attr_e('Product Digital', 'lwpcommerce'); ?>
                    </label>

                    <div class="pane-metabox">

                        <!-- Hookable :: Extending for Upload via DropBox -->
                        <?php if (has_action("lwpcommerce/product/digital/upload")) : ?>
                            <?php do_action('lwpcommerce/product/digital/upload'); ?>
                        <?php else : ?>
                            <br>
                            <label for="digital_file" style="margin-left:10px;"><?php esc_attr_e('File', 'lwpcommerce'); ?> : </label>
                            <input type="text" class="form-input" style="width:50%" name="digital_file_url" placeholder="http://dropbox.com/file.zip" value="<?php echo get_post_meta($post->ID, '_digital_file_url', true); ?>">

                            <label for="digital_file_version"><?php esc_attr_e('Versi', 'lwpcommerce'); ?> :</label>
                            <input type="text" class="form-input" name="digital_file_version" placeholder="1.0.0" value="<?php echo get_post_meta($post->ID, '_digital_file_version', true); ?>">


                        <?php endif; ?>

                        <!-- Hookable :: Extending for More Information Digital -->
                        <?php do_action('lwpcommerce/product/digital'); ?>
                    </div>

                                      <!-- Physical -->
                    <input name="shipping_tabs" value="physical" id="physical" type="radio" />
                    <label class="label" for="physical">
                    <?php esc_attr_e('Product Fisik', 'lwpcommerce'); ?>
                    </label>

                    <div class="pane-metabox">
                        <label for="physical_weight">
                            <?php esc_attr_e('Berat', 'lwpcommerce'); ?> /g :
                        </label>
                        <input type="text" class="form-input currency" name="physical_weight" placeholder="50" value="<?php echo get_post_meta($post->ID, '_physical_weight', true); ?>">
                        <label for="physical_volume" style="margin-left:10px"><?php esc_attr_e('Volume', 'lwpcommerce'); ?> /cm : </label>
                        <input type="text" class="form-input currency" name="physical_volume" placeholder="300" value="<?php echo get_post_meta($post->ID, '_physical_volume', true); ?>">
                    </div>
                    <?php $shipping_type = empty(get_post_meta($post->ID, '_shipping_type', true)) ? 'digital' : get_post_meta($post->ID, '_shipping_type', true); ?>
                    <script>
                        jQuery('input[value="<?php echo esc_attr($shipping_type); ?>"]').prop("checked", true);
                    </script>

     <?php 
    }

    public function metabox_product_data()
    {
        global $post;
        wp_nonce_field(basename(__FILE__), 'lwpc_admin_nonce'); ?>

        <style>
            #product-data .inside,
            #product-data .wp-tab-bar,
            #product-data .wp-tab-panel {
                margin: 0 !important;
            }

            #product-data .inside,
            #product-data .wp-tab-bar {
                padding: 0;
            }

            #product-data .wp-tab-panel {
                min-height: 250px;
                height: auto;
                max-height: 100%;
            }

            #product-data .wp-tab-active {
                border: none;
            }

            #product-data .wp-tab-bar li {
                padding: 7px 10px;
                display: block;
                margin: 0;
                border-bottom: 1px solid #ddd;
            }

            li.wp-tab-active {
                background: #f3f3f3;
            }

            #product-data a:active,
            #product-data a:hover,
            #product-data a:focus {
                box-shadow: none;
                outline: 0;
            }

            .wp-tab-bar li {
                text-decoration: none;
            }

            #product-data .wp-tab-bar li a span {
                padding: 0 10px;

            }

            #product-data .wp-tab-bar li a {
                display: flex;
                justify-content: left;
            }

            .metabox-field {
                padding: 7px 0;
            }

            .lsdp-hide {
                display: none;
            }

            .mfield {
                margin: 4px 0 6px;
            }

            #product-data ul.wp-tab-bar {
                /* Style Vertical Tab Widh*/
                float: left;
                width: 165px;
                text-align: left;
                margin: 0 -165px 0 5px;
                padding: 0;
            }

            #product-data div.wp-tab-panel {
                margin: 0 5px 0 125px;
            }
        </style>

        <div id="product-data">

            <!-- Vertical Tab -->
            <ul class="wp-tab-bar">
                <li class="wp-tab-active">
                    <a href="#price">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-tag">
                            <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                            <line x1="7" y1="7" x2="7.01" y2="7"></line>
                        </svg>
                        <span><?php _e('Pricing', 'lwpcommerce'); ?></span>
                    </a>
                </li>
                <li>
                    <a href="#stock">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-database">
                            <ellipse cx="12" cy="5" rx="9" ry="3"></ellipse>
                            <path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path>
                            <path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path>
                        </svg>
                        <span><?php _e('Stock', 'lwpcommerce'); ?></span>
                    </a>
                </li>
      
          
            </ul>

            <!-- Price -->
            <div class="wp-tab-panel" id="price">
                <?php
                $price_normal = get_post_meta($post->ID, '_price_normal', true) == null ? "" : lwp_currency_format(false, abs(get_post_meta($post->ID, '_price_normal', true)));
                $price_discount = get_post_meta($post->ID, '_price_discount', true) == null ? "" : lwp_currency_format(false, abs(get_post_meta($post->ID, '_price_discount', true)));
                ?>
                <div class="metabox-field">
                    <label for="price_normal">
                        <?php esc_attr_e('Normal', 'lwpcommerce'); ?> ( <?php echo lwp_currency_display('symbol'); ?> )
                    </label>
                    <p class="mfield"><input type="text" name="price_normal" class="currency" placeholder="<?php echo lwp_currency_display('format'); ?>" value="<?php echo $price_normal; ?>"></p>
                   
                    <label for="price_discount">
                        <?php esc_attr_e('Discount', 'lwpcommerce'); ?> ( <?php echo lwp_currency_display('symbol'); ?> )
                    </label>
                    <p class="mfield"><input type="text" name="price_discount" class="currency" placeholder="<?php echo lwp_currency_display('format'); ?>" value="<?php echo $price_discount; ?>"></p>
                </div>
            </div>

            <!-- Stock  -->
            <div class="wp-tab-panel lsdp-hide" id="stock">
                <?php
                $stock = empty(get_post_meta($post->ID, '_stock', true)) ? 9999 : abs(get_post_meta($post->ID, '_stock', true));
                $stock_unit = empty(get_post_meta($post->ID, '_stock_unit', true)) ? 'pcs' : esc_attr(get_post_meta($post->ID, '_stock_unit', true));
                $min_purchase = empty(get_post_meta($post->ID, '_min_purchase', true)) ? 1 : intval(get_post_meta($post->ID, '_min_purchase', true));
                $max_purchase = empty(get_post_meta($post->ID, '_max_purchase', true)) ? -1 : intval(get_post_meta($post->ID, '_max_purchase', true));
                ?>
                <div class="metabox-field">
                <label for="stock"><?php esc_attr_e('SKU (Stock Keeping Unit)', 'lwpcommerce'); ?></label>
                    <p class="mfield"><input type="text" name="stock" placeholder="9999" value="<?php echo $stock; ?>"></p>

                    <label for="stock"><?php esc_attr_e('Stock', 'lwpcommerce'); ?></label>
                    <p class="mfield"><input type="text" name="stock" placeholder="9999" value="<?php echo $stock; ?>"></p>

                    <label for="stock_unit"><?php esc_attr_e('Stock Unit', 'lwpcommerce'); ?> </label>
                    <p class="mfield"><input type="text" name="stock_unit" placeholder="pcs" value="<?php echo $stock_unit; ?>"></p>

                    <label for="min_purchase"><?php esc_attr_e('Min Purchase', 'lwpcommerce'); ?> </label>
                    <p class="mfield"><input type="text" name="min_purchase" placeholder="-1" value="<?php echo $min_purchase; ?>"></p>

                    <label for="max_purchase"><?php esc_attr_e('Max Purchase', 'lwpcommerce'); ?> </label>
                    <p class="mfield"><input type="text" name="max_purchase" placeholder="1" value="<?php echo $max_purchase; ?>"></p>
                </div>
            </div>


            <div class="spacer" style="clear: both;"></div>
        </div>

        <!-- Script for Tab Click -->
        <script>
            jQuery(document).ready(function($) {
                $('.wp-tab-bar a').click(function(event) {
                    event.preventDefault();

                    // Limit effect to the container element.
                    var context = $(this).closest('.wp-tab-bar').parent();
                    $('.wp-tab-bar li', context).removeClass('wp-tab-active');

                    $(this).closest('li').addClass('wp-tab-active');
                    $('.wp-tab-panel', context).addClass('lsdp-hide');
                    $($(this).attr('href'), context).removeClass('lsdp-hide');

                });

                // Make setting wp-tab-active optional.
                $('.wp-tab-bar').each(function() {
                    if ($('.wp-tab-active', this).length) {
                        $('.wp-tab-active', this).click();
                    } else {
                        $('a', this).first().click();
                    }
                });
            });
        </script>

        <style>
            .wp-tab-panel {
                padding: 8px 14px 14px;
            }
        </style>
<?php
    }

    public function metabox_save($post_id)
    {
        if (!isset($_POST['lwpc_admin_nonce']) || !wp_verify_nonce($_POST['lwpc_admin_nonce'], basename(__FILE__))) {
            return 'Nonce not Verified';
        }

        if (wp_is_post_autosave($post_id)) // Check AutoSave
        {
            return 'autosave';
        }

        if (wp_is_post_revision($post_id)) // Check Revision
        {
            return 'revision';
        }

        if ('product' == $_POST['post_type']) // Checking Posttype
        {
            if (!current_user_can('edit_page', $post_id)) {
                return 'cannot edit page';
            }
        } else if (!current_user_can('edit_post', $post_id)) {
            return 'cannot edit post';
        }

        update_post_meta($post_id, '_price_normal', lwp_currency_to_number($_POST['price_normal']));
        update_post_meta($post_id, '_price_discount', lwp_currency_to_number($_POST['price_discount']));

        update_post_meta($post_id, '_stock', empty($_POST['stock']) ? 1 : abs(sanitize_text_field($_POST['stock'])));
        update_post_meta($post_id, '_stock_unit', sanitize_text_field($_POST['stock_unit']));
        update_post_meta($post_id, '_min_purchase', intval($_POST['min_purchase']));
        update_post_meta($post_id, '_max_purchase', intval($_POST['max_purchase']));

        // update_post_meta($post_id, '_shipping_type', sanitize_text_field($_POST['shipping_tabs']));
        // update_post_meta($post_id, '_product_type', sanitize_text_field($_POST['shipping_tabs']));

        // Digital
        // update_post_meta($post_id, '_digital_file_url', sanitize_text_field($_POST['digital_file_url']));
        // update_post_meta($post_id, '_digital_file_version', isset($_POST['digital_file_version']) && $_POST['digital_file_version'] != null ? sanitize_text_field($_POST['digital_file_version']) : '1.0.0');

        // // Physical
        // update_post_meta($post_id, '_physical_weight', lwpbb_set_currency_to_number($_POST['physical_weight']));
        // update_post_meta($post_id, '_physical_volume', lwpbb_set_currency_to_number($_POST['physical_volume']));
    }
}
