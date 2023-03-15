<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-dark navbar-primary">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
  </ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
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
            <small><?= $_SESSION["akses"]; ?></small>
          </p>
        </li>
        <!-- Menu Body -->
        <li class="user-body">
          <div class="row">
            <div class="col-12 text-center">
              <button type="button" class="btn btn-block btn-outline-primary" data-toggle="modal" data-target="#update-password">Ganti Password</button>
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
</nav>
<!-- /.navbar -->

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-light-primary elevation-4 sidebar-no-expand">
  <!-- Brand Logo -->
  <a href="<?= $df['home']; ?>" class="brand-link bg-primary">
    <img src="<?= $df['brand-image']; ?>" alt="Logo" class="brand-image" style="opacity: 0.8;" />
    <span class="brand-text font-weight-light">Pelabuhan Domestik Sekupang</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-1 pb-2 mb-3 d-flex">
      <div class="image d-flex align-items-center">
        <img src="<?= cekFoto($_SESSION['foto_user'], 'uploads/users'); ?>" class="img-circle elevation-2" alt="User Image" />
      </div>
      <div class="info">
        <a href="#" class="d-block">
          <span style="font-size: 16px;"><?= $_SESSION["nm_user"]; ?></span>
          <br>
          <small><?= $_SESSION["akses"]; ?></small>
        </a>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column nav-flat" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
        <?php sidebar($setSidebar) ?>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>