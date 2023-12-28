                     

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
                            <div class="panel panel-default tabs">
                                <ul class="nav nav-tabs nav-justified">
                                    <li class="active"><a href="#tab8" data-toggle="tab">List Data Jaringan</a></li>
                                    <li><a href="#tab9" data-toggle="tab">Insert Data Jaringan</a></li>
                                </ul>
                                <div class="panel-body tab-content">
                                    <div class="tab-pane active" id="tab8">
                                        <table class="table datatable">
                                            <thead>
                                                <tr>
                                                    <th>Id jaringan</th>
                                                    <th>SSID jaringan</th>
                                                    <th>Lokasi jaringan</th>
                                                    <th>Tanggal pembuatan</th>
                                                    <th>Aksi</th>     
                                                </tr>
                                            </thead>
                                                  <?php foreach ($select_admin_unit as $key => $value) { ?>
                                                <tr>
                                                    <th><?php echo $value->id_jaringan ?></th>
                                                    <th><?php echo $value->ssid_jaringan ?></th>
                                                    <th><?php echo $value->created_jaringan ?></th>
                                                    <th><?php echo $value->lokasi_jaringan ?></th>
                                                    <th><button class=" btn btn-primary"  data-toggle='modal' data-target='#modal_basic<?php echo $value->id_jaringan ?> '>Ubah</button> <button type='button' class='btn btn-danger mb-control' data-box='#message-box-sound-2<?php echo $value->id_jaringan ?>'>Hapus</button></th>
                                                </tr>
                                                 <?php } ?>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                     <div class="tab-pane" id="tab9">
                                        <form class="form-horizontal"  enctype="multipart/form-data"  action="<?php echo base_url();?>Data_jaringan/insert" method="post">
                                             <div class="form-group">
                                                <label class="col-md-3 col-xs-12 control-label">SSID Jaringan</label>
                                                <div class="col-md-6 col-xs-12">                                            
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><span class="fa fa-user"></span></span>
                                                        <input type="text" name="ssid_jaringan"  id="ssid_jaringan" class="form-control" pattern="^[a-z A-Z 0-9]*$" oninvalid="setCustomValidity('Gunakan karakter yang valid dan tidak boleh kosong')" oninput="this.setCustomValidity('')" required>
                                                    </div>                                            
                                                </div>
                                            </div>
                                            <div class="form-group"> 
                                                <label class="col-md-3 col-xs-12 control-label">Lokasi Jaringan</label>
                                                <div class="col-md-6 col-xs-12">                                            
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><span class="fa fa-user"></span></span>
                                                        <input type="text" name="lokasi_jaringan"  id="lokasi_jaringan" class="form-control" pattern="^[a-z A-Z 0-9]*$" oninvalid="setCustomValidity('Gunakan karakter yang valid dan tidak boleh kosong')" oninput="this.setCustomValidity('')" required>
                                                    </div>                                            
                                                </div>
                                            </div> 
                                            <button class="btn btn-default">Clear Form</button>                                    
                                            <button class="btn btn-primary float-right" type="submit"><span class="fa fa-save"></span> Simpan</button>
                                            </form>                               
                                        </div>
                                    </div>
                                </div>   
                            </div>
                        </div>
                    </div>
                    <!-- danger with sound -->
                      <?php foreach ($select_admin_unit as $key => $value) { ?>
                        <div class="message-box message-box-danger animated fadeIn"  data-sound="fail" id="message-box-sound-2<?php echo $value->id_jaringan ?>">
                            <div class="mb-container">
                                <div class="mb-middle">
                                    <div class="mb-title"><span class="fa fa-times"></span> peringatan!</div>
                                    <div class="mb-content">
                                        <p>Jika anda menghapus data ini akun ini tidak akan bisa di pakai kembalu , Apa anda yakin ingin menghapusnya ?.</p>                    
                                    </div>
                                    <div class="mb-footer">
                                        <div class="float-right">
                                            <a href="<?php echo base_url();?>Data_jaringan/delete/<?php echo $value->id_jaringan ?>" class="btn btn-success btn-lg">Ya</a>
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
                        <div class='modal' id='modal_basic<?php echo $value->id_jaringan ?>' tabindex='-1' role='dialog' aria-labelledby='defModalHead' aria-hidden='true'>
                            <div class='modal-dialog'>
                                <div class='modal-content'>
                                    <div class='modal-header'>
                                        <button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span></button>
                                        <h4 class='modal-title' id='defModalHead'>Ubah Data</h4>
                                    </div>
                                    <div class='modal-body'>
                                        <form class='form-horizontal' enctype="multipart/form-data"  action='<?php echo base_url();?>Data_jaringan/update/<?php echo $value->id_jaringan ?>' method='post'>
                                             <div class="form-group">
                                                <label class="col-md-3 col-xs-12 control-label">SSID jaringan</label>
                                                <div class="col-md-9 col-xs-12">                                            
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><span class="fa fa-user"></span></span>
                                                        <input type="text" name="ssid_jaringan"  id="ssid_jaringan" value="<?php echo $value->ssid_jaringan ?>" class="form-control" pattern="^[a-z A-Z 0-9]*$" oninvalid="setCustomValidity('Gunakan karakter yang valid dan tidak boleh kosong')" oninput="this.setCustomValidity('')" required>
                                                    </div>                                            
                                                </div>
                                            </div>
                                             <div class="form-group">
                                                <label class="col-md-3 col-xs-12 control-label">Lokasi jaringan</label>
                                                <div class="col-md-9 col-xs-12">                                            
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><span class="fa fa-user"></span></span>
                                                        <input type="text" name="lokasi_jaringan"  id="lokasi_jaringan" value="<?php echo $value->lokasi_jaringan ?>" class="form-control" pattern="^[a-z A-Z 0-9]*$" oninvalid="setCustomValidity('Gunakan karakter yang valid dan tidak boleh kosong')" oninput="this.setCustomValidity('')" required>
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






