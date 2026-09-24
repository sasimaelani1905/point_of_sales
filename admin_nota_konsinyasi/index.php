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
  <title>AdminLTE 3 | Data Nota Konsinyasi</title>

  <?php
  include '../css.php';
  $hal = 'nota_konsinyasi';
  ?>
  <style>
    .table-custom th, .table-custom td {
      padding: 8px 10px !important;
      vertical-align: middle !important;
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
            <h3 class="card-title">Data Nota Konsinyasi</h3>
          </div>
          
          <div class="card-body">
            <button type="button" class="btn btn-success mb-3 btn-sm" data-toggle="modal" data-target="#modal-tambah">
              <i class="fas fa-plus"></i> Tambah Nota Konsinyasi
            </button>
        
            <div class="table-responsive">
              <table id="example1" class="table table-bordered table-striped table-custom">
                <thead>
                  <tr>
                    <th style="width: 15%;">No. Nota</th>
                    <th style="width: 25%;">Nama Barang</th>
                    <th style="width: 20%;">Nama Supplier</th>
                    <th style="width: 15%;" class="text-center">Tgl Titip</th>
                    <th style="width: 10%;" class="text-center">Total Item</th>
                    <th style="width: 15%;" class="text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $panggil_data_nota = mysqli_query($koneksi, "
                    SELECT n.*, b.nama_brg 
                    FROM tbl_nota_konsinyasi n
                    LEFT JOIN tbl_barang_konsinyasi b ON n.kode_brg_konsinyasi = b.kode_brg_konsinyasi
                    ORDER BY n.created_at DESC
                  ") or die(mysqli_error($koneksi));

                  $rv = mysqli_num_rows($panggil_data_nota);
                  if ($rv > 0) {
                    while ($data = mysqli_fetch_array($panggil_data_nota)) {
                      $no_nota_konsinyasi  = $data['no_nota_konsinyasi'];
                      $kode_brg_konsinyasi = $data['kode_brg_konsinyasi'];
                      $nama_brg            = isset($data['nama_brg']) ? $data['nama_brg'] : '-';
                      $nama_supplier       = $data['nama_supplier'];
                      $tgl_titip           = $data['tgl_titip'];
                      $total_item          = $data['total_item'];
                  ?>
                      <tr>
                        <td class="text-nowrap"><strong><?= htmlspecialchars($no_nota_konsinyasi); ?></strong></td>
                        <td><?= htmlspecialchars($nama_brg); ?> <small class="text-muted">(Kode: <?= $kode_brg_konsinyasi; ?>)</small></td>
                        <td><?= htmlspecialchars($nama_supplier); ?></td>
                        <td class="text-center"><?= date('d-m-Y', strtotime($tgl_titip)); ?></td>
                        <td class="text-center"><?= number_format($total_item, 0, ',', '.'); ?></td>
                        <td class="text-center text-nowrap"> 
                          <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#modal-edit<?= str_replace(['/', ' '], '_', $no_nota_konsinyasi); ?>" title="Edit">
                            <i class="fas fa-pen"></i>
                          </button>
                          <a href="hapus.php?no_nota_konsinyasi=<?= urlencode($no_nota_konsinyasi); ?>" class="btn btn-danger btn-xs" onclick="return confirm('Yakin ingin menghapus nota ini?')" title="Hapus">
                            <i class="fas fa-trash"></i>
                            <a href="../admin_detail_nota_konsinyasi?no_nota_konsinyasi=<?= $no_nota_konsinyasi; ?>" class="btn btn-secondary btn-xs"><i class="fas fa-eye"></i></a>
                          </a>
                        </td>
                      </tr>

                      <!-- Modal Edit -->
                      <div class="modal fade" id="modal-edit<?= str_replace(['/', ' '], '_', $no_nota_konsinyasi); ?>">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h4 class="modal-title">Edit Nota Konsinyasi</h4>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>

                            <form action="ubah.php" method="post">
                              <div class="modal-body">
                                <div class="form-group">
                                  <label>No. Nota Konsinyasi</label>
                                  <input type="text" class="form-control" value="<?= htmlspecialchars($no_nota_konsinyasi); ?>" disabled>
                                  <input type="hidden" name="no_nota_konsinyasi" value="<?= htmlspecialchars($no_nota_konsinyasi); ?>">
                                </div>

                                <div class="form-group">
                                  <label>Barang Konsinyasi</label>
                                  <select class="form-control" name="kode_brg_konsinyasi" required>
                                    <option value="">-- Pilih Barang --</option>
                                    <?php
                                    $q_brg = mysqli_query($koneksi, "SELECT kode_brg_konsinyasi, nama_brg, merk FROM tbl_barang_konsinyasi");
                                    while ($brg = mysqli_fetch_array($q_brg)) {
                                      $selected = ($brg['kode_brg_konsinyasi'] == $kode_brg_konsinyasi) ? 'selected' : '';
                                      echo '<option value="' . $brg['kode_brg_konsinyasi'] . '" ' . $selected . '>' . $brg['kode_brg_konsinyasi'] . ' - ' . $brg['nama_brg'] . ' (' . $brg['merk'] . ')</option>';
                                    }
                                    ?>
                                  </select>
                                </div>

                                <div class="form-group">
                                  <label>Nama Supplier</label>
                                  <select class="form-control" name="nama_supplier" required>
                                    <option value="">-- Pilih Supplier --</option>
                                    <?php
                                    $q_sup = mysqli_query($koneksi, "SELECT nama_supplier FROM tbl_supplier");
                                    while ($sup = mysqli_fetch_array($q_sup)) {
                                      $selected = ($sup['nama_supplier'] == $nama_supplier) ? 'selected' : '';
                                      echo '<option value="' . htmlspecialchars($sup['nama_supplier']) . '" ' . $selected . '>' . htmlspecialchars($sup['nama_supplier']) . '</option>';
                                    }
                                    ?>
                                  </select>
                                </div>

                                <div class="form-group">
                                  <label>Tanggal Titip</label>
                                  <input type="date" name="tgl_titip" class="form-control" value="<?= $tgl_titip; ?>" required>
                                </div>

                                <div class="form-group">
                                  <label>Total Item</label>
                                  <input type="number" name="total_item" class="form-control" value="<?= $total_item; ?>" min="1" required>
                                </div>
                              </div>

                              <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                                <button type="submit" name="ubah_nota_konsinyasi" class="btn btn-primary">Simpan</button>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>
                      <!-- /.modal edit -->

                  <?php
                    }
                  } else {
                    echo '<tr><td colspan="6" class="text-center">Data Nota Konsinyasi Tidak Ditemukan</td></tr>';
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

  <!-- Modal Tambah -->
  <div class="modal fade" id="modal-tambah">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Tambah Nota Konsinyasi</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <form action="tambah.php" method="post">
          <div class="modal-body">
            <div class="form-group">
              <label>No. Nota Konsinyasi (Otomatis)</label>
              <?php
              // Generate No Nota Otomatis (Format: NK-YYYYMMDD001)
              $today = date("Ymd");
              $prefix = "NK-" . $today;

              // Cari no_nota_konsinyasi terakhir yang dibuat hari ini
              $query_auto = mysqli_query($koneksi, "SELECT no_nota_konsinyasi FROM tbl_nota_konsinyasi WHERE no_nota_konsinyasi LIKE '$prefix%' ORDER BY no_nota_konsinyasi DESC LIMIT 1");
              $data_auto  = mysqli_fetch_array($query_auto);

              if ($data_auto) {
                  // Ambil 3 digit angka terakhir lalu tambahkan 1
                  $last_no = substr($data_auto['no_nota_konsinyasi'], -3);
                  $next_no = sprintf("%03d", (int)$last_no + 1);
              } else {
                  // Jika belum ada transaksi hari ini, mulai dari 001
                  $next_no = "001";
              }

              $no_nota_auto = $prefix . $next_no;
              ?>

              <!-- Display ke user (disabled agar tidak bisa diedit) -->
              <input type="text" class="form-control" value="<?= $no_nota_auto; ?>" disabled>
              
              <!-- Value dikirimkan lewat hidden input ke proses tambah.php -->
              <input type="hidden" name="no_nota_konsinyasi" value="<?= $no_nota_auto; ?>">
            </div>

            <div class="form-group">
              <label>Barang Konsinyasi</label>
              <select class="form-control" name="kode_brg_konsinyasi" required>
                <option value="">-- Pilih Barang Konsinyasi --</option>
                <?php
                $q_brg2 = mysqli_query($koneksi, "SELECT kode_brg_konsinyasi, nama_brg, merk FROM tbl_barang_konsinyasi");
                while ($brg2 = mysqli_fetch_array($q_brg2)) {
                  echo '<option value="' . $brg2['kode_brg_konsinyasi'] . '">' . $brg2['kode_brg_konsinyasi'] . ' - ' . $brg2['nama_brg'] . ' (' . $brg2['merk'] . ')</option>';
                }
                ?>
              </select>
            </div>

            <div class="form-group">
              <label>Nama Supplier</label>
              <select class="form-control" name="nama_supplier" required>
                <option value="">-- Pilih Supplier --</option>
                <?php
                $q_sup2 = mysqli_query($koneksi, "SELECT nama_supplier FROM tbl_supplier");
                while ($sup2 = mysqli_fetch_array($q_sup2)) {
                  echo '<option value="' . htmlspecialchars($sup2['nama_supplier']) . '">' . htmlspecialchars($sup2['nama_supplier']) . '</option>';
                }
                ?>
              </select>
            </div>

            <div class="form-group">
              <label>Tanggal Titip</label>
              <input type="date" name="tgl_titip" class="form-control" value="<?= date('Y-m-d'); ?>" required>
            </div>

            <div class="form-group">
              <label>Total Item</label>
              <input type="number" name="total_item" class="form-control" placeholder="Masukan Jumlah Item" min="1" required>
            </div>
          </div>

          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            <button type="submit" name="tambah_nota_konsinyasi" class="btn btn-primary">Tambah</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <?php include '../footer.php'; ?>
</div>

<?php include '../script.php'; ?>

</body>
</html>
<?php
// }
?>