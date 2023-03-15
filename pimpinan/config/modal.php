<div class="modal fade" id="update-password">
  <form method="POST" action="<?= $df['home']; ?>" class="modal-dialog">
    <div class="modal-content bg-secondary">
      <div class="modal-header">
        <h4 class="modal-title">Ubah Password</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group" style="border-bottom: 1px solid #e9ecef;">
          <div class="input-group">
            <div class="row w-100 ml-0 mr-0">
              <div class="col-md-12 mb-3">
                <label class="float-right" for="pass_lama">Password Lama</label>
                <input type="password" name="pass_lama" class="form-control" id="pass_lama" placeholder="Password Lama">
                <input type="hidden" name="id" value="<?= $_SESSION['id']; ?>">
              </div>
            </div>
          </div>
        </div>
        <div class="form-group m-0" style="border-bottom: 1px solid #e9ecef;">
          <div class="input-group">
            <div class="row w-100 ml-0 mr-0">
              <div class="col-md-6 mb-3">
                <label class="float-right" for="pass_baru1">Password Baru</label>
                <input type="password" name="pass_baru1" class="form-control" id="pass_baru1" placeholder="Password Baru">
              </div>
              <div class="col-md-6 mb-3">
                <label class="float-right" for="pass_baru2">Konfirmasi Password</label>
                <input type="password" name="pass_baru2" class="form-control" id="pass_baru2" placeholder="Konfirmasi Password">
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer pb-0">
        <div class="row w-100 ml-0 mr-0">
          <div class="col-md-12">
            <button type="submit" name="u-password" class="btn btn-outline-light btn-block mb-3">Simpan</button>
          </div>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </form>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->