<?php

namespace LokusWP\Commerce;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Reports {

    public function render() {

        update_option( "lwcommerce_order_awaiting", 0 );

        $this->table();
        $this->modal_import();
    }

    private function table() {
        ?>
        <div class="lwc-container-filter">
            <button class="lwc-btn-filter" type="button" style="display: none !important;">
                Filter
                <svg xmlns="http://www.w3.org/2000/svg" class="lwc-search-icon filter-up" style="color: #5c5c5c" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" class="lwc-search-icon filter-down" style="display: none" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z"
                          clip-rule="evenodd"/>
                </svg>
            </button>
            <div class="lwc-search-box">
                <input type="text" class="lwc-input-search" id="search-order" required>
                <span class="lwc-floating-label">Invoice, Customer Name, No Resi and other</span>
                <button class="lwc-btn-search">
                    <svg xmlns="http://www.w3.org/2000/svg" class="lwc-search-icon" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
            <button class="btn" id="export-order">Export</button>
        </div>

        <div class="lwc-card-body lwc-card-shadow lwc-dropdown-filter lwc-card-shadow-blue lwc-relative">
            <div class="lwc-grid">

                <div>
                    <div class="lwc-flex-column lwc-gap-1">
                        <span class="lwp-commerce-order-filter-text noselect filter-orders filter-selected" data-filter="all"><?php _e( 'All Orders', 'lwcommerce' ) ?> (233)</span>
                        <span class="lwp-commerce-order-filter-text noselect filter-orders" data-filter="unpaid"><?php _e( 'Unpaid', 'lwcommerce' ) ?> (50)</span>
                        <span class="lwp-commerce-order-filter-text noselect filter-orders" data-filter="paid"><?php _e( 'Paid', 'lwcommerce' ) ?> (12)</span>
                        <span class="lwp-commerce-order-filter-text noselect filter-orders" data-filter="unprocessed"><?php _e( 'Unprocessed', 'lwcommerce' ) ?> (5)</span>
                        <span class="lwp-commerce-order-filter-text noselect filter-orders" data-filter="processing"><?php _e( 'In Process', 'lwcommerce' ) ?> (100)</span>
                        <span class="lwp-commerce-order-filter-text noselect filter-orders" data-filter="shipping"><?php _e( 'Shipping', 'lwcommerce' ) ?>(20)</span>
                        <input type="hidden" id="orders-filter-value" value="all">
                    </div>
                </div>

                <div class="lwc-flex-column lwc-gap-1">
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
            <div class="lwc-loading-filter lwc-absolute" style="display: none">
                <span class="loading" style="top: 50%;left: 50%;transform: translate(-50%, -50%);"></span>
            </div>
        </div>

        <div class="lwc-flex lwc-gap-1 currently-filtering" style="margin-left: 1.4rem; display: none">
            <span><?php _e( 'Filtering', 'lwcommerce' ) ?></span>
            <span class="lwc-filter-text viewing-search" style="font-weight: bold"></span> |
            <span class="lwc-filter-text viewing-order-filter" style="font-weight: bold"></span> |
            <span class="lwc-filter-text viewing-date-filter" style="font-weight: bold"></span>
        </div>

        <div class="wrap lwc-admin">
            <div class="columns">
                <!-- Table -->
                <div class="column col-12" style="padding: 0 20px; position: relative">
                    <div class="lwc-overlay-table"><h3>Loading .. Please wait</h3></div>
                    <table id="orders" class="display order-column" style="position:relative;">
                        <thead>
                        <tr>
                            <th> <?php _e( 'Donors', 'lwcommerce' ) ?> </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                <div class="container">
                                    <div class="lwc-card-header lwc-flex">
                                        <button class="lwc-skeleton lwc-skeleton-button" style="margin-right: 10px"></button>
                                        <span class="lwc-text-red lwc-mr-10">
                                            <div class="lwc-skeleton lwc-skeleton-text"></div>
                                        </span>
                                        <span lass="lwc-text-bold lwc-mr-10">
                                            <div class="lwc-skeleton lwc-skeleton-text"></div>
                                        </span>
                                    </div>
                                    <div class="lwc-card-body">
                                        <div class="lwc-grid lwc-m-20">
                                            <div class="lwc-grid-item">
                                                <div class="lwc-product-image lwc-skeleton" style="margin-right: 0.5rem"></div>
                                                <div class="lwc-flex-column">
                                                    <span class="lwc-text-bold">
                                                        <div class="lwc-skeleton lwc-skeleton-text"></div>
                                                    </span>
                                                    <span class="lwc-text-bold">
                                                        <div class="lwc-skeleton lwc-skeleton-text"></div>
                                                    </span>
                                                    <span class="lwc-text-secondary">
                                                        <div class="lwc-skeleton lwc-skeleton-text"></div>
                                                    </span>
                                                    <a style="color: #0EBA29; margin-top: 10px" class="lwc-hover">
                                                        <div class="lwc-skeleton lwc-skeleton-text"></div>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="lwc-grid-item">
                                                <div class="lwc-flex-column">
                                                    <span class="lwc-text-bold">
                                                        <div class="lwc-skeleton lwc-skeleton-text"></div>
                                                    </span>
                                                    <span style="margin-top: 10px">
                                                        <div class="lwc-skeleton lwc-skeleton-text"></div>
                                                    </span>
                                                    <span>
                                                        <div class="lwc-skeleton lwc-skeleton-text"></div>
                                                    </span>
                                                    <span>
                                                        <div class="lwc-skeleton lwc-skeleton-text"></div>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="lwc-grid-item">
                                                <div class="lwc-flex-column">
                                                    <span class="lwc-text-bold">
                                                        <div class="lwc-skeleton lwc-skeleton-text"></div>
                                                    </span>
                                                    <span style="margin-top: 10px">
                                                        <div class="lwc-skeleton lwc-skeleton-text"></div>
                                                    </span>
                                                    <div class="lwc-flex">
                                                        <span>
                                                            <div class="lwc-skeleton lwc-skeleton-text"></div>
                                                        </span>
                                                        <span>
                                                            <div class="lwc-skeleton lwc-skeleton-text"></div>
                                                        </span>
                                                    </div>
                                                    <span>
                                                        <div class="lwc-skeleton lwc-skeleton-text"></div>
                                                    </span>
                                                    <span>
                                                        <div class="lwc-skeleton lwc-skeleton-text"></div>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="lwc-grid-item">
                                                <div class="lwc-flex-column">
                                                    <span class="lwc-text-bold">
                                                        <div class="lwc-skeleton lwc-skeleton-text"></div>
                                                    </span>
                                                    <span>
                                                        <div class="lwc-skeleton lwc-skeleton-text"></div>
                                                    </span>
                                                    <span style="margin-top: 10px" class="lwc-text-bold">
                                                        <div class="lwc-skeleton lwc-skeleton-text"></div>
                                                    </span>
                                                    <span>-</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="lwc-card-footer">
                                        <div class="lwc-flex lwc-justify-content-space-between">
                                            <div class="lwc-flex">
                                                <span class="lwc-mr-100">
                                                    <div class="lwc-skeleton lwc-skeleton-text"></div>
                                                </span>
                                                <span class="lwc-mr-40">
                                                    <div class="lwc-skeleton lwc-skeleton-text"></div>
                                                </span>
                                                <span class="lwc-mr-10">
                                                    <div class="lwc-skeleton lwc-skeleton-text"></div>
                                                </span>
                                                <div type="text" class="lwc-skeleton lwc-skeleton-input"></div>
                                                <button class="lwc-skeleton lwc-skeleton-button"></button>
                                            </div>
                                            <div class="lwc-flex">
                                                <span class="lwc-text-bold lwc-mr-10">
                                                    <div class="lwc-skeleton lwc-skeleton-text"></div>
                                                </span>
                                                <button class="lwc-skeleton lwc-skeleton-button"></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="container">
                                    <div class="lwc-card-header lwc-flex">
                                        <button class="lwc-skeleton lwc-skeleton-button" style="margin-right: 10px"></button>
                                        <span class="lwc-text-red lwc-mr-10">
                                            <div class="lwc-skeleton lwc-skeleton-text"></div>
                                        </span>
                                        <span lass="lwc-text-bold lwc-mr-10">
                                            <div class="lwc-skeleton lwc-skeleton-text"></div>
                                        </span>
                                    </div>
                                    <div class="lwc-card-body">
                                        <div class="lwc-grid lwc-m-20">
                                            <div class="lwc-grid-item">
                                                <div class="lwc-product-image lwc-skeleton" style="margin-right: 0.5rem"></div>
                                                <div class="lwc-flex-column">
                                                    <span class="lwc-text-bold">
                                                        <div class="lwc-skeleton lwc-skeleton-text"></div>
                                                    </span>
                                                    <span class="lwc-text-bold">
                                                        <div class="lwc-skeleton lwc-skeleton-text"></div>
                                                    </span>
                                                    <span class="lwc-text-secondary">
                                                        <div class="lwc-skeleton lwc-skeleton-text"></div>
                                                    </span>
                                                    <a style="color: #0EBA29; margin-top: 10px" class="lwc-hover">
                                                        <div class="lwc-skeleton lwc-skeleton-text"></div>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="lwc-grid-item">
                                                <div class="lwc-flex-column">
                                                    <span class="lwc-text-bold">
                                                        <div class="lwc-skeleton lwc-skeleton-text"></div>
                                                    </span>
                                                    <span style="margin-top: 10px">
                                                        <div class="lwc-skeleton lwc-skeleton-text"></div>
                                                    </span>
                                                    <span>
                                                        <div class="lwc-skeleton lwc-skeleton-text"></div>
                                                    </span>
                                                    <span>
                                                        <div class="lwc-skeleton lwc-skeleton-text"></div>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="lwc-grid-item">
                                                <div class="lwc-flex-column">
                                                    <span class="lwc-text-bold">
                                                        <div class="lwc-skeleton lwc-skeleton-text"></div>
                                                    </span>
                                                    <span style="margin-top: 10px">
                                                        <div class="lwc-skeleton lwc-skeleton-text"></div>
                                                    </span>
                                                    <div class="lwc-flex">
                                                        <span>
                                                            <div class="lwc-skeleton lwc-skeleton-text"></div>
                                                        </span>
                                                        <span>
                                                            <div class="lwc-skeleton lwc-skeleton-text"></div>
                                                        </span>
                                                    </div>
                                                    <span>
                                                        <div class="lwc-skeleton lwc-skeleton-text"></div>
                                                    </span>
                                                    <span>
                                                        <div class="lwc-skeleton lwc-skeleton-text"></div>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="lwc-grid-item">
                                                <div class="lwc-flex-column">
                                                    <span class="lwc-text-bold">
                                                        <div class="lwc-skeleton lwc-skeleton-text"></div>
                                                    </span>
                                                    <span>
                                                        <div class="lwc-skeleton lwc-skeleton-text"></div>
                                                    </span>
                                                    <span style="margin-top: 10px" class="lwc-text-bold">
                                                        <div class="lwc-skeleton lwc-skeleton-text"></div>
                                                    </span>
                                                    <span>-</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="lwc-card-footer">
                                        <div class="lwc-flex lwc-justify-content-space-between">
                                            <div class="lwc-flex">
                                                <span class="lwc-mr-100">
                                                    <div class="lwc-skeleton lwc-skeleton-text"></div>
                                                </span>
                                                <span class="lwc-mr-40">
                                                    <div class="lwc-skeleton lwc-skeleton-text"></div>
                                                </span>
                                                <span class="lwc-mr-10">
                                                    <div class="lwc-skeleton lwc-skeleton-text"></div>
                                                </span>
                                                <div type="text" class="lwc-skeleton lwc-skeleton-input"></div>
                                                <button class="lwc-skeleton lwc-skeleton-button"></button>
                                            </div>
                                            <div class="lwc-flex">
                                                <span class="lwc-text-bold lwc-mr-10">
                                                    <div class="lwc-skeleton lwc-skeleton-text"></div>
                                                </span>
                                                <button class="lwc-skeleton lwc-skeleton-button"></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="container">
                                    <div class="lwc-card-header lwc-flex">
                                        <button class="lwc-skeleton lwc-skeleton-button" style="margin-right: 10px"></button>
                                        <span class="lwc-text-red lwc-mr-10">
                                            <div class="lwc-skeleton lwc-skeleton-text"></div>
                                        </span>
                                        <span lass="lwc-text-bold lwc-mr-10">
                                            <div class="lwc-skeleton lwc-skeleton-text"></div>
                                        </span>
                                    </div>
                                    <div class="lwc-card-body">
                                        <div class="lwc-grid lwc-m-20">
                                            <div class="lwc-grid-item">
                                                <div class="lwc-product-image lwc-skeleton" style="margin-right: 0.5rem"></div>
                                                <div class="lwc-flex-column">
                                                    <span class="lwc-text-bold">
                                                        <div class="lwc-skeleton lwc-skeleton-text"></div>
                                                    </span>
                                                    <span class="lwc-text-bold">
                                                        <div class="lwc-skeleton lwc-skeleton-text"></div>
                                                    </span>
                                                    <span class="lwc-text-secondary">
                                                        <div class="lwc-skeleton lwc-skeleton-text"></div>
                                                    </span>
                                                    <a style="color: #0EBA29; margin-top: 10px" class="lwc-hover">
                                                        <div class="lwc-skeleton lwc-skeleton-text"></div>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="lwc-grid-item">
                                                <div class="lwc-flex-column">
                                                    <span class="lwc-text-bold">
                                                        <div class="lwc-skeleton lwc-skeleton-text"></div>
                                                    </span>
                                                    <span style="margin-top: 10px">
                                                        <div class="lwc-skeleton lwc-skeleton-text"></div>
                                                    </span>
                                                    <span>
                                                        <div class="lwc-skeleton lwc-skeleton-text"></div>
                                                    </span>
                                                    <span>
                                                        <div class="lwc-skeleton lwc-skeleton-text"></div>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="lwc-grid-item">
                                                <div class="lwc-flex-column">
                                                    <span class="lwc-text-bold">
                                                        <div class="lwc-skeleton lwc-skeleton-text"></div>
                                                    </span>
                                                    <span style="margin-top: 10px">
                                                        <div class="lwc-skeleton lwc-skeleton-text"></div>
                                                    </span>
                                                    <div class="lwc-flex">
                                                        <span>
                                                            <div class="lwc-skeleton lwc-skeleton-text"></div>
                                                        </span>
                                                        <span>
                                                            <div class="lwc-skeleton lwc-skeleton-text"></div>
                                                        </span>
                                                    </div>
                                                    <span>
                                                        <div class="lwc-skeleton lwc-skeleton-text"></div>
                                                    </span>
                                                    <span>
                                                        <div class="lwc-skeleton lwc-skeleton-text"></div>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="lwc-grid-item">
                                                <div class="lwc-flex-column">
                                                    <span class="lwc-text-bold">
                                                        <div class="lwc-skeleton lwc-skeleton-text"></div>
                                                    </span>
                                                    <span>
                                                        <div class="lwc-skeleton lwc-skeleton-text"></div>
                                                    </span>
                                                    <span style="margin-top: 10px" class="lwc-text-bold">
                                                        <div class="lwc-skeleton lwc-skeleton-text"></div>
                                                    </span>
                                                    <span>-</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="lwc-card-footer">
                                        <div class="lwc-flex lwc-justify-content-space-between">
                                            <div class="lwc-flex">
                                                <span class="lwc-mr-100">
                                                    <div class="lwc-skeleton lwc-skeleton-text"></div>
                                                </span>
                                                <span class="lwc-mr-40">
                                                    <div class="lwc-skeleton lwc-skeleton-text"></div>
                                                </span>
                                                <span class="lwc-mr-10">
                                                    <div class="lwc-skeleton lwc-skeleton-text"></div>
                                                </span>
                                                <div type="text" class="lwc-skeleton lwc-skeleton-input"></div>
                                                <button class="lwc-skeleton lwc-skeleton-button"></button>
                                            </div>
                                            <div class="lwc-flex">
                                                <span class="lwc-text-bold lwc-mr-10">
                                                    <div class="lwc-skeleton lwc-skeleton-text"></div>
                                                </span>
                                                <button class="lwc-skeleton lwc-skeleton-button"></button>
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

    private function modal_import() {
        ?>
        <div class="modal" id="modal-refund-order">
            <a href="#close" class="modal-overlay" aria-label="Close"></a>
            <div class="modal-container">
                <div class="modal-header">
                    <a href="#close" class="btn btn-clear float-right" aria-label="Close"></a>
                    <div class="modal-title h5">Order <span></span></div>
                </div>
                <div class="modal-body">
                    <div class="content">
                        <!-- form input control -->
                        <div class="form-group">
                            <label class="form-label" for="refund-amount">Input Refund Amount</label>
                            <input class="form-input" type="number" id="refund-amount" placeholder="amount">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn lwc-btn-refund">Submit</button>
                </div>
            </div>
        </div>
        <?php
    }
}


$x = new Reports();
$x->render();