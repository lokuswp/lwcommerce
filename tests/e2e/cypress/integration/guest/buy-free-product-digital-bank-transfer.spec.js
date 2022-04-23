
const baseUrl = 'http://localhost:10039'

Cypress.on('uncaught:exception', (err, runnable) => {
    // returning false here prevents Cypress from
    // failing the test
    cy.clearCookies()
    return false
})


describe('Buy Free Digital Product via Bank Transfer', () => {

    // Opening StoreFront
    beforeEach(() => {
        cy.viewport('macbook-13')
    })

    it('add free product to cart', function () {
        // Kunjungi Halaman Daftar Product
        // cy.visit(baseUrl + '/products');

        // Pilih Produk Pertama ( Gratis )
        // cy.get('button.lwc-addtocart').eq(0).click();

        // Cek :: Keranjang punya 1 produk
        // cy.get('.cart-qty').contains('1');
    });

    it('add quantity in cart', function () {
        // Kunjungi Halaman Keranjang
        // cy.visit(baseUrl + '/cart');
        // cy.wait(500)

        // Tambahkan Quantity
        // cy.get('tr:first() .plus').click()

        // Cek :: Keranjang punya 2 produk
        //
    });

    it('self checkout', function () {

        // Kunjungi Halaman Checkout
        // cy.visit(baseUrl + '/checkout');

        // Isi Data Pembeli
        // cy.get(':nth-child(1) > .form-control').type("Hafid")
        // cy.get(':nth-child(2) > .form-control').type("085216746174")
        // cy.get(':nth-child(3) > .form-control').type("hafid@lokuswp.com")

        // Klik Tombol Selanjutnya
        // cy.get('[data-cy="verify-form"]').click()

        // Cek :: Pindah ke Halaman Pengiriman

        // Klik Tombol Selanjutnya
        // cy.get('[data-cy="verify-shipping"]').click()

        // Cek :: Pindah ke Halaman Pembayaran

        // Pilih Metode Pembayaran Transfer Bank
        // cy.get('.form-group:first() .item-radio > label').click()

        // Buat Pesanan
        // cy.get('[data-cy="make-a-checkout"]').click();
    });

    it('checkout - fill form buyer', function () {

    });

    it('checkout - choose shipping', function () {

    });

    it('checkout - choose payment method', function () {

    });

    it('afterCheckout - have thank you screen', function () {

    });

    it('afterCheckout - have order status section', function () {

    });

    it('afterCheckout - have download file section', function () {

    });

    it('systemCheck - system was sending notification email to User : Completed', function () {

    });

    it('systemCheck - system was sending notification email to Admin : Completed', function () {

    });

})

