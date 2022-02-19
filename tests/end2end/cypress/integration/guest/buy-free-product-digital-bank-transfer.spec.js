const baseUrl = Cypress.env("baseUrl");


Cypress.on('uncaught:exception', (err, runnable) => {
    // returning false here prevents Cypress from
    // failing the test
    return false
  })

describe('Buy Digital Product Free via Bank Transfer', () => {

    beforeEach(function () {
        cy.visit(baseUrl + '/etalase');
        cy.wait(1000);
    
    });

    it('add digital product to cart', function () {
        cy.get('button.lwpc-addtocart').eq(1).click();

        // Assert Text
        cy.get('.cart-qty').contains('1');
        cy.visit(baseUrl + '/cart');

        // Add New Item in Cart
        cy.wait(500)
        cy.get('tr:first() .plus').click()

        // Go To Tranasction
        cy.get('#goto-cashier').click()
    
        // cy.get(':nth-child(1) > .form-control').type("Hafid")
        // cy.get(':nth-child(2) > .form-control').type("085216746174")
        // cy.get(':nth-child(3) > .form-control').type("hafid@lokuswp.com")

        // Click Payment Tab
        cy.get('.swiper-tabs-nav > .swiper-wrapper > .swiper-slide').contains("Payment").click()


        // Click Payment Transfer Bank
        cy.get('.form-group:first() .item-radio > label').click()

        // Make Transaction
        cy.get('#lokuswp-checkout').click();

        // Assert 
        // Bank Transfer Instruction
        cy.get('#instruction').should('contain', 'Please make payment').end();
    });



})

