<?php

namespace LokusWP\Commerce\Modules\Product;

if (!defined('WPTEST')) {
    defined('ABSPATH') or die("Direct access to files is prohibited");
}

class Post_Type_Product
{
    public function __construct()
    {
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

        register_taxonomy(
            'product-category',
            'product',
            array(
                'hierarchical' => true,
                'label' => __('Kategori'),
                'query_var' => true,
                'public' => true,
                'rewrite' => array(
                    'slug' => __('kategori', 'lwpcommerce'),
                    'with_front' => true,
                    'hierarchical' => true,
                ),
                'has_archive' => false,
            )
        );

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


    /**
     * Display Listing Product
     *
     * @return void
     */
    public function archive()
    {
        if (is_post_type_archive('product')) {
            // return LWPC_PATH . 'frontend/templates/storefront/listing.php';
        }
    }

    /**
     * Display Detail Product
     *
     * @param string $template
     * @return string
     */
    public function single(string $template)
    {
        global $post;

        if ($post->post_type == 'product') {
            if (file_exists(LWPC_PATH . 'src/templates/presentation/product/single.php')) {
                return LWPC_PATH . 'src/templates/presentation/product/single.php';
            }
        }

        // Return Default Template
        return $template;
    }

    /**
     * Add Custom Column Header in Admin Listing Product
     * adding new colume 
     * - Image
     * - Title
     * - Price
     * - Stock
     * - ID
     * 
     * @param array $columns
     * @return array
     */
    public function column_header(array $columns)
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

    /**
     * Add Custom Column Content in Admin Listing Product
     * 
     * @param string $column
     * @param int $post_id
     * @return void
     */
    public function columen_content($column, $post_id)
    {

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

            if (lwpc_get_discount_price($post_id)) {
                echo '<span style="text-decoration: line-through">' . lwp_currency_format(true, lwpc_get_price($post_id)) .  '</span><br>';
                echo lwp_currency_format(true, lwpc_get_discount_price($post_id));
            } else {
                echo lwp_currency_format(true, lwpc_get_price($post_id));
            }
        }

    ?>
        <style>
            .column-image,
            .column-id {
                width: 3.8%;
            }

            .column-price,
            .column-stock {
                width: 13%;
            }
        </style>
<?php
    }
}
