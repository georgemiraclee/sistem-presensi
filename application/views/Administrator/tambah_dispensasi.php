<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-md-12">
          <h1 class="m-0 text-dark">Tambah Dispensasi</h1>
        </div>
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <section class="content">
    <div class="container-fluid">
      <div class="card">
        <form class="form-horizontal" id="add-form" action="javascript:void(0);" method="post">
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group form-float">
                  <label class="form-label"><i class="text-danger">*</i> Pegawai</label>
                  <div class="form-line">
                    <select class="form-control" name="user_id" data-live-search="true" required>
                      <option></option>
                      <?php echo $list_pegawai; ?>
                    </select>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group form-float">
                  <label class="form-label"><i class="text-danger">*</i> Tanggal Dispensasi</label>
                  <div class="form-line">
                    <input type="date" name="tanggal_dispensasi" class="form-control" required>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group form-float">
                  <label class="form-label"><i class="text-danger">*</i> Jam Awal</label>
                  <div class="form-line">
                    <input type="time" name="jam_awal_dispensasi" class="form-control" required>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group form-float">
                  <label class="form-label"><i class="text-danger">*</i> Jam Akhir</label>
                  <div class="form-line">
                    <input type="time" name="jam_akhir_dispensasi" class="form-control" required>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card-footer">
            <div class="float-right">
              <button class="btn btn-secondary" type="button" onclick="window.location='<?php echo base_url(); ?>Administrator/dispensasi'"><span class="fa fa-ban"></span> Batal</button>
              <button class="btn btn-primary" type="submit"><span class="fa fa-save"></span> Simpan</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </section>
</div>

<script>
  var urlInsert = "<?php echo base_url('Administrator/dispensasi/insert'); ?>";

  $(document).ready(function() {
    $('#add-form').on('submit', (function(e) {
      e.preventDefault();

      var formData = new FormData(this);

      $.ajax({
        method: 'POST',
        url: urlInsert,
        data: formData,
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
                window.location = '<?php echo base_url(); ?>Administrator/dispensasi';
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