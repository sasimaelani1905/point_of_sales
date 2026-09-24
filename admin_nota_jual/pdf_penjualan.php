<?php
require_once '../database/koneksi.php';

// Filter Tanggal (Menangkap filter dari laporan utama)
$tgl_awal  = isset($_GET['tgl_awal']) ? $_GET['tgl_awal'] : date('Y-m-01');
$tgl_akhir = isset($_GET['tgl_akhir']) ? $_GET['tgl_akhir'] : date('Y-m-t');

// Query Header Nota Penjualan
$q_nota = mysqli_query($koneksi, "
    SELECT * FROM tbl_nota_jual 
    WHERE DATE(tgl_penjualan) BETWEEN '$tgl_awal' AND '$tgl_akhir'
    ORDER BY tgl_penjualan ASC
") or die("Gagal query: " . mysqli_error($koneksi));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan_Penjualan_<?= $tgl_awal; ?>_s/d_<?= $tgl_akhir; ?></title>
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

        /* Control Bar (Tombol Aksi di Luar Kertas) */
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

    <!-- Halaman Dokumen A4 -->
    <div class="page">
        <!-- Kop Laporan -->
        <div class="header">
            <h2>LAPORAN PENJUALAN</h2>
            <p>POINT OF SALES</p>
        </div>

        <!-- Periode Tanggal -->
        <div class="periode">
            Periode: <?= date('d-m-Y', strtotime($tgl_awal)); ?> s/d <?= date('d-m-Y', strtotime($tgl_akhir)); ?>
        </div>

        <!-- Tabel Data Penjualan -->
        <table class="data-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="20%">Kode Nota</th>
                    <th width="25%">Tanggal Penjualan</th>
                    <th width="20%">Jumlah Item</th>
                    <th width="30%">Total Penjualan</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $total_omset = 0;
                $total_qty_semua = 0;

                if (mysqli_num_rows($q_nota) > 0) {
                    while ($nota = mysqli_fetch_assoc($q_nota)) {
                        $kd_nota = $nota['kode_nota'];

                        // Query Detail Barang
                        $q_detail = mysqli_query($koneksi, "SELECT * FROM tbl_detail_nota_jual WHERE kode_nota = '$kd_nota'");
                        
                        $subtotal_nota = 0;
                        $qty_nota = 0;

                        while ($detail = mysqli_fetch_assoc($q_detail)) {
                            $subtotal_nota += $detail['total_harga_jual'];
                            $qty_nota += $detail['jumlah'];
                        }

                        $total_omset += $subtotal_nota;
                        $total_qty_semua += $qty_nota;
                        ?>
                        <tr>
                            <td class="text-center"><?= $no++; ?></td>
                            <td class="text-center"><?= $kd_nota; ?></td>
                            <td class="text-center"><?= date('d-m-Y H:i', strtotime($nota['tgl_penjualan'])); ?></td>
                            <td class="text-center"><?= $qty_nota; ?> Pcs</td>
                            <td class="text-right">Rp <?= number_format($subtotal_nota, 0, ',', '.'); ?></td>
                        </tr>
                        <?php
                    }
                } else {
                    echo '<tr><td colspan="5" class="text-center">Tidak ada data penjualan pada periode ini.</td></tr>';
                }
                ?>
            </tbody>
            <tfoot>
                <tr style="font-weight: bold; background-color: #f2f2f2;">
                    <td colspan="3" class="text-center">TOTAL</td>
                    <td class="text-center"><?= $total_qty_semua; ?> Pcs</td>
                    <td class="text-right">Rp <?= number_format($total_omset, 0, ',', '.'); ?></td>
                </tr>
            </tfoot>
        </table>

        <!-- Area Tanda Tangan -->
        <table class="footer-ttd">
            <tr>
                <td width="60%"></td>
                <td class="text-center">
                    <?= date('d F Y'); ?><br>
                    Admin / Kasir,<br><br><br><br><br>
                    ( ___________________ )
                </td>
            </tr>
        </table>
    </div>

    <script>
        function simpanPdf() {
            // Membuka jendela print browser (Pengguna tinggal memilih pilihan "Save as PDF")
            window.print();
        }
    </script>
</body>
</html>