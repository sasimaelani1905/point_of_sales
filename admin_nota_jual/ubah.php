<?php
require_once '../database/koneksi.php';

if (isset($_POST['ubah_nota'])) {

    $kode_nota         = trim(mysqli_real_escape_string($koneksi, $_POST['kode_nota']));
    $kode_supplier     = trim(mysqli_real_escape_string($koneksi, $_POST['kode_supplier']));
    $tgl_penjualan     = trim(mysqli_real_escape_string($koneksi, $_POST['tgl_penjualan']));
    $total_penjualan   = trim(mysqli_real_escape_string($koneksi, $_POST['total_penjualan']));
    $metode_pembayaran = trim(mysqli_real_escape_string($koneksi, $_POST['metode_pembayaran']));
    $status            = trim(mysqli_real_escape_string($koneksi, $_POST['status']));
    $keterangan        = trim(mysqli_real_escape_string($koneksi, $_POST['keterangan']));

    $query_edit = mysqli_query($koneksi, "UPDATE tbl_nota_jual SET
        kode_supplier     = '$kode_supplier',
        tgl_penjualan     = '$tgl_penjualan',
        total_penjualan   = '$total_penjualan',
        metode_pembayaran = '$metode_pembayaran',
        status            = '$status',
        keterangan        = '$keterangan'
        WHERE kode_nota   = '$kode_nota'
    ") or die(mysqli_error($koneksi));

    echo '<script> alert ("Data Berhasil Diedit");
    window.location.href = "../admin_nota_jual"</script>';
    
}
?>