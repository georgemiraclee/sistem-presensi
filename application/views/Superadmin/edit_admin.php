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
                      <input type="text" name="nama_user" class="form-control" required placeholder="Masukkan Nama Admin" value="<?php echo $data_user->nama_user;?>">
                      <input type="hidden" name="user_id" class="form-control" required placeholder="Masukkan Nama Admin" value="<?php echo $data_user->user_id;?>">
                    </div>
                  </div>
                  <div class="form-group form-float">
                    <label class="form-label"><span class="text-danger">*</span> Username</label>
                    <div class="form-line">
                      <input type="text" name="email_user" class="form-control" required placeholder="Masukkan Alamat Email" value="<?php echo $data_user->email_user;?>">
                    </div>
                  </div>
                  <div class="form-group form-float">
                    <label class="form-label">Password <span class="text-danger" style="font-size: 10px;">(abaikan jika tidak mengubah password)</span></label>
                    <div class="form-line">
                      <input type="password" name="password_user" class="form-control" placeholder="Masukkan Password">
                    </div>
                  </div>
                  <div class="form-group form-float">
                    <label class="form-label"><span class="text-danger">*</span> Channel</label>
                    <select class="form-control show-tick" name="admin_channel" required>
                      <option></option>
                      <?php echo $list_channel;?>
                    </select>
                  </div>
                  <div class="form-group form-float">
                    <label class="form-label"><span class="text-danger">*</span> Status</label>
                    <select class="form-control show-tick" name="is_aktif" required>
                      <option></option>
                      <?php echo $list_status;?>
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
    </div>
  </div>
</div>

<script>
  var urlInsert = "<?php echo base_url('Superadmin/Channel/updateAdmin'); ?>";

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