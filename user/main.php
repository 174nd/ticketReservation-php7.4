<?php
$backurl = '../';
require_once($backurl . 'user/config/settings.php');
$pset = array(
  'title' => 'Dashboard',
  'content' => 'Dashboard',
  'breadcrumb' => array(
    'Dashboard' => 'active',
  ),
);

if (isset($_POST['u-password'])) {
  $pass = md5($_POST['pass_lama']);
  $cek = mysqli_query($conn, "SELECT * FROM login WHERE username LIKE '$_SESSION[username]' AND password LIKE '$pass'");
  $ketemu = mysqli_num_rows($cek);
  if ($ketemu > 0) {
    if ($_POST['pass_baru1'] == $_POST["pass_baru2"]) {
      $set = array(
        'pass' => $_POST['pass_baru1'],
      );
      $val = array(
        'id_user' => $_SESSION['id_user'],
        'pass' => $_POST['pass_lama'],
      );
      $query = setUpdate($set, 'user', $val);
      if (!$query) {
        $_SESSION['notifikasi'] = 'NOT02';
      } else {
        $_SESSION["password"] = md5($_POST['pass_baru1']);
        $_SESSION['notifikasi'] = 'NOT04';
      }
    } else {
      $_SESSION['notifikasi'] = 'NOT08';
    }
  } else {
    $_SESSION['notifikasi'] = 'NOT07';
  }
}


if (isset($_POST["pesan-tiket"])) {
  $_POST = setData($_POST);
  if ($_POST['id_tiket'] != '') {
    $set = [
      'id_rute' =>  $_POST['id_rute'],
      'id_user' =>  $_SESSION['id_user'],
      'id_kursi' =>  $_POST['id_kursi'],
      'nm_penumpang' =>  $_POST['nm_penumpang'],
      'umur_penumpang' =>  $_POST['umur_penumpang'],
      'jk_penumpang' =>  $_POST['jk_penumpang'],
      'tgl_keberangkatan' =>  $_POST['tgl_keberangkatan'],
      'stt_tiket' => 'booking',
    ];
    $query = setUpdate($set, 'tiket', ['id_tiket' => $_POST['id_tiket']]);
  } else {
    $set = [
      'id_rute' =>  $_POST['id_rute'],
      'id_user' =>  $_SESSION['id_user'],
      'id_kursi' =>  $_POST['id_kursi'],
      'nm_penumpang' =>  $_POST['nm_penumpang'],
      'umur_penumpang' =>  $_POST['umur_penumpang'],
      'jk_penumpang' =>  $_POST['jk_penumpang'],
      'tgl_keberangkatan' =>  $_POST['tgl_keberangkatan'],
      'stt_tiket' => 'booking',
    ];
    $query = setInsert($set, 'tiket');
  }
  if (!$query) {
    $_SESSION['notifikasi'] = 'NOT02';
  } else {
    $_SESSION['notifikasi'] = 'NOT03';
  }
}


if (isset($_POST["batal-tiket"])) {
  $_POST = setData($_POST);
  $query = setUpdate(['stt_tiket' => 'cancel'], 'tiket', ['id_tiket' => $_POST['id_tiket']]);
  if (!$query) {
    $_SESSION['notifikasi'] = 'NOT02';
  } else {
    $_SESSION['notifikasi'] = 'NOT05';
  }
}






?>
<!DOCTYPE html>
<html>

<head>
  <?php include $backurl . 'config/site/head.php'; ?>
  <style>
    .list-group-item:first-child:not(.list-group-item.d-none) {
      padding-top: 0;
      border-top: 0;
    }

    .list-group-item:last-child:not(.list-group-item.d-none) {
      padding-bottom: 0;
      border-bottom: 0;
    }
  </style>
</head>

<body class="hold-transition layout-top-nav text-sm">
  <div class="wrapper">
    <?php include $backurl . 'user/config/header.php'; ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <?php include $backurl . 'user/config/content-header.php'; ?>

      <!-- Main content -->
      <div class="content">
        <div class="container">
          <!-- Main row -->
          <div class="row">

            <section class="col-lg-8 connectedSortable">
              <div class="card card-primary card-outline">
                <div class="card-header">
                  <h3 class="card-title">Konfirmasi Pembayaran</h3>
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-minus"></i>
                    </button>
                  </div>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table id="table_pesanan" class="table table-bordered table-hover" style="min-width: 400px;">
                      <thead>
                        <tr>
                          <th>Tanggal</th>
                          <th>Nama Penumpang</th>
                          <th>Kapal</th>
                          <th>Kursi</th>
                          <th>Status</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </section>

            <section class="col-lg-4 connectedSortable">
              <div class="card">
                <div class="card-body bg-primary">
                  <button class="btn btn-block btn-outline-light" data-toggle="modal" data-target="#detail-pembayaran">Cara Pembayaran</button>
                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.Ruang Tunggu -->


              <div class="card card-primary card-outline collapsed-card" id="pemesanan-tiket">
                <div class="card-header">
                  <h3 class="card-title">Pemesanan Tiket</h3>
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-plus"></i>
                    </button>
                  </div>
                </div>
                <div class="card-body">
                  <ul class="list-group list-group-unbordered">
                    <li class="list-group-item">
                      <div class="input-group">
                        <div class="row w-100 ml-0 mr-0">
                          <div class="col-md-12 mb-2">
                            <div class="row">
                              <div class="col-12">
                                <label class="float-right" for="pelabuhan_asal">Asal</label>
                              </div>
                              <div class="col-12">
                                <select name="pelabuhan_asal" id="pelabuhan_asal" class="form-control custom-select select2" required>
                                  <?php
                                  $sql = mysqli_query($conn, "SELECT * FROM pelabuhan ORDER BY nm_pelabuhan ASC");
                                  for ($i = 1; $Data = mysqli_fetch_array($sql); $i++) { ?>
                                    <option value="<?= $Data['id_pelabuhan']; ?>"><?= $Data['nm_pelabuhan']; ?></option>
                                  <?php } ?>
                                </select>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </li>
                    <li class="list-group-item">
                      <div class="input-group">
                        <div class="row w-100 ml-0 mr-0">
                          <div class="col-md-12 mb-2">
                            <div class="row">
                              <div class="col-12">
                                <label class="float-right" for="pelabuhan_tujuan">Tujuan</label>
                              </div>
                              <div class="col-12">
                                <select name="pelabuhan_tujuan" id="pelabuhan_tujuan" class="form-control custom-select select2" required>
                                  <?php
                                  $sql = mysqli_query($conn, "SELECT * FROM pelabuhan ORDER BY nm_pelabuhan ASC");
                                  for ($i = 1; $Data = mysqli_fetch_array($sql); $i++) { ?>
                                    <option value="<?= $Data['id_pelabuhan']; ?>"><?= $Data['nm_pelabuhan']; ?></option>
                                  <?php } ?>
                                </select>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </li>
                    <li class="list-group-item">
                      <button type="button" name="cari-rute" class="btn btn-block btn-outline-primary">Simpan</button>
                    </li>
                  </ul>
                </div>
              </div>


              <div class="card">
                <div class="card-body bg-primary">
                  <a href="<?= $df['home'] . 'export/laporan-keberangkatan.php' ?>" class="btn btn-block btn-outline-light">Laporan Keberangkatan</a>
                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.Ruang Tunggu -->
            </section>
          </div>
          <!-- /.row (main row) -->


        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <div class="modal fade" id="data-jadwal">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="overlay d-flex justify-content-center align-items-center invisible">
            <i class="fas fa-2x fa-sync fa-spin"></i>
          </div>
          <div class="modal-header">
            <h4 class="modal-title">Rute Kapal</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">

            <ul class="list-group list-group-unbordered">
              <li class="list-group-item">
                <b>Pelabuhan Asal</b><span class="float-right" id="pelabuhan_asal">x</span>
              </li>
              <li class="list-group-item">
                <b>Pelabuhan Tujuan</b><span class="float-right" id="pelabuhan_tujuan">x</span>
              </li>
              <li class="list-group-item">
                <div class="table-responsive">
                  <table id="table_jadwal" class="table table-bordered table-hover" style="min-width: 400px;">
                    <thead>
                      <tr>
                        <th>Kapal</th>
                        <th>Hari</th>
                        <th>Jam</th>
                        <th>Harga</th>
                        <th>Act</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>
              </li>
            </ul>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <form class="modal fade" id="pesan-tiket" method="POST" enctype="multipart/form-data" autocomplete="off">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="overlay d-flex justify-content-center align-items-center invisible">
            <i class="fas fa-2x fa-sync fa-spin"></i>
          </div>
          <div class="modal-header">
            <h4 class="modal-title">Pemesanan Tiket</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <input type="hidden" name="id_rute">
          <input type="hidden" name="id_tiket">

          <div class="modal-body">
            <ul class="list-group list-group-unbordered">
              <li class="list-group-item">
                <div class="input-group">
                  <div class="row w-100 ml-0 mr-0">
                    <div class="col-md-12 mb-2">
                      <label class="float-right" for="nm_penumpang">Nama Penumpang</label>
                      <input type="text" name="nm_penumpang" class="form-control" id="nm_penumpang" placeholder="Nama Penumpang" required>
                    </div>
                  </div>
                </div>
              </li>
              <li class="list-group-item">
                <div class="input-group">
                  <div class="row w-100 ml-0 mr-0">
                    <div class="col-md-6 mb-2">
                      <label class="float-right" for="umur_penumpang">Umur</label>
                      <div class="input-group">
                        <input type="number" name="umur_penumpang" class="form-control" id="umur_penumpang" placeholder="Umur" required>
                        <div class="input-group-append">
                          <span class="input-group-text">Tahun</span>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6 mb-2">
                      <label class="float-right" for="jk_penumpang">Jenis Kelamin</label>
                      <select name="jk_penumpang" id="jk_penumpang" class="form-control custom-select" required>
                        <option value="laki-laki">Laki-Laki</option>
                        <option value="perempuan">Perempuan</option>
                      </select>
                    </div>
                  </div>
                </div>
              </li>
              <li class="list-group-item">
                <div class="input-group">
                  <div class="row w-100 ml-0 mr-0">
                    <div class="col-md-6 mb-2">
                      <label class="float-right" for="nm_kapal">Kapal</label>
                      <input type="text" name="nm_kapal" class="form-control" id="nm_kapal" placeholder="Kapal" Disabled>
                    </div>
                    <div class="col-md-6 mb-2">
                      <label class="float-right" for="harga_tiket">Harga</label>
                      <div class="input-group">
                        <div class="input-group-append">
                          <span class="input-group-text">Rp.</span>
                        </div>
                        <input type="text" name="harga_tiket" class="form-control format_uang" id="harga_tiket" placeholder="Harga Tiket" disabled>
                      </div>
                    </div>
                  </div>
                </div>
              </li>
              <li class="list-group-item">
                <div class="input-group">
                  <div class="row w-100 ml-0 mr-0">
                    <div class="col-md-6 mb-2 mb-md-0">
                      <div class="row">
                        <div class="col-12">
                          <label class="float-right" for="id_kursi">Kursi</label>
                        </div>
                        <div class="col-12">
                          <select name="id_kursi" id="id_kursi" class="form-control custom-select select2" required>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <label class="float-right" for="tgl_keberangkatan">Tanggal Keberangkatan</label>
                      <div class="input-group">
                        <div class="input-group-append">
                          <span class="input-group-text"><i class="fa fa-calendar-alt"></i></span>
                        </div>
                        <input type="text" name="tgl_keberangkatan" class="form-control mydatetoppicker" id="tgl_keberangkatan" placeholder="Tanggal Keberangkatan" required>
                      </div>
                    </div>
                  </div>
                </div>
              </li>
            </ul>
          </div>
          <div class="modal-footer">
            <div class="btn-group btn-block">
              <button class="btn btn-outline-danger" data-dismiss="modal">Batal</button>
              <button type="submit" name="pesan-tiket" class="btn btn-outline-success">Simpan</button>
            </div>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </form>
    <!-- /.modal -->

    <div class="modal fade" id="data-pesanan">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="overlay d-flex justify-content-center align-items-center invisible">
            <i class="fas fa-2x fa-sync fa-spin"></i>
          </div>
          <div class="modal-header">
            <h4 class="modal-title">Data Pesanan</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <ul class="list-group list-group-unbordered">
              <li class="list-group-item">
                <b>Nama Penumpang</b><span class="float-right" id="nm_penumpang">x</span>
              </li>
              <li class="list-group-item">
                <b>Umur Penumpang</b><span class="float-right" id="umur_penumpang">x</span>
              </li>
              <li class="list-group-item">
                <b>Jenis Kelamin</b><span class="float-right" id="jk_penumpang">x</span>
              </li>
              <li class="list-group-item">
                <b>Nama Kapal</b><span class="float-right" id="nm_kapal">x</span>
              </li>
              <li class="list-group-item">
                <b>Nomor Kursi</b><span class="float-right" id="no_kursi">x</span>
              </li>
              <li class="list-group-item">
                <b>Tujuan</b><span class="float-right" id="tujuan">x</span>
              </li>
              <li class="list-group-item">
                <b>Harga Tiket</b><span class="float-right" id="harga_tiket">x</span>
              </li>
              <li class="list-group-item">
                <b>Tanggal Keberangkatan</b><span class="float-right" id="tgl_keberangkatan">x</span>
              </li>
              <li class="list-group-item">
                <b>Status Tiket</b><span class="float-right" id="stt_tiket">x</span>
              </li>
              <li class="list-group-item" id="button_pesanan">
                <div class="btn-group btn-block">
                  <button class="btn btn-danger" data-toggle="modal" data-target="#cancel-tiket" id="cancel_tiket">Cancel</button>
                  <button class="btn btn-warning" data-toggle="modal" data-target="#reschedule-tiket" id="reschedule_tiket">Reschedule</button>
                </div>
              </li>
              <li class="list-group-item">
                <div class="btn-group btn-block">
                  <a href="#" id="cetak_tiket" class="btn btn-primary" target="_blank">Cetak Tiket</a>
                </div>
              </li>
            </ul>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <div class="modal fade" id="reschedule-tiket">
      <form method="POST" class="modal-dialog" enctype="multipart/form-data" autocomplete="off">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Reschedule Tiket</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="id_tiket">
            <ul class="list-group list-group-unbordered">
              <li class="list-group-item">
                <div class="input-group">
                  <div class="row w-100 ml-0 mr-0">
                    <div class="col-md-12 mb-2">
                      <div class="row">
                        <div class="col-12">
                          <label class="float-right" for="pelabuhan_asal">Asal</label>
                        </div>
                        <div class="col-12">
                          <select name="pelabuhan_asal" id="pelabuhan_asal" class="form-control custom-select select2" required>
                            <?php
                            $sql = mysqli_query($conn, "SELECT * FROM pelabuhan ORDER BY nm_pelabuhan ASC");
                            for ($i = 1; $Data = mysqli_fetch_array($sql); $i++) { ?>
                              <option value="<?= $Data['id_pelabuhan']; ?>"><?= $Data['nm_pelabuhan']; ?></option>
                            <?php } ?>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </li>
              <li class="list-group-item">
                <div class="input-group">
                  <div class="row w-100 ml-0 mr-0">
                    <div class="col-md-12 mb-2">
                      <div class="row">
                        <div class="col-12">
                          <label class="float-right" for="pelabuhan_tujuan">Tujuan</label>
                        </div>
                        <div class="col-12">
                          <select name="pelabuhan_tujuan" id="pelabuhan_tujuan" class="form-control custom-select select2" required>
                            <?php
                            $sql = mysqli_query($conn, "SELECT * FROM pelabuhan ORDER BY nm_pelabuhan ASC");
                            for ($i = 1; $Data = mysqli_fetch_array($sql); $i++) { ?>
                              <option value="<?= $Data['id_pelabuhan']; ?>"><?= $Data['nm_pelabuhan']; ?></option>
                            <?php } ?>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </li>
            </ul>
          </div>
          <div class="modal-footer">
            <div class="btn-group btn-block">
              <button type="button" name="cari-rute" class="btn btn-block btn-outline-primary">Simpan</button>
            </div>
          </div>
        </div>
        <!-- /.modal-content -->
      </form>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <div class="modal fade" id="cancel-tiket">
      <form method="POST" class="modal-dialog" enctype="multipart/form-data" autocomplete="off">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Batalkan Tiket</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <h4 class="modal-title">Anda yakin Ingin Membatalkan Tiket ini?</h4>
            <input type="hidden" name="id_tiket" id="did_tiket">
          </div>
          <div class="modal-footer">
            <div class="btn-group btn-block">
              <button class="btn btn-outline-success" data-dismiss="modal">Batal</button>
              <button type="submit" name="batal-tiket" class="btn btn-outline-danger">Hapus</button>
            </div>
          </div>
        </div>
        <!-- /.modal-content -->
      </form>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <div class="modal fade" id="detail-pembayaran">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="overlay d-flex justify-content-center align-items-center invisible">
            <i class="fas fa-2x fa-sync fa-spin"></i>
          </div>
          <div class="modal-header">
            <h4 class="modal-title">Detail Pembayaran</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <p>untuk pembayaran bisa dilakukan ke No. Rekening xxxx Bank xxxxx. <br>
              setelah melakukan pembayaran, silahkan chat admin melalui WhatsApp dengan nomor +62812-0000-0000
            </p>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    <?php include $backurl . 'user/config/modal.php'; ?>
    <?php include $backurl . 'config/site/footer.php'; ?>
  </div>
  <!-- ./wrapper -->

  <?php include $backurl . 'config/site/script.php'; ?>
  <!-- page script -->
  <script>
    $(function() {
      var host = "<?= $df['home'] ?>";
      var table_pesanan = $('#table_pesanan').DataTable({
        'paging': true,
        'lengthChange': false,
        "pageLength": 10,
        'info': true,
        "order": [
          [0, "desc"]
        ],
        'searching': true,
        'ordering': true,
        "language": {
          "paginate": {
            "previous": "<",
            "next": ">"
          }
        },

        "processing": true,
        "serverSide": true,
        "ajax": {
          "url": host + "../config/get-tables.php",
          "data": {
            "set_tables": "SELECT tiket.id_tiket, tiket.tgl_keberangkatan, tiket.nm_penumpang, kapal.nm_kapal, kursi.no_kursi, tiket.stt_tiket FROM tiket JOIN rute JOIN jadwal JOIN kursi JOIN kapal WHERE tiket.id_rute=rute.id_rute AND rute.id_jadwal=jadwal.id_jadwal AND tiket.id_kursi=kursi.id_kursi AND jadwal.id_kapal=kapal.id_kapal AND tiket.id_user='<?= $_SESSION['id_user'] ?>'",
            'query': true
          },
          "type": "POST"
        },
        "columns": [{
          'className': "align-middle text-center",
          "data": "tgl_keberangkatan",
          "width": "50px",
        }, {
          'className': "align-middle",
          "data": "nm_penumpang",
        }, {
          'className': "align-middle text-center",
          "data": "nm_kapal",
          "width": "10px",
        }, {
          'className': "align-middle text-center",
          "data": "no_kursi",
          "width": "10px",
        }, {
          'className': "align-middle text-center",
          "data": "id_tiket",
          "width": "10px",
          "render": function(data, type, row, meta) {
            return '<div class="btn-group"><button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#detail-tiket" id_tiket="' + data + '">Detail</button></div>';
          }
        }],
      });
      $('#table_pesanan_filter').hide();
      var table_jadwal = $('#table_jadwal').DataTable({
        'paging': false,
        'info': false,
        'searching': false,
        'ordering': true,
        "autoWidth": false,
        "columns": [{
          'className': "align-middle",
        }, {
          'className': "align-middle text-center",
          "width": "10px",
        }, {
          'className': "align-middle text-center",
          "width": "10px",
        }, {
          'className': "align-middle text-center",
          "width": "100px",
        }, {
          'className': "align-middle text-center",
          "width": "10px",
        }],
        "order": [
          [0, "desc"]
        ],
      });

      $('#pemesanan-tiket button[name="cari-rute"]').click(function() {
        $('#data-jadwal').modal('show');
        $('#data-jadwal .overlay').removeClass('invisible');
        let pelabuhan_asal = $('#pemesanan-tiket #pelabuhan_asal').val();
        let pelabuhan_tujuan = $('#pemesanan-tiket #pelabuhan_tujuan').val();
        $.ajax({
          type: "POST",
          url: host + "get-kapal.php",
          dataType: "JSON",
          data: {
            'set': 'get_jadwal',
            'pelabuhan_asal': pelabuhan_asal,
            'pelabuhan_tujuan': pelabuhan_tujuan,
          },
          success: function(data) {
            console.log(data);
            if (data['status'] == 'done') {
              fid_barang = data['id_barang'];
              $('#data-jadwal #pelabuhan_asal').html(data['pelabuhan_asal']);
              $('#data-jadwal #pelabuhan_tujuan').html(data['pelabuhan_tujuan']);

              table_jadwal.clear().draw();
              $(data['data_rute']).each(function(index, hasil) {
                let button_set = '<button type="button" class="btn btn-sm btn-info" id="pesan_tiket" id_rute="' + hasil['id_rute'] + '" id_tiket="">Pilih</button>';
                table_jadwal.row.add([hasil['nm_kapal'], hasil['hari_jadwal'], hasil['jam_jadwal'], hasil['harga_tiket'], button_set]).draw();
              });
              $('#data-jadwal .overlay').addClass('invisible')
            }
          },
          error: function(request, status, error) {
            console.log(request.responseText);
          }
        });
      });

      table_jadwal.on('click', 'button[id="pesan_tiket"]', function() {
        $('#pesan-tiket').modal('show');
        $('#pesan-tiket .overlay').removeClass('invisible');
        $.ajax({
          type: "POST",
          url: host + "get-kapal.php",
          dataType: "JSON",
          data: {
            'set': 'get_kursi',
            'id_rute': $(this).attr('id_rute'),
            'id_tiket': $(this).attr('id_tiket'),
          },
          success: function(data) {
            console.log(data);
            if (data['status'] == 'done') {
              $('#pesan-tiket #nm_penumpang').val(data['nm_penumpang']);
              $('#pesan-tiket #umur_penumpang').val(data['umur_penumpang']);
              $('#pesan-tiket #jk_penumpang').val(data['jk_penumpang']);

              $('#pesan-tiket #nm_kapal').val(data['nm_kapal']);
              $('#pesan-tiket input[name="id_rute"]').val(data['id_rute']);
              $('#pesan-tiket input[name="id_tiket"]').val(data['id_tiket']);
              $('#pesan-tiket #harga_tiket').val(data['harga_tiket']).trigger('input');
              $('#pesan-tiket #id_kursi').empty().select2({
                data: data['id_kursi'],
                width: "100%",
                theme: 'bootstrap4',
              });

              $('#pesan-tiket .overlay').addClass('invisible')
            }
          },
          error: function(request, status, error) {
            console.log(request.responseText);
          }
        });
      });

      table_pesanan.on('click', 'button[data-target="#detail-tiket"]', function() {
        $('#data-pesanan').modal('show');
        $('#data-pesanan .overlay').removeClass('invisible');
        let id_tiket = $(this).attr('id_tiket');
        $.ajax({
          type: "POST",
          url: host + "get-kapal.php",
          dataType: "JSON",
          data: {
            'set': 'get_data_tiket',
            'id_tiket': id_tiket,
          },
          success: function(data) {
            console.log(data);
            if (data['status'] == 'done') {
              $('#data-pesanan #nm_penumpang').html(data['nm_penumpang']);
              $('#data-pesanan #umur_penumpang').html(data['umur_penumpang']);
              $('#data-pesanan #jk_penumpang').html(data['jk_penumpang']);
              $('#data-pesanan #nm_kapal').html(data['nm_kapal']);
              $('#data-pesanan #no_kursi').html(data['no_kursi']);
              $('#data-pesanan #tujuan').html(data['tujuan']);
              $('#data-pesanan #harga_tiket').html(data['harga_tiket']);
              $('#data-pesanan #tgl_keberangkatan').html(data['tgl_keberangkatan']);
              $('#data-pesanan #stt_tiket').html(data['stt_tiket']);

              if (data['stt_tiket'] == 'booking') {
                $('#data-pesanan #button_pesanan').removeClass('d-none');
              } else {
                $('#data-pesanan #button_pesanan').addClass('d-none');
              }


              $('#data-pesanan #cancel_tiket').unbind().click(function() {
                $('#cancel-tiket #did_tiket').val(id_tiket);
              });

              $('#data-pesanan #reschedule_tiket').unbind().click(function() {
                $('#reschedule-tiket input[name="id_tiket"]').val(id_tiket);
              });

              $('#data-pesanan #cetak_tiket').attr('href', host + 'export/tiket.php?id=' + id_tiket);
              $('#data-pesanan .overlay').addClass('invisible')
            }
          },
          error: function(request, status, error) {
            console.log(request.responseText);
          }
        });
      });

      $('#reschedule-tiket button[name="cari-rute"]').click(function() {
        $('#data-jadwal').modal('show');
        $('#data-jadwal .overlay').removeClass('invisible');
        let pelabuhan_asal = $('#reschedule-tiket #pelabuhan_asal').val();
        let pelabuhan_tujuan = $('#reschedule-tiket #pelabuhan_tujuan').val();
        let id_tiket = $('#reschedule-tiket input[name="id_tiket"]').val();
        $.ajax({
          type: "POST",
          url: host + "get-kapal.php",
          dataType: "JSON",
          data: {
            'set': 'get_jadwal',
            'pelabuhan_asal': pelabuhan_asal,
            'pelabuhan_tujuan': pelabuhan_tujuan,
          },
          success: function(data) {
            console.log(data);
            if (data['status'] == 'done') {
              fid_barang = data['id_barang'];
              $('#data-jadwal #pelabuhan_asal').html(data['pelabuhan_asal']);
              $('#data-jadwal #pelabuhan_tujuan').html(data['pelabuhan_tujuan']);

              table_jadwal.clear().draw();
              $(data['data_rute']).each(function(index, hasil) {
                let button_set = '<button type="button" class="btn btn-sm btn-info" id="pesan_tiket" id_rute="' + hasil['id_rute'] + '" id_tiket="' + id_tiket + '">Pilih</button>';
                table_jadwal.row.add([hasil['nm_kapal'], hasil['hari_jadwal'], hasil['jam_jadwal'], hasil['harga_tiket'], button_set]).draw();
              });
              $('#data-jadwal .overlay').addClass('invisible')
            }
          },
          error: function(request, status, error) {
            console.log(request.responseText);
          }
        });
      });




    });
  </script>
</body>

</html>