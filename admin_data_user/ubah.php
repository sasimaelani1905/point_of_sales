<?php
require_once '../database/koneksi.php';

if (isset($_POST['btn_edit'])) {

    $username  = trim(mysqli_real_escape_string($con, $_POST['username']) );
    $nama  = trim(mysqli_real_escape_string($con, $_POST['nama']) );
    $peran  = trim(mysqli_real_escape_string($con, $_POST['peran']) );

    $query_edit = mysqli_query($con,"UPDATE tbl_pengguna SET
    nama = '$nama',
    peran = '$peran' WHERE username = '$username'
     ")or die(mysqli_error($con));

    echo '<script> alert ("Data Berhasil Diedit");
    window.location.href = "../admin_data_administrator"</script>';
    
}
?>