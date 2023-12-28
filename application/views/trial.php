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
    <!-- Preloader end-->
    <section class="authentication-form" >
      <div class="innerpage-decor" style=" margin-top: 90px">
        <!-- <div class="innerpage-circle1"><img src="<?php echo base_url();?>assets/lp_2019/images/Testimonial2.png" alt=""></div>
        <div class="innerpage-circle2"><img src="<?php echo base_url();?>assets/lp_2019/images/Testimonial1.png" alt=""></div> -->
      </div>
      <div style="margin-top: 30px;">
        <div class="row">
          <div class="col-md-6">
            <div class="col-md-12">
              <div class="container" style="margin-top: 120px; margin-left:40px">
                <div class="about-contain">
                  <div>
                    <h2 class="title">
                      Tentang<span>pressensi app</span>
                    </h2>
                    <div class="row sm-mb">
                      <div class="col-md-12">
                        pressensi adalah aplikasi Absensi Online + Monitoring Pegawai berbasis mobile yang menyediakan fitur validasi dan tracking terbaik serta penghitungan gaji dan potongan pegawai
                      </div>
                    </div>
                    <br><br>
                    <div class="row sm-mb">
                      <div class="col-6">
                        <ul class="about-style">
                          <li class="abt-hover">
                            <div class="about-icon">
                              <div class="icon-hover"><img src="<?php echo base_url();?>assets/lp_2019/images/icon1.png" alt="easy-to-customized"></div>
                            </div>
                            <div class="about-text">
                              <h3>Best Validation</h3>
                            </div>
                          </li>
                          <li class="abt-hover">
                            <div class="about-icon">
                              <div class="icon-hover"><img src="<?php echo base_url();?>assets/lp_2019/images/icon3.png" alt="easy-to-use"></div>
                            </div>
                            <div class="about-text">
                              <h3>Realtime Track</h3>
                            </div>
                          </li>
                        </ul>
                      </div>
                      <div class="col-6">
                        <ul class="about-style">
                          <li class="abt-hover">
                            <div class="about-icon">
                              <div class="icon-hover">
                                <img src="<?php echo base_url();?>assets/lp_2019/images/icon2.png" alt="Awasome-Design">
                              </div>
                            </div>
                            <div class="about-text">
                              <h3>Flexible Report</h3>
                            </div>
                          </li>
                          <li class="abt-hover" >
                            <div class="about-icon">
                              <div class="icon-hover"><img src="<?php echo base_url();?>assets/lp_2019/images/icon4.png" alt="SEO-Friendly"></div>
                            </div>
                            <div class="about-text">
                              <h3>Leave System</h3>
                            </div>
                          </li>
                        </ul>
                      </div>
                    </div>
                    <br>
                    <div class="row sm-mb">
                      <div class="col-md-12" style="border-radius: 25px; border: 2px solid grey; background-color: rgba(255,255,255,0.4)">
                        <h2 style="margin-top: 30px">Hubungi kami di kontak berikut</h2>
                        <hr style="margin-top: -10pxx">
                        <ul class="">
                          <li>
                             <div class="icon-hover" style="float: left"><i class="fa fa-envelope" style="=padding-top:10px" ></i></div> <p style="color: black">support@pressensi.com</p>
                          </li>
                          <li>
                             <div class="icon-hover" style="float: left"><i class="fa fa-phone"></i></div> <p style="color: black"> 022-2010606 / 081310445410 / 087722526809</p>
                          </li>
                          <li>
                             <div class="icon-hover" style="float: left"><i class="fa fa-home"></i></div><p style="color: black"> Jl. Setrasari Indah No.4. Sukarasa. Sukasari.<br> &nbsp &nbsp &nbsp Kota Bandung, Jawa Barat 40152</p>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

            </div>
          </div>
          <div class="col-md-6">
            <h2 class="title text-center">Coba Gratis<span> 7 Hari</span></h2>
            <p class="text-center">Silahkan isi data pribadi anda untuk mendaftar</p>
            <div class="card">
              <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
              <form id="__vtigerWebForm" class="theme-form" role="form" name="pressensi" action="#" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                  <input type="hidden" name="__vtrftk" value="sid:309ca0d4a11b44099f56b7a078bb126e361f0274,1571804694">
                  <input type="hidden" name="publicid" value="eb826e541cf10c0cc4258ac1824e0b20">
                  <input type="hidden" name="urlencodeenable" value="1">
                  <input type="hidden" name="name" value="pressensi">
                  <div class="form-group">
                    <div class="md-fgrup-margin">
                      <input class="form-control" type="text" name="lastname" data-label="" value="" required="" placeholder="Nama Lengkap">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="md-fgrup-margin">
                      <input class="form-control" type="text" name="mobile" data-label="" value="" required="" placeholder="Nomor Telepon">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="md-fgrup-margin">
                      <input type="email" class="form-control" placeholder="Alamat Email" name="email" data-label="" value="" required="">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="md-fgrup-margin">
                      <input type="text" class="form-control" placeholder="Nama Perusahaan" name="company" data-label="" value="" required="">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="md-fgrup-margin">
                      <input type="number" class="form-control" placeholder="Jumlah Pegawai" name="noofemployees" data-label="" value="">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="md-fgrup-margin">
                      <select name="leadsource" data-label="leadsource" hidden="">
                        <option value="">Select Value</option>
                        <option value="Sales Call">Sales Call</option>
                        <option value="Existing Customer">Existing Customer</option>
                        <option value="Google Ads">Google Ads</option>
                        <option value="Employee">Employee</option>
                        <option value="Partner">Partner</option>
                        <option value="Public Relations">Public Relations</option>
                        <option value="Direct Mail">Direct Mail</option>
                        <option value="LPSE">LPSE</option>
                        <option value="pressensi Apps" selected="">pressensi Apps</option>
                        <option value="Web Site">Web Site</option>
                        <option value="Social Media Ads">Social Media Ads</option>
                        <option value="Sales Visit">Sales Visit</option>
                        <option value="Email Blast">Email Blast</option>
                        <option value="Other">Other</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-button text-center">
                    <input class="btn btn-custom theme-color" type="submit" value="Kirim">
                  </div>
              </form>

              <script type="text/javascript">
                window.onload = function() { var N = navigator.appName,
                      ua = navigator.userAgent,
                      tem; var M = ua.match(/(opera|chrome|safari|firefox|msie)\/?\s*(\.?\d+(\.\d+)*)/i); if (M && (tem = ua.match(/version\/([\.\d]+)/i)) != null) M[2] = tem[1];
                  M = M ? [M[1], M[2]] : [N, navigator.appVersion, "-?"]; var browserName = M[0]; var form = document.getElementById("__vtigerWebForm"),
                      inputs = form.elements;
                  form.onsubmit = function() { var required = [],
                          att, val; for (var i = 0; i < inputs.length; i++) { att = inputs[i].getAttribute("required");
                          val = inputs[i].value;
                          type = inputs[i].type; if (type == "email") { if (val != "") { var elemLabel = inputs[i].getAttribute("label"); var emailFilter = /^[_/a-zA-Z0-9]+([!"#$%&()*+,./:;<=>?\^_`{|}~-]?[a-zA-Z0-9/_/-])*@[a-zA-Z0-9]+([\_\-\.]?[a-zA-Z0-9]+)*\.([\-\_]?[a-zA-Z0-9])+(\.?[a-zA-Z0-9]+)?$/; var illegalChars = /[\(\)\<\>\,\;\:\"\[\]]/; if (!emailFilter.test(val)) { alert("For " + elemLabel + " field please enter valid email address"); return false; } else if (val.match(illegalChars)) { alert(elemLabel + " field contains illegal characters"); return false; } } } if (att != null) { if (val.replace(/^\s+|\s+$/g, "") == "") { required.push(inputs[i].getAttribute("label")); } } } if (required.length > 0) { alert("The following fields are required: " + required.join()); return false; } var numberTypeInputs = document.querySelectorAll("input[type=number]"); for (var i = 0; i < numberTypeInputs.length; i++) { val = numberTypeInputs[i].value; var elemLabel = numberTypeInputs[i].getAttribute("label"); var elemDataType = numberTypeInputs[i].getAttribute("datatype"); if (val != "") { if (elemDataType == "double") { var numRegex = /^[+-]?\d+(\.\d+)?$/; } else { var numRegex = /^[+-]?\d+$/; } if (!numRegex.test(val)) { alert("For " + elemLabel + " field please enter valid number"); return false; } } } var dateTypeInputs = document.querySelectorAll("input[type=date]"); for (var i = 0; i < dateTypeInputs.length; i++) { dateVal = dateTypeInputs[i].value; var elemLabel = dateTypeInputs[i].getAttribute("label"); if (dateVal != "") { var dateRegex = /^[1-9][0-9]{3}-(0[1-9]|1[0-2]|[1-9]{1})-(0[1-9]|[1-2][0-9]|3[0-1]|[1-9]{1})$/; if (!dateRegex.test(dateVal)) { alert("For " + elemLabel + " field please enter valid date in required format"); return false; } } } var inputElems = document.getElementsByTagName("input"); var totalFileSize = 0; for (var i = 0; i < inputElems.length; i++) { if (inputElems[i].type.toLowerCase() === "file") { var file = inputElems[i].files[0]; if (typeof file !== "undefined") { var totalFileSize = totalFileSize + file.size; } } } if (totalFileSize > 52428800) { alert("Maximum allowed file size including all files is 50MB."); return false; } }; }
              </script>

                <div class="or-saparator"><span>Atau</span></div>
                <div class="form-button text-center social-btns">
                  <a href="<?php echo base_url();?>trial/invite_me" class="kur">Undang Kami Ke Perusahaan Anda</a>
                </div>
                <div class="or-saparator"></div>
                <div class="form-button text-center social-btns">
                  <a href="<?php echo base_url();?>" class="btn btn-custom ggl">Kembali Ke Beranda</a>
                </div>
            </div>
          </div>
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
      <p style="color: white">2019 copyright by pressensi powered by Folkatech</p>
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
        var urlInsert = "<?php echo base_url('Trial/insert'); ?>";
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
                                    window.location='<?php echo base_url();?>Trial/sukses';
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