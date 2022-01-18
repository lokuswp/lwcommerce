<?php
if (!defined('WPTEST')) {
	defined('ABSPATH') or die("Direct access to files is prohibited");
}

/**
 * Registers the autoloader for classes
 *
 * @author Michiel Tramper - https://www.makeitworkpress.com
 */
spl_autoload_register(function ($classname) {

	//	var_dump( $classname );

	$class     = str_replace('\\', DIRECTORY_SEPARATOR, strtolower($classname));
	$classpath = LWPC_PATH . 'src/includes' . DIRECTORY_SEPARATOR . $class . '.php';
	$classpath = str_replace("lokuswp/commerce/", "", $classpath);
	$classpath = str_replace("lokuswp\\commerce\\", "", $classpath); // fix path for windows
	$classpath = str_replace("_", "-", $classpath); // fix path for windows

	// WordPress

	if (file_exists($classpath)) {
		include_once $classpath;
	}
});

// $classmap = array(
//     'LokaWP\Commerce\Plugin' => 'includes/plugin.php',
//     'LokaWP\Commerce\Post_Types' => 'includes/wordpress/posttypes.php',
//     'LokaWP\Commerce\Metabox' => 'includes/wordpress/metabox.php'
// );

add_action("lokuswp/transaction/tab/header", function () {
?>
	<div class="swiper-slide">
		<?php _e('Shipping', 'lokuswp'); ?>
	</div>
<?php
});
add_action("lokuswp/transaction/tab/content", function () {
	// require_once LWPC_PATH . 'src/templates/transaction/shipping.php';
});
