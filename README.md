# J&J Group - Enterprise Financial & Operational Ledger

Sistem pusat kendali keuangan (*Enterprise Financial & Operational Ledger*) internal J&J Group untuk pemantauan kesehatan finansial secara real-time. Aplikasi ini dirancang khusus untuk memetakan pendapatan layanan *eco-plumbing*, memantau pengeluaran operasional (OPEX harian/bulanan), dan mengelola penggajian (*payroll*) staf dan teknisi lapangan.

## Fitur Utama

1. **Dashboard Multi-Role (Owner & Admin)**:
   - **Owner**: Memiliki akses penuh melihat total Laba Bersih, rincian biaya pengeluaran gabungan (OPEX + Payroll), grafik tren analitik bulanan, dan kelola pengguna (Admin).
   - **Admin**: Akses terbatas untuk pencatatan master data (Kategori & Payroll) dan pencatatan transaksi harian. Akses terhadap Laba Bersih, detail gaji keseluruhan, dan chart visual analitik disembunyikan/dibatasi (Owner-Only).
2. **Kategori Anggaran & Transaksi Harian**:
   - Pencatatan transaksi masuk (Income) dan pengeluaran (Expense) harian.
   - Validasi ketat yang menjamin tipe transaksi sinkron dengan kategori anggaran.
   - Perlindungan data historis menggunakan constraints `onDelete('restrict')` pada database (audit compliance) dan sistem *Soft Deletes*.
3. **Data Payroll Terkelola**:
   - Pencatatan data gaji pokok staf dan teknisi lapangan dengan filter periode format `YYYY-MM`.
4. **Analitik Visual & Ekspor Laporan**:
   - Integrasi grafik batang interaktif (Chart.js) untuk visualisasi historis Omset Kotor vs Pengeluaran gabungan.
   - Ekspor data transaksi terpilih secara instan ke format CSV native yang super-cepat dengan penyesuaian metadata ringkasan berdasarkan role akun.

---

## Spesifikasi Teknologi

- **Framework**: Laravel 11.x (PHP 8.2+)
- **CSS Engine**: Tailwind CSS v4 + Vite Bundler
- **Database**: MySQL 8.x / MariaDB
- **Chart Visualizer**: Chart.js via CDN

---

## Langkah Instalasi & Konfigurasi

### 1. Klon Repositori & Install Dependensi
Klon proyek ini ke dalam web server lokal Anda (misalnya Laragon atau XAMPP), buka terminal pada folder proyek tersebut, lalu jalankan:

```bash
composer install
npm install
```

### 2. Salin Konfigurasi Lingkungan (`.env`)
Salin file `.env.example` menjadi `.env` lalu sesuaikan konfigurasi database Anda:

```bash
cp .env.example .env
```

Buka file `.env` dan atur detail koneksi database, misalnya:

```env
APP_NAME="J&J Finance"
APP_URL=http://finance.jj-group.id

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=finance
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Generate Application Key
Jalankan perintah berikut untuk meng-generate application key Laravel:

```bash
php artisan key:generate
```

### 4. Jalankan Migrasi & Database Seeding
Jalankan migrasi database beserta seed data awal untuk mendaftarkan akun default dan kategori anggaran default J&J Group:

```bash
php artisan migrate --seed
```

### 5. Kompilasi Aset Frontend (Tailwind CSS & JS)
Kompilasi file-file Tailwind CSS untuk production menggunakan Vite:

```bash
npm run build
```

Atau jalankan server development jika ingin memodifikasi file styling:

```bash
npm run dev
```

### 6. Jalankan Server Laravel
Jalankan server lokal Laravel:

```bash
php artisan serve
```

Aplikasi sekarang dapat diakses melalui browser pada alamat default `http://127.0.0.1:8000` (atau sesuaikan dengan virtual host Anda seperti `http://finance.jj-group.id`).

---

## Akun Demo Pengujian (Seeded Accounts)

Untuk mempermudah pengujian hak akses role di dasbor, gunakan kredensial berikut:

### 1. Akun Owner (Akses Penuh + Metrik Laba & Chart)
- **Email**: `owner@jj-group.id`
- **Password**: `password`

### 2. Akun Admin (Akses Operasional - Laba & Gaji Dibatasi)
- **Email**: `admin@jj-group.id`
- **Password**: `password`

---
*Dikembangkan dengan standar kualitas korporat profesional untuk J&J Group.*
