<?php
include 'koneksi.php';
include 'includes/header.php';
include 'includes/sidebar.php';

$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT k.*, j.nama_jabatan FROM karyawan k LEFT JOIN jabatan j ON k.jabatan_id = j.id WHERE k.id = $id");
$data = mysqli_fetch_array($query);

if (!$data) {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Data Tidak Ditemukan',
            text: 'Karyawan dengan ID tersebut tidak tersedia!',
            confirmButtonColor: '#3085d6'
        }).then(() => {
            window.location.href = '../karyawan/karyawan.php';
        });
    </script>";
    exit;
}

$rating_query = mysqli_query($conn, "SELECT nilai_rating FROM rating WHERE karyawan_id = $id ORDER BY bulan DESC LIMIT 1");
$rating_data = mysqli_fetch_array($rating_query);
$rating = isset($rating_data['nilai_rating']) ? $rating_data['nilai_rating'] : 0;

$jabatan = strtolower($data['nama_jabatan']);
$badgeClass = 'secondary';
if ($jabatan === 'manager') $badgeClass = 'primary';
elseif ($jabatan === 'staff') $badgeClass = 'success';
elseif ($jabatan === 'supervisor') $badgeClass = 'dark';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Karyawan</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9f9;
        }

        .card {
            border-radius: 1rem;
        }

        .detail-label {
            font-weight: 600;
            color: #333;
            width: 40%;
        }

        .detail-value {
            color: #555;
        }

        .img-box {
            padding: 15px;
        }

        .back-btn {
            margin-top: 30px;
        }

        .wrapper {
            margin-left: 320px;
        }
    </style>
</head>
<body>
<div class="container my-5 wrapper"  data-aos="fade-up">
    <h2 class="text-center fw-bold mb-4 text-uppercase text-primary" data-aos="fade-down">Detail Karyawan</h2>

    <div class="card shadow-lg p-4" data-aos="zoom-in">
        <div class="row g-4">
            <!-- FOTO -->
            <div class="col-md-4 text-center border-end d-flex flex-column align-items-center justify-content-center img-box">
                <?php
                $foto_path = "uploads/" . $data['foto'];
                if (!empty($data['foto']) && file_exists($foto_path)): ?>
                    <img src="<?= $foto_path ?>" class="img-fluid rounded shadow-sm mb-3" style="max-height: 300px; width: auto; object-fit: cover;" alt="Foto Karyawan">
                <?php else: ?>
                    <script>
                        Swal.fire({
                            icon: 'warning',
                            title: 'Foto Tidak Tersedia',
                            text: 'Foto karyawan ini tidak ditemukan!',
                            confirmButtonColor: '#f39c12'
                        });
                    </script>
                    <div class="bg-light text-muted py-5 px-3 rounded">Foto tidak tersedia</div>
                <?php endif; ?>

                <div class="mt-3">
                    <strong class="d-block mb-1">Rating:</strong>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <span style="font-size: 20px;" class="<?= $i <= $rating ? 'text-warning' : 'text-muted' ?>">&#9733;</span>
                    <?php endfor; ?>
                </div>
            </div>

            <!-- INFORMASI -->
            <div class="col-md-8 px-4">
                <table class="table table-borderless">
                    <tr><td class="detail-label">Nama</td><td class="detail-value">: <?= $data['nama'] ?></td></tr>
                    <tr><td class="detail-label">Umur</td><td class="detail-value">: <?= $data['umur'] ?> tahun</td></tr>
                    <tr><td class="detail-label">Jenis Kelamin</td><td class="detail-value">: <?= $data['jenis_kelamin'] ?></td></tr>
                    <tr><td class="detail-label">Alamat</td><td class="detail-value">: <?= $data['alamat'] ?></td></tr>
                    <tr><td class="detail-label">No. Telp</td><td class="detail-value">: <?= $data['no_hp'] ?></td></tr>
                    <tr>
                        <td class="detail-label">Status</td>
                        <td class="detail-value">:
                            <?php if ($data['status'] === 'Aktif'): ?>
                                <span class="badge bg-success">Aktif</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Tidak Aktif</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="detail-label">Jabatan</td>
                        <td class="detail-value">: <span class="badge bg-<?= $badgeClass ?> px-3 py-1"><?= $data['nama_jabatan'] ?></span></td>
                    </tr>
                    <tr><td class="detail-label">Tanggal Bergabung</td><td class="detail-value">: <?= date('d F Y', strtotime($data['tanggal_bergabung'])) ?></td></tr>
                </table>

                <div class="d-flex gap-2 mt-4 back-btn">
                    <button class="btn btn-primary me-1 btn-edit"
                        data-id="<?= $data['id'] ?>"
                        data-nama="<?= htmlspecialchars($data['nama']) ?>"
                        data-umur="<?= $data['umur'] ?>"
                        data-jenis-kelamin="<?= $data['jenis_kelamin'] ?>"
                        data-jabatan-id="<?= $data['jabatan_id'] ?>"
                        data-alamat="<?= htmlspecialchars($data['alamat']) ?>"
                        data-no-hp="<?= $data['no_hp'] ?>"
                        data-status="<?= $data['status'] ?>"
                        data-tanggal="<?= $data['tanggal_bergabung'] ?>"
                        data-rating="<?= $rating ?>"
                        data-foto="<?= $data['foto'] ?>">
                        Edit
                    </button>
                    <a href="dashboard.php" class="btn btn-secondary">← Kembali</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!--- edit karyawan detail ----->
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

<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    AOS.init();
</script>

<script>
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
        
        fetch('/karyawan/karyawan_edit.php', {
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
</script>
</body>
</html>