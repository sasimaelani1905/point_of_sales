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

 $hal ='admin_user';
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
              <h3 class="card-title">Data Admin</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <button type="button" class="btn btn-success mb-2" data-toggle="modal" data-target="#modal-tambah"><i class="fas fa-plus"></i> Tambah Data</button>

              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>No</th>
                  <th>Username</th>
                  <th>Password</th>
                  <th>Peran</th>
                  <th>Nama Panggilan</th>
                  <th>PIN</th>
                  <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                  <?php
                  $panggil_data_admin = mysqli_query($koneksi, "SELECT * FROM tbl_user") or die(mysqli_error($koneksi));
                  $no = 1;
                  $rv = mysqli_num_rows($panggil_data_admin);
                  if ($rv > 0) {
                    while ($data = mysqli_fetch_array($panggil_data_admin)) {
                      $username = $data['username'];
                      $password = $data['password'];
                      $peran = $data['peran'];
                      $nama_panggilan = $data['nama_panggilan'];
                      $pin = $data['pin'];
                      ?>
                      <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $username ?></td>
                        <td><?= $password ?></td>
                        <td><?= $peran ?></td>
                        <td><?= $nama_panggilan ?></td>
                        <td><?= $pin ?></td>
                        <td> 
                          <a href="edit.php?username=<?= $data['username']; ?>password=<?=$data['password'];?>&peran=<?=$data['peran'];?>&nama_panggilan=<?=$data['nama_panggilan'];?>&pin=<?=$data['pin'];?>"
                            class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>

                          <a href="hapus.php?username=<?= $data['username']; ?>" 
                          class="btn btn-danger btn-sm" onclick="return confirm('Yakin Mau Hapus Data Ini?')">
                          <i class="fas fa-trash"></i></a>
                        </td>
                      </tr>
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
            <h4 class="modal-title">Tambah Data User</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form action="tambah.php" method="post">
            <div class="modal-body">
              <div class="form-group">
                <label for="username">Username</label>
                <input type="number" name="username" class="form-control" id="username" placeholder="Masukan Username" required>
              </div>
              <div class="form-group">
                <label for="password">Password</label>
                <input type="number" name="password" class="form-control" id="password" placeholder="Masukan Password" required>
              </div>
              <div class="form-group">
                <label for="peran">Peran</label>
                <input type="text" name="peran" class="form-control" id="peran" placeholder="Masukan Peran" required>
              </div>
              <div class="form-group">
                <label for="nama_panggilan">Nama Panggilan</label>
                <input type="text" name="nama_panggilan" class="form-control" id="nama_panggilan" placeholder="Masukan Nama Panggilan" required>
              </div>
              <div class="form-group">
                <labe for="pin">PIN</label>
                <input type="number" name="pin" class="form-control" id="pin" placeholder="Masukan PIN" required>
              </div>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
              <button type="submit" name ="tambah_user" class="btn btn-primary">Tambah</button>
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