<?php
require_once '../database/koneksi.php';

if (isset($_POST['ubah_supplier'])) {
    $kode_supplier      = trim(mysqli_real_escape_string($koneksi, $_POST['kode_supplier']));
    $nama_supplier  = trim(mysqli_real_escape_string($koneksi, $_POST['nama_supplier']));
    $nama_pic  = trim(mysqli_real_escape_string($koneksi, $_POST['nama_pic']));
    $kontak_pic = trim(mysqli_real_escape_string($koneksi, $_POST['kontak_pic']));
    $alamat_supplier       = trim(mysqli_real_escape_string($koneksi, $_POST['alamat_supplier']));
    $website     = trim(mysqli_real_escape_string($koneksi, $_POST['website']));
    $akun_ig = trim(mysqli_real_escape_string($koneksi, $_POST['akun_ig']));
    $akun_tiktok =trim(mysqli_real_escape_string($koneksi, $_POST['akun_tiktok']));

    $query_edit = mysqli_query($koneksi, "UPDATE tbl_supplier SET
        nama_supplier   = '$nama_supplier',
        nama_pic   = '$nama_pic',
        kontak_pic = '$kontak_pic',
        alamat_supplier          = '$alamat_supplier',
        website  = '$website',
        akun_ig = '$akun_ig',
        akun_tiktok = '$akun_tiktok'
        WHERE kode_supplier = '$kode_supplier'
    ") or die(mysqli_error($koneksi));

    echo '<script> alert ("Data Berhasil Diedit");
    window.location.href = "../admin_data_supplier"</script>';
    
}
?>