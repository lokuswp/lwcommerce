const baseUrl = Cypress.env("baseUrl");

beforeEach(function () {
  cy.visit(baseUrl + '/wp-login.php');
  cy.wait(1000);
  cy.get('#user_login').type(Cypress.env("users").admin.username);
  cy.get('#user_pass').type(Cypress.env("users").admin.pw);
  cy.get('#wp-submit').click();
});

it('check plugin update manually', function () {
  cy.visit(baseUrl + '/wp-admin/plugins.php');
  cy.get('a#lokuswp-backbone').click();

  // Assert Text
  cy.get('p').contains('There is a new version of LokusWP Backbone available.');
});