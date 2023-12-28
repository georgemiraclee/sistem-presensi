<div class="content-wrapper">
  <div class="content-header">
      <div class="container-fluid">
          <div class="row mb-2">
              <div class="col-md-12">
                  <h1 class="m-0 text-dark">Ubah Password <strong><?php echo $user->nama_user;?></strong></h1>
              </div>
          </div><!-- /.row -->
      </div><!-- /.container-fluid -->
  </div>
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-body">
              <form action="#" id="formInsert">
                <div class="form-group row">
                  <label for="inputPassword" class="col-sm-2 col-form-label">Password Baru</label>
                  <div class="col-sm-10">
                    <input type="hidden" name="user_id" value="<?php echo $user->user_id;?>">
                    <input type="password" name="password" required class="form-control" id="inputPassword" placeholder="Input password baru">
                  </div>
                </div>

                <div class="float-right">
                  <a href="<?php echo base_url('Administrator/pegawai');?>" class="btn btn-secondary"><span class="fa fa-ban"></span> Batal</a>
                  <button class="btn btn-primary"><span class="fa fa-save"></span> Simpan</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<script>
  $(document).ready(function() {
    $('#formInsert').on('submit',(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
          method  : 'POST',
          url     : '<?php echo base_url("Administrator/pegawai/actionChangePassword");?>',
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
                  window.location='<?php echo base_url();?>Administrator/pegawai';
                });
              } else {
                swal("Warning!", result.message, "warning");
              }
            } catch (e) {
              swal("Warning!", "Sistem error.", "warning");
            }
          },
          error: function(data) {
            swal("Warning!", "Sistem error.", "warning");
          }
        });
    }));
  });
</script>
