<?php

namespace LokusWP\Commerce;

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
        <div class="lwpc-container-filter">
            <button class="lwpc-btn-filter" type="button">
                Filter
                <svg xmlns="http://www.w3.org/2000/svg" class="lwpc-search-icon filter-up" style="color: #5c5c5c" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" class="lwpc-search-icon filter-down" style="display: none" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z"
                          clip-rule="evenodd"/>
                </svg>
            </button>
            <div class="lwpc-search-box">
                <input type="text" class="lwpc-input-search" id="search-order" required>
                <span class="lwpc-floating-label">Invoice, Customer Name, No Resi and other</span>
                <button class="lwpc-btn-search">
                    <svg xmlns="http://www.w3.org/2000/svg" class="lwpc-search-icon" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        </div>

        <div class="lwpc-card-body lwpc-card-shadow lwpc-dropdown-filter lwpc-card-shadow-blue lwpc-relative">
            <div class="lwpc-grid">

                <div>
                    <div class="lwpc-flex-column lwpc-gap-1">
                        <span class="lwp-commerce-order-filter-text noselect filter-orders filter-selected" data-filter="all"><?php _e( 'All Orders', 'lokuswp-commerce' ) ?> (233)</span>
                        <span class="lwp-commerce-order-filter-text noselect filter-orders" data-filter="unpaid"><?php _e( 'Unpaid', 'lokuswp-commerce' ) ?> (50)</span>
                        <span class="lwp-commerce-order-filter-text noselect filter-orders" data-filter="paid"><?php _e( 'Paid', 'lokuswp-commerce' ) ?> (12)</span>
                        <span class="lwp-commerce-order-filter-text noselect filter-orders" data-filter="unprocessed"><?php _e( 'Unprocessed', 'lokuswp-commerce' ) ?> (5)</span>
                        <span class="lwp-commerce-order-filter-text noselect filter-orders" data-filter="processing"><?php _e( 'In Process', 'lokuswp-commerce' ) ?> (100)</span>
                        <span class="lwp-commerce-order-filter-text noselect filter-orders" data-filter="shipping"><?php _e( 'Shipping', 'lokuswp-commerce' ) ?>(20)</span>
                        <input type="hidden" id="orders-filter-value" value="all">
                    </div>
                </div>

                <div class="lwpc-flex-column lwpc-gap-1">
                    <span class="lwp-commerce-order-filter-text noselect filter-date filter-selected" data-filter="all">All</span>
                    <span class="lwp-commerce-order-filter-text noselect filter-date" data-filter="today">Today</span>
                    <span class="lwp-commerce-order-filter-text noselect filter-date" data-filter="yesterday">Yesterday</span>
                    <span class="lwp-commerce-order-filter-text noselect filter-date" data-filter="last 7 day">Last 7 Day</span>
                    <span class="lwp-commerce-order-filter-text noselect filter-date" data-filter="this month">This Month</span>
                    <div>
                        <span class="lwp-commerce-order-filter-text noselect">Custom Range</span>
                        <input type="text" id="datetimerange-input1" size="24" style="text-align:center">
                        <link type="text/css" rel="stylesheet" href="https://cdn.jsdelivr.net/gh/alumuko/vanilla-datetimerange-picker@latest/dist/vanilla-datetimerange-picker.css">
                        <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js" type="text/javascript"></script>
                        <script src="https://cdn.jsdelivr.net/gh/alumuko/vanilla-datetimerange-picker@latest/dist/vanilla-datetimerange-picker.js"></script>
                        <script>
                            window.addEventListener("load", function (event) {
                                new DateRangePicker('datetimerange-input1');
                            });
                        </script>
                    </div>
                    <input type="hidden" id="date-filter-value" value="all">
                </div>

            </div>
            <div class="lwpc-loading-filter lwpc-absolute" style="display: none">
                <span class="loading" style="top: 50%;left: 50%;transform: translate(-50%, -50%);"></span>
            </div>
        </div>

        <!--        <div class="lwpc-flex lwpc-gap-1" style="margin-left: 1.4rem">-->
        <!--            <span>--><?php //_e( 'CURRENTLY FILTERING', 'lokuswp-commerce' ) ?><!--</span>-->
        <!--            <span class="lwpc-filter-text">samlekom</span>-->
        <!--            <span class="lwpc-filter-text">samlekom</span>-->
        <!--            <span class="lwpc-filter-text">samlekom</span>-->
        <!--        </div>-->

        <div class="wrap lwpc-admin">
            <div class="columns">
                <!-- Table -->
                <div class="column col-12" style="padding: 0 20px;">
                    <table id="orders" class="display order-column" style="position:relative;">
                        <thead>
                        <tr>
                            <th> <?php _e( 'Donors', 'lokuswp-commerce' ) ?> </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                <div class="container">
                                    <div class="lwpc-card-header lwpc-flex">
                                        <button class="lwpc-skeleton lwpc-skeleton-button" style="margin-right: 10px"></button>
                                        <span class="lwpc-text-red lwpc-mr-10">
                                            <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                        </span>
                                        <span lass="lwpc-text-bold lwpc-mr-10">
                                            <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                        </span>
                                    </div>
                                    <div class="lwpc-card-body">
                                        <div class="lwpc-grid lwpc-m-20">
                                            <div class="lwpc-grid-item">
                                                <div class="lwpc-product-image lwpc-skeleton" style="margin-right: 0.5rem"></div>
                                                <div class="lwpc-flex-column">
                                                    <span class="lwpc-text-bold">
                                                        <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                                    </span>
                                                    <span class="lwpc-text-bold">
                                                        <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                                    </span>
                                                    <span class="lwpc-text-secondary">
                                                        <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                                    </span>
                                                    <a style="color: #0EBA29; margin-top: 10px" class="lwpc-hover">
                                                        <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="lwpc-grid-item">
                                                <div class="lwpc-flex-column">
                                                    <span class="lwpc-text-bold">
                                                        <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                                    </span>
                                                    <span style="margin-top: 10px">
                                                        <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                                    </span>
                                                    <span>
                                                        <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                                    </span>
                                                    <span>
                                                        <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="lwpc-grid-item">
                                                <div class="lwpc-flex-column">
                                                    <span class="lwpc-text-bold">
                                                        <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                                    </span>
                                                    <span style="margin-top: 10px">
                                                        <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                                    </span>
                                                    <div class="lwpc-flex">
                                                        <span>
                                                            <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                                        </span>
                                                        <span>
                                                            <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                                        </span>
                                                    </div>
                                                    <span>
                                                        <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                                    </span>
                                                    <span>
                                                        <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="lwpc-grid-item">
                                                <div class="lwpc-flex-column">
                                                    <span class="lwpc-text-bold">
                                                        <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                                    </span>
                                                    <span>
                                                        <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                                    </span>
                                                    <span style="margin-top: 10px" class="lwpc-text-bold">
                                                        <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                                    </span>
                                                    <span>-</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="lwpc-card-footer">
                                        <div class="lwpc-flex lwpc-justify-content-space-between">
                                            <div class="lwpc-flex">
                                                <span class="lwpc-mr-100">
                                                    <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                                </span>
                                                <span class="lwpc-mr-40">
                                                    <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                                </span>
                                                <span class="lwpc-mr-10">
                                                    <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                                </span>
                                                <div type="text" class="lwpc-skeleton lwpc-skeleton-input"></div>
                                                <button class="lwpc-skeleton lwpc-skeleton-button"></button>
                                            </div>
                                            <div class="lwpc-flex">
                                                <span class="lwpc-text-bold lwpc-mr-10">
                                                    <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                                </span>
                                                <button class="lwpc-skeleton lwpc-skeleton-button"></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="container">
                                    <div class="lwpc-card-header lwpc-flex">
                                        <button class="lwpc-skeleton lwpc-skeleton-button" style="margin-right: 10px"></button>
                                        <span class="lwpc-text-red lwpc-mr-10">
                                            <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                        </span>
                                        <span lass="lwpc-text-bold lwpc-mr-10">
                                            <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                        </span>
                                    </div>
                                    <div class="lwpc-card-body">
                                        <div class="lwpc-grid lwpc-m-20">
                                            <div class="lwpc-grid-item">
                                                <div class="lwpc-product-image lwpc-skeleton" style="margin-right: 0.5rem"></div>
                                                <div class="lwpc-flex-column">
                                                    <span class="lwpc-text-bold">
                                                        <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                                    </span>
                                                    <span class="lwpc-text-bold">
                                                        <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                                    </span>
                                                    <span class="lwpc-text-secondary">
                                                        <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                                    </span>
                                                    <a style="color: #0EBA29; margin-top: 10px" class="lwpc-hover">
                                                        <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="lwpc-grid-item">
                                                <div class="lwpc-flex-column">
                                                    <span class="lwpc-text-bold">
                                                        <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                                    </span>
                                                    <span style="margin-top: 10px">
                                                        <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                                    </span>
                                                    <span>
                                                        <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                                    </span>
                                                    <span>
                                                        <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="lwpc-grid-item">
                                                <div class="lwpc-flex-column">
                                                    <span class="lwpc-text-bold">
                                                        <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                                    </span>
                                                    <span style="margin-top: 10px">
                                                        <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                                    </span>
                                                    <div class="lwpc-flex">
                                                        <span>
                                                            <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                                        </span>
                                                        <span>
                                                            <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                                        </span>
                                                    </div>
                                                    <span>
                                                        <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                                    </span>
                                                    <span>
                                                        <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="lwpc-grid-item">
                                                <div class="lwpc-flex-column">
                                                    <span class="lwpc-text-bold">
                                                        <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                                    </span>
                                                    <span>
                                                        <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                                    </span>
                                                    <span style="margin-top: 10px" class="lwpc-text-bold">
                                                        <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                                    </span>
                                                    <span>-</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="lwpc-card-footer">
                                        <div class="lwpc-flex lwpc-justify-content-space-between">
                                            <div class="lwpc-flex">
                                                <span class="lwpc-mr-100">
                                                    <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                                </span>
                                                <span class="lwpc-mr-40">
                                                    <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                                </span>
                                                <span class="lwpc-mr-10">
                                                    <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                                </span>
                                                <div type="text" class="lwpc-skeleton lwpc-skeleton-input"></div>
                                                <button class="lwpc-skeleton lwpc-skeleton-button"></button>
                                            </div>
                                            <div class="lwpc-flex">
                                                <span class="lwpc-text-bold lwpc-mr-10">
                                                    <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                                </span>
                                                <button class="lwpc-skeleton lwpc-skeleton-button"></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="container">
                                    <div class="lwpc-card-header lwpc-flex">
                                        <button class="lwpc-skeleton lwpc-skeleton-button" style="margin-right: 10px"></button>
                                        <span class="lwpc-text-red lwpc-mr-10">
                                            <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                        </span>
                                        <span lass="lwpc-text-bold lwpc-mr-10">
                                            <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                        </span>
                                    </div>
                                    <div class="lwpc-card-body">
                                        <div class="lwpc-grid lwpc-m-20">
                                            <div class="lwpc-grid-item">
                                                <div class="lwpc-product-image lwpc-skeleton" style="margin-right: 0.5rem"></div>
                                                <div class="lwpc-flex-column">
                                                    <span class="lwpc-text-bold">
                                                        <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                                    </span>
                                                    <span class="lwpc-text-bold">
                                                        <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                                    </span>
                                                    <span class="lwpc-text-secondary">
                                                        <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                                    </span>
                                                    <a style="color: #0EBA29; margin-top: 10px" class="lwpc-hover">
                                                        <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="lwpc-grid-item">
                                                <div class="lwpc-flex-column">
                                                    <span class="lwpc-text-bold">
                                                        <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                                    </span>
                                                    <span style="margin-top: 10px">
                                                        <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                                    </span>
                                                    <span>
                                                        <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                                    </span>
                                                    <span>
                                                        <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="lwpc-grid-item">
                                                <div class="lwpc-flex-column">
                                                    <span class="lwpc-text-bold">
                                                        <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                                    </span>
                                                    <span style="margin-top: 10px">
                                                        <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                                    </span>
                                                    <div class="lwpc-flex">
                                                        <span>
                                                            <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                                        </span>
                                                        <span>
                                                            <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                                        </span>
                                                    </div>
                                                    <span>
                                                        <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                                    </span>
                                                    <span>
                                                        <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="lwpc-grid-item">
                                                <div class="lwpc-flex-column">
                                                    <span class="lwpc-text-bold">
                                                        <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                                    </span>
                                                    <span>
                                                        <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                                    </span>
                                                    <span style="margin-top: 10px" class="lwpc-text-bold">
                                                        <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                                    </span>
                                                    <span>-</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="lwpc-card-footer">
                                        <div class="lwpc-flex lwpc-justify-content-space-between">
                                            <div class="lwpc-flex">
                                                <span class="lwpc-mr-100">
                                                    <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                                </span>
                                                <span class="lwpc-mr-40">
                                                    <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                                </span>
                                                <span class="lwpc-mr-10">
                                                    <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                                </span>
                                                <div type="text" class="lwpc-skeleton lwpc-skeleton-input"></div>
                                                <button class="lwpc-skeleton lwpc-skeleton-button"></button>
                                            </div>
                                            <div class="lwpc-flex">
                                                <span class="lwpc-text-bold lwpc-mr-10">
                                                    <div class="lwpc-skeleton lwpc-skeleton-text"></div>
                                                </span>
                                                <button class="lwpc-skeleton lwpc-skeleton-button"></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        </tbody>
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
        <!--        <div class="lwpc-overlay">-->
        <!--            <div class="lwpc-loading loading-position"></div>-->
        <!--        </div>-->
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