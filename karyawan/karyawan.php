<?php include '../koneksi.php'; ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Karyawan - Sistem Manajemen Gaji</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- SweetAlert2 Theme -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@5/dark.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <!-- AOS CSS -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .judul-dashboard {
            font-size: 26px;
            font-weight: bold;
            color: #0d6efd;
        }
        .subjudul-dashboard {
            font-size: 16px;
            color: #6c757d;
        }
        .btn-tambah {
            background: linear-gradient(90deg, #0d6efd, #3b82f6);
            color: white;
            font-weight: 500;
            border: none;
            border-radius: 8px;
            padding: 8px 16px;
            transition: all 0.3s ease;
        }
        .btn-tambah:hover {
            background: linear-gradient(90deg, #2563eb, #1d4ed8);
            color: #fff;
        }
        .foto-karyawan {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }
        .kartu-karyawan {
            border-radius: 12px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .kartu-karyawan:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }
        .card-title {
            font-size: 18px;
            font-weight: 600;
        }
        .badge-role {
            font-size: 13px;
        }
        .btn-detail, .btn-hapus {
            font-size: 14px;
            padding: 4px 12px;
        }
        .color {
            color: #ffff;
        }
        .modal-content {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.2);
            animation: fadeIn .3s ease;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        #foto-preview {
            max-height: 150px;
            display: none;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div class="d-flex">
    <?php include '../includes/sidebar.php'; ?>
    <div class="p-4 w-100">
        <div class="text-center mb-4">
            <h3 class="judul-dashboard mb-2 text-primary fw-bold">
                <i class="bi bi-person-badge-fill me-2"></i> Daftar Karyawan
            </h3>
            <p class="subjudul-dashboard">Lihat informasi karyawan terbaru dengan mudah dan cepat</p>
            <button class="btn btn-tambah mt-2" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="bi bi-plus-circle me-1 color"></i> <span class="color">Tambah Karyawan</span>
            </button>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            <?php
            $query = mysqli_query($conn, "SELECT karyawan.*, jabatan.nama_jabatan 
                                       FROM karyawan
                                       JOIN jabatan ON karyawan.jabatan_id = jabatan.id 
                                       ORDER BY karyawan.id DESC") or die(mysqli_error($conn));

            $bulan_ini = date('Y-m');
            while ($row = mysqli_fetch_assoc($query)) {
                $id_karyawan = $row['id'];
                $rating_q = mysqli_query($conn, "SELECT nilai_rating FROM rating WHERE karyawan_id = $id_karyawan AND bulan = '$bulan_ini'");
                $data_rating = mysqli_fetch_assoc($rating_q);
                $nilai_rating = $data_rating['nilai_rating'] ?? '-';
                $bintang = is_numeric($nilai_rating) ? str_repeat('⭐', $nilai_rating) : '-';

                $jabatan = strtolower($row['nama_jabatan']);
                $badge_class = match ($jabatan) {
                    'manager' => 'primary',
                    'supervisor' => 'secondary',
                    'staff' => 'success',
                    'admin' => 'danger',
                    default => 'dark'
                };

                echo '
                <div class="col">
                    <div class="card kartu-karyawan shadow-sm h-100">
                        <img src="../uploads/' . $row['foto'] . '" class="foto-karyawan card-img-top" alt="Foto">
                        <div class="card-body text-center">
                            <h5 class="card-title mb-1">' . htmlspecialchars($row['nama']) . '</h5>
                            <div class="text-warning mb-1">Rating: ' . $bintang . '</div>
                            <span class="badge bg-' . $badge_class . ' badge-role mb-2">' . htmlspecialchars($row['nama_jabatan']) . '</span><br>
                            
                            <a href="karyawan_detail.php?id=' . $id_karyawan . '" class="btn btn-outline-primary btn-sm me-1">Detail</a>
                            
                            <button class="btn btn-outline-warning btn-sm me-1 btn-edit"
                                data-id="' . $id_karyawan . '"
                                data-nama="' . htmlspecialchars($row['nama']) . '"
                                data-jenis-kelamin="' . $row['jenis_kelamin'] . '"
                                data-jabatan-id="' . $row['jabatan_id'] . '"
                                data-alamat="' . htmlspecialchars($row['alamat']) . '"
                                data-no-hp="' . $row['no_hp'] . '"
                                data-tanggal="' . $row['tanggal_bergabung'] . '"
                                data-rating="' . $nilai_rating . '"
                                data-foto="' . $row['foto'] . '">
                                Edit
                            </button>

                            <button class="btn btn-outline-danger btn-sm btn-hapus" data-id="' . $id_karyawan . '" data-nama="' . htmlspecialchars($row['nama']) . '">
                                Hapus
                            </button>
                        </div>
                    </div>
                </div>';
            }
            ?>
        </div>

        <!-- Modal Tambah Karyawan -->
        <div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content p-5">
                    <form id="formTambahKaryawan" novalidate>
                        <div class="container">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label">Nama</label>
                                    <input type="text" name="nama" class="form-control" required />
                                    <div class="invalid-feedback">Nama wajib diisi.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Umur</label>
                                    <input type="text" class="form-control" name="umur" required>
                                    <div class="invalid-feedback">Umur wajib diisi.</div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Jenis Kelamin</label>
                                    <select class="form-select" name="jenis_kelamin" required>
                                        <option value="">-- Pilih Jenis Kelamin --</option>
                                        <option value="Laki-laki">Laki-laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    </select>
                                    <div class="invalid-feedback">Jenis kelamin wajib dipilih.</div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="status" required>
                                        <option value="">-- Pilih Status --</option>
                                        <option value="Aktif">Aktif</option>
                                        <option value="Tidak aktif">Tidak aktif</option>
                                    </select>
                                    <div class="invalid-feedback">Status wajib dipilih.</div>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label">Jabatan</label>
                                    <select name="jabatan_id" class="form-select" required>
                                        <option value="">-- Pilih Jabatan --</option>
                                        <?php
                                        $jabatan = mysqli_query($conn, "SELECT * FROM jabatan");
                                        while ($row = mysqli_fetch_assoc($jabatan)) {
                                            echo '<option value="' . $row['id'] . '">' . htmlspecialchars($row['nama_jabatan']) . '</option>';
                                        }
                                        ?>
                                    </select>
                                    <div class="invalid-feedback">Jabatan wajib dipilih.</div>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label">Alamat</label>
                                    <textarea name="alamat" class="form-control" rows="3" required></textarea>
                                    <div class="invalid-feedback">Alamat wajib diisi.</div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">No HP</label>
                                    <input type="text" name="no_hp" class="form-control" required />
                                    <div class="invalid-feedback">No HP wajib diisi.</div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Tanggal Bergabung</label>
                                    <input type="date" name="tanggal_bergabung" class="form-control"
                                        max="<?= date('Y-m-d') ?>" required />
                                    <div class="invalid-feedback">Tanggal bergabung wajib diisi.</div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Foto</label>
                                    <input type="file" name="foto" class="form-control" accept="image/*" required />
                                    <div class="invalid-feedback">Foto wajib diupload.</div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Rating (1 - 5)</label>
                                    <select name="nilai_rating" class="form-select" required>
                                        <option value="">-- Pilih Rating --</option>
                                        <option value="1">1 - Sangat Buruk</option>
                                        <option value="2">2 - Buruk</option>
                                        <option value="3">3 - Cukup</option>
                                        <option value="4">4 - Baik</option>
                                        <option value="5">5 - Sangat Baik</option>
                                    </select>
                                    <div class="invalid-feedback">Rating wajib dipilih.</div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                <button type="submit" class="btn btn-success px-4">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Edit Karyawan -->
        <div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content p-5">
                    <form id="formEditKaryawan" novalidate enctype="multipart/form-data">
                        <input type="hidden" name="id" id="edit-id">
                        <div class="container">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label">Nama</label>
                                    <input type="text" name="nama" id="edit-nama" class="form-control" required>
                                    <div class="invalid-feedback">Nama wajib diisi.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Umur</label>
                                    <input type="text" name="umur" id="edit-umur" class="form-control" min="1" required value="<?= $data['umur'] ?>">
                                    <div class="invalid-feedback">Umur wajib diisi.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Jenis Kelamin</label>
                                    <select name="jenis_kelamin" id="edit-jenis-kelamin" class="form-select" required>
                                        <option value="Laki-laki">Laki-laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    </select>
                                    <div class="invalid-feedback">Jenis kelamin wajib dipilih.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Status</label>
                                    <select name="status" id="edit-status" class="form-select" required>
                                        <option value="Aktif">Aktif</option>
                                        <option value="Tidak aktif">Tidak aktif</option>
                                    </select>
                                    <div class="invalid-feedback">Status wajib dipilih.</div>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Jabatan</label>
                                    <select name="jabatan_id" id="edit-jabatan-id" class="form-select" required>
                                        <?php
                                        $jabatan = mysqli_query($conn, "SELECT * FROM jabatan");
                                        while ($row = mysqli_fetch_assoc($jabatan)) {
                                            echo "<option value='{$row['id']}'>{$row['nama_jabatan']}</option>";
                                        }
                                        ?>
                                    </select>
                                    <div class="invalid-feedback">Jabatan wajib dipilih.</div>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Alamat</label>
                                    <textarea name="alamat" id="edit-alamat" class="form-control" required></textarea>
                                    <div class="invalid-feedback">Alamat wajib diisi.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">No HP</label>
                                    <input type="text" name="no_hp" id="edit-no-hp" class="form-control" required>
                                    <div class="invalid-feedback">No HP wajib diisi.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Tanggal Bergabung</label>
                                    <input type="date" name="tanggal_bergabung" id="edit-tanggal" class="form-control" max="<?= date('Y-m-d') ?>" required>
                                    <div class="invalid-feedback">Tanggal bergabung wajib diisi.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Foto</label>
                                    <input type="file" id="edit-foto" name="foto" class="form-control" accept="image/*">
                                    <div class="mt-2">
                                        <img id="foto-preview" src="" class="img-thumbnail">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Rating (1-5)</label>
                                    <select name="nilai_rating" id="edit-rating" class="form-select" required>
                                        <option value="1">1 - Sangat Buruk</option>
                                        <option value="2">2 - Buruk</option>
                                        <option value="3">3 - Cukup</option>
                                        <option value="4">4 - Baik</option>
                                        <option value="5">5 - Sangat Baik</option>
                                    </select>
                                    <div class="invalid-feedback">Rating wajib dipilih.</div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>

// Form Tambah Karyawan
document.getElementById('formTambahKaryawan').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = e.target;
    
    if (!form.checkValidity()) {
        e.stopPropagation();
        form.classList.add('was-validated');
        return;
    }
    
    const formData = new FormData(form);
    
    fetch('karyawan_simpan.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.text())
    .then(response => {
        if (response.trim() === "success") {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Data karyawan berhasil disimpan.',
                confirmButtonText: 'OK'
            }).then(() => window.location.reload());
        } else {
            throw new Error(response);
        }
    })
    .catch(err => {
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: err.message || 'Terjadi kesalahan saat menyimpan data',
            confirmButtonColor: '#dc3545'
        });
    });
});

// Event Delegation untuk Tombol Edit
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('btn-edit')) {
        const btn = e.target;
        const modal = new bootstrap.Modal(document.getElementById('modalEdit'));
        
        // Isi form edit
        document.getElementById('edit-id').value = btn.dataset.id;
        document.getElementById('edit-nama').value = btn.dataset.nama;
        document.getElementById('edit-umur').value = btn.dataset.umur || '';
        document.getElementById('edit-jenis-kelamin').value = btn.dataset.jenisKelamin;
        document.getElementById('edit-jabatan-id').value = btn.dataset.jabatanId;
        document.getElementById('edit-alamat').value = btn.dataset.alamat;
        document.getElementById('edit-no-hp').value = btn.dataset.noHp;
        document.getElementById('edit-status').value = btn.dataset.status || 'Aktif';
        document.getElementById('edit-tanggal').value = btn.dataset.tanggal;
        document.getElementById('edit-rating').value = btn.dataset.rating || '3';
        
        // Tampilkan foto preview jika ada
        if (btn.dataset.foto) {
            const fotoPreview = document.getElementById('foto-preview');
            fotoPreview.src = '../uploads/' + btn.dataset.foto;
            fotoPreview.style.display = 'block';
        }
        
        modal.show();
    }
});

// Form Edit Karyawan
document.getElementById('formEditKaryawan').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = e.target;
    
    if (!form.checkValidity()) {
        e.stopPropagation();
        form.classList.add('was-validated');
        return;
    }
    
    const formData = new FormData(form);
    
    fetch('karyawan_edit.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.text())
    .then(response => {
        if (response.trim() === "success") {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Data karyawan berhasil diperbarui.',
                confirmButtonText: 'OK'
            }).then(() => window.location.reload());
        } else {
            throw new Error(response);
        }
    })
    .catch(err => {
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: err.message || 'Terjadi kesalahan saat memperbarui data',
            confirmButtonColor: '#dc3545'
        });
    });
});

// Event Delegation untuk Tombol Hapus
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('btn-hapus')) {
        const btn = e.target;
        const id = btn.dataset.id;
        const nama = btn.dataset.nama;
        
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: `Apakah Anda yakin ingin menghapus karyawan ${nama}?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`karyawan_hapus.php?id=${id}`)
                    .then(res => res.text())
                    .then(response => {
                        if (response.trim() === "success") {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Data karyawan berhasil dihapus.',
                                confirmButtonText: 'OK'
                            }).then(() => window.location.reload());
                        } else {
                            throw new Error(response);
                        }
                    })
                    .catch(err => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: err.message || 'Terjadi kesalahan saat menghapus data',
                            confirmButtonColor: '#dc3545'
                        });
                    });
            }
        });
    }
});
</script>
</body>
</html>