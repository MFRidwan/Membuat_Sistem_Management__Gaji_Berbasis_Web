# 💼 Sistem Manajemen Gaji Berbasis Web

![License](https://img.shields.io/badge/license-MIT-green.svg)
![Status](https://img.shields.io/badge/status-development-orange)
![Made with](https://img.shields.io/badge/made%20with-Love-red)

Sistem Manajemen Gaji berbasis Web adalah aplikasi modern untuk membantu divisi HR dan perusahaan dalam mengelola data karyawan dan proses penggajian secara efisien, aman, dan terstruktur. Dengan antarmuka pengguna yang interaktif dan fitur lengkap, sistem ini menjadi solusi ideal untuk digitalisasi proses payroll.

---

## 🚀 Fitur Unggulan

- ✅ **Manajemen Karyawan**  
  Tambah, edit, dan hapus data karyawan dengan mudah dari antarmuka pengguna yang ramah.

- 💸 **Perhitungan Gaji Otomatis**  
  Hitung total gaji berdasarkan gaji pokok, tunjangan, potongan, dan bonus secara real-time.

- 🎨 **Antarmuka Modern + Dark Mode**  
  Desain UI terkini dengan dukungan tema gelap dan efek animasi halus.

- 🔐 **Sistem Login Admin**  
  Autentikasi untuk memastikan hanya admin yang dapat mengakses data penting.

- ⚡ **Validasi dan Notifikasi Live**  
  Validasi form secara langsung dan alert notifikasi animatif seperti Toast/SweetAlert.

---

## 🧱 Teknologi yang Digunakan

| Komponen     | Teknologi                    |
|--------------|------------------------------|
| Frontend     | HTML5, CSS3, JavaScript      |
| UI Framework | Tailwind CSS / Bootstrap     |
| Backend      | PHP (Native)                 |
| Database     | MySQL                        |
| Library UI   | SweetAlert2, Animate.css     |
| Server Lokal | XAMPP / Laragon              |

---

## 🖼️ Cuplikan Tampilan

> *(Tambahkan gambar atau screenshot proyek di sini untuk memberikan gambaran visual)*  
> ![Dashboard](screenshots/dashboard.png)  
> ![Form Gaji](screenshots/form-gaji.png)

---

## ⚙️ Cara Install di Lokal

1. **Clone Repository**
   ```bash
   git clone https://github.com/username/sistem-manajemen-gaji.git
   ```

2. **Pindahkan ke Folder `htdocs` (jika menggunakan XAMPP)**

3. **Import Database**
   - Buka `phpMyAdmin`
   - Buat database baru, contoh: `db_gaji`
   - Import file `management_gaji.sql` dari folder proyek

4. **Konfigurasi Koneksi Database**
   Buka file:
   ```php
   /config/koneksi.php
   ```
   Dan sesuaikan:
   ```php
   $host = "localhost";
   $user = "root";
   $pass = "";
   $db   = "management_gaji";
   ```

5. **Jalankan di Browser**
   ```
   http://localhost/sistem-manajemen-gaji/
   ```

---

## 👥 Role & Login

- **Admin Login**  
  - Username: `admin`  
  - Password: `admin123`  
  *(Catatan: ubah password setelah login pertama.)*

---

## 📂 Struktur Direktori (Contoh)

```
sistem-manajemen-gaji/
│
├── assets/              # File CSS, JS, dan gambar
├── config/              # Koneksi database
├── pages/               # Halaman utama sistem
├── includes/            # Komponen reusable (sidebar, navbar, dll)
├── database/            # File SQL
├── index.php            # Halaman awal
└── README.md
```

---

## 🙌 Kontribusi

Kontribusi sangat terbuka!  
Silakan fork repositori ini, buat perubahanmu, dan kirimkan pull request.  
Jangan ragu juga untuk membuat issue jika ada bug atau request fitur baru.

---

## 📜 Lisensi

Kode ini dilisensikan di bawah lisensi MIT.  
Silakan gunakan, modifikasi, dan distribusikan bebas – dengan menyertakan atribusi yang sesuai.

---

## ❤️ Dukungan

Jika proyek ini bermanfaat, bantu dengan:
- Memberi ⭐ di GitHub
- Membagikan ke rekan developer lainnya
- Memberi saran atau ide fitur tambahan

---

> Dibuat dengan 💻 oleh [ M Fauzi Ridwan ] – 2025  
> [LinkedIn](https://linkedin.com/in/mfauzi-ridwan-9b736b329/) • [GitHub](https://github.com/MFRidwan)
