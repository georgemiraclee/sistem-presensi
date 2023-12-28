<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Pressensi.com | Reset Password</title>
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
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <h2 class="title text-center">RESET <span> KATA SANDI</span></h2>
    </div>
    <!-- /.login-logo -->
    <div class="card">
        <div class="card-body login-card-body">
            <form action="javascript:void(0);" id="add-form">
              <div class="form-group">
                <input class="form-control" id="password" name="password" required="" type="password" placeholder="Password Baru" minlength="8">
                <input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id;?>">
              </div>
              <div class="form-group">
                <input class="form-control" id="ulang_password" type="password" name="ulang_password" placeholder="Ulangi Password" required="required" minlength="8">
                <div class="show-hide"><span class="show"></span></div>
              </div>
                <div class="row">
                <!-- /.col -->
                <div class="col-12">
                    <button type="submit" class="btn btn-primary btn-block">Reset Kata Sandi</button>
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
    var requestReset = "<?php echo base_url('reset/send'); ?>";
    $(document).ready(function() {
      $('#add-form').on('submit',(function(e) {
          e.preventDefault();
          var formData = new FormData(this);

          var password = document.getElementById("password").value; 
          var user_id = document.getElementById("user_id").value; 
          var ulang_password = document.getElementById("ulang_password").value; 

          if (password != ulang_password) {
            swal("Warning!", "Password yang anda masukkan tidak sesuai.", "warning");
          } else {
            $.ajax({
              method  : 'POST',
              url     : requestReset,
              async   : true,
              data    : {
                  password: password,
                  user_id: user_id
              },
              success: function(data, status, xhr) {
                  try {
                      var result = JSON.parse(xhr.responseText);
                      if (result.status) {
                          swal(result.message, {
                              icon: "success",
                          }).then((acc) => {
                              window.location='<?php echo base_url();?>reset/success';
                          });
                      } else {
                          swal("Warning!", "Terjadi kesalahan sistem", "warning");
                      }
                  } catch (e) {
                    swal("Warning!", "Terjadi kesalahan sistem", "warning");
                  }
              },
              error: function(data) {
                  swal("Warning!", "Terjadi kesalahan sistem", "warning");
              }
            });
          }
      }));
    });
</script>

</body>
</html>
