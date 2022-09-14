// const {test, expect} = require('@playwright/test');
//
// module.exports = function createTests() {
//
//     test.beforeEach(async ({page}) => {
//         // Runs before each test and signs in each page.
//         await page.goto('http://localhost:8001/wp-login.php?redirect_to=http%3A%2F%2Flocalhost%3A8001%2Fwp-admin%2F&reauth=1');
//         await page.locator('input[name="log"]').click();
//         await page.locator('input[name="log"]').fill('test');
//         await page.locator('input[name="pwd"]').click();
//         await page.locator('input[name="pwd"]').fill('test');
//         await page.locator('text=Log In').click();
//         await expect(page).toHaveURL('http://localhost:8001/wp-admin/');
//     });
//
//     test('Check is plugin lwcommerce available 7.3', async ({page}) => {
//         // Click #menu-plugins div:has-text("Plugins")
//         await page.locator('#menu-plugins div:has-text("Plugins")').click();
//         await expect(page).toHaveURL('http://localhost:8001/wp-admin/plugins.php');
//
//         // Is has LWDonation plugin
//         await expect(page.locator('.wp-list-table')).toHaveText(/LWCommerce/);
//
//         // Is the plugin version is 0.1.9?
//         await expect(page.locator('[data-slug="lwcommerce"]')).toHaveText(/Version 0.1.9/);
//     });
//
//     test('Plugin must not working on php 7.3', async ({page}) => {
//         await page.locator('#menu-plugins div:has-text("Plugins")').click();
//         await expect(page).toHaveURL('http://localhost:8001/wp-admin/plugins.php');
//         await page.waitForTimeout(1500);
//         test.skip(await page.locator('#activate-lwcommerce').isVisible() === false, "Plugin already activate");
//         await page.locator('#activate-lwcommerce').click();
//         await expect(page).toHaveURL('http://localhost:8001/wp-admin/plugins.php?plugin_status=all&paged=1&s');
//         await page.waitForTimeout(1500);
//         await expect(page.locator('.error p')).toHaveText(/This plugin run but not working. LWCommerce required version of PHP 7.4/);
//     })
// }