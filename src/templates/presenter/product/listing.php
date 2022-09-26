<style>

    h4 {
        color: grey;
        font-size: 24px;
        font-weight: 400;
    }

    #portfolio p {
        color: grey;
        font-size: 12px;
        font-weight: 200;
    }

    .content {
        width: 100%;
        margin: 0 auto;
        padding: 0px;
        text-align: center;
    }


    .filters {
        width: 100%;
        text-align: left;
    }

    ul {
        list-style: none;
        padding: 20px 0;
    }

    li {
        display: inline;
        padding: .5rem 2.4rem;
        font-size: 16px;
        color: #636363;
        cursor: pointer;
        font-weight: 500;

    }

    li:hover {
        color: #a6a6a6;
    }

    li.active {
        color: #fff;
        /*border: 1px solid #ccc;*/
        background-color: var(--lokuswp-secondary-darker-color) !important;
        border-radius: 6px;
    }
</style>


<style>
    .scrollmenu {
        overflow: auto;
        white-space: nowrap;
        padding: 16px 6px;
        margin: 0;
    }

    .scrollmenu li {
        display: inline-block;
        margin-right: 6px;
        text-align: center;
        text-decoration: none;
        background: #FAFAFA;
    }


</style>

<?php if ( $filter == 'category' ) : wp_enqueue_script( "isotope" ); ?>

    <div class="filters filter-button-group">
        <ul class="scrollmenu">

			<?php
			// Get campaign categories
			$categories = get_terms( array(
				'taxonomy'   => 'product_category',
				'hide_empty' => false,
			) );
			?>

			<?php if ( sizeof( $categories ) >= 1 ) : ?>
                <li class="active" data-filter="*">
					<?php _e( 'All', 'lsddonation-campaign' ); ?>
                </li>
			<?php endif ?>

			<?php foreach ( $categories as $term ) : ?>
				<?php if ( isset( $term->name ) ) : ?>
                    <li data-filter=".<?php echo $term->slug; ?>"><?php echo isset( $term->name ) ? $term->name : ""; ?></li>
				<?php endif; ?>
			<?php endforeach ?>

        </ul>
    </div>
    <script>
        jQuery(document).ready( function() {

            if ( jQuery.isFunction(jQuery.fn.isotope) ) {
                jQuery('.grid').isotope({
                    itemSelector: '.grid-item',
                    isOriginLeft: true
                });

// filter items on button click
                jQuery('.filter-button-group').on( 'click', 'li', function() {
                    var filterValue = jQuery(this).attr('data-filter');
                    jQuery('.grid').isotope({ filter: filterValue });
                    jQuery('.filter-button-group li').removeClass('active');
                    jQuery(this).addClass('active');
                });
            }


        })
    </script>
<?php endif; ?>

<div class="lwc-listing row content grid">

	<?php
	$args = array(
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'posts_per_page' => - 1,
		'orderby'        => 'date',
		'order'          => 'DESC',
		'cat'            => 'home',
	);

	$loop = new WP_Query( $args );

	while ( $loop->have_posts() ) : $loop->the_post();

		$col  = $column == 3 ? 'col-md-4' : 'col-md-3';
		$size = $column == 3 ? 'lwc-d-p-thumbnail' : 'lwc-d-p-thumbnail-crop';

		$terms = get_the_terms( get_the_ID(), 'product_category' );
		$term  = ( $terms ) ? implode( ' ', wp_list_pluck( $terms, 'slug' ) ) : '';
		?>
        <div class="single-content lwc-product-item col-xs-6 col-sm-6 <?= $term ?> grid-item <?= $mobile == true ? '' : $col ?> gutter">
            <div class="product-image">
				<?php do_action( "lwcommerce/product/listing/before_image", get_the_ID() ); ?>
                <a href="<?php echo get_permalink(); ?>">
                    <img src="<?php echo get_the_post_thumbnail_url( get_the_ID(), $size ); ?>"
                         alt="<?php the_title(); ?>">
                </a>

				<?php // Badge
				//echo '<div class="lwc-pre-order-badge">Pre Order</div>';
				?>

				<?php do_action( "lwcommerce/product/listing/after_image", get_the_ID() ); ?>
            </div>

			<?php do_action( "lwcommerce/product/listing/before_title", get_the_ID() ); ?>

            <a href="<?php echo get_permalink(); ?>">
                <h3 class="product-name"><?php the_title(); ?></h3>
            </a>

			<?php do_action( "lwcommerce/product/listing/after_title", get_the_ID() ); ?>

            <div class="product-price">
				<?php echo lwc_get_price_html( get_the_ID() ); ?>
				<?php do_action( "lwcommerce/product/listing/after_price", get_the_ID() ); ?>
            </div>

			<?php do_action( "lwcommerce/product/listing/after", get_the_ID() ); ?>
        </div>
	<?php
	endwhile;

	wp_reset_postdata();
	?>


</div>


<div class="lwc-listing row <?= $mobile == true ? 'lwp-mobile-first' : '' ?>">


    <div class="swiper-container swiper-tabs-nav  swiper-isotope">
        <div class="swiper-wrapper isotope-filter">


        </div>
    </div>


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
            padding: 4px 0;
            text-align: left;
            font-size: 14px;
            font-weight: 600;
            border-radius: 3px;
            margin-bottom: -8px;
            color: #a4afb7;
        }

        .lwc-product-badge.new {
            color: #00d319;
        }

        .lwc-product-badge.sale {
            color: #d30000;
        }
    </style>

</div>

<?php require_once LWC_PATH . 'src/templates/presenter/checkout/bottom-cart-panel.php'; ?>