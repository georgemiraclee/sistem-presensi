<!DOCTYPE html>
<html lang="en">
  <head>
    <title>pressensi | Absensi Online & Monitoring</title>
    <meta charset="utf-8">
    <meta name="author" content="pressensiapp">
    <meta name="description" content="pressensi | Absensi online & monitoring data pegawai, merupakan aplikasi absensi online modern dimana pengguna dapat menggunakan fitur-fitur istimewa seperti absensi dengan 3 validasi(face recognition , lokasi & jaringan), monitoring data pegawai secara realtime, perhitungan jam kerja dan lembur, pengajuan cuti dan sakit, perhitungan gaji dan tunjangan serta perhitungan BPJS&PPH21">
    <meta name="keywords" content="pressensi, Absensi Online, absen online, kelola karyawan, perhitungan BPJS, Software Payroll, pph21, penggajian, aplikasi hris, sdm, aplikasi sdm, aplikasi hrd berbasis web, THR, perhitungan THR, perhitungan pph 21, perhitungan lembur, cuti, karyawan,  absensi, pegawai, hris indonesia, manajemen sumber daya manusia, aplikasi hris gratis">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Fav icon-->
    <link rel="shortcut icon" href="<?php echo base_url();?>assets/images/icon/4.png">

    <!-- Font Family-->
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
    <!-- Font Awesome-->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/lp_2019/css/font-awesome.min.css">
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/lp_2019/css/bootstrap.css">
    <!-- Animation CSS-->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/lp_2019/css/animate.min.css">
    <!-- Owl carousel css-->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/lp_2019/css/owl.carousel.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/lp_2019/css/owl.theme.default.min.css">
    <!-- Form validation css-->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/lp_2019/css/validation.css">
    <!-- Style css-->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/lp_2019/css/style.css">
    <!-- Responsive css-->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/lp_2019/css/responsive.css">
    <!-- color variation-->
    <link id="color" rel="stylesheet" href="<?php echo base_url();?>assets/lp_2019/css/color/color-1.css" media="screen">
    <link rel="stylesheet" type="text/css" id="theme" href="<?php echo base_url();?>assets/js/plugins/sweetalert/dist/sweetalert.css"/>
    <script type="text/javascript" src="<?php echo base_url();?>/assets/js/plugins/sweetalert/dist/sweetalert.min.js"></script>
  </head>
  <style type="text/css">
    a.kur{
      color: #3b5998;
    }
    a.kur:hover{
      color: #6AC160;
    }
    .color-picker{
        visibility: hidden;
      }
  </style>
  <body>
    <!-- Preloader-->
    <div class="loader-wrapper">
      <div class="loader"></div>
    </div>
    <nav class="navbar navbar-expand-lg navbar-light theme-nav fixed-top">
      <div class="container"><a class="navbar-brand" href="index.html"><img src="<?php echo base_url();?>assets/lp_2019/images/logow.png" style="max-width: 142px;" alt="logo"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse default-nav" id="navbarSupportedContent">
          <ul class="navbar-nav ml-auto" id="mymenu">
            <li class="nav-item"><a class="nav-link" href="<?php echo base_url();?>"><?php echo $this->lang->line('home');?></a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo base_url();?>#about"><?php echo $this->lang->line('about');?></a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo base_url();?>#feature"><?php echo $this->lang->line('feature');?></a></li>
            <!-- <li class="nav-item"><a class="nav-link" href="#screenshot">Video</a></li> -->
            <li class="nav-item"><a class="nav-link" href="<?php echo base_url();?>#testimonial" data-menuanchor="testimonial"><?php echo $this->lang->line('download');?></a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo base_url();?>login"><?php echo $this->lang->line('login');?></a></li>

            <!-- set button trial -->
            <li class="nav-item">
              <a class="btn btn-primary btn-sm" style="border-color: white; border: 0;" href="<?php echo base_url();?>Trial"><?php echo $this->lang->line('free_trial');?></a>
            </li>
            
            <li class="nav-item"><a class="nav-link" href="<?php echo base_url();?>lang_setter/set_to/indonesia"><span class="flag-icon flag-icon-id"></span></a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo base_url();?>lang_setter/set_to/english"><span class="flag-icon flag-icon-gb"></span></a></li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- Preloader end-->
    <section class="authentication-form">
      <div class="innerpage-decor">
        <!-- <div class="innerpage-circle1"><img src="<?php echo base_url();?>assets/lp_2019/images/Testimonial2.png" alt=""></div>
        <div class="innerpage-circle2"><img src="<?php echo base_url();?>assets/lp_2019/images/Testimonial1.png" alt=""></div> -->
      </div>
      <div style="margin-top: 30px;">
        <h2 class="title text-center">Undang Kami Untuk Diskusi Lebih di Kantor Anda</h2>
        <p class="text-center">Silahkan isi data pribadi anda untuk kami hubungi</p>
        <div class="card">
          <form class="theme-form" role="form" id="add-form" action="javascript:void(0);" method="post">
            <div class="form-group">
              <input class="form-control" id="email" name="email" type="email" placeholder="Email" required="required">
            </div>
            <div class="form-group">
              <div class="md-fgrup-margin">
                <input class="form-control" id="name" name="nama" type="text" placeholder="Nama Lengkap" required="required">
              </div>
            </div>
            <div class="form-group">
              <input class="form-control" type="number" id="telepon" name="telepon" placeholder="Nomor Telepon" required="required">
            </div>
            <div class="form-group">
              <input class="form-control" type="text" name="perusahaan" placeholder="Nama Perusahaan" required="required">
            </div>
            <div class="form-group">
              <input class="form-control" type="text" name="bidang_usaha" placeholder="Bidang Usaha" required="required">
              <i style="font-size: 10px; color: red">Contoh : Teknologi Informasi</i>
            </div>
            <div class="form-group">
              <input class="form-control" type="number" name="jumlah_karyawan" placeholder="Jumlah Karyawan" required="required">
            </div>
            <div class="form-group">
              <input class="form-control" type="text" name="metode" placeholder="Metode Absensi Yang Berjalan" required="required">
              <i style="font-size: 10px; color: red">Contoh : Fingerprint</i>
            </div>
            <div class="form-group">
              <input class="form-control" type="date" name="implementasi" placeholder="Rencana Implementasi pressensi" required="required">
            </div>
            <div class="form-group">
              <textarea class="form-control" name="alamat" placeholder="Alamat Perusahaan" required="required"></textarea>
            </div>
          
            <div class="form-button text-center">
              <button class="btn btn-custom theme-color" type="submit">Daftar Akun</button>
            </div>
          </form>
        </div>
      </div>
    </section>
    <!-- facebook chat section start-->
    <div id="fb-root"></div>
    <!-- Your customer chat code-->
    <section class="p-0" style="background-color: #333231">
      <div class="container-fluid">
        <div class="bottom-section">
          <div class="row">
            <div class="col-md-3">
              <div class="container">
                <h3 style="color: white">Contact Us</h3>
                <hr style="border: solid 1px white">
                <ul class="">
                  <li>
                    <a href="mailto:support@pressensi.com"><p style="color: white"><i class="fa fa-envelope"></i> support@pressensi.com</p></a>
                  </li>
                  <li>
                    <a href="tel:0222010606"><p style="color: white"><i class="fa fa-phone-square"></i> 022-2010606</p></a>
                    <a href="tel:081310445410"><p style="color: white"><i class="fa fa-phone-square"></i> 081310445410</p></a>
                  </li>
                  <li>
                    <p style="color: white"><i class="fa fa-map-marker"></i> Jl. Setrasari Indah No.4. Sukarasa. Sukasari. Kota Bandung, Jawa Barat 40152</p>
                  </li>
                </ul>
              </div>
            </div>
            <div class="col-md-3">
              <div class="container">
                <h3 style="color: white">Features</h3>
                <hr style="border: solid 1px white">
                <ul class="">
                  <li>
                    <a href="<?php echo base_url();?>fitur/selfie_validation"><p style="color: white;">Selfie Validation</p></a>
                  </li>
                  <li>
                    <a href="<?php echo base_url();?>fitur/location_validation"><p style="color: white;">Localtion Validation</p></a>
                  </li>
                  <li>
                    <a style="color: white;" href="<?php echo base_url();?>fitur/network_validation"><p style="color: white;">Network Validation</p></a>
                  </li>
                  <li>
                    <a style="color: white;" href="<?php echo base_url();?>fitur/realtime_tracking"><p style="color: white;">Realtime Tracking</p></a>
                  </li>
                </ul>
              </div>
            </div>
            <div class="col-md-3">
              <div class="container">
                <h3 style="color: white">About Us</h3>
                <hr style="border: solid 1px white">
                <ul class="">
                  <li>
                    <a href="<?php echo base_url();?>about/termandcondition"><p style="color: white">Syarat & ketentuan</p></a>
                  </li>
                  <li>
                  <a href="<?php echo base_url();?>about/kebijakan_privasi"><p style="color: white">Kebijakan Privasi</p></a>
                  </li>
                  <li>
                    <a href="http://blog.pressensi.com"><p style="color: white;">Blog</p></a>
                  </li>
                </ul>
              </div>
            </div>

            <div class="col-md-3">
              <div class="container">
                <h3 style="color: white">Find Us</h3>
                <hr style="border: solid 1px white">
                <ul class="">
                  <li>
                      <a href="https://www.youtube.com/channel/UC3_h22QjWKTMNlTWrj0wMgA"><p style="color: white"><i class="fa fa-instagram"></i> pressensiApp</p></a>
                  </li>
                  <li>
                      <a href="https://www.instagram.com/pressensiapp/"><p style="color: white"><i class="fa fa-youtube"></i> pressensiApp</p></a>
                  </li>
                  <li>
                    <a href="https://play.google.com/store/apps/details?id=com.folkatech.pressensi">
                      <img src="<?php echo base_url();?>assets/lp_2019/images/play-store.png" alt="">
                    </a>
                  </li>
                  <!-- <li style="margin-top: 10px;">
                    <a href="#" style="cursor: help;">
                      <img src="<?php echo base_url();?>assets/lp_2019/images/appstore.png" alt="">
                    </a>
                  </li> -->
                </ul>
              </div>
            </div>            
        </div>
      </div>
    </section>
    <!-- Map Section Ends-->
    <!-- Tap on Top-->
    <div class="tap-top" style="bottom: 20px">
      <div><i class="fa fa-angle-double-up"></i></div>
    </div>
    <!-- Tap on Ends-->
    <!-- Footer Section start-->

    <div class="copyright-section index-footer" style="background-color: #333231">
      <p style="color: white">2019 copyright by pressensi powered by <a href="http://folkatech.com/" style="color: white;">Folkatech</a></p>
    </div>
    <!-- js file-->
    <script src="<?php echo base_url();?>assets/lp_2019/js/jquery-3.3.1.min.js"></script>
    <!-- bootstrap js file-->
    <script src="<?php echo base_url();?>assets/lp_2019/js/bootstrap.min.js"></script>
    <!-- popper js file-->
    <script src="<?php echo base_url();?>assets/lp_2019/js/popper.min.js"></script>
    <!-- Owl carousel js file-->
    <script src="<?php echo base_url();?>assets/lp_2019/js/owl.carousel.min.js"></script>
    <!-- script js file-->
    <script src="<?php echo base_url();?>assets/lp_2019/js/script.js"></script>
    <script src="<?php echo base_url();?>assets/admin/plugins/sweetalert/sweetalert.min.js"></script>
    <script>
        var urlInsert = "<?php echo base_url('Trial/insert_invite'); ?>";
        $(document).ready(function() {
            $('#password, #confirm_password').on('keyup', function () {
              if ($('#password').val() == $('#confirm_password').val()) {
                 confirm_password.setCustomValidity('');
              } else 
                confirm_password.setCustomValidity("Passwords Don't Match");
            });
            $('#add-form').on('submit',(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    method  : 'POST',
                    url     : urlInsert,
                    data    : formData,
                    contentType: false,
                    processData: false,
                    success: function(data, status, xhr) {
                      try {
                          var result = JSON.parse(xhr.responseText);
                          if (result.status) {
                              swal(result.message, {
                                  icon: "success",
                              }).then((acc) => {
                                  location.reload();
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
            }));
        });
    </script>
  </body>
</html>