# Sistem Management Sembako UMKM

Sistem inventory + marketplace untuk UMKM sembako.

## Fitur

- Role: `admin`, `staff`, `customer`
- Master data: produk, kategori, supplier
- Stok berbasis transaksi (`in/out`)
- Marketplace customer: katalog, keranjang, checkout, pesanan saya
- Marketplace admin/staff: kelola pesanan, update status, preview mode customer
- Statistik, analitik, laporan stok (CSV/Excel)

## Stack

- Laravel 12
- PHP 8.5
- MySQL
- Blade, Tailwind, Vite

## Quick Start

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan storage:link
php artisan serve
npm run dev
```

## Environment Minimum

```env
APP_NAME="UMKM Sembako"
APP_VERSION=0.4.0
DB_CONNECTION=mysql
```

## Struktur Screenshot

Simpan screenshot UI di folder berikut:

- `docs/screenshots/analytics.png`
- `docs/screenshots/products.png`
- `docs/screenshots/home.png`

## Catatan

- `public/storage` wajib ada agar gambar produk tampil.
- Artefak sementara tidak disimpan ke repo.
