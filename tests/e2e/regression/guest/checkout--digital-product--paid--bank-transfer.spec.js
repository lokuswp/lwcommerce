const {test, expect} = require('@playwright/test');

module.exports = function createTests() {


    test.beforeEach(async ({page}) => {
        // Runs before each test and signs in each page.
        await page.goto('marketplace');
        await expect(page).toHaveURL('marketplace/');
    })

    test('Test Checkout Premium Product', async ({page}) => {

        // Select Product Filter
        await page.locator('text=Pembayaran').click();

        // Click text=Diskon 50% LokusWP Tripay Rp 199.000Rp 399.000 Tambah >> button >> nth=0
        let product = page.locator('div.lwc-product-item', {has: page.locator('text=LokusWP Tripay')})
        product.locator(".lwc-add-to-cart").first().click();

        let cartIcon = page.locator('lwp-cart-icon span.cart-qty');
        // await expect(cartIcon).toHaveText('1');

        // // Go to https://sandbox.lokuswp.id/checkout/
        // await page.goto('https://sandbox.lokuswp.id/checkout/');
        //
        // await page.waitForSelector('#name')
        // await page.click('#name')

        // await page.waitForSelector('#phone')
        // await page.click('#phone')
        // await page.locator('#phone').fill('085616550281');
        //
        // await page.waitForSelector('#email')
        // await page.click('#email')
        // await page.locator('#email').fill('test@lokuswp.id');
        //
        // await page.waitForSelector('#lokuswp-verify-form')
        // await page.click('#lokuswp-verify-form')
        //
        // await page.waitForSelector('#shipping-tab')
        // await page.click('#shipping-tab')
        //
        // await page.waitForSelector('.swiper-no-swiping:nth-child(1) > .lwp-form-group > .item-radio > label > .row')
        // await page.click('.swiper-no-swiping:nth-child(1) > .lwp-form-group > .item-radio > label > .row')
        //
        // await page.waitForSelector('#pickup-time > .col-xs-4:nth-child(2) > .lwp-form-group > .item-radio > label')
        // await page.click('#pickup-time > .col-xs-4:nth-child(2) > .lwp-form-group > .item-radio > label')
        //
        // await page.waitForSelector('#lwc-verify-shipping')
        // await page.click('#lwc-verify-shipping')
        //
        // await page.waitForSelector('#transaction-payments-list > .form-group:nth-child(3) > .item-radio-custom > label > .row')
        // await page.click('#transaction-payments-list > .form-group:nth-child(3) > .item-radio-custom > label > .row')
        //
        // await page.waitForSelector('.top > #transaction-payments-list > .form-group:nth-child(3) > .item-radio-custom > label')
        // await page.click('.top > #transaction-payments-list > .form-group:nth-child(3) > .item-radio-custom > label')
        //
        // await page.waitForSelector('#lokuswp-checkout-commit')
        // await page.click('#lokuswp-checkout-commit')

    })

}