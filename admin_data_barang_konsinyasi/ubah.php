<?php
require_once '../database/koneksi.php';

if (isset($_POST['ubah_barang_konsinyasi'])) {

    $kode_brg_konsinyasi = trim(mysqli_real_escape_string($koneksi, $_POST['kode_brg_konsinyasi']));
    $kode_supplier       = trim(mysqli_real_escape_string($koneksi, $_POST['kode_supplier']));
    $nama_brg            = trim(mysqli_real_escape_string($koneksi, $_POST['nama_brg']));
    $merk                = trim(mysqli_real_escape_string($koneksi, $_POST['merk']));
    $stok                = trim(mysqli_real_escape_string($koneksi, $_POST['stok']));
    $harga_supplier      = trim(mysqli_real_escape_string($koneksi, $_POST['harga_supplier']));
    $harga_jual          = trim(mysqli_real_escape_string($koneksi, $_POST['harga_jual']));

    // 1. Update data utama ke tabel konsinyasi
    $query_edit = mysqli_query($koneksi, "UPDATE tbl_barang_konsinyasi SET
        kode_supplier  = '$kode_supplier',
        nama_brg       = '$nama_brg',
        merk           = '$merk',
        stok           = '$stok',
        harga_supplier = '$harga_supplier',
        harga_jual     = '$harga_jual'
        WHERE kode_brg_konsinyasi = '$kode_brg_konsinyasi'
    ") or die(mysqli_error($koneksi));

    // 2. Proses ganti foto jika ada file baru diunggah
    if (isset($_FILES['foto_barang']) && $_FILES['foto_barang']['error'] == 0) {
        $nama_file      = $_FILES['foto_barang']['name'];
        $tmp_file       = $_FILES['foto_barang']['tmp_name'];
        $ekstensi       = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
        $ekstensi_boleh = array('jpg', 'jpeg', 'png', 'webp');

        if (in_array($ekstensi, $ekstensi_boleh)) {
            $dir_tujuan = '../foto_barang_konsinyasi/';
            
            if (!is_dir($dir_tujuan)) {
                mkdir($dir_tujuan, 0777, true);
            }

            // Hapus foto lama dari direktori
            $q_foto_lama = mysqli_query($koneksi, "SELECT foto_barang FROM tbl_barang_konsinyasi WHERE kode_brg_konsinyasi = '$kode_brg_konsinyasi'");
            $data_foto = mysqli_fetch_array($q_foto_lama);
            $foto_lama = $data_foto['foto_barang'];

            if (!empty($foto_lama) && file_exists($dir_tujuan . $foto_lama)) {
                unlink($dir_tujuan . $foto_lama);
            }

            // Simpan foto baru
            $foto_baru = $kode_brg_konsinyasi . '_' . time() . '.' . $ekstensi;
            if (move_uploaded_file($tmp_file, $dir_tujuan . $foto_baru)) {
                mysqli_query($koneksi, "UPDATE tbl_barang_konsinyasi SET foto_barang = '$foto_baru' WHERE kode_brg_konsinyasi = '$kode_brg_konsinyasi'") or die(mysqli_error($koneksi));
            }
        }
    }

    echo '<script>alert("Data Barang Konsinyasi Berhasil Diedit"); window.location.href="../admin_data_barang_konsinyasi/";</script>';
} else {
    // Jika diakses tanpa menekan tombol submit
    echo '<script>window.location.href="../admin_data_barang_konsinyasi/";</script>';
}
?>