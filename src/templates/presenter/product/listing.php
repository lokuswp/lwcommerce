<div class="lwc-listing row <?= $mode == 'mobile' ? 'lwp-mobile-width' : '' ?>">

	<?php
	$args = array(
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'posts_per_page' => - 1,
		'orderby'        => 'date',
		'order'          => 'ASC',
		'cat'            => 'home',
	);

	$loop = new WP_Query( $args );


	while ( $loop->have_posts() ) : $loop->the_post();
		?>
        <div class="lwc-product-item col-xs-6 col-sm-6  <?= $mode == 'mobile' ? '' : 'col-md-3' ?> gutter">
            <div class="product-image">
				<?php do_action( "lwcommerce/product/listing/before_image", get_the_ID() ); ?>
                <a href="<?php echo get_permalink(); ?>">
                    <img src="<?php echo get_the_post_thumbnail_url( get_the_ID() ); ?>" alt="<?php the_title(); ?>">
                </a>

				<?php // Badge
				//echo '<div class="lwc-pre-order-badge">Pre Order</div>';
				?>

				<?php do_action( "lwcommerce/product/listing/after_image", get_the_ID() ); ?>
            </div>
            <a href="<?php echo get_permalink(); ?>">
                <h3 class="product-name"><?php the_title(); ?></h3>
				<?php do_action( "lwcommerce/product/listing/after_title", get_the_ID() ); ?>
            </a>
            <div class="product-price">
				<?php echo lwc_get_price_html( get_the_ID() ); ?>
				<?php do_action( "lwcommerce/product/listing/after_price", get_the_ID() ); ?>
            </div>
			<?php lwc_add_to_cart_html(); ?>
			<?php do_action( "lwcommerce/product/listing/after_cart_button", get_the_ID() ); ?>
        </div>
	<?php
	endwhile;

	wp_reset_postdata();
	?>

    <style>
  
        .product-image {
            position: relative;
        }

        .col-xs-6:nth-child(odd) {
            padding-right: 0.25rem
        }

        .col-xs-6:nth-child(even) {
            padding-left: 0.25rem
        }

        .lwc-listing .col-xs-6 {
            margin-bottom: 0.25rem;
        }

        /* PRO CSS */
        .lwc-product-badge {
            position: absolute;
            padding: 4px 8px;
            background: #ddd;
            top: 6px;
            left: 6px;
            background: var(--lokuswp-secondary-color);
            color: #fff;

            font-size: 12px;
            font-weight: 600;
            border-radius: 3px;
        }
    </style>

</div>