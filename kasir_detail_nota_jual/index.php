<?php
require_once '../database/koneksi.php';

$kode_nota = @$_GET['kode_nota'];

if (!$kode_nota) {
    echo "<script>alert('Kode Nota Tidak Ditemukan!'); window.location.href='kasir_nota_jual';</script>";
    exit;
}

if (isset($_POST['tambah_detail_jual'])) {
    $kode_brg        = mysqli_real_escape_string($koneksi, $_POST['kode_brg']);
    $jumlah          = (int)$_POST['jumlah'];
    $harga_jual      = (int)$_POST['harga_jual'];
    $total_harga_jual = $jumlah * $harga_jual;

    $q_stok = mysqli_query($koneksi, "SELECT stok FROM tbl_barang WHERE kode_brg = '$kode_brg'");
    $is_konsinyasi = false;

    if (mysqli_num_rows($q_stok) > 0) {
        $d_stok = mysqli_fetch_assoc($q_stok);
    } else {
        $q_stok = mysqli_query($koneksi, "SELECT stok FROM tbl_barang_konsinyasi WHERE kode_brg_konsinyasi = '$kode_brg_konsinyasi'");
        $d_stok = mysqli_fetch_assoc($q_stok);
        $is_konsinyasi = true;
    }

    if ($d_stok['stok'] < $jumlah) {
        echo "<script>alert('Stok barang tidak mencukupi! Stok saat ini: " . $d_stok['stok'] . "'); window.location.href='kasir_detail_nota_jual?kode_nota=$kode_nota';</script>";
        exit;
    }

    $query_tambah = "INSERT INTO tbl_detail_nota_jual (kode_nota, kode_brg, jumlah, harga_jual, total_harga_jual) 
                     VALUES ('$kode_nota', '$kode_brg', '$jumlah', '$harga_jual', '$total_harga_jual')";
    $simpan = mysqli_query($koneksi, $query_tambah);

    if ($simpan) {
        if ($is_konsinyasi) {
            mysqli_query($koneksi, "UPDATE tbl_barang_konsinyasi SET stok = stok - $jumlah WHERE kode_brg_konsinyasi = '$kode_brg_konsinyasi'");
        } else {
            mysqli_query($koneksi, "UPDATE tbl_barang SET stok = stok - $jumlah WHERE kode_brg = '$kode_brg'");
        }

        mysqli_query($koneksi, "UPDATE tbl_nota_jual SET total_penjualan = (SELECT SUM(total_harga_jual) FROM tbl_detail_nota_jual WHERE kode_nota = '$kode_nota') WHERE kode_nota = '$kode_nota'");
        echo "<script>alert('Barang berhasil dijual & stok berkurang!'); window.location.href='kasir_detail_nota_jual?kode_nota=$kode_nota';</script>";
    } else {
        echo "<script>alert('Gagal menambah barang: " . mysqli_error($koneksi) . "');</script>";
    }
}

if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus') {
    $id_detail = mysqli_real_escape_string($koneksi, $_GET['id_detail']);
    $kode_brg  = mysqli_real_escape_string($koneksi, $_GET['kode_brg']);
    $jumlah    = (int)$_GET['jumlah'];

    $query_hapus = mysqli_query($koneksi, "DELETE FROM tbl_detail_nota_jual WHERE id_detail = '$id_detail'");

    if ($query_hapus) {
        $q_cek = mysqli_query($koneksi, "SELECT kode_brg FROM tbl_barang WHERE kode_brg = '$kode_brg'");
        if (mysqli_num_rows($q_cek) > 0) {
            mysqli_query($koneksi, "UPDATE tbl_barang SET stok = stok + $jumlah WHERE kode_brg = '$kode_brg'");
        } else {
            mysqli_query($koneksi, "UPDATE tbl_barang_konsinyasi SET stok = stok + $jumlah WHERE kode_brg_konsinyasi = '$kode_brg_konsinyasi'");
        }

        mysqli_query($koneksi, "UPDATE tbl_nota_jual SET total_penjualan = IFNULL((SELECT SUM(total_harga_jual) FROM tbl_detail_nota_jual WHERE kode_nota = '$kode_nota'), 0) WHERE kode_nota = '$kode_nota'");

        echo "<script>alert('Item berhasil dihapus & stok dikembalikan!'); window.location.href='kasir_detail_nota_jual?kode_nota=$kode_nota';</script>";
    }
}

$q_nota = mysqli_query($koneksi, "SELECT * FROM tbl_nota_jual WHERE kode_nota = '$kode_nota'");
$d_nota = mysqli_fetch_array($q_nota);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>kasirPOS - Detail Nota Jual</title>
  <?php include '../css.php'; ?>
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">

  <!-- Navbar & Sidebar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>
  </nav>

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
        <a href="../kasir_nota_jual/" class="btn btn-secondary mb-2"><i class="fas fa-arrow-left"></i> Kembali ke Nota Jual</a>
        <a href="pdf.php?kode_nota=<?= $kode_nota;?>" target="_blank" class="btn btn-danger mb-2"><i class="fas fa-file-pdf"></i> Ekspor PDF</a>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">

        <!-- Informasi Nota Header -->
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title"><i class="fas fa-file-invoice"></i> Informasi Nota Jual #<?= $d_nota['kode_nota']; ?></h3>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-3">
                <strong>Kode Customer / Supplier:</strong> <br><?= isset($d_nota['kode_supplier']) ? $d_nota['kode_supplier'] : '-'; ?>
              </div>
              <div class="col-md-3">
                <strong>Tanggal Penjualan:</strong> <br><?= $d_nota['tgl_penjualan']; ?>
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

        <div class="card card-primary ">
          <div class="card-header p-0 pt-1 border-bottom-0">
            <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="tab-full" data-toggle="pill" href="#content-full" role="tab" aria-controls="content-full" aria-selected="true">FULL</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="tab-barang" data-toggle="pill" href="#content-barang" role="tab" aria-controls="content-barang" aria-selected="false">Barang</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="tab-konsinyasi" data-toggle="pill" href="#content-konsinyasi" role="tab" aria-controls="content-konsinyasi" aria-selected="false">Konsinyasi</a>
              </li>
            </ul>
          </div>

          <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
              <h3 class="card-title font-weight-bold">Daftar Barang Dijual</h3>
              <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal-tambah-item">
                <i class="fas fa-plus"></i> Tambah Item Barang
              </button>
            </div>

            <div class="tab-content" id="custom-tabs-one-tabContent">
              <?php
              // Definisi Kategori Tab
              $tabs = [
                'full'       => 'all',
                'barang'     => 'tbl_barang',
                'konsinyasi' => 'tbl_barang_konsinyasi'
              ];

              foreach ($tabs as $tab_id => $kategori_sumber) :
                $active_class = ($tab_id == 'full') ? 'show active' : '';
              ?>
                <div class="tab-pane fade <?= $active_class; ?>" id="content-<?= $tab_id; ?>" role="tabpanel">
                  <table class="table table-bordered table-striped example-table" style="width: 100%;">
                    <thead>
                      <tr>
                        <th style="width: 50px">No</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Harga Jual</th>
                        <th>Jumlah Jual</th>
                        <th>Total Harga Jual</th>
                        <th style="width: 80px">Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $no = 1;
                      $grand_total = 0;
                      $q_detail = mysqli_query($koneksi, "SELECT * FROM tbl_detail_nota_jual WHERE kode_nota = '$kode_nota'");
                      $rv = mysqli_num_rows($q_detail);
                      $has_item = false;

                      if ($rv > 0) {
                        while ($dt = mysqli_fetch_array($q_detail)) {
                          $kd_brg = $dt['kode_brg'];
                          $nama_brg = '';
                          $jenis_tabel = '';

                          $q_barang = mysqli_query($koneksi, "SELECT nama_brg FROM tbl_barang WHERE kode_brg = '$kd_brg'");
                          if (mysqli_num_rows($q_barang) > 0) {
                            $d_barang = mysqli_fetch_array($q_barang);
                            $nama_brg = $d_barang['nama_brg'];
                            $jenis_tabel = 'tbl_barang';
                          } else {
                            $q_konsin = mysqli_query($koneksi, "SELECT nama_brg FROM tbl_barang_konsinyasi WHERE kode_brg_konsinyasi = '$kd_brg'");
                            if (mysqli_num_rows($q_konsin) > 0) {
                              $d_konsin = mysqli_fetch_array($q_konsin);
                              $nama_brg = $d_konsin['nama_brg'];
                              $jenis_tabel = 'tbl_barang_konsinyasi';
                            } else {
                              $nama_brg = '<em>Barang tidak terdaftar</em>';
                              $jenis_tabel = 'unknown';
                            }
                          }

                          if ($kategori_sumber == 'all' || $kategori_sumber == $jenis_tabel) {
                            $has_item = true;
                            $grand_total += $dt['total_harga_jual'];
                            ?>
                            <tr>
                              <td><?= $no++; ?></td>
                              <td><?= $dt['kode_brg']; ?></td>
                              <td><?= $nama_brg; ?></td>
                              <td>Rp <?= number_format($dt['harga_jual'], 0, ',', '.'); ?></td>
                              <td><?= $dt['jumlah']; ?></td>
                              <td>Rp <?= number_format($dt['total_harga_jual'], 0, ',', '.'); ?></td>
                              <td>
                                <a href="?kode_nota=<?= $kode_nota; ?>&aksi=hapus&kode_brg=<?= $dt['kode_brg']; ?>&jumlah=<?= $dt['jumlah']; ?>"  class="btn btn-danger btn-sm mb-1" 
                                   onclick="return confirm('Yakin Mau Hapus Data Ini?')"><i class="fas fa-trash"></i></a>
                              </td>
                            </tr>
                            <?php
                          }
                        }
                      }

                      if (!$has_item) {
                        echo '<tr><td colspan="7" class="text-center">Data Tidak Ditemukan</td></tr>';
                      }
                      ?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th colspan="5" class="text-right">Grand Total:</th>
                        <th colspan="2" class="text-success">Rp <?= number_format($grand_total, 0, ',', '.'); ?></th>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              <?php endforeach; ?>
            </div>
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
          <h4 class="modal-title">Tambah Barang Jual</h4>
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
                <optgroup label="Barang Reguler">
                  <?php
                  $q_brg = mysqli_query($koneksi, "SELECT * FROM tbl_barang");
                  while ($d_brg = mysqli_fetch_array($q_brg)) {
                    echo "<option value='".$d_brg['kode_brg']."'>[ ".$d_brg['kode_brg']." ] ".$d_brg['nama_brg']." (Stok: ".$d_brg['stok'].")</option>";
                  }
                  ?>
                </optgroup>
                <optgroup label="Barang Konsinyasi">
                  <?php
                  $q_konsin = mysqli_query($koneksi, "SELECT * FROM tbl_barang_konsinyasi");
                  while ($d_ks = mysqli_fetch_array($q_konsin)) {
                    echo "<option value='".$d_ks['kode_brg']."'>[ ".$d_ks['kode_brg']." ] ".$d_ks['nama_brg']." (Stok: ".$d_ks['stok'].")</option>";
                  }
                  ?>
                </optgroup>
              </select>
            </div>
            <div class="form-group">
              <label for="harga_jual">Harga Jual Satuan (Rp)</label>
              <input type="number" name="harga_jual" class="form-control" placeholder="Masukan harga jual" required>
            </div>
            <div class="form-group">
              <label for="jumlah">Jumlah Kuantitas Jual</label>
              <input type="number" name="jumlah" class="form-control" min="1" placeholder="Masukan jumlah" required>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            <button type="submit" name="tambah_detail_jual" class="btn btn-success">Simpan & Kurangi Stok</button>
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