const {test, expect} = require('@playwright/test');

module.exports = function createTests() {

    test.beforeEach(async ({page}) => {
        // Runs before each test and signs in each page.
        await page.goto('http://localhost:8000/wp-login.php?redirect_to=http%3A%2F%2Flocalhost%3A8000%2Fwp-admin%2F&reauth=1');
        await page.locator('input[name="log"]').click();
        await page.locator('input[name="log"]').fill('test');
        await page.locator('input[name="pwd"]').click();
        await page.locator('input[name="pwd"]').fill('test');
        await page.locator('text=Log In').click();
        await expect(page).toHaveURL('http://localhost:8000/wp-admin/');
    });

    test('Create new product', async ({page}) => {
        await page.locator('#toplevel_page_edit-post_type-product div:has-text("Products")').click();
        await expect(page).toHaveURL('http://localhost:8000/wp-admin/edit.php?post_type=product');
        await page.locator('text=New Product').click();
        await expect(page).toHaveURL('http://localhost:8000/wp-admin/post-new.php?post_type=product');
        await page.locator('input[name="post_title"]').fill('test');
        await page.locator('input[name="_unit_price"]').fill('50000');
        await page.locator('a:has-text("Stock")').click();
        await page.locator('input[name="_stock"]').fill('999');
        await page.locator('a:has-text("Price")').click();
        await page.locator('#product_category-2 >> text=Plugin').click();
        await page.waitForSelector('input[name="publish"]');
        await page.waitForSelector('input.disabled', {
            state: 'detached'
        });
        await page.locator('input[name="publish"]').click();
        await expect(page).toHaveURL(/^(http|https)?(:\/\/)?([\w-]+:+[\d-]+)?(\/)?(wp-admin)?(\/)?(post.php)?(\?post=\d+)&(action)=(edit)$/g);
        await page.locator('text=Post published. View post').click();
    })

    test('Create new product digital product', async ({page}) => {
        await page.locator('#toplevel_page_edit-post_type-product div:has-text("Products")').click();
        await expect(page).toHaveURL('http://localhost:8000/wp-admin/edit.php?post_type=product');
        await page.locator('text=New Product').click();
        await expect(page).toHaveURL('http://localhost:8000/wp-admin/post-new.php?post_type=product');
        await page.locator('input[name="post_title"]').fill('Digital Product');
        await page.locator('input[name="_unit_price"]').fill('75000');
        await page.locator('a:has-text("Stock")').click();
        await page.locator('input[name="_stock"]').fill('999');
        await page.locator('a:has-text("Price")').click();
        await page.locator('#product_category-2 >> text=Plugin').click();
        await page.waitForSelector('input[name="publish"]');
        await page.waitForSelector('input.disabled', {
            state: 'detached'
        });
        await page.locator('input[name="publish"]').click();
        await expect(page).toHaveURL(/^(http|https)?(:\/\/)?([\w-]+:+[\d-]+)?(\/)?(wp-admin)?(\/)?(post.php)?(\?post=\d+)&(action)=(edit)$/g);
        await page.locator('text=Post published. View post').click();
    })

    test('Create new physical product with optional item volume', async ({page}) => {
        await page.locator('#toplevel_page_edit-post_type-product div:has-text("Products")').click();
        await expect(page).toHaveURL('http://localhost:8000/wp-admin/edit.php?post_type=product');
        await page.locator('text=New Product').click();
        await expect(page).toHaveURL('http://localhost:8000/wp-admin/post-new.php?post_type=product');
        await page.locator('input[name="post_title"]').fill('Physical Product');
        await page.locator('input[name="_unit_price"]').fill('25600');

        // Select physical
        await page.locator('select[name="_product_type"]').selectOption('physical');
        await page.locator('a:has-text("Stock")').click();
        await page.locator('input[name="_stock"]').fill('999');
        await page.locator('a:has-text("Price")').click();
        await page.locator('input[name="_weight"]').fill('200');
        await page.waitForSelector('input[name="publish"]');
        await page.waitForSelector('input.disabled', {
            state: 'detached'
        });
        await page.locator('input[name="publish"]').click();
        await expect(page).toHaveURL(/^(http|https)?(:\/\/)?([\w-]+:+[\d-]+)?(\/)?(wp-admin)?(\/)?(post.php)?(\?post=\d+)&(action)=(edit)$/g);
        await page.locator('text=Post published. View post').click();
    })

    test('Create new physical product with item volume', async ({page}) => {
        await page.locator('#toplevel_page_edit-post_type-product div:has-text("Products")').click();
        await expect(page).toHaveURL('http://localhost:8000/wp-admin/edit.php?post_type=product');
        await page.locator('text=New Product').click();
        await expect(page).toHaveURL('http://localhost:8000/wp-admin/post-new.php?post_type=product');
        await page.locator('input[name="post_title"]').fill('Physical Product');
        await page.locator('input[name="_unit_price"]').fill('25600');

        // Select physical
        await page.locator('select[name="_product_type"]').selectOption('physical');
        await page.locator('a:has-text("Stock")').click();
        await page.locator('input[name="_stock"]').fill('999');
        await page.locator('a:has-text("Price")').click();
        await page.locator('input[name="_weight"]').fill('200');
        await page.locator('input[name="_length"]').fill('50');
        await page.locator('input[name="_width"]').fill('28');
        await page.locator('input[name="_height"]').fill('1');
        await page.waitForSelector('input[name="publish"]');
        await page.waitForSelector('input.disabled', {
            state: 'detached'
        });
        await page.locator('input[name="publish"]').click();
        await expect(page).toHaveURL(/^(http|https)?(:\/\/)?([\w-]+:+[\d-]+)?(\/)?(wp-admin)?(\/)?(post.php)?(\?post=\d+)&(action)=(edit)$/g);
        await page.locator('text=Post published. View post').click();
    })

    test("Import product", async ({page}) => {
        await page.locator('#toplevel_page_edit-post_type-product div:has-text("Products")').click();
        await expect(page).toHaveURL('http://localhost:8000/wp-admin/edit.php?post_type=product');
        await page.locator('.import').click();
        await page.locator('#csv-upload');
        await page.setInputFiles('#csv-upload', './tests/e2e/wordpress7.4/admin/assets/template.csv');
        const textUpload = page.locator('.body-dialog-import .input-file span');
        await expect(textUpload).toHaveText(/template.csv/);

        async function waitForMessageAsync() {
            return new Promise(function (resolve) {
                page.on('dialog', async dialog => {
                    if (/Success import 22 product/.test(dialog.message())) {
                        await dialog.dismiss();
                        resolve(true);
                    }
                });
            });
        }

        page.on('dialog', dialog => dialog.accept());
        await Promise.all([
            page.waitForResponse(response => response.url() === 'http://localhost:8000/wp-admin/admin-ajax.php' && response.status() === 200),
            page.click('div[role="dialog"] button:has-text("Import")'),
        ]);
        await expect(await waitForMessageAsync).toBeTruthy();
        await page.waitForNavigation();
        await expect(page).toHaveURL('http://localhost:8000/wp-admin/edit.php?post_type=product');
    });

    test("Import with false file mimetype", async ({page}) => {
        await page.locator('#toplevel_page_edit-post_type-product div:has-text("Products")').click();
        await expect(page).toHaveURL('http://localhost:8000/wp-admin/edit.php?post_type=product');
        await page.locator('.import').click();
        await page.locator('#csv-upload');

        async function waitForMessageAsync() {
            return new Promise(function (resolve) {
                page.on('dialog', async dialog => {
                    if (/File format must be .csv/.test(dialog.message())) {
                        await dialog.dismiss();
                        resolve(true);
                    }
                });
            });
        }

        await page.setInputFiles('#csv-upload', './tests/e2e/wordpress7.4/admin/assets/grabexpress.png');
        await expect(await waitForMessageAsync).toBeTruthy();
        const textUpload = page.locator('.body-dialog-import .input-file span');
        await expect(textUpload).toHaveText(/grabexpress.png/);
    });

    test("Download Template", async ({page}) => {
        await page.locator('#toplevel_page_edit-post_type-product div:has-text("Products")').click();
        await expect(page).toHaveURL('http://localhost:8000/wp-admin/edit.php?post_type=product');
        await page.locator('.import').click();
        await Promise.all([
            page.waitForEvent('popup'),
            page.waitForEvent('download'),
            page.locator('text=Download Template Import').click()
        ]);
    });
}
