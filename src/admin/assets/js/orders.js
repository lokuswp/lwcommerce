(function ($) {
    'use strict';

    String.prototype.escapeHtml = function (unsafe) {
        if (!unsafe) {
            unsafe = this;
        }
        const entityMap = { // from mustache.js https://github.com/janl/mustache.js/blob/master/mustache.js#L60
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;',
            '/': '&#x2F;',
            '`': '&#x60;',
            '=': '&#x3D;'
        };
        return String(unsafe).replace(/[&<>"'`=\/]/g, function (s) {
            return entityMap[s];
        });
    }

    // Check date if it over 1 hour
    function checkDate(date) {
        const now = new Date();
        const dateTime = new Date(date);
        const diff = now.getTime() - dateTime.getTime();
        const diffHours = Math.floor(diff / (1000 * 60 * 60));
        return diffHours > 1;
    }

    // Convert date to Date Month Year indonesia
    function convertDate(date) {
        const dateTime = new Date(date);
        const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        const month = monthNames[dateTime.getMonth()];
        const year = dateTime.getFullYear();
        const day = dateTime.getDate();
        // add 0 if hour, minute, second < 10
        const hour = dateTime.getHours() < 10 ? '0' + dateTime.getHours() : dateTime.getHours();
        const minute = dateTime.getMinutes() < 10 ? '0' + dateTime.getMinutes() : dateTime.getMinutes();
        return day + ' ' + month + ' ' + year + ' ' + hour + ':' + minute;
    }


    $(document).ready(function () {

        //=============== Datatables ===============//
        const tableOrders = $('#orders').DataTable(
            {
                processing: false,
                serverSide: true,
                lengthChange: false,
                lengthMenu: [
                    [10, 50, 100, 250, -1],
                    [10, 50, 100, 250, "All"]
                ],
                ajax: {
                    url: lwpc_orders.ajax_url,
                    data: d => {
                        d.action = 'lwpc_get_orders';
                        d.security = lwpc_orders.ajax_nonce;
                        d.dateFilter = $('#date-filter-value').val();
                        d.orderFilter = $('#orders-filter-value').val();
                    },
                    complete: function (jqXHR) {
                        $('.lwpc-overlay-table').hide();
                        if (jqXHR.responseJSON.searchQuery.length > 0) {
                            $('.viewing-search').text(jqXHR.responseJSON.searchQuery);
                            $('.viewing-date-filter').text(ucfirst(jqXHR.responseJSON.dateFilter));
                            $('.viewing-order-filter').text(ucfirst(jqXHR.responseJSON.ordersFilter));
                            $('.currently-filtering').show();
                        } else {
                            if (jqXHR.responseJSON.dateFilter !== 'all' || jqXHR.responseJSON.ordersFilter !== 'all') {
                                $('.viewing-date-filter').text(ucfirst(jqXHR.responseJSON.dateFilter));
                                $('.viewing-order-filter').text(ucfirst(jqXHR.responseJSON.ordersFilter));
                                $('.currently-filtering').show();
                            }
                        }
                    }
                },
                columns: [{
                    data: 'transaction_id',
                    render: function (nTd, sData, data, row, col) {
                        return `
                    <div class="container">
                        <div class="lwpc-card-header lwpc-card-shadow">
                        ${checkDate(data.created_at) ? `<span class="lwpc-badge lwpc-badge-seccondary">${convertDate(data.created_at)}</span>` : '<button class="lwpc-btn-rounded lwpc-mr-10">Pesanan Baru</button>'}
                            <span class="lwpc-text-red lwpc-mr-10">${data.invoice}</span>
                            <span class="lwpc-text-bold lwpc-mr-10">${data.name.escapeHtml()}</span>
                            <span style="
                                         ${data.status_processing === 'unprocessed' ? `color: #38c;` : ''}
                                         ${data.status_processing === 'processed' ? `color: #38c;` : ''}
                                         ${data.status_processing === 'canceled' ? `color: #ff0000;` : ''}
                                         ${data.status_processing === 'shipping' ? `color: #f5a53d;` : ''}
                                         ${data.status_processing === 'oos' ? 'color: #910000;' : ''}
                                          font-weight: bold; float: right; padding: 0 .4rem 0 .4rem">
                                ${data.status_processing === 'unprocessed' ? 'Awaiting Processing' : ''}
                                ${data.status_processing === 'processed' ? 'Processing' : ''}
                                ${data.status_processing === 'canceled' ? 'Canceled' : ''}
                                ${data.status_processing === 'shipping' ? 'Shipping' : ''}
                                ${data.status_processing === 'oos' ? 'Out of Stock' : ''}
                            </span>
                             ${data.status === 'unpaid' ? `<span style="color: #fd6; font-weight: bold; float: right;">Awaiting Payment</span>` : `<span style="color: #085; font-weight: bold; float: right; padding: 0 .4rem 0 .4rem">Paid</span>`}
                        </div>
                        <div class="lwpc-card-body lwpc-card-shadow">
                            <div class="lwpc-grid lwpc-m-20">
                                <div class="lwpc-flex-column">
                                    ${data.product.map((item, index) => {
                            return `
                                            <div class="lwpc-grid-item ${index >= 1 ? 'lwpc-hidden' : ``}">
                                                <img src="${item.image || 'https://i.pinimg.com/originals/a6/e8/d6/a6e8d6c8122c34de94463e071a4c7e45.png'}" alt="gedung" width="100px" height="100px" style="margin-right: 0.5rem">
                                                <div class="lwpc-flex-column">
                                                    <span class="lwpc-text-bold">${item.post_title.escapeHtml()}</span>
                                                    <span class="lwpc-text-bold">${item.quantity.escapeHtml()} x ${item.price_discount !== null ? `<del class="del">${item.price.escapeHtml()}</del> ${item.price_discount.escapeHtml()}` : item.price.escapeHtml()}</span>
                                                    <span class="lwpc-text-secondary">"${item.note.escapeHtml().length > 20 ? item.note.escapeHtml().slice(0, 20) + '...' : item.note.escapeHtml()}"</span>
                                                    ${data.product.length > 1 ? `<a style="color: #0EBA29; margin-top: 10px" class="lwpc-hover more-product">Lihat ${data.product.length - 1} Produk Lainya...</a>` : ``}
                                                </div>
                                            </div>
                                        `;
                        }).join(' ')}
                                    <a style="color: #0EBA29; margin-top: 10px" class="lwpc-hover lwpc-grid-item lwpc-hidden show-less">Show Less...</a>
                                </div>
                                <div class="lwpc-grid-item">
                                    <div class="lwpc-flex-column">
                                        <span class="lwpc-text-bold">Pembeli</span>
                                        <span style="margin-top: 10px">${data.name.escapeHtml()}</span>
                                        <span>${data.phone.escapeHtml()}</span>
                                        <span>${data.email.escapeHtml()}</span>
                                    </div>
                                </div>
                                <div class="lwpc-grid-item">
                                    <div class="lwpc-flex-column">
                                        <span class="lwpc-text-bold">Alamat</span>
                                        <span style="margin-top: 10px">${data.address.address.escapeHtml() ?? ''}</span>
                                        <div class="lwpc-flex">
                                            <span>${data.address.district.escapeHtml() ?? ''}</span>
                                            <span>&nbsp;&nbsp;&nbsp;${data.address.postal_code ?? ''}</span>
                                        </div>
                                        <span>${data.address.city.escapeHtml() ?? ''}</span>
                                        <span>${data.address.state.escapeHtml() ?? ''}</span>
                                    </div>
                                </div>
                                <div class="lwpc-grid-item">
                                    <div class="lwpc-flex-column">
                                        <span class="lwpc-text-bold">Kurir</span>
                                        <span>${data.courier.toUpperCase().escapeHtml()} ${data.service.toUpperCase().escapeHtml()}</span>
                                        <span style="margin-top: 10px" class="lwpc-text-bold">Nomor Resi</span>
                                        ${data.status_processing === 'processed' ? `
                                                <input type="text" class="lwpc-input-text" placeholder="Masukkan nomor resi" id="resi">
                                                <button class="lwpc-btn-rounded" id="btn-resi" data-id="${data.transaction_id}">tambah</button>
                                            ` : data.status_processing === 'shipping' ? `` : `<span>-</span>`}
                                        ${data.status_processing === 'shipping' ? `<span class="lwpc-hover lwpc-text-underline__on-hover" style="font-weight: bold; color: #5c5c5c;">${data.no_resi.escapeHtml()}</span>` : ``}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="lwpc-card-footer lwpc-card-shadow">
                            <div class="lwpc-flex lwpc-justify-content-space-between">
                                <div>
                                    <span class="lwpc-mr-100">Detail Pesanan</span>
                                    <button class="btn lwpc-mr-40 lwpc-btn-print-invoice" data-id="${data.transaction_id}">
                                        <div class="lwpc-flex">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="lwpc-search-icon lwpc-mr-10" viewBox="0 0 20 20" fill="currentColor">
                                              <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd" />
                                            </svg>
                                            <span>Print Invoice</span>
                                        </div>
                                    </button>
                                    <span class="lwpc-mr-10">Note</span>
                                    <input type="text" class="lwpc-report-input">
                                    <button class="lwpc-btn-rounded-secondary" style="">Tambah Note</button>
                                </div>
                                <div>
                                    <span class="lwpc-text-bold lwpc-mr-10">${data.total}</span>
                                    ${data.status_processing === 'unprocessed' || data.status_processing === 'oos' ? `
                                        <div class="lwpc-dropdown">
                                            <div>
                                                <button type="button"
                                                        class="${data.status === 'unpaid' ? 'lwpc-btn-rounded-secondary' : 'lwpc-btn-dropdown'}" aria-expanded="true" aria-haspopup="true" ${data.status === 'unpaid' ? 'disabled' : ''}>
                                                    Process Order 
                                                    
                                                    <svg class="${data.status === 'unpaid' ? 'lwpc-hidden' : 'lwpc-dropdown-icon-down'}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                    </svg>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="lwpc-dropdown-icon-up" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </div>
                                
                                            <div class="lwpc-dropdown-content" role="menu" aria-orientation="vertical"
                                                 aria-labelledby="menu-button" tabindex="-1">
                                                <div class="py-1" role="none">
                                                    <!-- Active: "bg-gray-100 text-gray-900", Not Active: "text-gray-700" -->
                                                    <a href="#" class="lwpc-dropdown-item process-order" role="menuitem" tabindex="-1" data-id="${data.transaction_id}" data-status="processed">Process Order</a>
                                                    <a href="#" class="lwpc-dropdown-item process-order" role="menuitem" tabindex="-1" data-id="${data.transaction_id}" data-status="oos">Out of Stock</a>
                                                </div>
                                            </div>
                                        </div>` : ''}                                    
                                    ${data.status_processing === 'processed' ? `<button class="lwpc-btn-rounded-danger process-order" style="color:white" data-id="${data.transaction_id}" data-status="canceled">Cancel Order</button>` : ''}                                    
                                    ${data.status_processing === 'canceled' ? `<button class="lwpc-btn-rounded-warning process-order" style="color:white" data-id="${data.transaction_id}" data-status="processed">Cancel Cancellation</button>` : ''}                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    `;
                    }
                }],
            }
        )

        tableOrders.on('processing.dt', function (e, settings, processing) {
            $('.lwpc-overlay-table').show();
        });

        tableOrders.on('draw', function () {
            $('.lwpc-loading-filter').hide();
            $('.lwpc-overlay-table').hide();
        });

        $(document).on('click', '.more-product', function (e) {
            $(this).parent().parent().find('show-less').removeClass('lwpc-hidden');
            $(this).parent().parent().siblings().removeClass('lwpc-hidden');
            $(this).parent().parent().parent().find('.more-product').hide();
        });

        $(document).on('click', '.show-less', function (e) {
            $(this).addClass('lwpc-hidden');
            const products = $(this).siblings();
            for (let i = 0; i < products.length; i++) {
                if (i >= 1) {
                    $(products[i]).addClass('lwpc-hidden');
                }
            }
            products.children().find('.more-product').show();
        });

        $(document).on('click', '.process-order', function (e) {
            $(this).text('Processing...');
            $(this).attr('disabled');
            $(this).css('background-color', '#ccc');
            $.ajax({
                url: lwpc_orders.ajax_url,
                method: 'POST',
                data: {
                    action: 'lwpc_process_order',
                    security: lwpc_orders.ajax_nonce,
                    transaction_id: $(this).attr('data-id'),
                    status: $(this).attr('data-status'),
                },
                success: data => {
                    if (data.success) {
                        tableOrders.ajax.reload(null, false);
                    }
                }
            })
        })

        $(document).on('click', '#btn-resi', function (e) {
            e.preventDefault();
            $(this).text('Processing...');
            $(this).attr('disabled');
            $(this).css('background-color', '#ccc');
            const transaction_id = $(this).attr('data-id');
            const resi = $('#resi').val();
            $.ajax({
                url: lwpc_orders.ajax_url,
                method: 'POST',
                data: {
                    action: 'lwpc_update_resi',
                    security: lwpc_orders.ajax_nonce,
                    transaction_id: transaction_id,
                    resi: resi,
                },
                success: data => {
                    if (data.success) {
                        tableOrders.ajax.reload(null, false);
                    }
                }
            })
        })

        // Filter
        $(document).on('click', '.filter-orders', function () {
            const filter = $(this).attr('data-filter');
            $('#orders-filter-value').val(filter);
            tableOrders.draw();
            $(this).addClass('filter-selected');
            $(this).siblings().removeClass('filter-selected');
            $('.lwpc-loading-filter').show();
        })

        $(document).on('click', '.filter-date', function () {
            const filter = $(this).attr('data-filter');
            $('#date-filter-value').val(filter);
            tableOrders.draw();
            $(this).addClass('filter-selected');
            $(this).siblings().removeClass('filter-selected');
            $('.lwpc-loading-filter').show();
            $('#datetimerange-input1').siblings('.lwp-commerce-order-filter-text').removeClass('filter-selected');
        })

        $(document).on('click', '.lwpc-btn-dropdown', function () {
            $(this).parent().siblings().slideToggle('fast');
            $(this).children().toggle();
        })

        function delay(fn, ms) {
            let timer = 0
            return function (...args) {
                clearTimeout(timer)
                timer = setTimeout(fn.bind(this, ...args), ms || 0)
            }
        }

        $(document).on('keyup', '#search-order', delay(function () {
            console.log(this.value);
            tableOrders.search(this.value).draw();
        }, 500))

        $(document).on('click', '.lwpc-btn-filter', function () {
            $('.filter-up').toggle();
            $('.filter-down').toggle();
            $('.lwpc-dropdown-filter').slideToggle('fast');
        })

        window.addEventListener('apply.daterangepicker', function (ev) {
            $('#date-filter-value').val(`${ev.detail.startDate.format('YYYY-MM-DD')} / ${ev.detail.endDate.format('YYYY-MM-DD')}`);
            const dateTimeElement = $('#datetimerange-input1');
            dateTimeElement.siblings('.lwp-commerce-order-filter-text').addClass('filter-selected')
            dateTimeElement.parent().siblings().removeClass('filter-selected');
            tableOrders.draw();
            $('.lwpc-loading-filter').show();
        });

        const date = new Date(),
            dateNow = date.toISOString().split('T')[0].replaceAll('-', '');

        // print invoice
        $(document).on('click', '.lwpc-btn-print-invoice', function () {
            const transaction_id = $(this).attr('data-id');
            const that = $(this);

            // Add loading to button
            that.addClass('loading');
            $.ajax({
                url: lwpc_orders.ajax_url,
                method: 'POST',
                data: {
                    action: 'lwpc_print_invoice',
                    security: lwpc_orders.ajax_nonce,
                    transaction_id: transaction_id,
                },
                success: data => {
                    that.removeClass('loading');

                    // Make <a> element for download invoice.pdf using tag download
                    let urllink = document.createElement("a");
                    urllink.download = `invoice-${dateNow}-${data.id}.pdf`;
                    urllink.href = data.uri;
                    urllink.click();
                }
            }).fail(function () {
                alert('Please check your internet connection');
            })
        })
    });
})(jQuery)
