# Courier Management API - Laravel

Project ini adalah sebuah RESTful API untuk mengelola master data Kurir (Couriers) yang dibangun menggunakan Laravel 11. Project ini dirancang tanpa frontend dan diuji sepenuhnya menggunakan API Client (Postman) serta Automated Testing bawaan Laravel.

---

## 🚀 Fitur Utama
* **CRUD Kurir Lengkap**: Mendukung Index, Show, Store, Update, dan Destroy.
* **Fitur Pencarian & Filter Tingkat Lanjut**:
  * Pencarian nama kurir yang cerdas (`?search=budi+agung` cocok dengan "Budiono Hadi Agung").
  * Filter multi-level (`?level=2,3`).
  * Kustomisasi pengurutan data (`?sort=date` untuk mengurutkan berdasarkan tanggal buat, default berdasarkan nama).
  * Pagination default untuk efisiensi performa data.
* **Keamanan & Validasi Ketat**:
  * Validasi NIK wajib 16 digit angka dan unik.
  * Validasi batasan umur kurir (minimal 15 tahun dari tanggal lahir/DOB).
  * Batasan nilai Level hanya diizinkan angka 1 sampai 5.
  * Proteksi penuh dari **SQL Injection** menggunakan *PDO Parameter Binding* bawaan Eloquent ORM.
* **Integrasi Database & UUID**: Menggunakan UUID sebagai Primary Key yang terintegrasi dengan struktur tabel MySQL.

---

## 🛠️ Spesifikasi Tabel (`couriers`)
Struktur data yang digunakan pada database MySQL:
* `uuid` (CHAR 36, Primary Key)
* `name` (TEXT)
* `nik` (VARCHAR 16, Unique)
* `address` (TEXT)
* `phone` (VARCHAR 20)
* `dob` (DATE)
* `status` (VARCHAR 50)
* `level` (INT, Constraint 1-5)
* `created_at` / `updated_at` (TIMESTAMP)
* `created_by` / `updated_by` (CHAR 36)

---

## 💻 Cara Instalasi & Menjalankan Project

1. Clone Repository
```bash
git clone https://github.com/Wirahman/gradin
cd gradin

2. Instalasi Dependensi Composer
composer install

3. Konfigurasi Environment File
Buka file .env dan sesuaikan pengaturan database Anda:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gradin
DB_USERNAME=root
DB_PASSWORD=

4. Generate Application Key & Jalankan Migrasi
php artisan key:generate
php artisan migrate

🧪 Cara Menjalankan Automated Testing
php artisan test

📑 Dokumentasi Endpoint API
Semua request wajib menyertakan header berikut agar respon error berupa JSON:

Header: Accept = application/json

1. Get All Couriers (Index)
Method: GET

URL: /api/couriers

Query Params (Opsional):

?search=budi+agung (Mencari kurir bersesuaian nama)

?level=2,3 (Filter level tertentu)

?sort=date (Mengubah urutan berdasarkan tanggal dibuat)

2. Get Detail Courier (Show)
Method: GET

URL: /api/couriers/{uuid}

3. Create Courier (Store)
Method: POST

URL: /api/couriers

Body (JSON):
{
    "myUUID": "f4e3d2c1-b0a9-9876-5432-10fedcba9876",
    "name": "Budiono Hadi Agung",
    "nik": "1234567890123456",
    "address": "Jl. Merdeka No. 10, Jakarta",
    "phone": "081234567890",
    "dob": "2000-01-01",
    "status": "Active",
    "level": 3
}

4. Update Courier (Update)
Method: PUT

URL: /api/couriers/{uuid}

Body (JSON): Sama seperti struktur Create Courier.

5. Delete Courier (Destroy)
Method: DELETE

URL: /api/couriers/{uuid}