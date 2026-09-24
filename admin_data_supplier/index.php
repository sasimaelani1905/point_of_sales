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
  <title>AdminLTE 3 | Log In</title>

 <?php
 include '../css.php';

 $hal ='admin_supplier';
 ?>
</head>
<body class="hold-transition with-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-with">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button">
         <i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

      <!-- Notifications Dropdown Menu -->
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
    <!-- Brand Logo -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="info">
          <a href="#" class="d-block">POINT OF SALES</a>
        </div>
      </div>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar Menu -->
      <?php
      include '../sidebar_admin.php';
      ?>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="card">
            <div class="card-header">
              <h3 class="card-title">Data Supplier</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <button type="button" class="btn btn-success mb-2" data-toggle="modal" data-target="#modal-tambah"><i class="fas fa-plus"></i> Tambah Data</button>

              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>No</th>
                  <th>Kode Supplier</th>
                  <th>Nama Supplier</th>
                  <th>Nama Pic</th>
                  <th>Kontak Pic</th>
                  <th>Alamat Supplier</th>
                  <th>Website</th>
                  <th>Akun IG</th>
                  <th>Akun Tiktok</th>
                  <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                  <?php
                  $panggil_data_supplier = mysqli_query($koneksi, "SELECT * FROM tbl_supplier") or die(mysqli_error($koneksi));
                  $no = 1;
                  $rv = mysqli_num_rows($panggil_data_supplier);
                  if ($rv > 0) {
                    while ($data = mysqli_fetch_array($panggil_data_supplier)) {
                      $kode_supplier = $data['kode_supplier'];
                      $nama_supplier = $data['nama_supplier'];
                      $nama_pic = $data['nama_pic'];
                      $kontak_pic = $data['kontak_pic'];
                      $alamat_supplier = $data['alamat_supplier'];
                      $website = $data['website'];
                      $akun_ig = $data['akun_ig'];
                      $akun_tiktok = $data['akun_tiktok'];
                      ?>
                      <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $kode_supplier ?></td>
                        <td><?= $nama_supplier ?></td>
                        <td><?= $nama_pic ?></td>
                        <td><?= $kontak_pic ?></td>
                        <td><?= $alamat_supplier ?></td>
                        <td><?= $website ?></td>
                        <td><?= $akun_ig ?></td>
                        <td><?= $akun_tiktok ?></td>
                        <td> 
                          <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#modal-edit<?= $kode_supplier; ?>" title="Edit">
                            <i class="fas fa-pen"></i>
                          </button>

                          <a href="hapus.php?kode_supplier=<?= $data['kode_supplier']; ?>" 
                          class="btn btn-danger btn-sm" onclick="return confirm('Yakin Mau Hapus Data Ini?')">
                          <i class="fas fa-trash"></i></a>
                        </td>
                      </tr>

                      <div class="modal fade" id="modal-edit<?= $kode_supplier; ?>">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h4 class="modal-title">Edit Data Supplier</h4>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <form action="ubah.php" method="post">
                              <div class="modal-body">
                                <div class="form-group">
                                  <label for="kode_supplier">Kode Supplier</label>
                                  <input type="number" class="form-control" value="<?= $kode_supplier; ?>" disabled>
                                  <input type="hidden" name="kode_supplier" value="<?= $kode_supplier; ?>">
                                </div>
                                <div class="form-group">
                                  <label for="nama_supplier">Nama Supplier</label>
                                  <input type="text" name="nama_supplier" class="form-control" value="<?= htmlspecialchars($nama_supplier); ?>" placeholder="Masukan Nama Supplier" required>
                                </div>
                                <div class="form-group">
                                  <label for="nama_pic">Nama PIC</label>
                                  <input type="text" name="nama_pic" class="form-control" value="<?= htmlspecialchars($nama_pic); ?>" placeholder="Masukan Nama PIC" required>
                                </div>
                                <div class="form-group">
                                  <label for="kontak_pic">Kontak PIC</label>
                                  <input type="tel" name="kontak_pic" class="form-control" value="<?= htmlspecialchars($kontak_pic); ?>" placeholder="Masukan Kontak PIC" required>
                                </div>
                                <div class="form-group">
                                  <labe for="alamat_supplier">Alamat Supplier</label>
                                  <input type="text" name="alamat_supplier" class="form-control" value="<?= htmlspecialchars($alamat_supplier); ?>" placeholder="Masukan Alamat" required>
                                </div>
                                <div class="form-group">
                                  <labe for="website">Website</label>
                                  <input type=text" name="website" class="form-control" value="<?= htmlspecialchars($website); ?>" placeholder="Masukan Webasite" required>
                                </div>
                                <div class="form-group">
                                  <labe for="akun_ig">Akun IG</label>
                                  <input type="text" name="akun_ig" class="form-control" value="<?= htmlspecialchars($akun_ig); ?>" placeholder="Masukan Nama Akun" required>
                                </div>
                                <div class="form-group">
                                  <labe for="akun_tiktok">Akun Tiktok</label>
                                  <input type="tex" name="akun_tiktok" class="form-control" value="<?= htmlspecialchars($akun_tiktok); ?>" placeholder="Masukan Nama Akun" required>
                                </div>
                              </div>
                              <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                                <button type="submit" name ="ubah_supplier" class="btn btn-primary">Simpan</button>
                              </div>
                            </form>
                          </div>
                          <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                      </div>
                      
                      <?php
                    }
                  }else {
                    echo '<center>Data Tidak Ditemukan</center>';
                  }
                  ?>
                </tbody>
              </table>
            </div>
              <!-- /.card-body -->
          </div>
            <!-- /.card -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->        
      </div><!--/. container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->

    <div class="modal fade" id="modal-tambah">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Tambah Data Supplier</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form action="tambah.php" method="post">
            <div class="modal-body">
              <div class="form-group">
                <label for="kode_supplier">Kode Supplier</label>
                <input type="number" name="kode_supplier" class="form-control" id="kode_supplier" placeholder="Masukan Kode Supplier" required>
              </div>
              <div class="form-group">
                <label for="nama_supplier">Nama Supplier</label>
                <input type="text" name="nama_supplier" class="form-control" id="nama_supplier" placeholder="Masukan Nama Supplier" required>
              </div>
              <div class="form-group">
                <label for="nama_pic">Nama PIC</label>
                <input type="text" name="nama_pic" class="form-control" id="nama_pic" placeholder="Masukan Nama PIC" required>
              </div>
              <div class="form-group">
                <label for="kontak_pic">Kontak PIC</label>
                <input type="tel" name="kontak_pic" class="form-control" id="kontak_pic" placeholder="Masukan Kontak PIC" required>
              </div>
              <div class="form-group">
                <labe for="alamat_supplier">Alamat Supplier</label>
                <input type="text" name="alamat_supplier" class="form-control" id="alamat_supplier" placeholder="Masukan Alamat Supplier" required>
              </div>
              <div class="form-group">
                <labe for="website">Website</label>
                <input type=text" name="website" class="form-control" id="website" placeholder="Masukan Website" required>
              </div>
              <div class="form-group">
                <labe for="akun_ig">Akun IG</label>
                <input type="text" name="akun_ig" class="form-control" id="akun_ig" placeholder="Masukan Akun IG" required>
              </div>
              <div class="form-group">
                <labe for="akun_tiktok">Akun Tiktok</label>
                <input type="tex" name="akun_tiktok" class="form-control" id="akun_tiktok" placeholder="Masukan Akun Tiktok" required>
              </div>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
              <button type="submit" name ="tambah_supplier" class="btn btn-primary">Tambah</button>
            </div>
          </form>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>

    
  <!-- /.modal -->
<?php
include '../footer.php';
?>

<!-- REQUIRED SCRIPTS -->
<?php
include '../script.php';
?>

</body>
</html>
<!-- <?php
// }
?> -->