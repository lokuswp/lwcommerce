(function ($) {
    'use strict';

    String.prototype.escapeHtml = function (unsafe)  {
        if ( ! unsafe ) { unsafe = this; }
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }
    $(document).ready(function () {
        //=============== Datatables ===============//
        const tableOrders = $('#orders').DataTable({
            processing: true,
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
                        <div class="lwpc-card-header">
                            <button class="lwpc-btn-rounded lwpc-mr-10">
                                Pesanan Baru
                            </button>
                            <span class="lwpc-text-red lwpc-mr-10">Ini Invoice</span>
                            <span class="lwpc-text-bold lwpc-mr-10">${data.name.escapeHtml()}</span>
                        </div>
                        <div class="lwpc-card-body">
                            <div class="lwpc-grid lwpc-m-20">
                                <div class="lwpc-flex-column">
                                    ${data.product.map((item, index) => {
                                        return `
                                            <div class="lwpc-grid-item ${index >= 1 ? 'lwpc-hidden' : ``}">
                                                <img src="${item.image || 'https://i.pinimg.com/originals/a6/e8/d6/a6e8d6c8122c34de94463e071a4c7e45.png'}" alt="gedung" width="100px" height="100px">
                                                <div class="lwpc-flex-column">
                                                    <span class="lwpc-text-bold">${item.post_title.escapeHtml()}</span>
                                                    <span class="lwpc-text-bold">${item.quantity.escapeHtml()} x ${item.price_discount !== null ? `<del class="del">${item.price.escapeHtml()}</del> ${item.price_discount.escapeHtml()}` : item.price.escapeHtml()}</span>
                                                    <span class="lwpc-text-secondary">"Note dari pembeli"</span>
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
                                        <span>${data.shipping.courier.toUpperCase().escapeHtml()} ${data.shipping.service.toUpperCase().escapeHtml()}</span>
                                        <span style="margin-top: 10px" class="lwpc-text-bold">Input resi?</span>
                                        <span>-</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="lwpc-card-footer">
                            <div class="lwpc-flex ">
                                <div>
                                    <span class="lwpc-mr-100">Detail Pesanan</span>
                                    <span class="lwpc-mr-40">Cetak Label</span>
                                    <span class="lwpc-mr-10">Note</span>
                                    <input type="text" class="lwpc-report-input">
                                    <button class="lwpc-btn-rounded-secondary ">Tambah Note</button>
                                </div>
                                <div>
                                    <span class="lwpc-text-bold lwpc-mr-10">${data.total}</span>
                                    <button class="lwpc-btn-rounded" style="background-color: #00BD12; color:white">Terima Pesanan</button>
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
            $('.show-less').removeClass('lwpc-hidden');
            $(this).parent().parent().siblings().removeClass('lwpc-hidden');
            $('.more-product').hide();
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
    });
})(jQuery)