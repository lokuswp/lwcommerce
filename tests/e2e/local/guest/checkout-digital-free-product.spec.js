const {test, expect} = require('@playwright/test');

module.exports = function createTests() {


    test.beforeEach(async ({page}) => {
        // Runs before each test and signs in each page.
        await page.goto('products');
        await expect(page).toHaveURL('products/');
    })

    test('Checkout Free Product', async ({page}) => {
        await page.waitForSelector('.elementor-element > .elementor-widget-container > .filters > .scrollmenu > li:nth-child(4)')
        await page.click('.elementor-element > .elementor-widget-container > .filters > .scrollmenu > li:nth-child(4)')

        await page.waitForSelector('.elementor-widget-container > .lwc-listing > .single-content:nth-child(6) > .product-action > .lokus-btn')
        await page.click('.elementor-widget-container > .lwc-listing > .single-content:nth-child(6) > .product-action > .lokus-btn')

        await page.waitForSelector('.page-content > .lwc-listing > .single-content:nth-child(2) > .product-action > .lokus-btn')
        await page.click('.page-content > .lwc-listing > .single-content:nth-child(2) > .product-action > .lokus-btn')

        await page.goto('checkout');
        await expect(page).toHaveURL('checkout/');

        await page.waitForSelector('#name')
        await page.click('#name')
        await page.locator('#name').fill('lokuse2e');

        await page.waitForSelector('#phone')
        await page.click('#phone')
        await page.locator('#phone').fill('085616550281');

        // await page.waitForSelector('#email')
        // await page.click('#email')
        // await page.locator('#email').fill('test@lokuswp.id');

        await page.waitForSelector('#lokuswp-verify-form')
        await page.click('#lokuswp-verify-form')

        await page.waitForSelector('#shipping-tab')
        await page.click('#shipping-tab')

        await page.waitForSelector('.swiper-no-swiping:nth-child(1) > .lwp-form-group > .item-radio > label > .row')
        await page.click('.swiper-no-swiping:nth-child(1) > .lwp-form-group > .item-radio > label > .row')

        await page.waitForSelector('#pickup-time > .col-xs-4:nth-child(2) > .lwp-form-group > .item-radio > label')
        await page.click('#pickup-time > .col-xs-4:nth-child(2) > .lwp-form-group > .item-radio > label')

        await page.waitForSelector('#lwc-verify-shipping')
        await page.click('#lwc-verify-shipping')

        await page.waitForSelector('#transaction-payments-list > .form-group:nth-child(3) > .item-radio-custom > label > .row')
        await page.click('#transaction-payments-list > .form-group:nth-child(3) > .item-radio-custom > label > .row')

        await page.waitForSelector('.top > #transaction-payments-list > .form-group:nth-child(3) > .item-radio-custom > label')
        await page.click('.top > #transaction-payments-list > .form-group:nth-child(3) > .item-radio-custom > label')

        await page.waitForSelector('#lokuswp-checkout-commit')
        await page.click('#lokuswp-checkout-commit')

    })

}