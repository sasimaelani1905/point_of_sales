<?php
require_once '../database/koneksi.php';

if (isset($_POST['tambah_detail_jual'])) {
    
    $kode_nota = mysqli_real_escape_string($koneksi, $_POST['kode_nota']);
    $kode_brg  = mysqli_real_escape_string($koneksi, $_POST['kode_brg']);
    $jumlah    = (int)$_POST['jumlah'];
    $harga_jual = (int)$_POST['harga_jual'];

    if (empty($kode_nota) || empty($kode_brg) || $jumlah <= 0 || $harga_jual < 0) {
        echo "<script>
            alert('Input tidak valid! Pastikan semua field terisi dengan benar.');
            window.history.back();
        </script>";
        exit;
    }

    $cek_stok = mysqli_query($koneksi, "SELECT stok FROM tbl_barang WHERE kode_brg = '$kode_brg'");
    $data_brg = mysqli_fetch_array($cek_stok);

    if (!$data_brg) {
        echo "<script> alert('Barang tidak ditemukan!');
         window.history.back(); </script>";
        exit;
    }

    $stok_sekarang = (int)$data_brg['stok'];

    if ($stok_sekarang < $jumlah) {
        echo "<script>
            alert('Stok tidak mencukupi! Stok saat ini: $stok_sekarang');
            window.history.back();
        </script>";
        exit;
    }

    $total_harga_jual = $jumlah * $harga_jual;

    $query_tambah = "INSERT INTO tbl_detail_nota_jual (kode_nota, kode_brg, jumlah, harga_jual, total_harga_jual) 
                     VALUES ('$kode_nota', '$kode_brg', '$jumlah', '$harga_jual', '$total_harga_jual')";
    
    $simpan = mysqli_query($koneksi, $query_tambah);

    if ($simpan) {
        mysqli_query($koneksi, "UPDATE tbl_barang SET stok = stok - $jumlah WHERE kode_brg = '$kode_brg'");

        mysqli_query($koneksi, "UPDATE tbl_nota_jual 
                                SET total_penjualan = (SELECT SUM(total_harga_jual) FROM tbl_detail_nota_jual WHERE kode_nota = '$kode_nota') 
                                WHERE kode_nota = '$kode_nota'");

        echo "<script>
            alert('Barang berhasil ditambahkan & stok berkurang!');
            window.location.href='admin_detail_nota_jual';
        </script>";
    } else {
        echo "<script>
            alert('Gagal menambah barang: " . mysqli_error($koneksi) . "');
            window.history.back();
        </script>";
    }
} else {
    // Jika diakses secara langsung tanpa submit form
    header("Location: admin_detail_nota_jual");
    exit;
}
?>