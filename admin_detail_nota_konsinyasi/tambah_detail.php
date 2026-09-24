<?php
require_once '../database/koneksi.php';

if (isset($_POST['tambah_detail_konsinyasi'])) {
    
    // Ambil input dan sanitasi data
    $no_nota_konsinyasi = mysqli_real_escape_string($koneksi, $_POST['no_nota_konsinyasi']);
    $kode_brg_konsinyasi = (int)$_POST['kode_brg_konsinyasi'];
    $jumlah_titip        = (int)$_POST['jumlah_titip'];
    $harga_satuan        = (int)$_POST['harga_satuan'];

    // Validasi input
    if (empty($no_nota_konsinyasi) || $kode_brg_konsinyasi <= 0 || $jumlah_titip <= 0 || $harga_satuan < 0) {
        echo "<script>
            alert('Input tidak valid! Pastikan semua field terisi dengan benar.');
            window.history.back();
        </script>";
        exit;
    }

    // Cek keberadaan barang konsinyasi
    $cek_brg = mysqli_query($koneksi, "SELECT kode_brg_konsinyasi FROM tbl_barang_konsinyasi WHERE kode_brg_konsinyasi = '$kode_brg_konsinyasi'");
    if (mysqli_num_rows($cek_brg) == 0) {
        echo "<script>
            alert('Barang konsinyasi tidak ditemukan!');
            window.history.back();
        </script>";
        exit;
    }

    // Hitung subtotal
    $subtotal = $jumlah_titip * $harga_satuan;

    // Insert ke tabel detail nota konsinyasi
    $query_tambah = "INSERT INTO tbl_detail_nota_konsinyasi (no_nota_konsinyasi, kode_brg_konsinyasi, jumlah_titip, harga_satuan, subtotal) 
                     VALUES ('$no_nota_konsinyasi', '$kode_brg_konsinyasi', '$jumlah_titip', '$harga_satuan', '$subtotal')";
    
    $simpan = mysqli_query($koneksi, $query_tambah);

    if ($simpan) {
        // 1. Tambahkan stok barang konsinyasi (karena ada titipan baru)
        mysqli_query($koneksi, "UPDATE tbl_barang_konsinyasi 
                                SET stok = stok + $jumlah_titip 
                                WHERE kode_brg_konsinyasi = '$kode_brg_konsinyasi'");

        // 2. Update total item pada header nota konsinyasi
        mysqli_query($koneksi, "UPDATE tbl_nota_konsinyasi 
                                SET total_item = (SELECT IFNULL(SUM(jumlah_titip), 0) 
                                                  FROM tbl_detail_nota_konsinyasi 
                                                  WHERE no_nota_konsinyasi = '$no_nota_konsinyasi') 
                                WHERE no_nota_konsinyasi = '$no_nota_konsinyasi'");

        echo "<script>
            alert('Barang konsinyasi berhasil ditambahkan & stok bertambah!');
            window.location.href='admin_detail_nota_konsinyasi?no_nota=$no_nota_konsinyasi';
        </script>";
    } else {
        echo "<script>
            alert('Gagal menambah barang: " . mysqli_error($koneksi) . "');
            window.history.back();
        </script>";
    }
} else {
    // Jika diakses langsung tanpa POST
    header("Location: admin_detail_nota_konsinyasi");
    exit;
}
?>