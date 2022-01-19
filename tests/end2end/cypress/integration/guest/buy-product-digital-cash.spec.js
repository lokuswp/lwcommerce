const baseUrl = Cypress.env("baseUrl");

beforeEach(function () {
    cy.visit(baseUrl + '/etalase');
    cy.wait(1000);

});

it('add digital product to cart', function () {
    cy.get('button.lwpc-addtocart').click({multiple:true});

    // Assert Text
    cy.get('.cart-icon-wrapper small').contains('2');
    cy.visit(baseUrl + '/cart');
});



// it('make a transaction', function () {
//     cy.visit(baseUrl + '/wp-admin/plugins.php');
//     cy.get('a#lokuswp-backbone').click();

//     // Assert Text
//     cy.get('p').contains('There is a new version of LokusWP Backbone available.');
// });

// it('receipt', function () {
//     cy.visit(baseUrl + '/wp-admin/plugins.php');
//     cy.get('a#lokuswp-backbone').click();

//     // Assert Text
//     cy.get('p').contains('There is a new version of LokusWP Backbone available.');
// });