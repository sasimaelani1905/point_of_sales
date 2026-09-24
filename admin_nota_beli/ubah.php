<?php
require_once '../database/koneksi.php';

// PROSES UBAH/EDIT NOTA
if (isset($_POST['ubah_nota'])) {

    $kode_nota         = trim(mysqli_real_escape_string($koneksi, $_POST['kode_nota']));
    $kode_supplier     = trim(mysqli_real_escape_string($koneksi, $_POST['kode_supplier']));
    $tgl_pembelian     = trim(mysqli_real_escape_string($koneksi, $_POST['tgl_pembelian']));
    $total_pembelian   = trim(mysqli_real_escape_string($koneksi, $_POST['total_pembelian']));
    $status            = trim(mysqli_real_escape_string($koneksi, $_POST['status']));
    $metode_pembayaran = trim(mysqli_real_escape_string($koneksi, $_POST['metode_pembayaran']));
    $keterangan        = trim(mysqli_real_escape_string($koneksi, $_POST['keterangan']));

    // Perbaikan: Menambahkan tanda petik tunggal (') penutup pada $metode_pembayaran
    $query_edit = mysqli_query($koneksi, "UPDATE tbl_nota_beli SET
        kode_supplier     = '$kode_supplier',
        tgl_pembelian     = '$tgl_pembelian',
        total_pembelian   = '$total_pembelian',
        status            = '$status',
        metode_pembayaran = '$metode_pembayaran',
        keterangan        = '$keterangan'
        WHERE kode_nota   = '$kode_nota'
    ") or die(mysqli_error($koneksi));

    echo '<script>
        alert("Data Berhasil Diedit");
        window.location.href = "../admin_nota_beli";
    </script>';
}

// PROSES SIMPAN/HITUNG PEMBAYARAN NOTA
if (isset($_POST['simpan_nota'])) {

    $kode_nota       = trim(mysqli_real_escape_string($koneksi, $_POST['kode_nota']));
    $total_pembelian = (int)$_POST['total_pembelian'];
    $status          = mysqli_real_escape_string($koneksi, $_POST['status']);
    
    // Tentukan Persentase & Keterangan berdasarkan Status
    $persen = 0;
    $keterangan = 'Belum Lunas';

    if ($status == 'L') {
        $persen = 1.0;
        $keterangan = 'Lunas';
    } else if ($status == '2') {
        $persen = 0.25;
    } else if ($status == '3') {
        $persen = 0.50;
    } else if ($status == '4') {
        $persen = 0.75;
    }

    // Kalkulasi nominal yang dibayar
    $total_dibayar = $total_pembelian * $persen;
    $sisa_bayar    = $total_pembelian - $total_dibayar;

    // Simpan ke database
    $query_simpan = "UPDATE tbl_nota_beli SET 
                total_pembelian = '$total_pembelian',
                total_dibayar   = '$total_dibayar',
                status          = '$status',
                keterangan      = '$keterangan'
              WHERE kode_nota   = '$kode_nota'";
    
    $simpan = mysqli_query($koneksi, $query_simpan) or die(mysqli_error($koneksi));

    if ($simpan) {
        echo '<script>
            alert("Pembayaran Berhasil Disimpan");
            window.location.href = "../admin_nota_beli";
        </script>';
    }
}
?>