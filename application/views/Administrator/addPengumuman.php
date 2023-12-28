<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-12">
                    <h1 class="m-0 text-dark">Tambah Pengumuman</h1>
                </div>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <form class="form-horizontal" id="add-form" action="javascript:void(0);" method="post">                                
                        <div class="card-body">
                            <?php echo $this->session->flashdata('pesan'); ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <h2 class="card-inside-title">Target</h2>
                                    <div class="col-md-12">
                                        <?php echo $html;?>
                                    </diV>
                                </div>
                                <div class="col-md-12">
                                    <h2 class="card-inside-title">Pengumuman</h2>
                                    <div class="col-md-12">
                                        <div class="form-group form-float">
                                            <label class="form-label"><span class="text-danger">*</span> Judul</label>
                                            <div class="form-line">
                                                <input type="text" id="judul_pengumuman" class="form-control" placeholder="Masukkan Judul Pengumuman" name="judul_pengumuman" required>
                                                <input type="hidden" name="idType" value="<?php echo $id;?>">
                                            </div>
                                        </div>
                                        <div class="form-group form-float">
                                            <label class="form-label"><span class="text-danger">*</span> Pesan</label>
                                            <div class="form-line">
                                                <textarea class="form-control" name="deskripsi_pengumuman" placeholder="Masukkan Deskripsi Pengumuman" required></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group form-float">
                                          <label class="form-label"><span class="text-danger">*</span> Tanggal Mulai Pengumuman</label>
                                          <label class="col-md-6 mb-2 text-right ml-4"><span class="text-danger">*</span>Tanggal Berakhir Pengumuman</label>
                                          <div class="row">
                                            <div class="col-6">
                                              <div class="form-line">
                                                <input type="date" class="form-control" min="<?php echo date('Y-m-d');?>" id="start_date" name="start_date" required>
                                              </div>
                                            </div>
                                            <div class="col-6">
                                              <div class="form-line">
                                                <input type="date" class="form-control" min="<?php echo date('Y-m-d');?>" id="end_date" name="end_date" required>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="form-group form-float">
                                            <label class="form-label">File</label>
                                            <div class="form-line">
                                                <input type="file" name="userfile">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="float-right">
                                <button class="btn btn-primary" type="submit"><span class="fa fa-save"></span> Simpan</button>
                                <button class="btn btn-default" type="button" onclick="window.location='<?php echo base_url();?>Administrator/pengumuman'"><span class="fa fa-ban"></span> Batal</button>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
  var urlInsert = "<?php echo base_url('Administrator/pengumuman/insert'); ?>";
  $(document).ready(function() {
    $('.js-example-basic-multiple').select2();
    
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
            if (result.status) {
              swal(result.message, {
                icon: "success",
              }).then((acc) => {
                window.location='<?php echo base_url();?>Administrator/pengumuman';
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