# Sistem Management Sembako UMKM

Aplikasi manajemen inventory + marketplace sederhana berbasis Laravel untuk operasional UMKM sembako.

## Fitur Utama

- Autentikasi login/register dengan role:
- `admin`
- `staff`
- `customer`
- Manajemen master data (`Produk`, `Kategori`, `Supplier`)
- Perhitungan stok berbasis transaksi (`stock_transactions`)
- Marketplace customer:
- katalog produk
- keranjang + checkout
- halaman pesanan saya
- Marketplace admin/staff:
- panel kelola pesanan
- update status order
- preview tampilan customer
- Statistik & analitik stok
- Laporan stok (CSV/Excel)

## Tech Stack

- Laravel 12
- PHP 8.5
- MySQL
- Blade + Tailwind + Vite

## Setup Lokal

1. Install dependency backend:

```bash
composer install
```

2. Install dependency frontend:

```bash
npm install
```

3. Salin env dan generate app key:

```bash
cp .env.example .env
php artisan key:generate
```

4. Konfigurasi database pada `.env`, lalu migrate:

```bash
php artisan migrate
```

5. Buat symlink storage (wajib untuk gambar produk):

```bash
php artisan storage:link
```

6. Jalankan aplikasi:

```bash
php artisan serve
npm run dev
```

## Catatan

- File artefak export/report dan file sementara tidak disimpan ke repo.
- Pastikan `public/storage` tersedia agar upload gambar bisa tampil.
