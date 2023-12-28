<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-md-12">
          <h1 class="m-0 text-dark">Import Data Pegawai</h1>
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
              <?php echo $this->session->flashdata('pesan'); ?>

              <form class="form-horizontal" enctype="multipart/form-data" id="add-form" method="POST" action="javascript:void(0);">
                <div class="row clearfix">
                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                        <label for="password_2">File</label>
                      </div>
                      <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                        <div class="form-group">
                          <input type="file" name="userfile" id="file" required accept=".xls,.xlsx">
                          <p style="color: red; font-size: 10px;">(*)xlsx</p>
                        </div>
                    </div>
                </div>
              </form>
            </div>
            <div class="card-footer">
              <div class="float-right">
                <a href="<?php echo base_url();?>assets/importData/format.xlsx" download class="btn btn-secondary"><span class="fa fa-download"></span> Download Format</a>
                <button type="submit" class="btn btn-primary"><span class="fa fa-upload"></span> Import</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<script>
    $(document).ready(function() {
      $('#add-form').on('submit',(function(e) {
        // check extention
        var file = $('#file');
        if (isCSV(file.val())) {
          // lanjutkan proses import
          e.preventDefault();
          var formData = new FormData(this);

          if ($("#add-form").valid()) {
            $.ajax({
              method  : 'POST',
              url     : '<?php echo base_url();?>Administrator/pegawai/import_data',
              data    : formData,
              contentType: false,
              processData: false,
              success: function(data, status, xhr) {
                try {
                  var result = JSON.parse(xhr.responseText);
                  if (result.status == true) {
                    swal(result.message, {
                      icon: "success",
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
                swal("Warning!", "Terjadi kesalahan sistem.", "warning");
              }
            });
          }
        }else{
          // menampilkan error jika file bukan ber-extensi csv
          alert('format harus .csv!')
        }
      }));

      function isCSV(filename) {
        var ext = getExtension(filename);
        switch (ext.toLowerCase()) {
          case 'xlsx':
            //etc
            return true;
        }
        return false;
      }

      function getExtension(filename) {
        var parts = filename.split('.');
        return parts[parts.length - 1];
      }
    });
</script>