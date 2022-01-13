const baseUrl = Cypress.env("baseUrl");

beforeEach(function () {
  cy.visit(baseUrl + '/etalase');
  cy.wait(1000);
  cy.get('#user_login').type(Cypress.env("users").admin.username);
  cy.get('#user_pass').type(Cypress.env("users").admin.pw);
  cy.get('#wp-submit').click();
});
