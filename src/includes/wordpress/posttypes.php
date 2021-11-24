<?php

namespace LokaWP\Commerce;

// defined( 'ABSPATH' ) or die( 'ABSPATH Not Defined' );

class Post_Types
{
    public function __construct()
    {
        add_action("admin_init", [$this, "catalog_product"]);

        add_filter('manage_product_posts_columns', [$this, 'column_header']);
        add_action('manage_product_posts_custom_column', [$this, 'columen_content'], 10, 2);

        add_action('archive_template', [$this, 'archive']);
        add_filter('single_template', [$this, 'single'], 11);
    }

    public function catalog_product()
    {
        $supports = array(
            'title',
            'excerpt',
            'thumbnail',
        );

        $labels = array(
            'name' => _x('Produk', 'plural', 'lwpcommerce'),
            'singular_name' => _x('Produk', 'singular', 'lwpcommerce'),
            'add_new' => _x('Produk Baru', 'Tambah Produk', 'lwpcommerce'),
            'add_new_item' => __('Tambah Produk', 'lwpcommerce'),
            'new_item' => __('Produk Baru', 'lwpcommerce'),
            'edit_item' => __('Edit Produk', 'lwpcommerce'),
            'view_item' => __('Lihat Produk', 'lwpcommerce'),
            'all_items' => __('Semua Produk', 'lwpcommerce'),
            'search_items' => __('Cari Produk', 'lwpcommerce'),
            'not_found' => __('Produk Tidak Ditemukan.', 'lwpcommerce'),
        );

        $args = array(
            'supports' => $supports,
            'labels' => $labels,
            'public' => false,
            'publicly_queryable' => false,
            'show_ui' => true,
            'show_in_menu' => false,
            'show_in_admin_bar' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'product'),
            'has_archive' => true,
            'hierarchical' => false,
            'menu_icon' => LWPC_URL . 'admin/assets/svg/product.svg',
            'comments' => true,
            'exclude_from_search' => true,
        );

        register_post_type('product', $args);

        // Category
        register_taxonomy(
            'product-category',
            'product',
            array(
                'hierarchical' => true,
                'label' => __('Kategori', 'lwpcommerce'),
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
    }


    /**
     * Display Listing Product
     *
     * @return void
     */
    public function archive()
    {
        if (is_post_type_archive('product')) {
            return LSDC_PATH . 'frontend/templates/storefront/listing.php';
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
            if (file_exists(LSDC_PATH . 'frontend/templates/storefront/product/single.php')) {
                return LSDC_PATH . 'frontend/templates/storefront/product/single.php';
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
            'price' => __('Harga', 'lsdcommerce'),
            'stock' => __('Stok', 'lsdcommerce'),
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
            .column-id{
                width: 7%;
            }

            .column-price,
            .column-stock{
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
            if(get_post_meta($post_id, '_stock', true) > 999 ){
                _e('Tersedia', 'lsdcommerce');
                return;
            }

            if(get_post_meta($post_id, '_stock', true) == 0 ){
                _e('Kosong', 'lsdcommerce');
                return;
            }

            echo esc_attr(ucfirst(get_post_meta($post_id, '_stock', true)));
        }

        if ( 'price' === $column ) {
            // if(lsdc_get_price( $post_id ) == 0 ){
            //     _e('Gratis', 'lsdcommerce');
            //     return;
            // }

            // if( lsdc_get_price_discount( $post_id ) ){
            //     echo '<span style="text-decoration: line-through">' . lsdc_currency_format( true, lsdc_get_price( $post_id ) ) .  '</span><br>';
            //     echo lsdc_currency_format( true, lsdc_get_price_discount( $post_id ) );
            // }else{
            //     echo lsdc_currency_format( true, lsdc_get_price( $post_id ) );
            // }
        }
    }
}

// new Post_Types;