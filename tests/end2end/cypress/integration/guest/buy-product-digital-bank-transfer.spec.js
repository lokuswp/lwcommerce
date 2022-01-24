const baseUrl = Cypress.env("baseUrl");



describe('Buy Digital Product', () => {

    beforeEach(function () {
        cy.visit(baseUrl + '/etalase');
        cy.wait(1000);
    
    });

    it('add digital product to cart', function () {
        cy.get('button.lwpc-addtocart').click({
            multiple: true
        });

        // Assert Text
        cy.get('.cart-icon-wrapper small').contains('2');
        cy.visit(baseUrl + '/cart');

        // Add New Item in Cart
        cy.get('tr[product-id="5"] > .text-right > .lwp-stepper > .plus').click()

        // Go To Tranasction
        cy.get('#go-to-transaction').click()

        // Fill Form
        cy.get(':nth-child(1) > .form-control').type("Hafid")
        cy.get(':nth-child(2) > .form-control').type("085216746174")
        cy.get(':nth-child(3) > .form-control').type("hafid@lokuswp.com")

        // Click Payment Tab
        cy.get('.swiper-tabs-nav > .swiper-wrapper > .swiper-slide-next').click()

        // Click Payment Transfer Bank
        cy.get(':nth-child(1) > .item-radio > label').click()

        // Make Transaction
        cy.get('#lokuswp-checkout').click();

        // Instruction
      
        cy.get('#instruction').should('contain', 'Bank Transfer').end();
    });



})

