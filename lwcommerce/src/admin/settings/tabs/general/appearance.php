<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use LokusWP\Admin\Shortcode_Lists;
use LokusWP\Admin\Switch_Options;

?>

<div class="entry columns col-gapless">

    <!-- Appearance Switch Options -->
    <section id="appearance" class="column col-8 form-horizontal">
        <form>
			<?php Switch_Options::render( "lwcommerce" ); ?>
        </form>

        <br>

        <button class="btn btn-primary w-120" id="lokuswp_admin_appearance_save" app="lwcommerce">
			<?php _e( 'Save', 'lokuswp' ); ?>
        </button>
    </section>

    <!-- Sidebar - Shortcodes -->
    <section class="column col-4">
        <!-- <a class="btn btn-primary " target="_blank" href="">
            <?php _e( 'Learn Shortcode', 'lwcommerce' ); ?>
        </a> -->
		<?php Shortcode_Lists::render( "lokuswp" ); ?>
		<?php Shortcode_Lists::render( "lwcommerce" ); ?>
    </section>

</div>
