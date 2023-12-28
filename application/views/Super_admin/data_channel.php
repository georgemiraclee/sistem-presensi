                     

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
                            <a href="<?php echo base_url();?>Data_channel/tambahChannel" class="btn btn-primary btn-lg">Tambah Channel</a>
                            <div class="panel-heading">
                            </div> 
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <?php echo $list;?>
                                    </div>
                                </div>   
                            </div>
                        </div>
                    </div>
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
        <script type="text/javascript" src="<?php echo base_url();?>/assets/js/plugins/bootstrap/bootstrap-datepicker.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>/assets/js/plugins/bootstrap/bootstrap-timepicker.min.js"></script>                
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

<script type="text/javascript">
    function aktif(id_channel, idx) {
        $('#btnStatusNA'+idx).toggle();

        $.ajax({
            type  : 'POST',
            url     : '<?php echo base_url(); ?>Data_channel/changeStatus',
            data    : {
                id: id_channel,
                status: 1
            },
            success: function(data, status, xhr){
                var result = JSON.parse(xhr.responseText);

                if (result.status == true) {
                    location.reload();
                } else {
                    $('#btnStatusNA'+idx).toggle();
                }
            }
        });
    }

    function Naktif(id_channel, idx) {
        $('#btnStatusA'+idx).toggle();

        $.ajax({
            type  : 'POST',
            url     : '<?php echo base_url(); ?>Data_channel/changeStatus',
            data    : {
                id: id_channel,
                status: 0
            },
            success: function(data, status, xhr){
                var result = JSON.parse(xhr.responseText);

                if (result.status == true) {
                    location.reload();
                } else {
                    $('#btnStatusA'+idx).toggle();
                }
            }
        });
    }
</script>






