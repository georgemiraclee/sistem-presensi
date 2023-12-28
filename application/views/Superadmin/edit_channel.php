<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <h3>Edit Channel</h3>
      <div class="card">
        <form class="form-horizontal" id="add-form" action="javascript:void(0);" method="post">
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group form-float">
                  <label class="form-label"><span class="text-danger">*</span> Nama Channel</label>
                  <div class="form-line">
                    <input type="text" name="nama_channel" class="form-control" required placeholder="Isikan Nama Channel" value="<?php echo $data_channel->nama_channel;?>">
                    <input type="hidden" name="id_channel" class="form-control" required placeholder="Isikan Nama Channel" value="<?php echo $data_channel->id_channel;?>">
                  </div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group form-float">
                  <label class="form-label"><span class="text-danger">*</span> Alamat Channel</label>
                  <div class="form-line">
                    <textarea class="form-control" name="alamat_channel" placeholder="Alamat Channel"><?php echo $data_channel->alamat_channel;?></textarea>
                  </div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group form-float">
                  <label class="form-label"><span class="text-danger">*</span> Email Channel</label>
                  <div class="form-line">
                    <input type="email" name="email_channel" class="form-control" required placeholder="Isikan Email yang valid" value="<?php echo $data_channel->email_channel;?>">
                  </div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group form-float">
                  <label class="form-label"><span class="text-danger">*</span> Nomor Telepon</label>
                  <div class="form-line">
                    <input type="text" name="telp_channel" class="form-control" required placeholder="Isikan Nomor Telepon" value="<?php echo $data_channel->telp_channel;?>">
                  </div>
                </div>
              </div>
              
              <div class="col-md-6">
                <div class="form-group form-float">
                  <label class="form-label"><span class="text-danger">*</span> Limit User</label>
                  <div class="form-line">
                    <input type="number" name="limit_user" min="1" class="form-control" value="<?php echo $data_channel->limit_user;?>" data-rule="quantity">
                  </div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group form-float">
                  <label class="form-label">Icon Channel</label>
                  <div class="form-line">
                    <input type="file" name="userfile">
                    <p class="text-danger" style="font-size: 13px;">* Kosongkan jika tidak akan mengubah icon</p>
                  </div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group form-float">
                  <label class="form-label" for="code_company"><span class="text-danger">*</span> Code Company</label>
                  <div class="form-line">
                    <input type="text" class="form-control" name="code_company" id="code_company" placeholder="Code Company" maxlength="3" minlength="3" value="<?php echo $data_channel->code_channel;?>">
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
                  <label class="form-label">Deskripsi Channel</label>
                  <div class="form-line">
                    <textarea class="form-control" name="deskripsi_channel" placeholder="Isikan Deskripsi Channel"><?php echo $data_channel->deskripsi_channel;?></textarea>
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
  var urlInsert = "<?php echo base_url('Superadmin/Channel/update'); ?>";
  $(document).ready(function() {
    $('#package').val('<?php echo $data_channel->package;?>');
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
                window.location='<?php echo base_url();?>Superadmin/Channel';
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