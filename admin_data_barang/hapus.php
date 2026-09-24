<html>
    <head>
        <body>
            <?php 
                require_once('../database/koneksi.php');

                $kode_brg = @$_GET['kode_brg'];

                $hapus_barang = mysqli_query($koneksi, "DELETE FROM tbl_barang WHERE kode_brg='$kode_brg'") or die(mysqli_error($koneksi));

                echo '<script> alert ("Data Barang '.$kode_brg.' Berhasil Dihapus");
                window.location.href="../admin_data_barang"</script>';
            ?>
        </body>
    </head>
</html>