<?php

namespace LokaWP\Commerce;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Reports {

	public function render() {
//		update_option( 'lsdd_report_unread', 0 );

		$this->table();
		$this->panel_editor();
		$this->modal_import();
		$this->modal_add_report_manual();

//		$payment_method = get_option( 'lsdd_payment_settings' );
	}

	private function table() {
		?>
        <style>
            table.dataTable thead .sorting {
                background-image: url(<?= LWPC_URL . "/src/includes/libraries/js/datatables/sort_both.png" ?>)
            }

            table.dataTable thead .sorting_asc {
                background-image: url(<?= LWPC_URL . "/src/includes/libraries/js/datatables/sort_asc.png" ?>) !important
            }

            table.dataTable thead .sorting_desc {
                background-image: url(<?= LWPC_URL . "/src/includes/libraries/js/datatables/sort_desc.png" ?>) !important
            }

            table.dataTable thead .sorting_asc_disabled {
                background-image: url(<?= LWPC_URL . "/src/includes/libraries/js/datatables/sort_asc_disabled.png" ?>)
            }

            table.dataTable thead .sorting_desc_disabled {
                background-image: url(<?= LWPC_URL . "/src/includes/libraries/js/datatables/sort_desc_disabled.png" ?>)
            }
        </style>
        <!--        <div class="lwpc-screen-loading"></div>-->
        <div class="wrap lwpc-admin">
            <div class="columns">
                <!-- Table -->
                <div class="column col-12" style="padding: 0 20px;">
                    <div class="table-loading" style="display:none;"></div>
                    <table id="orders" class="display order-column" style="position:relative;">
                        <thead>
                        <tr>
                            <th> <?php _e( 'Donors', 'lwpcommerce' ) ?> </th>
                        </tr>
                        </thead>
                    </table>
                </div>
                <!-- Table -->

            </div>
        </div>

        <input type="hidden" name="link-url-invoice"
               value="<?= get_site_url( null, '/wp-content/uploads/lsddonation/invoice.pdf' ) ?>">
		<?php
	}

	private function panel_editor() {
		?>

        <div class="column col-6 col-12"
             style="top: 30px;position: fixed;right: 0;z-index:-1;height: 97.5%;width: 400px;display:none;">
            <div id="report_editor" class="panel" style="height: 100%;background: #fff;margin-right: -10px;">
                <div class="panel-header text-center">

                    <div class="panel-title h5 mt-10 float-left"><?php _e( 'Edit Report', 'lwpcommerce' ); ?></div>
                    <section class="panel-close float-right">
                        <i class="icon icon-cross"></i>
                    </section>
                </div>

                <div class="panel-body">
                    <div class="form-group">
                        <label class="form-label" for="nama"><?php _e( 'Name', 'lwpcommerce' ); ?></label>
                        <input class="form-input" type="text" id="name" placeholder="John Doe">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="nohp"><?php _e( 'Phone', 'lwpcommerce' ); ?></label>
                        <input class="form-input" type="text" id="phone" placeholder="08561655212">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="status"><?php _e( 'Status', 'lwpcommerce' ); ?></label>
                        <select id="status" class="select2" style="width:100%;">
                            <option value="completed"><?php _e( 'Complete', 'lwpcommerce' ); ?></option>
                            <option value="hold"><?php _e( 'Pending', 'lwpcommerce' ); ?></option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="date"><?php _e( 'Date', 'lwpcommerce' ); ?></label>
                        <input class="form-input" type="date" id="date" placeholder="12/12/2020">
                    </div>
                </div>

                <div class="panel-footer">
                    <button class="btn btn-primary btn-block" style="margin-bottom:15px"
                            id="lsdd_report_update"><?php _e( 'Update', 'lwpcommerce' ); ?></button>
                </div>

            </div>
        </div>
		<?php
	}

	private function modal_import() {
		?>
        <div class="modal modal-md" id="import-db">
            <button class="modal-overlay" aria-label="Close"></button>
            <div class="modal-container" role="document">
                <div class="modal-header">
                    <button class="btn btn-clear float-right modal-close" aria-label="Close"></button>
                    <div class="modal-title h5">
						<?php _e( 'Import', 'lwpcommerce' ); ?>
                    </div>
                </div>
                <div class="modal-body" style="padding-top:0;padding-bottom:25px;">
                    <small>
						<?php _e( 'This will replace all existing data in the database', 'lwpcommerce' ); ?>
                    </small>
                    <div class="input-group">
                        <input class="form-input" id="import-data" type="file" title="Choose CSV File">
                        <button id="import-click" class="btn btn-primary"
                                style="height: 40px;margin-top: -1px;padding:8px;" id="importcsv">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 512 512">
                                <title>ionicons-v5-j</title>
                                <rect x="128" y="128" width="336" height="336" rx="57" ry="57"
                                      style="fill:none;stroke:#fff;stroke-linejoin:round;stroke-width:32px"/>
                                <path d="M383.5,128l.5-24a56.16,56.16,0,0,0-56-56H112a64.19,64.19,0,0,0-64,64V328a56.16,56.16,0,0,0,56,56h24"
                                      style="fill:none;stroke:#fff;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px"/>
                                <line x1="296" y1="216" x2="296" y2="376"
                                      style="fill:none;stroke:#fff;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px"/>
                                <line x1="376" y1="296" x2="216" y2="296"
                                      style="fill:none;stroke:#fff;stroke-linecap:round;stroke-linejoin:round;stroke-width:32px"/>
                            </svg>
                            <span style="margin-left: 10px;float: right;margin-top: -1px;">
								<?php _e( 'Import', 'lwpcommerce' ); ?>
							</span>
                        </button>
                    </div>
                    <small>
						<?php _e( 'You have to adjust the import data first, export and adjust your data',
							'lwpcommerce' ); ?>
                    </small>
                </div>
            </div>
        </div>
		<?php
	}

	private function notification_invoice_downloaded() {
		?>
        <style>
            .notification-warp {
                position: fixed;
                top: 2.5rem;
                left: 70rem;
                z-index: 6;
            }

            div.notification_invoice {
                background: #fff;
                padding: 10px;
                border: 0;
                box-shadow: 0 0.25rem 1rem rgb(48 55 66 / 15%);
            }

            .badge-success {
                white-space: nowrap;
            }

            .badge-success[data-badge]::after {
                font-size: .7rem;
                height: .9rem;
                line-height: 1;
                min-width: .9rem;
                padding: .1rem .2rem;
                text-align: center;
                white-space: nowrap;
                position: absolute;
                top: 6px;
                left: -5px;
            }

            .badge-success:not([data-badge])::after,
            .badge-success[data-badge]::after {
                background: #000;
                background-clip: padding-box;
                border-radius: .5rem;
                box-shadow: 0 0 0 0.1rem #fff;
                color: #fff;
                content: attr(data-badge);
                display: inline-block;
                transform: translate(-.05rem, -.5rem);
            }
        </style>
        <div class="notification-warp col-3"></div>
		<?php
	}

	private function modal_add_report_manual() {
		?>
        <!-- Add report manual modal -->
        <div class="modal" id="add-report">
            <a href="#close" class="modal-overlay" aria-label="Close"></a>
            <div class="modal-container">
                <div class="modal-header">
                    <div class="columns">
                        <div class="column modal-title h5"><?php _e( 'Add Donation', 'lwpcommerce' ) ?></div>
                        <div class="column" style="text-align:right;">
                            <label for="reports-bulk-input"><?php _e( 'Input', 'lwpcommerce' ) ?></label>
                            <input class="col-2" type="text" id="reports-bulk-input" value="1"
                                   style="text-align:center;" maxlength="3">
                            <label for="reports-bulk-input"><?php _e( 'data', 'lwpcommerce' ) ?></label>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="content" id="warper-add-report"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary lsdd-report-save">Submit</button>
                    <button type="button" class="btn btn-add-donation-close">Close</button>
                </div>
            </div>
        </div>
		<?php
	}
}


$x = new Reports();
$x->render();