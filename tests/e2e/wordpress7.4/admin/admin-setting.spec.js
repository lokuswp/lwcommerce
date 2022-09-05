const {test, expect} = require('@playwright/test');

module.exports = function createTests() {

    test.beforeEach(async ({page}) => {
        // Runs before each test and signs in each page.
        await page.goto('http://localhost:8000/wp-login.php?redirect_to=http%3A%2F%2Flocalhost%3A8000%2Fwp-admin%2F&reauth=1');
        await page.locator('input[name="log"]').fill('test');
        await page.locator('input[name="pwd"]').fill('test');
        await page.locator('text=Log In').click();
        await expect(page).toHaveURL('http://localhost:8000/wp-admin/');
    });

    test('Check settings', async ({page}) => {
        await page.locator('#toplevel_page_lwcommerce div:has-text("LWCommerce")').click();
        await expect(page).toHaveURL('http://localhost:8000/wp-admin/admin.php?page=lwcommerce&tab=settings');
        await expect(page.locator('.tab-primary a')).toHaveText(['Settings', 'Notification', 'Shipping']);
        await expect(page.locator('.tab-primary .active')).toHaveText('Settings');
        await expect(page.locator('.tab-nested .tab-item')).toHaveText(['General', 'Appearance', 'Store']);
        await expect(page.locator('#tab1')).toHaveAttribute('checked', 'checked');
        await expect(page.locator('#tab-body-1 .form-label')).toHaveText(['Whatsapp Checkout Template', 'Whatsapp Follow Up Template']);
        await expect(page.locator('#tab-body-1 .form-group textarea[name="checkout_template"]')).toHaveAttribute('rows', '12');
        await expect(page.locator('#tab-body-1 .form-group textarea[name="followup_template"]')).toHaveAttribute('rows', '12');
        await expect(page.locator('#tab-body-1 .form-group textarea[name="checkout_template"]')).toHaveText('Hi, Saya sudah pesan\n' +
            'ID Pesanan : *#{{order_id}}*\n' +
            '\n' +
            '*Detail Pesanan*\n' +
            '{{summary}}\n' +
            '\n' +
            '*Pembayaran*\n' +
            '{{payment}}\n' +
            '\n' +
            'Tolong segera diproses ya min,\n' +
            '{{order_link}}\n' +
            '\n' +
            'ini bukti pembayarannya');

        await expect(page.locator('#tab-body-1 .form-group textarea[name="followup_template"]')).toHaveText('Hi *{{name}}*\n' +
            '\n' +
            'Kami ingin mengingatkan terkait pesanan Anda\n' +
            'Yang masih belum diselesaikan\n' +
            'ID Pesanan : *#{{order_id}}*\n' +
            '\n' +
            '*Detail Pesanan* :\n' +
            '{{summary}}\n' +
            '\n' +
            '*Pembayaran* :\n' +
            '{{payment}}\n' +
            '\n' +
            '_Jika ada yang ingin ditanyakan,_\n' +
            '_silahkan balas pesan ini_\n' +
            '\n' +
            'Terimakasih\n' +
            '*{{brand_name}}*\n');

        // Click label:has-text("Appearance")
        await page.locator('label:has-text("Appearance")').click();
        await expect(page.locator('input[name="checkout_whatsapp"]')).toHaveAttribute('checked', 'checked');

        // Click text=Store
        await page.locator('text=Store').click();
        await expect(page.locator('input[name="name"]')).toHaveValue('test');
        await expect(page.locator('#tab-body-3 img')).toHaveAttribute('src', /^(http|https)?(:\/\/)?([\w-]+:+[\d-]+)?(\/)?wp-content\/plugins\/lwcommerce\/src\/admin\/assets\/images\/lwcommerce.png$/g);
        await expect(page.locator('input[name="description"]')).toHaveValue('test');
        await expect(page.locator('select[name="category"]')).toHaveValue('digital');
        await expect(page.locator('input[name="email"]')).toHaveValue('test@test.com');
        await expect(page.locator('#tab-body-3 textarea')).toHaveText('test');

        // Click div[role="main"] >> text=Notification
        await page.locator('div[role="main"] >> text=Notification').click();
        await expect(page).toHaveURL('http://localhost:8000/wp-admin/admin.php?page=lwcommerce&tab=notification');
        await page.locator('#notification-email').isChecked();
        await page.locator('text=SMTP Email').click();
        await page.locator('text=WEBHOOK WEBHOOK').click();

        // Click text=Shipping
        await page.locator('text=Shipping').click();
        await expect(page).toHaveURL('http://localhost:8000/wp-admin/admin.php?page=lwcommerce&tab=shipping');
        
    })

}
