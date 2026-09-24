<html>
    <head>
        <body>
            <?php 
                require_once('../database/koneksi.php');

                $urut = @$_GET['urut'];
                $cek_nota = mysqli_query($koneksi, "SELECT * FROM tbl_detail_nota_jual WHERE urut = '$urut'") or die(mysqli_error($koneksi));
                $jumlah = mysqli_num_rows($cek_nota);

                if ($jumlah > 0) {
                    $hapus_nota = mysqli_query($koneksi, "DELETE FROM tbl_detail_nota_jual WHERE urut = '$urut'") or die(mysqli_error($koneksi));
                    
                    if ($hapus_nota) {
                        echo '<script>alert("Data nota Jual dengan Kode nota ' . $urut . ' Berhasil Dihapus");
                        window.location.href="../kasir_detail_nota_jual";</script>';
                    } else {
                        echo '<script>alert("Gagal Menghapus Data nota Jual");
                        window.location.href="../kasir_detail_nota_jual";</script>';
                    }
                } else {
                    echo '<script>alert("Data nota Jual Tidak Ditemukan");
                    window.location.href="../kasir_detail_nota_jual";</script>';
                }
            ?>
        </body>
    </head>
</html>