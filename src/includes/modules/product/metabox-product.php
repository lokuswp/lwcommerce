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
class Metabox_Product
{
    public function __construct()
    {
        add_filter('add_meta_boxes', [$this, 'metabox_register'], 0);
        add_action('save_post', [$this, 'metabox_save']);
        add_action('new_to_publish', [$this, 'metabox_save']);
        add_filter('admin_post_thumbnail_html', [$this, 'thumbnail_recommendation'], 10, 3);
    }

    /**
     * Add Thumbnail Recommendation Size in Product
     * 
     * Thanks to jeremyescott
     * @author jeremyescott    
     * @link https://stackoverflow.com/questions/30817906/wordpress-add-description-below-to-featured-image
     * 
     * @since 0.5.0
     * @return html
     */
    public function thumbnail_recommendation($content, $post_id, $thumbnail_id)
    {
        if ('product' !== get_post_type($post_id)) {
            return $content;
        }
        $caption = '<p>' . esc_html__('Recommended image size: ', 'lwpcommerce') . '<strong>800x800px</strong></p>';
        return $content . $caption;
    }

    public function metabox_register()
    {
        add_meta_box(
            'product-metabox',
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
    }

    public function metabox_product_format()
    {
        global $post;
?>

        <div class="tabs-component">
            <input type="radio" name="tab" id="tab1" checked="checked" />
            <label class="tab" for="tab1"><?php esc_attr_e('Digital', 'lwpcommerce'); ?></label>

            <input type="radio" name="tab" id="tab2" />
            <label class="tab" for="tab2"><?php esc_attr_e('Physical', 'lwpcommerce'); ?></label>

            <div class="tab-body-component">
                <div id="tab-body-1" class="tab-body">
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

                <div id="tab-body-2" class="tab-body">
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
                </div>
            </div>
        </div>

        <style>
            .tabs-component input[type=radio] {
                display: none !important;
            }

            .tabs-component [type=radio]:checked+label.tab,
            .tabs-component [type=radio]:not(:checked)+label.tab {
                padding-left: 0;
            }

            .tabs-component label.tab {
                display: inline-block;
                cursor: pointer;
                padding: 10px 20px !important;
                text-align: center;
            }

            .tabs-component label.tab:after,
            .tabs-component label.tab:before {
                display: none;
            }

            .tabs-component label.tab:last-of-type {
                border-bottom: none
            }

            .tabs-component label.tab:hover {
                background: #eee
            }

            .tabs-component input[type=radio]:checked+label.tab {
                border-bottom: 3px solid #000;
                margin: 0;
                margin-bottom: 2px;
            }

            .tabs-component .tab-body {
                position: absolute;
                opacity: 0;
                padding: 20px 0;
            }

            .tab-body-component {
                border-top: #ddd 3px solid;
                margin-top: -5px;
                position: initial
            }

            #tab1:checked~.tab-body-component #tab-body-1,
            #tab2:checked~.tab-body-component #tab-body-2 {
                position: relative;
                top: 0;
                opacity: 1
            }

        </style>

    <?php
    }

    public function metabox_product_data()
    {
        global $post;
        wp_nonce_field(basename(__FILE__), 'lwpc_admin_nonce'); 

        $price_normal = get_post_meta($post->ID, '_price_normal', true) == null ? null : lwp_currency_format(false, lwpc_get_normal_price($post->ID));
        $price_discount = get_post_meta($post->ID, '_price_discount', true) == null ? null : lwp_currency_format(false, lwpc_get_discount_price($post->ID));

        $product_data_args = [
            'price_normal' => $price_normal,
            'price_discount' => $price_discount,
        ];
        $product_type_args = [];
        $metabox_data = __DIR__ . '/metabox/product-data.php';
        $metabox_type = __DIR__ . '/metabox/product-type.php';


        load_template($metabox_data, true, $product_data_args);
        load_template($metabox_type, true, $product_type_args);
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
