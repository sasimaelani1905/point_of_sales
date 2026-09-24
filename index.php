<?php
require_once 'database/koneksi.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Log in</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="asett/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="asett/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="asett/dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="#"><b>POINT OF SALES</b></a>
  </div>

  <div class="card">
    <div class="card-body login-card-body">

      <form action="pin2fa" method="post">
        <div class="input-group mb-3">
          <input type="text" name="username" class="form-control" placeholder="Username" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
     
          <input type="password" name="password" class="form-control" placeholder="Password" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>

        <div class="social-auth-links text-center mb-3">
        
          <button type="submit" name="point_of_sales" class="btn btn-block btn-primary">
            <i class="fas fa-sign-in-alt"></i> Masuk
          </button>
        </div>
      </form>

        <?php
        if (isset($_POST['point_of_sales'])) {
            $username = trim(mysqli_real_escape_string($koneksi, $_POST['username']));
            $password = sha1(trim(mysqli_real_escape_string($koneksi, $_POST['password'])));

            $query_cek = mysqli_query($koneksi, "SELECT * FROM tbl_user WHERE username='$username' AND password='$password'") or die(mysqli_error($koneksi));

            if (mysqli_num_rows($query_cek) === 1) {
                $data = mysqli_fetch_assoc($query_cek);

                $_SESSION['peran']          = $data['peran'];
                $_SESSION['pin']            = $data['pin'];
                $_SESSION['username']       = $data['username'];
                $_SESSION['nama_panggilan'] = $data['nama_panggilan'];

                echo '<script>window.location = "pin2fa/";</script>';
            } else {
                echo '<script>alert("Username atau password salah!");</script>';
            }
          }
        ?>
    </div>
  </div>
</div>

<!-- jQuery -->
<script src="asett/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="asett/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="asett/dist/js/adminlte.min.js"></script>
</body>
</html>