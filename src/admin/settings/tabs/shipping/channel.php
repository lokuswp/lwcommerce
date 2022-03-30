<?php

use LokusWP\Commerce\Shipping;

?>
<!-- Skeleton UI -->

<div class="container columns col-gapless header">
    <div class="column col-3"><?php _e( 'Name', 'lwcommerce' ); ?></div>
    <div class="column col-2 text-center"><?php _e( 'Zone', 'lwcommerce' ); ?></div>
    <div class="column col-3 text-center"><?php _e( 'Status', 'lwcommerce' ); ?></div>
    <div class="column  text-center"><?php _e( 'Method', 'lwcommerce' ); ?></div>
    <!-- <div class="column col text-right"><?php _e( 'Action', 'lwcommerce' ); ?></div> -->
</div>

<?php

class Shipping_Carrier {

	public function __construct() {

		if ( ! class_exists( 'LokusWP\Commerce\Shipping\Manager' ) ) {
			return false;
		}



		$carriers = Shipping\Manager::registered();


		if ( $carriers ) : ?>

            <ul class="methods" id="draggable">
				<?php foreach ( $carriers as $carrier ) : ?>

                    <li class="shipping-channel" draggable="true" style="vertical-align:middle">
                        <div class="services columns col-gapless">

                            <!-- Channel -->
                            <div class="column col-3 method"
                                 style="margin-bottom: -8px; display: flex; align-items: center">
                                <img src="<?php echo esc_url( $carrier->logo_url ); ?>"
                                     alt="<?= $carrier->name ?>"
                                     style="max-height:40px">
                                <h6 style="padding: 0 10px 0;">
									<?php esc_attr_e( $carrier->name ); ?>
                                </h6>
                            </div>

                            <!-- Zones -->
                            <div class="column col-2 method text-center" style="padding-top: 8px;">
								<?php foreach ( $carrier->zones as $key => $zone ) : ?>
                                    <span class="label label-rounded label-primary"><?php esc_attr_e( ucfirst( $zone ) ); ?></span>
								<?php endforeach; ?>
                            </div>

                            <!-- Status -->
                            <div class="column col-3 lwcommerce-shipping-status" style="display: flex; justify-content: center">
                                <div class="form-group">

                                    <label class="form-switch">
                                        <input type="checkbox"
                                               id="<?php echo $carrier->id; ?>" <?php echo ( $carrier->get_status() ) ? 'checked' : ''; ?>>
                                        <i class="form-icon"></i> <?php _e( 'Active', 'lwcommerce' ); ?>
                                    </label>

                                </div>
                            </div>

                            <!-- Method -->
                            <div class="column method  text-center">
                                <h6 style="padding-top: 8px;"><?php _e( "Send to Buyer", "lwcommerce", ); ?></h6>
                            </div>

                            <!-- Manage Button -->
                            <!-- <div class="column text-right">
                                <button class="btn " id="<?php //echo $carrier_id; ?>">
                                    <?php //_e( 'Manage', 'lwcommerce' ); ?>
                                </button>
                            </div> -->
                        </div>

                        <!-- Services -->
                        <div class="services-bar" style="width:100%;border-top:1px solid #ddd;">
							<?php if ( isset( $carrier->services ) && ! empty( $carrier->services ) ) : ?>
								<?php _e( 'Services', 'lwcommerce' ); ?> :
								<?php foreach ( $carrier->services as $key => $service ) : ?>
                                    <label class="form-checkbox">
                                        <input type="checkbox" checked=""><i class="form-icon"></i>
										<?php esc_attr_e( ucfirst( $key ) ); ?>
                                    </label>
                                    <!-- <input type="checkbox" id="<?php esc_attr_e( $key ); ?>" class="lwc_shipping_service_status" value="<?php esc_attr_e( $key ) ?>" <? ( $service === 'on' ) ? 'checked' : '' ?> data-action="<? shipping_id ?>"> -->
								<?php endforeach; ?>
							<?php endif; ?>
                        </div>

                    </li>

				<?php endforeach; ?>
            </ul>

		<?php endif;
	}
}

new Shipping_Carrier();


?>

<style>
    .services-bar input[type="checkbox"] {
        margin: 0 2px 0;;
        width: 14px !important;
    }

    .shipping-channel {
        /* padding:12px; */
        border-radius: 4px;
        border: 1px solid #ddd;
    }

    .shipping-channel .services {
        padding: 12px;
    }


    .shipping-channel .services-bar {
        padding: 0 12px 0;
    }

    .label.label-primary {
        padding: 3px 17px;
    }

    .form-checkbox {
        display: inline-block;
        margin-left: 4px;
    }
</style>
