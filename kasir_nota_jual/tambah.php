<?php
require_once ('../database/koneksi.php');

if (isset($_POST['tambah_nota'])) {
    $kode_nota         = trim(mysqli_real_escape_string($koneksi, $_POST['kode_nota']));
    $kode_supplier     = trim(mysqli_real_escape_string($koneksi, $_POST['kode_supplier']));
    $tgl_penjualan     = trim(mysqli_real_escape_string($koneksi, $_POST['tgl_penjualan']));
    $total_penjualan   = trim(mysqli_real_escape_string($koneksi, $_POST['total_penjualan']));
    $metode_pembayaran = trim(mysqli_real_escape_string($koneksi, $_POST['metode_pembayaran']));
    $status            = trim(mysqli_real_escape_string($koneksi, $_POST['status']));
    $keterangan        = trim(mysqli_real_escape_string($koneksi, $_POST['keterangan']));

    $cek_nota = mysqli_query($koneksi, "SELECT kode_nota FROM tbl_nota_jual WHERE kode_nota='$kode_nota'") or die(mysqli_error($koneksi));
    $rv = mysqli_num_rows($cek_nota);

    if ($rv == 1) {
        echo '<script> alert ("Data Sudah Terdaftar! Input yang lain");
        window.location.href="../kasir_nota_jual/"</script>';
    } else {
        $query_simpan = mysqli_query($koneksi, "INSERT INTO tbl_nota_jual 
        (kode_nota, kode_supplier, tgl_penjualan, total_penjualan, metode_pembayaran, status, keterangan) 
        VALUES 
        ('$kode_nota', '$kode_supplier', '$tgl_penjualan', '$total_penjualan', '$metode_pembayaran', '$status', '$keterangan')") or die(mysqli_error($koneksi));

        echo '<script> alert ("Data Berhasil Disimpan"); 
        window.location.href="../kasir_nota_jual" </script>';
    }
}
?>