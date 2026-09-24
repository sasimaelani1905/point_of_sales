<html>
    <head>
        <body>
            <?php 
                require_once('../database/koneksi.php');

                $kode_nota = @$_GET['kode_nota'];
                $cek_nota = mysqli_query($koneksi, "SELECT * FROM tbl_nota_beli WHERE kode_nota = '$kode_nota'") or die(mysqli_error($koneksi));
                $jumlah = mysqli_num_rows($cek_nota);

                if ($jumlah > 0) {
                    $hapus_nota = mysqli_query($koneksi, "DELETE FROM tbl_nota_beli WHERE kode_nota = '$kode_nota'") or die(mysqli_error($koneksi));
                    
                    if ($hapus_nota) {
                        echo '<script>alert("Data Nota Beli dengan Kode Nota ' . $kode_nota . ' Berhasil Dihapus");
                        window.location.href="../admin_nota_beli";</script>';
                    } else {
                        echo '<script>alert("Gagal Menghapus Data Nota Beli");
                        window.location.href="../admin_nota_beli";</script>';
                    }
                } else {
                    echo '<script>alert("Data Nota Beli Tidak Ditemukan");
                    window.location.href="../admin_nota_beli";</script>';
                }
            ?>
        </body>
    </head>
</html>