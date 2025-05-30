<?php include '../koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Daftar Rating - Sistem Manajemen Gaji</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@5/dark.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }

        .judul-halaman {
            text-align: center;
            font-weight: bold;
            font-size: 28px;
            color: #0d6efd;
            margin-bottom: 20px;
        }

        .btn-primary {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .table thead {
            background-color: #343a40;
            color: white;
        }

        .table td,
        .table th {
            vertical-align: middle;
        }

        .btn-sm i {
            margin-right: 4px;
        }
        .btn-tambah {
            background: linear-gradient(90deg, #0d6efd, #3b82f6);
            color: white;
            font-weight: 500;
            border: none;
            border-radius: 8px;
            padding: 8px 16px;
            transition: all 0.3s ease;
            color: #fff;
        }
        .btn-tambah:hover {
            background: linear-gradient(90deg, #2563eb, #1d4ed8);
        }

        .color {
            color: #fff;
        }
    </style>
</head>

<body>
    <div class="d-flex">
        <?php include '../includes/sidebar.php'; ?>
        <div class="p-4 w-100">
            <h2 class="judul-halaman">
                <i class="bi bi-star-fill me-2"></i>Daftar Rating
            </h2>
            <div class="mb-3 text-end">
                <button class="btn btn-tambah mt-2" data-bs-toggle="modal" data-bs-target="#modalTambah">
                    <i class="bi bi-plus-circle me-1 color"></i> <span class="color">Tambah Rating</span>
                </button>
            </div>
            <div class="card shadow-sm">
                <div class="card-body table-responsive">
                    <table class="table table-striped table-bordered table-hover align-middle">
                        <thead>
                            <tr class="table-dark">
                                <th>No</th>
                                <th>Nama Karyawan</th>
                                <th>Bulan</th>
                                <th>Nilai Rating</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                           <?php
                            $no = 1;
                            $query = mysqli_query($conn, "SELECT rating.*, karyawan.nama, karyawan.id AS karyawan_id 
                                                                        FROM rating 
                                                                        JOIN karyawan ON rating.karyawan_id = karyawan.id 
                                                                        ORDER BY rating.id DESC");

                            if ($query && mysqli_num_rows($query) > 0) {
                                while ($row = mysqli_fetch_assoc($query)) {
                                    $bulanJS = htmlspecialchars($row['bulan'], ENT_QUOTES);
                                    $namaKaryawan = htmlspecialchars($row['nama'], ENT_QUOTES);
                                    $nilaiRating = htmlspecialchars($row['nilai_rating']); 
                                    $karyawanId = $row['karyawan_id']; 
                                    
                                    echo "
                                    <tr>
                                        <td>$no</td>
                                        <td>$namaKaryawan</td>
                                        <td>{$row['bulan']}</td>
                                        <td>$nilaiRating</td> <!-- Pastikan sesuai nama kolom -->
                                        <td class='text-center'>
                                            <a href='javascript:void(0)' class='btn btn-warning btn-sm me-1'
                                                onclick=\"openEditRatingModal(
                                                    {$row['id']},
                                                    {$row['karyawan_id']},
                                                    '{$bulanJS}',
                                                    $nilaiRating
                                                )\">
                                                <i class='bi bi-pencil-square'></i> Edit
                                            </a>
                                            <a href='../rating/rating_detail.php?id={$row['id']}' class='btn btn-info btn-sm text-white me-1'>
                                                <i class='bi bi-eye-fill'></i> Detail
                                            </a>
                                            <button class='btn btn-danger btn-sm' 
                                                onclick='hapusRating({$row['id']}, \"{$bulanJS}\")'>
                                                <i class='bi bi-trash'></i> Hapus
                                            </button>
                                        </td>
                                    </tr>";
                                    $no++;
                                }
                            } else {
                                echo "<tr><td colspan='5' class='text-center'>Tidak ada data rating</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- Modal Tambah Rating -->
                <div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content p-3">
                            <form id="formTambahRating" novalidate>
                                <div class="mb-3">
                                    <label for="karyawan_id" class="form-label">Nama Karyawan</label>
                                    <select name="karyawan_id" id="karyawan_id" class="form-select" required>
                                        <option value="">-- Pilih Karyawan --</option>
                                        <?php
                                        $data = mysqli_query($conn, "SELECT * FROM karyawan");
                                        while ($row = mysqli_fetch_assoc($data)) {
                                            echo "<option value='{$row['id']}'>{$row['nama']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="bulan" class="form-label">Bulan</label>
                                    <input type="month" name="bulan" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="nilai_rating" class="form-label">Nilai Rating</label>
                                    <input type="number" name="nilai_rating" class="form-control" min="1" max="5" required>
                                </div>
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="rating.php" class="btn btn-secondary">
                                        <i class="bi bi-arrow-left-circle"></i> Kembali
                                    </a>
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-check-circle"></i> Simpan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- End Modal Tambah Rating -->

                 <!-- Modal Edit Rating -->
                <div class="modal fade" id="modalEditRating" tabindex="-1" aria-labelledby="modalEditRatingLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content p-3">
                            <form id="formEditRating" novalidate>
                                <input type="hidden" name="id" id="edit-id-rating">
                                <h5 class="mb-3">Edit Rating Karyawan</h5>
                                
                                <div class="form-icon-group mb-3">
                                    <i class="bi bi-person-fill"></i>
                                    <select name="karyawan_id" id="edit-karyawan" class="form-select" required>
                                        <option value="">-- Pilih Karyawan --</option>
                                        <?php
                                        $karyawan = mysqli_query($conn, "SELECT * FROM karyawan");
                                        while ($row = mysqli_fetch_assoc($karyawan)) {
                                            echo "<option value='{$row['id']}'>{$row['nama']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="form-icon-group mb-3">
                                    <i class="bi bi-calendar-month"></i>
                                    <input type="month" name="bulan" id="edit-bulan" class="form-control" placeholder="Bulan" required>
                                </div>

                                <div class="form-icon-group mb-3">
                                    <i class="bi bi-star-fill"></i>
                                    <input type="number" name="nilai_rating" id="edit-rating" class="form-control" 
                                        placeholder="Nilai Rating" min="1" max="5" required>
                                </div>

                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-save me-1"></i> Simpan Perubahan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>

<!-- Validasi Form Tambah Jabatan -->
document.getElementById('formTambahRating').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = e.target;

    if (!form.checkValidity()) {
        form.classList.add('was-validated');
        return;
    }

    const formData = new FormData(form);
    fetch('rating_simpan.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.text())
    .then(response => {
        if (response.trim() === "success") {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Data jabatan berhasil disimpan.',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.reload();
            });
        } else {
            throw new Error();
        }
    })
    .catch(() => {
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: 'Terjadi kesalahan saat menyimpan.',
        });
    });
});

// Validasi Form Edit Rating
// Edit Rating
function openEditRatingModal(id, karyawanId, bulan, nilaiRating) {
    document.getElementById('edit-id-rating').value = id;
    
    // Set select karyawan
     document.getElementById('edit-karyawan').value = karyawanId;
    
    document.getElementById('edit-bulan').value = bulan;
    document.getElementById('edit-rating').value = nilaiRating;
    
    const modalEdit = new bootstrap.Modal(document.getElementById('modalEditRating'));
    modalEdit.show();
}

document.getElementById('formEditRating').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);

    fetch('rating_edit.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.text())
    .then(response => {
        if (response.trim() === "success") {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Rating berhasil diperbarui.',
            }).then(() => window.location.reload());
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: response,
            });
        }
    })
    .catch(() => {
        Swal.fire({
            icon: 'error',
            title: 'Kesalahan!',
            text: 'Terjadi kesalahan saat mengirim permintaan.',
        });
    });
});

// Hapus Rating
function hapusRating(id, nama) {
    Swal.fire({
        title: 'Hapus Jabatan?',
        text: `Yakin ingin menghapus jabatan "${nama}"? Data akan dihapus permanen.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`rating_hapus.php?id=${id}`)
            .then(res => res.text())
            .then(response => {
                if (response.trim() === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: `Jabatan "${nama}" berhasil dihapus.`,
                    }).then(() => window.location.reload());
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan saat menghapus data.',
                    });
                }
            })
            .catch(() => {
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan!',
                    text: 'Gagal terhubung ke server.',
                });
            });
        }
    });
}
</script>
</body>
</html>