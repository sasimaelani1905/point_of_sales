<?php
require_once '../database/koneksi.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Masukan PIN</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../asett/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="../asett/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../asett/dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="../asett/index2.html"><b>2 FACTOR AUTHENTICATION</b></a>
  </div>
  
  <div class="card">
    <div class="card-body login-card-body">

      <form action="../home_superadmin" method="post">
        <div class="input-group mb-3">
          <input type="number" name="pin" class="form-control" placeholder="Masukan PIN Anda" required autofocus>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="social-auth-links text-center mb-3">
          <button type="submit" name="submit" class="btn btn-block btn-primary">
            <i class="fas fa-key"></i> Masuk
          </button>
        </div>
      </form>

      <?php
        if (isset($_POST['submit'])) {
                  
            $peran = isset($_SESSION['peran']) ? $_SESSION['peran'] : '';
            $pin   = isset($_SESSION['pin']) ? $_SESSION['pin'] : '';

            $pin_inputan = trim(mysqli_real_escape_string($koneksi, $_POST['pin']));

            if ($pin_inputan == $pin) {
                if ($peran == 'S') {
                    echo'<script>window.location= "../home_superadmin/";</script>';
                } else {
                    echo'<script>window.location= "../home_kasir/";</script>';
                }
            } else {
                echo '<script>alert("PIN Anda Salah! SILAHKAN LOGIN KEMBALI");window.location = "../";</script>';
            }
        }
      ?>

    </div>
  </div>
</div>

<!-- jQuery -->
<script src="../asett/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../asett/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../asett/dist/js/adminlte.min.js"></script>
</body>
</html>