<?php
get_header();
wp_enqueue_style( "lokuswp-grid" );
?>

<!-- Open Graph -->

<!-- Google Rich Snippet -->
<!--<script type="application/ld+json">-->
<!--    {-->
<!--        "@context": "https://schema.org/",-->
<!--        "@type": "Product",-->
<!--        "name": "--><?php //the_title(); ?><!--",-->
<!--        "image": ['--><?//= get_the_post_thumbnail_url(); ?><!--'],-->
<!--        "description": "--><?php //the_content(); ?><!--",-->
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
	<?php // require_once LWC_PATH . 'src/templates/component/navigation.php'; ?>

    <div class="lwp-product row">
        <div class="col-xs-12 col-sm-12">
            <a href="<?php echo get_permalink(); ?>" class="product-image">
				<?php the_post_thumbnail(); ?>
            </a>
        </div>

        <div class="lwc-product-content">

            <div class="col-xs-12 col-sm-12 row no-gutter">

                <div class="col-xs-12 col-sm-7" style="margin-bottom:8px;">
                    <h2 class="product-title">
                        <?php the_title(); ?>
                    </h2>
                    <div class="product-price">
						<?php echo lwc_get_price_html(); ?>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-5 end-sm" style="display:flex;">
                    <div class="start-sm" style="padding: 4px 12px 4px 0;">
						<?php lwc_whatsapp_button_html(); ?>
                    </div>
                    <div class="end-sm">
						<?php lwc_add_to_cart_html( get_the_ID(), ['catalog_mode' => "off", 'whatsapp_button' => "off"] ); ?>
                    </div>
                </div>

				<?= lwc_get_stock_html(); ?>
            </div>


            <div class="col-xs-12 col-sm-12 row no-gutter" style="margin-top: 12px;">
				<?php do_action( 'lokuswp/product/variant' ); ?>
            </div>

            <br>
            <div class="lwp-content-area col-sm-12 no-gutter">
                <h3><?= __( "Description", "lwcommerce" ); ?></h3>
                <p><?php the_content(); ?></p>
            </div>
        </div>

    </div>
</div>

<?php require_once LWC_PATH . 'src/templates/presenter/checkout/bottom-cart-panel.php'; ?>

<style>


    .single-product .product-title{
        font-size: 20px;
    }

    span.lwc-stock {
        margin: 0 4px;
        font-weight: 600;
    }

    .lwp-product img {
        margin-bottom: -6px;
    }

    .lwc-product-content {
        padding: 16px;
        background: #fff;
        width: 100%;
    }

    .lwp-content-area h3 {
        font-size: 18px;
    }

    .lwp-container {
        max-width: 480px;
        margin: 0 auto;
    }

    .lwp-content-area ol,
    .lwp-content-area ul {
        margin-bottom: 1rem;
        padding-left: 28px;
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
