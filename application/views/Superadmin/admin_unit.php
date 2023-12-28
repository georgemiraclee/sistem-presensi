                     

                <!-- START BREADCRUMB -->
                <ul class="breadcrumb">
                    <li><a href="#">Menu</a></li>                    
                    <li>Data User</li>
                    <li class="active">Admin unit</li>
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
                                    <li class="active"><a href="#tab8" data-toggle="tab">List Data Admin Unit</a></li>
                                    <li><a href="#tab9" data-toggle="tab">Insert Data Admin Unit</a></li>
                                </ul>
                                <div class="panel-body tab-content">
                                    <div class="tab-pane active" id="tab8">
                                        <table class="table datatable">
                                            <thead>
                                                <tr>
                                                    <th>Nip</th>
                                                    <th>Nama</th>
                                                    <th>Jabatan</th>
                                                    <th>email</th>
                                                    <th>Telepon</th>
                                                    <th>Alamat</th>
                                                    <th>Unit</th>
                                                    <th>Foto</th>
                                                    <th>Aksi</th>     
                                                </tr>
                                            </thead>
                                                  <?php foreach ($select_admin_unit as $key => $value) { ?>
                                                <tr>
                                                    <th><?php echo $value->nip ?></th>
                                                    <th><?php echo $value->nama_user ?></th>
                                                    <th><?php echo $value->jabatan ?></th>
                                                    <th><?php echo $value->email_user ?></th>
                                                    <th><?php echo $value->telp_user ?></th>
                                                    <th><?php echo $value->alamat_user ?></th>
                                                    <th><?php echo $value->nama_unit ?></th>
                                                    <th><img src="<?php echo base_url(); ?>assets/upload/<?php echo $value->foto_user ?>" style="max-width: 50px"></th>
                                                    <th><button class=" btn btn-primary"  data-toggle='modal' data-target='#modal_basic<?php echo $value->nip ?> '>Ubah</button><button type='button' class='btn btn-danger mb-control' data-box='#message-box-sound-2<?php echo $value->nip ?>'>Hapus</button></th>
                                                </tr>
                                                 <?php } ?>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                     <div class="tab-pane" id="tab9">
                                        <form class="form-horizontal"  enctype="multipart/form-data"  action="<?php echo base_url();?>Admin_unit/insert" method="post">
                                             <div class="form-group">
                                                <label class="col-md-3 col-xs-12 control-label">Nama Lengkap</label>
                                                <div class="col-md-6 col-xs-12">                                            
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><span class="fa fa-user"></span></span>
                                                        <input type="text" name="nama_user"  id="nama_user" class="form-control" pattern="^[a-z A-Z 0-9]*$" oninvalid="setCustomValidity('Gunakan karakter yang valid dan tidak boleh kosong')" oninput="this.setCustomValidity('')" required>
                                                    </div>                                            
                                                </div>
                                            </div> 
                                            <div class="form-group">
                                                <label class="col-md-3 col-xs-12 control-label">Email</label>
                                                <div class="col-md-6 col-xs-12">                                            
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><span class="fa fa-envelope"></span></span>
                                                        <input type="email" name="email_user"  id="email_user" class="form-control" oninvalid="setCustomValidity('Gunakan karakter yang valid dan tidak boleh kosong')" oninput="this.setCustomValidity('')" required>
                                                    </div>                                            
                                                </div>
                                            </div> 
                                            <div class="form-group">
                                                <label class="col-md-3 col-xs-12 control-label">Telepon</label>
                                                <div class="col-md-6 col-xs-12">                                            
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><span class="fa fa-phone"></span></span>
                                                        <input type="text" name="telp_user"  id="telp_user" class="form-control" pattern="^[a-z A-Z 0-9]*$" oninvalid="setCustomValidity('Gunakan karakter yang valid dan tidak boleh kosong')" oninput="this.setCustomValidity('')" required>
                                                    </div>                                            
                                                </div>
                                            </div>
                                             <div class="form-group">
                                                <label class="col-md-3 col-xs-12 control-label">Alamat</label>
                                                <div class="col-md-6 col-xs-12">                                            
                                                    <textarea class="form-control" rows="5" name="alamat_user"  id="alamat_user"></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                    <label class="col-md-3 col-xs-12 control-label">Foto</label>
                                                    <div class="col-md-6 col-xs-12">
                                                        <input type="file" class="fileinput btn-primary" name="userfile" id="userfile" title="Upload foto"/>
                                                        <span class="help-block">max sixw 2mb (JPG,PNG atau JPEG)</span>
                                                    </div>
                                                </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Unit</label>
                                                <div class="col-md-6 col-xs-12">                                         
                                                    <select class="form-control select" name="id_unit"  id="id_unit">
                                                        <option></option>
                                                        <?php foreach ($data_unit as $key => $value) { ?>
                                                        <option value="<?php echo $value->id_unit;?>"><?php echo $value->nama_unit;?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 col-xs-12 control-label">Jabatan</label>
                                                <div class="col-md-6 col-xs-12">                                            
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><span class="fa fa-bookmark"></span></span>
                                                        <input type="text" name="jabatan"  id="jabatan" class="form-control" pattern="^[a-z A-Z 0-9]*$" oninvalid="setCustomValidity('Gunakan karakter yang valid dan tidak boleh kosong')" oninput="this.setCustomValidity('')" required>
                                                    </div>                                            
                                                </div>
                                            </div> 
                                            <div class="form-group">
                                                <label class="col-md-3 col-xs-12 control-label">Nip</label>
                                                <div class="col-md-6 col-xs-12">                                            
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><span class="fa fa-pencil"></span></span>
                                                        <input type="text" name="nip"  id="nip" class="form-control" pattern="^[a-z A-Z 0-9]*$" oninvalid="setCustomValidity('Gunakan karakter yang valid dan tidak boleh kosong')" oninput="this.setCustomValidity('')" required>
                                                    </div>                                            
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 col-xs-12 control-label">Password</label>
                                                <div class="col-md-6 col-xs-12">                                            
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><span class="fa fa-lock"></span></span>
                                                        <input type="password" name="password_user"  id="password_user" class="form-control" oninvalid="setCustomValidity('Gunakan karakter yang valid dan tidak boleh kosong')" oninput="this.setCustomValidity('')" required>
                                                    </div>                                            
                                                </div>
                                            </div> 
                                             <input type="hidden" name="is_admin"  id="is_admin" value="1" class="form-control"/>
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
                        <div class="message-box message-box-danger animated fadeIn" data-sound="fail" id="message-box-sound-2<?php echo $value->nip ?>">
                            <div class="mb-container">
                                <div class="mb-middle">
                                    <div class="mb-title"><span class="fa fa-times"></span> peringatan!</div>
                                    <div class="mb-content">
                                        <p>Jika anda menghapus data ini akun ini tidak akan bisa di pakai kembalu , Apa anda yakin ingin menghapusnya ?.</p>                    
                                    </div>
                                    <div class="mb-footer">
                                        <div class="float-right">
                                            <a href="<?php echo base_url();?>Admin_unit/delete/<?php echo $value->nip ?>" class="btn btn-success btn-lg">Ya</a>
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
                        <div class='modal' id='modal_basic<?php echo $value->nip ?>' tabindex='-1' role='dialog' aria-labelledby='defModalHead' aria-hidden='true'>
                            <div class='modal-dialog'>
                                <div class='modal-content'>
                                    <div class='modal-header'>
                                        <button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span></button>
                                        <h4 class='modal-title' id='defModalHead'>Ubah Data</h4>
                                    </div>
                                    <div class='modal-body'>
                                        <form class='form-horizontal' enctype="multipart/form-data" action='<?php echo base_url();?>Admin_unit/update/<?php echo $value->nip ?>' method='post'>
                                             <div class="form-group">
                                                <label class="col-md-3 col-xs-12 control-label">Nama Lengkap</label>
                                                <div class="col-md-9 col-xs-12">                                            
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><span class="fa fa-user"></span></span>
                                                        <input type="text" name="nama_user"  id="nama_user" value="<?php echo $value->nama_user ?>" class="form-control" pattern="^[a-z A-Z 0-9]*$" oninvalid="setCustomValidity('Gunakan karakter yang valid dan tidak boleh kosong')" oninput="this.setCustomValidity('')" required>
                                                    </div>                                            
                                                </div>
                                            </div> 
                                            <div class="form-group">
                                                <label class="col-md-3 col-xs-12 control-label">Email</label>
                                                <div class="col-md-9 col-xs-12">                                            
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><span class="fa fa-envelope"></span></span>
                                                        <input type="email" name="email_user"  id="email_user" value="<?php echo $value->email_user ?>" class="form-control" oninvalid="setCustomValidity('Gunakan karakter yang valid dan tidak boleh kosong')" oninput="this.setCustomValidity('')" required>
                                                    </div>                                            
                                                </div>
                                            </div> 
                                            <div class="form-group">
                                                <label class="col-md-3 col-xs-12 control-label">Nomor Telepon</label>
                                                <div class="col-md-9 col-xs-12">                                            
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><span class="fa fa-phone"></span></span>
                                                        <input type="text" name="telp_user"  id="telp_user" value="<?php echo $value->telp_user ?>" class="form-control" pattern="^[a-z A-Z 0-9]*$" oninvalid="setCustomValidity('Gunakan karakter yang valid dan tidak boleh kosong')" oninput="this.setCustomValidity('')" required>
                                                    </div>                                            
                                                </div>
                                            </div>
                                             <div class="form-group">
                                                <label class="col-md-3 col-xs-12 control-label">Alamat Rumah</label>
                                                <div class="col-md-9 col-xs-12">                                            
                                                    <textarea class="form-control" rows="5" name="alamat_user"  id="alamat_user"><?php echo $value->alamat_user?></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                    <label class="col-md-3 col-xs-12 control-label">Foto terbaru</label>
                                                    <div class="col-md-9 col-xs-12">
                                                        <input type="file" class="fileinput btn-primary" name="foto_user" id="foto_user" title="Upload foto"/>
                                                        <span class="help-block">Abaikan jika tidak ingin mengganti Foto</span>
                                                    </div>
                                                </div>
                                            <div class="form-group">
                                                <label class="col-md-3 control-label">Unit</label>
                                                <div class="col-md-9 col-xs-12">                                         
                                                    <select class="form-control select" name="id_unit"  id="id_unit">
                                                        <option value="<?php echo $value->id_unit?>">Abaikan jika tidak ingin mengganti unit</option>
                                                        <?php foreach ($data_unit as $key => $values) { ?>
                                                            <option value="<?php echo $values->id_unit;?>"><?php echo $values->nama_unit;?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 col-xs-12 control-label">Jabatan</label>
                                                <div class="col-md-9 col-xs-12">                                            
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><span class="fa fa-bookmark"></span></span>
                                                        <input type="text" name="jabatan"  id="jabatan" value="<?php echo $value->jabatan ?>" class="form-control" pattern="^[a-z A-Z 0-9]*$" oninvalid="setCustomValidity('Gunakan karakter yang valid dan tidak boleh kosong')" oninput="this.setCustomValidity('')" required>
                                                    </div>                                            
                                                </div>
                                            </div> 
                                            <div class="form-group">
                                                <label class="col-md-3 col-xs-12 control-label">Nip</label>
                                                <div class="col-md-9 col-xs-12">                                            
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><span class="fa fa-pencil"></span></span>
                                                        <input type="text" name="nip"  id="nip" value="<?php echo $value->nip ?>" class="form-control" pattern="^[a-z A-Z 0-9]*$" oninvalid="setCustomValidity('Gunakan karakter yang valid dan tidak boleh kosong')" oninput="this.setCustomValidity('')" required>
                                                    </div>                                            
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-3 col-xs-12 control-label">Password</label>
                                                <div class="col-md-9 col-xs-12">                                            
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><span class="fa fa-lock"></span></span>
                                                        <input type="password" name="password_user"  id="password_user" class="form-control" placeholder="Abaikan jika Tidak ingin mengganti Password">
                                                    </div>                                            
                                                </div>
                                            </div> 
                                    </div>
                                    <div class='modal-footer'>
                                        <button class='btn btn-primary'  type="submit"><span class="fa fa-save"></span> Simpan</button>
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






