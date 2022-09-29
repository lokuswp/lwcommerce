<?php
get_header();
wp_enqueue_style( "lokuswp-grid" );

lwp_set_meta_counter( "_product_view", get_the_ID() );
?>

<!-- Open Graph -->

<!-- Google Rich Snippet -->
<!--<script type="application/ld+json">-->
<!--    {-->
<!--        "@context": "https://schema.org/",-->
<!--        "@type": "Product",-->
<!--        "name": "--><?//= the_title(); ?><!--",-->
<!--        "image": ['--><?//= get_the_post_thumbnail_url(); ?><!--'],-->
<!--        "description": --><?//= the_content(); ?><!--,-->
<!--        "sku": "0374984678",-->
<!--        "mpn": "738930",-->
<!--        "brand": {-->
<!--            "@type": "lokuswp",-->
<!--            "name": "LokusWP"-->
<!--        },-->
<!--        "review": {-->
<!--            "@type": "Review",-->
<!--            "reviewRating": {-->
<!--                "@type": "Rating",-->
<!--                "ratingValue": "4",-->
<!--                "bestRating": "5"-->
<!--            },-->
<!--            "author": {-->
<!--                "@type": "Person",-->
<!--                "name": "Warlok"-->
<!--            }-->
<!--        },-->
<!--        "aggregateRating": {-->
<!--            "@type": "AggregateRating",-->
<!--            "ratingValue": "4.7",-->
<!--            "reviewCount": "1455"-->
<!--        },-->
<!--        "offers": {-->
<!--            "@type": "Offer",-->
<!--            "url": "--><?//= get_permalink(); ?><!--",-->
<!--            "priceCurrency": "IDR",-->
<!--            "price": "500,000",-->
<!--            "lowPrice": "119.99",-->
<!--            "highPrice": "199.99",-->
<!--            "priceValidUntil": "2021-11-20",-->
<!--            "itemCondition": "https://schema.org/NewCondition",-->
<!--            "availability": "https://schema.org/InStock"-->
<!--        }-->
<!--    }-->
<!---->
<!--</script>-->

<div class="lwcommerce lwp-container">
	<?php require_once LWC_PATH . 'src/templates/component/navigation.php'; ?>

    <div class="lwp-product row">
        <div class="col-xs-12 col-sm-12">
            <a href="<?php echo get_permalink(); ?>" class="product-image">
				<?php the_post_thumbnail( 'full' ); ?>
            </a>
        </div>
        <div class="col-xs-12 col-sm-12 row no-gutter">
            <h2 class="product-title"><?php the_title(); ?></h2>
            <div class="product-price">
				<?php echo lwc_get_price_html(); ?>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 row no-gutter">
			<?php do_action( 'lokuswp/product/variant' ); ?>
        </div>
        <div class="col-xs-12 col-sm-12 row no-gutter" style="margin-top:12px">
            <div class="col-xs-7 col-sm-8">
				<?php lwc_whatsapp_button_html(); ?>
            </div>
            <div class="col-xs-5 col-sm-4  end-sm">
				<?php lwc_add_to_cart_html(); ?>
				<?= lwc_get_stock_html(); ?>
            </div>
        </div>
        <div class="col-sm-12 no-gutter">
            <p><?php the_content(); ?></p>
        </div>
    </div>
</div>

<?php require_once LWC_PATH . 'src/templates/presenter/checkout/bottom-cart-panel.php'; ?>

<style>
    .lwp-container {
        max-width: 480px;
        margin: 0 auto;
    }

    h2 {
        font-size: 18px;
    }

    .p12 {
        padding: 0 12px;
    }
</style>

<?php get_footer(); ?>

<script>
    Hooks.do_action('lwcommerce/product/single');
</script>
