<?php
$backurl = '../../';
require_once($backurl . 'user/config/settings.php');
require $backurl . 'plugins/PhpSpreadSheet/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

$spreadsheet = new Spreadsheet();

$inputFileType = 'Xlsx';
$inputFileName = './laporan-keberangkatan.xlsx';
$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
$spreadsheet = $reader->load($inputFileName);


$judul = 'Laporan Jadwal Keberangkatan - ' . tanggal_indo((date('Y-m-d')));

$no = 1;
$numrow = 4;
$spreadsheet->getActiveSheet()->setCellValue('D2', tanggal_indo((date('Y-m-d'))));
$sql = mysqli_query($conn, "SELECT nm_kapal,(SELECT nm_pelabuhan FROM pelabuhan WHERE pelabuhan.id_pelabuhan=rute.pelabuhan_awal) AS pelabuhan_awal, (SELECT nm_pelabuhan FROM pelabuhan WHERE pelabuhan.id_pelabuhan=rute.pelabuhan_akhir) AS pelabuhan_akhir, CONCAT(jadwal.hari_jadwal,', ', SUBSTRING(jadwal.jam_jadwal, 1, 5),' WIB') AS jadwal, rute.harga_tiket FROM kapal JOIN jadwal JOIN rute WHERE kapal.id_kapal=jadwal.id_kapal AND jadwal.id_jadwal=rute.id_jadwal AND jadwal.stt_jadwal='Y' ORDER BY kapal.nm_kapal ASC");
while ($data = mysqli_fetch_array($sql)) {
  $spreadsheet->getActiveSheet()->setCellValue('A' . $numrow, $no);
  $spreadsheet->getActiveSheet()->setCellValue('B' . $numrow, $data['nm_kapal']);
  $spreadsheet->getActiveSheet()->setCellValue('C' . $numrow, $data['pelabuhan_awal']);
  $spreadsheet->getActiveSheet()->setCellValue('D' . $numrow, $data['pelabuhan_akhir']);
  $spreadsheet->getActiveSheet()->setCellValue('E' . $numrow, $data['jadwal']);
  $spreadsheet->getActiveSheet()->setCellValue('F' . $numrow, $data['harga_tiket']);
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
