<?php include '../koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tarif Gaji - Sistem Manajemen Gaji</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@5/dark.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-color: #f8f9fa;
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

        .table th,
        .table td {
            vertical-align: middle;
            text-align: center;
        }

        .card {
            border-radius: 1rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 0.4rem;
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
            <div class="card p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0">
                        <i class="bi bi-cash-stack text-success me-2"></i>Daftar Tarif Gaji
                    </h4>
                    <button class="btn btn-tambah mt-2" data-bs-toggle="modal" data-bs-target="#modalTambah">
                        <i class="bi bi-plus-circle me-1 color"></i> <span class="color">Tambah Gaji</span>
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Nama Karyawan</th>
                                <th>Bulan</th>
                                <th>Total Gaji</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                            <?php
                                $no = 1;
                                $query = mysqli_query($conn, "
                                    SELECT gaji.id, karyawan.nama, gaji.bulan, gaji.total_gaji 
                                    FROM gaji 
                                    JOIN karyawan ON gaji.karyawan_id = karyawan.id 
                                    ORDER BY gaji.bulan DESC
                                ");

                                if ($query && mysqli_num_rows($query) > 0) {
                                    while ($row = mysqli_fetch_assoc($query)) {
                                        $id = (int)$row['id'];
                                        $namaHTML = htmlspecialchars($row['nama']);
                                        $namaJS = htmlspecialchars($row['nama'], ENT_QUOTES); // aman untuk JS string
                                        $bulanHTML = htmlspecialchars($row['bulan']);
                                        $bulanJS = htmlspecialchars($row['bulan'], ENT_QUOTES); // aman untuk JS string
                                        $totalGaji = (int)$row['total_gaji'];
                                        $gajiFormat = number_format($totalGaji, 0, ',', '.');

                                        echo "
                                        <tr>
                                            <td>$no</td>
                                            <td class='text-start'>$namaHTML</td>
                                            <td>$bulanHTML</td>
                                            <td class='text-end'>Rp $gajiFormat</td>
                                            <td class='text-center'>
                                                <a href='javascript:void(0)' class='btn btn-warning btn-sm me-1'
                                                onclick=\"openEditModalGaji($id, '$namaJS', '$bulanJS', $totalGaji)\">
                                                    <i class='bi bi-pencil-square'></i> Edit
                                                </a>
                                                <a href='../gaji/gaji_detail.php?id=$id' class='btn btn-info btn-sm text-white me-1'>
                                                    <i class='bi bi-eye-fill'></i> Detail
                                                </a>
                                                <button type='button' class='btn btn-danger btn-sm'
                                                        onclick=\"hapusGaji($id, '$namaJS', '$bulanJS')\">
                                                    <i class='bi bi-trash-fill'></i> Hapus
                                                </button>
                                            </td>
                                        </tr>";
                                        $no++;
                                    }
                                } else {
                                    echo "<tr><td colspan='5' class='text-center'>Tidak ada data gaji</td></tr>";
                                }
                            ?>



                        </tbody>
                    </table>
                </div>

                <!-- Modal Tambah Gaji -->
                <div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content p-5">
                            <form id="formTambahGaji" novalidate>
                                <div class="mb-3">
                                    <label class="form-label">Nama Karyawan</label>
                                    <select name="karyawan_id" class="form-select" required>
                                        <option value="">-- Pilih --</option>
                                        <?php
                                        $res = mysqli_query($conn, "SELECT * FROM karyawan");
                                        while ($k = mysqli_fetch_assoc($res)) {
                                            echo "<option value='{$k['id']}'>{$k['nama']}</option>";
                                        }
                                        ?>
                                    </select>
                                    <div class="invalid-feedback">Silakan pilih karyawan.</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Bulan</label>
                                    <input type="text" name="bulan" class="form-control" required>
                                    <div class="invalid-feedback">Bulan harus diisi.</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Total Gaji</label>
                                    <input type="number" name="total_gaji" class="form-control" required min="0">
                                    <div class="invalid-feedback">Masukkan jumlah gaji yang valid.</div>
                                </div>
                                <button type="submit" name="simpan" class="btn btn-success w-100">
                                    <i class="bi bi-check-circle-fill me-1"></i> Simpan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Modal Edit Gaji -->
                <div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content p-5">
                            <form id="formEditGaji" novalidate>
                                <!-- Tambahkan hidden input di sini -->
                                <input type="hidden" id="edit-id" name="id">
                                <input type="hidden" id="edit-nama" name="nama">

                                <div class="mb-3">
                                    <label class="form-label">Bulan</label>
                                    <input type="text" name="bulan" class="form-control" id="edit-bulan" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Total Gaji</label>
                                    <input type="number" name="total_gaji" class="form-control" id="edit-total-gaji" required>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <a href="gaji.php" class="btn btn-secondary">
                                        <i class="bi bi-arrow-left"></i> Kembali
                                    </a>
                                    <button type="submit" name="update" class="btn btn-success">
                                        <i class="bi bi-save"></i> Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>



            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Validasi dan Submit Form Tambah Gaji -->
    <script>
        document.getElementById('formTambahGaji').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = e.target;

            if (!form.checkValidity()) {
                form.classList.add('was-validated');
                return;
            }

            const formData = new FormData(form);
            fetch('gaji_simpan.php', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.text())
                .then(response => {
                    if (response.trim() === "success") {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Data gaji berhasil disimpan.',
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
                        text: 'Terjadi kesalahan saat menyimpan data gaji.',
                    });
                });
        });

        //Edit Gaji
        function openEditModalGaji(id, nama, bulan, totalGaji) {
            document.getElementById('edit-id').value = id;
            document.getElementById('edit-nama').value = nama;
            document.getElementById('edit-bulan').value = bulan;
            document.getElementById('edit-total-gaji').value = totalGaji;
            const modalEdit = new bootstrap.Modal(document.getElementById('modalEdit'));
            modalEdit.show();
        }

        document.getElementById('formEditGaji').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);

            fetch('gaji_edit.php', {
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

        //Hapus Gaji
        function hapusGaji(id, nama, bulan) {
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
                    fetch(`gaji_hapus.php?id=${id}`)
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