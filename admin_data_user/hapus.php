<?php 
    require_once('../database/koneksi.php');
    error_reporting(0);
    $username_login  = $_SESSION['username'];
    $username = @$_GET['username'];
    $cek_admin = mysqli_query($koneksi, "SELECT COUNT(*) AS jumlah FROM tbl_user WHERE peran = 'S'")or die(mysqli_error($koneksi));
    $data = mysqli_fetch_assoc($cek_admin);
    $jumlah = $data['jumlah'];

    if ($username_login == $username && $jumlah == 1) {
        echo '<script> alert ("Anda Tidak Dapat Menghapus Akun Anda Sendiri Atau Akun Admin Tinggal 1");
        window.location.href="../admin_data_user"</script>';

    }elseif($username_login !=$username && $jumlah ==0){
    $hapus_username =mysqli_query($koneksi, "DELETE FROM tbl_user WHERE username='$username'")or die(mysqli_error($koneksi));
    echo '<script> alert ("Data '.$username.' Berhasil Dihapus");
    window.location.href="../admin_data_user"</script>';

    }else{
    $hapus_username =mysqli_query($koneksi, "DELETE FROM tbl_user WHERE username='$username'")or die(mysqli_error($koneksi));
    echo '<script> alert ("Data '.$username.' Berhasil Dihapus");
    window.location.href="../admin_data_user"</script>';
    }
?>