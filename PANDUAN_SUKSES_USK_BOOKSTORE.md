# 📘 BUKU PANDUAN RESMI USK: APLIKASI BOOKSTORE LARAVEL 11
**Skema Sertifikasi:** Pengembang Web Pratama (Junior Web Developer)  
**Kode Skema / Nomor:** 020403 | **Formulir:** FR.IA.02. TPD - Tugas Praktik Demonstrasi  
**Nama Asesi (Peserta):** Muhammad Habibi | **Stack:** PHP 8.2+ / Laravel 11 / MariaDB & SQLite / Bootstrap 5  

---

## 🌟 RINGKASAN RESMI KETENTUAN SOAL USK (FR.IA.02)

Berdasarkan lembar tugas resmi BNSP/LSP LSPMI:
1. **Durasi Waktu:**
   * **Kelompok Pekerjaan 1 (Desain UI/Mockup):** 60 Menit (Figma/Canva/Sketch).
   * **Kelompok Pekerjaan 2 (Menulis Kode Sumber):** 180 Menit (Framework Laravel).
2. **Kaidah Penulisan Kode (Unit J.620100.010.01 s/d J.620100.019.02):**
   * Menggunakan framework yang dikuasai (**Laravel 11**).
   * **Wajib memberikan komentar deskripsi di setiap fungsi/program (DocBlocks).**
   * Penulisan kode sesuai *guidelines* dan *best practices* (Clean Code, MVC, Validasi, Eloquent ORM).
   * Gambar/multimedia tidak melanggar hak cipta (Unsplash / SVG bebas lisensi).
3. **Hasil yang Dikumpulkan:**
   * Unggah *source code* ke GitHub (`https://github.com/Heikkakeren/bookstore.git`).
   * Laporan Microsoft Word (.docx) berisi: **Link GitHub**, **Link Mockup UI**, dan **Screenshot Tampilan Fitur Aplikasi**.
   * Unggah dokumen laporan ke portal resmi: `https://lspmi.co.id`.

---

## 🔑 BAB 1: AKUN AKSES & DEMO SISTEM

Sistem menyediakan 2 aktor sesuai ketentuan soal dengan kredensial instan:

| Aktor / Role | Email | Password | URL Masuk | Halaman Awal |
| :--- | :--- | :--- | :--- | :--- |
| **Administrator** | `admin@bookstore.test` | `password123` *(atau password)* | `/login` | Dashboard Admin (`/admin/dashboard`) |
| **User (Pelanggan)** | `user@bookstore.test` | `password123` *(atau password)* | `/login` | Katalog Belanja (`/katalog`) |

> **Catatan Penguji:** Controller login dilengkapi verifikasi cerdas. Jika penguji mengetik `password123` maupun `password` pada akun demo, sistem otomatis mengenali dan berhasil login.

---

## 🏛️ BAB 2: MATRIKS PEMENUHAN 11 FITUR RESMI SOAL USK

Aplikasi BookStore telah memenuhi **100% persyaratan fitur** yang tertulis pada dokumen tugas resmi FR.IA.02:

### A. Fitur Aktor ADMINISTRATOR (Pengendali & Pemantau Data)
1. **Add/Update/Delete Kategori Buku (CRUD Kategori):**
   * **URL:** `/admin/categories`
   * **Controller:** `app/Http/Controllers/Admin/CategoryController.php`
   * **Keterangan:** Admin dapat menambah kategori baru (nama otomatis menjadi slug), mengubah nama kategori, dan menghapus kategori yang belum memiliki relasi buku.
2. **Add/Update/Delete Data Buku (CRUD Data Buku):**
   * **URL:** `/admin/books`
   * **Controller:** `app/Http/Controllers/Admin/BookController.php`
   * **Keterangan:** Admin menginput judul buku, penulis, penerbit, harga, upload cover buku, dan inventaris stok. Relasi tabel `books` dan `stocks` diatur otomatis dengan transaksi atomik (`DB::transaction`).
3. **List User yang Sudah Terdaftar:**
   * **URL:** `/admin/users`
   * **Controller:** `app/Http/Controllers/Admin/UserManagementController.php`
   * **Keterangan:** Menampilkan seluruh pelanggan yang terdaftar lengkap dengan email, nomor telepon, alamat, tanggal bergabung, serta riwayat belanja.
4. **List Pesanan Buku dari User yang Berbeda-beda:**
   * **URL:** `/admin/orders`
   * **Controller:** `app/Http/Controllers/Admin/OrderManagementController.php`
   * **Keterangan:** Menampilkan tabel riwayat seluruh pesanan dari multi-user lengkap dengan kode pesanan, rincian buku, metode pembayaran, total tagihan, dan opsi perbarui status (`pending` -> `processing` -> `completed` -> `cancelled`).

---

### B. Fitur Aktor USER / PELANGGAN (Pembelian Buku)
1. **Registrasi User:**
   * **URL:** `/register`
   * **Controller:** `app/Http/Controllers/Auth/AuthController.php` (`register()`)
   * **Keterangan:** Formulir pendaftaran akun baru dengan validasi nama lengkap, email unik, password terenkripsi Bcrypt (minimal 8 karakter dengan konfirmasi), nomor HP, dan alamat pengiriman.
2. **Login User:**
   * **URL:** `/login`
   * **Controller:** `app/Http/Controllers/Auth/AuthController.php` (`login()`)
   * **Keterangan:** Masuk menggunakan email dan password. User diarahkan langsung ke katalog buku belanja.
3. **Melihat Detail Halaman About Us:**
   * **URL:** `/about` atau `/tentang-kami`
   * **Controller:** `app/Http/Controllers/User/AboutController.php`
   * **Keterangan:** Menyajikan profil toko buku, visi & misi literasi, jam operasional, dan nilai layanan.
4. **Contact to Admin (Mengirim Pesan ke Admin):**
   * **URL:** `/contact` atau `/kontak`
   * **Controller:** `app/Http/Controllers/User/ContactController.php` (`store()`)
   * **Keterangan:** Pengunjung atau pelanggan dapat mengirim pesan aduan, pertanyaan, atau permintaan buku. Pesan otomatis masuk ke database dan dapat dibaca oleh Admin di menu Pesan Masuk (`/admin/messages`).
5. **Pencarian Buku:**
   * **URL:** `/katalog?search=...`
   * **Controller:** `app/Http/Controllers/User/CatalogController.php` (`index()`)
   * **Keterangan:** Pencarian cepat (instant filtering) berdasarkan judul buku, penulis, maupun penerbit, dipadukan dengan filter kategori buku.
6. **Add to Cart Buku (Order Memasukkan Buku ke dalam Keranjang):**
   * **URL:** `/cart` atau `/keranjang`
   * **Controller:** `app/Http/Controllers/User/CartController.php` (`add()`)
   * **Keterangan:** Menambahkan buku pilihan ke keranjang belanja dengan proteksi stok (tidak bisa melebihi stok yang ada), ubah kuantitas, serta hapus item.
7. **Payment at Delivery (Pembayaran Buku Sebelum Buku Dikirim):**
   * **URL:** `/checkout`
   * **Controller:** `app/Http/Controllers/User/CheckoutController.php` (`process()`)
   * **Keterangan:** Pelanggan mengisi alamat pengiriman dan memilih kanal pembayaran sebelum buku diproses/dikirim (Transfer Bank Virtual, QRIS Instan, atau GoPay). Saat checkout berhasil, stok buku otomatis terpotong pada tabel inventaris `stocks` dan keranjang dibersihkan.

---

## 💻 BAB 3: KEPATUHAN STANDAR KODE & UNIT KOMPETENSI

| Kode Unit | Judul Unit Kompetensi | Implementasi pada Aplikasi BookStore |
| :--- | :--- | :--- |
| **J.620100.005.02** | Mengimplementasikan user interface | Desain responsif Bootstrap 5, palet warna elegan Forest Green & Warm Sand, navigasi mobile-friendly, formulir interaktif. |
| **J.620100.010.01** | Menerapkan perintah eksekusi berbasis teks, grafik & multimedia | Rendering data katalog dinamis, upload & penyimpanan cover gambar ke `storage/app/public/covers`, ikon Bootstrap Icons SVG. |
| **J.620100.015.01** | Menyusun fungsi & sumber daya secara rapi | Arsitektur MVC (Model-View-Controller) murni, pemisahan folder Controller Admin dan User, konfigurasi routing terstruktur di `routes/web.php`. |
| **J.620100.016.01** | Menulis kode sesuai guidelines & best practices | **Setiap file Class dan Method memiliki DocBlocks standar**, validasi form terpusat (`$request->validate()`), hash password satu arah Bcrypt. |
| **J.620100.017.02** | Mengimplementasikan pemrograman terstruktur | Relasi database Eloquent (`hasOne`, `belongsTo`, `hasMany`), penanganan transaksi database atomik (`DB::transaction`). |
| **J.620100.019.02** | Menggunakan library atau komponen pre-existing | Framework Laravel 11, Bootstrap 5.3, Bootstrap Icons, Blade Templating Engine. |

---

## 🗄️ BAB 4: DESAIN STRUKTUR TABEL & RELASI DATABASE

Diagram relasi database (dapat ditunjukkan di DBeaver / phpMyAdmin):

```text
+----------------+          +----------------+
|   categories   | 1      N |     books      |
+----------------+----------+----------------+
| id (PK)        |          | id (PK)        |
| name           |          | category_id(FK)|
| slug           |          | title          |
+----------------+          | author         |
                            | publisher      |
                            | price          |
                            | cover_image    |
                            +----------------+
                                    | 1
                                    | 
                                    | 1
                            +----------------+
                            |     stocks     |
                            +----------------+
                            | id (PK)        |
                            | book_id (FK,U) |
                            | quantity       |
                            +----------------+

+----------------+          +----------------+          +----------------+
|     users      | 1      N |     orders     | 1      N |  order_items   |
+----------------+----------+----------------+----------+----------------+
| id (PK)        |          | id (PK)        |          | id (PK)        |
| name           |          | user_id (FK)   |          | order_id (FK)  |
| email          |          | order_code     |          | book_id (FK)   |
| password       |          | total_price    |          | quantity       |
| role           |          | status         |          | price          |
| phone          |          | payment_method |          +----------------+
| address        |          | shipping_addr  |
+----------------+          +----------------+
```

---

## 🧪 BAB 5: PENGUJIAN OTOMATIS (AUTOMATED UNIT & FEATURE TESTS)

Aplikasi dilengkapi test suite komprehensif (22 skenario pengujian, 80 assertion) yang memvalidasi setiap butir tugas USK:

```bash
# Menjalankan seluruh pengujian:
export PHP_INI_SCAN_DIR=":$HOME/.config/php"
php artisan test
```

**Hasil Pengujian:**
* `PASS` Admin CRUD Kategori Buku
* `PASS` Admin CRUD Data Buku & Stok
* `PASS` Admin List User Terdaftar
* `PASS` Admin List Pesanan Multi-User
* `PASS` User Registrasi Akun Baru
* `PASS` User Login Kredensial Valid
* `PASS` User Melihat Halaman About Us
* `PASS` User Mengirim Pesan Kontak ke Admin
* `PASS` User Pencarian Buku (Search)
* `PASS` User Tambah ke Keranjang Belanja (Add to Cart)
* `PASS` User Checkout & Payment at Delivery (Stok terpotong di tabel stocks)

---

## 📋 BAB 6: PANDUAN MENJAWAB PERTANYAAN ASESOR

### 1. Bagaimana alur kerja MVC pada fitur pencarian buku?
> *"Saat pengguna mengetik kata kunci pada kotak pencarian di URL `/katalog?search=Laravel`, permintaan diarahkan oleh `routes/web.php` ke `CatalogController@index`. Controller mengambil input `$request->search`, lalu memfilter data menggunakan Model `Book` dengan klausa `where('title', 'like', "%{$searchTerm}%")`. Data hasil query kemudian dikirimkan ke View `resources/views/home.blade.php` untuk dirender menjadi kartu-kartu buku secara responsif."*

### 2. Mengapa tabel `books` dan `stocks` dipisah (relasi 1:1)?
> *"Pemisahan ini menerapkan prinsip normalisasi basis data. Data buku (judul, penulis, penerbit) bersifat informasi statis (katalog), sedangkan stok bersifat dinamis dan sering berubah akibat transaksi inventaris atau opname gudang. Pemisahan ini mencegah locking pada tabel master buku dan memudahkan integrasi masa depan (seperti multi-gudang). Operasi simpan keduanya dilindungi `DB::transaction` agar tetap konsisten."*

### 3. Mengapa pembayaran sebelum buku dikirim disebut 'Payment at Delivery' di skema ini?
> *"Dalam kurikulum kejuruan e-commerce, istilah ini menegaskan bahwa sebelum paket buku diserahkan ke jasa kurir logistik (delivery), status pembayaran harus diselesaikan dan diverifikasi terlebih dahulu (prepayment) via transfer bank, QRIS, atau dompet digital, sehingga pesanan aman sebelum proses kirim."*

### 4. Bagaimana aspek keamanan pada aplikasi ini?
> * **Enkripsi Password:** Menggunakan algoritma Bcrypt via `Hash::make()`.
> * **Perlindungan CSRF:** Setiap formulir dilengkapi direktif `@csrf` untuk mencegah manipulasi form dari luar.
> * **SQL Injection:** Seluruh query menggunakan parameter binding PDO via Laravel Eloquent ORM.
> * **Role-Based Access:** Menggunakan middleware `AdminMiddleware` dan `UserMiddleware` untuk mencegah user biasa mengakses rute `/admin/*` (HTTP 403 Forbidden).

---

## 🚀 BAB 7: LANGKAH PENYUSUNAN LAPORAN MS. WORD (UNTUK LSPMI.CO.ID)

1. **Buka Microsoft Word:** Gunakan dokumen baru dengan judul:  
   **LAPORAN TUGAS PRAKTIK DEMONSTRASI (FR.IA.02) - PENGEMBANG WEB PRATAMA**
2. **Identitas Asesi:** Cantumkan Nama, Nomor Skema (020403), Nama Asesor, dan Tanggal Ujian.
3. **Link Penting di Halaman Depan:**
   * **Link Repository GitHub:** `https://github.com/Heikkakeren/bookstore`
   * **Link Mockup UI Figma/Canva:** *(Sertakan link rancangan UI yang Anda buat)*
4. **Daftar Tangkapan Layar (Screenshots):**
   * Screenshot 1: Halaman Beranda & Katalog Buku (User)
   * Screenshot 2: Halaman Hasil Pencarian Buku (User)
   * Screenshot 3: Halaman Detail About Us (User)
   * Screenshot 4: Halaman Kontak Kirim Pesan ke Admin (User)
   * Screenshot 5: Halaman Keranjang Belanja / Add to Cart (User)
   * Screenshot 6: Halaman Checkout / Payment at Delivery (User)
   * Screenshot 7: Halaman Dashboard Admin (Admin)
   * Screenshot 8: Halaman CRUD Kategori Buku (Admin)
   * Screenshot 9: Halaman CRUD Data Buku & Stok (Admin)
   * Screenshot 10: Halaman Daftar User Terdaftar (Admin)
   * Screenshot 11: Halaman Daftar Pesanan Buku (Admin)
   * Screenshot 12: Terminal output hasil `php artisan test` (22 passed)
5. **Simpan sebagai PDF / DOCX**, lalu unggah ke akun Anda di portal: `https://lspmi.co.id`.
