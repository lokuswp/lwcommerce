# End to end test menggunakan playwright

Untuk tau lebih lanjut tentang apa itu playwright kunjungi situs https://playwright.dev/docs/intro

**Untuk menjalankan test ini membutuhkan docker. Jadi pastikan docker sudah terinstall.**

## Langkah-langkah untuk yang baru pertama kali menjalankan playwright

1. Jalankan npm install terlebih dahulu pada root folder lwcommerce:

```shell script
npm install
```

2. Lalu jalankan perintah npx playwright install untuk menginstall playwright browser pada root folder lwcommerce:

```shell script
npx playwright install
```

3. Kemudian langkah terakhir jalankan test pada root folder lwcommerce:

```shell script
npm run test:e2e
```

**Langkah 1 dan 2 bisa kalian skip jika kalian sudah menjalankannya sekali.**

## Cara membuat test

Kalian bisa menulis test pada direktori `tests/e2e/wordpress{versi php}`
Setiap test yang ditulis di sana harus di include pada file `tests/e2e/test.list.js`
ini agar test bisa berjalan berurutan.

Sebenarnya bisa menjalankan test dengan paralel. Cuman di wordpress ada step dimana saat baru pertama kali menjalankan
wordpress harus memasuki tahap **installasi**.
dan saat pertama kali mengaktifkan plugin **lwcommerce** harus melalui tahap **onboarding**

Jika ada cara lain yang bisa dilakukan untuk tetap paralel tapi harus melalui 2 tahap itu terlebih dahulu,
kami sangat menerima pull request dari kalian (●'◡'●).

### Langkah-langkah membuat test

Ada 2 cara untuk membuat test pada playwright

1. dengan cara manual menulis test seperti biasa
2. cara otomatis dengan:

```shell script
npx playwright codegen {url website anda}
```

```shell script
npx playwright test --project=lasida --headed
```


untuk lebih lanjut apa itu code generation dengan playwright. Kalian bisa
mengunjungi https://playwright.dev/docs/codegen-intro