<?php
require_once '../database/koneksi.php';

// Filter Tanggal
$tgl_awal  = isset($_GET['tgl_awal']) ? $_GET['tgl_awal'] : date('Y-m-01');
$tgl_akhir = isset($_GET['tgl_akhir']) ? $_GET['tgl_akhir'] : date('Y-m-t');

// ==================== 1. HITUNG TOTAL PENJUALAN ====================
$q_nota_jual = mysqli_query($koneksi, "
    SELECT kode_nota FROM tbl_nota_jual 
    WHERE DATE(tgl_penjualan) BETWEEN '$tgl_awal' AND '$tgl_akhir'
") or die("Gagal query penjualan: " . mysqli_error($koneksi));

$total_penjualan = 0;
$total_qty_jual  = 0;

if ($q_nota_jual && mysqli_num_rows($q_nota_jual) > 0) {
    while ($nj = mysqli_fetch_assoc($q_nota_jual)) {
        $kd_nota_jual = $nj['kode_nota'];
        
        $q_det_jual = mysqli_query($koneksi, "
            SELECT SUM(total_harga_jual) AS subtotal, SUM(jumlah) AS total_qty 
            FROM tbl_detail_nota_jual 
            WHERE kode_nota = '$kd_nota_jual'
        ");
        $d_det_jual = mysqli_fetch_assoc($q_det_jual);
        
        $total_penjualan += (float) ($d_det_jual['subtotal'] ?? 0);
        $total_qty_jual  += (int) ($d_det_jual['total_qty'] ?? 0);
    }
}

// ==================== 2. HITUNG TOTAL PEMBELIAN (HPP) ====================
$q_nota_beli = mysqli_query($koneksi, "
    SELECT kode_nota FROM tbl_nota_beli 
    WHERE DATE(tgl_pembelian) BETWEEN '$tgl_awal' AND '$tgl_akhir'
") or die("Gagal query pembelian: " . mysqli_error($koneksi));

$total_pembelian = 0;
$total_qty_beli  = 0;

if ($q_nota_beli && mysqli_num_rows($q_nota_beli) > 0) {
    while ($nb = mysqli_fetch_assoc($q_nota_beli)) {
        $kd_nota_beli = $nb['kode_nota'];

        // Subquery Detail Barang Pembelian (Sesuai Struktur Laporan Cetak Pembelian)
        $q_det_beli = mysqli_query($koneksi, "
            SELECT * FROM tbl_detail_nota_beli 
            WHERE kode_nota = '$kd_nota_beli'
        ") or die("Gagal query detail pembelian: " . mysqli_error($koneksi));
        
        $subtotal_nota_harga = 0;
        $subtotal_nota_qty   = 0;

        while ($detail = mysqli_fetch_assoc($q_det_beli)) {
            $subtotal_nota_harga += $detail['total_harga_beli'];
            $subtotal_nota_qty   += $detail['jumlah'];
        }

        $total_pembelian += $subtotal_nota_harga;
        $total_qty_beli  += $subtotal_nota_qty;
    }
}

// ==================== 3. KALKULASI LABA / RUGI ====================
$laba_rugi = $total_penjualan - $total_pembelian;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan_Laba_Rugi_<?= $tgl_awal; ?>_s/d_<?= $tgl_akhir; ?></title>
    <!-- Link FontAwesome untuk Icon Tombol -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <style>
        /* Tampilan Background Layar ala MS Word */
        body {
            background-color: #e0e0e0;
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            margin: 0;
            padding: 20px 0;
        }

        /* Lembar Kertas A4 */
        .page {
            width: 210mm;
            min-height: 297mm;
            padding: 20mm;
            margin: 0 auto 20px auto;
            background: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            box-sizing: border-box;
            position: relative;
        }

        /* Bar Tombol Aksi */
        .action-bar {
            width: 210mm;
            margin: 0 auto 15px auto;
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .btn {
            padding: 10px 20px;
            font-size: 14px;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            color: #fff;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .btn-print { background-color: #007bff; }
        .btn-save { background-color: #28a745; }
        .btn-close { background-color: #dc3545; }
        .btn:hover { opacity: 0.9; }

        /* KOP SURAT / HEADER */
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            font-size: 18pt;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0 0 0;
            font-size: 11pt;
        }

        .periode {
            margin-bottom: 15px;
            font-weight: bold;
        }

        /* TABEL LAPORAN */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #000;
            padding: 8px 10px;
            font-size: 11pt;
        }
        table.data-table th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
        }
        .section-header {
            background-color: #fafafa;
            font-weight: bold;
        }
        .subtotal-row {
            font-weight: bold;
            background-color: #f9f9f9;
        }
        .result-row {
            font-weight: bold;
            background-color: #e9ecef;
            font-size: 12pt;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }

        /* TANDA TANGAN */
        .footer-ttd {
            width: 100%;
            margin-top: 40px;
            page-break-inside: avoid;
        }
        .footer-ttd td {
            border: none;
            font-size: 11pt;
        }

        /* MEDIA PRINT (SAAT DICETAK ATAU DISIMPAN KE PDF) */
        @media print {
            body {
                background: none;
                padding: 0;
            }
            .action-bar {
                display: none !important;
            }
            .page {
                box-shadow: none;
                margin: 0;
                width: 100%;
                padding: 0;
            }
        }
    </style>
</head>
<body>

    <!-- Baris Tombol Aksi -->
    <div class="action-bar">
        <button onclick="window.print()" class="btn btn-print">
            <i class="fas fa-print"></i> Cetak Dokumen
        </button>
        <button onclick="simpanPdf()" class="btn btn-save">
            <i class="fas fa-download"></i> Simpan ke Laptop (PDF)
        </button>
        <button onclick="window.close()" class="btn btn-close">
            <i class="fas fa-times"></i> Tutup
        </button>
    </div>

    <!-- Lembar Kertas A4 -->
    <div class="page">
        <!-- Kop Laporan -->
        <div class="header">
            <h2>LAPORAN LABA</h2>
            <p>POINT OF SALES</p>
        </div>

        <!-- Periode Tanggal -->
        <div class="periode">
            Periode: <?= date('d-m-Y', strtotime($tgl_awal)); ?> s/d <?= date('d-m-Y', strtotime($tgl_akhir)); ?>
        </div>

        <!-- Tabel Data Laba Rugi -->
        <table class="data-table">
            <thead>
                <tr>
                    <th width="50%">Keterangan Transaksi</th>
                    <th width="20%">Jumlah Barang</th>
                    <th width="30%">Jumlah Nominal</th>
                </tr>
            </thead>
            <tbody>
                <!-- 1. PENDAPATAN / PENJUALAN -->
                <tr class="section-header">
                    <td colspan="3">1. PENDAPATAN OPERASIONAL</td>
                </tr>
                <tr>
                    <td style="padding-left: 20px;">Total Penjualan Barang</td>
                    <td class="text-center"><?= number_format($total_qty_jual, 0, ',', '.'); ?> Pcs</td>
                    <td class="text-right">Rp <?= number_format($total_penjualan, 0, ',', '.'); ?></td>
                </tr>
                <tr class="subtotal-row">
                    <td>TOTAL PENDAPATAN (GROSS)</td>
                    <td class="text-center"><?= number_format($total_qty_jual, 0, ',', '.'); ?> Pcs</td>
                    <td class="text-right">Rp <?= number_format($total_penjualan, 0, ',', '.'); ?></td>
                </tr>

                <!-- 2. BEBAN / PEMBELIAN -->
                <tr class="section-header">
                    <td colspan="3">2. BEBAN & PEMBELIAN STOK (HPP)</td>
                </tr>
                <tr>
                    <td style="padding-left: 20px;">Total Pembelian Barang</td>
                    <td class="text-center"><?= number_format($total_qty_beli, 0, ',', '.'); ?> Pcs</td>
                    <td class="text-right">Rp <?= number_format($total_pembelian, 0, ',', '.'); ?></td>
                </tr>
                <tr class="subtotal-row">
                    <td>TOTAL BEBAN PEMBELIAN</td>
                    <td class="text-center"><?= number_format($total_qty_beli, 0, ',', '.'); ?> Pcs</td>
                    <td class="text-right">Rp <?= number_format($total_pembelian, 0, ',', '.'); ?></td>
                </tr>

                <!-- 3. ESTIMASI LABA BERSIH / RUGI BERSIH -->
                <tr class="result-row">
                    <td colspan="2">ESTIMASI <?= ($laba_rugi >= 0) ? 'LABA BERSIH' : 'RUGI BERSIH'; ?> OPERASIONAL</td>
                    <td class="text-right">Rp <?= number_format(abs($laba_rugi), 0, ',', '.'); ?></td>
                </tr>
            </tbody>
        </table>

        <!-- Area Tanda Tangan -->
        <table class="footer-ttd">
            <tr>
                <td width="60%"></td>
                <td class="text-center">
                    Purwokerto, <?= date('d F Y'); ?><br>
                    kasir / Keuangan,<br><br><br><br><br>
                    ( ___________________ )
                </td>
            </tr>
        </table>
    </div>

    <script>
        function simpanPdf() {
            window.print();
        }
    </script>
</body>
</html>