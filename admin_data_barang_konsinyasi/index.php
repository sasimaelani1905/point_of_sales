<?php
require_once '../database/koneksi.php';

// $authority = @$_SESSION['peran'];
// if ($authority != 'S') {
//     echo '<script>alert("Akun Ini Bukan Cross Authority Akan Segera Di Logout");</script>';
//     echo '<script>window.location.href="../logout.php"</script>';
// } else {
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Data Barang Konsinyasi</title>

  <?php
  include '../css.php';
  $hal = 'admin_konsinyasi';
  ?>
  <style>
    /* Mengatur ukuran font tabel sedikit lebih ringkas agar pas di layar */
    .table-custom th, .table-custom td {
      padding: 6px 8px !important;
      vertical-align: middle !important;
      font-size: 0.88rem;
    }
  </style>
</head>

<body class="hold-transition with-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-with">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button">
          <i class="fas fa-bars"></i>
        </a>
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
  <!-- /.navbar -->

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
            <h3 class="card-title">Data Barang Konsinyasi</h3>
          </div>
          
          <div class="card-body">
            <button type="button" class="btn btn-success btn-sm mb-3" data-toggle="modal" data-target="#modal-tambah">
              <i class="fas fa-plus"></i> Tambah Data
            </button>

            <div class="table-responsive">
              <table id="example1" class="table table-bordered table-striped table-custom w-100">
                <thead>
                  <tr>
                    <th style="width: 7%;" class="text-nowrap">Kode</th>
                    <th style="width: 15%;">Supplier</th>
                    <th style="width: 15%;">Nama Barang</th>
                    <th style="width: 10%;">Merk</th>
                    <th style="width: 5%;" class="text-center">Stok</th>
                    <th style="width: 10%;" class="text-nowrap">Hrg Supplier</th>
                    <th style="width: 10%;" class="text-nowrap">Harga Jual</th>
                    <th style="width: 12%;" class="text-center">Barcode</th>
                    <th style="width: 8%;" class="text-center">Foto</th>
                    <th style="width: 8%;" class="text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $panggil_data_barang = mysqli_query($koneksi, "SELECT * FROM tbl_barang_konsinyasi") or die(mysqli_error($koneksi));
                  $rv = mysqli_num_rows($panggil_data_barang);

                  if ($rv > 0) {
                      while ($data = mysqli_fetch_array($panggil_data_barang)) {
                          $kode_brg_konsinyasi = $data['kode_brg_konsinyasi'];
                          $kode_supplier       = $data['kode_supplier'];
                          $nama_brg            = $data['nama_brg'];
                          $merk                = $data['merk'];
                          $stok                = $data['stok'];
                          $harga_supplier      = $data['harga_supplier'];
                          $harga_jual          = $data['harga_jual'];
                          $barcode_barang      = $data['barcode_barang'];
                          $foto_barang         = $data['foto_barang'];

                          // Ambil nama supplier
                          $get_sup = mysqli_query($koneksi, "SELECT nama_supplier FROM tbl_supplier WHERE kode_supplier = '$kode_supplier'");
                          $data_sup = mysqli_fetch_array($get_sup);
                          $nama_supplier = isset($data_sup['nama_supplier']) ? $data_sup['nama_supplier'] : '-';
                  ?>
                          <tr>
                            <td class="text-nowrap"><?= htmlspecialchars($kode_brg_konsinyasi); ?></td>
                            <td><small><?= htmlspecialchars($kode_supplier); ?> - <?= htmlspecialchars($nama_supplier); ?></small></td>
                            <td><?= htmlspecialchars($nama_brg); ?></td>
                            <td><?= htmlspecialchars($merk); ?></td>
                            <td class="text-center"><?= htmlspecialchars($stok); ?></td>
                            <td class="text-nowrap">Rp <?= number_format($harga_supplier, 0, ',', '.'); ?></td>
                            <td class="text-nowrap">Rp <?= number_format($harga_jual, 0, ',', '.'); ?></td>

                            <!-- Barcode Ringkas -->
                            <td class="text-center p-1">
                              <?php if (!empty($barcode_barang)) : ?>
                                <svg class="barcode" data-barcode="<?= htmlspecialchars($barcode_barang); ?>" style="max-width: 100%; height: 30px;"></svg>
                                <br>
                                <small style="font-size: 10px;"><?= htmlspecialchars($barcode_barang); ?></small>
                              <?php else : ?>
                                <small class="text-muted">Tidak ada</small>
                              <?php endif; ?>
                            </td>

                            <!-- Foto Ringkas -->
                            <td class="text-center p-1">
                              <?php if (!empty($foto_barang) && file_exists('../foto_barang_konsinyasi/' . $foto_barang)) : ?>
                                <img src="../foto_barang_konsinyasi/<?= htmlspecialchars($foto_barang); ?>" alt="<?= htmlspecialchars($nama_brg); ?>" width="45" height="45" style="object-fit:cover; border-radius:4px;">
                              <?php else : ?>
                                <small class="text-muted">Tidak ada</small>
                              <?php endif; ?>
                            </td>

                            <!-- Aksi -->
                            <td class="text-center text-nowrap">
                              <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#modal-edit<?= $kode_brg_konsinyasi; ?>" title="Edit">
                                <i class="fas fa-pen"></i>
                              </button>
                              <a href="hapus.php?kode_brg_konsinyasi=<?= $kode_brg_konsinyasi; ?>" class="btn btn-danger btn-xs" onclick="return confirm('Yakin Mau Hapus Data Ini?')" title="Hapus">
                                <i class="fas fa-trash"></i>
                              </a>
                            </td>
                          </tr>

                          <!-- MODAL EDIT -->
                          <div class="modal fade" id="modal-edit<?= $kode_brg_konsinyasi; ?>">
                            <div class="modal-dialog">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h4 class="modal-title">Edit Data Barang Konsinyasi</h4>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>

                                <form action="ubah.php" method="post" enctype="multipart/form-data">
                                  <div class="modal-body">
                                    <div class="form-group">
                                      <label>Kode Barang</label>
                                      <input type="number" class="form-control" value="<?= $kode_brg_konsinyasi; ?>" disabled>
                                      <input type="hidden" name="kode_brg_konsinyasi" value="<?= $kode_brg_konsinyasi; ?>">
                                    </div>

                                    <div class="form-group">
                                      <label>Kode Supplier</label>
                                      <select class="form-control" name="kode_supplier" required>
                                        <option value="">-- Pilih Supplier --</option>
                                        <?php
                                        $q_sup = mysqli_query($koneksi, "SELECT * FROM tbl_supplier");
                                        while ($sup = mysqli_fetch_array($q_sup)) {
                                            $selected = ($sup['kode_supplier'] == $kode_supplier) ? 'selected' : '';
                                        ?>
                                          <option value="<?= $sup['kode_supplier']; ?>" <?= $selected; ?>>
                                            <?= $sup['kode_supplier']; ?> - <?= $sup['nama_supplier']; ?>
                                          </option>
                                        <?php } ?>
                                      </select>
                                    </div>

                                    <div class="form-group">
                                      <label>Nama Barang</label>
                                      <input type="text" name="nama_brg" class="form-control" value="<?= htmlspecialchars($nama_brg); ?>" required>
                                    </div>

                                    <div class="form-group">
                                      <label>Merk</label>
                                      <input type="text" name="merk" class="form-control" value="<?= htmlspecialchars($merk); ?>" required>
                                    </div>

                                    <div class="form-group">
                                      <label>Stok</label>
                                      <input type="number" name="stok" class="form-control" value="<?= $stok; ?>" required>
                                    </div>

                                    <div class="form-group">
                                      <label>Harga Supplier</label>
                                      <input type="number" name="harga_supplier" class="form-control" value="<?= $harga_supplier; ?>" required>
                                    </div>

                                    <div class="form-group">
                                      <label>Harga Jual</label>
                                      <input type="number" name="harga_jual" class="form-control" value="<?= $harga_jual; ?>" required>
                                    </div>

                                    <div class="form-group">
                                      <label>Barcode Barang</label>
                                      <input type="text" class="form-control" value="<?= htmlspecialchars($barcode_barang); ?>" disabled>
                                    </div>

                                    <div class="form-group">
                                      <label>Foto Barang</label>
                                      <?php if (!empty($foto_barang) && file_exists('../foto_barang_konsinyasi/' . $foto_barang)) : ?>
                                        <div class="mb-2">
                                          <img src="../foto_barang_konsinyasi/<?= htmlspecialchars($foto_barang); ?>" width="80" height="80" style="object-fit:cover; border-radius:5px;">
                                        </div>
                                      <?php endif; ?>
                                      <input type="file" name="foto_barang" class="form-control-file" accept="image/*">
                                      <small class="text-muted">Kosongkan jika tidak ingin mengganti foto.</small>
                                    </div>
                                  </div>

                                  <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                                    <button type="submit" name="ubah_barang_konsinyasi" class="btn btn-primary">Simpan</button>
                                  </div>
                                </form>
                              </div>
                            </div>
                          </div>
                  <?php
                      }
                  } else {
                      echo '<tr><td colspan="10" class="text-center">Data Tidak Ditemukan</td></tr>';
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <!-- MODAL TAMBAH -->
  <div class="modal fade" id="modal-tambah">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Tambah Data Barang Konsinyasi</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <form action="tambah.php" method="post" enctype="multipart/form-data">
          <div class="modal-body">
            <div class="form-group">
              <label>Kode Barang</label>
              <input type="text" class="form-control" value="Otomatis" disabled>
            </div>

            <div class="form-group">
              <label>Kode Supplier</label>
              <select class="form-control" name="kode_supplier" required>
                <option value="">-- Pilih Supplier --</option>
                <?php
                $q_sup2 = mysqli_query($koneksi, "SELECT * FROM tbl_supplier");
                while ($sup2 = mysqli_fetch_array($q_sup2)) {
                ?>
                  <option value="<?= $sup2['kode_supplier']; ?>">
                    <?= $sup2['kode_supplier']; ?> - <?= $sup2['nama_supplier']; ?>
                  </option>
                <?php } ?>
              </select>
            </div>

            <div class="form-group">
              <label>Nama Barang</label>
              <input type="text" name="nama_brg" class="form-control" placeholder="Masukan Nama Barang" required>
            </div>

            <div class="form-group">
              <label>Merk</label>
              <input type="text" name="merk" class="form-control" placeholder="Masukan Merk" required>
            </div>

            <div class="form-group">
              <label>Stok</label>
              <input type="number" name="stok" class="form-control" placeholder="Masukan Stok" required>
            </div>

            <div class="form-group">
              <label>Harga Supplier</label>
              <input type="number" name="harga_supplier" class="form-control" placeholder="Masukan Harga Supplier" required>
            </div>

            <div class="form-group">
              <label>Harga Jual</label>
              <input type="number" name="harga_jual" class="form-control" placeholder="Masukan Harga Jual" required>
            </div>

            <div class="form-group">
              <label>Foto Barang</label>
              <input type="file" name="foto_barang" class="form-control-file" accept="image/*">
            </div>
          </div>

          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            <button type="submit" name="tambah_barang_konsinyasi" class="btn btn-primary">Tambah</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <?php include '../footer.php'; ?>
</div>

<?php include '../script.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
<script>
  $(document).ready(function() {
    $('.barcode').each(function() {
      var barcode = $(this).attr('data-barcode');
      if (barcode) {
        JsBarcode(this, barcode, {
          format: "CODE128",
          width: 1,
          height: 30,
          displayValue: false,
          margin: 0
        });
      }
    });
  });
</script>
</body>
</html>
<?php
// }
?>