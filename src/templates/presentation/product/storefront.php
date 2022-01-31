<?php

/**
 * Sorting
 * Filtering
 * Product List
 */
?>

<section id="lwpc-etalase" class="lwp-mobile-width">
    <?php require_once LWPC_PATH . 'src/templates/component/navigation.php'; ?>

    <div class="lwpc-listing row">

        <?php
        $args = array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'orderby' => 'date',
            'order' => 'ASC',
            'cat' => 'home',
        );

        $loop = new WP_Query($args);

        while ($loop->have_posts()) : $loop->the_post();
        ?>
            <div class="col-sm-6 gutter">
                <div class="product-image">
                    <a href="<?php echo get_permalink(); ?>">
                        <img src="<?php echo get_the_post_thumbnail_url(get_the_ID()); ?>" alt="<?php the_title(); ?>">
                    </a>
                </div>
                <h3 class="product-name"><?php the_title(); ?></h3>
                <div class="product-price">
                    <?php lwpc_get_price_html(); ?>
                </div>
                <?php lwpc_add_to_cart_html(); ?>
            </div>
        <?php
        endwhile;

        wp_reset_postdata();
        ?>

</section>

<style>
    .lwpc-listing {
        padding: 8px;
        text-align: center;
    }

    .lwpc-listing h3.product-name {
        font-size: 16px;

    }

    .product-action {
        margin-top: 8px;
    }
</style>