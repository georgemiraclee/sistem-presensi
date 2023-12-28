                     

                <!-- START BREADCRUMB -->
                <ul class="breadcrumb">
                    <li><a href="#">Menu</a></li>                    
                    <li class="active">Data Channel</li>
                </ul>
                <!-- END BREADCRUMB -->                      
                
                <!-- PAGE CONTENT WRAPPER -->
                <div class="page-content-wrap">
                    <div class="CONTAINER">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Edit Channel</h3>                             
                                </div>
                                <div class="panel-body">
                                    <div id="alert"></div>
                                    <form class="form-horizontal" id="addForm" action="javascript:void(0);" data-toggle="validator" method="post">
                                        <div class="form-group">
                                            <label class="col-md-3 col-xs-12 control-label">Nama Channel</label>
                                            <div class="col-md-6 col-xs-12">                                            
                                                <div class="input-group">
                                                    <span class="input-group-addon"><span class="fa fa-user"></span></span>
                                                    <input type="hidden" name="id_channel" value="<?php echo $data_channel->id_channel;?>">
                                                    <input type="text" name="nama_channel" value="<?php echo $data_channel->nama_channel;?>" id="nama_channel" class="form-control" pattern="^[a-z A-Z 0-9]*$" oninvalid="setCustomValidity('Gunakan karakter yang valid dan tidak boleh kosong')" oninput="this.setCustomValidity('')" required>
                                                </div>                                            
                                            </div>
                                        </div> 
                                        <div class="form-group">
                                            <label class="col-md-3 col-xs-12 control-label">Alamat</label>
                                            <div class="col-md-6 col-xs-12">                                            
                                                <textarea class="form-control" rows="5" name="alamat_channel"  id="alamat_channel"><?php echo $data_channel->alamat_channel;?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 col-xs-12 control-label">Email Channel</label>
                                            <div class="col-md-6 col-xs-12">                                            
                                                <div class="input-group">
                                                    <span class="input-group-addon"><span class="fa fa-user"></span></span>
                                                    <input type="email" value="<?php echo $data_channel->email_channel;?>" name="email_channel"  id="email_channel" class="form-control" required>
                                                </div>                                            
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 col-xs-12 control-label">Nomor Telepon</label>
                                            <div class="col-md-6 col-xs-12">                                            
                                                <div class="input-group">
                                                    <span class="input-group-addon"><span class="fa fa-user"></span></span>
                                                    <input type="text" name="telp_channel" value="<?php echo $data_channel->telp_channel;?>" id="telp_channel" class="form-control" required>
                                                </div>                                            
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 col-xs-12 control-label">Nomor Handphone</label>
                                            <div class="col-md-6 col-xs-12">                                            
                                                <div class="input-group">
                                                    <span class="input-group-addon"><span class="fa fa-user"></span></span>
                                                    <input type="text" name="sms_channel" value="<?php echo $data_channel->sms_channel;?>" id="sms_channel" class="form-control">
                                                </div>                                            
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 col-xs-12 control-label">Deskripsi Channel</label>
                                            <div class="col-md-6 col-xs-12">                                            
                                                <textarea class="form-control" rows="5" name="deskripsi_channel"  id="alamat_channel" required><?php echo $data_channel->deskripsi_channel;?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Jam Kerja</label>
                                            <div class="col-md-2">
                                                <div class="input-group bootstrap-timepicker">
                                                    <input type="text" value="<?php echo $data_channel->jam_masuk;?>" class="form-control timepicker24" name="jam_masuk" required>
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="input-group bootstrap-timepicker">
                                                    <input type="text" value="<?php echo $data_channel->jam_keluar;?>" class="form-control timepicker24" name="jam_keluar" required>
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                             <label class="col-md-3 col-xs-12 control-label">Ikon Channel</label>
                                                <div class="col-md-6 col-xs-12">
                                                    <input type="file" class="fileinput btn-primary" name="logo_channel" id="userfile" title="Upload foto"/>
                                                    <span class="help-block">max size 2mb (JPG,PNG atau JPEG)</span>
                                                </div>
                                        </div>
                                        <button class="btn btn-primary float-right" type="submit"><span class="fa fa-save"></span> Simpan</button>
                                    </form>
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
    var path = '<?php echo base_url();?>';
     $(document).ready(function() {
        $('#addForm').on('submit',(function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                method  : 'POST',
                url     : "<?php echo base_url('Data_channel/updateChannel'); ?>",
                data    : formData,
                contentType: false,
                processData: false,
                success: function(data, status, xhr) {
                    var result = JSON.parse(xhr.responseText);

                    if (result.status == true) {
                        $('#alert').html('<div class="alert alert-info" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>'+result.message+'</div>');
                        window.location = path+'Data_channel';
                    } else {
                        $('#alert').html('<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>'+result.message+'</div>');
                    }
                },
                error: function(data) {
                    $('#alert').html('<div class="alert alert-danger" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>'+result.message+'</div>');
                }
            });
            }));
     });
</script>