<?php
add_action("lwcommerce/product/listing/after", "lwc_add_to_cart_html", 10, 2);
function lwc_add_to_cart_html($product_id, $options)
{
    require LWC_PATH . 'src/templates/component/add-to-cart.php';
}


add_action("lokuswp/commerce/single/after", "lwc_product_description", 10);
function lwc_product_description( $product_id )
{
    ?>
    <div class="lwp-content-area col-sm-12 no-gutter">
        <h3><?= __("Description", "lwcommerce"); ?></h3>
        <p><?php the_content(); ?></p>
    </div>
    <?php
}
