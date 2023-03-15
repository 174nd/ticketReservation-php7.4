<?php
$backurl = './';
require_once($backurl . 'config/conn.php');
require_once($backurl . 'config/function.php');
$pset = array('title' => 'Error!');


?>

<!DOCTYPE html>
<html>

<head>
  <?php include $backurl . 'config/site/head.php'; ?>
</head>

<body class="hold-transition login-page">
  <div class="login-box">
    <div class="alert alert-warning">
      <h5><i class="icon fas fa-ban"></i> Error!</h5>
      Terjadi Kesalahan pada Akun Anda! Mohon untuk menghubungi Admin untuk info lebih lanjut!<br><br>
      <a href="<?= $df['host']; ?>" class="btn btn-warning float-right text-dark" style="text-decoration:none;">Kembali</a><br><br>
    </div>

  </div>
  <?php include $backurl . 'config/site/script.php'; ?>
</body>

</html>