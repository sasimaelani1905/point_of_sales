<?php
require_once '../database/koneksi.php';

// Sanitasi Input Tanggal (Mencegah SQL Injection)
$tgl_awal  = isset($_GET['tgl_awal']) ? mysqli_real_escape_string($koneksi, $_GET['tgl_awal']) : date('Y-m-01');
$tgl_akhir = isset($_GET['tgl_akhir']) ? mysqli_real_escape_string($koneksi, $_GET['tgl_akhir']) : date('Y-m-t');

// Query gabungan (JOIN + GROUP BY) agar performa super cepat (Hanya 1 Query)
$query = "
    SELECT 
        n.kode_nota,
        n.tgl_pembelian,
        COALESCE(SUM(d.jumlah), 0) AS total_qty,
        COALESCE(SUM(d.total_harga_beli), 0) AS total_harga
    FROM tbl_nota_beli n
    LEFT JOIN tbl_detail_nota_beli d ON n.kode_nota = d.kode_nota
    WHERE DATE(n.tgl_pembelian) BETWEEN '$tgl_awal' AND '$tgl_akhir'
    GROUP BY n.kode_nota, n.tgl_pembelian
    ORDER BY n.tgl_pembelian ASC
";

$q_nota = mysqli_query($koneksi, $query) or die("Gagal query laporan: " . mysqli_error($koneksi));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan_Pembelian_<?= htmlspecialchars($tgl_awal); ?>_s/d_<?= htmlspecialchars($tgl_akhir); ?></title>
    <!-- FontAwesome untuk Icon Tombol -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <style>
        /* Layout Dasar Kertas A4 */
        body {
            background-color: #e0e0e0;
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            margin: 0;
            padding: 20px 0;
        }

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

        /* Action Bar */
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

        /* Kop Surat */
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

        /* Tabel Data */
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

        /* Tanda Tangan */
        .footer-ttd {
            width: 100%;
            margin-top: 40px;
            page-break-inside: avoid;
        }
        .footer-ttd td {
            border: none;
            font-size: 11pt;
        }

        /* Media Print */
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
        <button onclick="window.print()" class="btn btn-save">
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
            <h2>LAPORAN PEMBELIAN</h2>
            <p>POINT OF SALES</p>
        </div>

        <!-- Periode Tanggal -->
        <div class="periode">
            Periode: <?= date('d-m-Y', strtotime($tgl_awal)); ?> s/d <?= date('d-m-Y', strtotime($tgl_akhir)); ?>
        </div>

        <!-- Tabel Data Pembelian -->
        <table class="data-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="20%">Kode Nota</th>
                    <th width="25%">Tanggal</th>
                    <th width="20%">Jumlah Item Beli</th>
                    <th width="30%">Total Pembelian</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $grand_total_harga = 0;
                $grand_total_qty   = 0;

                if (mysqli_num_rows($q_nota) > 0) {
                    while ($nota = mysqli_fetch_assoc($q_nota)) {
                        $grand_total_harga += $nota['total_harga'];
                        $grand_total_qty   += $nota['total_qty'];
                        ?>
                        <tr>
                            <td class="text-center"><?= $no++; ?></td>
                            <td class="text-center"><?= htmlspecialchars($nota['kode_nota']); ?></td>
                            <td class="text-center"><?= date('d-m-Y H:i', strtotime($nota['tgl_pembelian'])); ?></td>
                            <td class="text-center"><?= number_format($nota['total_qty'], 0, ',', '.'); ?> Pcs</td>
                            <td class="text-right">Rp <?= number_format($nota['total_harga'], 0, ',', '.'); ?></td>
                        </tr>
                        <?php
                    }
                } else {
                    echo '<tr><td colspan="5" class="text-center">Tidak ada data pembelian pada periode ini.</td></tr>';
                }
                ?>
            </tbody>
            <tfoot>
                <tr style="font-weight: bold; background-color: #f2f2f2;">
                    <td colspan="3" class="text-center">TOTAL</td>
                    <td class="text-center"><?= number_format($grand_total_qty, 0, ',', '.'); ?> Pcs</td>
                    <td class="text-right">Rp <?= number_format($grand_total_harga, 0, ',', '.'); ?></td>
                </tr>
            </tfoot>
        </table>

        <!-- Area Tanda Tangan -->
        <table class="footer-ttd">
            <tr>
                <td width="60%"></td>
                <td class="text-center">
                    <?= date('d F Y'); ?><br>
                    kasir / Staff,<br><br><br><br><br>
                    ( ___________________ )
                </td>
            </tr>
        </table>
    </div>

</body>
</html>