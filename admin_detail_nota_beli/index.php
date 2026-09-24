<?php
require_once '../database/koneksi.php';

$kode_nota = @$_GET['kode_nota'];

if (!$kode_nota) {
    echo "<script>alert('Kode Nota Tidak Ditemukan!'); window.location.href='admin_nota_beli';</script>";
    exit;
}

if (isset($_POST['tambah_detail_beli'])) {
    $kode_brg = mysqli_real_escape_string($koneksi, $_POST['kode_brg']);
    $jumlah      = (int)$_POST['jumlah'];
    $harga_beli  = (int)$_POST['harga_beli'];
    $total_harga_beli    = $jumlah * $harga_beli;

    $query_tambah = "INSERT INTO tbl_detail_nota_beli (kode_nota, kode_brg, jumlah, harga_beli, total_harga_beli) 
                     VALUES ('$kode_nota', '$kode_brg', '$jumlah', '$harga_beli', '$total_harga_beli')";
    $simpan = mysqli_query($koneksi, $query_tambah);

    if ($simpan) {
        mysqli_query($koneksi, "UPDATE tbl_barang SET stok = stok + $jumlah WHERE kode_brg = '$kode_brg'");

        mysqli_query($koneksi, "UPDATE tbl_nota_beli SET total_pembelian = (SELECT SUM(total_harga_beli) FROM tbl_detail_nota_beli WHERE kode_nota = '$kode_nota') WHERE kode_nota = '$kode_nota'");

        echo "<script>alert('Barang berhasil ditambahkan & stok bertambah!'); window.location.href='admin_detail_nota_beli?kode_nota=$kode_nota';</script>";
    } else {
        echo "<script>alert('Gagal menambah barang: " . mysqli_error($koneksi) . "');</script>";
    }
}

if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus') {
    $id_detail   = mysqli_real_escape_string($koneksi, $_GET['id_detail']);
    $kode_brg = mysqli_real_escape_string($koneksi, $_GET['kode_brg']);
    $jumlah      = (int)$_GET['jumlah'];

    $query_hapus = mysqli_query($koneksi, "DELETE FROM tbl_detail_nota_beli WHERE id_detail = '$id_detail'");

    if ($query_hapus) {
        mysqli_query($koneksi, "UPDATE tbl_barang SET stok = stok - $jumlah WHERE kode_brg = '$kode_brg'");

        mysqli_query($koneksi, "UPDATE tbl_nota_beli SET total_pembelian = IFNULL((SELECT SUM(total_harga_beli) FROM tbl_detail_nota_beli WHERE kode_nota = '$kode_nota'), 0) WHERE kode_nota = '$kode_nota'");

        echo "<script>alert('Item berhasil dihapus & stok dikurangi kembali!'); window.location.href='admin_detail_nota_beli?kode_nota=$kode_nota';</script>";
    }
}
$q_nota = mysqli_query($koneksi, "SELECT * FROM tbl_nota_beli WHERE kode_nota = '$kode_nota'");
$d_nota = mysqli_fetch_array($q_nota);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Detail Nota Beli | AdminPOS</title>
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
        <a href="../admin_nota_beli/" class="btn btn-secondary mb-2"><i class="fas fa-arrow-left"></i> Kembali ke Nota Beli</a>
        <a href="pdf.php?kode_nota=<?= $kode_nota;?>" target="_blank" class="btn btn-danger mb-2"><i class="fas fa-file-pdf"></i>Ekspor PDF</a>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        
        <!-- Header Informasi Nota -->
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title"><i class="fas fa-file-invoice"></i> Informasi Nota Beli #<?= $d_nota['kode_nota']; ?></h3>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-3">
                <strong>Kode Supplier:</strong> <br><?= $d_nota['kode_supplier']; ?>
              </div>
              <div class="col-md-3">
                <strong>Tanggal Pembelian:</strong> <br><?= $d_nota['tgl_pembelian']; ?>
              </div>
              <div class="col-md-3">
                <strong>Status Bayar:</strong> <br>
                <span class="badge badge-info"><?= $d_nota['status']; ?></span>
              </div>
              <div class="col-md-3">
                <strong>Keterangan:</strong> <br><?= $d_nota['keterangan']; ?>
              </div>
            </div>
          </div>
        </div>

        <!-- Tabel Item Barang -->
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Daftar Barang Dibeli</h3>
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
                  <th style="width: 50px">No</th>
                  <th>Kode Barang</th>
                  <th>Nama Barang</th>
                  <th>Harga Beli</th>
                  <th>Jumlah Beli</th>
                  <th>total_harga_beli</th>
                  <th style="width: 80px">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $no = 1;
                $grand_total = 0;
                
                $q_detail = mysqli_query($koneksi, "SELECT * FROM tbl_detail_nota_beli WHERE kode_nota = '$kode_nota'");

                if (mysqli_num_rows($q_detail) > 0) {
                  while ($dt = mysqli_fetch_array($q_detail)) {
                    $grand_total += $dt['total_harga_beli'];
                    $kd_brg = $dt['kode_brg'];

                    // Query terpisah untuk mengambil nama_brg dari tbl_barang berdasarkan kode_brg
                    $q_barang = mysqli_query($koneksi, "SELECT nama_brg FROM tbl_barang WHERE kode_brg = '$kd_brg'");
                    $d_barang = mysqli_fetch_array($q_barang);
                    $nama_brg = isset($d_barang['nama_brg']) ? $d_barang['nama_brg'] : '<em>Barang tidak terdaftar</em>';
                    ?>
                    <tr>
                      <td><?= $no++; ?></td>
                      <td><?= $dt['kode_brg']; ?></td>
                      <td><?= $nama_brg; ?></td>
                      <td>Rp <?= number_format($dt['harga_beli'], 0, ',', '.'); ?></td>
                      <td><?= $dt['jumlah']; ?></td>
                      <td>Rp <?= number_format($dt['total_harga_beli'], 0, ',', '.'); ?></td>
                      <td>
                        <a href="hapus.php?urut=<?= $urut; ?>&aksi=hapus&&kode_brg=<?= $dt['kode_brg']; ?>&jumlah=<?= $dt['jumlah']; ?>" 
                           class="btn btn-danger btn-sm" 
                           onclick="return confirm('Yakin menghapus item ini? Stok barang akan dikurangi kembali.')">
                          <i class="fas fa-trash"></i>
                        </a>
                      </td>
                    </tr>
                    <?php
                  }
                } else {
                  echo '<tr><td colspan="7" class="text-center">Belum ada item barang pada nota ini.</td></tr>';
                }
                ?>
              </tbody>
              <tfoot>
                <tr>
                  <th colspan="5" class="text-right">Grand Total Pembelian:</th>
                  <th colspan="2" class="text-success">Rp <?= number_format($grand_total, 0, ',', '.'); ?></th>
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
          <h4 class="modal-title">Tambah Barang Masuk</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="" method="post">
          <div class="modal-body">
            <div class="form-group">
              <label for="kode_brg">Pilih Barang</label>
              <select name="kode_brg" class="form-control" required>
                <option value="">-- Pilih Barang --</option>
                <?php
                $kode_supplier_nota = $d_nota['kode_supplier'];
                $q_brg = mysqli_query($koneksi, "SELECT * FROM tbl_barang WHERE kode_supplier = '$kode_supplier_nota'");
                while ($d_brg = mysqli_fetch_array($q_brg)) {
                  echo "<option value='".$d_brg['kode_brg']."'>[ ".$d_brg['kode_brg']." ] ".$d_brg['nama_brg']." (Stok: ".$d_brg['stok'].")</option>";
                }
                ?>
              </select>
            </div>
            <div class="form-group">
              <label for="harga_beli">Harga Beli Satuan (Rp)</label>
              <input type="number" name="harga_beli" class="form-control" placeholder="Masukan harga beli" required>
            </div>
            <div class="form-group">
              <label for="jumlah">Jumlah Kuantitas Beli</label>
              <input type="number" name="jumlah" class="form-control" min="1" placeholder="Masukan jumlah" required>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            <button type="submit" name="tambah_detail_beli" class="btn btn-success">Simpan & Tambah Stok</button>
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