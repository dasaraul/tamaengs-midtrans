# Midtrans Shop Template 🛍️

Template webstore keren berbasis Laravel lengkap dengan integrasi payment gateway Midtrans, fitur CRUD, dan admin panel yang siap pakai.

![Midtrans Shop Banner](https://via.placeholder.com/800x400)

> ⚠️ **PERHATIAN**: Ini hanya template! Silakan modifikasi sesuai kebutuhan proyek Anda.

## ✨ Fitur Utama

- 💳 Integrasi payment gateway Midtrans yang udah siap pakai
- 🛒 Sistem CRUD produk yang simpel dan fungsional 
- 👑 Admin panel dengan UI yang clean dan user-friendly
- 📱 Responsive design untuk semua ukuran layar
- 🔒 Sistem otentikasi yang aman untuk user dan admin

## 🔧 Persyaratan Sistem

- PHP 8.0 atau lebih tinggi
- Composer
- MySQL 5.7 atau lebih tinggi
- Node.js & NPM
- Laravel 8.x
- Akun Midtrans (untuk kunci API)

## 🚀 Langkah-langkah Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/username/midtrans-shop.git
cd midtrans-shop
```

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Setup Environment

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Konfigurasi Database

Edit file `.env` dan sesuaikan dengan database kamu:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=jawa_midtrans
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Konfigurasi Midtrans

Tambahkan konfigurasi Midtrans di file `.env`:

```
MIDTRANS_SERVER_KEY=SB-Mid-server-XXXXXXXXXXXXXXXX
MIDTRANS_CLIENT_KEY=SB-Mid-client-XXXXXXXXXXXXXXXX
MIDTRANS_MERCHANT_ID=GXXXXXXX
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true
```

> ℹ️ **Tip**: Gunakan kunci Sandbox (awalan SB-) untuk testing. Ubah ke kunci Production saat website siap diluncurkan dengan mengatur `MIDTRANS_IS_PRODUCTION=true`.

### 6. Migrasi dan Seeding Database

```bash
php artisan migrate
php artisan db:seed
```

### 7. Setup Storage Link

```bash
php artisan storage:link
```

### 8. Compile Assets

```bash
npm run dev
# atau untuk production
npm run prod
```

### 9. Buat User Admin

```bash
php artisan make:admin admin@example.com password123
```

### 10. Menjalankan Aplikasi

```bash
php artisan serve
```

Aplikasi bisa diakses pada http://localhost:8000 🎉

## 🏗️ Struktur Aplikasi

- **Customer Interface**: Homepage, katalog produk, detail produk, keranjang, checkout, dan halaman pesanan
- **Admin Panel**: Dashboard, manajemen produk, pesanan, dan pengguna
- **Payment Gateway**: Integrasi Midtrans untuk pemrosesan pembayaran

## 📝 Cara Penggunaan

### Panel Admin

1. Login dengan email admin yang udah dibuat
2. Akses menu Admin Dashboard dari dropdown di navbar
3. Kelola produk: tambah, edit, hapus produk, dan upload gambar
4. Pantau pesanan dan status pembayaran dengan mudah

### Pelanggan

1. Browse katalog produk yang kece
2. Tambahkan produk ke keranjang belanja
3. Proses checkout dengan mengisi informasi pengiriman
4. Lakukan pembayaran melalui Midtrans (multiple payment methods)
5. Lacak status pesanan di halaman pesanan

## ⚙️ Konfigurasi Callback Midtrans

Untuk menerima notifikasi pembayaran dari Midtrans, konfigurasikan URL callback di dashboard Midtrans:

1. Login ke [Dashboard Midtrans](https://dashboard.midtrans.com)
2. Pilih project kamu
3. Buka **Settings** > **Configuration**
4. Isi **Payment Notification URL** dengan `https://your-domain.com/payment/notification`
5. Simpan pengaturan

## 🔍 Troubleshooting

### Gambar Tidak Muncul
- Pastikan sudah menjalankan `php artisan storage:link`
- Periksa permission folder storage: `chmod -R 775 storage bootstrap/cache`

### Error Payment Gateway
- Verifikasi konfigurasi Midtrans di `.env`
- Pastikan callback URL sudah dikonfigurasi dengan benar

### Sidebar Admin Tidak Muncul
- Jalankan `php artisan optimize:clear` untuk membersihkan cache
- Periksa error JavaScript di console browser

## 📦 Petunjuk Deployment

### Shared Hosting
1. Upload semua file ke server
2. Setup environment dari file `.env`
3. Konfigurasi web server untuk mengarah ke folder `public/`
4. Jalankan migrasi dan setup aplikasi

### VPS/Dedicated Server
1. Clone repository ke server
2. Ikuti langkah instalasi seperti di atas
3. Konfigurasi Nginx/Apache untuk aplikasi Laravel

## 👏 Credits
Template ini menggunakan beberapa package open source:
- Laravel Framework
- Bootstrap 5
- jQuery
- Midtrans PHP Library

## 👨‍💻 Meet The Creator

<div align="center">

### Tama
*Frontend Enthusiast & Payment Integration Specialist*

[![Instagram](https://img.shields.io/badge/Instagram-%40tam.aspx-E4405F?style=for-the-badge&logo=instagram&logoColor=white)](https://instagram.com/tam.aspx)
[![WhatsApp](https://img.shields.io/badge/WhatsApp-0851--8455--0704-25D366?style=for-the-badge&logo=whatsapp&logoColor=white)](https://wa.me/6285184550704)

</div>

> **Anyway, so basically** template ini tuh didesain sebagai starter kit yang literally bisa langsung lo customize sesuai kebutuhan bisnis. Untuk pengalaman yang lebih seamless, jangan lupa adjust sesuai brand identity lo ya!

---

<div align="center">
<img src="https://img.shields.io/badge/MADE%20WITH%20%E2%9D%A4%EF%B8%8F%20IN-JAKARTA-blue?style=for-the-badge" alt="Made with love in Jakarta" />
</div>

---

Happy coding! 🚀