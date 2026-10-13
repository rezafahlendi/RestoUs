# RestoUs — Sistem Informasi Reservasi Meja & Pemesanan Menu Restoran

<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="280" alt="Laravel Logo">
</p>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.3">
  <img src="https://img.shields.io/badge/Framework-Laravel%2011-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 11">
  <img src="https://img.shields.io/badge/Database-MySQL%208.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL 8.0">
  <img src="https://img.shields.io/badge/Frontend-Bootstrap%205.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white" alt="Bootstrap 5.3">
  <img src="https://img.shields.io/badge/Tests-9%20Passed%20(38%20Assertions)-success?style=for-the-badge&logo=phpunit&logoColor=white" alt="Tests Passed">
  <img src="https://img.shields.io/badge/License-MIT-blue?style=for-the-badge" alt="MIT License">
</p>

---

## 📌 Tentang RestoUs

**RestoUs** adalah aplikasi berbasis web yang dirancang untuk mengatasi permasalahan antrean fisik, tabrakan pemakaian meja makan, dan ketidakpastian stok bahan baku pada restoran. 

Aplikasi ini dibangun sebagai **portofolio resmi Asesmen Sertifikasi Kompetensi BNSP (Badan Nasional Sertifikasi Profesi) Skema Pemrogram (Programmer)** oleh asesi **Reza**, mengacu pada dokumen spesifikasi formal **[SKPL-RestoUs-01.md](SKPL-RestoUs-01.md)** (mengadopsi standar *IEEE Std 830-1998*) dan Standar Kompetensi Kerja Nasional Indonesia (**SKKNI**).

---

## 🏆 4 Aturan Emas Bisnis (*Core Business Rules*)

RestoUs menerapkan 4 pilar logika bisnis utama yang tervalidasi secara otomatis oleh sistem:

1. 📅 **Minimal Reservasi H-1 (`SKPL-R-05`)**  
   Pelanggan tidak dapat memesan meja di hari H atau tanggal lampau guna memberikan waktu persiapan bahan baku segar bagi dapur restoran. Jam kedatangan dibatasi pada rentang operasional **10:00 s/d 19:00 WIB**.
2. ⏱️ **Durasi Santap Maksimal 2 Jam (`SKPL-R-06`)**  
   Mencegah tabrakan pemesanan meja (*anti-double booking*). Sistem menjalankan algoritma persinggungan interval waktu untuk menonaktifkan (*disabled*) meja yang telah dipesan pada rentang $\pm 2\text{ jam}$ bersinggungan.
3. 🍲 **Batas Kuota Harian 50 Porsi/Menu (`SKPL-R-07`)**  
   Setiap hidangan dibatasi maksimal 50 porsi per hari untuk menjamin kualitas kesegaran makanan. Sisa kuota dihitung dinamis berdasarkan pesanan aktif pada tanggal tersebut.
4. ⏳ **Auto-Cancel 1 Jam & Direct WhatsApp (`SKPL-R-08` & `SKPL-R-09`)**  
   Pesanan baru berstatus `Pending` dengan tenggat waktu pembayaran **60 menit**. Jika melewati batas tanpa verifikasi admin, sistem otomatis mengubah status menjadi `Cancelled` untuk melepaskan meja kembali bagi pelanggan lain. Konfirmasi pembayaran diarahkan langsung ke WhatsApp resmi **0822-5478-1995**.

---

## 🚀 Fitur Utama Sistem

### 👤 Portal Pelanggan (*Customer*)
* **Landing Page & Tata Tertib**: Halaman pengenalan restoran dan transparansi 5 tata tertib operasional (`/rules`).
* **Katalog Menu & Sisa Kuota Dinamis**: Daftar makanan dan minuman lengkap dengan penanda kuota porsi harian berbasis tanggal terpilih.
* **Pemesanan Meja Real-time**: Formulir booking dengan pengecekan ketersediaan meja interaktif (AJAX).
* **Invoice & Countdown Pembayaran**: Halaman nota reservasi dengan penghitung mundur batas bayar 1 jam dan generator link Direct WhatsApp dengan draf rincian pesanan siap kirim.
* **Cetak & Unduh Faktur / Invoice PDF (`SKPL-R-11`)**: Unduh salinan faktur resmi berstandar cetak A4 dengan watermark status lunas dan rincian pemesanan.
* **Lacak Riwayat Reservasi**: Pengecekan status booking pelanggan cukup dengan memasukkan nomor telepon.

### 🛡️ Dasbor Super Admin (`/admin`)
* **Proteksi Hak Akses (Middleware)**: Jalur panel admin terproteksi ketat; akses oleh pengguna umum/customer otomatis ditolak.
* **Manajemen Reservasi Transaksional**: Verifikasi bukti bayar WhatsApp, perubahan status (`Pending` $\rightarrow$ `Confirmed` $\rightarrow$ `Completed` $\rightarrow$ `Cancelled`).
* **Manajemen Menu & Kuota Harian**: Pengaturan harga porsi, ketersediaan menu, dan batas porsi per hari.
* **Manajemen Meja Restoran**: Pemantauan kapasitas meja dan ketersediaan meja makan.

---

## 🛠️ Arsitektur & Teknologi (*Tech Stack*)

| Komponen | Teknologi yang Digunakan | Penjelasan |
| :--- | :--- | :--- |
| **Bahasa Pemrograman** | PHP 8.3+ | Mengikuti standar penulisan **PSR-12** (*Clean Code*). |
| **Framework Backend** | Laravel 11 / 13 | Arsitektur **MVC**, Routing, Middleware, Eloquent ORM. |
| **Basis Data** | MySQL 8.0+ | Database Relasional dengan Foreign Key constraint & indexing. |
| **Manipulasi Waktu** | Carbon Library | Penanganan interval durasi 2 jam, batas H-1, dan countdown 1 jam. |
| **Frontend UI** | Blade + Bootstrap 5.3 + Bootstrap Icons | Antarmuka responsif dan estetik tanpa dependensi CSS eksternal berat. |
| **Otomasi Pengujian** | PHPUnit Framework | Pengujian otomatis skenario bisnis via `php artisan test`. |
| **Manajemen Library** | Composer | Pengelolaan dependensi berbasis open-source legal (**MIT License**). |

---


## 💻 Panduan Instalasi & Menjalankan Sistem

### 1. Prasyarat Sistem
* PHP $\ge$ 8.3 dengan ekstensi: `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`.
* Composer $\ge$ 2.5
* MySQL Database $\ge$ 8.0 (XAMPP / Laragon / MySQL Service)







