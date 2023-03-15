<?php
$backurl = './';
require_once($backurl . 'config/conn.php');
require_once($backurl . 'config/function.php');
$pset = array('title' => 'Log-In');
?>

<!DOCTYPE html>
<html>

<head>
  <?php include $backurl . 'config/site/head.php'; ?>
  <style>
    .maindiv {
      height: 100vh;
      width: 100%;
    }
  </style>
</head>

<body class="bg-primary">
  <div class="row maindiv p-3">
    <div class="col-6">
      <div class="row h-100">
        <div class="col-md-12 p-3">
          <div class="small-box bg-success h-100 m-0" data-toggle="modal" data-target="#karyawan-masuk">
            <div class="inner px-4">
              <h3 class="text-left">
                <span id="karyawan_masuk" style="font-size: 10vh;">Null</span>
                <sup style="font-size: 4vh;top: -4vh;">Karyawan</sup>
              </h3>
              <p class="text-left" style="font-size: 6vh;">Masuk</p>
            </div>
            <div class="icon">
              <i class="fas fa-user-tie text-white" style="font-size: 20vh;margin-right: 1vw;"></i>
            </div>
          </div>
        </div>
        <div class="col-md-12 p-3">
          <div class="small-box bg-warning h-100 m-0" data-toggle="modal" data-target="#karyawan-izin">
            <div class="inner px-4">
              <h3 class="text-left">
                <span id="karyawan_izin" style="font-size: 10vh;">Null</span>
                <sup style="font-size: 4vh;top: -4vh;">Karyawan</sup>
              </h3>

              <p class="text-left" style="font-size: 6vh;">Izin</p>
            </div>
            <div class="icon">
              <i class="fas fa-house-user text-dark" style="font-size: 20vh;margin-right: 1vw;"></i>
            </div>
          </div>
        </div>
        <div class="col-md-12 p-3">
          <div class="small-box bg-danger h-100 m-0" data-toggle="modal" data-target="#karyawan-undian">
            <div class="inner px-4">
              <h3 class="text-left">
                <span id="karyawan_tm" style="font-size: 10vh;">Null</span>
                <sup style="font-size: 4vh;top: -4vh;">Karyawan</sup>
              </h3>

              <p class="text-left" style="font-size: 6vh;">Belum Masuk</p>
            </div>
            <div class="icon">
              <i class="fas fa-user-slash text-white" style="font-size: 20vh;margin-right: 1vw;"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-6 d-flex justify-content-center align-items-center">
      <img src="<?= $df['host'] . 'dist/img/potrait.png' ?>" style="height: 95vh;width: 100%;">
    </div>
  </div>


  <div class="modal fade" id="karyawan-masuk">
    <div class="modal-dialog">
      <div class="modal-content bg-success">
        <div class="overlay d-flex justify-content-center align-items-center invisible">
          <i class="fas fa-2x fa-sync fa-spin"></i>
        </div>
        <div class="modal-header">
          <h4 class="modal-title">Karyawan Masuk</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="text" class="form-control" id="nup" placeholder="NUP Karyawan Masuk" autocomplete="off">
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->


  <div class="modal fade" id="warning-masuk">
    <div class="modal-dialog">
      <div class="modal-content bg-warning">
        <div class="modal-header">
          <h4 class="modal-title">Warning!</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <h3>Karyawan Sudah Terdata Masuk!</h3>
        </div>
        <div class="modal-footer">
          <button class="btn btn-block btn-outline-dark" data-dismiss="modal">Lanjutkan</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="data-masuk">
    <div class="modal-dialog">
      <div class="modal-content bg-success">
        <div class="modal-header">
          <h4 class="modal-title">Data Karyawan</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="text-center">
            <img class="profile-user-img img-fluid mb-2" src="#" alt="User profile picture" style="width: 300px;" id="foto_karyawan">
          </div>
          <ul class="list-group list-group-unbordered">
            <li class="list-group-item bg-success border-light pt-0" style="border-top: 0;">
              <b>Nama</b> <span class="float-right" id="nm_karyawan"></span>
            </li>
            <li class="list-group-item bg-success border-light">
              <b>NUP</b> <span class="float-right" id="nup"></span>
            </li>
            <li class="list-group-item bg-success border-light pb-0" style="border-bottom: 0;">
              <b>Waktu Masuk</b> <span class="float-right" id="waktu_masuk"></span>
            </li>
          </ul>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->



  <div class="modal fade" id="karyawan-izin">
    <div class="modal-dialog">
      <div class="modal-content bg-warning">
        <div class="overlay d-flex justify-content-center align-items-center invisible">
          <i class="fas fa-2x fa-sync fa-spin"></i>
        </div>
        <div class="modal-header">
          <h4 class="modal-title">Izin Karyawan</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <ul class="list-group list-group-unbordered">
            <li class="list-group-item bg-warning pt-0" style="border-top: 0;">
              <input type="text" class="form-control" id="nup" placeholder="NUP Karyawan" autocomplete="off">
            </li>
            <li class="list-group-item bg-warning pb-0" style="border-bottom: 0;">
              <textarea id="ket_absensi" class="form-control" placeholder="Keterangan Absensi" autocomplete="off"></textarea>
            </li>
          </ul>
        </div>
        <div class="modal-footer">
          <div class="btn-group btn-block">
            <button class="btn btn-outline-danger" data-dismiss="modal">Batal</button>
            <button type="button" id="simpan" class="btn btn-outline-primary">Simpan</button>
          </div>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->


  <div class="modal fade" id="warning-izin">
    <div class="modal-dialog">
      <div class="modal-content bg-warning">
        <div class="modal-header">
          <h4 class="modal-title">Warning!</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <h3>Karyawan Sudah Memiliki Data Izin!</h3>
        </div>
        <div class="modal-footer">
          <button class="btn btn-block btn-outline-dark" data-dismiss="modal">Lanjutkan</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->


  <div class="modal fade" id="data-izin">
    <div class="modal-dialog">
      <div class="modal-content bg-warning">
        <div class="modal-header">
          <h4 class="modal-title">Data Karyawan</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="text-center">
            <img class="profile-user-img img-fluid mb-2" src="#" alt="User profile picture" style="width: 300px;" id="foto_karyawan">
          </div>
          <ul class="list-group list-group-unbordered">
            <li class="list-group-item bg-warning border-light pt-0" style="border-top: 0;">
              <b>Nama</b> <span class="float-right" id="nm_karyawan"></span>
            </li>
            <li class="list-group-item bg-warning border-light">
              <b>NUP</b> <span class="float-right" id="nup"></span>
            </li>
            <li class="list-group-item bg-warning border-light">
              <b>Waktu Izin</b> <span class="float-right" id="waktu_izin"></span>
            </li>
            <li class="list-group-item bg-warning border-light pb-0" style="border-bottom: 0;">
              <b>Keterangan</b> <span class="float-right" id="ket_izin"></span>
            </li>
          </ul>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->


  <div class="modal fade" id="karyawan-undian">
    <div class="modal-dialog">
      <div class="modal-content bg-danger">
        <div class="overlay d-flex justify-content-center align-items-center invisible">
          <i class="fas fa-2x fa-sync fa-spin"></i>
        </div>
        <div class="modal-header">
          <h4 class="modal-title">Undian Karyawan</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="text" class="form-control text-light" id="nup" placeholder="NUP Karyawan Masuk" autocomplete="off">
        </div>
        <div class="modal-footer">
          <a class="btn btn-block btn-outline-light" href="#">Cetak Laporan Absensi</a>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="warning-undian">
    <div class="modal-dialog">
      <div class="modal-content bg-warning">
        <div class="modal-header">
          <h4 class="modal-title">Warning!</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <h3>Karyawan Tidak Hadir!</h3>
        </div>
        <div class="modal-footer">
          <button class="btn btn-block btn-outline-dark" data-dismiss="modal">Lanjutkan</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="data-undian">
    <div class="modal-dialog">
      <div class="modal-content bg-primary">
        <div class="modal-header">
          <h4 class="modal-title">Data Karyawan</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="text-center">
            <img class="profile-user-img img-fluid mb-2" src="<?= $df['host'] . 'dist/img/roll.gif' ?>" alt="User profile picture" style="width: 300px;" id="foto_karyawan">
          </div>
          <ul class="list-group list-group-unbordered">
            <li class="list-group-item bg-primary border-light pt-0" style="border-top: 0;">
              <b>Nama</b> <span class="float-right" id="nm_karyawan"></span>
            </li>
            <li class="list-group-item bg-primary border-light pb-0" style="border-bottom: 0;">
              <b>NUP</b> <span class="float-right" id="nup"></span>
            </li>
          </ul>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->


  <?php include $backurl . 'config/site/script.php'; ?>
  <script>
    $(function() {
      let clicked = 0;
      var host = "<?= $df['host'] ?>";

      function refreshChat() {
        $.ajax({
          type: "POST",
          url: host + "config/get-absensi.php",
          dataType: "JSON",
          data: {
            'set': 'Refresh',
          },
          success: function(data) {
            $("#karyawan_masuk").html(data['masuk']);
            $("#karyawan_izin").html(data['keluar']);
            $("#karyawan_tm").html(data['tidak_hadir']);
          },
          error: function(request, status, error) {
            console.log(request.responseText);
          }
        });
      }
      setInterval(function() {
        refreshChat();
      }, 1000);



      $("#karyawan-masuk").on('shown.bs.modal', function() {
        $("#data-masuk #nm_karyawan").html("");
        $("#data-masuk #nup").html("");
        $("#data-masuk #waktu_masuk").html("");
        $("#data-masuk #foto_karyawan").attr("src", "#");

        $("#karyawan-masuk #nup").val('');
        $("#karyawan-masuk #nup").focus();
        $("#karyawan-masuk #nup").unbind();
        $("#karyawan-masuk #nup").on('keyup', function(e) {
          if (this.value != '') {
            if (e.key === 'Enter' || e.keyCode === 13) {
              $("#karyawan-masuk .modal-content .overlay").removeClass("invisible");
              $.ajax({
                type: "POST",
                url: host + "config/get-absensi.php",
                dataType: "JSON",
                data: {
                  'set': 'set_masuk',
                  'nup': this.value
                },
                success: function(data) {
                  console.log(data)
                  if (data['status'] == 'done') {
                    $("#karyawan-masuk .modal-content .overlay").addClass("invisible");
                    $("#data-masuk #nup").html(data['nup']);
                    $("#data-masuk #nm_karyawan").html(data['nm_karyawan']);
                    $("#data-masuk #waktu_masuk").html(data['waktu_absensi']);
                    $("#data-masuk #foto_karyawan").attr("src", host + "uploads/karyawan/" + data['foto_karyawan']);
                    $("#karyawan-masuk").modal("hide");
                    $("#data-masuk").modal("show");
                  } else if (data['status'] == 'masuk') {
                    $("#warning-masuk").modal("show");
                    $('#warning-masuk [data-dismiss="modal"]').focus();
                    $("#karyawan-masuk .modal-content .overlay").addClass("invisible");
                  }
                },
                error: function(request, status, error) {
                  console.log(request.responseText);
                }
              });
            }
          }

        });
      });
      $("#warning-masuk").on('hide.bs.modal', function() {
        $("#karyawan-masuk #nup").val('');
        $("#karyawan-masuk #nup").focus();
      });

      $("#karyawan-izin").on('shown.bs.modal', function() {
        $("#data-izin #nm_karyawan").html("");
        $("#data-izin #nup").html("");
        $("#data-izin #ket_izin").html("");
        $("#data-izin #waktu_izin").html("");
        $("#data-izin #foto_karyawan").attr("src", "#");


        $("#karyawan-izin #nup").attr('disabled', false);
        $("#karyawan-izin #ket_absensi").val('');
        $("#karyawan-izin #nup").val('');
        $("#karyawan-izin #nup").focus();
        $("#karyawan-izin #nup").unbind();
        $("#karyawan-izin #nup").on('keyup', function(e) {
          if (this.value != '') {
            if (e.key === 'Enter' || e.keyCode === 13) {
              $("#karyawan-izin #nup").attr('disabled', true);
              $("#karyawan-izin #ket_absensi").focus();
            }
          }
        });

        $("#karyawan-izin #simpan").unbind();
        $("#karyawan-izin #simpan").on('click', function(e) {
          $.ajax({
            type: "POST",
            url: host + "config/get-absensi.php",
            dataType: "JSON",
            data: {
              'set': 'set_izin',
              'nup': $("#karyawan-izin #nup").val(),
              'ket_absensi': $("#karyawan-izin #ket_absensi").val(),
            },
            success: function(data) {
              console.log(data)
              if (data['status'] == 'done') {
                $("#karyawan-izin .modal-content .overlay").addClass("invisible");
                $("#data-izin #nm_karyawan").html(data['nup']);
                $("#data-izin #nup").html(data['nm_karyawan']);
                $("#data-izin #ket_izin").html(data['ket_absensi']);
                $("#data-izin #waktu_izin").html(data['waktu_absensi']);
                $("#data-izin #foto_karyawan").attr("src", host + "uploads/karyawan/" + data['foto_karyawan']);
                $("#karyawan-izin").modal("hide");
                $("#data-izin").modal("show");
              } else if (data['status'] == 'keluar') {
                $("#warning-izin").modal("show");
                $('#warning-izin [data-dismiss="modal"]').focus();
                $("#karyawan-izin #nup").attr('disabled', false);
                $("#karyawan-izin .modal-content .overlay").addClass("invisible");
              }
            },
            error: function(request, status, error) {
              console.log(request.responseText);
            }
          });
        });
        $("#warning-izin").on('hide.bs.modal', function() {
          $("#karyawan-izin #nup").val('');
          $("#karyawan-izin #nup").focus();
        });
      });


      $("#karyawan-undian").on('shown.bs.modal', function() {
        $("#data-undian #nm_karyawan").html("");
        $("#data-undian #nup").html("");
        $("#data-undian #foto_karyawan").attr("src", host + "dist/img/roll.gif");

        $("#karyawan-undian #nup").val('');
        $("#karyawan-undian #nup").focus();
        $("#karyawan-undian #nup").unbind();
        $("#karyawan-undian #nup").on('keyup', function(e) {
          if (this.value != '') {
            if (e.key === 'Enter' || e.keyCode === 13) {
              $("#karyawan-undian .modal-content .overlay").removeClass("invisible");
              $.ajax({
                type: "POST",
                url: host + "config/get-absensi.php",
                dataType: "JSON",
                data: {
                  'set': 'get_undian',
                  'nup': this.value
                },
                success: function(data) {
                  console.log(data)
                  $("#karyawan-undian .modal-content .overlay").addClass("invisible");
                  $("#karyawan-undian").modal("hide");
                  $("#data-undian").modal("show");
                  setTimeout(function() {
                    if (data['status'] == 'done') {
                      $("#data-undian #nup").html(data['nup']);
                      $("#data-undian #nm_karyawan").html(data['nm_karyawan']);
                      $("#data-undian #foto_karyawan").attr("src", host + "uploads/karyawan/" + data['foto_karyawan']);
                    } else if (data['status'] == 'tidak-hadir') {
                      $("#data-undian").modal("hide");
                      $("#warning-undian").modal("show");
                    }
                  }, 3000);
                },
                error: function(request, status, error) {
                  console.log(request.responseText);
                }
              });
            }
          }

        });
      });

      $("#warning-undian").on('hide.bs.modal', function() {
        $("#data-undian #nm_karyawan").html("");
        $("#data-undian #nup").html("");
        $("#data-undian #foto_karyawan").attr("src", host + "dist/img/roll.gif");
        $("#karyawan-undian").modal("show");
        $("#karyawan-undian #nup").focus();
      });




    });
  </script>
</body>

</html>