<?php
get_header();
?>
    <style>
        .lwd-container {
            max-width: 1140px;
            margin: 12px auto;
        }
    </style>

    <div class="lwd-container">
        <?= do_shortcode('[lwcommerce_product_listing]'); ?>
    </div>

<?php

get_footer();
