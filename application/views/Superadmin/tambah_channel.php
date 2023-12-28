<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <h3>Tambah Channel</h3>
      <div class="card">
        <form class="form-horizontal" id="add-form" action="javascript:void(0);" method="post">
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group form-float">
                  <label class="form-label" for="nama_channel"><span class="text-danger">*</span> Nama Channel</label>
                  <div class="form-line">
                    <input type="text" name="nama_channel" id="nama_channel" class="form-control" required placeholder="Nama Channel">
                  </div>
                </div>
              </div>
  
              <div class="col-md-6">
                <div class="form-group form-float">
                  <label class="form-label" for="alamat_channel"><span class="text-danger">*</span> Alamat Channel</label>
                  <div class="form-line">
                    <textarea class="form-control" name="alamat_channel" id="alamat_channel" placeholder="Alamat Channel"></textarea>
                  </div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group form-float">
                  <label class="form-label" for="email_channel"><span class="text-danger">*</span> Email Channel</label>
                  <div class="form-line">
                    <input type="email" name="email_channel" id="email_channel" class="form-control" required placeholder="Email Perusahaan">
                  </div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group form-float">
                  <label class="form-label" for="telp_channel"><span class="text-danger">*</span> Nomor Telepon</label>
                  <div class="form-line">
                    <input type="text" name="telp_channel" id="telp_channel" class="form-control" required placeholder="Nomor Telepon">
                  </div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group form-float">
                  <label class="form-label" for="limit_user"><span class="text-danger">*</span> Limit User</label>
                  <div class="form-line">
                    <input type="number" id="limit_user" name="limit_user" value="1" min="1" class="form-control">
                  </div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group form-float">
                  <label class="form-label" for="userfile">Icon Channel</label>
                  <div class="form-line">
                      <input type="file" name="userfile" id="userfile">
                  </div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group form-float">
                  <label class="form-label" for="code_company"><span class="text-danger">*</span> Code Company</label>
                  <div class="form-line">
                    <input type="text" class="form-control" name="code_company" id="code_company" placeholder="Code Company" maxlength="3" minlength="3">
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group form-float">
                  <label class="form-label" for="code_company"><span class="text-danger">*</span> Package</label>
                  <div class="form-line">
                    <select name="package" id="package" class="form-control" required>
                      <option value="">-- Select package --</option>
                      <option value="basic">Basic</option>
                      <option value="premium">Premium</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group form-float">
                  <label class="form-label" for="deskripsi_channel">Description Channel</label>
                  <div class="form-line">
                    <textarea class="form-control" id="deskripsi_channel" name="deskripsi_channel" placeholder="Deskripsi Channel"></textarea>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card-footer">
            <div class="float-right">
              <button class="btn btn-secondary" type="button" onclick="window.location='<?php echo base_url();?>Superadmin/channel'"><span class="fa fa-ban"></span> Batal</button>
              <button class="btn btn-primary" type="submit"><span class="fa fa-save"></span> Simpan</button>
            </div>
          </div>
        </form>
      </div>
    </div><!-- /.container-fluid -->
  </div>
</div>

<script>
var urlInsert = "<?php echo base_url('Superadmin/channel/insert'); ?>";

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
                window.location = '<?php echo base_url();?>Superadmin/Channel';
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