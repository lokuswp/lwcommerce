<div class="lwc-listing row lwp-mobile-width">

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
        <div class="col-xs-6 col-sm-6 gutter">
            <div class="product-image">
                <a href="<?php echo get_permalink(); ?>">
                    <img src="<?php echo get_the_post_thumbnail_url(get_the_ID()); ?>" alt="<?php the_title(); ?>">
                </a>
            </div>
            <a href="<?php echo get_permalink(); ?>">
                <h3 class="product-name"><?php the_title(); ?></h3>
            </a>
            <div class="product-price">
                <?php lwc_get_price_html(); ?>
            </div>
            <?php lwc_add_to_cart_html(); ?>
        </div>
    <?php
    endwhile;

    wp_reset_postdata();
    ?>

    <style>
        .col-xs-6:nth-child(odd){
            padding-right:0.25rem
        }
        .col-xs-6:nth-child(even){
            padding-left:0.25rem
        }

        .lwc-listing .col-xs-6{
            margin-bottom: 0.25rem;
        }
    </style>

</div>