<?php include '../koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tarif Lembur - Sistem Manajemen Gaji</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@5/dark.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }

        .table thead th {
            vertical-align: middle;
        }

        .table td,
        .table th {
            vertical-align: middle;
        }

        .btn-icon {
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .color {
            color: #fff;
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
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
            animation: fadeIn .3s ease;
            width: 5000px;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-icon-group {
            position: relative;
        }
    </style>
</head>

<body>
    <div class="d-flex">
        <?php include '../includes/sidebar.php'; ?>
        <div class="p-4 w-100">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="text-success"><i class="bi bi-clock-history me-2"></i>Daftar Tarif Lembur</h3>
                <button class="btn btn-tambah mt-2" data-bs-toggle="modal" data-bs-target="#modalTambah">
                    <i class="bi bi-plus-circle me-1 color"></i> <span class="color">Tambah Lembur</span>
                </button>
            </div>
            <div class="table-responsive shadow rounded bg-white p-3">
                <table class="table table-striped table-bordered table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama Jabatan</th>
                            <th>Tarif Per Jam</th>
                            <th>Jumlah Jam</th>
                            <th>Total Lembur</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $query = mysqli_query($conn, "SELECT lembur.*, jabatan.nama_jabatan FROM lembur 
                                                    JOIN jabatan ON lembur.jabatan_id = jabatan.id 
                                                    ORDER BY lembur.id DESC");
                        if ($query && mysqli_num_rows($query) > 0) {
                            while ($row = mysqli_fetch_assoc($query)) {
                                $total = $row['tarif_per_jam'] * $row['jumlah_jam'];
                                $tarif = number_format($row['tarif_per_jam'], 0, ',', '.');
                                $totalFormatted = number_format($total, 0, ',', '.');
                                $namaJabatan = htmlspecialchars($row['nama_jabatan'], ENT_QUOTES);
                                echo "
                                <tr>
                                    <td>$no</td>
                                    <td>{$row['nama_jabatan']}</td>
                                    <td>Rp $tarif</td>
                                    <td>{$row['jumlah_jam']} jam</td>
                                    <td>Rp $totalFormatted</td>
                                    <td class='text-center'>
                                        <a href='javascript:void(0)' class='btn btn-warning btn-sm me-1'
                                            onclick=\"openEditLemburModal({$row['id']}, {$row['jabatan_id']}, {$row['tarif_per_jam']}, {$row['jumlah_jam']})\">
                                            <i class='bi bi-pencil-square'></i> Edit
                                        </a>
                                        <a href='../lembur/lembur_detail.php?id={$row['id']}' class='btn btn-info btn-sm text-white me-1'>
                                            <i class='bi bi-info-circle'></i> Detail
                                        </a>
                                        <button class='btn btn-danger btn-sm' onclick='hapusLembur({$row['id']}, \"{$namaJabatan}\")'>
                                            <i class='bi bi-trash'></i> Hapus
                                        </button>
                                    </td>
                                </tr>";
                                $no++;
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center'>Tidak ada data lembur</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>

            </div>

            <!--- Tambah Lembur ----->
            <div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content p-5">
                        <form id="formTambahLembur" novalidate>
                            <div class="mb-3">
                                <label for="jabatan_id" class="form-label">Jabatan</label>
                                <select name="jabatan_id" class="form-select" required>
                                    <option value="">-- Pilih Jabatan --</option>
                                    <?php
                                    $jabatan = mysqli_query($conn, "SELECT * FROM jabatan");
                                    while ($j = mysqli_fetch_assoc($jabatan)) {
                                        echo '<option value="' . $j['id'] . '">' . $j['nama_jabatan'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tarif Per Jam</label>
                                <input type="number" name="tarif_per_jam" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Jumlah Jam</label>
                                <input type="number" name="jumlah_jam" class="form-control" required>
                            </div>
                            <button type="submit" name="simpan" class="btn btn-primary w-100">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
            <!--- End Tambah Lembur ----->

            <!-- Modal Edit Jabatan -->
            <div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content p-5">
                        <form id="formEditLembur" novalidate>
                            <!-- Hidden input for ID -->
                            <input type="hidden" name="id" id="edit-id">

                            <div class="mb-3">
                                <label for="edit-jabatan-id" class="form-label">Jabatan</label>
                                <select name="jabatan_id" id="edit-jabatan-id" class="form-select" required>
                                    <?php
                                    $jabatan = mysqli_query($conn, "SELECT * FROM jabatan");
                                    while ($j = mysqli_fetch_assoc($jabatan)) {
                                        echo '<option value="' . $j['id'] . '">' . $j['nama_jabatan'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tarif Per Jam (Rp)</label>
                                <input type="number" name="tarif_per_jam" id="edit-tarif" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Jumlah Jam</label>
                                <input type="number" name="jumlah_jam" id="edit-jumlah-jam" class="form-control" required>
                            </div>
                            <div class="d-flex justify-content-between">
                                <a href="../lembur/lembur.php" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left-circle"></i> Kembali
                                </a>
                                <button type="submit" name="update" class="btn btn-success">
                                    <i class="bi bi-save-fill"></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>

    </div>
    </div>

    <!-- Bootstrap JS (Opsional jika butuh komponen dinamis) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.getElementById('formTambahLembur').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = e.target;

            if (!form.checkValidity()) {
                form.classList.add('was-validated');
                return;
            }

            const formData = new FormData(form);
            fetch('lembur_simpan.php', {
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

        // Edit lembur
        function openEditLemburModal(id, jabatanId, tarif, jumlahJam) {
            document.getElementById('edit-id').value = id;
            document.getElementById('edit-jabatan-id').value = jabatanId;
            document.getElementById('edit-tarif').value = tarif;
            document.getElementById('edit-jumlah-jam').value = jumlahJam;

            const modal = new bootstrap.Modal(document.getElementById('modalEdit'));
            modal.show();
        }

        document.getElementById('formEditLembur').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);

            fetch('lembur_edit.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.text())
            .then(response => {
                if (response.trim() === "success") {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Data lembur berhasil diperbarui.',
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
        function hapusLembur(id, nama) {
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
                    fetch(`lembur_hapus.php?id=${id}`)
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