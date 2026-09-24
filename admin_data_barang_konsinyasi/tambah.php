<?php 
require_once '../database/koneksi.php'; 

if (isset($_POST['tambah_barang_konsinyasi'])) { 
    // Generasi Kode Barang Konsinyasi Otomatis
    $q_kode = mysqli_query($koneksi, "SELECT MAX(kode_brg_konsinyasi) AS kode_terakhir FROM tbl_barang_konsinyasi") or die(mysqli_error($koneksi));
    $data_kode = mysqli_fetch_array($q_kode);
    $kode_brg_konsinyasi = empty($data_kode['kode_terakhir']) ? 1 : $data_kode['kode_terakhir'] + 1;

    // Ambil Input Form & Sanitasi Data
    $kode_supplier  = trim(mysqli_real_escape_string($koneksi, $_POST['kode_supplier'])); 
    $nama_brg       = trim(mysqli_real_escape_string($koneksi, $_POST['nama_brg'])); 
    $merk           = trim(mysqli_real_escape_string($koneksi, $_POST['merk'])); 
    $stok           = trim(mysqli_real_escape_string($koneksi, $_POST['stok'])); 
    $harga_supplier = trim(mysqli_real_escape_string($koneksi, $_POST['harga_supplier'])); 
    $harga_jual     = trim(mysqli_real_escape_string($koneksi, $_POST['harga_jual'])); 
    
    // Barcode dibuat otomatis dengan prefiks '10000000' + kode_brg_konsinyasi
    $barcode_barang = '10000000' . $kode_brg_konsinyasi;
    $foto_barang    = ""; 

    // Cek Duplikasi Kode
    $query_cek_kode = mysqli_query($koneksi, "SELECT kode_brg_konsinyasi FROM tbl_barang_konsinyasi WHERE kode_brg_konsinyasi = '$kode_brg_konsinyasi'") or die(mysqli_error($koneksi)); 
    $rv = mysqli_num_rows($query_cek_kode); 

    if ($rv > 0) { 
        echo '<script>alert("Data Sudah Terdaftar! Silakan coba lagi."); window.location.href="../admin_data_barang_konsinyasi/";</script>'; 
    } else { 
        // Proses Upload Foto Barang Konsinyasi
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
                $foto_barang = $kode_brg_konsinyasi . '_' . time() . '.' . $ekstensi;
                move_uploaded_file($tmp_file, $dir_tujuan . $foto_barang);
            }
        }

        // Query Insert ke Tabel Barang Konsinyasi
        $query_simpan = mysqli_query($koneksi, "INSERT INTO tbl_barang_konsinyasi (
            kode_brg_konsinyasi,
            kode_supplier,
            nama_brg,
            merk,
            stok,
            harga_supplier,
            harga_jual,
            barcode_barang,
            foto_barang
        ) VALUES (
            '$kode_brg_konsinyasi',
            '$kode_supplier',
            '$nama_brg',
            '$merk',
            '$stok',
            '$harga_supplier',
            '$harga_jual',
            '$barcode_barang',
            '$foto_barang'
        )") or die(mysqli_error($koneksi)); 

        if ($query_simpan) {
            echo '<script>alert("Data barang konsinyasi berhasil ditambahkan"); window.location.href="../admin_data_barang_konsinyasi/";</script>';
        }
    } 
} 
?>