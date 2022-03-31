

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


Cypress.Commands.overwrite('visit', (originalFn, ...args) => {
    const [ url, options ] = (() => {
        if (typeof args[0] === 'object') {
            const { url, ...options } = args[0];
            return [ url, options ];
        } else {
            return args;
        }
    })();

    if (options && options.__original) {
        return originalFn(url, options);
    } else {
        escapeCookie("firstInvalidCookie");
        escapeCookie("secondInvalidCookie");

        return cy.visit(url, {
            ...options,
            __original: true
        });
    }
});

function escapeCookie(name) {
    return cy.getCookie(name, { log: false }).then(cookie => {
        cy.clearCookie(name, { log: false });

        if (cookie) {
            cy.setCookie(name, encodeURIComponent(cookie.value), {
                domain: 'my.domain.com',
                log: false
            });
        }
    });
}