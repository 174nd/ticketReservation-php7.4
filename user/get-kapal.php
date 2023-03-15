<?php
$backurl = '../';
require_once($backurl . 'user/config/settings.php');

if ($_POST['set'] == 'get_jadwal') {

  $data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT (SELECT nm_pelabuhan from pelabuhan WHERE pelabuhan.id_pelabuhan=$_POST[pelabuhan_asal]) AS pelabuhan_awal, (SELECT nm_pelabuhan from pelabuhan WHERE pelabuhan.id_pelabuhan=$_POST[pelabuhan_tujuan]) AS pelabuhan_akhir"));



  $set_rute = array();
  $sql1 = mysqli_query($conn, "SELECT * FROM ((rute JOIN jadwal) JOIN kapal) WHERE rute.id_jadwal=jadwal.id_jadwal AND jadwal.id_kapal=kapal.id_kapal AND rute.pelabuhan_awal='$_POST[pelabuhan_asal]' AND rute.pelabuhan_akhir='$_POST[pelabuhan_tujuan]' AND jadwal.stt_jadwal='Y'");
  for ($i = 0; $Data = mysqli_fetch_assoc($sql1); $i++) {
    $set_rute[] = [
      'id_rute' => $Data['id_rute'],
      'id_kapal' => $Data['id_kapal'],
      'nm_kapal' => $Data['nm_kapal'],
      'hari_jadwal' => $Data['hari_jadwal'],
      'jam_jadwal' => $Data['jam_jadwal'],
      'harga_tiket' => format_rupiah($Data['harga_tiket']),
    ];
  }

  $hasil = [
    'pelabuhan_asal' => $data['pelabuhan_awal'],
    'pelabuhan_tujuan' => $data['pelabuhan_akhir'],
    'data_rute' => $set_rute,
    'status' => 'done'
  ];
} else if ($_POST['set'] == 'get_kursi') {
  $sql = mysqli_query($conn, "SELECT * FROM ((rute JOIN jadwal) JOIN kapal) WHERE rute.id_jadwal=jadwal.id_jadwal AND jadwal.id_kapal=kapal.id_kapal AND rute.id_rute='$_POST[id_rute]' AND jadwal.stt_jadwal='Y'");
  if (mysqli_num_rows($sql) > 0) {
    $data = mysqli_fetch_assoc($sql);

    if ($_POST['id_tiket'] != '') {
      $data1 = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tiket WHERE id_tiket='$_POST[id_tiket]'"));
    } else {
      $data1 = [
        'id_tiket' => '',
        'nm_penumpang' => '',
        'umur_penumpang' => '',
        'jk_penumpang' => 'laki-laki',
      ];
    }

    $set_kursi = array();
    $sql1 = mysqli_query($conn, "SELECT * FROM kursi WHERE kursi.id_kapal='$data[id_kapal]'");
    for ($i = 0; $Data = mysqli_fetch_assoc($sql1); $i++) {
      $set_kursi[] = [
        'id' => $Data['id_kursi'],
        'text' => $Data['no_kursi'],
      ];
    }

    $hasil = [
      'id_tiket' => $data1['id_tiket'],
      'nm_penumpang' => $data1['nm_penumpang'],
      'umur_penumpang' => $data1['umur_penumpang'],
      'jk_penumpang' => $data1['jk_penumpang'],
      'id_rute' => $data['id_rute'],
      'nm_kapal' => $data['nm_kapal'],
      'harga_tiket' => $data['harga_tiket'],
      'id_kursi' => $set_kursi,
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
} else {
  $hasil['status'] = 'none';
}

echo json_encode($hasil);
