
const baseUrl = Cypress.env("baseUrl");

// Cypress.on('uncaught:exception', (err, runnable) => {
//     // returning false here prevents Cypress from
//     // failing the test
//     return false
// })

describe('Buy Digital Product Free via Bank Transfer', () => {

    // Opening StoreFront
    beforeEach(function () {
        cy.visit( 'http://testwp.local/');

        // Cypress.Cookies.preserveOnce('lokuswp_cart', 'remember_token')
        // Cypress.Cookies.defaults({
        //     preserve: 'lokuswp_cart',
        // })
    });

    it('add free product to cart', function () {
        cy.get('button.lwpc-addtocart').eq(1).click();

        // Assert Quantity 1
        // cy.get('.cart-qty').contains('1');
    });

    it('manage product in cart', function () {
        cy.visit(baseUrl + '/cart');

        // Add New Item in Cart
        cy.wait(500)
        cy.get('tr:first() .plus').click()
    });

    it('start checkout', function () {
        cy.visit(baseUrl + '/checkout');
        // cy.getCookies().should('be.empty')
        // cy.setCookie('session_id', '189jd09sufh33aaiidhf99d09')
        // cy.getCookie('session_id').should(
        //     'have.property',
        //     'value',
        //     '189jd09sufh33aaiidhf99d09'
        // )
        //
        // Fill Form
        cy.get(':nth-child(1) > .form-control').type("Hafid")
        cy.get(':nth-child(2) > .form-control').type("085216746174")
        cy.get(':nth-child(3) > .form-control').type("hafid@lokuswp.com")

        // cy.get('[data-cy="verify-form"]').click()
        //
        // // Choose Shipping Template
        // cy.get('[data-cy="verify-shipping"]').click()

        // Click Payment Tab
        cy.get('.swiper-tabs-nav > .swiper-wrapper > .swiper-slide').contains("Payment").click()

        // Click Payment Transfer Bank
        cy.get('.form-group:first() .item-radio > label').click()

        // Make Transaction
        // cy.get('[data-cy="make-a-checkout"]').click();

        // Checking Confirmation Page
        // Terimakasih, Pesanan Selesai
        // Detail Pesanan
        // Tombol Download File
        // cy.get('#instruction').should('contain', 'Please make payment').end();
    });

    it('instruction', function () {

    });

    it('download file', function () {

    });

    it('check order history', function () {

    });


    it('admin check shipping email was sending', function () {

    });

})

