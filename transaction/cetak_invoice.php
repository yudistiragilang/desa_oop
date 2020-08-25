<?php
session_start();
require('../assets/fpdf/fpdf.php');
require_once '../database/Login.php';
require_once 'Transaction.php';

$id = $_GET['id'];
$db = new Login();
$userLogin = $db->user_online();
$namaUser = $userLogin['nama'];
$trans = new Transaction();
$data = $trans->get_data_service_by_id($id);

foreach ($data as $key) {
	$nama = $key['nama'];
	$alamat = $key['alamat'];
}

$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(190, 7, 'FAKTUR TAGIHAN', 0, 1, 'C');

$pdf->Cell(10, 7, '', 0, 1);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(130, 6, 'CV. FAIZ PUTRA JAYA', 0, 0);
$pdf->Cell(60, 6, 'Kepada Yth : ', 0, 1);

$pdf->Cell(130, 6, 'Solo Baru - Sukoharjo', 0, 0);
$pdf->Cell(60, 6, 'Customer : '.$nama, 0, 1);

$pdf->Cell(130, 6, '', 0, 0);
$pdf->Cell(60, 6, 'Alamat : '.$alamat, 0, 1);

$pdf->Cell(130, 6, '', 0, 0);
$pdf->Cell(60, 6, 'Nomor : '.$id, 0, 1);

$pdf->Cell(130, 6, '', 0, 0);
$pdf->Cell(60, 6, 'Tanggal : '.date('d-M-Y'), 0, 1);

$pdf->Cell(10, 7, '', 0, 1, 'C');

$pdf->Cell(10, 6, 'No', 1, 0, 'C');
$pdf->Cell(80, 6, 'Item', 1, 0, 'C');
$pdf->Cell(30, 6, 'Harga', 1, 0, 'C');
$pdf->Cell(40, 6, 'Biaya Tambahan', 1, 0, 'C');
$pdf->Cell(30, 6, 'Total', 1, 1, 'C');

$no = 1;
$total = 0;
foreach ($data as $dt) {
	$pdf->Cell(10, 6, $no, 'L,R', 0, 'C');
	$pdf->Cell(80, 6, $dt['description'], 'R', 0, 'C');
	$pdf->Cell(30, 6, $dt['harga'], 'R', 0, 'C');
	$pdf->Cell(40, 6, $dt['biaya_tambahan'], 'R', 0, 'C');
	$pdf->Cell(30, 6, ($dt['harga']+$dt['biaya_tambahan']), 'R', 1, 'C');

	$pdf->Cell(10, 6, '', 'L,R', 0, 'C');
	$pdf->Cell(80, 6, '', 'R', 0, 'C');
	$pdf->Cell(30, 6, '', 'R', 0, 'C');
	$pdf->Cell(40, 6, '', 'R', 0, 'C');
	$pdf->Cell(30, 6, '', 'R', 1, 'C');

	$no++;
	$total += ($dt['harga']+$dt['biaya_tambahan']);
}

$pdf->Cell(160, 6, 'Grand Total', 'L,B,T', 0, 'C');
$pdf->Cell(30, 6, $total, 'R,B,T', 1, 'C');

$pdf->Cell(10, 7, '', 0, 1, 'C');
$pdf->Cell(160, 6, '', 0, 0, 'C');
$pdf->Cell(30, 6, 'Sukoharjo,', 0, 1, 'C');
$pdf->Ln(15);
$pdf->Cell(160, 6, '', 0, 0, 'C');
$pdf->Cell(30, 6, $namaUser, 0, 1, 'C');

$pdf->Cell(190, 6, "Terima kasih Atas Kepercayaan Anda", 0, 1, 'C');


$pdf->Output('I', 'invoice'.$id.'.pdf');

function _sql_to_date($original_date){
	$timestamp = strtotime($original_date);
	$new_date = date("d-M-Y", $timestamp);
	return $new_date;
}

?>