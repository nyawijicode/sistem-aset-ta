# 📦 Sistem Manajemen Aset — Tugas Akhir

> Sistem berbasis web untuk pencatatan dan pengelolaan inventaris aset, dirancang menggunakan **Laravel 12** + **FilamentPHP 3** dengan panel administrasi modern, mendukung pelacakan per nomor seri (serial number), laporan cetak PDF, dan proses stock opname berkala.

---

## 🗂️ Daftar Isi

- [Tech Stack](#-tech-stack)
- [System Requirements](#-system-requirements)
- [Instalasi Lengkap](#️-instalasi-lengkap-step-by-step)
- [Perintah CLI Penting](#-perintah-cli-penting-artisan)
- [Alur Penggunaan Sistem](#-alur-penggunaan-sistem-workflow)
- [Role & Hak Akses](#-role--hak-akses-pengguna)
- [Laporan & Cetak PDF](#-laporan--cetak-pdf)
- [Database — Skema, Relasi, Index](#️-database--skema-relasi-index)
- [Penjelasan Model (Eloquent)](#-penjelasan-model-eloquent)
- [Penjelasan Observer (Business Logic)](#-penjelasan-observer-business-logic)
- [Penjelasan Filament Resources](#-penjelasan-filament-resources)
- [Penjelasan Routes](#-penjelasan-routes)
- [Pertanyaan & Jawaban Sidang TA](#-pertanyaan--jawaban-sidang-ta)

---

## 🚀 Tech Stack

| Layer                   | Teknologi                             | Versi        |
| ----------------------- | ------------------------------------- | ------------ |
| **Backend Framework**   | Laravel                               | 12.x         |
| **Admin Panel / UI**    | FilamentPHP (TALL Stack)              | 3.3          |
| **Bahasa Pemrograman**  | PHP                                   | ^8.2         |
| **Frontend Styling**    | Tailwind CSS (bawaan Filament)        | via Filament |
| **Reaktivitas UI**      | Livewire (bawaan Filament)            | via Filament |
| **JavaScript**          | Alpine.js (bawaan Filament)           | via Filament |
| **Database ORM**        | Eloquent (Laravel)                    | via Laravel  |
| **Database Server**     | MySQL / MariaDB / PostgreSQL / SQLite | Bebas        |
| **PDF Generator**       | barryvdh/laravel-dompdf               | ^3.1         |
| **Package Manager PHP** | Composer                              | v2.x         |
| **Package Manager JS**  | NPM                                   | —            |
| **Development Server**  | php artisan serve / XAMPP / Laragon   | —            |

### Kenapa FilamentPHP?

FilamentPHP adalah framework admin panel berbasis TALL Stack (Tailwind CSS + Alpine.js + Laravel + Livewire). Manfaat utamanya:

- **Rapid Development**: Form, tabel, dan filter bisa dibangun dengan deklarasi kode tanpa membuat HTML manual.
- **Reactive tanpa JavaScript manual**: Komponen Livewire memungkinkan form yang bereaksi real-time, misalnya stok otomatis diisi saat memilih aset di Stock Opname.
- **Role-based UI built-in**: Visibilitas menu, aksi, dan tombol bisa dikontrol berbasis logic PHP.

---

## 💻 System Requirements

Sebelum instalasi, pastikan environment memiliki:

| Requirement | Versi Minimal                                          |
| ----------- | ------------------------------------------------------ |
| PHP         | 8.2                                                    |
| Composer    | 2.x                                                    |
| Node.js     | 14+                                                    |
| NPM         | 6+                                                     |
| Database    | MySQL 5.7+ / MariaDB 10.3+ / PostgreSQL 13+ / SQLite 3 |

**Ekstensi PHP yang wajib aktif:**

- `pdo_mysql` (atau driver database yang digunakan)
- `openssl`
- `mbstring`
- `tokenizer`
- `xml`
- `ctype`
- `json`
- `bcmath`
- `fileinfo`
- `gd` / `imagick` (untuk PDF generation)

> **Jika menggunakan XAMPP (Windows):** Pastikan Apache + MySQL aktif, dan PHP di `php.ini` sudah mengaktifkan ekstensi di atas.

---

## 🛠️ Instalasi Lengkap Step-by-Step

### Step 1 — Clone Repository

```bash
git clone https://github.com/username/sistem-aset-ta.git
cd sistem-aset-ta
```

### Step 2 — Install Dependensi PHP

```bash
composer install
```

> Perintah ini mengunduh semua package Laravel, Filament, DomPDF, dan dependensinya ke folder `vendor/`.

### Step 3 — Install Dependensi JavaScript

```bash
npm install
```

> Mengunduh Tailwind CSS dan package frontend yang dibutuhkan Filament.

### Step 4 — Salin File Konfigurasi `.env`

**Linux / Mac:**

```bash
cp .env.example .env
```

**Windows (CMD):**

```cmd
copy .env.example .env
```

**Windows (PowerShell):**

```powershell
Copy-Item .env.example .env
```

### Step 5 — Konfigurasi Database di `.env`

Buka file `.env`, cari bagian database dan isi sesuai kondisi lokal:

```env
# Untuk MySQL / MariaDB (XAMPP)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sistem_aset_ta
DB_USERNAME=root
DB_PASSWORD=

# Untuk PostgreSQL
# DB_CONNECTION=pgsql
# DB_HOST=127.0.0.1
# DB_PORT=5432
# DB_DATABASE=sistem_aset_ta
# DB_USERNAME=postgres
# DB_PASSWORD=secret

# Untuk SQLite (paling mudah, file-based)
# DB_CONNECTION=sqlite
# DB_DATABASE=/absolute/path/to/database.sqlite
```

> Jika menggunakan MySQL, **buat database** terlebih dahulu:
>
> ```sql
> CREATE DATABASE sistem_aset_ta CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
> ```

### Step 6 — Generate Application Key

```bash
php artisan key:generate
```

> Key ini diisi otomatis ke `APP_KEY` di `.env`. Berfungsi sebagai basis enkripsi session, cookie, dan data sensitif.

### Step 7 — Jalankan Migrasi Database

```bash
php artisan migrate
```

> Perintah ini membuat semua tabel di database sesuai file-file di `database/migrations/`. Urutan eksekusi otomatis diatur oleh timestamp nama file.

Jika ingin **reset dan jalankan ulang** dari awal:

```bash
php artisan migrate:fresh
```

### Step 8 — Build Aset Frontend

```bash
npm run build
```

> Meng-compile Tailwind CSS dan asset lainnya ke folder `public/build/`. Wajib dijalankan sekali sebelum deploy atau pertama kali setup.

Untuk **development dengan hot-reload**:

```bash
npm run dev
```

### Step 9 — Buat User Admin Pertama

FilamentPHP menggunakan model `User` dengan interface `FilamentUser`. Buat user pertama lewat CLI:

```bash
php artisan make:filament-user
```

Sistem akan meminta input interaktif:

```
Name: Admin Utama
Email: admin@sistem.com
Password: ********
```

User ini akan langsung bisa login ke panel admin.

Atau, buat user langsung dengan **Tinker** (untuk mengatur role juga):

```bash
php artisan tinker
```

```php
\App\Models\User::create([
    'name' => 'Pimpinan',
    'email' => 'pimpinan@sistem.com',
    'password' => bcrypt('password123'),
    'role' => 'pimpinan',
]);
```

### Step 10 — Jalankan Development Server

```bash
php artisan serve
```

Aplikasi berjalan di: **http://127.0.0.1:8000**

Akses Panel Admin di: **http://127.0.0.1:8000/admin**

> Login menggunakan email dan password yang dibuat di Step 9.

---

## 🔧 Perintah CLI Penting (Artisan)

```bash
# Jalankan server lokal
php artisan serve

# Jalankan semua migrasi
php artisan migrate

# Reset database dan jalankan ulang semua migrasi
php artisan migrate:fresh

# Reset + jalankan Seeder
php artisan migrate:fresh --seed

# Rollback migrasi terakhir
php artisan migrate:rollback

# Buat user admin Filament
php artisan make:filament-user

# Clear semua cache (konfigurasi, route, view)
php artisan optimize:clear

# Cache konfigurasi (untuk production)
php artisan optimize

# Masuk ke shell interaktif (REPL)
php artisan tinker

# Lihat semua route yang terdaftar
php artisan route:list

# Lihat semua event/listener yang terdaftar
php artisan event:list

# Upgrade package Filament (setelah update composer)
php artisan filament:upgrade

# Jalankan test otomatis
php artisan test
```

---

## 🔄 Alur Penggunaan Sistem (Workflow)

### 1. Login ke Panel Admin

Buka browser, akses `/admin`, masukkan email dan password.

- Role `pimpinan`: akses penuh termasuk menu **Kelola User** dan tombol **Hapus**.
- Role `admin`: akses ke semua modul operasional (Daftar Aset, Aset Masuk, Aset Keluar, Stock Opname) namun **tidak bisa menghapus** data dan **tidak melihat menu Kelola User**.

### 2. Tambah Master Data Aset (Modul: Daftar Aset)

Ini adalah **langkah pertama** sebelum bisa mencatat transaksi. Aset adalah katalog barang yang dikelola.

- Klik **"Daftar Aset"** di sidebar.
- Klik **"Tambah Daftar Aset"**.
- Isi: Kode Aset (unique), Nama, Kategori, Satuan.
- Aktifkan toggle **"Aset ini memiliki Serial Number?"** jika setiap unit barang ini perlu dilacak nomor serinya (misal: laptop, printer). Jika hanya barang habis pakai biasa (misal: kertas, tinta), biarkan non-aktif.
- Kolom **Stok** sengaja dikunci (read-only) — stok hanya berubah lewat transaksi masuk/keluar.

> **Catatan penting:** Toggle `has_serial_number` akan **dikunci** setelah aset disimpan pertama kali dan tidak bisa diubah lagi. Ini untuk menjaga konsistensi data historis.

### 3. Catat Barang Masuk (Modul: Aset Masuk)

Ketika ada penerimaan/pembelian aset baru.

- Klik **"Aset Masuk"** di sidebar → **"Tambah Aset Masuk"**.
- Pilih Aset (bisa dicari berdasarkan kode atau nama). Bisa juga **tambah aset baru** langsung dari form ini.
- Isi Tanggal, Supplier, dan Jumlah (Qty).
- **Jika aset ber-Serial Number:** Field qty dikunci, sebagai gantinya muncul form repeater **"Serial Number (SN)"**. User harus menginput SN satu per satu. Jumlah SN yang diisi = jumlah qty otomatis.
- Simpan → **stok aset otomatis bertambah** (dihandle oleh `AssetInObserver`).

### 4. Catat Barang Keluar (Modul: Aset Keluar)

Ketika aset didistribusikan, dipinjam, atau digunakan.

- Klik **"Aset Keluar"** di sidebar → **"Tambah Aset Keluar"**.
- Pilih Aset. Label dropdown menampilkan stok saat ini: `"AST-001 — Laptop Dell (stok: 5)"`.
- Isi Tanggal, Penerima/Tujuan, dan Jumlah.
- **Jika aset ber-Serial Number:** Muncul dropdown multi-pilih **"Pilih Serial Number yang keluar"**. Hanya SN dengan status `in` (tersedia di gudang) yang tampil. Qty dikunci mengikuti jumlah SN yang dipilih.
- Simpan → **stok aset otomatis berkurang** dan status SN berubah ke `out`.

### 5. Stock Opname (Modul: Stock Opname)

Rekonsiliasi fisik gudang dengan data sistem secara berkala.

- Klik **"Stock Opname"** → **"Tambah Stock Opname"**.
- Isi Tanggal dan Keterangan (misal: "Opname Akhir Kuartal Q3 2026").
- Klik **"Tambah Aset"** (form repeater) untuk memasukkan baris per aset:
    - Pilih Aset → kolom **"Stok Sistem"** otomatis terisi dari data database saat ini (snapshot real-time).
    - Isi **"Stok Fisik"** berdasarkan hasil hitung fisik di gudang.
- Simpan. Selisih (Fisik - Sistem) dihitung otomatis dan ditampilkan di halaman Detail.
- Data opname tidak mengubah stok sistem — hanya dicatat sebagai laporan audit.

### 6. Cetak Laporan PDF

Setiap modul memiliki tombol **"Cetak Laporan"** di header tabel. Filter yang aktif di tabel akan otomatis diterapkan ke laporan PDF.

- **Daftar Aset**: Filter berdasarkan Kategori, status Serial Number, kondisi stok (tersedia/habis).
- **Aset Masuk**: Filter berdasarkan rentang tanggal, nama aset, supplier.
- **Aset Keluar**: Filter berdasarkan rentang tanggal, nama aset, penerima.
- **Stock Opname**: Tombol cetak per baris di tabel, menghasilkan laporan opname spesifik.

### 7. Kelola User (Modul: Kelola User — Khusus Pimpinan)

- Hanya terlihat dan bisa diakses oleh user dengan `role = 'pimpinan'`.
- Bisa menambah, edit, dan hapus user.
- Role yang tersedia: `pimpinan` dan `admin`.

---

## 🔐 Role & Hak Akses Pengguna

Sistem menggunakan dua role yang dikontrol melalui kolom `role` di tabel `users`:

| Fitur / Aksi                     | Pimpinan | Admin |
| -------------------------------- | :------: | :---: |
| Lihat Daftar Aset                |    ✅    |  ✅   |
| Tambah / Edit Aset               |    ✅    |  ✅   |
| **Hapus Aset**                   |    ✅    |  ❌   |
| Catat Aset Masuk/Keluar          |    ✅    |  ✅   |
| **Hapus Transaksi Masuk/Keluar** |    ✅    |  ❌   |
| Buat & Edit Stock Opname         |    ✅    |  ✅   |
| **Hapus Stock Opname**           |    ✅    |  ❌   |
| Cetak Laporan PDF                |    ✅    |  ✅   |
| **Kelola User (CRUD)**           |    ✅    |  ❌   |

Implementasi teknis role check ada di method `isPimpinan()` di model `User` dan digunakan di setiap Resource:

```php
// Di UserResource.php — menyembunyikan menu dari sidebar untuk non-pimpinan
public static function shouldRegisterNavigation(): bool
{
    return auth()->user()?->isPimpinan() ?? false;
}
```

---

## 📄 Laporan & Cetak PDF

Laporan diimplementasikan menggunakan **DomPDF** (`barryvdh/laravel-dompdf`) dengan View Blade sebagai template HTML-nya.

### Route Laporan

| URL                          | Nama Route             | Keterangan                      |
| ---------------------------- | ---------------------- | ------------------------------- |
| `/laporan/aset`              | `laporan.asset`        | Laporan daftar semua aset       |
| `/laporan/aset-masuk`        | `laporan.asset-in`     | Laporan transaksi barang masuk  |
| `/laporan/aset-keluar`       | `laporan.asset-out`    | Laporan transaksi barang keluar |
| `/laporan/stock-opname/{id}` | `laporan.stock-opname` | Laporan stock opname per sesi   |

Semua route laporan dilindungi middleware `auth` — hanya user yang sudah login yang bisa mengakses.

---

## 🗄️ Database — Skema, Relasi, Index

### Diagram Relasi (ERD Tekstual)

```
users
  └─ id (PK)

assets
  └─ id (PK)
  └─ code (UNIQUE)

asset_ins
  └─ id (PK)
  ├─ asset_id (FK → assets.id, CASCADE DELETE)
  └─ created_by (FK → users.id)

asset_outs
  └─ id (PK)
  ├─ asset_id (FK → assets.id, CASCADE DELETE)
  └─ created_by (FK → users.id)

asset_serial_numbers
  └─ id (PK)
  ├─ asset_id (FK → assets.id, CASCADE DELETE)
  ├─ asset_in_id (FK → asset_ins.id, SET NULL)
  ├─ asset_out_id (FK → asset_outs.id, SET NULL)
  └─ UNIQUE(asset_id, serial_number)

stock_opnames
  └─ id (PK)
  └─ created_by (FK → users.id, CASCADE DELETE)

stock_opname_items
  └─ id (PK)
  ├─ stock_opname_id (FK → stock_opnames.id, CASCADE DELETE)
  └─ asset_id (FK → assets.id, CASCADE DELETE)
```

---

### Tabel 1: `users`

Menyimpan data seluruh pengguna sistem dan hak aksesnya.

| Kolom               | Tipe Data       | Constraint                      | Keterangan                                                   |
| ------------------- | --------------- | ------------------------------- | ------------------------------------------------------------ |
| `id`                | BIGINT UNSIGNED | **PRIMARY KEY**, AUTO INCREMENT | Identifikasi unik pengguna                                   |
| `name`              | VARCHAR(255)    | NOT NULL                        | Nama lengkap pengguna                                        |
| `email`             | VARCHAR(255)    | NOT NULL, **UNIQUE INDEX**      | Email sebagai username login; unique mencegah duplikasi akun |
| `email_verified_at` | TIMESTAMP       | NULLABLE                        | Waktu verifikasi email (fitur bawaan Laravel)                |
| `password`          | VARCHAR(255)    | NOT NULL                        | Password di-hash menggunakan `bcrypt` (irreversible)         |
| `role`              | VARCHAR(255)    | NOT NULL                        | Nilai: `'pimpinan'` atau `'admin'`                           |
| `remember_token`    | VARCHAR(100)    | NULLABLE                        | Token "ingat saya" untuk sesi yang persisten                 |
| `created_at`        | TIMESTAMP       | NULLABLE                        | Waktu record dibuat (auto by Laravel)                        |
| `updated_at`        | TIMESTAMP       | NULLABLE                        | Waktu record terakhir diperbarui (auto by Laravel)           |

**Index:** `email` memiliki Unique Index — query login (`WHERE email = ?`) sangat cepat dan dijamin tidak ada email ganda.

**Tabel Pendamping Otomatis (dibuat bersamaan):**

- `password_reset_tokens` — menyimpan token reset password
- `sessions` — menyimpan sesi login aktif (indexed di `user_id` dan `last_activity`)

---

### Tabel 2: `assets`

Master data aset / katalog barang yang dikelola organisasi.

| Kolom               | Tipe Data       | Constraint                      | Keterangan                                                             |
| ------------------- | --------------- | ------------------------------- | ---------------------------------------------------------------------- |
| `id`                | BIGINT UNSIGNED | **PRIMARY KEY**, AUTO INCREMENT | Identifikasi unik aset                                                 |
| `code`              | VARCHAR(255)    | NOT NULL, **UNIQUE INDEX**      | Kode / SKU aset; unique memastikan tidak ada dua aset dengan kode sama |
| `name`              | VARCHAR(255)    | NOT NULL                        | Nama deskriptif aset                                                   |
| `category`          | VARCHAR(255)    | NULLABLE                        | Kategori pengelompokan (Elektronik, Furniture, ATK, dll.)              |
| `unit`              | VARCHAR(255)    | NOT NULL, DEFAULT `'unit'`      | Satuan barang (Pcs, Unit, Box, Rim, dll.)                              |
| `has_serial_number` | TINYINT(1)      | NOT NULL, DEFAULT `0`           | Flag apakah aset ini butuh pelacakan SN per unit. `1`=ya, `0`=tidak    |
| `qty`               | INT UNSIGNED    | NOT NULL, DEFAULT `0`           | Stok saat ini. **Tidak diedit manual** — berubah otomatis via Observer |
| `description`       | TEXT            | NULLABLE                        | Deskripsi panjang atau spesifikasi aset                                |
| `created_at`        | TIMESTAMP       | NULLABLE                        | —                                                                      |
| `updated_at`        | TIMESTAMP       | NULLABLE                        | —                                                                      |

**Desain penting — Kolom `qty`:** Kolom ini berfungsi sebagai **derived/computed column** yang di-manage oleh Observers. Nilainya selalu mencerminkan `SUM(asset_ins.qty) - SUM(asset_outs.qty)` secara real-time. Diputuskan untuk disimpan (bukan dihitung tiap query) demi performa.

**Desain penting — `has_serial_number`:** Setelah diset saat aset pertama kali dibuat, flag ini **tidak dapat diubah lagi** (diimplementasikan dengan `->disabled()` pada form edit di FilamentPHP). Hal ini menjaga konsistensi: tidak mungkin tiba-tiba aset yang sudah punya 10 transaksi tanpa SN diubah menjadi ber-SN, atau sebaliknya.

---

### Tabel 3: `asset_ins`

Mencatat setiap transaksi penerimaan / pembelian aset (barang masuk).

| Kolom        | Tipe Data       | Constraint                              | Keterangan                                                                                       |
| ------------ | --------------- | --------------------------------------- | ------------------------------------------------------------------------------------------------ |
| `id`         | BIGINT UNSIGNED | **PRIMARY KEY**, AUTO INCREMENT         | —                                                                                                |
| `asset_id`   | BIGINT UNSIGNED | NOT NULL, **FOREIGN KEY** → `assets.id` | Aset apa yang masuk. `CASCADE DELETE`: jika aset dihapus, seluruh rekaman masuknya ikut terhapus |
| `qty`        | INT UNSIGNED    | NOT NULL                                | Jumlah unit yang masuk dalam transaksi ini                                                       |
| `date`       | DATE            | NOT NULL                                | Tanggal penerimaan fisik barang                                                                  |
| `supplier`   | VARCHAR(255)    | NULLABLE                                | Nama supplier / sumber pengadaan                                                                 |
| `notes`      | TEXT            | NULLABLE                                | Catatan tambahan (nomor PO, nomor surat, dll.)                                                   |
| `created_by` | BIGINT UNSIGNED | NOT NULL, **FOREIGN KEY** → `users.id`  | ID user yang menginput transaksi (audit trail)                                                   |
| `created_at` | TIMESTAMP       | NULLABLE                                | —                                                                                                |
| `updated_at` | TIMESTAMP       | NULLABLE                                | —                                                                                                |

**Foreign Key Detail:**

- `asset_id → assets.id` dengan `ON DELETE CASCADE`: Menghapus aset akan ikut menghapus semua riwayat transaksi masuknya. Ini dipertimbangkan karena tanpa aset master, data transaksi tidak bermakna.
- `created_by → users.id`: Tidak ada cascade delete — user yang sudah menginput tidak bisa dihapus begitu saja tanpa mempertimbangkan referensial integritas.

---

### Tabel 4: `asset_outs`

Mencatat setiap transaksi distribusi / pengeluaran aset (barang keluar).

| Kolom        | Tipe Data       | Constraint                              | Keterangan                                                         |
| ------------ | --------------- | --------------------------------------- | ------------------------------------------------------------------ |
| `id`         | BIGINT UNSIGNED | **PRIMARY KEY**, AUTO INCREMENT         | —                                                                  |
| `asset_id`   | BIGINT UNSIGNED | NOT NULL, **FOREIGN KEY** → `assets.id` | Aset apa yang keluar. `CASCADE DELETE`                             |
| `qty`        | INT UNSIGNED    | NOT NULL                                | Jumlah unit yang keluar                                            |
| `date`       | DATE            | NOT NULL                                | Tanggal pengeluaran                                                |
| `recipient`  | VARCHAR(255)    | NULLABLE                                | Nama penerima, bagian/divisi yang menerima, atau tujuan penggunaan |
| `notes`      | TEXT            | NULLABLE                                | Catatan tambahan                                                   |
| `created_by` | BIGINT UNSIGNED | NOT NULL, **FOREIGN KEY** → `users.id`  | ID user yang menginput (audit trail)                               |
| `created_at` | TIMESTAMP       | NULLABLE                                | —                                                                  |
| `updated_at` | TIMESTAMP       | NULLABLE                                | —                                                                  |

---

### Tabel 5: `asset_serial_numbers`

Pelacakan level unit individual untuk aset yang `has_serial_number = true`. Setiap baris mewakili satu unit fisik dengan identitas unik.

| Kolom           | Tipe Data         | Constraint                                  | Keterangan                                                                          |
| --------------- | ----------------- | ------------------------------------------- | ----------------------------------------------------------------------------------- |
| `id`            | BIGINT UNSIGNED   | **PRIMARY KEY**, AUTO INCREMENT             | —                                                                                   |
| `asset_id`      | BIGINT UNSIGNED   | NOT NULL, **FOREIGN KEY** → `assets.id`     | Aset mana yang dimiliki SN ini. `CASCADE DELETE`                                    |
| `serial_number` | VARCHAR(255)      | NOT NULL                                    | Nomor seri fisik dari pabrikan/penyedia                                             |
| `status`        | ENUM('in', 'out') | NOT NULL, DEFAULT `'in'`                    | Status unit saat ini. `'in'` = ada di gudang/tersedia. `'out'` = sedang dikeluarkan |
| `asset_in_id`   | BIGINT UNSIGNED   | NULLABLE, **FOREIGN KEY** → `asset_ins.id`  | Referensi transaksi masuk yang memasukkan SN ini. `SET NULL` on delete              |
| `asset_out_id`  | BIGINT UNSIGNED   | NULLABLE, **FOREIGN KEY** → `asset_outs.id` | Referensi transaksi keluar yang mengeluarkan SN ini. `SET NULL` on delete           |
| `created_at`    | TIMESTAMP         | NULLABLE                                    | —                                                                                   |
| `updated_at`    | TIMESTAMP         | NULLABLE                                    | —                                                                                   |

**Index Kritis — Composite Unique Constraint `(asset_id, serial_number)`:**

- Memastikan dalam satu jenis aset tidak ada nomor seri yang sama dua kali.
- Contoh: Laptop Dell (asset_id=1) tidak boleh punya SN "SN-ABC123" dua kali. Namun aset lain (asset_id=2) boleh punya SN "SN-ABC123" karena itu produk berbeda.
- Ini lebih tepat daripada unique global, karena pabrikan berbeda sangat mungkin menggunakan format SN yang sama.

**Foreign Key on `asset_out_id` dengan `SET NULL`:**
Jika sebuah transaksi keluar dihapus, `asset_out_id` di semua SN yang terkait diset ke NULL, dan statusnya dikembalikan ke `'in'`. Logic ini dihandle di `AssetOutObserver::deleting()`.

---

### Tabel 6: `stock_opnames`

Header / kepala dokumen dari setiap sesi pemeriksaan stok fisik.

| Kolom        | Tipe Data       | Constraint                             | Keterangan                                                 |
| ------------ | --------------- | -------------------------------------- | ---------------------------------------------------------- |
| `id`         | BIGINT UNSIGNED | **PRIMARY KEY**, AUTO INCREMENT        | —                                                          |
| `tanggal`    | DATE            | NOT NULL                               | Tanggal pelaksanaan opname                                 |
| `keterangan` | TEXT            | NULLABLE                               | Deskripsi opname, misalnya "Stock Opname Akhir Tahun 2026" |
| `created_by` | BIGINT UNSIGNED | NOT NULL, **FOREIGN KEY** → `users.id` | User yang membuat sesi opname ini. `CASCADE DELETE`        |
| `created_at` | TIMESTAMP       | NULLABLE                               | —                                                          |
| `updated_at` | TIMESTAMP       | NULLABLE                               | —                                                          |

---

### Tabel 7: `stock_opname_items`

Detail per aset dalam satu sesi opname. Setiap baris adalah satu aset yang diperiksa pada sesi tersebut.

| Kolom             | Tipe Data       | Constraint                                     | Keterangan                                                                                                                                        |
| ----------------- | --------------- | ---------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------- |
| `id`              | BIGINT UNSIGNED | **PRIMARY KEY**, AUTO INCREMENT                | —                                                                                                                                                 |
| `stock_opname_id` | BIGINT UNSIGNED | NOT NULL, **FOREIGN KEY** → `stock_opnames.id` | Milik sesi opname mana. `CASCADE DELETE`: jika header opname dihapus, semua detail ikut terhapus                                                  |
| `asset_id`        | BIGINT UNSIGNED | NOT NULL, **FOREIGN KEY** → `assets.id`        | Aset yang diperiksa. `CASCADE DELETE`                                                                                                             |
| `stok_fisik`      | INT             | NOT NULL                                       | Hasil hitung fisik di lapangan oleh petugas                                                                                                       |
| `stok_sistem`     | INT             | NOT NULL                                       | **Snapshot** qty dari tabel `assets` pada saat baris ini disimpan. Bersifat historical — tidak berubah meski stok sistem berubah di kemudian hari |
| `created_at`      | TIMESTAMP       | NULLABLE                                       | —                                                                                                                                                 |
| `updated_at`      | TIMESTAMP       | NULLABLE                                       | —                                                                                                                                                 |

**Konsep `stok_sistem` sebagai Snapshot:**
Alasan `stok_sistem` disimpan bukan di-query ulang adalah untuk **historical accuracy**. Jika sebuah opname tercatat bulan lalu dengan stok sistem = 10, kemudian hari ini stok menjadi 15, laporan opname bulan lalu tetap harus menampilkan angka 10 yang akurat pada saat itu. Tanpa snapshot, angka akan berubah-ubah mengikuti kondisi stok terkini, yang membuat laporan historis tidak valid.

**Virtual Attribute `selisih`:**
Selisih tidak disimpan di database, melainkan dihitung via accessor di model:

```php
// Model StockOpnameItem.php
public function getSelisihAttribute(): int
{
    return $this->stok_fisik - $this->stok_sistem;
}
// Nilai bisa positif (lebih banyak fisik) atau negatif (lebih sedikit fisik = kemungkinan kehilangan)
```

---

## 🧩 Penjelasan Model (Eloquent)

Model adalah representasi PHP dari tabel database. Setiap Model mengextend `Illuminate\Database\Eloquent\Model` dan mendefinisikan relasi antar tabel.

### `App\Models\Asset`

```
File: app/Models/Asset.php
Tabel: assets
```

| Method/Property            | Tipe     | Penjelasan                                                                                |
| -------------------------- | -------- | ----------------------------------------------------------------------------------------- |
| `$fillable`                | Property | Whitelist kolom yang boleh diisi via mass-assignment (`create()`, `fill()`)               |
| `$casts`                   | Property | `has_serial_number` dicast ke tipe PHP `boolean` (dari integer 0/1 di DB)                 |
| `ins()`                    | HasMany  | Mendapatkan semua transaksi masuk aset ini. `Asset::find(1)->ins`                         |
| `outs()`                   | HasMany  | Mendapatkan semua transaksi keluar aset ini                                               |
| `serialNumbers()`          | HasMany  | Mendapatkan semua nomor seri yang terkait aset ini (semua status)                         |
| `availableSerialNumbers()` | HasMany  | Mendapatkan hanya SN yang masih tersedia (status = `'in'`). Digunakan di form Aset Keluar |

### `App\Models\AssetIn`

```
File: app/Models/AssetIn.php
Tabel: asset_ins
```

| Method/Property   | Tipe      | Penjelasan                                                                            |
| ----------------- | --------- | ------------------------------------------------------------------------------------- |
| `$fillable`       | Property  | Kolom: `asset_id`, `qty`, `date`, `supplier`, `notes`, `created_by`                   |
| `$casts`          | Property  | `date` dicast ke objek `Carbon\Carbon` (date) untuk kemudahan formatting              |
| `asset()`         | BelongsTo | Relasi ke model `Asset`. Inverse dari `Asset::ins()`                                  |
| `creator()`       | BelongsTo | Relasi ke `User` via kolom `created_by`. Foreign key custom (bukan default `user_id`) |
| `serialNumbers()` | HasMany   | SN yang masuk melalui transaksi ini, via kolom `asset_in_id`                          |

### `App\Models\AssetOut`

```
File: app/Models/AssetOut.php
Tabel: asset_outs
```

Struktur identik dengan `AssetIn`, perbedaannya:

- Punya kolom `recipient` (bukan `supplier`)
- `serialNumbers()` menggunakan FK `asset_out_id`

### `App\Models\AssetSerialNumber`

```
File: app/Models/AssetSerialNumber.php
Tabel: asset_serial_numbers
```

| Method       | Tipe      | Penjelasan                                                              |
| ------------ | --------- | ----------------------------------------------------------------------- |
| `asset()`    | BelongsTo | Aset induk pemilik SN ini                                               |
| `assetIn()`  | BelongsTo | Transaksi masuk yang pertama kali memasukkan SN ini ke sistem           |
| `assetOut()` | BelongsTo | Transaksi keluar yang membawa SN ini keluar. `NULL` jika masih tersedia |

### `App\Models\StockOpname`

```
File: app/Models/StockOpname.php
Tabel: stock_opnames
```

| Method      | Tipe      | Penjelasan                                       |
| ----------- | --------- | ------------------------------------------------ |
| `items()`   | HasMany   | Semua detail baris opname yang dimiliki sesi ini |
| `creator()` | BelongsTo | User yang membuat sesi opname ini                |

### `App\Models\StockOpnameItem`

```
File: app/Models/StockOpnameItem.php
Tabel: stock_opname_items
```

| Method/Property                    | Penjelasan                                                                                                                      |
| ---------------------------------- | ------------------------------------------------------------------------------------------------------------------------------- |
| `stockOpname()` (BelongsTo)        | Header opname induk (satu sesi opname)                                                                                          |
| `asset()` (BelongsTo)              | Aset yang diperiksa dalam baris ini                                                                                             |
| `getSelisihAttribute()` (Accessor) | Virtual computed property: `stok_fisik - stok_sistem`. Tidak tersimpan di DB, dihitung on-the-fly. Diakses via `$item->selisih` |

### `App\Models\User`

```
File: app/Models/User.php
Tabel: users
Implements: FilamentUser (interface Filament untuk otorisasi akses panel)
```

| Method                         | Penjelasan                                                                                                                                                                                                     |
| ------------------------------ | -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `isPimpinan()`                 | Memeriksa apakah `role === 'pimpinan'`. Digunakan di seluruh Resource untuk mengontrol visibilitas tombol hapus, menu, dll.                                                                                    |
| `canAccessPanel(Panel $panel)` | Implementasi dari interface `FilamentUser`. Mengembalikan `true` — artinya **semua** user (baik admin maupun pimpinan) boleh login ke Filament panel. Pembatasan per-fitur dihandle di masing-masing Resource. |

---

## 🔭 Penjelasan Observer (Business Logic)

Observer adalah komponen Laravel yang "mendengarkan" event lifecycle pada sebuah Model (created, updated, deleted, dll.). Di sistem ini, Observer digunakan untuk mengotomatisasi perubahan stok agar tidak perlu ditulis ulang di setiap titik yang mengubah data.

> **Kenapa Observer, bukan langsung di Controller/Resource?**
> Karena Observer bersifat **reusable dan terisolasi**. Logika bisnis (tambah/kurang stok) tetap berjalan benar tidak peduli apakah mutasi data dilakukan lewat Filament UI, API, Artisan Tinker, atau cara lain. Ini prinsip **OCP (Open-Closed Principle)** dalam SOLID.

### `App\Observers\AssetInObserver`

```
File: app/Observers/AssetInObserver.php
Mengamati: AssetIn model
```

| Event      | Method                       | Logic                                                                                                                                                                                                                          |
| ---------- | ---------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| `created`  | `created(AssetIn $assetIn)`  | Setelah record baru tersimpan: **tambah qty** aset induk sebesar `$assetIn->qty` menggunakan atomic `increment()`                                                                                                              |
| `updated`  | `updated(AssetIn $assetIn)`  | Saat qty diubah (bukan kolom lain): Hitung **delta** (qty baru - qty lama), lalu increment/decrement dengan **delta** tersebut. Ini menghindari double-counting                                                                |
| `deleting` | `deleting(AssetIn $assetIn)` | Sebelum record dihapus: (1) Cek apakah ada SN dari transaksi ini yang sudah berstatus `out`. Jika ada, **tolak penghapusan** dengan melempar `ValidationException`. (2) Jika aman, kurangi qty aset dan hapus semua SN terkait |

**Mengapa cek SN sebelum hapus?** Mencegah konflik data: jika laptop SN-001 masuk via transaksi A, kemudian SN-001 dikeluarkan via transaksi B, menghapus transaksi A akan membuat SN-001 seolah tidak pernah ada padahal catatan keluarnya masih ada di sistem.

### `App\Observers\AssetOutObserver`

```
File: app/Observers/AssetOutObserver.php
Mengamati: AssetOut model
```

| Event      | Method                         | Logic                                                                                                                                                                     |
| ---------- | ------------------------------ | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `created`  | `created(AssetOut $assetOut)`  | Setelah record tersimpan: **kurangi qty** aset induk sebesar qty yang keluar                                                                                              |
| `updated`  | `updated(AssetOut $assetOut)`  | Jika qty berubah: Hitung delta, lalu decrement dengan delta. (Misal qty naik dari 2 ke 3, stok berkurang 1 lagi).                                                         |
| `deleting` | `deleting(AssetOut $assetOut)` | Sebelum dihapus: (1) **Kembalikan qty** aset (increment). (2) Set semua SN yang terkait menjadi `status='in'` dan `asset_out_id=NULL` — seolah barang tidak pernah keluar |

---

## ⚙️ Penjelasan Filament Resources

Resource adalah "jantung" panel admin Filament. Setiap Resource mengatur 3 hal utama: **Form** (input data), **Table** (tampilan daftar), dan **Pages** (routing halaman).

### `AssetResource` — Daftar Aset

```
File: app/Filament/Resources/AssetResource.php
Model: Asset | URL: /admin/daftar-aset
Urutan Navigasi: 1 (paling atas)
```

**Form `form()`:**

- `code`: TextInput wajib, validasi `unique` (abaikan record sendiri saat edit via `ignoreRecord: true`).
- `name`: TextInput wajib.
- `has_serial_number`: Toggle, dikunci (`disabled`) saat edit (`$record !== null`). Tidak bisa diubah setelah disimpan.
- `qty`: TextInput disabled + `dehydrated(false)` saat edit — tidak dikirim ke server saat form edit, mencegah overwrite stok oleh form.

**Table `table()`:**

- Kolom `qty` ditampilkan sebagai badge hijau (stok > 0) atau merah (stok = 0).
- **Filter:** Kategori (SelectFilter dari data distinct di DB), status SN (TernaryFilter), kondisi stok (SelectFilter custom query).
- **Header Action "Cetak Laporan":** Mengambil filter aktif dari state Livewire (`$livewire->tableFilters`) dan memasukkannya sebagai query parameter ke URL laporan.
- **Tombol Hapus:** Hanya muncul jika `auth()->user()->isPimpinan()`.

**Infolist `infolist()`:** Tampilan detail (View page). Menampilkan semua info aset + daftar SN beserta statusnya (hanya tampil jika `has_serial_number = true`).

---

### `AssetInResource` — Aset Masuk

```
File: app/Filament/Resources/AssetInResource.php
Model: AssetIn | URL: /admin/aset-masuk
Urutan Navigasi: 2
```

**Form `form()` — Fitur Kunci:**

1. **Select Aset dengan `createOptionForm`:** User bisa memilih aset existing, atau langsung membuat aset baru dari dalam form ini tanpa keluar halaman.

2. **Logika Serial Number (live/reactive form):**
    - Saat Aset dipilih, sistem memanggil `assetHasSerial($asset_id)` — query ke DB.
    - Jika `true`: Field `qty` di-disable, Repeater SN muncul.
    - User mengisi SN satu per satu di repeater.
    - `afterStateUpdated` pada Repeater: `$set('qty', count($state))` — qty otomatis mengikuti jumlah SN yang diisi.

3. **`assetHasSerial()` (protected method):**
    ```php
    protected static function assetHasSerial(?int $assetId): bool {
        if (!$assetId) return false;
        return (bool) Asset::find($assetId)?->has_serial_number;
    }
    ```
    Helper internal yang dipakai di banyak tempat dalam form untuk mengecek flag SN aset.

**Pages yang terdaftar:**

- `index` → `ListAssetIns` (tabel)
- `create` → `CreateAssetIn` (form tambah) — SN disimpan via `afterCreate` hook di halaman ini
- `edit` → `EditAssetIn` — Asset tidak bisa diganti saat edit (disabled)
- `view` → `ViewAssetIn` — Infolist detail + daftar SN yang masuk

---

### `AssetOutResource` — Aset Keluar

```
File: app/Filament/Resources/AssetOutResource.php
Model: AssetOut | URL: /admin/aset-keluar
Urutan Navigasi: 3
```

**Form `form()` — Fitur Kunci:**

1. **Label Dropdown Aset menampilkan stok:**

    ```php
    ->getOptionLabelFromRecordUsing(fn(Asset $record) => "{$record->code} — {$record->name} (stok: {$record->qty})")
    ```

    User langsung tahu stok tersedia tanpa perlu membuka halaman lain.

2. **Multi-Select Serial Number:**
    - Hanya tampil jika aset ber-SN (`assetHasSerial()`).
    - Options di-query: hanya SN dengan `status = 'in'` yang muncul (SN yang sudah keluar tidak akan muncul lagi).
    - Saat SN dipilih, `qty` otomatis terhitung dari jumlah SN yang dipilih.

3. **Validasi qty:** Field `qty` pada aset non-SN memiliki `helperText` yang mengingatkan tidak boleh melebihi stok (validasi backend dihandle di Observer — decrement bisa jadi negatif, perlu diperhatikan saat konfigurasi lanjutan).

---

### `StockOpnameResource` — Stock Opname

```
File: app/Filament/Resources/StockOpnameResource.php
Model: StockOpname | URL: /admin/stock-opname
Urutan Navigasi: 4
```

**Form `form()` — Fitur Kunci:**

1. **Nested Repeater dengan `->relationship()`:**

    ```php
    Forms\Components\Repeater::make('items')->relationship()
    ```

    Repeater ini terhubung ke relasi `StockOpname::items()`. Saat form disimpan, Filament otomatis menyimpan baris-baris items ke tabel `stock_opname_items` dengan `stock_opname_id` yang sesuai.

2. **Auto-fill `stok_sistem` real-time:**

    ```php
    ->afterStateUpdated(function (Set $set, ?int $state) {
        if ($state) {
            $asset = Asset::find($state);
            $set('stok_sistem', $asset?->qty ?? 0);
        }
    })
    ```

    Saat user memilih aset di dropdown, `stok_sistem` langsung terisi dari `assets.qty` saat itu. Ini merupakan snapshot real-time yang kemudian tersimpan permanen.

3. **Field `stok_sistem` di-readOnly:** User **tidak bisa** mengubah stok sistem — hanya sistem yang mengisinya.

**Infolist `infolist()` — Menampilkan Selisih:**

- Mengakses `$item->selisih` (accessor virtual di Model).
- Warna badge dinamis: hijau (selisih = 0, sesuai), kuning (selisih positif, fisik lebih banyak), merah (selisih negatif, fisik kurang — potensi kehilangan aset).
- Format: `+3` atau `-2` untuk kejelasan visual.

**Action Cetak per Baris:**

```php
Tables\Actions\Action::make('cetak')
    ->url(fn(StockOpname $record) => route('laporan.stock-opname', $record->id))
    ->openUrlInNewTab(),
```

---

### `UserResource` — Kelola User

```
File: app/Filament/Resources/UserResource.php
Model: User | URL: /admin/users
Khusus: Hanya Pimpinan
```

**Otorisasi berlapis:**

```php
// Menyembunyikan dari navigasi sidebar
public static function shouldRegisterNavigation(): bool { ... }

// Menolak akses ke halaman index
public static function canViewAny(): bool { ... }

// Menolak akses create, edit, delete
public static function canCreate(): bool { ... }
public static function canEdit($record): bool { ... }
public static function canDelete($record): bool { ... }
```

Semua method di atas mengecek `isPimpinan()`. Implementasi berlapis ini memastikan keamanan: meski URL-nya diakses langsung browser, Filament tetap mengembalikan 403.

**Password Handling:**

```php
Forms\Components\TextInput::make('password')
    ->dehydrated(fn($state) => filled($state))          // Hanya kirim jika diisi
    ->dehydrateStateUsing(fn($state) => Hash::make($state)) // Hash sebelum simpan
    ->required(fn(string $context) => $context === 'create') // Wajib hanya saat create
```

Saat edit user, password bisa dibiarkan kosong (tidak akan mengubah password lama).

---

## 🌐 Penjelasan Routes

```
File: routes/web.php
```

Sistem memiliki dua kelompok route:

### 1. Route Publik

```php
Route::get('/', fn() => view('welcome'));
```

Halaman landing page (bisa dikustomisasi atau redirect ke `/admin`).

### 2. Route Laporan (Auth Protected)

```php
Route::middleware(['auth'])->group(function () { ... });
```

Semua route laporan memerlukan user login (`middleware auth`). Filament otomatis mengarahkan ke halaman login jika belum login.

| Route                        | Parameter                                   | Logic                                                                                    |
| ---------------------------- | ------------------------------------------- | ---------------------------------------------------------------------------------------- |
| `/laporan/aset`              | `?category=&has_serial_number=&stok=`       | Query `assets` dengan filter opsional. View: `laporan/asset.blade.php`                   |
| `/laporan/aset-masuk`        | `?date_from=&date_to=&asset_id=&supplier=`  | Query `asset_ins` dengan eager loading relasi. View: `laporan/asset-in.blade.php`        |
| `/laporan/aset-keluar`       | `?date_from=&date_to=&asset_id=&recipient=` | Query `asset_outs` dengan eager loading relasi. View: `laporan/asset-out.blade.php`      |
| `/laporan/stock-opname/{id}` | `id` di path — ID sesi opname               | Query opname beserta `items.asset` dan `creator`. View: `laporan/stock-opname.blade.php` |

---

## 💬 Pertanyaan & Jawaban Sidang TA

**Q: Mengapa menggunakan FilamentPHP bukan membangun UI sendiri?**

> FilamentPHP mempercepat pembangunan admin panel yang kompleks secara signifikan. Membangun form reaktif (seperti auto-fill stok sistem, show/hide SN field) dari nol membutuhkan waktu jauh lebih lama. Dengan Filament, fokus dapat diarahkan ke logika bisnis, bukan boilerplate UI. Filament juga sudah menyediakan autentikasi, otorisasi, notifikasi, dan layout responsif secara bawaan.

**Q: Bagaimana sistem mencegah stok menjadi negatif?**

> Saat ini, validasi stok negatif dihandle di level UI: helper text mengingatkan user bahwa qty tidak boleh melebihi stok. Untuk aset ber-Serial Number, validasi lebih ketat karena user hanya bisa memilih SN yang tersedia (`status='in'`), sehingga secara desain tidak mungkin mengeluarkan lebih dari yang ada.

**Q: Apa yang terjadi jika transaksi Aset Masuk dihapus setelah barang sudah dikeluarkan?**

> `AssetInObserver::deleting()` akan memeriksa apakah ada SN dari transaksi tersebut yang sudah berstatus `out`. Jika ada, penghapusan **ditolak** dengan pesan error. Ini mencegah inkonsistensi data historis.

**Q: Kenapa `stok_sistem` disimpan di tabel stock_opname_items, bukan di-query ulang dari tabel assets?**

> Untuk menjaga akurasi historis (historical accuracy). Laporan opname 3 bulan lalu harus menampilkan angka stok yang sama seperti saat opname dilakukan, bukan stok saat ini. Ini adalah teknik "snapshot" yang umum digunakan dalam sistem inventory dan akuntansi.

**Q: Apa perbedaan `HasMany` dan `BelongsTo` di Eloquent?**

> `HasMany` digunakan di sisi "induk" (satu ke banyak). Contoh: `Asset::ins()` — satu aset punya banyak transaksi masuk. `BelongsTo` digunakan di sisi "anak" (banyak ke satu). Contoh: `AssetIn::asset()` — setiap transaksi masuk dimiliki oleh satu aset. Keduanya membentuk dua sisi dari relasi yang sama.

**Q: Kenapa password di-hash dan tidak bisa dibaca kembali?**

> Sistem menggunakan algoritma `bcrypt` (via `Hash::make()`) yang bersifat **one-way hash** (tidak bisa didekripsi). Ini adalah standar keamanan industri. Bahkan admin sistem tidak bisa mengetahui password user lain. Jika lupa, harus direset — bukan dilihat.

**Q: Apa itu Composite Unique Constraint pada `asset_serial_numbers`?**

> Constraint `UNIQUE(asset_id, serial_number)` berarti kombinasi dari kedua kolom tersebut harus unik di seluruh tabel. Ini memperbolehkan SN yang sama untuk aset berbeda, namun mencegah SN duplikat untuk aset yang sama. Lebih fleksibel daripada unique hanya pada `serial_number`.

---

<div align="center">

**Sistem Manajemen Aset — Tugas Akhir**

Dibangun dengan ❤️ menggunakan **Laravel 12** + **FilamentPHP 3**

</div>
