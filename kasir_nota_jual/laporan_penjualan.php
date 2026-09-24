<?php
require_once '../database/koneksi.php';

// Filter Tanggal (Default: Awal Bulan s/d Akhir Bulan)
$tgl_awal  = isset($_GET['tgl_awal']) ? $_GET['tgl_awal'] : date('Y-m-01');
$tgl_akhir = isset($_GET['tgl_akhir']) ? $_GET['tgl_akhir'] : date('Y-m-t');

// Query Header Nota Penjualan berdasarkan rentang tgl_penjualan
$q_nota = mysqli_query($koneksi, "
    SELECT * FROM tbl_nota_jual 
    WHERE DATE(tgl_penjualan) BETWEEN '$tgl_awal' AND '$tgl_akhir'
    ORDER BY tgl_penjualan ASC
") or die("Gagal query: " . mysqli_error($koneksi));
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Laporan Penjualan</title>

  <?php
  include '../css.php';
  $hal = 'laporan_penjualan';
  ?>

  <style>
    @media print {
      .main-header,
      .main-sidebar,
      .btn,
      .card-tools,
      .filter-section,
      footer {
        display: none !important;
      }
      .content-wrapper {
        margin-left: 0 !important;
        padding: 0 !important;
        background-color: #fff !important;
      }
      .card {
        border: none !important;
        box-shadow: none !important;
      }
    }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
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
          <a href="../logout.php" class="dropdown-item">
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
      <?php include '../sidebar_kasir.php'; ?>
    </div>
  </aside>

  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="card card-primary card-outline">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title"><i class="fas fa-file-invoice-dollar mr-1"></i> Data Penjualan</h3>
            
          </div>
          
          <div class="card-body">
            <!-- Filter Tanggal -->
            <div class="filter-section bg-light p-3 rounded mb-4 border">
              <form method="GET" class="form-inline">
                <label class="mr-2 font-weight-bold">Dari Tanggal:</label>
                <input type="date" name="tgl_awal" class="form-control mr-3 mb-2 mb-sm-0" value="<?= $tgl_awal; ?>" required>
                
                <label class="mr-2 font-weight-bold">Sampai Tanggal:</label>
                <input type="date" name="tgl_akhir" class="form-control mr-3 mb-2 mb-sm-0" value="<?= $tgl_akhir; ?>" required>
                
                <button type="submit" class="btn btn-success"><i class="fas fa-filter"></i> Filter</button>
                <a href="pdf_penjualan.php?kode_nota=<?= $kode_nota;?>" target="_blank" class="btn btn-danger"><i class="fas fa-file-pdf"></i>Ekspor PDF</a>
              </form>
            </div>

            <p class="mb-3">
              <strong>Periode:</strong> <?= date('d-m-Y', strtotime($tgl_awal)); ?> s/d <?= date('d-m-Y', strtotime($tgl_akhir)); ?>
            </p>

            <table class="table table-bordered table-striped text-nowrap">
              <thead>
                <tr class="text-dark text-center">
                  <th width="5%">No</th>
                  <th width="20%">Kode Nota</th>
                  <th width="25%">Tanggal Penjualan</th>
                  <th width="20%">Jumlah Item</th>
                  <th width="30%">Total Penjualan</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $no = 1;
                $total_omset = 0;
                $total_qty_semua = 0;

                if (mysqli_num_rows($q_nota) > 0) {
                  while ($nota = mysqli_fetch_assoc($q_nota)) {
                    $kd_nota = $nota['kode_nota'];

                    // Query 2: Detail Barang Tanpa JOIN
                    $q_detail = mysqli_query($koneksi, "SELECT * FROM tbl_detail_nota_jual WHERE kode_nota = '$kd_nota'");
                    
                    $subtotal_nota = 0;
                    $qty_nota = 0;

                    while ($detail = mysqli_fetch_assoc($q_detail)) {
                      $subtotal_nota += $detail['total_harga_jual'];
                      $qty_nota += $detail['jumlah'];
                    }

                    $total_omset += $subtotal_nota;
                    $total_qty_semua += $qty_nota;
                    ?>
                    <tr>
                      <td class="text-center"><?= $no++; ?></td>
                      <td class="text-center"><?= $kd_nota; ?></td>
                      <td class="text-center"><?= date('d-m-Y H:i', strtotime($nota['tgl_penjualan'])); ?></td>
                      <td class="text-center"><?= $qty_nota; ?> Pcs</td>
                      <td class="text-right">Rp <?= number_format($subtotal_nota, 0, ',', '.'); ?></td>
                    </tr>
                    <?php
                  }
                } else {
                  echo '<tr><td colspan="5" class="text-center">Tidak ada data penjualan pada periode ini.</td></tr>';
                }
                ?>
              </tbody>
              <tfoot>
                <tr class="bg-light font-weight-bold">
                  <td colspan="3" class="text-center">TOTAL</td>
                  <td class="text-center"><?= $total_qty_semua; ?> Pcs</td>
                  <td class="text-right">Rp <?= number_format($total_omset, 0, ',', '.'); ?></td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </section>
  </div>

  <?php include '../footer.php'; ?>
</div>

<?php include '../script.php'; ?>
</body>
</html>