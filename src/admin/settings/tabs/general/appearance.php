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
			<?php $settings = lwp_get_option( "lwcommerce_appearance" ); ?>
            <!-- Font Settings -->
            <div class="form-group">
                <div class="col-3 col-sm-12">
                    <label class="form-label" for="fontlist"><?php _e( 'Font', 'lokuswp' ); ?></label>
                </div>
                <div class="col-5 col-sm-12">
                    <select class="form-select" id="fontlist" name="font" style="width: 100%;">
                        <option>Poppins</option>
                    </select>
                    <div id="selectedfont" class="hidden">
						<?php echo ! isset( $settings['font'] ) ? 'Poppins' : esc_attr( $settings['font'] ); ?>
                    </div>
                </div>
            </div>

            <!-- Cache Font List -->
            <script>
                if (localStorage.getItem("lokuswp_font_cache") == null || localStorage.getItem("lokuswp_font_cache") == '') {
                    jQuery.getJSON("https://www.googleapis.com/webfonts/v1/webfonts?key=AIzaSyCoDdOKhPem_sbA-bDgJ_-4cVhJyekWk-U", function (fonts) {
                        var lokuswp_font_cache = {};
                        for (var i = 0; i < fonts.items.length; i++) {
                            lokuswp_font_cache[fonts.items[i].family] = fonts.items[i].files.regular;
                        }
                        localStorage.setItem("lokuswp_font_cache", JSON.stringify(lokuswp_font_cache));
                    });
                } else {
                    var lokuswp_font_cache = JSON.parse(localStorage.getItem("lokuswp_font_cache"));
                    var selectedfont = jQuery('#selectedfont').text().trim();
                    jQuery.each(lokuswp_font_cache, function (index, value) {
                        jQuery('#fontlist')
                            .remove("option")
                            .append(jQuery((index == selectedfont) ? "<option selected></option>" : "<option></option>")
                                .attr("value", index)
                                .attr("style", "font-family:" + index + "; font-size: 16px")
                                .text(index));
                    });
                }
            </script>

            <!-- Primary Color -->
            <div class="form-group">
                <div class="col-3 col-sm-12">
                    <label class="form-label" for="primary-color"><?php _e( 'Secondary Color', 'lokuswp' ); ?></label>
                </div>
                <div class="col-9 col-sm-12" style="height: 10px !important;">
                    <input type="text" name="primary_color"
                           value="<?php echo isset( $settings['primary_color'] ) ? esc_attr( $settings['primary_color'] ) : '#282828'; ?>"
                           class="lokuswp-color-picker">
                    <div class="color-picker" style="display: inline-block;z-index:999;"></div>
                </div>
            </div>


            <!-- Secondary Color -->
            <div class="form-group">
                <div class="col-3 col-sm-12">
                    <label class="form-label" for="secondary-color"><?php _e( 'Accent Color', 'lokuswp' ); ?></label>
                </div>
                <div class="col-9 col-sm-12" style="height: 10px !important;">
                    <input type="text" name="secondary_color"
                           value="<?php echo isset( $settings['secondary_color'] ) ? esc_attr( $settings['secondary_color'] ) : '#282828'; ?>"
                           class="lokuswp-color-picker">
                    <div class="color-picker" style="display: inline-block;z-index:999;"></div>
                </div>
            </div>

			<?php
			$options = new Switch_Options();
			?>
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
