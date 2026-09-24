<?php
require_once '../database/koneksi.php';

if (isset($_POST['ubah_barang'])) {

    $kode_brg      = trim(mysqli_real_escape_string($koneksi, $_POST['kode_brg']));
    $kode_supplier  = trim(mysqli_real_escape_string($koneksi, $_POST['kode_supplier']));
    $nama_brg  = trim(mysqli_real_escape_string($koneksi, $_POST['nama_brg']));
    $merk = trim(mysqli_real_escape_string($koneksi, $_POST['merk']));
    $stok        = trim(mysqli_real_escape_string($koneksi, $_POST['stok']));
    $rata_harga_beli     = trim(mysqli_real_escape_string($koneksi, $_POST['rata_harga_beli']));
    $harga_jual = trim(mysqli_real_escape_string($koneksi, $_POST['harga_jual']));

    $query_edit = mysqli_query($koneksi, "UPDATE tbl_barang SET
        kode_supplier   = '$kode_supplier',
        nama_brg   = '$nama_brg',
        merk = '$merk',
        stok          = '$stok',
        rata_harga_beli      = '$rata_harga_beli',
        harga_jual = '$harga_jual'
        WHERE kode_brg = '$kode_brg'
    ") or die(mysqli_error($koneksi));

    echo '<script> alert ("Data Berhasil Diedit");
    window.location.href = "../admin_data_barang"</script>';
    
}
?>