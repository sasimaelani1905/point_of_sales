<!DOCTYPE html>
<html>
<head>
    <title>Hapus Detail Nota Konsinyasi</title>
</head>
<body>
    <?php 
        require_once('../database/koneksi.php');

        // Ambil ID detail dari URL
        $id_detail = isset($_GET['id_detail']) ? (int)$_GET['id_detail'] : 0;

        // Cek keberadaan data detail nota konsinyasi
        $cek_detail = mysqli_query($koneksi, "SELECT * FROM tbl_detail_nota_konsinyasi WHERE id_detail = '$id_detail'") or die(mysqli_error($koneksi));
        
        if (mysqli_num_rows($cek_detail) > 0) {
            $data_detail = mysqli_fetch_assoc($cek_detail);
            
            $no_nota_konsinyasi = $data_detail['no_nota_konsinyasi'];
            $kode_brg_konsinyasi = $data_detail['kode_brg_konsinyasi'];
            $jumlah_titip        = (int)$data_detail['jumlah_titip'];

            // 1. Hapus data dari tbl_detail_nota_konsinyasi
            $hapus_detail = mysqli_query($koneksi, "DELETE FROM tbl_detail_nota_konsinyasi WHERE id_detail = '$id_detail'") or die(mysqli_error($koneksi));
            
            if ($hapus_detail) {
                // 2. Kurangi kembali stok barang konsinyasi karena item titipan dihapus
                mysqli_query($koneksi, "UPDATE tbl_barang_konsinyasi 
                                        SET stok = GREATEST(0, stok - $jumlah_titip) 
                                        WHERE kode_brg_konsinyasi = '$kode_brg_konsinyasi'");

                // 3. Update total_item pada header nota konsinyasi
                mysqli_query($koneksi, "UPDATE tbl_nota_konsinyasi 
                                        SET total_item = (SELECT IFNULL(SUM(jumlah_titip), 0) 
                                                          FROM tbl_detail_nota_konsinyasi 
                                                          WHERE no_nota_konsinyasi = '$no_nota_konsinyasi') 
                                        WHERE no_nota_konsinyasi = '$no_nota_konsinyasi'");

                echo '<script>
                    alert("Item detail nota konsinyasi berhasil dihapus dan stok diperbarui!");
                    window.location.href="../admin_detail_nota_konsinyasi?no_nota=' . $no_nota_konsinyasi . '";
                </script>';
            } else {
                echo '<script>
                    alert("Gagal menghapus item detail nota konsinyasi");
                    window.history.back();
                </script>';
            }
        } else {
            echo '<script>
                alert("Data detail nota konsinyasi tidak ditemukan");
                window.location.href="../admin_detail_nota_konsinyasi";
            </script>';
        }
    ?>
</body>
</html>