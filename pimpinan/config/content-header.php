<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark"><?= $pset['content']; ?></h1>
        </div>
        <!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <?php
            if (isset($pset['breadcrumb'])) {
              foreach ($pset['breadcrumb'] as $key => $value) {
                if ($value != '#' && $value != "active") {
                  echo "<li class=\"breadcrumb-item\"><a href=\"$value\">$key</a></li>";
                } else if ($value == "active") {
                  echo "<li class=\"breadcrumb-item active\">$key</li>";
                } else {
                  echo "<li class=\"breadcrumb-item\">$key</li>";
                }
              }
            }
            ?>
          </ol>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
    <?php notifikasi('in'); ?>
  </div>
</div>
<!-- /.content-header -->