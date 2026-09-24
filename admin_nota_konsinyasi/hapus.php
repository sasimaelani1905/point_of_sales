<html>
    <head>
    </head>
    <body>
        <?php 
            require_once('../database/koneksi.php');
            $no_nota_konsinyasi = isset($_GET['no_nota_konsinyasi']) ? mysqli_real_escape_string($koneksi, $_GET['no_nota_konsinyasi']) : '';
            if (!empty($no_nota_konsinyasi)) {
                $cek_nota = mysqli_query($koneksi, "SELECT * FROM tbl_nota_konsinyasi WHERE no_nota_konsinyasi = '$no_nota_konsinyasi'") or die(mysqli_error($koneksi));
                $jumlah = mysqli_num_rows($cek_nota);
                if ($jumlah > 0) {
                    $hapus_nota = mysqli_query($koneksi, "DELETE FROM tbl_nota_konsinyasi WHERE no_nota_konsinyasi = '$no_nota_konsinyasi'") or die(mysqli_error($koneksi));
                    if ($hapus_nota) {
                        echo '<script>alert("Data Nota Konsinyasi dengan No. Nota ' . htmlspecialchars($no_nota_konsinyasi) . ' Berhasil Dihapus");
                        window.location.href="index.php";</script>';
                    } else {
                        echo '<script>alert("Gagal Menghapus Data Nota Konsinyasi");
                        window.location.href="index.php";</script>';
                    }
                } else {
                    echo '<script>alert("Data Nota Konsinyasi Tidak Ditemukan");
                    window.location.href="index.php";</script>';
                }
            } else {
                echo '<script>alert("Parameter No. Nota Tidak Valid");
                window.location.href="index.php";</script>';
            }
        ?>
    </body>
</html>