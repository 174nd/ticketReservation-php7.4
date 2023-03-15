<?php
$backurl = '../../';
require_once($backurl . 'pimpinan/config/settings.php');
require $backurl . 'plugins/PhpSpreadSheet/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

$spreadsheet = new Spreadsheet();

$inputFileType = 'Xlsx';
$inputFileName = './laporan-penumpang.xlsx';
$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
$spreadsheet = $reader->load($inputFileName);


$judul = 'Laporan Penumpang Tanggal ' . tanggal_indo($_POST['tgl_keberangkatan']);

$no = 1;
$numrow = 5;
$spreadsheet->getActiveSheet()->setCellValue('D2', tanggal_indo($_POST['tgl_keberangkatan']));
$sql = mysqli_query($conn, "SELECT *,CONCAT(jadwal.hari_jadwal,', ', SUBSTRING(jadwal.jam_jadwal, 1, 5),' WIB') AS jadwal FROM tiket JOIN rute JOIN jadwal JOIN kursi JOIN kapal WHERE tiket.id_rute=rute.id_rute AND tiket.id_kursi=kursi.id_kursi AND rute.id_jadwal=jadwal.id_jadwal AND kursi.id_kapal=kapal.id_kapal AND tiket.tgl_keberangkatan='$_POST[tgl_keberangkatan]' ORDER BY tiket.nm_penumpang ASC");
while ($data = mysqli_fetch_array($sql)) {
  $spreadsheet->getActiveSheet()->setCellValue('A' . $numrow, $no);
  $spreadsheet->getActiveSheet()->setCellValue('B' . $numrow, $data['nm_penumpang']);
  $spreadsheet->getActiveSheet()->setCellValue('C' . $numrow, $data['umur_penumpang']);
  $spreadsheet->getActiveSheet()->setCellValue('D' . $numrow, $data['jk_penumpang']);
  $spreadsheet->getActiveSheet()->setCellValue('E' . $numrow, $data['no_kursi']);
  $spreadsheet->getActiveSheet()->setCellValue('F' . $numrow, $data['nm_kapal']);
  $spreadsheet->getActiveSheet()->setCellValue('G' . $numrow, $data['jadwal']);
  $spreadsheet->getActiveSheet()->setCellValue('H' . $numrow, $data['harga_tiket']);
  $spreadsheet->getActiveSheet()->insertNewRowBefore($numrow + 1, 1);
  $spreadsheet->getActiveSheet()->getRowDimension($numrow)->setRowHeight(-1);
  $numrow++;
  $no++;
}
(mysqli_num_rows($sql) > 0) ? $spreadsheet->getActiveSheet()->removeRow(($numrow + 1), 1) : '';



// $spreadsheet->setActiveSheetIndexByName('Realisasi');
$spreadsheet->getProperties()->setCreator('AndL')->setLastModifiedBy('AndL')->setTitle($judul)->setSubject("Kartu Kontrol")->setDescription("Export Data $judul")->setKeywords("$judul");
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $judul . '.xlsx"');
header('Cache-Control: max-age=0');

$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
