<?php
$backurl = '../';
require_once($backurl . 'pimpinan/config/settings.php');

if ($_POST['set'] == 'get_dkapal') {
  $sql = mysqli_query($conn, "SELECT * FROM kapal JOIN perusahaan WHERE kapal.id_perusahaan=perusahaan.id_perusahaan AND kapal.id_kapal='$_POST[id_kapal]'");
  if (mysqli_num_rows($sql) > 0) {
    $data_kapal = mysqli_fetch_assoc($sql);



    $set_jadwal = array();
    $jadwal_aktif = array();
    $sql1 = mysqli_query($conn, "SELECT * FROM jadwal WHERE id_kapal='$_POST[id_kapal]'");
    for ($i = 0; $Data = mysqli_fetch_assoc($sql1); $i++) {
      if ($Data['stt_jadwal'] != 'Y') {
        $set_jadwal[] = [
          'id_jadwal' => $Data['id_jadwal'],
          'hari_jadwal' => $Data['hari_jadwal'],
          'jam_jadwal' => $Data['jam_jadwal'],
          'stt_jadwal' => $Data['stt_jadwal'],
        ];
      } else {
        $jadwal_aktif = [
          'id_jadwal' => $Data['id_jadwal'],
          'hari_jadwal' => $Data['hari_jadwal'],
          'jam_jadwal' => $Data['jam_jadwal'],
          'stt_jadwal' => $Data['stt_jadwal'],
        ];
      }
    }


    $hasil = [
      'nm_kapal' => $data_kapal['nm_kapal'],
      'nm_perusahaan' => $data_kapal['nm_perusahaan'],
      'jadwal_aktif' => $jadwal_aktif,
      'data_jadwal' => $set_jadwal,
      'status' => 'done'
    ];
  } else {
    $hasil['status'] = 'none';
  }
} else if ($_POST['set'] == 'get_data_tiket') {
  $sql = mysqli_query($conn, "SELECT *,(SELECT nm_pelabuhan from pelabuhan WHERE pelabuhan.id_pelabuhan=rute.pelabuhan_awal) AS pelabuhan_awal, (SELECT nm_pelabuhan from pelabuhan WHERE pelabuhan.id_pelabuhan=rute.pelabuhan_akhir) AS pelabuhan_akhir FROM tiket JOIN kursi JOIN rute JOIN jadwal JOIN kapal WHERE tiket.id_kursi=kursi.id_kursi AND tiket.id_rute=rute.id_rute AND rute.id_jadwal=jadwal.id_jadwal AND jadwal.id_kapal=kapal.id_kapal AND tiket.id_tiket='$_POST[id_tiket]' LIMIT 1");
  if (mysqli_num_rows($sql) > 0) {
    $data = mysqli_fetch_assoc($sql);

    $hasil = [
      'nm_penumpang' => $data['nm_penumpang'],
      'umur_penumpang' => $data['umur_penumpang'] . ' Tahun',
      'jk_penumpang' => $data['jk_penumpang'],
      'nm_kapal' => $data['nm_kapal'],
      'no_kursi' => $data['no_kursi'],
      'tujuan' => $data['pelabuhan_awal'] . ' - ' . $data['pelabuhan_akhir'],
      'harga_tiket' => format_rupiah($data['harga_tiket']),
      'tgl_keberangkatan' => tanggal_indo($data['tgl_keberangkatan']),
      'stt_tiket' => $data['stt_tiket'],
      'status' => 'done'
    ];
  } else {
    $hasil['status'] = 'none';
  }
} else if ($_POST['set'] == 'set_tiket') {
  $id = explode('/', $_POST['kode_barcode']);
  $id_tiket = $id[6];
  if ($_POST['status'] == 'ruang-tunggu') {
    $cek = mysqli_query($conn, "SELECT * FROM tiket WHERE stt_tiket='payment' AND id_tiket='$id_tiket' LIMIT 1");
    $set = setUpdate(['stt_tiket' =>  'waiting'], 'tiket', ['id_tiket' => $id_tiket]);
  } else {
    $cek = mysqli_query($conn, "SELECT * FROM tiket WHERE stt_tiket='waiting' AND id_tiket='$id_tiket' LIMIT 1");
    $set = setUpdate(['stt_tiket' =>  'success'], 'tiket', ['id_tiket' => $id_tiket]);
  }

  if (mysqli_num_rows($cek) > 0) {
    if ($set) {
      $sql = mysqli_query($conn, "SELECT *,(SELECT nm_pelabuhan from pelabuhan WHERE pelabuhan.id_pelabuhan=rute.pelabuhan_awal) AS pelabuhan_awal, (SELECT nm_pelabuhan from pelabuhan WHERE pelabuhan.id_pelabuhan=rute.pelabuhan_akhir) AS pelabuhan_akhir FROM tiket JOIN kursi JOIN rute JOIN jadwal JOIN kapal WHERE tiket.id_kursi=kursi.id_kursi AND tiket.id_rute=rute.id_rute AND rute.id_jadwal=jadwal.id_jadwal AND jadwal.id_kapal=kapal.id_kapal AND tiket.id_tiket='$id_tiket' LIMIT 1");
      if (mysqli_num_rows($sql) > 0) {
        $data = mysqli_fetch_assoc($sql);
        $hasil = [
          'nm_penumpang' => $data['nm_penumpang'],
          'umur_penumpang' => $data['umur_penumpang'] . ' Tahun',
          'jk_penumpang' => $data['jk_penumpang'],
          'nm_kapal' => $data['nm_kapal'],
          'no_kursi' => $data['no_kursi'],
          'tujuan' => $data['pelabuhan_awal'] . ' - ' . $data['pelabuhan_akhir'],
          'harga_tiket' => format_rupiah($data['harga_tiket']),
          'tgl_keberangkatan' => tanggal_indo($data['tgl_keberangkatan']),
          'stt_tiket' => $data['stt_tiket'],
          'status' => 'done'
        ];
      } else {
        $hasil['status'] = 'none';
      }
    } else {
      $hasil['status'] = 'none';
    }
  } else {
    $hasil['status'] = 'none';
  }
} else if ($_POST['set'] == 'get_penumpang') {
  $data_tiket = array();
  $sql1 = mysqli_query($conn, "SELECT * FROM tiket JOIN kursi JOIN kapal WHERE tiket.id_kursi=kursi.id_kursi AND kursi.id_kapal=kapal.id_kapal AND tiket.tgl_keberangkatan='$_POST[ctgl_pemesanan]'");
  for ($i = 0; $Data = mysqli_fetch_assoc($sql1); $i++) {
    $data_tiket[] = [
      'id_tiket' => $Data['id_tiket'],
      'nm_penumpang' => $Data['nm_penumpang'],
      'nm_kapal' => $Data['nm_kapal'],
      'no_kursi' => $Data['no_kursi'],
    ];
  }


  $hasil = [
    'data_tiket' => $data_tiket,
    'status' => 'done'
  ];
} else {
  $hasil['status'] = 'none';
}

echo json_encode($hasil);
