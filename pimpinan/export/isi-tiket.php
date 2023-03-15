<?php
if (isset($_GET['id'])) {

  $sql = mysqli_query($conn, "SELECT *,(SELECT nm_pelabuhan from pelabuhan WHERE pelabuhan.id_pelabuhan=rute.pelabuhan_awal) AS pelabuhan_awal, (SELECT nm_pelabuhan from pelabuhan WHERE pelabuhan.id_pelabuhan=rute.pelabuhan_akhir) AS pelabuhan_akhir, CONCAT(jadwal.hari_jadwal,', ', SUBSTRING(jadwal.jam_jadwal, 1, 5),' WIB') AS jadwal FROM tiket JOIN rute JOIN jadwal JOIN kursi JOIN kapal WHERE tiket.id_rute=rute.id_rute AND tiket.id_kursi=kursi.id_kursi AND rute.id_jadwal=jadwal.id_jadwal AND kursi.id_kapal=kapal.id_kapal AND tiket.id_tiket='$_GET[id]'");
  if (mysqli_num_rows($sql) > 0) {
    $data = mysqli_fetch_assoc($sql);
    $id = date('y/m/d', strtotime($data['tgl_keberangkatan'])) . '/' . $data['id_rute']  . '/' . $data['id_kursi']  . '/' . $data['id_user']  . '/' . $data['id_tiket'];
    QRcode::png($id, $backurl . "pimpinan/export/barcode.png", QR_ECLEVEL_H, 10);

?>

    <table style="width: 100%;" border="1">
      <tr>
        <td style="width: 25%;"><img src='<?= $backurl . "pimpinan/export/barcode.png" ?>' style="width: 230px;" /></td>
        <td style="width: 55%;">

          <table style="margin-left:10px;">
            <tr>
              <td style="width: 150px;">Nama Penumpang</td>
              <td>:</td>
              <td><?= $data['nm_penumpang']; ?></td>
            </tr>


            <tr>
              <td>Umur</td>
              <td>:</td>
              <td><?= $data['umur_penumpang'] . ' Tahun'; ?></td>
            </tr>


            <tr>
              <td>Jenis Kelamin</td>
              <td>:</td>
              <td><?= $data['jk_penumpang']; ?></td>
            </tr>


            <tr>
              <td>Nomor Kursi</td>
              <td>:</td>
              <td><?= $data['no_kursi']; ?></td>
            </tr>


            <tr>
              <td>Kapal</td>
              <td>:</td>
              <td><?= $data['nm_kapal']; ?></td>
            </tr>


            <tr>
              <td>Jadwal</td>
              <td>:</td>
              <td><?= tanggal_indo($data['tgl_keberangkatan']) . ', ' . $data['jadwal']; ?></td>
            </tr>


            <tr>
              <td>Pelabuhan Asal</td>
              <td>:</td>
              <td><?= $data['pelabuhan_awal']; ?></td>
            </tr>


            <tr>
              <td>Pelabuhan Tujuan</td>
              <td>:</td>
              <td><?= $data['pelabuhan_akhir']; ?></td>
            </tr>
          </table>

        </td>
      </tr>
    </table>

<?php
  } else {
    header("location:" . $df['home']);
    exit();
  }
} else {
  header("location:" . $df['home']);
  exit();
}
