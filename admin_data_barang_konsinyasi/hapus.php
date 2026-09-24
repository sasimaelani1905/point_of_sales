<html>
    <head>
        <body>
            <?php 
                require_once('../database/koneksi.php');

                $kode_brg_konsinyasi = @$_GET['kode_brg_konsinyasi'];

                $hapus_barang = mysqli_query($koneksi, "DELETE FROM tbl_barang_konsinyasi WHERE kode_brg_konsinyasi='$kode_brg_konsinyasi'") or die(mysqli_error($koneksi));

                echo '<script> alert ("Data Barang '.$kode_brg_konsinyasi.' Berhasil Dihapus");
                window.location.href="../admin_data_barang_konsinyasi"</script>';
            ?>
        </body>
    </head>
</html>