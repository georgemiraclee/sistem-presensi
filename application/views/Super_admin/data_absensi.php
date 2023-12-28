                     

                <!-- START BREADCRUMB -->
                <ul class="breadcrumb">
                    <li><a href="#">Menu</a></li>                    
                    <li class="active">Data Absensi</li>
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
                                <div class="panel-body tab-content">
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
                                                <th>Aksi</th>     
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($select_admin_unit as $key => $value) { ?>
                                            <tr>
                                                <th><?php echo $value->nip ?></th>
                                                <th><?php echo $value->nama_user ?></th>
                                                <th><?php echo $value->jabatan ?></th>
                                                <th><?php echo $value->email_user ?></th>
                                                <th><?php echo $value->telp_user ?></th>
                                                <th><?php echo $value->alamat_user ?></th>
                                                <th><?php echo $value->nama_unit ?></th>
                                                <th><a href="<?php echo base_url(); ?>Pegawai/Log/<?php echo $value->nip ?>"><button class=" btn btn-primary">Lihat Detail</button></a></th>
                                            </tr>
                                             <?php } ?>
                                        </tbody>
                                    </table>   
                                </div>
                            </div>   
                        </div>
                    </div>
                </div>
                    
    
            
       
       

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






