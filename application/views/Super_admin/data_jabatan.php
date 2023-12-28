<!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="#">Menu</a></li>                    
        <li class="active">Data Jaringan</li>
    </ul>
<!-- END BREADCRUMB --> 
<?php
    $message = $this->session->flashdata('notif');
    if ($message) {
        echo '<div class="col-md-12">
            '.$message.'
        </div>
        ';
    }
?>                      

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">
    <div class="CONTAINER">
        <div class="col-md-12">
            <button class="btn btn-primary btn-lg" data-toggle='modal' data-target='#addUnit'><span class="fa fa-plus"></span> Tambah Jabatan</button>
            <div class="panel-heading"></div> 
            <div class="panel panel-default">
                <div class="panel-body">
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Jabatan</th>
                                <th>Status</th>
                                <th>Aksi</th>     
                            </tr>
                        </thead>
                        <?php foreach ($data_jabatan as $key => $value) { 
                            if ($value->is_aktif == 1) {
                                $value->is_aktif = '<span class="label label-success label-form">Aktif</span>';
                            }else{
                                $value->is_aktif = '<span class="label label-danger label-form">Tidak Aktif</span>';
                            }
                        ?>
                            <tr>
                                <th><?php echo $key+1 ?></th>
                                <th width="500"><?php echo $value->nama_jabatan ?></th>
                                <th width="250"><?php echo $value->is_aktif ?></th>
                                <th>
                                    <button class=" btn btn-primary"  data-toggle='modal' data-target='#modal_basic<?php echo $value->id_jabatan ?> '>Ubah</button>
                                    <button type='button' class='btn btn-danger mb-control' data-box='#message-box-sound-2<?php echo $value->id_jabatan ?>'>Hapus</button>
                                </th>
                            </tr>
                        <?php } ?>
                        <tbody></tbody>
                    </table>
                </div>
            </div>   
        </div>
    </div>
</div>

<div class='modal' id='addUnit' tabindex='-1' role='dialog' aria-labelledby='defModalHead' aria-hidden='true'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal'>
                    <span aria-hidden='true'>&times;</span>
                    <span class='sr-only'>Close</span>
                </button>
                <h4 class='modal-title' id='defModalHead'>Tambah Data</h4>
            </div>
            <div class='modal-body'>
                <form class="form-horizontal" id="add-form" action="javascript:void(0);">
                    <div class="form-group">
                        <label class="col-md-3 col-xs-12 control-label">Nama Jabatan</label>
                        <div class="col-md-6 col-xs-12">                                            
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <span class="fa fa-user"></span>
                                </span>
                                <input type="text" name="nama_jabatan"  id="nama_jabatan" class="form-control" oninvalid="setCustomValidity('Gunakan karakter yang valid dan tidak boleh kosong')" oninput="this.setCustomValidity('')" required>
                            </div>                                            
                        </div>
                    </div>
            </div>
            <div class='modal-footer'>
                <button class='btn btn-primary'><span class="fa fa-save"></span> Simpan</button>
                </form>
                <button type='button' class='btn btn-warning' data-dismiss='modal'><span class="fa fa-ban"></span> Batal</button>
            </div>
        </div>
    </div>
</div>

<!-- danger with sound -->
<?php foreach ($data_jabatan as $key => $value) { ?>
    <div class="message-box message-box-danger animated fadeIn"  data-sound="fail" id="message-box-sound-2<?php echo $value->id_jabatan ?>">
        <div class="mb-container">
            <div class="mb-middle">
                <div class="mb-title"><span class="fa fa-times"></span> peringatan!</div>
                <div class="mb-content">
                    <p>Jika anda menghapus data ini akun ini tidak akan bisa di pakai kembalu , Apa anda yakin ingin menghapusnya ?.</p>                    
                </div>
                <div class="mb-footer">
                    <div class="float-right">
                        <a href="<?php echo base_url();?>Data_unit/delete/<?php echo $value->id_jabatan ?>" class="btn btn-success btn-lg">Ya</a>
                        <button class="btn btn-default btn-lg mb-control-close">Tidak</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<!-- end danger with sound -->
<!-- Modal Edit -->
<?php foreach ($data_jabatan as $key => $value) { ?>
    <div class='modal' id='modal_basic<?php echo $value->id_jabatan ?>' tabindex='-1' role='dialog' aria-labelledby='defModalHead' aria-hidden='true'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span></button>
                    <h4 class='modal-title' id='defModalHead'>Ubah Data</h4>
                </div>
                <div class='modal-body'>
                    <form class='form-horizontal' id="update-form" action="javascript:void(0);">
                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label">Nama Jabatan</label>
                            <div class="col-md-9 col-xs-12">                                            
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="fa fa-user"></span></span>
                                    <input type="text" name="nama_jabatan"  id="nama_jabatan" value="<?php echo $value->nama_jabatan ?>" class="form-control" oninvalid="setCustomValidity('Gunakan karakter yang valid dan tidak boleh kosong')" oninput="this.setCustomValidity('')" required>
                                    <input type="hidden" name="id_jabatan" value="<?php echo $value->id_jabatan;?>">
                                </div>                                            
                            </div>
                        </div>
                </div>
                <div class='modal-footer'>
                    <button class='btn btn-primary'><span class="fa fa-save"></span> Simpan</button>
                    </form>
                    <button type='button' class='btn btn-warning' data-dismiss='modal'><span class="fa fa-ban"></span> Batal</button>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<!-- Modal Edit -->
</div>
<!-- END PAGE CONTENT WRAPPER -->                                
</div>            
<!-- END PAGE CONTENT -->
</div>
<!-- END PAGE CONTAINER -->



<audio id="audio-alert" src="<?php echo base_url();?>/assets/audio/alert.mp3" preload="auto"></audio>
<audio id="audio-fail" src="<?php echo base_url();?>/assets/audio/fail.mp3" preload="auto"></audio>
<!-- END PRELOADS -->          

<!-- START SCRIPTS -->
<!-- START PLUGINS -->
<script type="text/javascript" src="<?php echo base_url();?>/assets/js/plugins/jquery/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>/assets/js/plugins/jquery/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>/assets/js/plugins/bootstrap/bootstrap.min.js"></script>

<script type="text/javascript" src="<?php echo base_url();?>/assets/js/plugins/sweetalert/dist/sweetalert.min.js"></script>     
<!-- END PLUGINS -->

<!-- START THIS PAGE PLUGINS-->        
<script type='text/javascript' src='<?php echo base_url();?>/assets/js/plugins/icheck/icheck.min.js'></script>
<script type="text/javascript" src="<?php echo base_url();?>/assets/js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js"></script>
<!-- END THIS PAGE PLUGINS-->        
<script type="text/javascript" src="<?php echo base_url();?>/assets/js/plugins/datatables/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>/assets/js/plugins/bootstrap/bootstrap-datepicker.js"></script>                
<script type="text/javascript" src="<?php echo base_url();?>/assets/js/plugins/bootstrap/bootstrap-file-input.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>/assets/js/plugins/bootstrap/bootstrap-select.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>/assets/js/plugins/tagsinput/jquery.tagsinput.min.js"></script>
<!-- START TEMPLATE -->
<script type="text/javascript" src="<?php echo base_url();?>/assets/js/settings.js"></script>

<script type="text/javascript" src="<?php echo base_url();?>/assets/js/plugins.js"></script>        
<script type="text/javascript" src="<?php echo base_url();?>/assets/js/actions.js"></script>        
<!-- END TEMPLATE -->
<!-- END SCRIPTS -->         
</body>
</html>

<script>
    var urlInsert = "<?php echo base_url('Jabatan/insert'); ?>";
    var urlUpdate = "<?php echo base_url('Jabatan/update'); ?>";
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
                            location.reload();
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

        $('#update-form').on('submit',(function(e) {
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
                          }).then((acc) => {
                            location.reload();
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