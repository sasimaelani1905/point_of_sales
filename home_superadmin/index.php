<?php
require_once '../database/koneksi.php';

// -------------------------------------------------------------
// 1. MENGAMBIL DATA METRIK UTAMA (TANPA JOIN)
// -------------------------------------------------------------

// Total Penjualan
$q_penjualan = mysqli_query($koneksi, "SELECT SUM(total_penjualan) AS total_jual FROM tbl_nota_jual");
$d_penjualan = mysqli_fetch_assoc($q_penjualan);
$total_penjualan = $d_penjualan['total_jual'] ?? 0;

// Total Pembelian (dari tbl_nota_beli)
$q_pembelian = mysqli_query($koneksi, "SELECT SUM(total_pembelian) AS total_beli FROM tbl_nota_beli");
if ($q_pembelian) {
    $d_pembelian = mysqli_fetch_assoc($q_pembelian);
    $total_pembelian = $d_pembelian['total_beli'] ?? 0;
} else {
    $total_pembelian = 0; 
}

// Total Barang
$q_barang = mysqli_query($koneksi, "SELECT COUNT(*) AS total_brg FROM tbl_barang");
$d_barang = mysqli_fetch_assoc($q_barang);
$total_barang = $d_barang['total_brg'] ?? 0;

// Laba Bersih (Penjualan - Pembelian)
$laba_bersih = $total_pembelian - $total_penjualan;

// -------------------------------------------------------------
// 2. DATA UNTUK DIAGRAM TOP 5 BARANG TERLARIS (TANPA JOIN)
// -------------------------------------------------------------
$array_nama_brg = [];
$array_jumlah_terjual = [];

$q_top_barang = mysqli_query($koneksi, "
    SELECT kode_brg, SUM(jumlah) AS total_jumlah 
    FROM tbl_detail_nota_jual 
    GROUP BY kode_brg 
    ORDER BY total_jumlah DESC 
    LIMIT 5
");

if ($q_top_barang && mysqli_num_rows($q_top_barang) > 0) {
    while ($row = mysqli_fetch_assoc($q_top_barang)) {
        $kd_brg = $row['kode_brg'];
        $jumlah    = $row['total_jumlah'];

        // Ambil nama barang tanpa JOIN
        $q_brg = mysqli_query($koneksi, "SELECT nama_brg FROM tbl_barang WHERE kode_brg = '$kd_brg'");
        $d_brg = mysqli_fetch_assoc($q_brg);
        $nama_brg = isset($d_brg['nama_brg']) ? $d_brg['nama_brg'] : $kd_brg;

        $array_nama_brg[] = $nama_brg;
        $array_jumlah_terjual[] = (int)$jumlah;
    }
} else {
    $array_nama_brg = ['Belum Ada Data'];
    $array_jumlah_terjual = [0];
}

$json_nama_brg = json_encode($array_nama_brg);
$json_jumlah_terjual = json_encode($array_jumlah_terjual);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Dashboard</title>

  <?php
  include '../css.php';
  $hal = 'beranda';
  ?>
  <!-- Chart.js CDN -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.css">
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
      <?php include '../sidebar_admin.php'; ?>
    </div>
  </aside>

  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        
        <!-- Small boxes (Stat box seperti di contoh) -->
        <div class="row">
          <!-- Box 1: Penjualan (Biru) -->
          <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
              <div class="inner">
                <h3>Rp <?= number_format($total_penjualan, 0, ',', '.'); ?></h3>
                <p>Total Penjualan</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
                <i class="fas fa-shopping-bag"></i>
              </div>
              <a href="../admin_nota_jual/" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <!-- Box 2: Pembelian (Hijau) -->
          <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
              <div class="inner">
                <h3>Rp <?= number_format($total_pembelian, 0, ',', '.'); ?></h3>
                <p>Total Pembelian</p>
              </div>
              <div class="icon">
                <i class="fas fa-chart-line"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <!-- Box 3: Total Laba (Kuning) -->
          <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
              <div class="inner text-white">
                <h3>Rp <?= number_format($laba_bersih, 0, ',', '.'); ?></h3>
                <p>Estimasi Laba Rugi</p>
              </div>
              <div class="icon">
                <i class="fas fa-wallet"></i>
              </div>
              <a href="#" class="small-box-footer text-white" style="color: #fff !important;">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <!-- Box 4: Total Jenis Barang (Merah) -->
          <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
              <div class="inner">
                <h3><?= number_format($total_barang, 0, ',', '.'); ?></h3>
                <p>Total Jenis Barang</p>
              </div>
              <div class="icon">
                <i class="fas fa-boxes"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
        </div>

        <!-- Main row (Diagrams) -->
        <div class="row">
          <!-- Grafik Penjualan vs Pembelian -->
          <section class="col-lg-7 connectedSortable">
            <div class="card">
              <div class="card-header border-0">
                <h3 class="card-title">
                  <i class="fas fa-chart-pie mr-1"></i>
                  Grafik Penjualan, Pembelian & Laba
                </h3>
              </div>
              <div class="card-body">
                <canvas id="keuanganChart" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
              </div>
            </div>
          </section>

          <!-- Top 5 Barang Terlaris -->
          <section class="col-lg-5 connectedSortable">
            <div class="card">
              <div class="card-header border-0">
                <h3 class="card-title">
                  <i class="fas fa-th mr-1"></i>
                  Top 5 Barang Terlaris
                </h3>
              </div>
              <div class="card-body">
                <canvas id="topBarangChart" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
              </div>
            </div>
          </section>
        </div>

      </div>
    </section>
  </div>

  <?php include '../footer.php'; ?>
</div>

<?php include '../script.php'; ?>
<!-- Script untuk Diagram Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>

<script>
  $(document).ready(function () {
    // 1. Chart Ringkasan Keuangan
    var totalPenjualan = <?= $total_penjualan; ?>;
    var totalPembelian = <?= $total_pembelian; ?>;
    var totalLaba      = <?= $laba_bersih; ?>;

    var ctxPie = document.getElementById('keuanganChart').getContext('2d');
    new Chart(ctxPie, {
      type: 'bar',
      data: {
        labels: ['Penjualan', 'Pembelian', 'Laba Bersih'],
        datasets: [{
          label: 'Jumlah (Rp)',
          data: [totalPenjualan, totalPembelian, totalLaba],
          backgroundColor: ['#17a2b8', '#28a745', '#ffc107']
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          yAxes: [{
            ticks: {
              beginAtZero: true
            }
          }]
        }
      }
    });

    // 2. Chart Top 5 Barang Terlaris
    var namaBarang = <?= $json_nama_brg; ?>;
    var jumlahTerjual = <?= $json_jumlah_terjual; ?>;

    var ctxBar = document.getElementById('topBarangChart').getContext('2d');
    new Chart(ctxBar, {
      type: 'horizontalBar',
      data: {
        labels: namaBarang,
        datasets: [{
          label: 'jumlah Terjual',
          data: jumlahTerjual,
          backgroundColor: '#dc3545'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          xAxes: [{
            ticks: {
              beginAtZero: true,
              precision: 0
            }
          }]
        }
      }
    });
  });
</script>

</body>
</html>