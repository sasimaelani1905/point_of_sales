<?php
require_once '../database/koneksi.php';

$no_nota_konsinyasi = isset($_GET['no_nota_konsinyasi']) ? mysqli_real_escape_string($koneksi, $_GET['no_nota_konsinyasi']) : '';

if (!$no_nota_konsinyasi) {
    echo "<script>alert('No. Nota Konsinyasi Tidak Ditemukan!'); window.location.href='../admin_nota_konsinyasi';</script>";
    exit;
}

// 1. TAMBAH DETAIL NOTA KONSINYASI
if (isset($_POST['tambah_detail_konsinyasi'])) {
    $kode_brg_konsinyasi = mysqli_real_escape_string($koneksi, $_POST['kode_brg_konsinyasi']);
    $jumlah_titip        = (int)$_POST['jumlah_titip'];
    $harga_satuan        = (int)$_POST['harga_satuan'];
    $subtotal            = $jumlah_titip * $harga_satuan;

    $query_tambah = "INSERT INTO tbl_detail_nota_konsinyasi (no_nota_konsinyasi, kode_brg_konsinyasi, jumlah_titip, harga_satuan, subtotal) 
                     VALUES ('$no_nota_konsinyasi', '$kode_brg_konsinyasi', '$jumlah_titip', '$harga_satuan', '$subtotal')";
    $simpan = mysqli_query($koneksi, $query_tambah);

    if ($simpan) {
        // Update total_item pada Header Nota Konsinyasi
        mysqli_query($koneksi, "UPDATE tbl_nota_konsinyasi SET total_item = (SELECT IFNULL(SUM(jumlah_titip), 0) FROM tbl_detail_nota_konsinyasi WHERE no_nota_konsinyasi = '$no_nota_konsinyasi') WHERE no_nota_konsinyasi = '$no_nota_konsinyasi'");

        // Tambahkan stok barang konsinyasi
        mysqli_query($koneksi, "UPDATE tbl_barang_konsinyasi SET stok = stok + $jumlah_titip WHERE kode_brg_konsinyasi = '$kode_brg_konsinyasi'");

        echo "<script>alert('Item konsinyasi berhasil ditambahkan!'); window.location.href='index.php?no_nota_konsinyasi=$no_nota_konsinyasi';</script>";
    } else {
        echo "<script>alert('Gagal menambah item: " . mysqli_error($koneksi) . "');</script>";
    }
}

// 2. HAPUS DETAIL NOTA KONSINYASI
if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus') {
    $id_detail           = mysqli_real_escape_string($koneksi, $_GET['id_detail']);
    $kode_brg_konsinyasi = mysqli_real_escape_string($koneksi, $_GET['kode_brg_konsinyasi']);
    $jumlah_titip        = (int)$_GET['jumlah_titip'];

    $query_hapus = mysqli_query($koneksi, "DELETE FROM tbl_detail_nota_konsinyasi WHERE id_detail = '$id_detail'");

    if ($query_hapus) {
        // Kurangi kembali stok barang konsinyasi
        mysqli_query($koneksi, "UPDATE tbl_barang_konsinyasi SET stok = GREATEST(stok - $jumlah_titip, 0) WHERE kode_brg_konsinyasi = '$kode_brg_konsinyasi'");

        // Update total_item pada Header Nota Konsinyasi
        mysqli_query($koneksi, "UPDATE tbl_nota_konsinyasi SET total_item = (SELECT IFNULL(SUM(jumlah_titip), 0) FROM tbl_detail_nota_konsinyasi WHERE no_nota_konsinyasi = '$no_nota_konsinyasi') WHERE no_nota_konsinyasi = '$no_nota_konsinyasi'");

        echo "<script>alert('Item konsinyasi berhasil dihapus!'); window.location.href='index.php?no_nota_konsinyasi=$no_nota_konsinyasi';</script>";
    }
}

// Ambil Data Header Nota
$q_nota = mysqli_query($koneksi, "
    SELECT n.*, b.nama_brg 
    FROM tbl_nota_konsinyasi n
    LEFT JOIN tbl_barang_konsinyasi b ON n.kode_brg_konsinyasi = b.kode_brg_konsinyasi
    WHERE n.no_nota_konsinyasi = '$no_nota_konsinyasi'
");
$d_nota = mysqli_fetch_array($q_nota);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminPOS - Detail Nota Konsinyasi</title>
  <?php include '../css.php'; ?>
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
  </nav>

  <!-- Sidebar Container -->
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
      <div class="container-fluid">
        <a href="../admin_nota_konsinyasi/" class="btn btn-secondary mb-2"><i class="fas fa-arrow-left"></i> Kembali ke Nota Konsinyasi</a>
        <a href="pdf.php?no_nota_konsinyasi=<?= urlencode($no_nota_konsinyasi); ?>" target="_blank" class="btn btn-danger mb-2"><i class="fas fa-file-pdf"></i> Ekspor PDF</a>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">

        <!-- Header Informasi Nota Konsinyasi -->
        <div class="card card-success card-outline">
          <div class="card-header">
            <h3 class="card-title"><i class="fas fa-file-contract"></i> Informasi Nota Konsinyasi #<?= htmlspecialchars($d_nota['no_nota_konsinyasi']); ?></h3>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-3">
                <strong>Supplier:</strong> <br><?= htmlspecialchars($d_nota['nama_supplier']); ?>
              </div>
              <div class="col-md-3">
                <strong>Barang Utama:</strong> <br><?= htmlspecialchars(isset($d_nota['nama_brg']) ? $d_nota['nama_brg'] : '-'); ?>
              </div>
              <div class="col-md-3">
                <strong>Tanggal Titip:</strong> <br><?= date('d-m-Y', strtotime($d_nota['tgl_titip'])); ?>
              </div>
              <div class="col-md-3">
                <strong>Total Item:</strong> <br><span class="badge badge-success"><?= number_format($d_nota['total_item'], 0, ',', '.'); ?> Item</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Tabel Item Barang Konsinyasi -->
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Daftar Item Barang Titipan</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal-tambah-item">
                <i class="fas fa-plus"></i> Tambah Item Barang
              </button>
            </div>
          </div>
          <div class="card-body">
            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th style="width: 50px" class="text-center">No</th>
                  <th>Kode Barang</th>
                  <th>Nama Barang</th>
                  <th class="text-right">Harga Satuan</th>
                  <th class="text-center">Jumlah Titip</th>
                  <th class="text-right">Subtotal</th>
                  <th style="width: 80px" class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $no = 1;
                $grand_total = 0;
                $total_qty = 0;

                $q_detail = mysqli_query($koneksi, "
                  SELECT d.*, b.nama_brg 
                  FROM tbl_detail_nota_konsinyasi d
                  LEFT JOIN tbl_barang_konsinyasi b ON d.kode_brg_konsinyasi = b.kode_brg_konsinyasi
                  WHERE d.no_nota_konsinyasi = '$no_nota_konsinyasi'
                ");

                if (mysqli_num_rows($q_detail) > 0) {
                  while ($dt = mysqli_fetch_array($q_detail)) {
                    $grand_total += $dt['subtotal'];
                    $total_qty   += $dt['jumlah_titip'];
                    $nama_brg     = isset($dt['nama_brg']) ? $dt['nama_brg'] : '<em>Barang tidak terdaftar</em>';
                    ?>
                    <tr>
                      <td class="text-center"><?= $no++; ?></td>
                      <td><?= htmlspecialchars($dt['kode_brg_konsinyasi']); ?></td>
                      <td><?= htmlspecialchars($nama_brg); ?></td>
                      <td class="text-right">Rp <?= number_format($dt['harga_satuan'], 0, ',', '.'); ?></td>
                      <td class="text-center"><?= number_format($dt['jumlah_titip'], 0, ',', '.'); ?></td>
                      <td class="text-right">Rp <?= number_format($dt['subtotal'], 0, ',', '.'); ?></td>
                      <td class="text-center">
                        <a href="index.php?no_nota_konsinyasi=<?= urlencode($no_nota_konsinyasi); ?>&aksi=hapus&id_detail=<?= $dt['id_detail']; ?>&kode_brg_konsinyasi=<?= urlencode($dt['kode_brg_konsinyasi']); ?>&jumlah_titip=<?= $dt['jumlah_titip']; ?>" 
                           class="btn btn-danger btn-sm" 
                           onclick="return confirm('Yakin ingin menghapus item ini?')">
                           <i class="fas fa-trash"></i>
                        </a>
                      </td>
                    </tr>
                    <?php
                  }
                } else {
                  echo '<tr><td colspan="7" class="text-center">Belum ada item barang pada nota konsinyasi ini.</td></tr>';
                }
                ?>
              </tbody>
              <tfoot>
                <tr>
                  <th colspan="4" class="text-right">Total Item & Grand Total Nominal:</th>
                  <th class="text-center"><?= number_format($total_qty, 0, ',', '.'); ?></th>
                  <th class="text-right text-success">Rp <?= number_format($grand_total, 0, ',', '.'); ?></th>
                  <th></th>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>

      </div>
    </section>
  </div>

  <!-- Modal Tambah Item Barang -->
  <div class="modal fade" id="modal-tambah-item">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Tambah Barang Titipan Konsinyasi</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="" method="post">
          <div class="modal-body">
            <div class="form-group">
              <label for="kode_brg_konsinyasi">Pilih Barang Konsinyasi</label>
              <select name="kode_brg_konsinyasi" class="form-control" required>
                <option value="">-- Pilih Barang --</option>
                <?php
                $q_brg = mysqli_query($koneksi, "SELECT * FROM tbl_barang_konsinyasi");
                while ($d_brg = mysqli_fetch_array($q_brg)) {
                  echo "<option value='".$d_brg['kode_brg_konsinyasi']."'>[ ".$d_brg['kode_brg_konsinyasi']." ] ".$d_brg['nama_brg']." (Merk: ".$d_brg['merk'].")</option>";
                }
                ?>
              </select>
            </div>
            <div class="form-group">
              <label for="harga_satuan">Harga Satuan (Rp)</label>
              <input type="number" name="harga_satuan" class="form-control" placeholder="Masukkan harga titip per unit" required>
            </div>
            <div class="form-group">
              <label for="jumlah_titip">Jumlah Titip Item</label>
              <input type="number" name="jumlah_titip" class="form-control" min="1" placeholder="Masukkan jumlah item" required>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            <button type="submit" name="tambah_detail_konsinyasi" class="btn btn-success">Simpan & Tambah Stok</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <?php include '../footer.php'; ?>
  <?php include '../script.php'; ?>

</div>
</body>
</html>