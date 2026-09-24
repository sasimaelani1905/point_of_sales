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
  <title>ADMINLTE 3 | Nota Beli</title>

  <?php
  include '../css.php';
  $hal ='nota_beli';
  ?>
</head>
<body class="hold-transition with-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-with">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item"><i class="fas fa-user mr-2"></i> Profil</a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item"><i class="fas fa-sign-out-alt mr-2"></i> Logout</a>
        </div>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="info">
        <a href="#" class="d-block">POINT OF SALES</a>
      </div>
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
      <?php include '../sidebar_kasir.php'; ?>
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
            <h3 class="card-title">Nota Beli</h3>
          </div>
          
          <div class="card-body">
            <button type="button" class="btn btn-success mb-3" data-toggle="modal" data-target="#modal-tambah"><i class="fas fa-plus"></i> Tambah Data</button>
            <a href="laporan_pembelian.php" class="btn btn-primary mb-3">Laporan Pembelian</a>

            <!-- Tab Navigation -->
            <div class="card card-primary card-tabs">
              <div class="card-header p-0 pt-1">
                <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="tab-semua" data-toggle="pill" href="#content-semua" role="tab">Semua Pembayaran</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="tab-lunas" data-toggle="pill" href="#content-lunas" role="tab">Dibayar Lunas</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="tab-25" data-toggle="pill" href="#content-25" role="tab">Dibayar 25%</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="tab-50" data-toggle="pill" href="#content-50" role="tab">Dibayar 50%</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="tab-75" data-toggle="pill" href="#content-75" role="tab">Dibayar 75%</a>
                  </li>
                </ul>
              </div>

              <div class="card-body">
                <div class="tab-content" id="custom-tabs-one-tabContent">
                  <?php
                  $tabs = [
                    'semua' => '',
                    'lunas' => "WHERE status = 'L'",
                    '25'    => "WHERE status = '2'",
                    '50'    => "WHERE status = '3'",
                    '75'    => "WHERE status = '4'"
                  ];

                  foreach ($tabs as $tab_id => $where_clause) :
                    $active_class = ($tab_id == 'semua') ? 'show active' : '';
                  ?>
                    <div class="tab-pane fade <?= $active_class; ?>" id="content-<?= $tab_id; ?>" role="tabpanel">
                      <table class="table table-bordered table-striped example-table" style="width: 100%;">
                        <thead>
                          <tr>
                            <th>Kode Nota</th>
                            <th>Kode Supplier</th>
                            <th>Tanggal Pembelian</th>
                            <th>Total Pembelian</th>
                            <th>Status</th>
                            <th>Metode Pembayaran</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $query_str = "SELECT * FROM tbl_nota_beli $where_clause ORDER BY kode_nota DESC";
                          $panggil_data_nota = mysqli_query($koneksi, $query_str) or die(mysqli_error($koneksi));
                          $rv = mysqli_num_rows($panggil_data_nota);

                          if ($rv > 0) {
                            while ($data = mysqli_fetch_array($panggil_data_nota)) {
                              $kode_nota          = $data['kode_nota'];
                              $kode_supplier      = $data['kode_supplier'];
                              $tgl_pembelian      = $data['tgl_pembelian'];
                              $total_pembelian    = $data['total_pembelian'];
                              $status             = $data['status'];
                              $metode_pembayaran  = isset($data['metode_pembayaran']) ? $data['metode_pembayaran'] : '-';
                              $keterangan         = $data['keterangan'];

                              // Format label status
                              $status_label = '-';
                              if ($status == 'L') $status_label = '<span class="badge badge-success">Lunas 100%</span>';
                              else if ($status == '2') $status_label = '<span class="badge badge-warning">25%</span>';
                              else if ($status == '3') $status_label = '<span class="badge badge-info">50%</span>';
                              else if ($status == '4') $status_label = '<span class="badge badge-primary">75%</span>';

                              $get_sup = mysqli_query($koneksi, "SELECT nama_supplier FROM tbl_supplier WHERE kode_supplier = '$kode_supplier'");
                              $data_sup = mysqli_fetch_array($get_sup);
                              $nama_supplier = isset($data_sup['nama_supplier']) ? $data_sup['nama_supplier'] : '-';
                              ?>
                              <tr>
                                <td><?= $kode_nota ?></td>
                                <td><?= $kode_supplier ?> - <?= $nama_supplier ?></td>
                                <td><?= $tgl_pembelian ?></td>
                                <td>Rp <?= number_format($total_pembelian, 0, ',', '.'); ?></td>
                                <td><?= $status_label ?></td>
                                <td><span class="badge badge-secondary"><?= strtoupper($metode_pembayaran) ?></span></td>
                                <td><?= $keterangan ?></td>
                                <td> 
                                  <a href="../kasir_detail_nota_beli?kode_nota=<?= $kode_nota; ?>" class="btn btn-secondary btn-sm"><i class="fas fa-eye"></i></a>
                                  <button type="button" class="btn btn-primary btn-sm mb-1" data-toggle="modal" data-target="#modal-edit<?= $kode_nota; ?>_<?= $tab_id; ?>"><i class="fas fa-pen"></i></button>
                                  <a href="hapus.php?kode_nota=<?= $kode_nota; ?>" class="btn btn-danger btn-sm mb-1" onclick="return confirm('Yakin Mau Hapus Data Ini?')"><i class="fas fa-trash"></i></a>
                                </td>
                              </tr>

                              <!-- Modal Edit Data -->
                              <div class="modal fade" id="modal-edit<?= $kode_nota; ?>_<?= $tab_id; ?>">
                                <div class="modal-dialog">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <h4 class="modal-title">Edit Data Nota Beli</h4>
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
                                            $q_sup = mysqli_query($koneksi, "SELECT * FROM tbl_supplier");
                                            while ($sup = mysqli_fetch_array($q_sup)) {
                                              $selected = ($sup['kode_supplier'] == $kode_supplier) ? 'selected' : '';
                                              echo '<option value="' . $sup['kode_supplier'] . '" ' . $selected . '>' . $sup['kode_supplier'] . ' - ' . $sup['nama_supplier'] . '</option>';
                                            }
                                            ?>
                                          </select>
                                        </div>
                                        <div class="form-group">
                                          <label for="tgl_pembelian">Tanggal Pembelian</label>
                                          <input type="date" name="tgl_pembelian" class="form-control" value="<?= $tgl_pembelian; ?>" required>
                                        </div>
                                        <div class="form-group">
                                          <label for="total_pembelian">Total Nilai Nota (Rp)</label>
                                          <input type="number" id="total_pembelian_edit_<?= $kode_nota; ?>_<?= $tab_id; ?>" name="total_pembelian" class="form-control" value="<?= $total_pembelian; ?>" required oninput="hitungPembayaran('<?= $kode_nota; ?>_<?= $tab_id; ?>')">
                                        </div>

                                        <div class="form-group">
                                          <label>Status</label>
                                          <select class="form-control" id="status_edit_<?= $kode_nota; ?>_<?= $tab_id; ?>" name="status" onchange="hitungPembayaran('<?= $kode_nota; ?>_<?= $tab_id; ?>')">
                                            <option value="">-- Pilih Status --</option>
                                            <option value="L" <?= ($status == 'L') ? 'selected': '' ;?>>Lunas 100%</option>
                                            <option value="2" <?= ($status == '2') ? 'selected': '' ;?>>Dibayar 25%</option>
                                            <option value="3" <?= ($status == '3') ? 'selected': '' ;?>>Dibayar 50%</option>
                                            <option value="4" <?= ($status == '4') ? 'selected': '' ;?>>Dibayar 75%</option>
                                          </select>
                                        </div>

                                        <!-- Tambahan Metode Pembayaran (Edit) -->
                                        <div class="form-group">
                                          <label>Metode Pembayaran</label>
                                          <select class="form-control" name="metode_pembayaran" required>
                                            <option value="">-- Pilih Metode Pembayaran --</option>
                                            <option value="Cash" <?= ($metode_pembayaran == 'Cash') ? 'selected' : ''; ?>>Cash</option>
                                            <option value="QRIS" <?= ($metode_pembayaran == 'QRIS') ? 'selected' : ''; ?>>QRIS</option>
                                            <option value="Transfer" <?= ($metode_pembayaran == 'Transfer') ? 'selected' : ''; ?>>Transfer</option>
                                          </select>
                                        </div>

                                        <div class="form-group">
                                          <label>Total Sudah Dibayar (Rp)</label>
                                          <input type="text" id="total_dibayar_view_edit_<?= $kode_nota; ?>_<?= $tab_id; ?>" class="form-control" readonly placeholder="0">
                                          <input type="hidden" id="total_dibayar_edit_<?= $kode_nota; ?>_<?= $tab_id; ?>" name="total_dibayar">
                                        </div>

                                        <div class="form-group">
                                          <label>Sisa (Rp)</label>
                                          <input type="text" id="sisa_pembayaran_view_edit_<?= $kode_nota; ?>_<?= $tab_id; ?>" class="form-control" readonly placeholder="0">
                                        </div>

                                        <div class="form-group">
                                          <label>Keterangan</label>
                                          <input type="text" id="keterangan_edit_<?= $kode_nota; ?>_<?= $tab_id; ?>" name="keterangan" class="form-control" value="<?= $keterangan; ?>" readonly placeholder="Otomatis mengikuti status">
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
                  <?php 
                endforeach; 
                ?>

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <!-- Modal Tambah (1 Saja) -->
  <div class="modal fade" id="modal-tambah">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Tambah Data Nota Beli</h4>
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
                $q_sup2 = mysqli_query($koneksi, "SELECT * FROM tbl_supplier");
                while ($sup2 = mysqli_fetch_array($q_sup2)) {
                  echo '<option value="' . $sup2['kode_supplier'] . '">' . $sup2['kode_supplier'] . ' - ' . $sup2['nama_supplier'] . '</option>';
                }
                ?>
              </select>
            </div>
            <div class="form-group">
              <label for="tgl_pembelian">Tanggal Pembelian</label>
              <input type="date" name="tgl_pembelian" class="form-control" required>
            </div>
            <div class="form-group">
              <label for="total_pembelian">Total Nilai Nota (Rp)</label>
              <input type="number" id="total_pembelian_tambah" name="total_pembelian" class="form-control" placeholder="Masukan Total Pembelian" required oninput="hitungPembayaranTambah()">
            </div>
            <div class="form-group">
              <label>Status</label>
              <select class="form-control" id="status_tambah" name="status" onchange="hitungPembayaranTambah()">
                <option value="">-- Pilih Status --</option>
                <option value="L">Lunas 100%</option>
                <option value="2">Dibayar 25%</option>
                <option value="3">Dibayar 50%</option>
                <option value="4">Dibayar 75%</option>
              </select>
            </div>

            <!-- Tambahan Metode Pembayaran (Tambah) -->
            <div class="form-group">
              <label>Metode Pembayaran</label>
              <select class="form-control" name="metode_pembayaran" required>
                <option value="">-- Pilih Metode Pembayaran --</option>
                <option value="Cash">Cash</option>
                <option value="QRIS">QRIS</option>
                <option value="Transfer">Transfer</option>
              </select>
            </div>

            <div class="form-group">
              <label>Total Sudah Dibayar (Rp)</label>
              <input type="text" id="total_dibayar_view_tambah" class="form-control" readonly placeholder="0">
              <input type="hidden" id="total_dibayar_tambah" name="total_dibayar">
            </div>
            <div class="form-group">
              <label>Sisa (Rp)</label>
              <input type="text" id="sisa_pembayaran_view_tambah" class="form-control" readonly placeholder="0">
            </div>
            <div class="form-group">
              <label>Keterangan</label>
              <input type="text" id="keterangan_tambah" name="keterangan" class="form-control" readonly placeholder="Otomatis mengikuti status">
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

<!-- Script Hitung Otomatis -->
<script>
// 1. Fungsi Hitung Otomatis Modal Tambah
function hitungPembayaranTambah() {
  let totalNota = parseFloat(document.getElementById('total_pembelian_tambah').value) || 0;
  let status = document.getElementById('status_tambah').value;
  
  let persentase = 0;
  if (status === 'L') persentase = 1.0;
  else if (status === '2') persentase = 0.25;
  else if (status === '3') persentase = 0.50;
  else if (status === '4') persentase = 0.75;

  let totalDibayar = totalNota * persentase;
  let sisaPembayaran = totalNota - totalDibayar;

  document.getElementById('total_dibayar_tambah').value = totalDibayar;
  document.getElementById('total_dibayar_view_tambah').value = 'Rp ' + totalDibayar.toLocaleString('id-ID');
  document.getElementById('sisa_pembayaran_view_tambah').value = 'Rp ' + sisaPembayaran.toLocaleString('id-ID');

  if (status === 'L') {
    document.getElementById('keterangan_tambah').value = 'Lunas';
  } else if (status !== '') {
    document.getElementById('keterangan_tambah').value = 'Belum Lunas';
  } else {
    document.getElementById('keterangan_tambah').value = '';
  }
}

// 2. Fungsi Dinamis Hitung Otomatis Modal Edit
function hitungPembayaran(key) {
  let totalNota = parseFloat(document.getElementById('total_pembelian_edit_' + key).value) || 0;
  let status = document.getElementById('status_edit_' + key).value;
  
  let persentase = 0;
  if (status === 'L') persentase = 1.0;
  else if (status === '2') persentase = 0.25;
  else if (status === '3') persentase = 0.50;
  else if (status === '4') persentase = 0.75;

  let totalDibayar = totalNota * persentase;
  let sisaPembayaran = totalNota - totalDibayar;

  document.getElementById('total_dibayar_edit_' + key).value = totalDibayar;
  document.getElementById('total_dibayar_view_edit_' + key).value = 'Rp ' + totalDibayar.toLocaleString('id-ID');
  document.getElementById('sisa_pembayaran_view_edit_' + key).value = 'Rp ' + sisaPembayaran.toLocaleString('id-ID');

  if (status === 'L') {
    document.getElementById('keterangan_edit_' + key).value = 'Lunas';
  } else if (status !== '') {
    document.getElementById('keterangan_edit_' + key).value = 'Belum Lunas';
  } else {
    document.getElementById('keterangan_edit_' + key).value = '';
  }
}

// Inisialisasi hitung saat modal edit dibuka
$(document).ready(function() {
  $('.modal').on('shown.bs.modal', function () {
    let modalId = $(this).attr('id');
    if (modalId.startsWith('modal-edit')) {
      let key = modalId.replace('modal-edit', '');
      hitungPembayaran(key);
    }
  });

  // Jika menggunakan DataTable
  if ($.fn.DataTable) {
    $('.example-table').DataTable({
      "responsive": true,
      "autoWidth": false,
    });
  }
});
</script>

</body>
</html>