                <!-- START BREADCRUMB -->
                <ul class="breadcrumb">
                    <li><a href="#">Menu</a></li>                    
                    <li class="active">Data Area</li>
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
                            <button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#modal_basic">Tambah Area</button>
                            <div class="panel-heading"></div>
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <table class="table datatable">
                                        <thead>
                                            <tr>
                                                <th>Id Lokasi</th>
                                                <th>Nama Lokasi</th>
                                                <th>Url</th>
                                                <th>Aksi</th>     
                                            </tr>
                                        </thead>
                                              <?php foreach ($select_admin_unit as $key => $value) { ?>
                                            <tr>
                                                <th><?php echo $value->id_lokasi ?></th>
                                                <th><?php echo $value->nama_lokasi ?></th>
                                                <th><?php echo $value->url_file_lokasi ?></th>
                                                <th><button class=" btn btn-primary"  data-toggle='modal' data-target='#Newmodal_basic<?php echo $value->id_lokasi ?> '>Ubah</button> <button type='button' class='btn btn-danger mb-control' data-box='#message-box-sound-2<?php echo $value->id_lokasi ?>'>Hapus</button></th>
                                            </tr>
                                             <?php } ?>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>   
                        </div>
                    </div>
                </div>

                <div class="modal" id="modal_basic" tabindex="-1" role="dialog" aria-labelledby="defModalHead" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                <h4 class="modal-title" id="defModalHead">Tambah Area</h4>
                            </div>
                            <div class="modal-body">
                                <form method="post" id="add-form" action="javascript:void(0);">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label><small>Nama Wilayah</small></label>
                                            <input type="text" class="form-control" name="nama_lokasi" required>
                                        </div>
                                        <div class="form-group">
                                            <label><small>File Kordinat Wilayah <code>(.csv)</code></small></label>
                                            <input type="file" name="userfile">
                                        </div>
                                    </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-ban"></span> Batal</button>
                                <button type="submit" class="btn btn-primary ladda-button" id="add-btn" data-style="zoom-in">Submit</button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
                    <!-- danger with sound -->
                      <?php foreach ($select_admin_unit as $key => $value) { ?>
                        <div class="message-box message-box-danger animated fadeIn"  data-sound="fail" id="message-box-sound-2<?php echo $value->id_lokasi ?>">
                            <div class="mb-container">
                                <div class="mb-middle">
                                    <div class="mb-title"><span class="fa fa-times"></span> Peringatan!</div>
                                    <div class="mb-content">
                                        <p>Jika anda menghapus data ini akun ini tidak akan bisa di pakai kembalu , Apa anda yakin ingin menghapusnya ?.</p>                    
                                    </div>
                                    <div class="mb-footer">
                                        <div class="float-right">
                                            <a href="<?php echo base_url();?>Data_jaringan/delete/<?php echo $value->id_lokasi ?>" class="btn btn-success btn-lg">Ya</a>
                                            <button class="btn btn-default btn-lg mb-control-close">Tidak</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                        <!-- end danger with sound -->
                        <!-- Modal Edit -->
                        <?php foreach ($select_admin_unit as $key => $value) { ?>
                        <div class='modal' id='Newmodal_basic<?php echo $value->id_lokasi ?>' tabindex='-1' role='dialog' aria-labelledby='defModalHead' aria-hidden='true'>
                            <div class='modal-dialog'>
                                <div class='modal-content'>
                                    <div class='modal-header'>
                                        <button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span></button>
                                        <h4 class='modal-title' id='defModalHead'>Ubah Data</h4>
                                    </div>
                                    <div class='modal-body'>
                                        <form class='form-horizontal' method="post" id="update-form" action="javascript:void(0);">
                                             <div class="form-group">
                                                <label class="col-md-3 col-xs-12 control-label">Nama Lokasi</label>
                                                <div class="col-md-9 col-xs-12">                                            
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><span class="fa fa-user"></span></span>
                                                        <input type="text" name="nama_lokasi"  id="nama_lokasi" value="<?php echo $value->nama_lokasi ?>" class="form-control" pattern="^[a-z A-Z 0-9]*$" oninvalid="setCustomValidity('Gunakan karakter yang valid dan tidak boleh kosong')" oninput="this.setCustomValidity('')" required>
                                                        <input type="hidden" name="id_lokasi" value="<?php echo $value->id_lokasi;?>">
                                                    </div>                                            
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 col-xs-12 control-label"><small>File Kordinat Wilayah <code>(.csv)</code></small></label>
                                                <div class="col-md-9 col-xs-12">
                                                    <input type="file" name="userfile">
                                                    <code>* kosongkan jika tidak diubah</code>
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
    var urlInsert = "<?php echo base_url('Data_jaringan/insertArea'); ?>";
    var urlUpdate = "<?php echo base_url('Data_jaringan/updateArea'); ?>";
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
                            location.reload();
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