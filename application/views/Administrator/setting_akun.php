<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-md-12">
        </div>
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <?php echo $head;?>
            </div>
            <div class="card-body">
              <?php echo $list;?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<script>
  var urlInsert = "<?php echo base_url('Administrator/setting_akun/update'); ?>";
  $(document).ready(function() {
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
              }).then((acc) => {
                window.location='<?php echo base_url();?>Administrator/setting_akun';
              });
            } else {
              swal("Warning!", result.message, "warning");
            }
          } catch (e) {
            swal("Warning!", "Sistem error.", "warning");
          }
        },
        error: function(data) {
          swal("Warning!", "Terjadi kesalahan sistem", "warning");
        }
      });
    }));
  });
</script>