<?php 
get_header();
wp_enqueue_style("lwp-grid");
?>

<div class="lwpcommerce lwp-container">
    <div class="lwp-navigate row">
        <div class="col-xs-2">Back</div>
        <div class="col-xs-8 middle-sm" style="text-align:center;">Keranjang</div>
        <div class="col-xs-2">Icon</div>
    </div>

    <div class="lwp-product row">
        <div class="col-xs-12 col-sm-12">
            <?php the_post_thumbnail(); ?>
        </div>
        <div class="col-xs-12 col-sm-12 row gutter" style="margin-top:8px;">
            <div class="col-xs-9">
                <?php the_title(); ?>
                <br>
                Harga : Rp 10.000
                <br>
            </div>
            <div class="col-xs-3 end-sm">
                <button class="add-troli" product-id="<?php the_ID(); ?>">Tambah</button><br>
                10 Tersisa
            </div>



        </div>
        <div class="col-sm-12 gutter">
            Deskripsi
        </div>
    </div>
</div>

<style>
    .lwp-container {
        /* max-width: 960px; */
        max-width: 420px;
        margin: 0 auto;
    }
    .lwp-navigate
    {
        height: 40px;
    }
    .lwp-navigate div {
        background: greenyellow;
    }
</style>

<?php get_footer(); ?>