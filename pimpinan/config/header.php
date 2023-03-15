<!-- Navbar -->
<nav class="main-header navbar navbar-expand-md navbar-light">
  <div class="container">

    <a href="<?= $df['home']; ?>" class="navbar-brand">
      <img src="<?= $df['brand-image']; ?>" alt="Logo" class="brand-image" style="opacity: 0.8;" />
      <span class="brand-text font-weight-light">Pelabuhan Domestik Sekupang</span>
    </a>

    <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
      <li class="nav-item dropdown user-menu">
        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
          <img src="<?= cekFoto($_SESSION['foto_user'], 'uploads/users'); ?>" class="user-image" alt="User Image" />
        </a>
        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <!-- User image -->
          <li class="user-header bg-primary">
            <img src="<?= cekFoto($_SESSION['foto_user'], 'uploads/users'); ?>" class="img-circle elevation-2" alt="User Image" />
            <p>
              <?= $_SESSION['nm_user']; ?>
              <small><?= $_SESSION['akses']; ?></small>
            </p>
          </li>
          <li class="user-body">
            <div class="row">
              <div class="col-12 text-center">
                <button type="button" class="btn btn-block btn-outline-secondary" data-toggle="modal" data-target="#update-password">Ganti Password</button>
              </div>
            </div>
            <!-- /.row -->
          </li>
          <!-- Menu Footer-->
          <li class="user-footer">
            <a href="<?= $df['host'] . 'logout/'; ?>" class="btn btn-default btn-flat float-right">Keluar</a>
          </li>
        </ul>
      </li>
    </ul>
  </div>
</nav>
<!-- /.navbar -->