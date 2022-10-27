const {test, expect} = require('@playwright/test');

module.exports = function createTests() {




    test.beforeEach(async ({page}) => {
        // Runs before each test and signs in each page.
        await page.goto('products');
        await expect(page).toHaveURL('products/');

    })

    test('test buy, type: food, shipping : takeaway, payment : cash', async ({page}) => {

        await page.waitForSelector('.lwc-listing > .single-content:nth-child(3) > .product-action > .lokus-btn')
        await page.click('.lwc-listing > .single-content:nth-child(3) > .product-action > .lokus-btn')

        // await page.waitForSelector('#lwp-checkout')
        // await page.click('#lwp-checkout')

        await page.getByRole('link', { name: 'Checkout' }).click();
        await expect(page).toHaveURL('checkout/');

        await page.waitForSelector('#name')
        await page.click('#name')
        await page.locator('#name').fill('E2ETESTING');

        await page.waitForSelector('#phone')
        await page.click('#phone')
        await page.locator('#phone').fill('081238642022');

        await page.waitForSelector('#lokuswp-verify-form')
        await page.click('#lokuswp-verify-form')

        // --------------- Shipping --------------- //

        await page.waitForSelector('#shipping-tab')
        await page.click('#shipping-tab')

        await page.waitForSelector('.swiper-no-swiping:nth-child(1) > .lwp-form-group > .item-radio > label > .row')
        await page.click('.swiper-no-swiping:nth-child(1) > .lwp-form-group > .item-radio > label > .row')

        await page.waitForSelector('#pickup-time > .col-xs-4:nth-child(2) > .lwp-form-group > .item-radio > label')
        await page.click('#pickup-time > .col-xs-4:nth-child(2) > .lwp-form-group > .item-radio > label')

        await page.waitForSelector('#lwc-verify-shipping')
        await page.click('#lwc-verify-shipping')

        // --------------- Payment --------------- //

        await page.locator('#transaction-payments-list div:has-text("Cash")').nth(2).click();

        await page.waitForSelector('#lokuswp-checkout-commit')
        await page.click('#lokuswp-checkout-commit')


        await expect(page.url()).toContain("/checkout");

    })

}