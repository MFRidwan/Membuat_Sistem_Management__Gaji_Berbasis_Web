
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
      border: none;
      border-radius: 8px;
      padding: 8px 16px;
      color: #fff;
      transition: all 0.3s ease;
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
  </style>
</head>

<body>
  <div class="d-flex">
    <?php include '../includes/sidebar.php'; ?>
    <div class="p-4 w-100">
      <div class="card p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h4 class="mb-0"><i class="bi bi-cash-stack text-success me-2"></i>Daftar Tarif Gaji</h4>
          <button class="btn btn-tambah" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-circle me-1"></i> Tambah Gaji
          </button>
        </div>

        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover">
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
                  $namaJS = htmlspecialchars($row['nama'], ENT_QUOTES);
                  $bulanHTML = htmlspecialchars($row['bulan']);
                  $bulanJS = htmlspecialchars($row['bulan'], ENT_QUOTES);
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
        <div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content p-5">
              <form id="formTambahGaji" novalidate>
                <div class="row">
                  <!-- Kolom Kiri -->
                  <div class="col-md-6">
                    <!-- Nama Karyawan -->
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

                    <!-- Bulan -->
                    <div class="mb-3">
                      <label class="form-label">Bulan</label>
                      <input type="month" name="bulan" class="form-control" required>
                      <div class="invalid-feedback">Bulan harus diisi.</div>
                    </div>

                    <!-- Jumlah Jam Kerja -->
                    <div class="mb-3">
                      <label class="form-label">Jumlah Jam Kerja</label>
                      <input type="number" name="jumlah_jam" class="form-control" required min="0" step="0.1">
                      <div class="invalid-feedback">Masukkan jumlah jam kerja yang valid.</div>
                    </div>

                    <!-- Rating -->
                    <div class="mb-3">
                      <label class="form-label">Rating (1-5)</label>
                      <input type="number" name="rating" class="form-control" required min="1" max="5">
                      <div class="invalid-feedback">Masukkan rating antara 1 hingga 5.</div>
                    </div>
                  </div>

                  <!-- Kolom Kanan -->
                  <div class="col-md-6">
                    <!-- Tarif Lembur -->
                    <div class="mb-3">
                      <label class="form-label">Tarif Lembur Per Jam (Rp)</label>
                      <input type="number" name="tarif_lembur" class="form-control" required min="0">
                      <div class="invalid-feedback">Masukkan tarif lembur yang valid.</div>
                    </div>

                    <!-- Jumlah Jam Lembur -->
                    <div class="mb-3">
                      <label class="form-label">Jumlah Jam Lembur</label>
                      <input type="number" name="jumlah_jam_lembur" class="form-control" required min="0" step="0.1">
                      <div class="invalid-feedback">Masukkan jumlah jam lembur yang valid.</div>
                    </div>

                    <!-- Bonus -->
                    <div class="mb-3">
                      <label class="form-label">Bonus (Rp)</label>
                      <input type="number" name="bonus" class="form-control" required min="0">
                      <div class="invalid-feedback">Masukkan bonus yang valid.</div>
                    </div>
                  </div>
                </div>

                <!-- Total Gaji -->
                <div class="mb-3">
                  <label class="form-label">Total Gaji (Rp)</label>
                  <input type="number" name="total_gaji" class="form-control" readonly>
                </div>

                <!-- Tombol Simpan -->
                <button type="submit" class="btn btn-success w-100">
                  <i class="bi bi-check-circle-fill me-1"></i> Simpan
                </button>
              </form>
            </div>
          </div>
        </div>


        <!-- Modal Edit Gaji -->
        <div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-5">
              <form id="formEditGaji" novalidate>
                <input type="hidden" name="id" id="edit-id">
                <div class="mb-3">
                  <label class="form-label">Bulan</label>
                  <input type="text" name="bulan" id="edit-bulan" class="form-control" required>
                </div>
                <div class="mb-3">
                  <label class="form-label">Total Gaji</label>
                  <input type="number" name="total_gaji" id="edit-total-gaji" class="form-control" required>
                </div>
                <div class="d-flex justify-content-between">
                  <a href="gaji.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
                  <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Simpan Perubahan</button>
                </div>
              </form>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const jumlahJamInput = document.querySelector('input[name="jumlah_jam"]');
    const tarifLemburInput = document.querySelector('input[name="tarif_lembur"]');
    const jumlahJamLemburInput = document.querySelector('input[name="jumlah_jam_lembur"]');
    const bonusInput = document.querySelector('input[name="bonus"]');
    const totalGajiInput = document.querySelector('input[name="total_gaji"]');

    function hitungTotalGaji() {
      const jamKerja = parseFloat(jumlahJamInput.value) || 0;
      const tarifLembur = parseInt(tarifLemburInput.value) || 0;
      const jamLembur = parseFloat(jumlahJamLemburInput.value) || 0;
      const bonus = parseInt(bonusInput.value) || 0;
      const gajiPokok = jamKerja * 20000;
      const lembur = tarifLembur * jamLembur;
      const total = gajiPokok + lembur + bonus;
      totalGajiInput.value = total;
    }

    [jumlahJamInput, tarifLemburInput, jumlahJamLemburInput, bonusInput].forEach(input => {
      input.addEventListener('input', hitungTotalGaji);
    });

    document.getElementById('formTambahGaji').addEventListener('submit', function (e) {
      e.preventDefault();
      const form = e.target;
      if (!form.checkValidity()) {
        form.classList.add('was-validated');
        return;
      }
      const formData = new FormData(form);
      fetch('gaji_simpan.php', { method: 'POST', body: formData })
        .then(res => res.text())
        .then(response => {
          if (response.trim() === "success") {
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: 'Data gaji berhasil disimpan.' })
              .then(() => window.location.reload());
          } else {
            Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Terjadi kesalahan saat menyimpan data gaji.' });
          }
        });
    });

    function openEditModalGaji(id, nama, bulan, totalGaji) {
      document.getElementById('edit-id').value = id;
      document.getElementById('edit-bulan').value = bulan;
      document.getElementById('edit-total-gaji').value = totalGaji;
      new bootstrap.Modal(document.getElementById('modalEdit')).show();
    }

    document.getElementById('formEditGaji').addEventListener('submit', function (e) {
      e.preventDefault();
      const formData = new FormData(this);
      fetch('gaji_edit.php', { method: 'POST', body: formData })
        .then(res => res.text())
        .then(response => {
          if (response.trim() === "success") {
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: 'Data gaji berhasil diperbarui.' })
              .then(() => window.location.reload());
          } else {
            Swal.fire({ icon: 'error', title: 'Gagal!', text: response });
          }
        });
    });

    function hapusGaji(id, nama, bulan) {
      Swal.fire({
        title: 'Hapus Gaji?',
        text: `Yakin ingin menghapus gaji "${nama}" bulan "${bulan}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
      }).then(result => {
        if (result.isConfirmed) {
          fetch(`gaji_hapus.php?id=${id}`)
            .then(res => res.text())
            .then(response => {
              if (response.trim() === 'success') {
                Swal.fire({ icon: 'success', title: 'Berhasil!', text: `Data gaji berhasil dihapus.` })
                  .then(() => window.location.reload());
              } else {
                Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Terjadi kesalahan saat menghapus data.' });
              }
            });
        }
      });
    }
  </script>
</body>

</html>
 