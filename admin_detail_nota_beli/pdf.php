<?php
require_once '../database/koneksi.php';
require('../asett/fpdf/fpdf.php');

$kode_nota = isset($_GET['kode_nota']) ? mysqli_real_escape_string($koneksi, $_GET['kode_nota']) : '';

if (empty($kode_nota)) {
    die('Kode nota tidak ditemukan.');
}

// Check Data Detail Pembelian
$q_detail = mysqli_query($koneksi, "SELECT * FROM tbl_detail_nota_beli WHERE kode_nota = '$kode_nota'") or die(mysqli_error($koneksi));

if (mysqli_num_rows($q_detail) == 0) {
    die('Data barang pada nota tidak ditemukan.');
}

$jumlah_data = mysqli_num_rows($q_detail);
$tinggi_kertas = 95 + ($jumlah_data * 10);

class PDF extends FPDF
{
    function Header() {}
    function Footer() {}

    // Garis Pemisah Modern Tipis
    function DrawLine()
    {
        $this->SetDrawColor(180, 180, 180);
        $this->SetLineWidth(0.2);
        $this->Line($this->GetX(), $this->GetY() + 1, $this->GetX() + 50, $this->GetY() + 1);
        $this->Ln(3);
    }
}

$pdf = new PDF('P', 'mm', array(58, $tinggi_kertas));
$pdf->SetMargins(4, 4, 4);
$pdf->SetAutoPageBreak(false);
$pdf->AddPage();

// ==================== HEADER NOTA ====================
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(50, 5, 'NOTA PEMBELIAN', 0, 1, 'C');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(50, 3.5, 'POINT OF SALES', 0, 1, 'C');

$pdf->Ln(2);
$pdf->DrawLine();

// ==================== INFORMASI TRANSAKSI ====================
$pdf->SetFont('Arial', '', 7);

$pdf->Cell(16, 3.5, 'Kode Nota', 0, 0, 'L');
$pdf->Cell(2, 3.5, ':', 0, 0, 'C');
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(32, 3.5, $kode_nota, 0, 1, 'L');

$pdf->SetFont('Arial', '', 7);
$pdf->Cell(16, 3.5, 'Tanggal', 0, 0, 'L');
$pdf->Cell(2, 3.5, ':', 0, 0, 'C');
$pdf->Cell(32, 3.5, date('d-m-Y H:i'), 0, 1, 'L');

$pdf->Ln(1);
$pdf->DrawLine();

// ==================== DETAIL BARANG ====================
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(50, 4, 'DETAIL PEMBELIAN', 0, 1, 'L');
$pdf->Ln(1);

$grand_total = 0;

while ($dt = mysqli_fetch_array($q_detail)) {

    $kd_brg = $dt['kode_brg'];
    $q_barang = mysqli_query($koneksi, "SELECT nama_brg FROM tbl_barang WHERE kode_brg = '$kd_brg'");
    $d_barang = mysqli_fetch_array($q_barang);

    if (isset($d_barang['nama_brg'])) {
        $nama_brg = $d_barang['nama_brg'];
    } else {
        $nama_brg = 'Tidak Ditemukan';
    }

    $harga  = $dt['harga_beli'];
    $jumlah = $dt['jumlah'];
    $total  = $dt['total_harga_beli'];

    $grand_total += $total;

    // Nama Barang
    $pdf->SetFont('Arial', 'B', 7.5);
    $pdf->MultiCell(50, 3.5, $nama_brg, 0, 'L');

    // Qty x Harga Beli & Subtotal
    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(28, 3.5, 'Rp ' . number_format($harga, 0, ',', '.') . ' x ' . $jumlah, 0, 0, 'L');
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->Cell(22, 3.5, 'Rp ' . number_format($total, 0, ',', '.'), 0, 1, 'R');
    
    $pdf->Ln(1);
}

$pdf->DrawLine();

// ==================== TOTAL ====================
$pdf->SetFont('Arial', 'B', 8.5);
$pdf->Cell(25, 5, 'TOTAL', 0, 0, 'L');
$pdf->Cell(25, 5, 'Rp ' . number_format($grand_total, 0, ',', '.'), 0, 1, 'R');

$pdf->Ln(2);
$pdf->DrawLine();

// ==================== FOOTER ====================
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(50, 3.5, 'Terima kasih atas pembelian Anda', 0, 1, 'C');
$pdf->Cell(50, 3.5, 'Simpan nota sebagai bukti transaksi', 0, 1, 'C');

// Output PDF Stream
$pdf->Output('I', 'Nota-Beli-' . $kode_nota . '.pdf');
?>