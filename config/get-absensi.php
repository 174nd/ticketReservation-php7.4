<?php
$backurl = '../';
require_once($backurl . 'config/conn.php');
require_once($backurl . 'config/function.php');

if ($_POST['set'] == 'Refresh') {
  $sql = mysqli_query($conn, "SELECT
  (SELECT COALESCE(COUNT(karyawan.nup),0) FROM karyawan WHERE nup NOT IN (SELECT nup FROM absensi WHERE DATE(waktu_absensi)=CURDATE())) AS tidak_hadir, (SELECT COALESCE(COUNT(nup),0) FROM (SELECT nup FROM absensi WHERE DATE(waktu_absensi)=CURDATE() GROUP BY nup) AS a WHERE (SELECT stt_absensi FROM absensi AS xx WHERE xx.nup=a.nup ORDER BY waktu_absensi DESC LIMIT 1)='masuk') AS masuk, (SELECT COALESCE(COUNT(nup),0) FROM (SELECT nup FROM absensi WHERE DATE(waktu_absensi)=CURDATE() GROUP BY nup) AS a WHERE (SELECT stt_absensi FROM absensi AS xx WHERE xx.nup=a.nup ORDER BY waktu_absensi DESC LIMIT 1)='keluar') AS keluar");
  if (mysqli_num_rows($sql) > 0) {
    $Data = mysqli_fetch_assoc($sql);
    $hasil = [
      'tidak_hadir' => $Data['tidak_hadir'],
      'masuk' => $Data['masuk'],
      'keluar' => $Data['keluar'],
      'status' => 'done'
    ];
  } else {
    $hasil['status'] = 'none';
  }
} else if ($_POST['set'] == 'set_masuk') {
  $sql = mysqli_query($conn, "SELECT nup FROM absensi AS a WHERE DATE(waktu_absensi)=CURDATE() AND (SELECT stt_absensi FROM absensi AS xx WHERE xx.nup=a.nup ORDER BY waktu_absensi DESC LIMIT 1)='masuk' AND a.nup='$_POST[nup]' LIMIT 1");
  if (mysqli_num_rows($sql) == 0) {
    $data_tanggal = date('Y-m-d');
    $data_waktu = date('H:i:s');
    $set = array(
      'nup' =>  $_POST['nup'],
      'stt_absensi' => 'masuk',
      'waktu_absensi' => $data_tanggal . ' ' . $data_waktu,
    );
    if (setInsert($set, 'absensi')) {
      $dataSql = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nm_karyawan,foto_karyawan FROM karyawan WHERE nup='$_POST[nup]' LIMIT 1"));
      $hasil = [
        'nup' =>  $_POST['nup'],
        'nm_karyawan' => $dataSql['nm_karyawan'],
        'foto_karyawan' => $dataSql['foto_karyawan'],
        'waktu_absensi' => $data_waktu . ', ' . tanggal_indo($data_tanggal),
        'status' =>  'done',
      ];
    } else {
      $hasil['status'] = 'none';
    }
  } else {
    $hasil['status'] = 'masuk';
  }
} else if ($_POST['set'] == 'set_izin') {
  $sql = mysqli_query($conn, "SELECT nup FROM absensi AS a WHERE DATE(waktu_absensi)=CURDATE() AND (SELECT stt_absensi FROM absensi AS xx WHERE xx.nup=a.nup ORDER BY waktu_absensi DESC LIMIT 1)='keluar' AND a.nup='$_POST[nup]' LIMIT 1");
  if (mysqli_num_rows($sql) == 0) {
    $data_tanggal = date('Y-m-d');
    $data_waktu = date('H:i:s');
    $set = array(
      'nup' =>  $_POST['nup'],
      'stt_absensi' => 'keluar',
      'ket_absensi' =>  $_POST['ket_absensi'],
      'waktu_absensi' => $data_tanggal . ' ' . $data_waktu,
    );
    if (setInsert($set, 'absensi')) {
      $dataSql = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nm_karyawan,foto_karyawan FROM karyawan WHERE nup='$_POST[nup]' LIMIT 1"));
      $hasil = [
        'nup' =>  $_POST['nup'],
        'nm_karyawan' => $dataSql['nm_karyawan'],
        'foto_karyawan' => $dataSql['foto_karyawan'],
        'ket_absensi' =>  $_POST['ket_absensi'],
        'waktu_absensi' => $data_waktu . ', ' . tanggal_indo($data_tanggal),
        'status' =>  'done',
      ];
    } else {
      $hasil['status'] = 'none';
    }
  } else {
    $hasil['status'] = 'keluar';
  }
} else if ($_POST['set'] == 'get_undian') {
  $sql = mysqli_query($conn, "SELECT nup FROM absensi AS a WHERE DATE(waktu_absensi)=CURDATE() AND (SELECT stt_absensi FROM absensi AS xx WHERE xx.nup=a.nup ORDER BY waktu_absensi DESC LIMIT 1)='masuk' AND a.nup='$_POST[nup]' LIMIT 1");
  if (mysqli_num_rows($sql) > 0) {
    $sql = mysqli_query($conn, "SELECT nm_karyawan,foto_karyawan FROM karyawan WHERE nup='$_POST[nup]' LIMIT 1");
    if (mysqli_num_rows($sql) > 0) {
      $dataSql = mysqli_fetch_assoc($sql);
      $hasil = [
        'nup' =>  $_POST['nup'],
        'nm_karyawan' => $dataSql['nm_karyawan'],
        'foto_karyawan' => $dataSql['foto_karyawan'],
        'status' =>  'done',
      ];
    } else {
      $hasil['status'] = 'none';
    }
  } else {
    $hasil['status'] = 'tidak-hadir';
  }
} else {
  $hasil['status'] = 'none';
}

echo json_encode($hasil);
