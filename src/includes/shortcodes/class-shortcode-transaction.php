<?php 
// function that runs when shortcode is called
// function lokuswp_transaction()
// {
// $request = wp_remote_get('http://localhost:10024/wp-json/lokuswp/v1/payment/list');

// if (is_wp_error($request)) {
// 	return false; // Bail early
// }

// $body = wp_remote_retrieve_body($request);
// $parse = json_decode($body);
// ?>

<ul><?php
// 	foreach ($parse as $method_id => $item) { ?>
		<li>
		ID : <?php //  echo $method_id; ?><br>
 			Method : <?php // echo $item->name; ?><br>
			<img src="<?php // $item->logo; ?>"/>
		Auto Confirm : <?php // echo $item->auto_confirm == false ? "Manual" : "Otomatis"; ?>
		</li>
	<?php
// 	}
// 	?>
</ul><?php
// 	}
// // register shortcode
// add_shortcode('lokuswp_transaction', 'lokuswp_transaction');

