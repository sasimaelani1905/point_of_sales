<?php
require_once ('../database/koneksi.php');

if (isset($_POST['tambah_detail_nota'])) {
    $kode_nota   = trim(mysqli_real_escape_string($koneksi, $_POST['kode_nota']));
    $kode_brg   = trim(mysqli_real_escape_string($koneksi, $_POST['kode_brg']));
    $jumlah      = trim(mysqli_real_escape_string($koneksi, $_POST['jumlah']));
    $harga_beli      = trim(mysqli_real_escape_string($koneksi, $_POST['harga_beli']));
    $total_harga_beli      = trim(mysqli_real_escape_string($koneksi, $_POST['total_harga_beli']));

    $cek_nota = mysqli_query($koneksi, "SELECT kode_nota FROM tbl_nota_beli WHERE kode_nota='$kode_nota' AND kode_brg='$kode_brg'")or die(mysqli_error($koneksi));
    $rv = mysqli_num_rows($cek_nota);

    if ($rv > 0) {
        echo '<script> alert ("Data Sudah Terdaftar! Input yang lain");
        window.location.href="../kasir_detail_nota_beli?kode='.$kode_nota.'"</script>';
    }else {
        $query_simpan = mysqli_query ($koneksi, "INSERT INTO tbl_nota_beli 
        (kode_nota, kode_brg, jumlah, harga_beli, total_harga_beli) 
        VALUES 
        ('$kode_nota', '$kode_brg', '$jumlah', '$harga_beli', '$total_harga_beli')")or die(mysqli_error($koneksi));

        echo '<script> alert ("Data Berhasil Disimpan"); 
        window.location.href="../kasir_detail_nota_beli?kode='.$kode_nota.'" </script>';
    }
}
?>