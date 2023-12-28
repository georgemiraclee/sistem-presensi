<link href="<?php echo base_url();?>assets/admin/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
    <link href="<?php echo base_url();?>assets/admin/plugins/multi-select/css/multi-select.css" rel="stylesheet">
<section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Tambah informasi</h2>
            </div>
            <div class="row clearfix">
                <div class="col-md-12">
                    <div class="card">
                        <div class="body">
                            <form class="form-horizontal" id="add-form" action="javascript:void(0);" method="post">
                                <?php echo $this->session->flashdata('pesan'); ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-12">
                                            <div class="col-md-12">
                                                 <div class="form-group form-float">
                                                    <label class="form-label">Jenis Penerima</label>
                                                    <div class="form-line">
                                                        <input name="group1" type="radio" value="1" class="with-gap" id="radio1">
                                                        <label for="radio1">Unit Kerja</label>
                                                        <input name="group1" type="radio" value="2" class="with-gap" id="radio2">
                                                        <label for="radio2">Pegawai</label>
                                                        <input name="group1" type="radio" value="3" class="with-gap" id="radio3">
                                                        <label for="radio3">All</label>
                                                    </div>
                                                </div>
                                                <div id="list_ttp"></div>
                                                <div class="form-group form-float">
                                                    <label class="form-label">Judul</label>
                                                    <div class="form-line">
                                                        <input type="text" id="judul_informasi" class="form-control" name="judul_informasi" required>
                                                    </div>
                                                </div>
                                                <div class="form-group form-float">
                                                    <label class="form-label">Pesan</label>
                                                    <div class="form-line">
                                                        <textarea class="form-control" name="deskripsi_informasi"></textarea>
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
                                <button class="btn btn-primary" type="submit"><span class="fa fa-save"></span> Simpan</button>
                                <button class="btn btn-default" type="button" onclick="window.location='<?php echo base_url();?>Administrator/informasi'"><span class="fa fa-ban"></span> Batal</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="<?php echo base_url();?>assets/admin/plugins/bootstrap-select/js/bootstrap-select.js"></script>

    <script src="<?php echo base_url();?>assets/admin/plugins/multi-select/js/jquery.multi-select.js"></script>

    <script src="<?php echo base_url();?>assets/admin/js/pages/forms/advanced-form-elements.js"></script>

    <script>
    var urlInsert = "<?php echo base_url('Administrator/informasi/insert'); ?>";
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
                          }).then((acc) => {
                            window.location='<?php echo base_url();?>Administrator/informasi';
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
<script type="text/javascript">
    $(document).ready(function(){
        $('#radio1').change(function() {
            var e = $('#radio1').val();
            $.ajax({
                success: function(html){
                    $('#list_ttp').load(path+"Administrator/informasi/list_ttp/"+e);
                }
            });
        });
    });
    $(document).ready(function(){
        $('#radio2').change(function() {
            var e = $('#radio2').val();
            $.ajax({
                success: function(html){
                    $('#list_ttp').load(path+"Administrator/informasi/list_ttp/"+e);
                }
            });
        });
    });
    $(document).ready(function(){
        $('#radio3').change(function() {
            var e = $('#radio3').val();
            $.ajax({
                success: function(html){
                    $('#list_ttp').load(path+"Administrator/informasi/list_ttp/"+e);
                }
            });
        });
    });
    var path = "<?php echo base_url();?>";
</script>