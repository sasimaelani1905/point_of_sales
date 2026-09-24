<?php
require_once ('../database/koneksi.php');

if (isset($_POST['tambah_nota'])) {
    $kode_nota         = trim(mysqli_real_escape_string($koneksi, $_POST['kode_nota']));
    $kode_supplier     = trim(mysqli_real_escape_string($koneksi, $_POST['kode_supplier']));
    $tgl_pembelian     = trim(mysqli_real_escape_string($koneksi, $_POST['tgl_pembelian']));
    $total_pembelian   = trim(mysqli_real_escape_string($koneksi, $_POST['total_pembelian']));
    $status            = trim(mysqli_real_escape_string($koneksi, $_POST['status']));
    $metode_pembayaran = trim(mysqli_real_escape_string($koneksi, $_POST['metode_pembayaran']));
    $keterangan        = trim(mysqli_real_escape_string($koneksi, $_POST['keterangan']));

    // Cek apakah kode_nota sudah ada
    $cek_nota = mysqli_query($koneksi, "SELECT kode_nota FROM tbl_nota_beli WHERE kode_nota='$kode_nota'") or die(mysqli_error($koneksi));
    $rv = mysqli_num_rows($cek_nota);

    if ($rv == 1) {
        echo '<script> alert ("Data Sudah Terdaftar! Input yang lain");
        window.location.href="../kasir_nota_beli/"</script>';
    } else {
        // Perbaikan: Menambahkan kolom dan nilai metode_pembayaran pada query INSERT
        $query_simpan = mysqli_query($koneksi, "INSERT INTO tbl_nota_beli 
        (kode_nota, kode_supplier, tgl_pembelian, total_pembelian, status, metode_pembayaran, keterangan) 
        VALUES 
        ('$kode_nota', '$kode_supplier', '$tgl_pembelian', '$total_pembelian', '$status', '$metode_pembayaran', '$keterangan')") or die(mysqli_error($koneksi));

        echo '<script> alert ("Data Berhasil Disimpan"); 
        window.location.href="../kasir_nota_beli" </script>';
    }
}
?>