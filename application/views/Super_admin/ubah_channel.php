<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo base_url();?>DashboardChannel/getDashboard/<?php echo $data_channel->id_channel;?>">Beranda</a></li>
    <li><a href="<?php echo base_url();?>Akun/getAkun/<?php echo $data_channel->id_channel;?>">Perusahaan</a></li>
    <li class="active">Ubah Data Perusahaan</li>
</ul>
<!-- END BREADCRUMB --> 
<?php
    $message = $this->session->flashdata('notif');
    if ($message) {
        echo '<div class="col-md-12">
            '.$message.'
        </div>';
    }
?>                    

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">
    <div class="CONTAINER">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Ubah Data Perusahaan</h3>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="post" action="javascript:void(0);" id="addForm">
                        <div class="form-group">
                            <label class="col-md-2 control-label">Logo Perusahaan</label>
                            <div class="col-md-7">
                                <input type="file" class="fileinput" name="userfile" title="Upload">
                                <input type="hidden" name="id_channel" value="<?php echo $data_channel->id_channel;?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Nama Perusahaan</label>
                            <div class="col-md-7">
                                <input type="text" class="form-control" name="nama_channel" value="<?php echo $data_channel->nama_channel;?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Alamat</label>
                            <div class="col-md-7">
                                <textarea class="form-control" name="alamat_channel" rows="5"><?php echo $data_channel->alamat_channel;?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">No. Telp</label>
                            <div class="col-md-7">
                                <input type="text" class="form-control" name="telp_channel" value="<?php echo $data_channel->telp_channel;?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">No. Fax</label>
                            <div class="col-md-7">
                                <input type="text" class="form-control" name="fax_channel" value="<?php echo $data_channel->fax_channel;?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Email Perusahaan</label>
                            <div class="col-md-7">
                                <input type="email" class="form-control" name="email_channel" value="<?php echo $data_channel->email_channel;?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Website</label>
                            <div class="col-md-7">
                                <input type="text" class="form-control" name="website_channel" value="<?php echo $data_channel->website_channel;?>">
                            </div>
                        </div>
                </div>
                <div class="panel-footer">
                    <div class="float-right">
                        <button type="submit" class="btn btn-primary btn-sm"><span class="fa fa-save"></span> Simpan</button>
                        </form>
                        <button type="button" class="btn btn-danger btn-sm" onclick="window.location='<?php echo base_url();?>Akun/getAkun/<?php echo $data_channel->id_channel;?>'"><span class="fa fa-ban"></span> Batal</button>
                    </div>
                </div>
            </div>   
        </div>
    </div>
</div>

<audio id="audio-alert" src="<?php echo base_url();?>/assets/audio/alert.mp3" preload="auto"></audio>
<audio id="audio-fail" src="<?php echo base_url();?>/assets/audio/fail.mp3" preload="auto"></audio>

<script type="text/javascript" src="<?php echo base_url();?>/assets/js/plugins/jquery/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>/assets/js/plugins/jquery/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>/assets/js/plugins/bootstrap/bootstrap.min.js"></script>        

<script type='text/javascript' src='<?php echo base_url();?>/assets/js/plugins/icheck/icheck.min.js'></script>
<script type="text/javascript" src="<?php echo base_url();?>/assets/js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>/assets/js/plugins/sweetalert/dist/sweetalert.min.js"></script>

<script type="text/javascript" src="<?php echo base_url();?>/assets/js/plugins/datatables/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>/assets/js/plugins/bootstrap/bootstrap-datepicker.js"></script>                
<script type="text/javascript" src="<?php echo base_url();?>/assets/js/plugins/bootstrap/bootstrap-file-input.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>/assets/js/plugins/bootstrap/bootstrap-select.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>/assets/js/plugins/tagsinput/jquery.tagsinput.min.js"></script>

<script type="text/javascript" src="<?php echo base_url();?>/assets/js/settings.js"></script>

<script type="text/javascript" src="<?php echo base_url();?>/assets/js/plugins.js"></script>        
<script type="text/javascript" src="<?php echo base_url();?>/assets/js/actions.js"></script>        

</body>
</html>

<script>
    var urlInsert = "<?php echo base_url('Akun/insert'); ?>";

    $(document).ready(function() {
        $('#addForm').on('submit',(function(e) {
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
                            window.location.replace("<?php echo base_url();?>Akun/getAkun/<?php echo $data_channel->id_channel;?>");
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