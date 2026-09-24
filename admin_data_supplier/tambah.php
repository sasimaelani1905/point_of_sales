<?php
require_once ('../database/koneksi.php');

if (isset($_POST['tambah_supplier'])) {
    $kode_supplier      = trim(mysqli_real_escape_string($koneksi, $_POST['kode_supplier']));
    $nama_supplier      = trim(mysqli_real_escape_string($koneksi, $_POST['nama_supplier']));
    $nama_pic           = trim(mysqli_real_escape_string($koneksi, $_POST['nama_pic']));
    $kontak_pic         = trim(mysqli_real_escape_string($koneksi, $_POST['kontak_pic']));
    $alamat_supplier    = trim(mysqli_real_escape_string($koneksi, $_POST['alamat_supplier']));
    $website            = trim(mysqli_real_escape_string($koneksi, $_POST['website']));
    $akun_ig            = trim(mysqli_real_escape_string($koneksi, $_POST['akun_ig']));
    $akun_tiktok        = trim(mysqli_real_escape_string($koneksi, $_POST['akun_tiktok']));

    $query_cek_supplier = mysqli_query($koneksi, "SELECT kode_supplier FROM tbl_supplier WHERE kode_supplier ='$kode_supplier' ")
    or die(mysqli_error($koneksi));
    $rv = mysqli_num_rows($query_cek_supplier);
    if ($rv > 0) {
        echo '<script> alert ("Data Sudah Terdaftar! Input yang lain");
        window.location.href="../admin_data_supplier/"</script>';
    } else {
        $query_simpan = mysqli_query ($koneksi, "INSERT INTO tbl_supplier VALUES('$kode_supplier', '$nama_supplier', '$nama_pic', '$kontak_pic', '$alamat_supplier', '$website', '$akun_ig', '$akun_tiktok')")or die(mysqli_error($koneksi));
        
        $peran          = "S";
        $username       = $kode_supplier;
        $password       = "";
        $nama_panggilan = $nama_supplier;
        $pin            = "909090";

        $query_simpan_pengguna = mysqli_query($koneksi, "INSERT INTO tbl_user
            (username, password, peran, nama_panggilan, pin2fa)VALUES
            ('$username', '$password', '$peran', '$nama_panggilan', '$pin')") or die(mysqli_error($koneksi));

        echo '<script> alert ("Data barang Ditambah"); 
        window.location.href="../admin_data_supplier" </script>';
    }
}
?>