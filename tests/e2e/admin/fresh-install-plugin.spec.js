const {test, expect} = require('@playwright/test');

module.exports = function createTests() {

    test('Check fresh install for plugin going to onboarding', async ({page}) => {
        await page.goto('http://localhost:8000/wp-login.php?redirect_to=http%3A%2F%2Flocalhost%3A8000%2Fwp-admin%2F&reauth=1');
        await page.locator('input[name="log"]').click();
        await page.locator('input[name="log"]').fill('test');
        await page.locator('input[name="pwd"]').click();
        await page.locator('input[name="pwd"]').fill('test');
        await page.locator('text=Log In').click();
        await expect(page).toHaveURL('http://localhost:8000/wp-admin/');

        await page.locator('#menu-plugins div:has-text("Plugins")').click();
        await expect(page).toHaveURL('http://localhost:8000/wp-admin/plugins.php');
        const btnDeactivate = page.waitForSelector('#deactivate-lwcommerce');
        test.skip(await (await btnDeactivate).isVisible(), "Plugin already activate");
        await page.locator('#activate-lwcommerce').click();
        await expect(page).toHaveURL('http://localhost:8000/wp-admin/admin.php?page=lwcommerce');

        await page.locator('[placeholder="Lokus Store"]').fill('test');
        await page.locator('[placeholder="Local first online shop"]').fill('test');
        await page.locator('[placeholder="lokuswp\\@gmail\\.com"]').fill('test@test.com');
        await page.locator('textarea[name="address"]').fill('test');
        await page.locator('text=Save').click();
        const continueButton = await page.waitForSelector('text=Continue', {
            state: 'visible',
            timeout: 0
        });
        await continueButton.click();
        await page.locator('text=Add New Product').click();

        await expect(page).toHaveURL('http://localhost:8000/wp-admin/post-new.php?post_type=product');
        await page.locator('#toplevel_page_lwcommerce div:has-text("LWCommerce")').click();
        await expect(page).toHaveURL('http://localhost:8000/wp-admin/admin.php?page=lwcommerce&tab=settings');
    })

}
