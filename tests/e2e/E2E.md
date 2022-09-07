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