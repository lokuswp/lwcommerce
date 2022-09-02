const {test, expect} = require('@playwright/test');

module.exports = function createTests() {
    test('First install wordpress', async ({page, browser}) => {
        await page.goto('http://localhost:8000/wp-admin/install.php');
        const installedElement = page.locator('h1:has-text("Already Installed")');
        test.skip(await installedElement.isVisible(), 'Wordpress Already Setting Up');
        await page.locator('text=WordPress').click();
        await page.locator('text=Continue').click();
        await expect(page).toHaveURL('http://localhost:8000/wp-admin/install.php?step=1');
        await page.locator('input[name="admin_password"]').fill('test');
        await page.locator('input[name="weblog_title"]').click();
        await page.locator('input[name="weblog_title"]').fill('test');
        await page.locator('input[name="user_name"]').click();
        await page.locator('input[name="user_name"]').fill('test');
        await page.locator('input[name="admin_email"]').click();
        await page.locator('input[name="admin_email"]').fill('test@test.com');
        await page.locator('input[name="pw_weak"]').check();
        await page.locator('text=Install WordPress').click();
        await expect(page).toHaveURL('http://localhost:8000/wp-admin/install.php?step=2');
        await page.locator('text=Log In').click();
        await expect(page).toHaveURL('http://localhost:8000/wp-login.php');
        await page.locator('input[name="rememberme"]').check();
        await page.locator('input[name="log"]').click();
        await page.locator('input[name="log"]').fill('test');
        await page.locator('input[name="pwd"]').click();
        await page.locator('input[name="pwd"]').fill('test');
        await page.goto('http://localhost:8000/wp-admin/');
    });
}