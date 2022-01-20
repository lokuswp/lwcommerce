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
        let t = date.split(/[- :]/);
        const dateTime = new Date(Date.UTC(t[0], t[1] - 1, t[2], t[3], t[4], t[5]));
        const diff = now.getTime() - dateTime.getTime();
        const diffHours = Math.floor(diff / (1000 * 60 * 60));
        return diffHours > 1;
    }

    // Convert date to Date Month Year indonesia
    function convertDate(date) {
        let t = date.split(/[- :]/);
        const dateTime = new Date(Date.UTC(t[0], t[1] - 1, t[2], t[3], t[4], t[5]));
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
        const tableOrders = $('#orders').DataTable({
            // processing: true,
            serverSide: true,
            lengthMenu: [
                [10, 50, 100, 250, -1],
                [10, 50, 100, 250, "All"]
            ],
            language: {
                search: '',
                searchPlaceholder: 'Search....'
            },
            ajax: {
                url: lwpc_orders.ajax_url,
                data: d => {
                    d.action = 'lwpc_get_orders';
                    d.security = lwpc_orders.ajax_nonce;
                },
            },
            columns: [{
                data: 'transaction_id',
                render: function (nTd, sData, data, row, col) {
                    return `
                    <div class="container">
                        <div class="lwpc-card-header lwpc-card-shadow">
                        ${checkDate(data.created_at) ? `<span class="lwpc-badge lwpc-badge-seccondary">${convertDate(data.created_at)}</span>` : '<button class="lwpc-btn-rounded lwpc-mr-10">Pesanan Baru</button>'}
                            <span class="lwpc-text-red lwpc-mr-10">Ini Invoice</span>
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
                                                <img src="${item.image || 'https://i.pinimg.com/originals/a6/e8/d6/a6e8d6c8122c34de94463e071a4c7e45.png'}" alt="gedung" width="100px" height="100px">
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
                            <div class="lwpc-flex ">
                                <div>
                                    <span class="lwpc-mr-100">Detail Pesanan</span>
                                    <span class="lwpc-mr-40">Cetak Label</span>
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
        })

        tableOrders.on('draw', function () {
            document.querySelector('.lwpc-overlay').style.display = 'none';
        });

        $(document).on('click', '.more-product', function (e) {
            $(this).parent().parent().find('show-less').removeClass('lwpc-hidden');
            $(this).parent().parent().siblings().removeClass('lwpc-hidden');
            $(this).parent().parent().parent().find('.more-product').hide();
        });

        $(document).on('click', '.show-less', function (e) {
            $('.show-less').addClass('lwpc-hidden');
            const products = $(this).siblings();
            for (let i = 0; i < products.length; i++) {
                if (i >= 1) {
                    $(products[i]).addClass('lwpc-hidden');
                }
            }
            $('.more-product').show();
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

        $(document).on('click', '.lwpc-btn-dropdown', function () {
            $(this).parent().siblings().slideToggle('fast');
            $(this).children().toggle();
        })
    });
})(jQuery)