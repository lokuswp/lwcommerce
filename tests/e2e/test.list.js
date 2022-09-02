const {test} = require('@playwright/test');

test.describe(require('./setup/setup-wordpress.spec.js'));
test.describe(require('./setup/check-plugin.spec.js'));
test.describe(require('./admin/fresh-install-plugin.spec.js'));
