<?php
$db = array(
  'host'   => 'localhost',
  'user'   => 'root',
  'pass'   => '',
  'db'   => 'db_loket'
);

$host = 'http://localhost/loket/';

$df = array(
  'host'          => $host,
  'head'          => 'Pemesanan Tiket Kapal Ferry Pelabuhan Domestik Sekupang',
  'favicon'       => $host . 'dist/img/logo.png',
  'user-image'    => $host . 'dist/img/user.png',
  'brand-image'   => $host . 'dist/img/logo.png',
);

$conn = mysqli_connect($db['host'], $db['user'], $db['pass'], $db['db']);
date_default_timezone_set("Asia/Jakarta");
if (mysqli_connect_errno()) {
  echo "Koneksi database gagal : " . mysqli_connect_error();
}
