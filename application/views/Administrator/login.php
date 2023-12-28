<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Pressensi.com | Log in</title>
  <link rel="shortcut icon" href="<?php echo base_url();?>landingpage/assets/img/favicon.png">
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/admin/plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/admin/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <style>
    .input-group-text a {
        color: #777 !important;
    }
  </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="<?php echo base_url();?>assets/admin/index2.html"><b>Pressensi</b>.com</a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Sign in to start your session</p>

            <form action="javascript:void(0);" id="loginForm">
                <div class="input-group mb-3">
                    <input type="text" title="Input Your Username" class="form-control" placeholder="Input your username" name="username" id="username"  value="<?php echo isset($_COOKIE['username']) ? $_COOKIE['username'] : ''; ?>">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3" id="show_hide_password">
                    <input type="password" title="Please enter your password" placeholder="******" required name="password" id="password" class="form-control" value="<?php echo isset($_COOKIE['password']) ? $_COOKIE['password'] : ''; ?>">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <a href="#"><span class="fa fa-eye-slash" aria-hidden="true"></span></a>
                        </div>
                    </div>
                </div>
                <div class="row">
                  <!-- /.col -->
                  <div class="col-12">
                    <div class="form-check mb-3">
                      <input type="checkbox" name="remember_me" id="remember_me" class="form-check-input" >
                      <label for="remember_me" onclick="IsRememberMe()" class="form-check-label">Ingat saya</label>
                    </div>
                      <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                  </div>
                  <!-- /.col -->
                </div>
            </form>
        </div>
        <!-- /.login-card-body -->
    </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="<?php echo base_url();?>assets/admin/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo base_url();?>assets/admin/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url();?>assets/admin/js/adminlte.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script type="text/javascript">
     <?php if ($remember_me) { ?>
    $('#remember_me').prop('checked', true);
  <?php } ?>

    function setCookie(cname, cvalue, exdays) {
         const d = new Date();
         d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        let expires = "expires="+d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }
    
    var path = '<?php echo base_url();?>';
    $(document).ready(function() {
        $('#loginForm').on('submit',(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                method  : 'POST',
                url     : "<?php echo base_url('Administrator/login/auth'); ?>",
                data    : formData,
                contentType: false,
                processData: false,
                success: function(data, status, xhr) {
                    var result = JSON.parse(xhr.responseText);
                    try {
                        var result = JSON.parse(xhr.responseText);
                        if (result.status == true) {
                            const isRememberMe =formData.get('remember_me');
                            if(isRememberMe){
                                 const username = formData.get('username');
                                 const password = formData.get('password');
                                 setCookie("username", username, 365);
                                 setCookie("password", password, 365);
                            }
                            else{
                                document.getElementById("username").value = ""; // mengosongkan field username
                                document.getElementById("password").value = ""; // mengosongkan field password
                            }
                            
                            swal("Login Success", {
                                title: "Success!",
                                icon: "success",
                            }).then((acc) => {
                                location.reload();
                            });
                            setTimeout(function(){
                                location.reload();
                            }, 5000);
                        } else {
                          swal("Warning!", result.message, "warning");
                        }
                    } catch (e) {
                      swal("Warning!", "Sistem error.", "warning");
                    }
                },
                error: function(data) {
                  swal("Warning!", "Terjadi kesalahan sistem.", "warning");
                }
            });
        }));

        
        $("#show_hide_password a").on('click', function(event) {
            event.preventDefault();
            if($('#show_hide_password input').attr("type") == "text"){
                $('#show_hide_password input').attr('type', 'password');
                $('#show_hide_password span').addClass( "fa-eye-slash" );
                $('#show_hide_password span').removeClass( "fa-eye" );
            }else if($('#show_hide_password input').attr("type") == "password"){
                $('#show_hide_password input').attr('type', 'text');
                $('#show_hide_password span').removeClass( "fa-eye-slash" );
                $('#show_hide_password span').addClass( "fa-eye" );
            }
        });
    });
</script>
<script>
    const remember_me = document.getElementById("remember_me");

    clearbtn.addEventListener("unclick", function () {
     remember_me.value = "";
    });
</script>

</body>
</html>
