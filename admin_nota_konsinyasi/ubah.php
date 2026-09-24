<?php
require_once '../database/koneksi.php';

if (isset($_POST['ubah_nota_konsinyasi'])) {

    $no_nota_konsinyasi  = trim(mysqli_real_escape_string($koneksi, $_POST['no_nota_konsinyasi']));
    $kode_brg_konsinyasi = trim(mysqli_real_escape_string($koneksi, $_POST['kode_brg_konsinyasi']));
    $nama_supplier       = trim(mysqli_real_escape_string($koneksi, $_POST['nama_supplier']));
    $tgl_titip           = trim(mysqli_real_escape_string($koneksi, $_POST['tgl_titip']));
    $total_item          = trim(mysqli_real_escape_string($koneksi, $_POST['total_item']));

    $query_edit = mysqli_query($koneksi, "UPDATE tbl_nota_konsinyasi SET
        kode_brg_konsinyasi = '$kode_brg_konsinyasi',
        nama_supplier       = '$nama_supplier',
        tgl_titip           = '$tgl_titip',
        total_item          = '$total_item'
        WHERE no_nota_konsinyasi = '$no_nota_konsinyasi'
    ") or die(mysqli_error($koneksi));

    if ($query_edit) {
        echo '<script> alert("Data Nota Konsinyasi Berhasil Diedit");
        window.location.href = "index.php";</script>';
    } else {
        echo '<script> alert("Gagal Memperbarui Data!");
        window.location.href = "index.php";</script>';
    }
    
}
?>