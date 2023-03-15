<?php
$backurl = '../../';
require_once($backurl . 'admin/config/settings.php');
$pset = array(
  'title' => 'Jadwal',
  'content' => 'Jadwal',
  'breadcrumb' => array(
    'Jadwal' => 'active',
  ),
);

$setSidebar = activedSidebar($setSidebar, 'Jadwal');

if (isset($_POST['delete-jadwal'])) {
  $cekdata = mysqli_query($conn, "SELECT * FROM jadwal WHERE id_jadwal = '$_POST[id_jadwal]'");
  if (mysqli_num_rows($cekdata) > 0) {
    $ada = mysqli_fetch_assoc($cekdata);
    if (setDelete('jadwal', ['id_jadwal' => $_POST['id_jadwal']])) {
      $_SESSION['notifikasi'] = 'NOT05';
      header("location:" . $df['home'] . "jadwal/");
      exit();
    } else {
      $_SESSION['notifikasi'] = 'NOT02';
      header("location:" . $df['home'] . "jadwal/");
      exit();
    }
  } else {
    $_SESSION['notifikasi'] = 'NOT02';
    header("location:" . $df['home'] . "jadwal/");
    exit();
  }
} else if (isset($_GET['id_jadwal'])) {
  $cekdata = mysqli_query($conn, "SELECT * FROM jadwal WHERE id_jadwal='$_GET[id_jadwal]'");
  $ada = mysqli_fetch_assoc($cekdata);
  if (mysqli_num_rows($cekdata) > 0) {
    $isiVal = [
      'id_kapal' =>  $ada['id_kapal'],
      'hari_jadwal' =>  $ada['hari_jadwal'],
      'jam_jadwal' =>  substr($ada['jam_jadwal'], 0, -3),
      'stt_jadwal' => ($ada['stt_jadwal'] == 'Y') ? "AND stt_jadwal='Y'" : '',
    ];
    $pset = [
      'title' => 'Update Jadwal',
      'content' => 'Update Jadwal',
      'breadcrumb' => [
        'Dashboard' => $df['home'],
        'Jadwal' => $df['home'] . 'jadwal/',
        'Update' => 'active',
      ],
    ];

    if (isset($_POST["Simpan"])) {
      $_POST = setData($_POST);
      if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM jadwal WHERE hari_jadwal='$_POST[hari_jadwal]' AND jam_jadwal='$_POST[jam_jadwal]:00' $isiVal[stt_jadwal] AND id_kapal!='$_GET[id_kapal]'")) > 0) {
        $_SESSION['duplikasi'] = 'Nama Jadwal';
        $isiVal = [
          'id_kapal' =>  $_POST['id_kapal'],
          'hari_jadwal' =>  $_POST['hari_jadwal'],
          'jam_jadwal' =>  $_POST['jam_jadwal'],
        ];
      } else {
        $set = [
          'id_kapal' =>  $_POST['id_kapal'],
          'hari_jadwal' =>  $_POST['hari_jadwal'],
          'jam_jadwal' =>  $_POST['jam_jadwal'] . ':00',
        ];

        $query = setUpdate($set, 'jadwal', ['id_jadwal' => $_GET['id_jadwal']]);
        if (!$query) {
          $_SESSION['notifikasi'] = 'NOT02';
        } else {
          $_SESSION['notifikasi'] = 'NOT04';
          header("location:" . $df['home'] . "jadwal/");
          exit();
        }
      }
    }
  } else {
    $_SESSION['notifikasi'] = 'NOT02';
    header("location:" . $df['home'] . "jadwal/");
    exit();
  }
} else {
  $isiVal = [
    'id_kapal' => '',
    'hari_jadwal' => '',
    'jam_jadwal' => '',
  ];
  if (isset($_POST["Simpan"])) {
    $_POST = setData($_POST);
    if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM jadwal WHERE hari_jadwal='$_POST[hari_jadwal]' AND jam_jadwal='$_POST[jam_jadwal]:00' AND stt_jadwal='Y'")) > 0) {
      $_SESSION['duplikasi'] = 'Jadwal Keberangkatan';
      $isiVal = [
        'id_kapal' =>  $_POST['id_kapal'],
        'hari_jadwal' =>  $_POST['hari_jadwal'],
        'jam_jadwal' =>  $_POST['jam_jadwal'],
      ];
    } else {
      $set = [
        'id_kapal' =>  $_POST['id_kapal'],
        'hari_jadwal' =>  $_POST['hari_jadwal'],
        'jam_jadwal' =>  $_POST['jam_jadwal'] . ':00',
        'stt_jadwal' => 'Y'
      ];
      $query = setUpdate(['stt_jadwal' => 'N'], 'jadwal', ['id_kapal' => $_POST['id_kapal']]) && setInsert($set, 'jadwal');
      if (!$query) {
        $_SESSION['notifikasi'] = 'NOT02';
      } else {
        $_SESSION['notifikasi'] = 'NOT03';
      }
    }
  }
}


?>
<!DOCTYPE html>
<html>

<head>
  <?php include $backurl . 'config/site/head.php'; ?>
  <style>
    .select2-container:not(.select2.select2-container) {
      z-index: 10000 !important;
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
          <form method="POST" enctype="multipart/form-data" autocomplete="off">
            <div class="row">
              <div class="col-md-6">
                <div class="card card-primary card-outline">
                  <div class="card-body">
                    <ul class="list-group list-group-unbordered">
                      <li class="list-group-item pt-0" style="border-top: 0;">
                        <div class="input-group">
                          <div class="row w-100 ml-0 mr-0">
                            <div class="col-md-12 mb-2">
                              <div class="row">
                                <div class="col-12">
                                  <label class="float-right" for="id_kapal">Kapal</label>
                                </div>
                                <div class="col-12">
                                  <select name="id_kapal" id="id_kapal" class="form-control custom-select select2" required>
                                    <?php
                                    $sql = mysqli_query($conn, "SELECT * FROM kapal JOIN perusahaan WHERE kapal.id_perusahaan=perusahaan.id_perusahaan ORDER BY kapal.nm_kapal ASC");
                                    for ($i = 1; $Data = mysqli_fetch_array($sql); $i++) { ?>
                                      <option value="<?= $Data['id_kapal']; ?>" <?= cekSama($isiVal['id_kapal'], $Data['id_kapal']); ?>><?= $Data['nm_kapal'] . ' - ' . $Data['nm_perusahaan']; ?></option>
                                    <?php } ?>
                                  </select>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item pb-0" style="border-bottom: 0;">
                        <div class="input-group">
                          <div class="row w-100 ml-0 mr-0">
                            <div class="col-md-6 mb-2 mb-md-0">
                              <label class="float-right" for="hari_jadwal">Hari Keberangkatan</label>
                              <select name="hari_jadwal" id="hari_jadwal" class="form-control custom-select">
                                <option value="senin" <?= cekSama($isiVal['hari_jadwal'], 'senin'); ?>>Senin</option>
                                <option value="selasa" <?= cekSama($isiVal['hari_jadwal'], 'selasa'); ?>>Selasa</option>
                                <option value="rabu" <?= cekSama($isiVal['hari_jadwal'], 'rabu'); ?>>Rabu</option>
                                <option value="kamis" <?= cekSama($isiVal['hari_jadwal'], 'kamis'); ?>>Kamis</option>
                                <option value="jumat" <?= cekSama($isiVal['hari_jadwal'], 'jumat'); ?>>Jum'at</option>
                                <option value="sabtu" <?= cekSama($isiVal['hari_jadwal'], 'sabtu'); ?>>Sabtu</option>
                                <option value="minggu" <?= cekSama($isiVal['hari_jadwal'], 'minggu'); ?>>Minggu</option>
                              </select>
                            </div>
                            <div class="col-md-6 mb-0">
                              <label class="float-right" for="jam_jadwal">Jam Keberangkatan</label>
                              <div class="input-group">
                                <input type="text" name="jam_jadwal" id="jam_jadwal" class="form-control myclockbpicker" placeholder="Tanggal Transaksi" value="<?= $isiVal['jam_jadwal']; ?>" required>
                                <div class="input-group-append">
                                  <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </li>
                    </ul>
                  </div>
                  <!-- /.card-body -->
                  <div class="card-footer">
                    <button type="submit" name="Simpan" class="btn btn-block btn-primary">Simpan</button>
                  </div>
                  <!-- /.card-footer -->
                </div>
                <!-- /.card -->
              </div>
              <!-- /.col -->
              <div class="col-md-6">
                <div class="card card-primary card-outline">
                  <div class="card-body ">
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
                    <div class="table-responsive">
                      <table id="table_kapal" class="table table-bordered table-hover" style="min-width: 400px;">
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
                  </div>
                  <!-- /.card-body -->
                </div>
                <!-- /.nav-tabs-custom -->
              </div>
              <!-- /.col -->
            </div>
          </form>
        </div>
        <!-- /.container-fluid -->
      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->






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
              <li class="list-group-item pt-0" style="border-top: 0;">
                <b>Nama Kapal</b><span class="float-right" id="nm_kapal">x</span>
              </li>
              <li class="list-group-item">
                <b>Perusahaan</b><span class="float-right" id="nm_perusahaan">x</span>
              </li>
              <li class="list-group-item sembunyikan">
                <b>Jadwal Keberangkatan</b><span class="float-right" id="jadwal_keberangkatan">x</span>
              </li>
              <li class="list-group-item sembunyikan">
                <button type="button" class="btn btn-block btn-primary" id="cek_rute" id_jadwal="">Rute Utama</button>
              </li>
              <li class="list-group-item pb-0" style="border-bottom: 0;">
                <div class="table-responsive">
                  <table id="table_jadwal" class="table table-bordered table-hover" style="min-width: 300px; width:100%;">
                    <thead>
                      <tr>
                        <th>Hari</th>
                        <th>Status</th>
                        <th>Rute</th>
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


    <div class="modal fade" id="ganti-jadwal">
      <form method="POST" class="modal-dialog" enctype="multipart/form-data" autocomplete="off">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Ganti Jadwal</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <h4 class="modal-title">Anda yakin Ingin Mengganti Jadwal ini?</h4>
            <input type="hidden" name="id_jadwal" id="uid_jadwal">
          </div>
          <div class="modal-footer">
            <div class="btn-group btn-block">
              <button class="btn btn-outline-warning" data-dismiss="modal">Batal</button>
              <button type="button" name="ubah-jadwal" class="btn btn-outline-primary">Konfirmasi</button>
            </div>
          </div>
        </div>
        <!-- /.modal-content -->
      </form>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <div class="modal fade" id="data-rute">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="overlay d-flex justify-content-center align-items-center invisible">
            <i class="fas fa-2x fa-sync fa-spin"></i>
          </div>
          <div class="modal-header">
            <h4 class="modal-title">Data Rute</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <ul class="list-group list-group-unbordered">
              <li class="list-group-item pt-0" style="border-top: 0;">
                <button type="button" class="btn btn-sm btn-primary w-100" data-toggle="modal" data-target="#tambah-rute">Tambah Rute</button>
              </li>
              <li class="list-group-item pb-0" style="border-bottom: 0;">
                <div class="table-responsive">
                  <table id="table_rute" class="table table-bordered table-hover" style="min-width: 300px; width:100%;">
                    <thead>
                      <tr>
                        <th>Rute</th>
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


    <div class="modal fade" id="tambah-rute">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="overlay d-flex justify-content-center align-items-center invisible">
            <i class="fas fa-2x fa-sync fa-spin"></i>
          </div>
          <div class="modal-header">
            <h4 class="modal-title">Tambah Rute</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <ul class="list-group list-group-unbordered">
              <li class="list-group-item pt-0" style="border-top: 0;">
                <div class="input-group">
                  <div class="row w-100 ml-0 mr-0">
                    <div class="col-md-6 mb-2">
                      <div class="row">
                        <div class="col-12">
                          <label class="float-right" for="pelabuhan_awal">Kedatangan</label>
                        </div>
                        <div class="col-12">
                          <select name="pelabuhan_awal" id="pelabuhan_awal" class="form-control custom-select select2" required>
                            <?php
                            $sql = mysqli_query($conn, "SELECT * FROM pelabuhan ORDER BY pelabuhan.nm_pelabuhan ASC");
                            for ($i = 1; $Data = mysqli_fetch_array($sql); $i++) { ?>
                              <option value="<?= $Data['id_pelabuhan']; ?>"><?= $Data['nm_pelabuhan']; ?></option>
                            <?php } ?>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6 mb-2">
                      <div class="row">
                        <div class="col-12">
                          <label class="float-right" for="pelabuhan_akhir">Keberangkatan</label>
                        </div>
                        <div class="col-12">
                          <select name="pelabuhan_akhir" id="pelabuhan_akhir" class="form-control custom-select select2" required>
                            <?php
                            $sql = mysqli_query($conn, "SELECT * FROM pelabuhan ORDER BY pelabuhan.nm_pelabuhan ASC");
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
              <li class="list-group-item pb-0" style="border-bottom: 0;">
                <div class="input-group">
                  <div class="row w-100 ml-0 mr-0">
                    <div class="col-md-12 mb-2">
                      <label class="float-right" for="harga_tiket">Harga Tiket</label>
                      <div class="input-group">
                        <div class="input-group-append">
                          <span class="input-group-text">Rp.</span>
                        </div>
                        <input type="text" name="harga_tiket" class="form-control format_uang" id="harga_tiket" placeholder="Harga Tiket" required>
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
              <button type="button" name="simpan-rute" class="btn btn-outline-success">Simpan</button>
            </div>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->


    <div class="modal fade" id="hapus-rute">
      <form method="POST" class="modal-dialog" enctype="multipart/form-data" autocomplete="off">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Hapus Rute</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <h4 class="modal-title">Anda yakin Ingin Menghapus Rute ini?</h4>
            <input type="hidden" name="id_jadwal" id="did_rute">
          </div>
          <div class="modal-footer">
            <div class="btn-group btn-block">
              <button class="btn btn-outline-success" data-dismiss="modal">Batal</button>
              <button type="button" name="delete-rute" class="btn btn-outline-danger">Hapus</button>
            </div>
          </div>
        </div>
        <!-- /.modal-content -->
      </form>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    <?php include $backurl . 'admin/config/modal.php'; ?>
    <?php include $backurl . 'config/site/footer.php'; ?>
  </div>
  <!-- ./wrapper -->

  <?php include $backurl . 'config/site/script.php'; ?>
  <!-- page script -->
  <script>
    let cek_data;
    $(function() {
      let id_jadwal, host = "<?= $df['home'] ?>";
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
          "width": "50px",
        }, {
          'className': "align-middle text-center",
          "width": "10px",
        }, {
          'className': "align-middle text-center",
          "width": "10px",
        }],
        "order": [
          [0, "desc"]
        ],
      })
      var table_rute = $('#table_rute').DataTable({
        'paging': false,
        'info': false,
        'searching': false,
        'ordering': true,
        "autoWidth": false,
        "columns": [{
          'className': "align-middle",
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
      })



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

              table_jadwal.clear().draw();
              $(data['data_jadwal']).each(function(index, hasil) {
                let button_set = '<button type="button" class="btn btn-sm btn-info" id="cek_rute" id_jadwal="' + hasil['id_jadwal'] + '">rute</button>';
                let button_set1 = '<button type="button" class="btn btn-sm btn-warning" data-toggle="modal" onclick="$(\'#ganti-jadwal #uid_jadwal\').val(\'' + hasil['id_jadwal'] + '\')" data-target="#ganti-jadwal">Aktifkan</button>';
                table_jadwal.row.add([hasil['hari_jadwal'], hasil['jam_jadwal'], button_set, button_set1]).draw();
              });

              $('#data-kapal .overlay').addClass('invisible')
            }
          },
          error: function(request, status, error) {
            console.log(request.responseText);
          }
        });
      });

      $('#ganti-jadwal').on('click', 'button[name="ubah-jadwal"]', function() {
        $('#ganti-jadwal .overlay').removeClass('invisible');
        $.ajax({
          type: "POST",
          url: host + "get-kapal.php",
          dataType: "JSON",
          data: {
            'set': 'ubah_jadwal',
            'id_jadwal': $('#ganti-jadwal #uid_jadwal').val(),
          },
          success: function(data) {
            if (data['status'] == 'done') {
              toastr.success('Jadwal Keberangkatan Berhasil Diubah!');
              $('#data-kapal').modal('hide');
              $('#ganti-jadwal').modal('hide');
              $('#ganti-jadwal .overlay').addClass('invisible');
            }
          },
          error: function(request, status, error) {
            console.log(request.responseText);
          }
        });
      });

      $('#data-kapal').on('click', 'button[id="cek_rute"]', function() {
        $('#data-rute').modal('show');
        $('#data-rute .overlay').removeClass('invisible');
        id_jadwal = $(this).attr('id_jadwal');
        $.ajax({
          type: "POST",
          url: host + "get-kapal.php",
          dataType: "JSON",
          data: {
            'set': 'get_rute',
            'id_jadwal': id_jadwal,
          },
          success: function(data) {
            if (data['status'] == 'done') {
              table_rute.clear().draw();
              $(data['data_rute']).each(function(index, hasil) {
                button_set = '<button type="button" class="btn btn-sm btn-danger" data-toggle="modal" onclick="$(\'#hapus-rute #did_rute\').val(\'' + hasil['id_rute'] + '\')" data-target="#hapus-rute"><i class="fa fa-trash-alt"></i></button>';
                table_rute.row.add([hasil['pelabuhan_awal'] + " - " + hasil['pelabuhan_akhir'], hasil['harga_tiket'], button_set]).draw();
              });
              $('#data-rute .overlay').addClass('invisible')
            }
          },
          error: function(request, status, error) {
            console.log(request.responseText);
          }
        });
      });

      $('#tambah-rute').on('click', 'button[name="simpan-rute"]', function() {
        if ($('#tambah-rute #harga_tiket').val() == '') {
          toastr.warning('Lengkapi Data Terlebih Dahulu!');
        } else {
          $('#tambah-rute .overlay').removeClass('invisible');
          $.ajax({
            type: "POST",
            url: host + "get-kapal.php",
            dataType: "JSON",
            data: {
              'set': 'simpan_rute',
              'pelabuhan_awal': $('#tambah-rute #pelabuhan_awal').val(),
              'pelabuhan_akhir': $('#tambah-rute #pelabuhan_akhir').val(),
              'harga_tiket': $('#tambah-rute #harga_tiket').val(),
              'id_jadwal': id_jadwal,
            },
            success: function(data) {
              console.log(data);
              if (data['status'] == 'done') {
                table_rute.clear().draw();
                $(data['data_rute']).each(function(index, hasil) {
                  button_set = '<button type="button" class="btn btn-sm btn-danger" data-toggle="modal" onclick="$(\'#hapus-rute #did_rute\').val(\'' + hasil['id_rute'] + '\')" data-target="#hapus-rute"><i class="fa fa-trash-alt"></i></button>';
                  table_rute.row.add([hasil['pelabuhan_awal'] + " - " + hasil['pelabuhan_akhir'], hasil['harga_tiket'], button_set]).draw();
                });

                $('#tambah-rute #pelabuhan_awal').prop('selectedIndex', 0).trigger("change");
                $('#tambah-rute #pelabuhan_akhir').prop('selectedIndex', 0).trigger("change");
                $('#tambah-rute #harga_tiket').val('');
                $('#tambah-rute').modal('hide');
                $('#tambah-rute .overlay').addClass('invisible');
              }
            },
            error: function(request, status, error) {
              console.log(request.responseText);
            }
          });
        }
      });

      $('#hapus-rute').on('click', 'button[name="delete-rute"]', function() {
        $('#hapus-rute .overlay').removeClass('invisible');
        $.ajax({
          type: "POST",
          url: host + "get-kapal.php",
          dataType: "JSON",
          data: {
            'set': 'hapus_rute',
            'id_rute': $('#hapus-rute #did_rute').val(),
            'id_jadwal': id_jadwal,
          },
          success: function(data) {
            console.log(data);
            if (data['status'] == 'done') {
              table_rute.clear().draw();
              $(data['data_rute']).each(function(index, hasil) {
                button_set = '<button type="button" class="btn btn-sm btn-danger" data-toggle="modal" onclick="$(\'#hapus-rute #did_rute\').val(\'' + hasil['id_rute'] + '\')" data-target="#hapus-rute"><i class="fa fa-trash-alt"></i></button>';
                table_rute.row.add([hasil['pelabuhan_awal'] + " - " + hasil['pelabuhan_akhir'], hasil['harga_tiket'], button_set]).draw();
              });
              toastr.warning('Data Berhasil Dihapus!');
              $('#hapus-rute').modal('hide');
              $('#hapus-rute .overlay').addClass('invisible');
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