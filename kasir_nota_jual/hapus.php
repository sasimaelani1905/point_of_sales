<html>
    <head>
        <body>
            <?php 
                require_once('../database/koneksi.php');

                $kode_nota = @$_GET['kode_nota'];
                $cek_nota = mysqli_query($koneksi, "SELECT * FROM tbl_nota_jual WHERE kode_nota = '$kode_nota'") or die(mysqli_error($koneksi));
                $jumlah = mysqli_num_rows($cek_nota);

                if ($jumlah > 0) {
                    $hapus_nota = mysqli_query($koneksi, "DELETE FROM tbl_nota_jual WHERE kode_nota = '$kode_nota'") or die(mysqli_error($koneksi));
                    
                    if ($hapus_nota) {
                        echo '<script>alert("Data Nota Jual dengan Kode Nota ' . $kode_nota . ' Berhasil Dihapus");
                        window.location.href="../kasir_nota_jual";</script>';
                    } else {
                        echo '<script>alert("Gagal Menghapus Data Nota Jual");
                        window.location.href="../kasir_nota_jual";</script>';
                    }
                } else {
                    echo '<script>alert("Data Nota Jual Tidak Ditemukan");
                    window.location.href="../kasir_nota_jual";</script>';
                }
            ?>
        </body>
    </head>
</html>