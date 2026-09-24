<html>
    <head>
        <body>
            <?php 
                require_once('../database/koneksi.php');

                $urut = @$_GET['urut'];
                $cek_nota = mysqli_query($koneksi, "SELECT * FROM tbl_detail_nota_beli WHERE urut = '$urut'") or die(mysqli_error($koneksi));
                $jumlah = mysqli_num_rows($cek_nota);

                if ($jumlah > 0) {
                    $hapus_nota = mysqli_query($koneksi, "DELETE FROM tbl_detail_nota_beli WHERE urut = '$urut'") or die(mysqli_error($koneksi));
                    
                    if ($hapus_nota) {
                        echo '<script>alert("Data nota beli dengan Kode nota ' . $urut . ' Berhasil Dihapus");
                        window.location.href="kasir_detail_nota_beli";</script>';
                    } else {
                        echo '<script>alert("Gagal Menghapus Data nota beli");
                        window.location.href="kasir_detail_nota_beli";</script>';
                    }
                } else {
                    echo '<script>alert("Data nota beli Tidak Ditemukan");
                    window.location.href="kasir_detail_nota_beli";</script>';
                }
            ?>
        </body>
    </head>
</html>