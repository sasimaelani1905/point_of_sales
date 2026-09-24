<?php
require_once '../database/koneksi.php';

if (isset($_POST['tambah_detail_beli'])) {
    
    $kode_nota = mysqli_real_escape_string($koneksi, $_POST['kode_nota']);
    $kode_brg  = mysqli_real_escape_string($koneksi, $_POST['kode_brg']);
    $jumlah    = (int)$_POST['jumlah'];
    $harga_beli = (int)$_POST['harga_beli'];

    if (empty($kode_nota) || empty($kode_brg) || $jumlah <= 0 || $harga_beli < 0) {
        echo "<script>
            alert('Input tidak valid! Pastikan semua field terisi dengan benar.');
            window.history.back();
        </script>";
        exit;
    }

    $total_harga_beli = $jumlah * $harga_beli;
    $query_tambah = "INSERT INTO tbl_detail_nota_beli (kode_nota, kode_brg, jumlah, harga_beli, total_harga_beli) 
                     VALUES ('$kode_nota', '$kode_brg', '$jumlah', '$harga_beli', '$total_harga_beli')";
    
    $simpan = mysqli_query($koneksi, $query_tambah);

    if ($simpan) {
        mysqli_query($koneksi, "UPDATE tbl_barang SET stok = stok + $jumlah WHERE kode_brg = '$kode_brg'");

        mysqli_query($koneksi, "UPDATE tbl_nota_beli 
                                SET total_pembelian = (SELECT SUM(total_harga_beli) FROM tbl_detail_nota_beli WHERE kode_nota = '$kode_nota') 
                                WHERE kode_nota = '$kode_nota'");

        echo "<script>
            alert('Barang berhasil ditambahkan & stok bertambah!');
            window.location.href='kasir_detail_nota_beli?kode_nota=$kode_nota';
        </script>";
    } else {
        echo "<script>
            alert('Gagal menambah barang: " . mysqli_error($koneksi) . "');
            window.history.back();
        </script>";
    }
} else {
    header("Location: kasir_nota_beli");
    exit;
}
?>