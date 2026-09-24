<?php
require_once '../database/koneksi.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php 
  include'../css.php';

  $hal ='beranda_user';
  ?>
</head>

<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-user"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-user"></i> Profile
          </a>
          <a href="#" class="dropdown-item">
            <i class="fas fa-sign-out-alt"></i> logout
          </a>
        </div>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="info">
          <a href="#" class="d-block">POINT OF SELES</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
    <?php 
    include '../sidebar_admin.php' ?>
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
    <div class="content">
      <div class="container-fluid">
       <div class="card">
              <div class="card-header">
                <h3 class="card-title">Edit Data User</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <?php 
                $username = @$_GET['username'];
                $password = @$_GET['password'];
                $peran = @$_GET['peran'];
                $nama_panggilan = @$_GET['nama_panggilan'];
                $pin = @$_GET['pin'];
                ?>
                <form action="ubah.php" method="post">
                <div class="form-group">
                  <label for="username">Username</label>
                  <input type="text" name="username" class="form-control" id="username" value="<?= $username; ?>" placeholder="Masukan Username" readonly>
                </div>
                <div class="form-group">
                  <label for="password">Password</label>
                  <input type="text" name="password" class="form-control" id="password" value="<?= $password; ?>" placeholder="Masukan Password" required>
                </div>
                <div class="form-group">
                  <label>Peran</label>
                  <select class="form-control" name="peran" >
                    <option value="">-- Pilih Peran --</option>
                    <option value="S" <?= ($peran == 'S') ? 'selected': '' ;?>>Superadmin</option>
                    <option value="K" <?= ($peran == 'K') ? 'selected': '' ;?>>Kasir</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="nama_panggilan">Nama Panggilan</label>
                  <input type="text" name="nama_panggilan" class="form-control" id="nama_panggilan" value="<?= $nama_panggilan; ?>" placeholder="Masukan Nama Panggilan" required>
                </div>
                <div class="form-group">
                  <label for="pin">PIN</label>
                  <input type="text" name="pin" class="form-control" id="pin" value="<?= $pin; ?>" placeholder="Masukan PIN" required>
                </div>

            </div>
            <div class="modal-footer justify-content-between">
              <button type="submit" name="btn_edit" class="btn btn-primary btn-block">Edit</button>
            </div>
            </form>
            </div>
            <!-- /.card-body -->
          </div>
        <!-- /.card -->
      </div>
      <!-- /.container-fluid -->
    </div>
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
              <h4 class="modal-title">Tambah Data Pengguna</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="tambah.php" method="post">
            
            </form>
      
  <!-- Main Footer -->
    <?php include '../footer.php' ?>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<?php include '../script.php' ?>
</body>
</html>