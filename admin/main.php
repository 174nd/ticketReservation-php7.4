<?php
$backurl = '../';
require_once($backurl . 'admin/config/settings.php');
$pset = array(
  'title' => 'Dashboard',
  'content' => 'Dashboard',
  'breadcrumb' => array(
    'Dashboard' => 'active',
  ),
);

$setSidebar = activedSidebar($setSidebar, 'Dashboard');




if (isset($_POST['u-password'])) {
  $pass = md5($_POST['pass_lama']);
  if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM login WHERE username LIKE '$_SESSION[username]' AND password LIKE '$pass'")) > 0) {
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
      $_SESSION['notifikasi'] = 'NOT09';
    }
  } else {
    $_SESSION['notifikasi'] = 'NOT08';
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


if (isset($_POST["konfirmasi-tiket"])) {
  $_POST = setData($_POST);
  $upFile = uploadFile($_FILES['bukti_pembayaran'], ['jpg', 'jpeg', 'png'], $backurl . "uploads/pembayaran", $_POST['id_tiket'] . ' - ');
  if ($upFile == 'Wrong Extension') {
    $_SESSION['notifikasi'] = 'NOT07';
  } else {
    $set = [
      'stt_tiket' => 'payment',
      'bukti_pembayaran' => $upFile,
      'bp_admin' => $_SESSION['id_user'],
    ];
    $query = setUpdate($set, 'tiket', ['id_tiket' => $_POST['id_tiket']]);
    if (!$query) {
      $_SESSION['notifikasi'] = 'NOT02';
    } else {
      $_SESSION['notifikasi'] = 'NOT03';
    }
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

<body class="sidebar-mini layout-fixed control-sidebar-slide-open text-sm">
  <div class="wrapper">
    <?php include $backurl . 'admin/config/header-sidebar.php'; ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <?php include $backurl . 'admin/config/content-header.php'; ?>

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <!-- Main row -->
          <div class="row">

            <section class="col-lg-8 connectedSortable">
              <div class="row">
                <div class="col-md-6">

                  <div class="small-box bg-primary" data-toggle="modal" data-target="#list-kapal">
                    <div class="inner">
                      <h3><?php $data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(id_kapal) AS id FROM kapal LIMIT 1"));
                          echo $data['id'] ?><sup style="font-size: 20px">Kapal</sup></h3>
                      <p>Total Kapal</p>
                    </div>
                    <div class="icon">
                      <i class="fas fa-ship"></i>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="small-box bg-danger" data-toggle="modal" data-target="#detail-orang">
                    <div class="inner">
                      <h3><?php $data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(id_tiket) AS id FROM tiket WHERE stt_tiket='success' LIMIT 1"));
                          echo $data['id'] ?><sup style="font-size: 20px">Orang</sup></h3>
                      <p>Total Penumpang</p>
                    </div>
                    <div class="icon">
                      <i class="fas fa-user-tie"></i>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card card-primary card-outline">
                <div class="card-header">
                  <h3 class="card-title">Jadwal Kapal</h3>
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-minus"></i>
                    </button>
                  </div>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table id="table_jadwal" class="table table-bordered table-hover" style="min-width: 400px;">
                      <thead>
                        <tr>
                          <th>Jadwal</th>
                          <th>Nama Kapal</th>
                          <th>Nama Perusahaan</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

              <div class="card card-outline card-primary">
                <div class="card-header">
                  <h5 class="text-center w-100 mb-0">Statistik Penumpang 6 Bulan Terakhir</h5>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <div id="statistik-tiket" style="min-width: 400px;height:300px;"></div>
                  </div>
                </div>
              </div>

              <div class="card card-primary card-outline collapsed-card">
                <div class="card-header">
                  <h3 class="card-title">Pemesanan Tiket</h3>
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-plus"></i>
                    </button>
                  </div>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table id="table_pesanan" class="table table-bordered table-hover" style="width:100%; min-width: 400px;">
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
                  <button class="btn btn-block btn-outline-light" data-toggle="modal" data-target="#ruang-tunggu">Ruang Tunggu</button>
                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.Ruang Tunggu -->

              <div class="card">
                <div class="card-body bg-primary">
                  <button class="btn btn-block btn-outline-light" data-toggle="modal" data-target="#masuk-kapal">Masuk Kapal</button>
                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.Masuk Kapal -->

              <div class="card card-primary card-outline collapsed-card" id="cari_penumpang">
                <div class="card-header">
                  <h3 class="card-title">Cari Penumpang</h3>
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
                        <input type="text" name="ctgl_pemesanan" id="ctgl_pemesanan" class="form-control mydatetoppicker" placeholder="Tanggal Pemesanan">
                        <div class="input-group-append">
                          <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                        </div>
                      </div>
                    </li>
                    <li class="list-group-item">
                      <button type="button" name="cari-penumpang" class="btn btn-block btn-outline-primary">Cari</button>
                    </li>
                  </ul>
                </div>
              </div>
            </section>
          </div>
          <!-- /.row (main row) -->


        </div>
        <!-- /.container-fluid -->
      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->



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
                  <button class="btn btn-success" data-toggle="modal" data-target="#konfirmasi-tiket" id="konfirmasi_tiket">Konfirmasi</button>
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

    <form class="modal fade" id="konfirmasi-tiket" method="POST" enctype="multipart/form-data" autocomplete="off">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Konfirmasi Tiket</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="id_tiket" id="did_tiket">
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
                <div class="custom-file">
                  <input type="file" name="bukti_pembayaran" class="custom-file-input" id="bukti_pembayaran" required>
                  <label class="custom-file-label" for="bukti_pembayaran">Bukti Pembayaran</label>
                </div>
              </li>
            </ul>
          </div>
          <div class="modal-footer">
            <div class="btn-group btn-block">
              <button class="btn btn-danger" data-dismiss="modal">Batal</button>
              <button type="submit" name="konfirmasi-tiket" class="btn btn-success">Simpan</button>
            </div>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </form>
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

    <div class="modal fade" id="scan-barcode">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="overlay d-flex justify-content-center align-items-center invisible">
            <i class="fas fa-2x fa-sync fa-spin"></i>
          </div>
          <div class="modal-header">
            <h4 class="modal-title">Scan Barcode</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <ul class="list-group list-group-unbordered">
              <li class="list-group-item pt-0" style="border-top: 0;">
                <div class="input-group">
                  <div class="row w-100 ml-0 mr-0">
                    <div class="col-md-12 mb-2 d-flex justify-content-center">
                      <video id="preview" style="object-fit: cover; width: 300px; height: 300px;" paus></video>
                    </div>
                  </div>
                </div>
              </li>
              <li class="list-group-item">
                <div class="input-group">
                  <div class="row w-100 ml-0 mr-0">
                    <div class="col-md-12 mb-2">
                      <label class="float-right" for="pilih_kamera">Kamera</label>
                      <select name="pilih_kamera" id="pilih_kamera" class="form-control custom-select">
                      </select>
                    </div>
                  </div>
                </div>
              </li>
              <li class="list-group-item pb-0" style="border-bottom: 0;" id="div_kas_transaksi">
                <div class="input-group">
                  <div class="row w-100 ml-0 mr-0">
                    <div class="col-md-12">
                      <input type="text" name="kode_barcode" id="kode_barcode" class="form-control" placeholder="Kode Barcode">
                    </div>
                  </div>
                </div>
              </li>
            </ul>
          </div>
          <div class="modal-footer">
            <div class="btn-group btn-block">
              <button type="button" name="cari-barcode" class="btn btn-outline-primary">Simpan</button>
            </div>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <div class="modal fade" id="cari-penumpang">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="overlay d-flex justify-content-center align-items-center invisible">
            <i class="fas fa-2x fa-sync fa-spin"></i>
          </div>
          <div class="modal-header">
            <h4 class="modal-title">Detail Pesanan</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-4 mb-2">
                <select id="column_penumpang" class="form-control custom-select">
                  <option value="0">Nama Penumpang</option>
                  <option value="1">Kapal</option>
                  <option value="2">Kursi</option>
                </select>
              </div>
              <div class="col-md-8 mb-2">
                <input type="text" class="form-control" placeholder="Cari Data" id="field_penumpang">
              </div>
            </div>
            <div class="table-responsive">
              <table id="table_penumpang" class="table w-100 table-bordered table-hover" style="min-width: 400px;">
                <thead>
                  <tr>
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
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <div class="modal fade" id="detail-pesanan">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="overlay d-flex justify-content-center align-items-center invisible">
            <i class="fas fa-2x fa-sync fa-spin"></i>
          </div>
          <div class="modal-header">
            <h4 class="modal-title">Detail Pesanan</h4>
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
            </ul>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <div class="modal fade" id="list-kapal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="overlay d-flex justify-content-center align-items-center invisible">
            <i class="fas fa-2x fa-sync fa-spin"></i>
          </div>
          <div class="modal-header">
            <h4 class="modal-title">Data Kapal</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <ul class="list-group list-group-unbordered">
              <li class="list-group-item">
                <div class="row">
                  <div class="col-md-4 mb-2">
                    <select id="column_kapal" class="form-control custom-select">
                      <option value="0">Nama Kapal</option>
                      <option value="1">Perusahaan</option>
                    </select>
                  </div>
                  <div class="col-md-8 mb-2">
                    <input type="text" class="form-control" placeholder="Cari Data" id="field_kapal">
                  </div>
                </div>
              </li>
              <li class="list-group-item">
                <div class="table-responsive">
                  <table id="table_kapal" class="table table-bordered table-hover" style="width:100%; min-width: 400px;">
                    <thead>
                      <tr>
                        <th>Nama Kapal</th>
                        <th>Perusahaan</th>
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

    <div class="modal fade" id="data-kapal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="overlay d-flex justify-content-center align-items-center invisible">
            <i class="fas fa-2x fa-sync fa-spin"></i>
          </div>
          <div class="modal-header">
            <h4 class="modal-title">Data Kapal</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <ul class="list-group list-group-unbordered">
              <li class="list-group-item">
                <b>Nama Kapal</b><span class="float-right" id="nm_kapal">x</span>
              </li>
              <li class="list-group-item">
                <b>Perusahaan</b><span class="float-right" id="nm_perusahaan">x</span>
              </li>
              <li class="list-group-item sembunyikan">
                <b>Jadwal Keberangkatan</b><span class="float-right" id="jadwal_keberangkatan">x</span>
              </li>
            </ul>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <div class="modal fade" id="detail-orang">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="overlay d-flex justify-content-center align-items-center invisible">
            <i class="fas fa-2x fa-sync fa-spin"></i>
          </div>
          <div class="modal-header">
            <h4 class="modal-title">Detail Orang</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <ul class="list-group list-group-unbordered">
              <li class="list-group-item">
                <div class="row">
                  <div class="col-md-4 mb-2">
                    <select id="column_detail_orang" class="form-control custom-select">
                      <option value="1">Nama Penumpang</option>
                      <option value="0">Tanggal</option>
                      <option value="2">Kapal</option>
                      <option value="3">Kursi</option>
                    </select>
                  </div>
                  <div class="col-md-8 mb-2">
                    <input type="text" class="form-control" placeholder="Cari Data" id="field_detail_orang">
                  </div>
                </div>
              </li>
              <li class="list-group-item">
                <div class="table-responsive">
                  <table id="table_detail_orang" class="table table-bordered table-hover" style="width:100%; min-width: 400px;">
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
              </li>
            </ul>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    <?php include $backurl . 'admin/config/modal.php'; ?>
    <?php include $backurl . 'config/site/footer.php'; ?>
  </div>
  <!-- ./wrapper -->

  <?php include $backurl . 'config/site/script.php'; ?>
  <!-- instascan -->
  <script src="<?= $df['host'] . 'plugins/instascan/instascan.min.js' ?>"></script>
  <!-- page script -->
  <script>
    $(function() {
      var scanner, host = "<?= $df['home'] ?>";
      var data_tiket = {
        data: [
          <?php
          $isian_grafik = [];
          $sql = mysqli_query($conn, "SELECT MONTH(DATE_SUB(CURDATE(), INTERVAL 6 MONTH)) AS bulan, COALESCE((SELECT COUNT(id_tiket) FROM tiket WHERE stt_tiket='success' AND MONTH(tgl_keberangkatan)=MONTH(DATE_SUB(CURDATE(), INTERVAL 6 MONTH)) AND YEAR(tgl_keberangkatan)=YEAR(DATE_SUB(CURDATE(), INTERVAL 6 MONTH))) ,0) AS total UNION ALL SELECT MONTH(DATE_SUB(CURDATE(), INTERVAL 5 MONTH)) AS bulan, COALESCE((SELECT COUNT(id_tiket) FROM tiket WHERE stt_tiket='success' AND MONTH(tgl_keberangkatan)=MONTH(DATE_SUB(CURDATE(), INTERVAL 5 MONTH)) AND YEAR(tgl_keberangkatan)=YEAR(DATE_SUB(CURDATE(), INTERVAL 5 MONTH))) ,0) AS total UNION ALL SELECT MONTH(DATE_SUB(CURDATE(), INTERVAL 4 MONTH)) AS bulan, COALESCE((SELECT COUNT(id_tiket) FROM tiket WHERE stt_tiket='success' AND MONTH(tgl_keberangkatan)=MONTH(DATE_SUB(CURDATE(), INTERVAL 4 MONTH)) AND YEAR(tgl_keberangkatan)=YEAR(DATE_SUB(CURDATE(), INTERVAL 4 MONTH))) ,0) AS total UNION ALL SELECT MONTH(DATE_SUB(CURDATE(), INTERVAL 3 MONTH)) AS bulan, COALESCE((SELECT COUNT(id_tiket) FROM tiket WHERE stt_tiket='success' AND MONTH(tgl_keberangkatan)=MONTH(DATE_SUB(CURDATE(), INTERVAL 3 MONTH)) AND YEAR(tgl_keberangkatan)=YEAR(DATE_SUB(CURDATE(), INTERVAL 3 MONTH))) ,0) AS total UNION ALL SELECT MONTH(DATE_SUB(CURDATE(), INTERVAL 2 MONTH)) AS bulan, COALESCE((SELECT COUNT(id_tiket) FROM tiket WHERE stt_tiket='success' AND MONTH(tgl_keberangkatan)=MONTH(DATE_SUB(CURDATE(), INTERVAL 2 MONTH)) AND YEAR(tgl_keberangkatan)=YEAR(DATE_SUB(CURDATE(), INTERVAL 2 MONTH))) ,0) AS total UNION ALL SELECT MONTH(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)) AS bulan, COALESCE((SELECT COUNT(id_tiket) FROM tiket WHERE stt_tiket='success' AND MONTH(tgl_keberangkatan)=MONTH(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)) AND YEAR(tgl_keberangkatan)=YEAR(DATE_SUB(CURDATE(), INTERVAL 1 MONTH))) ,0) AS total ");
          for ($i = 1; $Data = mysqli_fetch_array($sql); $i++) {
            echo '[' . $i . ', ' . $Data['total'] . '],';
            $isian_grafik[] = $Data['bulan'];
          } ?>
        ],
        color: '<?= '#' . substr(md5(mt_rand()), 0, 6); ?>'
      }
      var barOpt_tiket = {
        grid: {
          hoverable: true,
          borderColor: '#f3f3f3',
          borderWidth: 1,
          tickColor: '#f3f3f3'
        },
        series: {
          shadowSize: 0,
          lines: {
            show: true
          },
          points: {
            show: true
          }
        },
        lines: {
          fill: false,
          color: '#3c8dbc'
        },
        yaxis: {
          show: true
        },
        xaxis: {
          show: true,
          ticks: [
            <?php
            for ($i = 0; $i < count($isian_grafik); $i++) {
              echo "[" . ($i + 1) . ", '" . bulan_indo($isian_grafik[$i]) . "'],";
            } ?>
          ]
        }
      };
      new ResizeSensor($("#statistik-tiket").parent().parent(), function() {
        $.plot($("#statistik-tiket"), [data_tiket], barOpt_tiket);
        $('<div class="tooltip-inner" id="line-chart-tooltip"></div>').css({
          position: 'absolute',
          display: 'none',
          opacity: 0.8
        }).appendTo('body')
        $('#statistik-tiket').bind('plothover', function(event, pos, item) {
          if (item) {
            var x = item.datapoint[0].toFixed(2),
              y = item.datapoint[1].toFixed(2)
            bulan = {
              <?php
              for ($i = 0; $i < count($isian_grafik); $i++) {
                echo ($i + 1)  ?>: <?php echo "'" . bulan_indo($isian_grafik[$i]) . "',";
                                  } ?>
            };
            $('#line-chart-tooltip').html('Penumpang pada Bulan ' + bulan[Math.round(x)] + ' = ' + Math.round(y))
              .css({
                top: item.pageY + 5,
                left: item.pageX + 5
              })
              .fadeIn(200)
          } else {
            $('#line-chart-tooltip').hide()
          }

        })
      });




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
            "set_tables": "SELECT tiket.id_tiket, tiket.tgl_keberangkatan, tiket.nm_penumpang, kapal.nm_kapal, kursi.no_kursi, tiket.stt_tiket FROM tiket JOIN rute JOIN jadwal JOIN kursi JOIN kapal WHERE tiket.id_rute=rute.id_rute AND rute.id_jadwal=jadwal.id_jadwal AND tiket.id_kursi=kursi.id_kursi AND jadwal.id_kapal=kapal.id_kapal AND tiket.stt_tiket='booking'",
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
            "set_tables": "SELECT CONCAT(jadwal.hari_jadwal,', ', SUBSTRING(jadwal.jam_jadwal, 1, 5),' WIB') AS jadwal, kapal.nm_kapal, perusahaan.nm_perusahaan FROM jadwal JOIN kapal JOIN perusahaan WHERE jadwal.id_kapal=kapal.id_kapal AND kapal.id_perusahaan=perusahaan.id_perusahaan AND jadwal.stt_jadwal='Y'",
            'query': true
          },
          "type": "POST"
        },
        "columns": [{
          'className': "align-middle text-center",
          "data": "jadwal",
          "width": "100px",
        }, {
          'className': "align-middle",
          "data": "nm_kapal",
        }, {
          'className': "align-middle text-center",
          "data": "nm_perusahaan",
          "width": "150px",
        }],
      });
      $('#table_jadwal_filter').hide();



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


              $('#data-pesanan #cancel_tiket').unbind().click(function() {
                $('#cancel-tiket #did_tiket').val(id_tiket);
              });

              $('#data-pesanan #konfirmasi_tiket').unbind().click(function() {
                $('#konfirmasi-tiket #did_tiket').val(id_tiket);
                $('#konfirmasi-tiket #nm_penumpang').html(data['nm_penumpang']);
                $('#konfirmasi-tiket #umur_penumpang').html(data['umur_penumpang']);
                $('#konfirmasi-tiket #jk_penumpang').html(data['jk_penumpang']);
                $('#konfirmasi-tiket #nm_kapal').html(data['nm_kapal']);
                $('#konfirmasi-tiket #no_kursi').html(data['no_kursi']);
                $('#konfirmasi-tiket #tujuan').html(data['tujuan']);
                $('#konfirmasi-tiket #harga_tiket').html(data['harga_tiket']);
                $('#konfirmasi-tiket #tgl_keberangkatan').html(data['tgl_keberangkatan']);
              });

              $('#data-pesanan #button_pesanan').removeClass('d-none');
              $('#data-pesanan #cetak_tiket').attr('href', host + 'export/tiket.php?id=' + id_tiket);
              $('#data-pesanan .overlay').addClass('invisible')
            }
          },
          error: function(request, status, error) {
            console.log(request.responseText);
          }
        });
      });

      $('#scan-barcode').on('shown.bs.modal', function() {
        $('#scan-barcode .overlay').removeClass('invisible');
        scanner = new Instascan.Scanner({
          video: document.getElementById('preview'),
          mirror: false,
        }).addListener('scan', function(content) {
          $('#scan-barcode #kode_barcode').val(content);
          $('#scan-barcode button[name="cari-barcode"]').click();
        });

        navigator.mediaDevices.getUserMedia({
          video: true,
          audio: false
        }).then(function success(stream) {
          Instascan.Camera.getCameras().then(function(cameras) {
            if (cameras.length > 0) {
              var pilih_kamera = document.getElementById("pilih_kamera");
              Instascan.Camera.getCameras().then(function(cameras) {
                if (pilih_kamera.options.length == 0) {
                  cameras.forEach(function(value, index) {
                    var option = document.createElement("option");
                    option.text = value['name'];
                    option.value = index;
                    pilih_kamera.add(option, pilih_kamera[index]);
                  });
                }

                scanner.start(cameras[pilih_kamera.value]);
                pilih_kamera.removeEventListener("change", function() {});
                pilih_kamera.addEventListener("change", function() {
                  scanner.stop();
                  scanner.start(cameras[this.value]);
                });
              });

              $('#scan-barcode .overlay').addClass('invisible');
            } else {
              console.error('No cameras found.');
              alert('No cameras found.');
            }
          }).catch(function(e) {
            alert(e);
          })
        }).catch(function(err) {
          alert(err.name);
        });

      }).on("hidden.bs.modal", function() {
        scanner.stop();
      });

      $('button[data-target="#ruang-tunggu"]').click(function() {
        $('#scan-barcode h4').html('Ruang Tunggu');
        $('#scan-barcode').modal('show');

        $('#scan-barcode button[name="cari-barcode"]').unbind().click(function() {
          $.ajax({
            type: "POST",
            url: host + "get-kapal.php",
            dataType: "JSON",
            data: {
              'set': 'set_tiket',
              'status': 'ruang-tunggu',
              'kode_barcode': $('#scan-barcode #kode_barcode').val(),
            },
            success: function(data) {
              console.log(data);
              if (data['status'] == 'done') {
                toastr.success('Penumpang telah masuk ke ruang tunggu');
                $('#detail-pesanan').modal('show');
                $('#detail-pesanan .overlay').removeClass('invisible');
                $('#detail-pesanan #nm_penumpang').html(data['nm_penumpang']);
                $('#detail-pesanan #umur_penumpang').html(data['umur_penumpang']);
                $('#detail-pesanan #jk_penumpang').html(data['jk_penumpang']);
                $('#detail-pesanan #nm_kapal').html(data['nm_kapal']);
                $('#detail-pesanan #no_kursi').html(data['no_kursi']);
                $('#detail-pesanan #tujuan').html(data['tujuan']);
                $('#detail-pesanan #harga_tiket').html(data['harga_tiket']);
                $('#detail-pesanan #tgl_keberangkatan').html(data['tgl_keberangkatan']);
                $('#detail-pesanan #stt_tiket').html(data['stt_tiket']);
                $('#detail-pesanan .overlay').addClass('invisible')
              } else {
                toastr.warning('Tiket Tidak Sesuai!');
              }
            },
            error: function(request, status, error) {
              console.log(request.responseText);
            }
          });
        });
      });

      $('button[data-target="#masuk-kapal"]').click(function() {
        $('#scan-barcode h4').html('Masuk Kapal');
        $('#scan-barcode').modal('show');

        $('#scan-barcode button[name="cari-barcode"]').unbind().click(function() {
          $.ajax({
            type: "POST",
            url: host + "get-kapal.php",
            dataType: "JSON",
            data: {
              'set': 'set_tiket',
              'status': 'masuk-kapal',
              'kode_barcode': $('#scan-barcode #kode_barcode').val(),
            },
            success: function(data) {
              console.log(data);
              if (data['status'] == 'done') {
                toastr.success('Penumpang telah masuk ke Kapal!');
                $('#detail-pesanan').modal('show');
                $('#detail-pesanan .overlay').removeClass('invisible');
                $('#detail-pesanan #nm_penumpang').html(data['nm_penumpang']);
                $('#detail-pesanan #umur_penumpang').html(data['umur_penumpang']);
                $('#detail-pesanan #jk_penumpang').html(data['jk_penumpang']);
                $('#detail-pesanan #nm_kapal').html(data['nm_kapal']);
                $('#detail-pesanan #no_kursi').html(data['no_kursi']);
                $('#detail-pesanan #tujuan').html(data['tujuan']);
                $('#detail-pesanan #harga_tiket').html(data['harga_tiket']);
                $('#detail-pesanan #tgl_keberangkatan').html(data['tgl_keberangkatan']);
                $('#detail-pesanan #stt_tiket').html(data['stt_tiket']);
                $('#detail-pesanan .overlay').addClass('invisible');
              } else {
                toastr.warning('Tiket Tidak Sesuai!');
              }
            },
            error: function(request, status, error) {
              console.log(request.responseText);
            }
          });
        });
      });


      $('#cari_penumpang button[name="cari-penumpang"]').click(function() {
        $('#cari-penumpang').modal('show');
        $.ajax({
          type: "POST",
          url: host + "get-kapal.php",
          dataType: "JSON",
          data: {
            'set': 'get_penumpang',
            'ctgl_pemesanan': $('#cari_penumpang #ctgl_pemesanan').val(),
          },
          success: function(data) {
            console.log(data);
            if (data['status'] == 'done') {
              table_penumpang.clear().draw();
              $(data['data_tiket']).each(function(index, hasil) {
                let button_set = '<div class="btn-group"><button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#detail-tiket" id_tiket="' + hasil['id_tiket'] + '">Detail</button></div>';
                table_penumpang.row.add([hasil['nm_penumpang'], hasil['nm_kapal'], hasil['no_kursi'], button_set]).draw();
              });
              $('#detail-pesanan .overlay').addClass('invisible');
            } else {
              toastr.warning('Tiket Tidak Sesuai!');
            }
          },
          error: function(request, status, error) {
            console.log(request.responseText);
          }
        });
      });

      var table_penumpang = $('#table_penumpang').DataTable({
        'paging': false,
        'lengthChange': false,
        "pageLength": 10,
        'info': false,
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
          "width": "10px",
        }],
      });
      $('#table_penumpang_filter').hide();
      $('#field_penumpang').keyup(function() {
        table_penumpang.columns($('#column_penumpang').val()).search(this.value).draw();
      });
      table_penumpang.on('click', 'button[data-target="#detail-tiket"]', function() {
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
              $('#data-pesanan #button_pesanan').addClass('d-none');



              $('#data-pesanan #cetak_tiket').attr('href', host + 'export/tiket.php?id=' + id_tiket);
              $('#data-pesanan .overlay').addClass('invisible')
            }
          },
          error: function(request, status, error) {
            console.log(request.responseText);
          }
        });
      });


      var table_kapal = $('#table_kapal').DataTable({
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
            "set_tables": "SELECT kapal.id_kapal, kapal.nm_kapal, perusahaan.nm_perusahaan FROM kapal JOIN perusahaan WHERE kapal.id_perusahaan=perusahaan.id_perusahaan",
            "query": true
          },
          "type": "POST"
        },
        "columns": [{
          'className': "align-middle",
          "data": "nm_kapal",
        }, {
          'className': "align-middle text-center",
          "data": "nm_perusahaan",
          "width": "100px",
        }, {
          'className': "align-middle text-center",
          "data": "id_kapal",
          "width": "10px",
          "render": function(data, type, row, meta) {
            return '<div class="btn-group"><button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#data-kapal" id_kapal="' + data + '"><i class="fa fa-eye"></i></button></div>';
          }
        }],
      });
      $('#table_kapal_filter').hide();
      $('#field_kapal').keyup(function() {
        table_kapal.columns($('#column_kapal').val()).search(this.value).draw();
      });


      table_kapal.on('click', 'button[data-target="#data-kapal"]', function() {
        $('#data-kapal .overlay').removeClass('invisible');
        let id_kapal = $(this).attr('id_kapal');
        $.ajax({
          type: "POST",
          url: host + "get-kapal.php",
          dataType: "JSON",
          data: {
            'set': 'get_dkapal',
            'id_kapal': id_kapal,
          },
          success: function(data) {
            if (data['status'] == 'done') {
              $('#data-kapal #nm_kapal').html(data['nm_kapal']);
              $('#data-kapal #nm_perusahaan').html(data['nm_perusahaan']);
              $('#data-kapal #jadwal_keberangkatan').html(data['jadwal_aktif']['hari_jadwal'] + ', ' + data['jadwal_aktif']['jam_jadwal']);
              $('#data-kapal .list-group-item #cek_rute').attr('id_jadwal', data['jadwal_aktif']['id_jadwal']);
              if (data['jadwal_aktif'].length != 0) {
                $('#data-kapal .list-group-item.sembunyikan').removeClass('d-none');
              } else {
                $('#data-kapal .list-group-item.sembunyikan').addClass('d-none');
              }

              $('#data-kapal .overlay').addClass('invisible')
            }
          },
          error: function(request, status, error) {
            console.log(request.responseText);
          }
        });
      });



      var table_detail_orang = $('#table_detail_orang').DataTable({
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
            "set_tables": "SELECT tiket.id_tiket, tiket.tgl_keberangkatan, tiket.nm_penumpang, kapal.nm_kapal, kursi.no_kursi, tiket.stt_tiket FROM tiket JOIN rute JOIN jadwal JOIN kursi JOIN kapal WHERE tiket.id_rute=rute.id_rute AND rute.id_jadwal=jadwal.id_jadwal AND tiket.id_kursi=kursi.id_kursi AND jadwal.id_kapal=kapal.id_kapal AND tiket.stt_tiket='success'",
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
      $('#table_detail_orang_filter').hide();
      $('#field_detail_orang').keyup(function() {
        table_detail_orang.columns($('#column_detail_orang').val()).search(this.value).draw();
      });
      table_detail_orang.on('click', 'button[data-target="#detail-tiket"]', function() {
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


              $('#data-pesanan #cancel_tiket').unbind().click(function() {
                $('#cancel-tiket #did_tiket').val(id_tiket);
              });

              $('#data-pesanan #konfirmasi_tiket').unbind().click(function() {
                $('#konfirmasi-tiket #did_tiket').val(id_tiket);
                $('#konfirmasi-tiket #nm_penumpang').html(data['nm_penumpang']);
                $('#konfirmasi-tiket #umur_penumpang').html(data['umur_penumpang']);
                $('#konfirmasi-tiket #jk_penumpang').html(data['jk_penumpang']);
                $('#konfirmasi-tiket #nm_kapal').html(data['nm_kapal']);
                $('#konfirmasi-tiket #no_kursi').html(data['no_kursi']);
                $('#konfirmasi-tiket #tujuan').html(data['tujuan']);
                $('#konfirmasi-tiket #harga_tiket').html(data['harga_tiket']);
                $('#konfirmasi-tiket #tgl_keberangkatan').html(data['tgl_keberangkatan']);
              });

              $('#data-pesanan #button_pesanan').addClass('d-none');
              $('#data-pesanan #cetak_tiket').attr('href', host + 'export/tiket.php?id=' + id_tiket);
              $('#data-pesanan .overlay').addClass('invisible')
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