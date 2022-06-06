
const baseUrl = 'http://localhost:10039'

Cypress.on('uncaught:exception', (err, runnable) => {
    cy.clearCookies()
    return false
})

describe('Free Digital Product Checkout via Bank Transfer', () => {

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

    it('add and sub quantity in cart', function () {

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

    it('Checkout', function () {

        // Visit Checkout Page
        cy.visit(baseUrl + '/checkout');

        // Fill Customer Data
        cy.get('#lokuswp-form > .top > .checkout-form > .lwp-form-group > #name').type('Test')
        cy.get('#lokuswp-form > .top > .checkout-form > .lwp-form-group > #phone').type('08561655028')
        cy.get('#lokuswp-form > .top > .checkout-form > .lwp-inline-form > #email').type('test@gmail.com')

        // Continue to Payment
        cy.get('#lokuswp-verify-form').click()

        // Assert :: Shipping Tab Active

        // Continue to Payment
        // cy.get('[data-cy="verify-shipping"]').click()

        // Assert :: Payment Tab Active
        cy.get('#lokuswp-checkout > .transaction-tabs > .swiper-container > #tab-nav > .swiper-slide-next').should('have.class', 'swiper-slide-thumb-active')

        // Choose Bank Transfer
        cy.get(':nth-child(1) > .item-radio > label').click()

        // Create Order
        cy.get('#lokuswp-checkout-commit').click()
    });

    it('Post Checkout', function () {

        // have thankyou screen
        cy.get('.message').should('contain', 'Pesanan anda telah sampai')

        // have status section
        cy.get('#transaction-status > .row > .col-xs-6 > #trx-status-refresh > span').should("contain", "Lunas")

        // have download file section
    });

    it('Email Notification', function () {
        // system was sending notification email to user : completed

        // system was sending notification email to admin : completed
    });

})

