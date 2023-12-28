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
    <link href="<?php echo base_url();?>assets/country/css/flag-icon.css" rel="stylesheet">

    
    <script type="text/javascript" src="<?php echo base_url();?>/assets/js/plugins/sweetalert/dist/sweetalert.min.js"></script>
  </head>
  <style type="text/css">
      .color-picker{
        visibility: hidden;
      }
      .feedback2 {
                  z-index: 999999;
                  max-width: 30px;
                  }
                
    #mybutton2 {
                display: none;
                position: fixed;
                bottom: 250px;
                right: 1px;
                z-index: 999999;
                }
    @media (max-width:767px){
      .hidden-xs{
        display:none!important
      }
      .freetrial{
        margin-top: 0px;
      }
      .formanying {
              display: inline-block;
              border: 1px solid #ccc;
              border-radius: 4px;
              box-sizing: border-box;
            } 
    }
  </style>
  <body data-spy="scroll" data-target=".navbar" data-offset="80">
    <!-- Preloader-->
    <div class="loader-wrapper">  
      <div class="loader"></div>
    </div>
    <!-- Preloader end-->
    <!-- Nav Start-->
    <nav class="navbar navbar-expand-lg navbar-light theme-nav fixed-top">
      <div class="container"><a class="navbar-brand" href="<?php echo base_url();?>"><img src="<?php echo base_url();?>assets/lp_2019/images/logow.png" style="max-width: 142px;" alt="logo"></a>
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
    <!-- Nav end-->
    <div id="mybutton2" class="hidden-xs">
      <a href="<?php echo base_url();?>Trial">
        <img class="feedback2" src="<?php echo base_url();?>assets/lp_2019/images/ft-04.png">
      </a>
    </div>
    <!-- Home Section start-->
     <section class="authentication-form download">
      <div class="innerpage-decor">
        <!-- <div class="innerpage-circle1"><img src="../assets/images/Testimonial2.png" alt=""></div>
        <div class="innerpage-circle2"><img src="../assets/images/Testimonial1.png" alt=""></div> -->
      </div>
      <div class="container">
        <div class="row" style="margin-top: 60px; margin-bottom: 20px">
          <div class="col-md-7">
                <img src="<?php echo base_url();?>assets/lp_2019/images/<?php echo $image;?>" style= "max-width: 600px">        
          </div>
          <div class="col-md-5">
                <h2 style="margin-top: 30px; margin-bottom: -20px"><?php echo $judul?></h2>
                <hr>
                <?php echo $keterangan?>
              </div>

        </div>
      </div>
        <!-- copy-right-section-->
       
        <!-- end copy right section-->
      </div>
    </section>
    <!--Subscribe section Ends-->
    <!-- Map Section Start-->
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

    <div class="tap-top" style="bottom: 20px">
      <div><i class="fa fa-angle-double-up"></i></div>
    </div>
    <!-- Tap on Ends-->
    <!-- Footer Section start-->

    <div class="copyright-section index-footer" style="background-color: #333231">
      <p style="color: white">2019 copyright by pressensi powered by <a href="https://folkatech.com/" style="color: white;">Folkatech</a></p>
    </div>
    <!-- Footer Section End-->
    <!-- facebook chat section start-->
    
    <!-- facebook chat section end-->
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
  </body>
</html>