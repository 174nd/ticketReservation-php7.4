<?php
$backurl = '../../';
require_once($backurl . 'admin/config/settings.php');
$pset = array(
  'title' => 'Perusahaan',
  'content' => 'Perusahaan',
  'breadcrumb' => array(
    'Perusahaan' => 'active',
  ),
);

$setSidebar = activedSidebar($setSidebar, 'Perusahaan');

if (isset($_POST['delete-perusahaan'])) {
  $cekdata = mysqli_query($conn, "SELECT * FROM perusahaan WHERE id_perusahaan = '$_POST[id_perusahaan]'");
  if (mysqli_num_rows($cekdata) > 0) {
    $ada = mysqli_fetch_assoc($cekdata);
    if (setDelete('perusahaan', ['id_perusahaan' => $_POST['id_perusahaan']])) {
      $_SESSION['notifikasi'] = 'NOT05';
      header("location:" . $df['home'] . "perusahaan/");
      exit();
    } else {
      $_SESSION['notifikasi'] = 'NOT02';
      header("location:" . $df['home'] . "perusahaan/");
      exit();
    }
  } else {
    $_SESSION['notifikasi'] = 'NOT02';
    header("location:" . $df['home'] . "perusahaan/");
    exit();
  }
} else if (isset($_GET['id_perusahaan'])) {
  $cekdata = mysqli_query($conn, "SELECT * FROM perusahaan WHERE id_perusahaan='$_GET[id_perusahaan]'");
  $ada = mysqli_fetch_assoc($cekdata);
  if (mysqli_num_rows($cekdata) > 0) {
    $isiVal = [
      'nm_perusahaan' => $ada['nm_perusahaan'],
    ];
    $pset = [
      'title' => 'Update Perusahaan',
      'content' => 'Update Perusahaan',
      'breadcrumb' => [
        'Dashboard' => $df['home'],
        'Perusahaan' => $df['home'] . 'perusahaan/',
        'Update' => 'active',
      ],
    ];

    if (isset($_POST["Simpan"])) {
      $_POST = setData($_POST);
      if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM perusahaan WHERE nm_perusahaan='$_POST[nm_perusahaan]' AND id_perusahaan!='$_GET[id_perusahaan]'")) > 0) {
        $_SESSION['duplikasi'] = 'Nama Perusahaan';
        $isiVal = [
          'nm_perusahaan' =>  $_POST['nm_perusahaan'],
        ];
      } else {
        $set = [
          'nm_perusahaan' =>  $_POST['nm_perusahaan'],
        ];

        $query = setUpdate($set, 'perusahaan', ['id_perusahaan' => $_GET['id_perusahaan']]);
        if (!$query) {
          $_SESSION['notifikasi'] = 'NOT02';
        } else {
          $_SESSION['notifikasi'] = 'NOT04';
          header("location:" . $df['home'] . "perusahaan/");
          exit();
        }
      }
    }
  } else {
    $_SESSION['notifikasi'] = 'NOT02';
    header("location:" . $df['home'] . "perusahaan/");
    exit();
  }
} else {
  $isiVal = [
    'nm_perusahaan' => '',
  ];
  if (isset($_POST["Simpan"])) {
    $_POST = setData($_POST);
    if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM perusahaan WHERE nm_perusahaan='$_POST[nm_perusahaan]'")) > 0) {
      $_SESSION['duplikasi'] = 'Nama Perusahaan';
      $isiVal = [
        'nm_perusahaan' =>  $_POST['nm_perusahaan'],
      ];
    } else {
      $set = [
        'nm_perusahaan' =>  $_POST['nm_perusahaan'],
      ];
      $query = setInsert($set, 'perusahaan');
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
                            <div class="col-md-12">
                              <label class="float-right" for="nm_perusahaan">Nama Perusahaan</label>
                              <input type="text" name="nm_perusahaan" class="form-control" id="nm_perusahaan" placeholder="Nama Perusahaan" value="<?= $isiVal['nm_perusahaan']; ?>" required>
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
                      <div class="col-md-12 mb-2">
                        <input type="text" class="form-control" placeholder="Cari Data" id="field_perusahaan">
                      </div>
                    </div>
                    <div class="table-responsive">
                      <table id="table_perusahaan" class="table table-bordered table-hover" style="min-width: 400px;">
                        <thead>
                          <tr>
                            <th>Nama Perusahaan</th>
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





    <div class="modal fade" id="delete-perusahaan">
      <form method="POST" class="modal-dialog" enctype="multipart/form-data" autocomplete="off">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Hapus perusahaan</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <h4 class="modal-title">Anda yakin Ingin Menghapus perusahaan ini?</h4>
            <input type="hidden" name="id_perusahaan" id="did_perusahaan">
          </div>
          <div class="modal-footer">
            <div class="btn-group btn-block">
              <button class="btn btn-outline-success" data-dismiss="modal">Batal</button>
              <button type="submit" name="delete-perusahaan" class="btn btn-outline-danger">Hapus</button>
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
      var table_perusahaan = $('#table_perusahaan').DataTable({
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
            "set_tables": "perusahaan"
          },
          "type": "POST"
        },
        "columns": [{
          'className': "align-middle",
          "data": "nm_perusahaan",
        }, {
          'className': "align-middle text-center",
          "data": "id_perusahaan",
          "width": "50px",
          "render": function(data, type, row, meta) {
            return '<div class="btn-group"><button type="button" class="btn btn-sm btn-danger" data-toggle="modal" onclick="$(\'#did_perusahaan\').val(\'' + data + '\')" data-target="#delete-perusahaan"><i class="fa fa-trash-alt"></i></button><a href="' + host + 'perusahaan/' + data + '" class="btn btn-sm bg-info"><i class="fa fa-edit"></i></a></div>';
          }
        }],
      });
      $('#table_perusahaan_filter').hide();
      $('#field_perusahaan').keyup(function() {
        table_perusahaan.columns(0).search(this.value).draw();
      });
    });
  </script>
</body>

</html>