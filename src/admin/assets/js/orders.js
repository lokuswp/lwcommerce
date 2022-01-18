(function ($) {
    $(document).ready(function () {
        //=============== Datatables ===============//
        const tableReport = $('#orders').DataTable({
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
                    console.log(data);
                    return `
                    <div class="container">
                        <div class="lwpc-card-header">
                            <button class="lwpc-btn-rounded lwpc-mr-10">
                                Pesanan Baru
                            </button>
                            <span class="lwpc-text-red lwpc-mr-10">Ini Invoice</span>
                            <span class="lwpc-text-bold lwpc-mr-10">${data.name}</span>
                        </div>
                        <div class="lwpc-card-body">
                            <div class="lwpc-grid lwpc-m-20">
                                <div class="lwpc-flex-column">
                                    ${data.product.map( (item, index) => {
                                        return `
                                            <div class="lwpc-grid-item ${index >= 1 ? 'lwpc-hidden' : ``}">
                                                <img src="${item.image || 'https://i.pinimg.com/originals/a6/e8/d6/a6e8d6c8122c34de94463e071a4c7e45.png'}" alt="gedung" width="100px" height="100px">
                                                <div class="lwpc-flex-column">
                                                    <span class="lwpc-text-bold">${item.post_title}</span>
                                                    <span class="lwpc-text-bold">${item.quantity} x ${item.price_discount !== null ? `<del class="del">${item.price}</del> ${item.price_discount}` : item.price}</span>
                                                    <span class="lwpc-text-secondary">"Note dari pembeli"</span>
                                                    <a style="color: #0EBA29; margin-top: 10px" class="lwpc-hover more-product">Lihat ${data.product.length - 1} Produk Lainya...</a>
                                                </div>
                                            </div>
                                        `;
                                    }).join(' ')}
                                    <a style="color: #0EBA29; margin-top: 10px" class="lwpc-hover lwpc-grid-item lwpc-hidden show-less">Show Less...</a>
                                </div>
                                <div class="lwpc-grid-item">
                                    <div class="lwpc-flex-column">
                                        <span class="lwpc-text-bold">Pembeli</span>
                                        <span style="margin-top: 10px">${data.name}</span>
                                        <span>${data.phone}</span>
                                        <span>${data.email}</span>
                                    </div>
                                </div>
                                <div class="lwpc-grid-item">
                                    <div class="lwpc-flex-column">
                                        <span class="lwpc-text-bold">Alamat</span>
                                        <span style="margin-top: 10px">${data.address.address ?? ''}</span>
                                        <div class="lwpc-flex">
                                            <span>${data.address.district ?? ''}</span>
                                            <span>&nbsp;&nbsp;&nbsp;${data.address.postal_code ?? ''}</span>
                                        </div>
                                        <span>${data.address.city ?? ''}</span>
                                        <span>${data.address.state ?? ''}</span>
                                    </div>
                                </div>
                                <div class="lwpc-grid-item">
                                    <div class="lwpc-flex-column">
                                        <span class="lwpc-text-bold">Kurir</span>
                                        <span>${data.shipping.courier.toUpperCase()} ${data.shipping.service.toUpperCase()}</span>
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

        tableReport.on('draw', function () {
            document.querySelector('.lsdd-screen-loading').style.display = 'none';
        });

        $(document).on('click', '.more-product', function (e) {
            $('.show-less').removeClass('lwpc-hidden');
            $(this).parent().parent().siblings().removeClass('lwpc-hidden');
            $('.more-product').hide();
        });

        $(document).on('click', '.show-less', function (e) {
            $('.show-less').addClass('lwpc-hidden');
            const products = $(this).siblings();
            for(let i = 0; i < products.length; i++) {
                if(i >= 1) {
                    $(products[i]).addClass('lwpc-hidden');
                }
            }
            $('.more-product').show();
        });
    });
})(jQuery)