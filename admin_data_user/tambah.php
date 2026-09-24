<?php
require_once ('../database/koneksi.php');

if (isset($_POST['tambah_user'])) {
    $username   = trim(mysqli_real_escape_string($koneksi, $_POST['username']));
    $password   = trim(mysqli_real_escape_string($koneksi, $_POST['password']));
    $peran      = trim(mysqli_real_escape_string($koneksi, $_POST['peran']));
    $nama_panggilan      = trim(mysqli_real_escape_string($koneksi, $_POST['nama_panggilan']));
    $pin        = trim(mysqli_real_escape_string($koneksi, $_POST['pin']));


    $cek_user = mysqli_query($koneksi, "SELECT username FROM tbl_user WHERE username='$username'")or die(mysqli_error($koneksi));
    $rv = mysqli_num_rows($cek_user);

    if ($rv == 1) {
        echo '<script> alert ("Data Sudah Terdaftar! Input yang lain");
        window.location.href="../admin_data_user/"</script>';
    }else {
        $query_simpan = mysqli_query ($koneksi, "INSERT INTO tbl_user 
        (username, password, peran, nama_panggilan, pin) 
        VALUES 
        ('$username', '$password', '$peran', '$nama_panggilan', '$pin')")or die(mysqli_error($koneksi));

        echo '<script> alert ("Data Berhasil Disimpan"); 
        window.location.href="../admin_data_user" </script>';
    }
}
?>