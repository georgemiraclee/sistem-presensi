<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <h3>Tambah Admin Channel</h3>
      <div class="card">
        <form class="form-horizontal" id="add-form" action="javascript:void(0);" method="post">
          <div class="card-body">
            <div class="row">
              <div class="col-md-12">
                  <div class="form-group form-float">
                    <label class="form-label"><span class="text-danger">*</span> Nama Admin</label>
                    <div class="form-line">
                      <input type="text" name="nama_user" class="form-control" required placeholder="Masukkan Nama Admin">
                    </div>
                  </div>
                  <div class="form-group form-float">
                    <label class="form-label"><span class="text-danger">*</span> Username</label>
                    <div class="form-line">
                      <input type="text" name="email_user" class="form-control" required placeholder="Masukkan Alamat Email">
                    </div>
                  </div>
                  <div class="form-group form-float">
                    <label class="form-label"><span class="text-danger">*</span> Password</label>
                    <div class="form-line">
                      <input type="password" name="password_user" class="form-control" required placeholder="Masukkan Password">
                    </div>
                  </div>
                  <div class="form-group form-float">
                    <label class="form-label"><span class="text-danger">*</span> Channel</label>
                    <select class="form-control show-tick" name="admin_channel" required>
                      <option>-- Nama Channel --</option>
                      <?php foreach ($data_channel as $key => $value) { ?>
                        <option value="<?php echo $value->id_channel;?>"><?php echo $value->nama_channel;?></option>
                      <?php } ?>
                    </select>
                  </div>
                  <div class="form-group form-float">
                    <label class="form-label"><span class="text-danger">*</span> Status</label>
                    <select class="form-control show-tick" name="is_aktif" required>
                      <option>-- Status Aktif --</option>
                      <option value="1">Aktif</option>
                      <option value="0">Tidak Aktif</option>
                    </select>
                  </div>
              </div>
            </div>
          </div>
          <div class="card-footer">
            <div class="float-right">
              <button class="btn btn-secondary" type="button" onclick="window.location='<?php echo base_url();?>Superadmin/channel/admin_channel'"><span class="fa fa-ban"></span> Batal</button>
              <button class="btn btn-primary" type="submit"><span class="fa fa-save"></span> Simpan</button>
            </div>
          </div>
        </form>
      </div>
    </div><!-- /.container-fluid -->
  </div>
</div>

<script>
var urlInsert = "<?php echo base_url('Superadmin/channel/insertAdmin'); ?>";

$(document).ready(function() {
    $('#example').DataTable();

    $('#add-form').on('submit',(function(e) {
      e.preventDefault();

      var formData = new FormData(this);

      $.ajax({
        method  : 'POST',
        url     : urlInsert,
        data    : formData,
        contentType: false,
        processData: false,
        success: function(data, status, xhr) {
          try {
            var result = JSON.parse(xhr.responseText);

            if (result.status == true) {
              swal(result.message, {
                icon: "success",
                title: "Success",
                text: "Data saved successfully",
              }).then((acc) => {
                window.location='<?php echo base_url();?>Superadmin/channel/admin_channel';
              });
            } else {
              swal("Warning!", result.message, "warning");
            }
          } catch (e) {
            swal("Warning!", "Sistem error.", "warning");
          }
        },
        error: function(data) {
          swal("Warning!", "Terjadi kesalahan sistem.", "warning");
        }
      });
    }));
});
</script>