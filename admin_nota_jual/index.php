<?php
require_once '../database/koneksi.php';
// $authority = @$_SESSION['peran'];
// if ($authority != 'S') {
//   echo '<script>alert("Akun Ini Bukan Cross Authority Akan Segera Di Logout");</script>';
//   echo '<script>window.location.href="../logout.php"</script>';
// } else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Nota Jual</title>

 <?php
 include '../css.php';

 $hal ='nota_jual';
 ?>
</head>
<body class="hold-transition with-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-with">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button">
         <i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
         <i class="far fa-bell"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-user mr-2"></i> Profil
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-sign-out-alt mr-2"></i> Logout
          </a>
        </div>
      </li>
    </ul>
  </nav>

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="info">
          <a href="#" class="d-block">POINT OF SALES</a>
        </div>
      </div>

    <div class="sidebar">
      <?php include '../sidebar_admin.php'; ?>
    </div>
  </aside>

  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid"></div>
    </div>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="card">
            <div class="card-header">
              <h3 class="card-title">Nota Jual</h3>
            </div>
            
            <div class="card-body">
              <button type="button" class="btn btn-success mb-2" data-toggle="modal" data-target="#modal-tambah"><i class="fas fa-plus"></i> Tambah Data</button>
              <a href="laporan_penjualan.php" class="btn btn-primary mb-2">Laporan Penjualan</a>
              <a href="laporan_laba.php" class="btn btn-primary mb-2">Laporan Laba Rugi</a>
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Kode Nota</th>
                  <th>Kode Supplier</th>
                  <th>Tanggal Penjualan</th>
                  <th>Total Penjualan</th>
                  <th>Metode Pembayaran</th>
                  <th>Status</th>
                  <th>Keterangan</th>
                  <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                  <?php
                    $panggil_data_nota = mysqli_query($koneksi, "SELECT * FROM tbl_nota_jual") or die(mysqli_error($koneksi));
                    $rv = mysqli_num_rows($panggil_data_nota);
                    if ($rv > 0) {
                      while ($data = mysqli_fetch_array($panggil_data_nota)) {
                        $kode_nota         = $data['kode_nota'];
                        $kode_supplier     = $data['kode_supplier'];
                        $tgl_penjualan     = $data['tgl_penjualan'];
                        $total_penjualan   = $data['total_penjualan'];
                        $metode_pembayaran = isset($data['metode_pembayaran']) ? $data['metode_pembayaran'] : '-';
                        $status            = $data['status'];
                        $keterangan        = $data['keterangan'];

                        // Subquery Manual Supplier (Tanpa JOIN)
                        $get_sup = mysqli_query($koneksi, "SELECT nama_supplier FROM tbl_supplier WHERE kode_supplier = '$kode_supplier'");
                        $data_sup = mysqli_fetch_array($get_sup);
                        $nama_supplier = isset($data_sup['nama_supplier']) ? $data_sup['nama_supplier'] : '-';
                        ?>
                        <tr>
                          <td><?= htmlspecialchars($kode_nota); ?></td>
                          <td><?= htmlspecialchars($kode_supplier); ?> - <?= htmlspecialchars($nama_supplier); ?></td>
                          <td><?= htmlspecialchars($tgl_penjualan); ?></td>
                          <td>Rp <?= number_format($total_penjualan, 0, ',', '.'); ?></td>
                          <td><?= htmlspecialchars($metode_pembayaran); ?></td>
                          <td><?= htmlspecialchars($status); ?></td>
                          <td><?= htmlspecialchars($keterangan); ?></td>
                          <td> 
                            <a href="../admin_detail_nota_jual?kode_nota=<?= $kode_nota; ?>" class="btn btn-secondary btn-sm"><i class="fas fa-eye"></i></a>

                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-edit<?= $kode_nota; ?>"><i class="fas fa-pen"></i></button>

                            <a href="hapus.php?kode_nota=<?= $kode_nota; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin Mau Hapus Data Ini?')"><i class="fas fa-trash"></i></a>
                          </td>
                        </tr>

                        <!-- Modal Edit Data -->
                        <div class="modal fade" id="modal-edit<?= $kode_nota; ?>">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h4 class="modal-title">Edit Data Nota Jual</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>

                              <form action="ubah.php" method="post" enctype="multipart/form-data">
                                <div class="modal-body">
                                  <div class="form-group">
                                    <label for="kode_nota">Kode Nota</label>
                                    <input type="number" class="form-control" value="<?= $kode_nota; ?>" disabled>
                                    <input type="hidden" name="kode_nota" value="<?= $kode_nota; ?>">
                                  </div>
                                  <div class="form-group">
                                    <label for="kode_supplier">Kode Supplier</label>
                                    <select class="form-control" name="kode_supplier" required>
                                      <option value="">-- Pilih Supplier --</option>
                                      <?php
                                      // Query Manual Supplier untuk Dropdown Edit (Tanpa JOIN)
                                      $q_sup = mysqli_query($koneksi, "SELECT * FROM tbl_supplier");
                                      while ($sup = mysqli_fetch_array($q_sup)) {
                                        $selected = ($sup['kode_supplier'] == $kode_supplier) ? 'selected' : '';
                                        echo '<option value="' . $sup['kode_supplier'] . '" ' . $selected . '>' . $sup['kode_supplier'] . ' - ' . $sup['nama_supplier'] . '</option>';
                                      }
                                      ?>
                                    </select>
                                  </div>
                                  <div class="form-group">
                                    <label for="tgl_penjualan">Tanggal Penjualan</label>
                                    <input type="date" name="tgl_penjualan" class="form-control" value="<?= $tgl_penjualan; ?>" required>
                                  </div>
                                  <div class="form-group">
                                    <label for="total_penjualan">Total Penjualan</label>
                                    <input type="number" name="total_penjualan" class="form-control" value="<?= $total_penjualan; ?>" placeholder="Masukan Total Penjualan" required>
                                  </div>
                                  <div class="form-group">
                                    <label for="metode_pembayaran">Metode Pembayaran</label>
                                    <select class="form-control" name="metode_pembayaran" required>
                                      <option value="">-- Pilih Metode Pembayaran --</option>
                                      <option value="Tunai" <?= ($metode_pembayaran == 'Tunai') ? 'selected' : ''; ?>>Tunai / Cash</option>
                                      <option value="Transfer" <?= ($metode_pembayaran == 'Transfer') ? 'selected' : ''; ?>>Transfer Bank</option>
                                      <option value="QRIS" <?= ($metode_pembayaran == 'QRIS') ? 'selected' : ''; ?>>QRIS</option>
                                    </select>
                                  </div>
                                  <div class="form-group">
                                    <label>Status</label>
                                    <select class="form-control" name="status">
                                      <option value="">-- Pilih Status --</option>
                                      <option value="L" <?= ($status == 'L') ? 'selected': '' ;?>>Lunas</option>
                                      <option value="2" <?= ($status == '2') ? 'selected': '' ;?>>25%</option>
                                      <option value="3" <?= ($status == '3') ? 'selected': '' ;?>>50%</option>
                                      <option value="4" <?= ($status == '4') ? 'selected': '' ;?>>75%</option>
                                    </select>
                                  </div>
                                  <div class="form-group">
                                    <label>Keterangan</label>
                                    <select class="form-control" name="keterangan">
                                      <option value="">-- Pilih Keterangan --</option>
                                      <option value="Lunas" <?= ($keterangan == 'Lunas') ? 'selected': '' ;?>>Lunas</option>
                                      <option value="Belum Lunas" <?= ($keterangan == 'Belum Lunas') ? 'selected': '' ;?>>Belum Lunas</option>
                                    </select>
                                  </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                  <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                                  <button type="submit" name="ubah_nota" class="btn btn-primary">Simpan</button>
                                </div>
                              </form>
                            </div>
                          </div>
                        </div>
                        <!-- /.modal edit -->

                        <?php
                      }
                    } else {
                      echo '<tr><td colspan="8" class="text-center">Data Tidak Ditemukan</td></tr>';
                    }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
      </div>
    </section>
  </div>

  <!-- Modal Tambah Data -->
  <div class="modal fade" id="modal-tambah">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Tambah Data Nota Jual</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <form action="tambah.php" method="post" enctype="multipart/form-data">
          <div class="modal-body">
            <div class="form-group">
              <label for="kode_nota">Kode Nota</label>
              <input type="number" name="kode_nota" class="form-control" placeholder="Masukan Kode Nota" required>
            </div>
            <div class="form-group">
              <label for="kode_supplier">Kode Supplier</label>
              <select class="form-control" name="kode_supplier" required>
                <option value="">-- Pilih Supplier --</option>
                <?php
                // Query Manual Supplier untuk Modal Tambah (Tanpa JOIN)
                $q_sup2 = mysqli_query($koneksi, "SELECT * FROM tbl_supplier");
                while ($sup2 = mysqli_fetch_array($q_sup2)) {
                  echo '<option value="' . $sup2['kode_supplier'] . '">' . $sup2['kode_supplier'] . ' - ' . $sup2['nama_supplier'] . '</option>';
                }
                ?>
              </select>
            </div>
            <div class="form-group">
              <label for="tgl_penjualan">Tanggal Penjualan</label>
              <input type="date" name="tgl_penjualan" class="form-control" required>
            </div>
            <div class="form-group">
              <label for="total_penjualan">Total Penjualan</label>
              <input type="number" name="total_penjualan" class="form-control" placeholder="Masukan Total Penjualan" required>
            </div>
            <div class="form-group">
              <label for="metode_pembayaran">Metode Pembayaran</label>
              <select class="form-control" name="metode_pembayaran" required>
                <option value="">-- Pilih Metode Pembayaran --</option>
                <option value="Tunai">Tunai / Cash</option>
                <option value="Transfer">Transfer Bank</option>
                <option value="QRIS">QRIS</option>
              </select>
            </div>
            <div class="form-group">
              <label>Status</label>
              <select class="form-control" name="status">
                <option value="">-- Pilih Status --</option>
                <option value="L">Lunas</option>
                <option value="2">25%</option>
                <option value="3">50%</option>
                <option value="4">75%</option>
              </select>
            </div>
            <div class="form-group">
              <label>Keterangan</label>
              <select class="form-control" name="keterangan">
                <option value="">-- Pilih Keterangan --</option>
                <option value="Lunas">Lunas</option>
                <option value="Belum Lunas">Belum Lunas</option>
              </select>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            <button type="submit" name="tambah_nota" class="btn btn-primary">Tambah</button>
          </div>
        </form>
      </div>
    </div>
  </div>

<?php include '../footer.php'; ?>
<?php include '../script.php'; ?>

</body>
</html>