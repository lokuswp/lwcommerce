<?php

use LokusWP\Notification;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Notification_Admin {

	public function __construct() {

		if ( ! class_exists( 'LokusWP\Notification\Manager' ) ) {
			return false;
		}

		$notifications = Notification\Manager::registered(); ?>

        <div id="notifications" class="verticaltab form-horizontal">
			<?php foreach ( $notifications as $key => $item ) :
				?>
                <section class="tabitem">
                    <!-- Tab -->
					<?= $item->tab(); ?>

                    <article style="margin-top:12px">
                        <!-- Manage -->
						<?= $item->manage_template_notification( "lwcommerce" ); ?>
                    </article>
                </section>
			<?php endforeach; ?>
        </div>

		<?php
	}
}

new Notification_Admin();