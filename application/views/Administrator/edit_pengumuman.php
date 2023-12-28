<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-md-12">
                    <h1 class="m-0 text-dark">Edit Pengumuman</h1>
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
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-12">
                                        <div class="form-group form-float">
                                            <label class="form-label"><span class="text-danger">*</span> Judul</label>
                                            <div class="form-line">
                                              <input type="text" id="judul_pengumuman" class="form-control" name="judul_pengumuman" value="<?php echo $item->judul_pengumuman;?>" required>
                                              <input type="hidden" id="id_pengumuman" class="form-control" name="id_pengumuman" value="<?php echo $item->id_pengumuman;?>" required>
                                            </div>
                                        </div>
                                        <div class="form-group form-float">
                                          <label class="form-label"><span class="text-danger">*</span> Pesan</label>
                                          <div class="form-line">
                                            <textarea class="form-control" name="deskripsi_pengumuman"><?php echo $item->deskripsi_pengumuman;?></textarea>
                                            <span class="text-danger" style="font-size: 12px;">* Lewati jika tidak mengubah foto</span>
                                          </div>
                                        </div>
                                        <div class="form-group form-float">
                                          <label class="form-label"><span class="text-danger">*</span> Tanggal Mulai Pengumuman</label>
                                          <label class="col-md-6 mb-2 text-right ml-4"><span class="text-danger">*</span>Tanggal Berakhir Pengumuman</label>
                                          <div class="row">
                                            <div class="col-6">
                                              <div class="form-line">
                                                <input type="date" class="form-control" value="<?php echo $item->start_date;?>" id="start_date" name="start_date" required>
                                              </div>
                                            </div>
                                            <div class="col-6">
                                              <div class="form-line">
                                                <input type="date" class="form-control" value="<?php echo $item->end_date;?>" id="end_date" name="end_date" required>
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
                                <a href="<?php echo base_url();?>Administrator/pengumuman" class="btn btn-default" type="button"><span class="fa fa-ban"></span> Batal</a>
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
  var urlUpdate = "<?php echo base_url('Administrator/pengumuman/update'); ?>";
  $(document).ready(function() {
    $('.js-example-basic-multiple').select2();
    
    $('#add-form').on('submit',(function(e) {
      e.preventDefault();
      var formData = new FormData(this);
      $.ajax({
        method  : 'POST',
        url     : urlUpdate,
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