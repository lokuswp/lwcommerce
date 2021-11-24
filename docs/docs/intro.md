---
sidebar_position: 1 
slug: /
---

# Introduction

Let's discover **LokaWP BackBone in less than 2 seconds** 🚀🚀.

🦴 LokaWP BackBone Plugin its Core, Transacation, and Integration for any LokaWP Plugin

## Getting Started

Dimulai dari membuat clone dari **LokaWP BackBone Plugin** Repository [Disini](https://github.com/lokawp/lokawp-backbone).

Masuk ke **WordPress** Plugin repository di `wp-content/plugins/` dan
lakukan `git clone https://github.com/lokawp/lokawp-backbone.git`.

Kemudian aktifkan plugin **LokaWP BackBone** yang tersedia di halaman plugin **WordPess**.

## Directory

LokaWP BackBone menempatkan directory berdasarkan **folder structure** yang sudah kami sepakati.

```shell
lokawp-backbone # Root directory dari lokabackbone plugin
├── docs # This documentation site location 🎉
├── src
    ├── admin
    │   └── class-admin.php
    ├── includes
    │   ├── api
    │   │   ├── cart
    │   │   │   ├── rest-api.php 
    │   │   ├── payment
    │   │   │   ├── method #harus dipindah
    │   │   │   │   ├── bank-trasnfer.php 
    │   │   │   │   ├── direct.php 
    │   │   │   ├── rest-api.php 
    │   ├── common
    │   ├── helper
    │   └── plugin.php
    ├── autoload.php
```

## Features

Lokabackbone rest api dibangun dengan mengextends base class `WP_REST_Controller`.

Untuk info lebih lanjut mengenai apa itu `WP_REST_Controller` bisa menuju
ke [SINI](https://developer.wordpress.org/rest-api/extending-the-rest-api/controller-classes/#class-inheritance-wp_rest_controller)
.

Kelas base ini dirancang untuk mewakili pola yang konsisten (**tidak berubah-ubah**) untuk memanipulasi **resource**
dari **WordPress**. Saat berinteraksi dengan endpoint yang mengimplementasikan `WP_REST_Controller`, HTTP client dapat
melakuakn request endpoint yang berperilaku secara konsisten.


