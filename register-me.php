<?php
$backurl = './';
require_once($backurl . 'config/conn.php');
require_once($backurl . 'config/function.php');
$pset = array('title' => 'Register');
$isiVal = [
  'nm_user' => '',
  'username' => '',
  'password' => '',
];

if (isset($_POST["daftar"])) {
  $_POST = setData($_POST);
  if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM user WHERE username='$_POST[username]'")) > 0) {
    $_SESSION['duplikasi'] = 'Usename';
    $isiVal = [
      'nm_user' => $_POST['nm_user'],
      'username' => $_POST['username'],
      'password' => '',
    ];
  } else {
    $set = [
      'nm_user' => $_POST['nm_user'],
      'username' => $_POST['username'],
      'password' => $_POST['password'],
      'akses' => 'user',
    ];
    $query = setInsert($set, 'user');
    if (!$query) {
      $_SESSION['notifikasi'] = 'NOT02';
    } else {
      $_SESSION['notifikasi'] = 'NOT03';
      header("location:" . $df['host']);
      exit();
    }
  }
}
?>

<!DOCTYPE html>
<html>

<head>
  <?php include $backurl . 'config/site/head.php'; ?>
</head>

<body class="hold-transition login-page bg-primary">
  <div class="login-box">
    <div class="login-logo">
      <a href="<?= $df['host']; ?>" class="text-light"><b>Pemesanan Tiket Kapal </b><br>Ferry Domestik Sekupang</a>
    </div>
    <?php notifikasi('in'); ?>
    <!-- /.login-logo -->
    <div class="card">
      <div class="card-body login-card-body">
        <form action="" method="POST" autocomplete="off">
          <div class="input-group mb-3">
            <input type="text" name="nm_user" class="form-control" placeholder="Nama User" required value="<?= $isiVal['nm_user'] ?>">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="text" name="username" class="form-control" placeholder="Username" required value="<?= $isiVal['username'] ?>">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" name="password" class="form-control" placeholder="Password" required value="<?= $isiVal['password'] ?>">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 offset-md-6">
              <button type="submit" name="daftar" class="btn btn-primary btn-block">Registrasi</button>
            </div>
            <!-- /.col -->
          </div>
        </form>
      </div>
    </div>
    <!-- /.login-box -->
    <?php include $backurl . 'config/site/script.php'; ?>
</body>

</html>