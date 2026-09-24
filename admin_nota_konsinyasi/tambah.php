<?php
require_once ('../database/koneksi.php');

if (isset($_POST['tambah_nota_konsinyasi'])) {
    $no_nota_konsinyasi  = trim(mysqli_real_escape_string($koneksi, $_POST['no_nota_konsinyasi']));
    $kode_brg_konsinyasi = trim(mysqli_real_escape_string($koneksi, $_POST['kode_brg_konsinyasi']));
    $nama_supplier       = trim(mysqli_real_escape_string($koneksi, $_POST['nama_supplier']));
    $tgl_titip           = trim(mysqli_real_escape_string($koneksi, $_POST['tgl_titip']));
    $total_item          = trim(mysqli_real_escape_string($koneksi, $_POST['total_item']));

    // Cek apakah No. Nota Konsinyasi sudah terdaftar
    $cek_nota = mysqli_query($koneksi, "SELECT no_nota_konsinyasi FROM tbl_nota_konsinyasi WHERE no_nota_konsinyasi='$no_nota_konsinyasi'") or die(mysqli_error($koneksi));
    $rv = mysqli_num_rows($cek_nota);

    if ($rv > 0) {
        echo '<script> alert("Nomor Nota Konsinyasi Sudah Terdaftar! Silakan coba lagi.");
        window.location.href="index.php";</script>';
    } else {
        // Query Insert ke tbl_nota_konsinyasi
        $query_simpan = mysqli_query($koneksi, "INSERT INTO tbl_nota_konsinyasi 
        (no_nota_konsinyasi, kode_brg_konsinyasi, nama_supplier, tgl_titip, total_item) 
        VALUES 
        ('$no_nota_konsinyasi', '$kode_brg_konsinyasi', '$nama_supplier', '$tgl_titip', '$total_item')") or die(mysqli_error($koneksi));

        if ($query_simpan) {
            echo '<script> alert("Data Nota Konsinyasi Berhasil Disimpan"); 
            window.location.href="index.php"; </script>';
        } else {
            echo '<script> alert("Gagal Menyimpan Data!"); 
            window.location.href="index.php"; </script>';
        }
    }
}
?>