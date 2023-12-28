                     

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
                            <button class="btn btn-primary btn-lg" data-toggle='modal' data-target='#addUnit'>Tambah Unit</button>
                            <div class="panel-heading">
                            </div> 
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <table class="table datatable">
                                        <thead>
                                            <tr>
                                                <th>Id Unit</th>
                                                <th>Nama unit</th>
                                                <th>Tanggal pembuatan</th>
                                                <th>Aksi</th>     
                                            </tr>
                                        </thead>
                                              <?php foreach ($select_admin_unit as $key => $value) { ?>
                                            <tr>
                                                <th><?php echo $value->id_unit ?></th>
                                                <th><?php echo $value->nama_unit ?></th>
                                                <th><?php echo $value->created_unit ?></th>
                                                <th><button class=" btn btn-primary"  data-toggle='modal' data-target='#modal_basic<?php echo $value->id_unit ?> '>Ubah</button> <button type='button' class='btn btn-danger mb-control' data-box='#message-box-sound-2<?php echo $value->id_unit ?>'>Hapus</button></th>
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

                    <div class='modal' id='addUnit' tabindex='-1' role='dialog' aria-labelledby='defModalHead' aria-hidden='true'>
                        <div class='modal-dialog'>
                            <div class='modal-content'>
                                <div class='modal-header'>
                                    <button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span></button>
                                    <h4 class='modal-title' id='defModalHead'>Tambah Data</h4>
                                </div>
                                <div class='modal-body'>
                                    <form class="form-horizontal"  enctype="multipart/form-data"  action="<?php echo base_url();?>Data_unit/insert" method="post">
                                        <div class="form-group">
                                            <label class="col-md-3 col-xs-12 control-label">Nama Unit</label>
                                            <div class="col-md-6 col-xs-12">                                            
                                                <div class="input-group">
                                                    <span class="input-group-addon"><span class="fa fa-user"></span></span>
                                                    <input type="text" name="nama_unit"  id="nama_unit" class="form-control" pattern="[a-z A-Z 0-9]" oninvalid="setCustomValidity('Gunakan karakter yang valid dan tidak boleh kosong')" oninput="this.setCustomValidity('')" required>
                                                </div>                                            
                                            </div>
                                        </div>
                                        <div class="form-group">
                                             <label class="col-md-3 col-xs-12 control-label">Ikon</label>
                                                <div class="col-md-6 col-xs-12">
                                                    <input type="file" class="fileinput btn-primary" name="userfile" id="userfile" title="Upload foto"/>
                                                    <span class="help-block">max size 2mb (JPG,PNG atau JPEG)</span>
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
                      <?php foreach ($select_admin_unit as $key => $value) { ?>
                        <div class="message-box message-box-danger animated fadeIn"  data-sound="fail" id="message-box-sound-2<?php echo $value->id_unit ?>">
                            <div class="mb-container">
                                <div class="mb-middle">
                                    <div class="mb-title"><span class="fa fa-times"></span> peringatan!</div>
                                    <div class="mb-content">
                                        <p>Jika anda menghapus data ini akun ini tidak akan bisa di pakai kembalu , Apa anda yakin ingin menghapusnya ?.</p>                    
                                    </div>
                                    <div class="mb-footer">
                                        <div class="float-right">
                                            <a href="<?php echo base_url();?>Data_unit/delete/<?php echo $value->id_unit ?>" class="btn btn-success btn-lg">Ya</a>
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
                        <div class='modal' id='modal_basic<?php echo $value->id_unit ?>' tabindex='-1' role='dialog' aria-labelledby='defModalHead' aria-hidden='true'>
                            <div class='modal-dialog'>
                                <div class='modal-content'>
                                    <div class='modal-header'>
                                        <button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span></button>
                                        <h4 class='modal-title' id='defModalHead'>Ubah Data</h4>
                                    </div>
                                    <div class='modal-body'>
                                        <form class='form-horizontal' enctype="multipart/form-data"  action='<?php echo base_url();?>Data_unit/update/<?php echo $value->id_unit ?>' method='post'>
                                             <div class="form-group">
                                                <label class="col-md-3 col-xs-12 control-label">Nama Unit</label>
                                                <div class="col-md-9 col-xs-12">                                            
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><span class="fa fa-user"></span></span>
                                                        <input type="text" name="nama_unit"  id="nama_unit" value="<?php echo $value->nama_unit ?>" class="form-control" pattern="[a-z A-Z 0-9]" oninvalid="setCustomValidity('Gunakan karakter yang valid dan tidak boleh kosong')" oninput="this.setCustomValidity('')" required>
                                                    </div>                                            
                                                </div>
                                            </div> 
                                            <div class="form-group">
                                                    <label class="col-md-3 col-xs-12 control-label">Ikon Unit</label>
                                                    <div class="col-md-9 col-xs-12">
                                                        <input type="file" class="fileinput btn-primary" name="ikon_unit" id="ikon_unit" title="Upload foto"/>
                                                        <span class="help-block">Abaikan jika tidak ingin mengganti Foto</span>
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






