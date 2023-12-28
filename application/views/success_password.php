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
</head>
<body class="hold-transition login-page">
<div class="container">
  <div class="text-center mt-5">
      <img class="img-fluid downlod-img" src="<?php echo base_url();?>landingpage/assets/img/success.png" width="50%">
      <div class="row mt-5">
        <div class="col-lg-12">
          <h2>Terima Kasih</h2>
          <h3>Selamat menggunakan aplikasi Pressensi.</h3>
          <a href="<?php echo base_url();?>" class="btn btn-primary mt-2"><span class="fa fa-home"></span> Halaman Utama</a>
        </div>
      </div>
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
                            swal({
                                title: "Login Success",
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
                  swal("Warning!", "Terjadi kesalahan sistem.", "warning");
                }
            });
        }));
    });
</script>

</body>
</html>
