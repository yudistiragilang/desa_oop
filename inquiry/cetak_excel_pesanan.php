<?php

/**
*  Author : Yudistira Gilang Adisetyo
*  Email  : yudhistiragilang22@gmail.com
*  
*/

require '../assets/vendor/autoload.php';
require_once '../database/Login.php';
require_once 'Inquiry.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$tgl_from = $_GET['from'];
$tgl_to = $_GET['to'];
$id_pelanggan = $_GET['id_pelanggan'];
$id_service = $_GET['id_service'];

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'LAPORAN PEMESANAN');
$sheet->setCellValue('A2', 'PERIODE '.$_GET['from'].' - '.$_GET['to']);
$sheet->setCellValue('A4', 'Kode Pesan');
$sheet->setCellValue('B4', 'Pelanggan');
$sheet->setCellValue('C4', 'Service');
$sheet->setCellValue('D4', 'Harga');
$sheet->setCellValue('E4', 'Tanggal');
$sheet->setCellValue('F4', 'Status');
$sheet->setCellValue('G4', 'Memo');

$sheet->mergeCells('A1:F1');
$sheet->mergeCells('A2:F2');
$sheet->getColumnDimension('A')->setWidth(15);
$sheet->getColumnDimension('B')->setWidth(30);
$sheet->getColumnDimension('C')->setWidth(30);
$sheet->getColumnDimension('D')->setWidth(25);
$sheet->getColumnDimension('E')->setWidth(20);
$sheet->getColumnDimension('F')->setWidth(50);
$sheet->getColumnDimension('G')->setWidth(50);

$pesan = new Inquiry();
$data = $pesan->get_pesanan_filter($id_service, $id_pelanggan, $tgl_from, $tgl_to);

$i = 5;
$no = 1;



foreach ($data as $row) {

	$sheet->setCellValue('A'.$i, $row['id_pesan']);
	$sheet->setCellValue('B'.$i, $row['nama']);
	$sheet->setCellValue('C'.$i, $row['description']);
	$sheet->setCellValue('D'.$i, $row['harga']);
	$sheet->setCellValue('E'.$i, _sql_to_date($row['created_date']));
	$sheet->setCellValue('F'.$i, _get_status($row['status']));
	$sheet->setCellValue('G'.$i, $row['memo']);
	$i++;

}

$styleTitle = [
    'font' => [
        'bold' => true,
        'size' => 16,
    ],
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
    ],
];
$sheet->getStyle('A1:A2')->applyFromArray($styleTitle);

$styleData = [
			'borders' => [
				'allBorders' => [
					'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
				],
			],
		];
$i = $i - 1;
$sheet->getStyle('A4:G'.$i)->applyFromArray($styleData);
$sheet->getStyle('A4:G4')->getFont()->setBold(true);

$writer = new Xlsx($spreadsheet);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Laporan_pemesanan.xlsx"');
$writer->save('php://output');


function _get_status($id){
	$status = "";
		switch ($id) {
			    case 0:
			        $status = "OPEN";
			        break;
			    case 1:
			        $status = "APPROVED";
			        break;
			    case -1:
			        $status = "REJECT";
			        break;
			    default:
			        $status;
			}

		return $status;
}

function _sql_to_date($original_date){
	$timestamp = strtotime($original_date);
	$new_date = date("d-M-Y", $timestamp);
	return $new_date;
}


?>