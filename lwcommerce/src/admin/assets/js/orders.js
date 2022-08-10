(function ($) {
    'use strict';

    // Check date if it over 1 hour
    function checkDate(date) {
        const now = new Date();
        const dateTime = new Date(date);
        const diff = now.getTime() - dateTime.getTime();
        const diffHours = Math.floor(diff / (1000 * 60 * 60));
        return diffHours > 1;
    }

    const ucfirst = (string) => {
        return string.charAt(0).toUpperCase() + string.slice(1)
    }

    function escHTML(unsafe) {
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

    // Convert date to Date Month Year indonesia
    function convertDate(date) {
        const dateTime = new Date(date);
        const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        const month = monthNames[dateTime.getMonth()];
        const year = dateTime.getFullYear();
        const day = dateTime.getDate() < 10 ? '0' + dateTime.getDate() : dateTime.getDate();

        // add leading 0 if hour, minute, second < 10
        const hour = dateTime.getHours() < 10 ? '0' + dateTime.getHours() : dateTime.getHours();
        const minute = dateTime.getMinutes() < 10 ? '0' + dateTime.getMinutes() : dateTime.getMinutes();
        const second = dateTime.getSeconds() < 10 ? '0' + dateTime.getSeconds() : dateTime.getSeconds();

        return day + ' ' + month + ' ' + year + ' - ' + hour + ':' + minute + ':' + second;
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
                    url: lwc_orders.ajax_url,
                    data: d => {
                        d.action = 'lwc_get_orders';
                        d.security = lwc_orders.ajax_nonce;
                        d.dateFilter = $('#date-filter-value').val();
                        d.orderFilter = $('#orders-filter-value').val();
                    },
                    // success: d => {
                    //     console.log(d);
                    // },
                    complete: function (jqXHR) {
                        $('.lwc-overlay-table').hide();
                    }
                },
                columns: [{
                    data: 'transaction_id',
                    render: function (nTd, sData, data, row, col) {
                        return `
                        <div class="container">
                            <div class="lwc-card-header lwc-card-shadow">
                                <div>
                                    <span class="lwc-text-bold lwc-mr-10">#${data.transaction_id}</span>
                                    <span class="lwc-text-bold lwc-mr-10">${convertDate(data.created_at)}</span>
                                </div>
                                <div style="display: flex; align-items: center; gap: .5rem;">
                                    <img src="${data.payment_url}" alt="payment logo" width="70px"/>
                                    <span style="font-weight: bold; padding: 0 .4rem 0 .4rem">
                                        Status:
                                        <span style="
                                             ${data.order_status.toLowerCase() === 'pending' ? `color: #38c;` : ''}
                                             ${data.order_status.toLowerCase() === 'shipped' ? `color: #ffb300;` : ''}
                                             ${data.order_status.toLowerCase() === 'completed' ? `color: #085;` : ''}
                                             ${data.order_status.toLowerCase() === 'refunded' ? `color: #ff0000;` : ''}
                                              ">
                                            ${ucfirst(data.order_status.toLowerCase())}
                                        </span>
                                    </span>
                                    ${data.shipping_type === 'digital' ? `
                                        ${data.order_status === 'refunded' ? '' : `
                                            <button class="btn btn-primary order-action" data-status="${data.order_status}" data-id="${data.transaction_id}" data-shipping="${data.shipping_type}">
                                                    ${data.order_status === 'pending' ? 'Sudah Dibayar' : ''}
                                                    ${data.order_status === 'shipped' ? 'Completed' : ''}
                                                    ${data.order_status === 'completed' ? 'Refunded' : ''} 
                                            </button>
                                        `}
                                    ` : `${data.order_status === 'refunded' ? '' : `
                                            <button class="btn btn-primary order-action" data-status="${data.order_status}" data-id="${data.transaction_id}" data-shipping="${data.shipping_type}">
                                                    ${data.order_status === 'pending' ? 'Sudah Dibayar' : ''}
                                                    ${data.order_status === 'processing' ? 'Shipped' : ''}
                                                    ${data.order_status === 'shipped' ? 'Completed' : ''}
                                                    ${data.order_status === 'completed' ? 'Refunded' : ''} 
                                            </button>
                                        `}`}
                                    <button class="btn btn-error delete-action" data-id="${data.transaction_id}">
                                        Delete
                                    </button>
                                </div>
                            </div>
                            <div class="lwc-card-body ">
                                <div class="lwc-grid lwc-grid-cols-3">
                                    <div class="lwc-grid-item lwc-justify-content-space-between">
                                        <div class="lwc-flex-column">
                                            <span class="lwc-text-bold">Transaksi</span>
                                            ${data.product.map((item, index) => {
                            return `
                                                    <div class="lwc-grid-item ${index >= 1 ? 'lwc-hidden' : ``}">
                                                        <img src="${item.image || 'https://i.pinimg.com/originals/a6/e8/d6/a6e8d6c8122c34de94463e071a4c7e45.png'}" alt="gedung" width="100px" height="100px" style="margin-right: 0.5rem">
                                                        <div class="lwc-flex-column">
                                                            <span class="lwc-text-bold">${item.post_title}</span>
                                                            <span class="lwc-text-bold">${item.quantity} x ${item.price_promo !== null ? `<del class="del">${item.price}</del> ${item.price_promo}` : item.price}</span>
                                                            ${data.product.length > 1 ? `<a style="color: #0EBA29; margin-top: 10px" class="lwc-hover more-product">Lihat ${data.product.length - 1} Produk Lainya...</a>` : ``}
                                                        </div>
                                                    </div>
                                                `;
                        }).join(' ')}
                                            <a style="color: #0EBA29; margin-left: 5.4rem;" class="lwc-hover lwc-grid-item lwc-hidden show-less">Show Less...</a>
                                            <span class="lwc-text-bold">${data.product.length} Barang</span>
                                        </div>
                                        ${lwc_orders.is_pro ? `
                                            <div class="lwc-flex-column">
                                                <span>Kupon:</span>
                                                <span>${data.coupon !== '0' ? data.coupon : '-'}</span>
                                            </div>` : ``
                        }
                                    </div>
                                    <div class="lwc-grid-item">
                                        <div class="lwc-flex-column">
                                            <span class="lwc-text-bold">Pembeli</span>
                                            <span style="margin-top: 10px">Name: ${data.name}</span>
                                            <span>Phone: ${data.phone}</span>
                                            <span>Email: ${data.email}</span>
                                        </div>
                                    </div>
                                    ${data.shipping_type === 'digital' ?
                            `<div class="lwc-grid-item lwc-justify-content-space-between">
                                            <div class="lwc-flex-column">
                                                <span class="lwc-text-bold">Pengiriman Digital</span>
                                                <span style="margin-top: 10px">Alamat:</span>
                                                <span>${data.email}</span>
                                                <span>${data.phone}</span>
                                            </div>
                                            <div class="lwc-flex-column">
                                                ${data.shipping_type === 'digital' ?
                                ``
                                :
                                `<span class="lwc-text-bold">Kurir</span>
                                                    ${data.courier ? `-`
                                    :
                                    `<span>${data.courier.toUpperCase()} ${data.service.toUpperCase()}</span>
                                                        <span style="margin-top: 10px" class="lwc-text-bold">Nomor Resi</span>
                                                        ${data.status_processing === 'processed' ? `
                                                            <input type="text" class="lwc-input-text" placeholder="Masukkan nomor resi" id="resi">
                                                            <button class="lwc-btn-rounded" id="btn-resi" data-id="${data.transaction_id}">tambah</button>
                                                        ` : data.status_processing === 'shipping' ? `` : `<span>-</span>`}
                                                        ${data.status_processing === 'shipping' ? `<span class="lwc-hover lwc-text-underline__on-hover" style="font-weight: bold; color: #5c5c5c;">${data.no_resi}</span>` : ``}`
                                }`
                            }
                                            </div>
                                        </div>`
                            :
                            `<div class="lwc-grid-item lwc-justify-content-space-between">
                                            <div class="lwc-flex-column">
                                                <span class="lwc-text-bold">Pengiriman Physical</span>
                                                <span style="margin-top: 10px">${data.address.address ?? ''}</span>
                                                <div class="lwc-flex">
                                                    <span>${data.address.district ?? ''}</span>
                                                    <span>&nbsp;&nbsp;&nbsp;${data.address.postal_code ?? ''}</span>
                                                </div>
                                                <span>${data.address.city ?? ''}</span>
                                                <span>${data.address.state ?? ''}</span>
                                            </div>
                                            <div class="lwc-flex-column">
                                                ${data.shipping_type === 'digital' ?
                                `<span class="lwc-text-bold">Kurir</span>
                                                            <span>-</span>`
                                :
                                `<span class="lwc-text-bold">Kurir</span>
                                                    ${data.courier == 0 ?
                                    '-'
                                    :
                                    `<span>${data.courier.toUpperCase()} ${data.service.toUpperCase()}</span>
                                                        <span style="margin-top: 10px" class="lwc-text-bold">Nomor Resi</span>
                                                        ${data.status_processing === 'processed' ? `
                                                            <input type="text" class="lwc-input-text" placeholder="Masukkan nomor resi" id="resi">
                                                            <button class="lwc-btn-rounded" id="btn-resi" data-id="${data.transaction_id}">tambah</button>
                                                        ` : data.status_processing === 'shipping' ? `` : `<span>-</span>`}
                                                        ${data.status_processing === 'shipping' ? `<span class="lwc-hover lwc-text-underline__on-hover" style="font-weight: bold; color: #5c5c5c;">${data.no_resi}</span>` : ``}`
                                }`
                            }
                                            </div>
                                        </div>`
                        }
                                </div>
                            </div>
                            <div class="lwc-card-footer lwc-card-shadow">
                                <div class="lwc-grid lwc-grid-cols-3">
                                    <div class="lwc-flex lwc-justify-content-space-between">
                                        <span class="lwc-text-bold">Total: ${data.raw_total === '0' ? 'Gratis' : data.total}</span>
                                        ${lwc_orders.is_pro ? `
                                        <button class="btn lwc-mr-10 lwc-btn-print-invoice" data-id="${data.transaction_id}">
                                            <div class="lwc-flex">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="lwc-search-icon lwc-mr-10" viewBox="0 0 20 20" fill="currentColor">
                                                  <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd" />
                                                </svg>
                                                <span>Print Invoice</span>
                                            </div>
                                        </button>
                                        ` : ``}
                                    </div>
                                    <div class="lwc-flex lwc-justify-content-space-between">
                                        <button class="btn btn-success lwc-mr-40 lwc-btn-follow-up" data-phone="${data.phone}" data-order='${JSON.stringify(data)}'>
                                            <div class="lwc-flex">
                                                <svg class="lwc-search-icon lwc-mr-10" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 448 512">
                                                  <path
                                                    d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"
                                                  ></path>
                                                </svg>
                                                <span>Follow Up</span>
                                            </div>
                                        </button>
                                    </div>
                                    <div class="lwc-flex lwc-justify-content-space-between">
                                        <span class="lwc-text-bold"></span>
                                        <span class="lwc-text-bold">${data.country === 'ID' ? 'Indonesia' : ''}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    }
                }],
            })

        tableOrders.on('processing.dt', function (e, settings, processing) {
            $('.lwc-overlay-table').show();
        });

        tableOrders.on('draw', function () {
            $('.lwc-loading-filter').hide();
            $('.lwc-overlay-table').hide();
        });

        // Make payment complete
        $(document).on('click', '.payment-status', function () {
            $(this).siblings('.dropdown-payment-status').slideToggle('fast');
            $(this).children().toggle();
        });

        $(document).on('click', '.change-payment-status', function () {
            $(this).addClass('loading');
            $.ajax({
                url: lwc_orders.ajax_url,
                method: 'POST',
                data: {
                    action: 'lwc_change_payment_status',
                    security: lwc_orders.ajax_nonce,
                    transaction_id: $(this).attr('data-id'),
                    status: $(this).attr('data-status'),
                },
            })
        })

        // Update resi
        $(document).on('click', '#btn-resi', function (e) {
            e.preventDefault();
            $(this).text('Processing...');
            $(this).attr('disabled');
            $(this).css('background-color', '#ccc');
            const transaction_id = $(this).attr('data-id');
            const resi = $('#resi').val();
            $.ajax({
                url: lwc_orders.ajax_url,
                method: 'POST',
                data: {
                    action: 'lwc_update_resi',
                    security: lwc_orders.ajax_nonce,
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

        $(document).on('click', '.more-product', function (e) {
            $(this).parent().parent().find('show-less').removeClass('lwc-hidden');
            $(this).parent().parent().siblings().removeClass('lwc-hidden');
            $(this).parent().parent().parent().find('.more-product').hide();
        });

        $(document).on('click', '.show-less', function (e) {
            $(this).addClass('lwc-hidden');
            const products = $(this).siblings('.lwc-grid-item');
            for (let i = 0; i < products.length; i++) {
                if (i >= 1) {
                    $(products[i]).addClass('lwc-hidden');
                }
            }
            products.children().find('.more-product').show();
        });

        $(document).on('click', '.process-order', function (e) {
            $(this).text('Processing...');
            $(this).attr('disabled');
            $(this).css('background-color', '#ccc');
            $.ajax({
                url: lwc_orders.ajax_url,
                method: 'POST',
                data: {
                    action: 'lwc_process_order',
                    security: lwc_orders.ajax_nonce,
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

        // Filter
        $(document).on('click', '.filter-orders', function () {
            const filter = $(this).attr('data-filter');
            $('#orders-filter-value').val(filter);
            tableOrders.draw();
            $(this).addClass('filter-selected');
            $(this).siblings().removeClass('filter-selected');
            $('.lwc-loading-filter').show();
        })

        $(document).on('click', '.filter-date', function () {
            const filter = $(this).attr('data-filter');
            $('#date-filter-value').val(filter);
            tableOrders.draw();
            $(this).addClass('filter-selected');
            $(this).siblings().removeClass('filter-selected');
            $('.lwc-loading-filter').show();
            $('#datetimerange-input1').siblings('.lwp-commerce-order-filter-text').removeClass('filter-selected');
        })

        $(document).on('click', '.lwc-btn-dropdown', function () {
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
            tableOrders.search(this.value).draw();
        }, 500))

        $(document).on('click', '.lwc-btn-filter', function () {
            $('.filter-up').toggle();
            $('.filter-down').toggle();
            $('.lwc-dropdown-filter').slideToggle('fast');
        })

        window.addEventListener('apply.daterangepicker', function (ev) {
            $('#date-filter-value').val(`${ev.detail.startDate.format('YYYY-MM-DD')} / ${ev.detail.endDate.format('YYYY-MM-DD')}`);
            const dateTimeElement = $('#datetimerange-input1');
            dateTimeElement.siblings('.lwp-commerce-order-filter-text').addClass('filter-selected')
            dateTimeElement.parent().siblings().removeClass('filter-selected');
            tableOrders.draw();
            $('.lwc-loading-filter').show();
        });

        // One tap order action
        $(document).on('click', '.order-action', function () {
            const that = $(this);
            $(this).addClass('loading');
            $(this).attr('disabled', true);
            const action = $(this).attr('data-status');
            const orderId = $(this).attr('data-id');
            const shipping = $(this).attr('data-shipping');
            $.ajax({
                url: lwc_orders.ajax_url,
                type: 'POST',
                data: {
                    action: 'lwc_order_action',
                    security: lwc_orders.ajax_nonce,
                    order_id: orderId,
                    action_type: action,
                    shipping_type: shipping
                },
                success: data => {
                    console.log(data);
                    that.removeClass('loading');
                    tableOrders.draw();
                }
            })
        })

        // followup whatsaap
        $(document).on('click', '.lwc-btn-follow-up', function () {
            const that = $(this);
            const order = that.attr('data-order')

            that.addClass('loading');
            that.attr("disabled", "disabled");
            $.ajax({
                url: lwc_orders.ajax_url,
                type: 'POST',
                data: {
                    action: 'lwc_follow_up_whatsapp',
                    security: lwc_orders.ajax_nonce,
                    data_order: JSON.parse(order),
                    phone_number: that.attr('data-phone'),
                },
                success: data => {
                    window.open(`https://wa.me/${data.phone}?text=${data.message}`, '_blank');
                    that.removeClass('loading');
                    that.removeAttr('disabled');
                }
            })
        });

        // delete order
        $(document).on('click', '.delete-action', function () {
            const that = $(this);
            const orderId = $(this).attr('data-id');

            if (confirm('Yakin ingin menghapus pesanan?')) {
                that.addClass('loading');
                that.attr('disabled', true);

                $.ajax({
                    url: lwc_orders.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'lwc_delete_order',
                        security: lwc_orders.ajax_nonce,
                        order_id: orderId,
                    },
                    success: data => {
                        if (data.success) {
                            that.removeClass('loading');
                            that.removeAttr('disabled');
                            tableOrders.draw();
                        } else {
                            alert('Gagal menghapus pesanan');
                        }
                    }
                })
            }
        })

        // export order to csv
        $(document).on('click', '#export-order', function () {
            $.ajax({
                url: lwc_orders.ajax_url,
                type: 'POST',
                data: {
                    action: 'lwc_export_order',
                    security: lwc_orders.ajax_nonce,
                },
                success: response => {
                    if (!response.success) {
                        alert(response.data);
                        return;
                    }

                    // Make <a> element for download lwdonation.csv using tag download
                    let urllink = document.createElement("a");
                    urllink.download = `lwcommerce.csv`;
                    urllink.href = response.data;
                    urllink.click();
                    console.log('success export');
                }
            })
        })


    });
})(jQuery)
