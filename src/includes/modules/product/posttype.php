<?php
namespace LokusWP\Commerce\Modules\Product;

if (!defined('WPTEST')) {
    defined('ABSPATH') or die("Direct access to files is prohibited");
}

class Posttype
{
    public function __construct()
    {
        add_filter('add_meta_boxes', [$this, 'metabox_register']);
        add_action('save_post', [$this, 'metabox_save']);
        add_action('new_to_publish', [$this, 'metabox_save']);

        add_filter('manage_product_posts_columns', [$this, 'column_header']);
        add_action('manage_product_posts_custom_column', [$this, 'columen_content'], 10, 2);

        add_action('archive_template', [$this, 'archive']);
        add_filter('single_template', [$this, 'single'], 11);

        add_action('init', [$this, 'register']);
    }

    /**
     * Registering Posttype Product
     *
     * @return void
     */
    public function register()
    {
        $supports = array(
            'title',
            'editor',
            'thumbnail',
            'excerpt',
        );

        $labels = array(
            'name' => _x('Products', 'plural', 'lwpcommerce'),
            'singular_name' => _x('Product', 'singular', 'lwpcommerce'),
            'add_new' => _x('New Product', 'Add Product', 'lwpcommerce'),
            'add_new_item' => __('Add Product', 'lwpcommerce'),
            'new_item' => __('New Product', 'lwpcommerce'),
            'edit_item' => __('Edit Product', 'lwpcommerce'),
            'view_item' => __('View Product', 'lwpcommerce'),
            'all_items' => __('All Product', 'lwpcommerce'),
            'search_items' => __('Find Product', 'lwpcommerce'),
            'not_found' => __('Product not found.', 'lwpcommerce'),
        );

        $args = array(
            'supports' => $supports,
            'labels' => $labels,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => false,
            'show_in_admin_bar' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'product'),
            'has_archive' => true,
            'hierarchical' => false,
            'comments' => true,
            'taxonomies' => array('product-category'),
        );

        register_post_type('product', $args);

        // register_taxonomy(
        //     'product-category',
        //     'product',
        //     array(
        //         'hierarchical' => true,
        //         'label' => __('Kategori'),
        //         'query_var' => true,
        //         'public' => true,
        //         'rewrite' => array(
        //             'slug' => __('kategori', 'lwpcommerce'),
        //             'with_front' => true,
        //             'hierarchical' => true,
        //         ),
        //         'has_archive' => false,
        //     )
        // );

        // $this->flush();
    }

    protected function flush()
    {
        // if (get_option('lwpcommerce_permalink_flush')) {
        //     // Force and Flush
        //     global $wp_rewrite;
        //     $wp_rewrite->set_permalink_structure('/%postname%/');
        //     update_option("rewrite_rules", false);
        //     $wp_rewrite->flush_rules(true);

        //     delete_option('lwpcommerce_permalink_flush');
        // }
    }

    protected function js_inject()
    {
?>
        <script>
            jQuery(document).ready(function() {
                jQuery('body.post-type-product #postimagediv .inside').append('<p class="recommended" id="donation-recommended">Recommended image size 392 x 210px</p>');
            });

            jQuery(document).on('change', 'input[name="product_type"]', function() {
                jQuery('body.post-type-product #postimagediv .recommended').hide()
                if (jQuery('input[name="product_type"]:checked').val()) {
                    jQuery('body.post-type-product #postimagediv #' + jQuery('input[name="product_type"]:checked').val().trim() + '-recommended').show();
                }
            });
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
        // $this->js_inject();
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
                        <span><?php _e('Harga', 'lwpcommerce'); ?></span>
                    </a>
                </li>
                <li>
                    <a href="#file">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-package">
                            <line x1="16.5" y1="9.4" x2="7.5" y2="4.21"></line>
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                            <line x1="12" y1="22.08" x2="12" y2="12"></line>
                        </svg>
                        <span><?php _e('File', 'lwpcommerce'); ?></span>
                    </a>
                </li>
                <!-- <li>
                    <a href="#shipping">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-truck">
                            <rect x="1" y="3" width="15" height="13"></rect>
                            <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                            <circle cx="5.5" cy="18.5" r="2.5"></circle>
                            <circle cx="18.5" cy="18.5" r="2.5"></circle>
                        </svg>
                        <span><?php //_e('Pengiriman', 'lwpcommerce'); 
                                ?></span>
                    </a>
                </li> -->

                <!-- Hookable :: Extendable Tab -->
                <?php //do_action('lwpcommerce/product/data/header'); 
                ?>
            </ul>

            <!-- Price -->
            <div class="wp-tab-panel" id="price">
                <?php
                $price_normal = get_post_meta($post->ID, '_price_normal', true) == null ? "" : lwpc_set_currency_format(false, abs(get_post_meta($post->ID, '_price_normal', true)));
                $price_discount = get_post_meta($post->ID, '_price_discount', true) == null ? "" : lwpc_set_currency_format(false, abs(get_post_meta($post->ID, '_price_discount', true)));
                ?>
                <!-- <div class="metabox-field"> -->
                <label for="price_normal">
                    <?php esc_attr_e('Harga Normal', 'lwpcommerce'); ?> ( <?php echo lwpc_set_currency_display('symbol'); ?> )
                </label>
                <p class="mfield"><input type="text" name="price_normal" class="currency" placeholder="<?php echo lwpc_set_currency_display('format'); ?>" value="<?php echo $price_normal; ?>"></p>
                <small>Jadi</small>
                <label for="price_discount">
                    <?php esc_attr_e('Harga Diskon', 'lwpcommerce'); ?> ( <?php echo lwpc_set_currency_display('symbol'); ?> )
                </label>
                <p class="mfield"><input type="text" name="price_discount" class="currency" placeholder="<?php echo lwpc_set_currency_display('format'); ?>" value="<?php echo $price_discount; ?>"></p>
                <!-- </div> -->
            </div>

            <!-- Stock  -->
            <div class="wp-tab-panel lsdp-hide" id="stock">
                <?php
                $stock = empty(get_post_meta($post->ID, '_stock', true)) ? 9999 : abs(get_post_meta($post->ID, '_stock', true));
                $stock_unit = empty(get_post_meta($post->ID, '_stock_unit', true)) ? 'pcs' : esc_attr(get_post_meta($post->ID, '_stock_unit', true));
                ?>
                <div class="metabox-field">
                    <label for="stock"><?php esc_attr_e('Stok', 'lwpcommerce'); ?> ( <small> > 100 : <?php _e('Tersedia', 'lwpcommerce'); ?></small> )</label>
                    <p class="mfield"><input type="text" name="stock" placeholder="9999" value="<?php echo $stock; ?>"></p>

                    <label for="stock_unit"><?php esc_attr_e('Stok Unit', 'lwpcommerce'); ?> </label>
                    <p class="mfield"><input type="text" name="stock_unit" placeholder="pcs" value="<?php echo $stock_unit; ?>"></p>
                </div>
            </div>

            <!-- Shipping Tab -->
            <div class="wp-tab-panel lsdp-hide" id="file">
                <div class="tabs tabs-inside">

                    <!-- Digital -->
                    <!-- <input name="shipping_tabs" value="digital" id="digital" type="radio" />
                    <label class="label" for="digital">
                        <?php esc_attr_e('Product Digital', 'lwpcommerce'); ?>
                    </label> -->

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
                    <!-- <input name="shipping_tabs" value="physical" id="physical" type="radio" />
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
                    </script> -->

                </div>
            </div>

            <!-- Hookable :: Extending Tab Content -->
            <?php do_action('lwpcommerce/product/data/content'); ?>

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

        update_post_meta($post_id, '_price_normal', abs($_POST['price_normal']));
        update_post_meta($post_id, '_price_discount', abs($_POST['price_discount']));

        // update_post_meta($post_id, '_stock', empty($_POST['stock']) ? 1 : abs(sanitize_text_field($_POST['stock'])));
        // update_post_meta($post_id, '_stock_unit', sanitize_text_field($_POST['stock_unit']));

        // update_post_meta($post_id, '_shipping_type', sanitize_text_field($_POST['shipping_tabs']));
        // update_post_meta($post_id, '_product_type', sanitize_text_field($_POST['shipping_tabs']));

        // Digital
        update_post_meta($post_id, '_digital_file_url', sanitize_text_field($_POST['digital_file_url']));
        update_post_meta($post_id, '_digital_file_version', isset($_POST['digital_file_version']) && $_POST['digital_file_version'] != null ? sanitize_text_field($_POST['digital_file_version']) : '1.0.0');

        // Physical
        update_post_meta($post_id, '_physical_weight', lwpbb_set_currency_to_number($_POST['physical_weight']));
        update_post_meta($post_id, '_physical_volume', lwpbb_set_currency_to_number($_POST['physical_volume']));
    }
    
    /**
     * Display Listing Product
     *
     * @return void
     */
    public function archive()
    {
        if (is_post_type_archive('product')) {
            return LWPC_PATH . 'frontend/templates/storefront/listing.php';
        }
    }

    /**
     * Display Detail Product
     *
     * @param string $template
     * @return void
     */
    public function single($template)
    {
        global $post;

        if ($post->post_type == 'product') {

            if (file_exists(LWPC_PATH . 'src/templates/product/single.php')) {
                return LWPC_PATH . 'src/templates/product/single.php';
            }
        }
        return $template;
    }

    public function column_header($columns)
    {
        $columns = array(
            'cb' => $columns['cb'],
            'image' => __('Image'),
            'title' => __('Title'),
            'price' => __('Harga', 'lwpcommerce'),
            'stock' => __('Stok', 'lwpcommerce'),
            'id' => __('ID'),
            'date' => $columns['date'],
        );

        return $columns;
    }

    public function columen_content($column, $post_id)
    {
    ?>
        <style>
            .column-image,
            .column-id {
                width: 7%;
            }

            .column-price,
            .column-stock {
                width: 13%;
            }
        </style>

<?php
        if ('image' === $column) {
            echo get_the_post_thumbnail($post_id, array(39, 39));
        }

        // Type ID
        if ('id' === $column) {
            echo '<input style="width:50px;text-align:center;" value="' . get_the_ID() . '"/>';
        }


        if ('stock' === $column) {
            if (get_post_meta($post_id, '_stock', true) > 999) {
                _e('Tersedia', 'lwpcommerce');
                return;
            }

            if (get_post_meta($post_id, '_stock', true) == 0) {
                _e('Kosong', 'lwpcommerce');
                return;
            }

            echo esc_attr(ucfirst(get_post_meta($post_id, '_stock', true)));
        }

        if ('price' === $column) {
            if (lwpc_get_price($post_id) == 0) {
                _e('Gratis', 'lwpcommerce');
                return;
            }

            if (lwpc_get_price_discount($post_id)) {
                echo '<span style="text-decoration: line-through">' . lwpc_set_currency_format(true, lwpc_get_price($post_id)) .  '</span><br>';
                echo lwpc_set_currency_format(true, lwpc_get_price_discount($post_id));
            } else {
                echo lwpc_set_currency_format(true, lwpc_get_price($post_id));
            }
        }
    }
}
