<!DOCTYPE html>
<html lang="en" class="body-full-height">
    <head>        
        <!-- META SECTION -->
        <title>Pressensi</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        
        <link rel="icon" href="<?php echo base_url();?>assets/images/icon/pressensi.png" type="image/x-icon" />
        <!-- END META SECTION -->
        
        <!-- CSS INCLUDE -->        
        <link rel="stylesheet" type="text/css" id="theme" href="<?php echo base_url();?>/assets/css/theme-default.css"/>
        <!-- EOF CSS INCLUDE -->
        <style>
            .alert {
                padding: 20px;
                background-color: #f44336;
                color: white;
                opacity: 1;
                transition: opacity 0.6s;
                margin-bottom: 15px;
            }
            .alert.success {background-color: #4CAF50;}
            .alert.info {background-color: #2196F3;}
            .alert.warning {background-color: #ff9800;}
            .closebtn {
                margin-left: 15px;
                color: white;
                font-weight: bold;
                float: right;
                font-size: 22px;
                line-height: 20px;
                cursor: pointer;
                transition: 0.3s;
            }
            .closebtn:hover {
                color: black;
            }
        </style>                                    
    </head>
    <body>
        
        <div class="login-container">
        
            <div class="login-box animated fadeInDown">
                <h2  align="center" style="color:white;"><b>Pressensi</b></h2>
                <div class="login-body">
                    <div class="login-title"><strong>Selamat datang</strong>, Silahkan Login di sini</div>
                    <div id="alert_dis"></div>
                    <form id="loginForm" action="javascript:void(0);" data-toggle="validator" class="form-horizontal" method="post">
                    <div class="form-group">
                        <div class="col-md-12">
                            <input type="text" class="form-control" placeholder="Username" name="username" id="username" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <input type="password" class="form-control" placeholder="Password" name="password" id="password" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6">
                            <!-- <a href="#" class="btn btn-link btn-block">Lupa password?</a> -->
                        </div>
                        <div class="col-md-6">
                            <button class="btn btn-info btn-block">Masuk</button>
                        </div>
                    </div>
                    </form>
                </div>
                <div class="login-footer">
                    <div class="float-left">
                        &copy; 2017 Folkatech Patform
                    </div>
                </div>
            </div>
            
        </div>
        
    </body>
    </html>
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
<script type="text/javascript">
    var path = '<?php echo base_url();?>';
     $(document).ready(function() {
        $('#loginForm').on('submit',(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                method  : 'POST',
                url     : "<?php echo base_url('Login/auth'); ?>",
                data    : formData,
                contentType: false,
                processData: false,
                success: function(data, status, xhr) {
                    var result = JSON.parse(xhr.responseText);
                    if (result.status == true) {
                        $('#alert_dis').html('<div class="alert success"><span class="closebtn" onclick="this.parentElement.style.display=\'none\';">&times;</span> '+result.message+'</div>');
                        window.location = path;
                    } else {
                        $('#alert_dis').html('<div class="alert"><span class="closebtn" onclick="this.parentElement.style.display=\'none\';">&times;</span> '+result.message+'</div>');
                    }
                },
                error: function(data) {
                    $('#alert_dis').html('<div class="alert"><span class="closebtn" onclick="this.parentElement.style.display=\'none\';">&times;</span> Terjadi kesalahan sistem</div>');
                }
            });
            }));
     });
 </script>