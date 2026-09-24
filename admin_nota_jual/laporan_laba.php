<?php
require_once '../database/koneksi.php';

// Filter Tanggal (Default: Awal Bulan s/d Akhir Bulan saat ini)
$tgl_awal  = isset($_GET['tgl_awal']) ? $_GET['tgl_awal'] : date('Y-m-01');
$tgl_akhir = isset($_GET['tgl_akhir']) ? $_GET['tgl_akhir'] : date('Y-m-t');

// ==================== 1. HITUNG TOTAL PENJUALAN ====================
$q_nota_jual = mysqli_query($koneksi, "
    SELECT kode_nota FROM tbl_nota_jual 
    WHERE DATE(tgl_penjualan) BETWEEN '$tgl_awal' AND '$tgl_akhir'
") or die("Gagal query penjualan: " . mysqli_error($koneksi));

$total_penjualan = 0;
$total_qty_jual  = 0;

if ($q_nota_jual && mysqli_num_rows($q_nota_jual) > 0) {
    while ($nj = mysqli_fetch_assoc($q_nota_jual)) {
        $kd_nota_jual = $nj['kode_nota'];
        $q_det_jual = mysqli_query($koneksi, "
            SELECT SUM(total_harga_jual) AS subtotal, SUM(jumlah) AS total_qty 
            FROM tbl_detail_nota_jual 
            WHERE kode_nota = '$kd_nota_jual'
        ");
        $d_det_jual = mysqli_fetch_assoc($q_det_jual);
        $total_penjualan += (float) ($d_det_jual['subtotal'] ?? 0);
        $total_qty_jual  += (int) ($d_det_jual['total_qty'] ?? 0);
    }
}

// ==================== 2. HITUNG TOTAL PEMBELIAN ====================
$q_nota_beli = mysqli_query($koneksi, "
    SELECT kode_nota FROM tbl_nota_beli 
    WHERE DATE(tgl_pembelian) BETWEEN '$tgl_awal' AND '$tgl_akhir'
") or die("Gagal query pembelian: " . mysqli_error($koneksi));

$total_pembelian = 0;
$total_qty_beli  = 0;

if ($q_nota_beli && mysqli_num_rows($q_nota_beli) > 0) {
    while ($nb = mysqli_fetch_assoc($q_nota_beli)) {
        $kd_nota_beli = $nb['kode_nota'];

        // Subquery Detail Barang Pembelian (Sesuai Struktur Laporan Pembelian Sebelumnya)
        $q_det_beli = mysqli_query($koneksi, "
            SELECT * FROM tbl_detail_nota_beli 
            WHERE kode_nota = '$kd_nota_beli'
        ") or die("Gagal query detail pembelian: " . mysqli_error($koneksi));
        
        $subtotal_nota_harga = 0;
        $subtotal_nota_qty   = 0;

        while ($detail = mysqli_fetch_assoc($q_det_beli)) {
            $subtotal_nota_harga += $detail['total_harga_beli'];
            $subtotal_nota_qty   += $detail['jumlah'];
        }

        $total_pembelian += $subtotal_nota_harga;
        $total_qty_beli  += $subtotal_nota_qty;
    }
}

// ==================== 3. KALKULASI ====================
$laba_rugi = $total_penjualan - $total_pembelian;
$margin_persen = ($total_penjualan > 0) ? round(($laba_rugi / $total_penjualan) * 100, 1) : 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Laporan Laba</title>

  <?php include '../css.php'; $hal = 'laporan_laba_rugi'; ?>

  <style>
    body { background-color: #f4f6f9; font-family: 'Source Sans Pro', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; }
    
    /* Stat Cards Styling */
    .card-stat { border: none; border-radius: 12px; transition: all 0.2s ease; box-shadow: 0 4px 12px rgba(0,0,0,0.03); }
    .card-stat:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(0,0,0,0.06); }
    .icon-box { width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px; }

    /* Custom Table Styling */
    .card-table { border: none; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.03); }
    .table-custom { width: 100%; margin-bottom: 0; }
    .table-custom thead th { background: #f8fafc; color: #475569; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #e2e8f0; padding: 14px 20px; }
    .table-custom td { padding: 14px 20px; vertical-align: middle; border-bottom: 1px solid #f1f5f9; }
    
    .category-header { background-color: #f1f5f9; font-weight: 700; color: #1e293b; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px; }
    .subtotal-row { background-color: #f8fafc; font-weight: 700; color: #0f172a; }
    
    .result-profit { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: #ffffff; font-size: 16px; font-weight: 700; }
    .result-loss { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: #ffffff; font-size: 16px; font-weight: 700; }
    
    .filter-box { background: #ffffff; border-radius: 12px; padding: 18px 22px; box-shadow: 0 4px 12px rgba(0,0,0,0.03); }

    @media print {
      .main-header, .main-sidebar, .btn, .filter-box, footer { display: none !important; }
      .content-wrapper { margin-left: 0 !important; padding: 0 !important; background: #fff !important; }
    }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light border-bottom-0 shadow-sm">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" href="../logout.php"><i class="fas fa-sign-out-alt mr-1"></i> Logout</a>
      </li>
    </ul>
  </nav>

  <!-- Sidebar -->
  <aside class="main-sidebar sidebar-dark-primary elevation-2">
    <div class="user-panel mt-3 pb-3 mb-3 d-flex pl-3">
      <div class="info">
        <a href="#" class="d-block font-weight-bold">POINT OF SALES</a>
      </div>
    </div>
    <div class="sidebar">
      <?php include '../sidebar_admin.php'; ?>
    </div>
  </aside>

  <!-- Main Content -->
  <div class="content-wrapper pt-3 px-3">
    <div class="container-fluid">
      
      <!-- Top Title Bar -->
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
          <h3 class="font-weight-bold text-dark mb-0">Laporan Laba</h3>
          <span class="badge badge-light border text-secondary px-2 py-1 mt-1">
            <i class="far fa-calendar-alt mr-1"></i> Periode: <?= date('d M Y', strtotime($tgl_awal)); ?> – <?= date('d M Y', strtotime($tgl_akhir)); ?>
          </span>
        </div>
        <div>
          <button onclick="window.print()" class="btn btn-outline-secondary btn-sm rounded-lg mr-1">
            <i class="fas fa-print mr-1"></i> Cetak
          </button>
          <a href="pdf_laba.php?tgl_awal=<?= $tgl_awal; ?>&tgl_akhir=<?= $tgl_akhir; ?>" target="_blank" class="btn btn-danger btn-sm rounded-lg">
            <i class="fas fa-file-pdf mr-1"></i> Ekspor PDF
          </a>
        </div>
      </div>

      <!-- Filter Section -->
      <div class="filter-box mb-4">
        <form method="GET" class="form-row align-items-end">
          <div class="col-md-4 col-sm-6 mb-2 mb-md-0">
            <label class="small font-weight-bold text-muted mb-1">DARI TANGGAL</label>
            <input type="date" name="tgl_awal" class="form-control form-control-sm rounded" value="<?= $tgl_awal; ?>" required>
          </div>
          <div class="col-md-4 col-sm-6 mb-2 mb-md-0">
            <label class="small font-weight-bold text-muted mb-1">SAMPAI TANGGAL</label>
            <input type="date" name="tgl_akhir" class="form-control form-control-sm rounded" value="<?= $tgl_akhir; ?>" required>
          </div>
          <div class="col-md-4 col-sm-12">
            <button type="submit" class="btn btn-primary btn-sm btn-block rounded"><i class="fas fa-filter mr-1"></i> Filter Data</button>
          </div>
        </form>
      </div>

      <!-- Visual Stat Cards -->
      <div class="row mb-4">
        <div class="col-md-4 mb-3 mb-md-0">
          <div class="card card-stat bg-white p-3">
            <div class="d-flex align-items-center">
              <div class="icon-box bg-primary-soft text-primary bg-light mr-3">
                <i class="fas fa-wallet text-primary"></i>
              </div>
              <div>
                <small class="text-uppercase text-muted font-weight-bold">Total Pendapatan</small>
                <h4 class="font-weight-bold mb-0 text-dark">Rp <?= number_format($total_penjualan, 0, ',', '.'); ?></h4>
                <small class="text-success"><i class="fas fa-box mr-1"></i><?= $total_qty_jual; ?> Jumlah Terjual</small>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-4 mb-3 mb-md-0">
          <div class="card card-stat bg-white p-3">
            <div class="d-flex align-items-center">
              <div class="icon-box bg-light text-warning mr-3">
                <i class="fas fa-shopping-cart text-warning"></i>
              </div>
              <div>
                <small class="text-uppercase text-muted font-weight-bold">Total Pembelian (HPP)</small>
                <h4 class="font-weight-bold mb-0 text-dark">Rp <?= number_format($total_pembelian, 0, ',', '.'); ?></h4>
                <small class="text-muted"><i class="fas fa-boxes mr-1"></i><?= $total_qty_beli; ?> Jumlah Dibeli</small>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card card-stat bg-white p-3">
            <div class="d-flex align-items-center">
              <div class="icon-box bg-light <?= ($laba_rugi >= 0) ? 'text-success' : 'text-danger'; ?> mr-3">
                <i class="fas <?= ($laba_rugi >= 0) ? 'fa-chart-line' : 'fa-chart-line-down'; ?>"></i>
              </div>
              <div>
                <small class="text-uppercase text-muted font-weight-bold">Hasil Operasional</small>
                <h4 class="font-weight-bold mb-0 <?= ($laba_rugi >= 0) ? 'text-success' : 'text-danger'; ?>">
                  Rp <?= number_format(abs($laba_rugi), 0, ',', '.'); ?>
                </h4>
                <small class="<?= ($laba_rugi >= 0) ? 'text-success' : 'text-danger'; ?>">
                  <i class="fas <?= ($laba_rugi >= 0) ? 'fa-arrow-up' : 'fa-arrow-down'; ?> mr-1"></i>
                  Selisih: <?= $margin_persen; ?>%
                </small>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Detail Table -->
      <div class="card card-table mb-4">
        <div class="table-responsive">
          <table class="table table-custom">
            <thead>
              <tr>
                <th style="width: 50%;">Rincian Transaksi</th>
                <th style="width: 20%; text-align: center;">Jumlah Barang</th>
                <th style="width: 30%; text-align: right;">Jumlah Nominal (Rp)</th>
              </tr>
            </thead>
            <tbody>
              <!-- Group Penjualan -->
              <tr class="category-header">
                <td colspan="3"><i class="fas fa-arrow-circle-down text-primary mr-2"></i> 1. Pendapatan Penjualan</td>
              </tr>
              <tr>
                <td class="pl-4">Penjualan Produk / Barang</td>
                <td class="text-center"><span class="badge badge-light px-2 py-1 border"><?= $total_qty_jual; ?> Pcs</span></td>
                <td class="text-right font-weight-bold text-dark">Rp <?= number_format($total_penjualan, 0, ',', '.'); ?></td>
              </tr>
              <tr class="subtotal-row">
                <td class="pl-4">TOTAL PENDAPATAN</td>
                <td class="text-center"><?= $total_qty_jual; ?> Pcs</td>
                <td class="text-right text-primary">Rp <?= number_format($total_penjualan, 0, ',', '.'); ?></td>
              </tr>

              <!-- Group Pembelian -->
              <tr class="category-header">
                <td colspan="3"><i class="fas fa-arrow-circle-up text-warning mr-2"></i> 2. Beban & Pembelian Stok (HPP)</td>
              </tr>
              <tr>
                <td class="pl-4">Pembelian Pasokan Barang</td>
                <td class="text-center"><span class="badge badge-light px-2 py-1 border"><?= $total_qty_beli; ?> Pcs</span></td>
                <td class="text-right font-weight-bold text-dark">Rp <?= number_format($total_pembelian, 0, ',', '.'); ?></td>
              </tr>
              <tr class="subtotal-row">
                <td class="pl-4">TOTAL BEBAN PEMBELIAN</td>
                <td class="text-center"><?= $total_qty_beli; ?> Pcs</td>
                <td class="text-right text-warning">Rp <?= number_format($total_pembelian, 0, ',', '.'); ?></td>
              </tr>

              <!-- Results Row -->
              <?php if ($laba_rugi >= 0): ?>
                <tr class="result-profit">
                  <td colspan="2"><i class="fas fa-check-circle mr-2"></i> ESTIMASI LABA BERSIH OPERASIONAL</td>
                  <td class="text-right">Rp <?= number_format($laba_rugi, 0, ',', '.'); ?></td>
                </tr>
              <?php else: ?>
                <tr class="result-loss">
                  <td colspan="2"><i class="fas fa-exclamation-triangle mr-2"></i> ESTIMASI RUGI BERSIH OPERASIONAL</td>
                  <td class="text-right">Rp (<?= number_format(abs($laba_rugi), 0, ',', '.'); ?>)</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>

  <?php include '../footer.php'; ?>
</div>

<?php include '../script.php'; ?>
</body>
</html>