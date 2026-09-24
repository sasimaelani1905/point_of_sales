<?php 
require_once ('../database/koneksi.php'); 
 
if (isset($_POST['tambah_barang'])) { 
    $q_kode = mysqli_query($koneksi, "SELECT MAX(kode_brg) AS kode_terakhir FROM tbl_barang") or die(mysqli_error($koneksi));
    $data_kode = mysqli_fetch_array($q_kode);
    $kode_brg = empty($data_kode['kode_terakhir']) ? 1 : $data_kode['kode_terakhir'] + 1;

    $kode_supplier   = trim(mysqli_real_escape_string($koneksi, $_POST['kode_supplier'])); 
    $nama_brg        = trim(mysqli_real_escape_string($koneksi, $_POST['nama_brg'])); 
    $merk            = trim(mysqli_real_escape_string($koneksi, $_POST['merk'])); 
    $stok            = trim(mysqli_real_escape_string($koneksi, $_POST['stok'])); 
    $rata_harga_beli = trim(mysqli_real_escape_string($koneksi, $_POST['rata_harga_beli'])); 
    $harga_jual      = trim(mysqli_real_escape_string($koneksi, $_POST['harga_jual'])); 
    $barcode_barang  = '10000000' . $kode_brg;
    $foto_barang     = ""; 
 
    $query_cek_kode = mysqli_query($koneksi, "SELECT kode_brg FROM tbl_barang WHERE kode_brg ='$kode_brg'") or die(mysqli_error($koneksi)); 
    $rv = mysqli_num_rows($query_cek_kode); 

    if ($rv > 0) { 
        echo '<script>alert("Data Sudah Terdaftar! Input yang lain");window.location.href="../admin_data_barang/"</script>'; 
    } else { 

        if (isset($_FILES['foto_barang']) && $_FILES['foto_barang']['error'] == 0) {
            $nama_file = $_FILES['foto_barang']['name'];
            $tmp_file = $_FILES['foto_barang']['tmp_name'];
            $ekstensi = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
            $ekstensi_boleh = array('jpg','jpeg','png','webp');

            if (in_array($ekstensi, $ekstensi_boleh)) {
                if (!is_dir('../foto_barang/')) mkdir('../foto_barang/', 0777, true);
                $foto_barang = $kode_brg . '_' . time() . '.' . $ekstensi;
                move_uploaded_file($tmp_file, '../foto_barang/' . $foto_barang);
            }
        }

        $query_simpan = mysqli_query($koneksi, "INSERT INTO tbl_barang (kode_brg,kode_supplier,nama_brg,merk,stok,rata_harga_beli,harga_jual,barcode_barang,foto_barang) VALUES ('$kode_brg','$kode_supplier','$nama_brg','$merk','$stok','$rata_harga_beli','$harga_jual','$barcode_barang','$foto_barang')") or die(mysqli_error($koneksi)); 
         
        $peran = "S"; 
        $username = $kode_brg; 
        $password = ""; 
        $nama_panggilan = $nama_brg; 
        $pin = "909090"; 
 
        $query_simpan_pengguna = mysqli_query($koneksi, "INSERT INTO tbl_user (username,password,peran,nama_panggilan,pin) VALUES (NULL,'$username','$password','$peran','$nama_panggilan','$pin')") or die(mysqli_error($koneksi)); 

        echo '<script>alert("Data barang Ditambah");window.location.href="../admin_data_barang"</script>'; 
    } 
} 
?>