<?php

namespace LokusWP\Commerce\Modules\Product;

if ( ! defined( 'WPTEST' ) ) {
	defined( 'ABSPATH' ) or die( "Direct access to files is prohibited" );
}

class Post_Type_Product {
	public function __construct() {
		add_filter( 'manage_product_posts_columns', [ $this, 'column_header' ] );
		add_action( 'manage_product_posts_custom_column', [ $this, 'column_content' ], 10, 2 );

		add_action( 'archive_template', [ $this, 'archive' ] );
		add_filter( 'single_template', [ $this, 'single' ], 11 );

		add_action( 'init', [ $this, 'register' ] );
	}

	/**
	 * Registering Posttype Product
	 *
	 * @return void
	 */
	public function register() {
		$supports = array(
			'title',
			'editor',
			'thumbnail',
			'excerpt',
		);

		$labels = array(
			'name'          => _x( 'Products', 'plural', 'lwcommerce' ),
			'singular_name' => _x( 'Product', 'singular', 'lwcommerce' ),
			'add_new'       => _x( 'New Product', 'Add Product', 'lwcommerce' ),
			'add_new_item'  => __( 'Add Product', 'lwcommerce' ),
			'new_item'      => __( 'New Product', 'lwcommerce' ),
			'edit_item'     => __( 'Edit Product', 'lwcommerce' ),
			'view_item'     => __( 'View Product', 'lwcommerce' ),
			'all_items'     => __( 'All Product', 'lwcommerce' ),
			'search_items'  => __( 'Find Product', 'lwcommerce' ),
			'not_found'     => __( 'Product not found.', 'lwcommerce' ),
		);

		$args = array(
			'supports'          => $supports,
			'labels'            => $labels,
			'public'            => true,
			'show_ui'           => true,
			'show_in_menu'      => false,
			'show_in_admin_bar' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'product' ),
			'has_archive'       => true,
			'hierarchical'      => false,
			'comments'          => true,
			'taxonomies'        => array( 'product-category' ),
		);

		register_post_type( 'product', $args );

		register_taxonomy(
			'product_category',
			'product',
			array(
				'hierarchical' => true,
				'label'        => __( 'Category', "lwcommerce" ),
				'query_var'    => true,
				'public'       => true,
				'rewrite'      => array(
					'slug'         => 'product-category',
					'with_front'   => true,
					'hierarchical' => true,
				),
				'has_archive'  => false,
			)
		);

		// $this->flush();
	}

	protected function flush() {
		// if (get_option('lwcommerce_permalink_flush')) {
		//     // Force and Flush
		//     global $wp_rewrite;
		//     $wp_rewrite->set_permalink_structure('/%postname%/');
		//     update_option("rewrite_rules", false);
		//     $wp_rewrite->flush_rules(true);

		//     delete_option('lwcommerce_permalink_flush');
		// }
	}


	/**
	 * Display Listing Product
	 *
	 * @return void
	 */
	public function archive() {
		if ( is_post_type_archive( 'product' ) ) {
			// return LWC_PATH . 'frontend/templates/storefront/listing.php';
		}
	}

	/**
	 * Display Detail Product
	 *
	 * @param string $template
	 *
	 * @return string
	 */
	public function single( string $template ) {
		global $post;

		if ( $post->post_type == 'product' ) {
			if ( file_exists( LWC_PATH . 'src/templates/presenter/product/single.php' ) ) {
				return LWC_PATH . 'src/templates/presenter/product/single.php';
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
	 *
	 * @return array
	 */
	public function column_header( array $columns ) {
		$columns = array(
			'cb'    => $columns['cb'],
			'image' => __( 'Image' ),
			'title' => __( 'Title' ),
			'price' => __( 'Price', 'lwcommerce' ),
			'stock' => __( 'Stock', 'lwcommerce' ),
			'id'    => __( 'ID' ),
			'date'  => $columns['date'],
		);

		return $columns;
	}

	/**
	 * Add Custom Column Content in Admin Listing Product
	 *
	 * @param string $column
	 * @param int $post_id
	 *
	 * @return void
	 */
	public function column_content( $column, $post_id ) {

		if ( 'image' === $column ) {
			echo get_the_post_thumbnail( $post_id, array( 39, 39 ) );
		}

		// Type ID
		if ( 'id' === $column ) {
			echo '<input style="width:50px;text-align:center;" value="' . get_the_ID() . '"/>';
		}


		if ( 'stock' === $column ) {
			if ( get_post_meta( $post_id, '_stock', true ) > 999 ) {
				_e( 'Available', 'lwcommerce' );

				return;
			}

			if ( get_post_meta( $post_id, '_stock', true ) == 0 ) {
				_e( 'Out of Stock', 'lwcommerce' );

				return;
			}

			echo esc_attr( ucfirst( get_post_meta( $post_id, '_stock', true ) ) );
		}

		if ( 'price' === $column ) {
			lwc_get_price_html( get_the_ID() );
		}

		?>
        <style>
            .column-image,
            .column-id {
                width: 4%;
            }

            .column-price,
            .column-stock {
                width: 13%;
            }

            .column-price small {
                display: block;
            }

            .widefat td, .widefat th {
                vertical-align: middle;
            }
        </style>
		<?php
	}
}