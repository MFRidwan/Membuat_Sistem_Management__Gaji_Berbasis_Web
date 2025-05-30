<?php include '../koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Jabatan - Sistem Manajemen Gaji</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@5/dark.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { background-color: #f8f9fa; }
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
            color: #fff;
        }
        .btn-tambah:hover {
            background: linear-gradient(90deg, #2563eb, #1d4ed8);
        }
        .modal-content {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.2);
            animation: fadeIn .3s ease;
            width: 5000px;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .form-icon-group {
            position: relative;
        }
        .form-icon-group i {
            position: absolute;
            top: 50%;
            left: 12px;
            transform: translateY(-50%);
            color: #6c757d;
        }
        .form-icon-group input {
            padding-left: 36px;
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
        <div class="text-center mb-4">
            <h3 class="judul-dashboard mb-2">
                <i class="bi bi-briefcase-fill me-2"></i> Daftar Jabatan
            </h3>
            <p class="subjudul-dashboard">Berikut adalah daftar jabatan dan gaji pokok masing-masing</p>
            <button class="btn btn-tambah mt-2" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="bi bi-plus-circle me-1 color"></i> <span class="color">Tambah Jabatan</span>
            </button>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover align-middle">
                        <thead>
                            <tr class="table-dark">
                                <th>No</th>
                                <th>Nama Jabatan</th>
                                <th>Gaji Pokok</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $query = mysqli_query($conn, "SELECT * FROM jabatan");
                            if ($query && mysqli_num_rows($query) > 0) {
                                while ($row = mysqli_fetch_assoc($query)) {
                                    $gaji = number_format($row['gaji_pokok'], 0, ',', '.');
                                    $namaJS = htmlspecialchars($row['nama_jabatan'], ENT_QUOTES);
                                    echo "
                                    <tr>
                                        <td>$no</td>
                                        <td>{$row['nama_jabatan']}</td>
                                        <td>Rp $gaji</td>
                                        <td class='text-center'>
                                            <a href='javascript:void(0)' class='btn btn-warning btn-sm me-1'
                                               onclick=\"openEditModal({$row['id']}, '{$namaJS}', {$row['gaji_pokok']})\">
                                                <i class='bi bi-pencil-square'></i> Edit
                                            </a>
                                            <a href='../jabatan/jabatan_detail.php?id={$row['id']}' class='btn btn-info btn-sm text-white me-1'>
                                                <i class='bi bi-info-circle'></i> Detail
                                            </a>
                                            <button class='btn btn-danger btn-sm' onclick='hapusJabatan({$row['id']}, \"{$namaJS}\")'>
                                                <i class='bi bi-trash'></i> Hapus
                                            </button>
                                        </td>
                                    </tr>";
                                    $no++;
                                }
                            } else {
                                echo "<tr><td colspan='4' class='text-center'>Tidak ada data</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal Tambah Jabatan -->
        <div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content p-5">
                    <form id="formTambahJabatan" novalidate>
                        <h5 class="mb-3">Tambah Jabatan</h5>
                        <div class="form-icon-group mb-3">
                            <i class="bi bi-briefcase-fill"></i>
                            <input type="text" name="nama_jabatan" class="form-control" placeholder="Nama Jabatan" required>
                        </div>
                        <div class="form-icon-group mb-3">
                            <i class="bi bi-cash-coin"></i>
                            <input type="number" name="gaji_pokok" class="form-control" placeholder="Gaji Pokok" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-save me-1"></i> Simpan
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Edit Jabatan -->
        <div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content p-5">
                    <form id="formEditJabatan" novalidate>
                        <input type="hidden" name="id" id="edit-id">
                        <h5 class="mb-3">Edit Jabatan</h5>
                        <div class="form-icon-group mb-3">
                            <i class="bi bi-briefcase-fill"></i>
                            <input type="text" name="nama_jabatan" id="edit-nama" class="form-control" placeholder="Nama Jabatan" required>
                        </div>
                        <div class="form-icon-group mb-3">
                            <i class="bi bi-cash-coin"></i>
                            <input type="number" name="gaji_pokok" id="edit-gaji" class="form-control" placeholder="Gaji Pokok" required>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>

<!-- Validasi Form Tambah Jabatan -->
document.getElementById('formTambahJabatan').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = e.target;

    if (!form.checkValidity()) {
        form.classList.add('was-validated');
        return;
    }

    const formData = new FormData(form);
    fetch('jabatan_simpan.php', {
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

//Edit Jabatan
function openEditModal(id, nama, gaji) {
    document.getElementById('edit-id').value = id;
    document.getElementById('edit-nama').value = nama;
    document.getElementById('edit-gaji').value = gaji;
    const modalEdit = new bootstrap.Modal(document.getElementById('modalEdit'));
    modalEdit.show();
}

document.getElementById('formEditJabatan').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);

    fetch('jabatan_edit.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.text())
    .then(response => {
        if (response.trim() === "success") {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Jabatan berhasil diperbarui.',
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

//Hapus Jabatan
function hapusJabatan(id, nama) {
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
            fetch(`jabatan_hapus.php?id=${id}`)
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
