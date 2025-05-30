# 📊 Sistem Manajemen Gaji Karyawan

Aplikasi web berbasis PHP yang digunakan untuk mengelola data karyawan, jabatan, rating kinerja, tarif lembur, dan penghitungan gaji karyawan.

## 📁 Fitur Utama

- **Dashboard** – Menampilkan ringkasan data dan kartu karyawan.
- **Manajemen Karyawan** – Tambah, edit, hapus, dan tampilkan daftar karyawan lengkap dengan foto.
- **Manajemen Jabatan** – CRUD data jabatan beserta gaji pokok.
- **Rating Kinerja** – Input dan kelola rating karyawan per bulan.
- **Tarif Lembur** – Atur tarif lembur berdasarkan jabatan.
- **Perhitungan Gaji** – Hitung gaji akhir berdasarkan gaji pokok, rating, dan lembur.
- **Export Data** – Ekspor data ke PDF dan Excel (opsional jika ditambahkan).
- **Login dan Hak Akses** – (jika sudah diterapkan) autentikasi pengguna.

## 🛠️ Teknologi yang Digunakan

- **Frontend**: HTML, CSS, Bootstrap
- **Backend**: PHP
- **Database**: MySQL
- **Tools**: XAMPP/Laragon, phpMyAdmin

## 📦 Struktur Folder

```
percobaanke-3/
├── dashboard.php
├── index.php
├── karyawan.php (+ edit, tambah, hapus)
├── jabatan.php (+ edit, tambah, hapus)
├── rating.php (+ edit, tambah, hapus)
├── lembur.php (+ edit, tambah, hapus)
├── gaji.php (+ edit, tambah, hapus)
├── uploads/ (folder penyimpanan foto)
└── koneksi.php (koneksi database)
```

## ⚙️ Cara Menjalankan

1. Clone atau download project ke folder `htdocs` (XAMPP) atau `www` (Laragon).
2. Buat database di `phpMyAdmin` sesuai dengan struktur yang digunakan (import file SQL jika ada).
3. Ubah konfigurasi database di file `koneksi.php` jika perlu.
4. Jalankan `localhost/percobaanke-3` di browser.

## 👨‍💼 Developer

- Nama: (M Fauzi Ridwan)
- Proyek tugas: Pak Fikri & Bu Susan
- Tanggal: 22 April 2025
