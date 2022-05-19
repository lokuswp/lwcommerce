
const baseUrl = 'http://localhost:10039'

Cypress.on('uncaught:exception', (err, runnable) => {
    cy.clearCookies()
    return false
})

describe('Buy Free Product (Digital) via Bank Transfer', () => {

    beforeEach(() => {
        Cypress.Cookies.defaults({
            preserve: /lokuswp_/,
        })
    })

    it('add free product to cart', function () {

        // Visit Product Listing
        cy.visit(baseUrl + '/products/');

        // Add to Cart Free Product Digital
        cy.get('.entry-content > .lwc-listing > .lwc-product-item:nth-child(1) > .product-action > .lokus-btn').click()

        // Asset : Quantity 1
        cy.get('.val-qty-9').should('have.value', '1')

    });

    it.only('add and sub quantity in cart', function () {

        // Visit Product Cart
        cy.visit(baseUrl + '/cart');

        // Add Quantity in Cart
        cy.get('tr:first() .plus').click()

        // Asset : Quantity 2
        cy.get('.val-qty-9').should('have.value', '2')

        // Sub Quantity in Cart
        cy.get('tr:first() .minus').click()

        // Asset : Quantity 2
        cy.get('.val-qty-9').should('have.value', '1')

    });

    it('checkout - fill form data', function () {

        // Visit Checkout Page
        cy.visit(baseUrl + '/checkout');

        // Fill Customer Data
        cy.get('#lokuswp-form > .top > .checkout-form > .lwp-form-group > #name').type('Test')
        cy.get('#lokuswp-form > .top > .checkout-form > .lwp-form-group > #phone').type('08272784187841')
        cy.get('#lokuswp-form > .top > .checkout-form > .lwp-inline-form > #email').type('test@gmail.com')

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


    it('checkout -  choose shipping', function () {

    });

    it('checkout - choose payment method', function () {

    });

    it('after-checkout - have thank you screen', function () {

    });

    it('after-checkout - have order status section', function () {

    });

    it('after-checkout - have download file section', function () {

    });

    it('system-check - system was sending notification email to user : completed', function () {

    });

    it('system-check - system was sending notification email to admin : completed', function () {

    });

})

