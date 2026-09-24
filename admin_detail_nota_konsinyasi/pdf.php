<?php
require_once '../database/koneksi.php';
require('../asett/fpdf/fpdf.php');

// Ambil parameter no_nota_konsinyasi dari URL
$no_nota_konsinyasi = isset($_GET['no_nota_konsinyasi']) ? mysqli_real_escape_string($koneksi, $_GET['no_nota_konsinyasi']) : '';

if (empty($no_nota_konsinyasi)) {
    die('Nomor nota konsinyasi tidak ditemukan.');
}

// 1. Ambil Header Nota Konsinyasi
$q_nota = mysqli_query($koneksi, "SELECT * FROM tbl_nota_konsinyasi WHERE no_nota_konsinyasi = '$no_nota_konsinyasi'") or die(mysqli_error($koneksi));

if (mysqli_num_rows($q_nota) == 0) {
    die('Data nota konsinyasi tidak ditemukan.');
}
$data_nota = mysqli_fetch_assoc($q_nota);

// 2. Ambil Nama Supplier dari tbl_supplier
// SESUAIKAN: ganti 'kode_supplier' jika kolom kunci di tabel nota bernama lain (misal: 'id_supplier')
$kode_supplier = isset($data_nota['kode_supplier']) ? $data_nota['kode_supplier'] : '';
$tampil_supplier = isset($data_nota['nama_supplier']) ? $data_nota['nama_supplier'] : '';

if (!empty($kode_supplier)) {
    // SESUAIKAN: ganti nama kolom 'nama_supplier' atau 'kode_supplier' jika di tbl_supplier berbeda
    $q_supplier = mysqli_query($koneksi, "SELECT nama_supplier FROM tbl_supplier WHERE kode_supplier = '$kode_supplier'");
    if ($q_supplier && mysqli_num_rows($q_supplier) > 0) {
        $d_supplier = mysqli_fetch_assoc($q_supplier);
        // Menampilkan: Nama Supplier (Kode)
        $tampil_supplier = $d_supplier['nama_supplier'] . ' (' . $kode_supplier . ')';
    } else {
        $tampil_supplier = $kode_supplier;
    }
}

// 3. Ambil Detail Barang Titipan
$q_detail = mysqli_query($koneksi, "SELECT * FROM tbl_detail_nota_konsinyasi WHERE no_nota_konsinyasi = '$no_nota_konsinyasi'") or die(mysqli_error($koneksi));

$jumlah_item = mysqli_num_rows($q_detail);
if ($jumlah_item == 0) {
    die('Data barang pada nota ini masih kosong.');
}

// Hitung Estimasi Tinggi Kertas Roll Thermal (58mm)
$tinggi_kertas = 110 + ($jumlah_item * 11);

// Class PDF Kustom
class PDF_Nota extends FPDF
{
    function Header() {}
    function Footer() {}

    // Garis Pemisah Tipis
    function DrawLine() {
        $this->SetDrawColor(180, 180, 180);
        $this->SetLineWidth(0.2);
        $this->Line($this->GetX(), $this->GetY() + 1, $this->GetX() + 50, $this->GetY() + 1);
        $this->Ln(3);
    }
}

// Inisialisasi PDF
$pdf = new PDF_Nota('P', 'mm', array(58, $tinggi_kertas));
$pdf->SetMargins(4, 4, 4);
$pdf->SetAutoPageBreak(false);
$pdf->AddPage();

// ==================== HEADER NOTA ====================
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(50, 5, 'NOTA KONSINYASI', 0, 1, 'C');
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(50, 3, 'TANDA TERIMA BARANG TITIPAN', 0, 1, 'C');

$pdf->Ln(2);
$pdf->DrawLine();

// ==================== INFORMASI NOTA ====================
$pdf->SetFont('Arial', '', 7);

// No Nota
$pdf->Cell(16, 3.5, 'No. Nota', 0, 0, 'L');
$pdf->Cell(2, 3.5, ':', 0, 0, 'C');
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(32, 3.5, $no_nota_konsinyasi, 0, 1, 'L');

// Tanggal
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(16, 3.5, 'Tanggal', 0, 0, 'L');
$pdf->Cell(2, 3.5, ':', 0, 0, 'C');
$tgl = !empty($data_nota['tgl_nota']) ? date('d/m/Y', strtotime($data_nota['tgl_nota'])) : date('d/m/Y');
$pdf->Cell(32, 3.5, $tgl, 0, 1, 'L');

// Supplier (Nama + Kode)
$pdf->Cell(16, 3.5, 'Supplier', 0, 0, 'L');
$pdf->Cell(2, 3.5, ':', 0, 0, 'C');
$pdf->Cell(32, 3.5, substr($tampil_supplier, 0, 22), 0, 1, 'L');

$pdf->Ln(1);
$pdf->DrawLine();

// ==================== DETAIL BARANG ====================
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(50, 4, 'RINCIAN BARANG TITIPAN', 0, 1, 'L');
$pdf->Ln(1);

$grand_total = 0;
$total_qty = 0;

while ($dt = mysqli_fetch_assoc($q_detail)) {
    $kode_brg = $dt['kode_brg_konsinyasi'];

    // Ambil Data Barang Terpisah
    $q_barang = mysqli_query($koneksi, "SELECT nama_brg FROM tbl_barang_konsinyasi WHERE kode_brg_konsinyasi = '$kode_brg'");
    $d_barang = mysqli_fetch_assoc($q_barang);

    if (isset($d_barang['nama_brg_konsinyasi'])) {
        $nama_brg = $d_barang['nama_brg_konsinyasi'];
        $satuan   = !empty($d_barang['satuan']) ? $d_barang['satuan'] : 'Pcs';
    } else {
        $nama_brg = 'Barang #' . $kode_brg;
        $satuan   = 'Pcs';
    }

    $harga    = (float)$dt['harga_satuan'];
    $qty      = (int)$dt['jumlah_titip'];
    $subtotal = (float)$dt['subtotal'];

    $grand_total += $subtotal;
    $total_qty   += $qty;

    // Nama Barang
    $pdf->SetFont('Arial', 'B', 7.5);
    $pdf->MultiCell(50, 3.5, $nama_brg, 0, 'L');

    // Qty x Harga & Subtotal
    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(28, 3.5, $qty . ' ' . $satuan . ' x Rp ' . number_format($harga, 0, ',', '.'), 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->Cell(22, 3.5, 'Rp ' . number_format($subtotal, 0, ',', '.'), 0, 1, 'R');
    
    $pdf->Ln(1);
}

$pdf->DrawLine();

// ==================== RINGKASAN / TOTAL ====================
$pdf->SetFont('Arial', '', 7.5);
$pdf->Cell(28, 4, 'Total Item Titip', 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 7.5);
$pdf->Cell(22, 4, $total_qty . ' Pcs', 0, 1, 'R');

$pdf->SetFont('Arial', 'B', 8.5);
$pdf->Cell(25, 5, 'GRAND TOTAL', 0, 0, 'L');
$pdf->Cell(25, 5, 'Rp ' . number_format($grand_total, 0, ',', '.'), 0, 1, 'R');

$pdf->Ln(2);
$pdf->DrawLine();

// ==================== FOOTER ====================
$pdf->SetFont('Arial', 'I', 6.5);
$pdf->Cell(50, 3, 'Harap simpan nota ini sebagai bukti', 0, 1, 'C');
$pdf->Cell(50, 3, 'penitipan barang konsinyasi.', 0, 1, 'C');

// Output PDF
$pdf->Output('I', 'Nota-Konsinyasi-' . $no_nota_konsinyasi . '.pdf');
?>