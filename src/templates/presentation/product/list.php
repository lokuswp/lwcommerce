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
            <a href="<?php echo get_permalink(); ?>">
                <h3 class="product-name"><?php the_title(); ?></h3>
            </a>
            <div class="product-price">
                <?php lwpc_get_price_html(); ?>
            </div>
            <?php lwpc_add_to_cart_html(); ?>
        </div>
    <?php
    endwhile;

    wp_reset_postdata();
    ?>

</div>