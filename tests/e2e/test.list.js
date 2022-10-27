const {test} = require('@playwright/test');

// PHP 7.3
// test.describe(require('./wordpress7.3/setup/setup-wordpress73.spec.js'));
// test.describe(require('./wordpress7.3/setup/check-plugin.spec.js'));
//
// // PHP 7.4
// test.describe(require('./wordpress7.4/setup/setup-wordpress74.spec.js'));
// test.describe(require('./wordpress7.4/setup/check-plugin.spec.js'));
// test.describe(require('./wordpress7.4/admin/fresh-install-plugin.spec.js'));
// test.describe(require('./wordpress7.4/admin/product.spec.js'));
// test.describe(require('./wordpress7.4/admin/admin-setting.spec.js'));


// Test Suite
test.describe(require('./regression/guest/checkout--food--takeaway-bank-transfer.spec.js'));
// test.describe(require('./regression/guest/checkout-food-delivery.spec.js'));