
const baseUrl = 'http://localhost:10039'

Cypress.on('uncaught:exception', (err, runnable) => {
    // returning false here prevents Cypress from
    // failing the test
    cy.clearCookies()
    return false
})


describe('Order Confirm, Ship Digital Product', () => {

    // Opening StoreFront
    beforeEach(() => {
        cy.viewport('macbook-13')
    })

    it('Admin/System - confirm order', function () {
        // Kunjungi Halaman Daftar Product
        // cy.visit(baseUrl + '/products');

        // Pilih Produk Pertama ( Gratis )
        // cy.get('button.lwc-addtocart').eq(0).click();

        // Cek :: Keranjang punya 1 produk
        // cy.get('.cart-qty').contains('1');
    });

    it('AdminCheck - system was sending notification email : Shipping', function () {

    });

    it('AdminCheck - system was sending notification email : Completed', function () {

    });

})

