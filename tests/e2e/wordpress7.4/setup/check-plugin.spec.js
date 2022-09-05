const {test, expect} = require('@playwright/test');

module.exports = function createTests() {
    test('Check is plugin lwcommerce available 7.4', async ({page}) => {
        await page.goto('http://localhost:8000/wp-login.php?redirect_to=http%3A%2F%2Flocalhost%3A8000%2Fwp-admin%2F&reauth=1');
        await page.locator('input[name="log"]').click();
        await page.locator('input[name="log"]').fill('test');
        await page.locator('input[name="pwd"]').click();
        await page.locator('input[name="pwd"]').fill('test');
        await page.locator('text=Log In').click();
        await expect(page).toHaveURL('http://localhost:8000/wp-admin/');

        // Click #menu-plugins div:has-text("Plugins")
        await page.locator('#menu-plugins div:has-text("Plugins")').click();
        await expect(page).toHaveURL('http://localhost:8000/wp-admin/plugins.php');

        // Is has LWDonation plugin
        await expect(page.locator('.wp-list-table')).toHaveText(/LWCommerce/);

        // Is the plugin version is 0.1.9?
        await expect(page.locator('[data-slug="lwcommerce"]')).toHaveText(/Version 0.1.9/);
    });
}