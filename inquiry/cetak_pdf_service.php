<?php

require('../assets/fpdf/fpdf.php');
require_once '../database/Login.php';
require_once 'Inquiry.php';


$tgl_from = $_GET['from'];
$tgl_to = $_GET['to'];
$id_pelanggan = $_GET['id_pelanggan'];
$id_service = $_GET['id_service'];

// Setting halaman PDF
$pdf = new FPDF('L','mm','A4');

// Menambah halaman baru
$pdf->AddPage();

// Setting jenis font
$pdf->SetFont('Arial','B',16);

// Membuat string
$pdf->Cell(280,7,'LAPORAN SERVICE',0,1,'C');
$pdf->SetFont('Arial','B',9);
$pdf->Cell(280,7,'PERIODE '.$tgl_from.' - '.$tgl_to,0,1,'C');

// Setting spasi kebawah supaya tidak rapat
$pdf->Cell(10,7,'',0,1);

$pdf->SetFont('Arial','B',10);
$pdf->Cell(30,6,'Kode Pesan',1,0);
$pdf->Cell(60,6,'Pelanggan',1,0);
$pdf->Cell(45,6,'Service',1,0);
$pdf->Cell(30,6,'Tanggal',1,0);
$pdf->Cell(30,6,'Status',1,0);
$pdf->Cell(80,6,'Memo',1,1);
 
$pdf->SetFont('Arial','',10);

$db = new Login();

$pesan = new Inquiry();
$data = $pesan->get_service_filter($id_service, $id_pelanggan, $tgl_from, $tgl_to);

foreach ($data as $row) {

    $pdf->Cell(30, 6, $row['id_pesan'], 1, 0);
    $pdf->Cell(60, 6, $row['nama'], 1, 0);
    $pdf->Cell(45, 6, $row['description'], 1, 0);
    $pdf->Cell(30, 6, _sql_to_date($row['created_date']), 1, 0);
    $pdf->Cell(30, 6, $row['status'] == 1? "Done":"Repaired", 1, 0);
    $pdf->Cell(80, 6, $row['memo'], 1, 1);

}

$pdf->Output('I', 'Laporan_service.pdf');

function _sql_to_date($original_date){
	$timestamp = strtotime($original_date);
	$new_date = date("d-M-Y", $timestamp);
	return $new_date;
}


?>