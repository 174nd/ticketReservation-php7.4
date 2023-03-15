<?php
$backurl = '../../';
require_once($backurl . 'admin/config/settings.php');
$pset = array(
  'title' => 'Kursi',
  'content' => 'Kursi',
  'breadcrumb' => array(
    'Kursi' => 'active',
  ),
);

$setSidebar = activedSidebar($setSidebar, 'Kursi');

if (isset($_POST['delete-kursi'])) {
  $cekdata = mysqli_query($conn, "SELECT * FROM kursi WHERE id_kursi = '$_POST[id_kursi]'");
  if (mysqli_num_rows($cekdata) > 0) {
    $ada = mysqli_fetch_assoc($cekdata);
    if (setDelete('kursi', ['id_kursi' => $_POST['id_kursi']])) {
      $_SESSION['notifikasi'] = 'NOT05';
      header("location:" . $df['home'] . "kursi/");
      exit();
    } else {
      $_SESSION['notifikasi'] = 'NOT02';
      header("location:" . $df['home'] . "kursi/");
      exit();
    }
  } else {
    $_SESSION['notifikasi'] = 'NOT02';
    header("location:" . $df['home'] . "kursi/");
    exit();
  }
} else if (isset($_GET['id_kursi'])) {
  $cekdata = mysqli_query($conn, "SELECT * FROM kursi WHERE id_kursi='$_GET[id_kursi]'");
  $ada = mysqli_fetch_assoc($cekdata);
  if (mysqli_num_rows($cekdata) > 0) {
    $isiVal = [
      'no_kursi' => $ada['no_kursi'],
      'id_kapal' => $ada['id_kapal'],
    ];
    $pset = [
      'title' => 'Update Kursi',
      'content' => 'Update Kursi',
      'breadcrumb' => [
        'Dashboard' => $df['home'],
        'Kursi' => $df['home'] . 'kursi/',
        'Update' => 'active',
      ],
    ];

    if (isset($_POST["Simpan"])) {
      $_POST = setData($_POST);
      if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM kursi WHERE (no_kursi='$_POST[no_kursi]' AND id_kapal='$_POST[id_kapal]') AND id_kursi!='$_GET[id_kursi]'")) > 0) {
        $_SESSION['duplikasi'] = 'Nama Kursi';
        $isiVal = [
          'no_kursi' =>  $_POST['no_kursi'],
          'id_kapal' =>  $_POST['id_kapal'],
        ];
      } else {
        $set = [
          'no_kursi' =>  $_POST['no_kursi'],
          'id_kapal' =>  $_POST['id_kapal'],
        ];

        $query = setUpdate($set, 'kursi', ['id_kursi' => $_GET['id_kursi']]);
        if (!$query) {
          $_SESSION['notifikasi'] = 'NOT02';
        } else {
          $_SESSION['notifikasi'] = 'NOT04';
          header("location:" . $df['home'] . "kursi/");
          exit();
        }
      }
    }
  } else {
    $_SESSION['notifikasi'] = 'NOT02';
    header("location:" . $df['home'] . "kursi/");
    exit();
  }
} else {
  $isiVal = [
    'no_kursi' => '',
    'id_kapal' => '',
  ];
  if (isset($_POST["Simpan"])) {
    $_POST = setData($_POST);
    if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM kursi WHERE no_kursi='$_POST[no_kursi]' AND id_kapal='$_POST[id_kapal]'")) > 0) {
      $_SESSION['duplikasi'] = 'Nama Kursi';
      $isiVal = [
        'no_kursi' =>  $_POST['no_kursi'],
        'id_kapal' =>  $_POST['id_kapal'],
      ];
    } else {
      $set = [
        'no_kursi' =>  $_POST['no_kursi'],
        'id_kapal' =>  $_POST['id_kapal'],
      ];
      $query = setInsert($set, 'kursi');
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
                      <li class="list-group-item py-0" style="border: 0;">
                        <div class="input-group">
                          <div class="row w-100 ml-0 mr-0">
                            <div class="col-md-4 mb-2 mb-md-0">
                              <label class="float-right" for="no_kursi">Nomor Kursi</label>
                              <input type="text" name="no_kursi" class="form-control" id="no_kursi" placeholder="Nomor Kursi" value="<?= $isiVal['no_kursi']; ?>" required>
                            </div>
                            <div class="col-md-8">
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
                        <select id="column_kursi" class="form-control custom-select">
                          <option value="0">Nama Kapal</option>
                          <option value="1">Nomor</option>
                        </select>
                      </div>
                      <div class="col-md-8 mb-2">
                        <input type="text" class="form-control" placeholder="Cari Data" id="field_kursi">
                      </div>
                    </div>
                    <div class="table-responsive">
                      <table id="table_kursi" class="table table-bordered table-hover" style="min-width: 400px;">
                        <thead>
                          <tr>
                            <th>Nama Kapal</th>
                            <th>Nomor</th>
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





    <div class="modal fade" id="delete-kursi">
      <form method="POST" class="modal-dialog" enctype="multipart/form-data" autocomplete="off">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Hapus kursi</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <h4 class="modal-title">Anda yakin Ingin Menghapus kursi ini?</h4>
            <input type="hidden" name="id_kursi" id="did_kursi">
          </div>
          <div class="modal-footer">
            <div class="btn-group btn-block">
              <button class="btn btn-outline-success" data-dismiss="modal">Batal</button>
              <button type="submit" name="delete-kursi" class="btn btn-outline-danger">Hapus</button>
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
    $(function() {
      var host = "<?= $df['home'] ?>";
      var table_kursi = $('#table_kursi').DataTable({
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
            "set_tables": "SELECT kursi.id_kursi, kursi.no_kursi, CONCAT(kapal.nm_kapal, ' - ', perusahaan.nm_perusahaan) AS nama_kapal FROM ((kursi JOIN kapal) JOIN perusahaan) WHERE kursi.id_kapal=kapal.id_kapal AND kapal.id_perusahaan=perusahaan.id_perusahaan",
            "query": true
          },
          "type": "POST"
        },
        "columns": [{
          'className': "align-middle",
          "data": "nama_kapal",
        }, {
          'className': "align-middle text-center",
          "data": "no_kursi",
          "width": "50px",
        }, {
          'className': "align-middle text-center",
          "data": "id_kursi",
          "width": "50px",
          "render": function(data, type, row, meta) {
            return '<div class="btn-group"><button type="button" class="btn btn-sm btn-danger" data-toggle="modal" onclick="$(\'#did_kursi\').val(\'' + data + '\')" data-target="#delete-kursi"><i class="fa fa-trash-alt"></i></button><a href="' + host + 'kursi/' + data + '" class="btn btn-sm bg-info"><i class="fa fa-edit"></i></a></div>';
          }
        }],
      });
      $('#table_kursi_filter').hide();
      $('#field_kursi').keyup(function() {
        table_kursi.columns($('#column_kursi').val()).search(this.value).draw();
      });
    });
  </script>
</body>

</html>