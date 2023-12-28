<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title>Pressensi.com | Dashboard</title>
    <link rel="shortcut icon" href="<?php echo base_url();?>landingpage/assets/img/favicon.png">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/admin/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/admin/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/admin/css/adminlte.min.css">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">
    <!-- jQuery -->
    <script src="<?php echo base_url();?>assets/admin/plugins/jquery/jquery.min.js"></script>
    <!-- JQuery DataTable Css -->
    <link rel="stylesheet" href="<?php echo base_url();?>assets/admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <!-- DATERANGEPICKER -->
    <link rel="stylesheet" type="text/css" media="all" href="<?php echo base_url();?>assets/js/plugins/daterangepicker/daterangepicker.css" />
    <link rel="stylesheet" type="text/css" media="all" href="<?php echo base_url();?>assets/admin/plugins/multi-select/css/multi-select.css" />
    <link rel="stylesheet" type="text/css" media="all" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" type="text/css" media="all" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css" />
    <link href="<?php echo base_url();?>assets/admin/plugins/multi-select/css/multi-select.css" rel="stylesheet">
    <link rel="stylesheet" href="https://js.arcgis.com/4.21/esri/themes/light/main.css">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <link href="<?php echo base_url();?>assets/admin/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet" />
    <script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/daterangepicker/moment.js"></script>
    <script src="<?php echo base_url();?>assets/admin/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>

    <style>
      #grad1 {
          background-color: : #9C27B0;
          background-image: linear-gradient(120deg, #FF4081, #81D4FA)
      }

      #msform {
          text-align: center;
          position: relative;
          margin-top: 20px
      }

      #msform fieldset .form-card {
          background: white;
          border: 0 none;
          border-radius: 0px;
          box-shadow: 0 2px 2px 2px rgba(0, 0, 0, 0.2);
          padding: 20px 40px 30px 40px;
          box-sizing: border-box;
          width: 94%;
          margin: 0 3% 20px 3%;
          position: relative
      }

      #msform fieldset {
          background: white;
          border: 0 none;
          border-radius: 0.5rem;
          box-sizing: border-box;
          width: 100%;
          margin: 0;
          padding-bottom: 20px;
          position: relative
      }

      #msform fieldset:not(:first-of-type) {
          display: none
      }

      #msform fieldset .form-card {
          text-align: left;
          color: #9E9E9E
      }

      #msform input,
      #msform textarea {
          padding: 0px 8px 4px 8px;
          border: none;
          border-bottom: 1px solid #ccc;
          border-radius: 0px;
          margin-bottom: 25px;
          margin-top: 2px;
          width: 100%;
          box-sizing: border-box;
          font-family: montserrat;
          color: #2C3E50;
          font-size: 16px;
          letter-spacing: 1px
      }

      #msform input:focus,
      #msform textarea:focus {
          -moz-box-shadow: none !important;
          -webkit-box-shadow: none !important;
          box-shadow: none !important;
          border: none;
          font-weight: bold;
          border-bottom: 2px solid skyblue;
          outline-width: 0
      }

      #msform .action-button {
          width: 100px;
          background: skyblue;
          font-weight: bold;
          color: white;
          border: 0 none;
          border-radius: 0px;
          cursor: pointer;
          padding: 10px 5px;
          margin: 10px 5px
      }

      #msform .action-button:hover,
      #msform .action-button:focus {
          box-shadow: 0 0 0 2px white, 0 0 0 3px skyblue
      }

      #msform .action-button-previous {
          width: 100px;
          background: #616161;
          font-weight: bold;
          color: white;
          border: 0 none;
          border-radius: 0px;
          cursor: pointer;
          padding: 10px 5px;
          margin: 10px 5px
      }

      #msform .action-button-previous:hover,
      #msform .action-button-previous:focus {
          box-shadow: 0 0 0 2px white, 0 0 0 3px #616161
      }

      select.list-dt {
          border: none;
          outline: 0;
          border-bottom: 1px solid #ccc;
          padding: 2px 5px 3px 5px;
          margin: 2px
      }

      select.list-dt:focus {
          border-bottom: 2px solid skyblue
      }

      .card {
          z-index: 0;
          border: none;
          border-radius: 0.5rem;
          position: relative
      }

      .fs-title {
          font-size: 25px;
          color: #2C3E50;
          margin-bottom: 10px;
          font-weight: bold;
          text-align: left
      }

      #progressbar {
          margin-bottom: 30px;
          overflow: hidden;
          color: lightgrey
      }

      #progressbar .active {
          color: #000000
      }

      #progressbar li {
          list-style-type: none;
          font-size: 12px;
          width: 25%;
          float: left;
          position: relative
      }

      #progressbar #account:before {
          font-family: FontAwesome;
          content: "\f023"
      }

      #progressbar #personal:before {
          font-family: FontAwesome;
          content: "\f007"
      }

      #progressbar #payment:before {
          font-family: FontAwesome;
          content: "\f09d"
      }

      #progressbar #confirm:before {
          font-family: FontAwesome;
          content: "\f00c"
      }

      #progressbar li:before {
          width: 50px;
          height: 50px;
          line-height: 45px;
          display: block;
          font-size: 18px;
          color: #ffffff;
          background: lightgray;
          border-radius: 50%;
          margin: 0 auto 10px auto;
          padding: 2px
      }

      #progressbar li:after {
          content: '';
          width: 100%;
          height: 2px;
          background: lightgray;
          position: absolute;
          left: 0;
          top: 25px;
          z-index: -1
      }

      #progressbar li.active:before,
      #progressbar li.active:after {
          background: skyblue
      }

      .radio-group {
          position: relative;
          margin-bottom: 25px
      }

      .radio {
          display: inline-block;
          width: 204;
          height: 104;
          border-radius: 0;
          background: lightblue;
          box-shadow: 0 2px 2px 2px rgba(0, 0, 0, 0.2);
          box-sizing: border-box;
          cursor: pointer;
          margin: 8px 2px
      }

      .radio:hover {
          box-shadow: 2px 2px 2px 2px rgba(0, 0, 0, 0.3)
      }

      .radio.selected {
          box-shadow: 1px 1px 2px 2px rgba(0, 0, 0, 0.1)
      }

      .fit-image {
          width: 100%;
          object-fit: cover
      }
    </style>

<style>
        #map {
            height: 400px;
            width: 100%;
        }
        .pac-card {
            margin: 10px 10px 0 0;
            border-radius: 2px 0 0 2px;
            box-sizing: border-box;
            -moz-box-sizing: border-box;
            outline: none;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
            background-color: #fff;
            font-family: Roboto;
        }

        #pac-container {
            padding-bottom: 12px;
            margin-right: 12px;
        }

        .pac-controls {
            display: inline-block;
            padding: 5px 11px;
        }

        .pac-controls label {
            font-family: Roboto;
            font-size: 13px;
            font-weight: 300;
        }

        #pac-input {
            background-color: #fff;
            font-family: Roboto;
            font-size: 15px;
            font-weight: 300;
            margin-right: 10px;
            margin-top: 10px;
            text-overflow: ellipsis;
            width: 200px;
        }

        #pac-input:focus {
            border-color: #4d90fe;
        }

        #title {
            color: #fff;
            background-color: #4d90fe;
            font-size: 25px;
            font-weight: 500;
            padding: 6px 12px;
        }
        #target {
            width: 345px;
        }
        .classBorder {
            background-color: rgba(255,0,0,0.2);
            border-color: red;
            border-style: solid;
            border-width: 2px;
        }

    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <div class="dropdown-divider"></div>
                        <a href="<?php echo base_url();?>Administrator/setting_akun" class="dropdown-item">My Profile</a>
                        <div class="dropdown-divider"></div>
                        <a href="javascript:void(0)" onclick="logout()" class="dropdown-item">Log Out</a>
                    </div>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="<?php echo base_url();?>" class="brand-link">
            <img src="<?php echo base_url();?>assets/admin/img/icon-white.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
                style="opacity: .8">
            <span class="brand-text font-weight-light">Pressensi.com</span>
            </a>
        </aside>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-md-12">
                        </div>
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <section class="content">
              <div class="container-fluid">
                  <div class="row">
                      <div class="col-md-12">
                          <div class="card p-3">
                              <h2><strong>Setup Account</strong></h2>
                              <div class="row">
                                  <div class="col-md-12 mx-0">
                                      <form id="msform">
                                          <!-- progressbar -->
                                          <ul id="progressbar">
                                              <li class="active" id="account"><strong>Account</strong></li>
                                              <li id="personal"><strong>Personal</strong></li>
                                              <li id="payment"><strong>Payment</strong></li>
                                              <li id="confirm"><strong>Finish</strong></li>
                                          </ul> <!-- fieldsets -->
                                          <fieldset>
                                              <div class="form-card">
                                                  <h2 class="fs-title">Wifi & Area</h2>
                                                  <fieldset>
                                                      <div class="row clearfix">
                                                          <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                              <label for="email_address_2" class="float-left">Nama SSID</label>
                                                          </div>
                                                          <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                                              <div class="form-group">
                                                                  <div class="form-line">
                                                                      <input type="text" name="ssid_jaringan" id="ssid_jaringan" class="form-control required" placeholder="Nama SSID" required>
                                                                  </div>
                                                              </div>
                                                          </div>
                                                          <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                              <label for="email_address_2" class="float-left">Mac Address Wifi</label>
                                                          </div>
                                                          <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                                              <div class="form-group">
                                                                  <div class="form-line">
                                                                      <input type="text" name="mac_address_jaringan" id="mac_address_jaringan" class="form-control required" placeholder="Mac Address Wifi" required>
                                                                  </div>
                                                              </div>
                                                          </div>
                                                          <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                              <label for="email_address_2" class="float-left">Lokasi Wifi</label>
                                                          </div>
                                                          <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                                              <div class="form-group">
                                                                  <div class="form-line">
                                                                      <input type="text" name="lokasi_jaringan" id="lokasi_jaringan" class="form-control" placeholder="Nama Wifi" required>
                                                                  </div>
                                                              </div>
                                                          </div>
                                                          <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                              <label for="email_address_2" class="float-left">Nama Area</label>
                                                          </div>
                                                          <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                                              <div class="form-group">
                                                                  <div class="form-line">
                                                                      <input type="text" name="area" id="area" class="form-control" placeholder="Nama Area" required>
                                                                  </div>
                                                              </div>
                                                          </div>
                                                          <input id="pac-input" class="form-control" type="text" placeholder="Cari">
                                                          <div class="col-md-12" id="map"></div>
                                                          <div class="text-center">
                                                            <a href="javascript:void(0);" class="btn btn-info" onclick="setNull()"><span class="fas fa-sync-alt"> Reset map</span></a>
                                                          </div>
                                                      </div>
                                                  <fieldset>
                                              </div> <input type="button" name="next" class="next action-button" value="Next Step" />
                                          </fieldset>
                                          <fieldset>
                                              <div class="form-card">
                                                  <h2 class="fs-title">Personal Information</h2> <input type="text" name="fname" placeholder="First Name" /> <input type="text" name="lname" placeholder="Last Name" /> <input type="text" name="phno" placeholder="Contact No." /> <input type="text" name="phno_2" placeholder="Alternate Contact No." />
                                              </div> <input type="button" name="previous" class="previous action-button-previous" value="Previous" /> <input type="button" name="next" class="next action-button" value="Next Step" />
                                          </fieldset>
                                          <fieldset>
                                              <div class="form-card">
                                                  <h2 class="fs-title">Payment Information</h2>
                                                  <div class="radio-group">
                                                      <div class='radio' data-value="credit"><img src="https://i.imgur.com/XzOzVHZ.jpg" width="200px" height="100px"></div>
                                                      <div class='radio' data-value="paypal"><img src="https://i.imgur.com/jXjwZlj.jpg" width="200px" height="100px"></div> <br>
                                                  </div> <label class="pay">Card Holder Name*</label> <input type="text" name="holdername" placeholder="" />
                                                  <div class="row">
                                                      <div class="col-9"> <label class="pay">Card Number*</label> <input type="text" name="cardno" placeholder="" /> </div>
                                                      <div class="col-3"> <label class="pay">CVC*</label> <input type="password" name="cvcpwd" placeholder="***" /> </div>
                                                  </div>
                                                  <div class="row">
                                                      <div class="col-3"> <label class="pay">Expiry Date*</label> </div>
                                                      <div class="col-9"> <select class="list-dt" id="month" name="expmonth">
                                                              <option selected>Month</option>
                                                              <option>January</option>
                                                              <option>February</option>
                                                              <option>March</option>
                                                              <option>April</option>
                                                              <option>May</option>
                                                              <option>June</option>
                                                              <option>July</option>
                                                              <option>August</option>
                                                              <option>September</option>
                                                              <option>October</option>
                                                              <option>November</option>
                                                              <option>December</option>
                                                          </select> <select class="list-dt" id="year" name="expyear">
                                                              <option selected>Year</option>
                                                          </select> </div>
                                                  </div>
                                              </div> <input type="button" name="previous" class="previous action-button-previous" value="Previous" /> <input type="button" name="make_payment" class="next action-button" value="Confirm" />
                                          </fieldset>
                                          <fieldset>
                                              <div class="form-card">
                                                  <h2 class="fs-title text-center">Success !</h2> <br><br>
                                                  <div class="row justify-content-center">
                                                      <div class="col-3"> <img src="https://img.icons8.com/color/96/000000/ok--v2.png" class="fit-image"> </div>
                                                  </div> <br><br>
                                                  <div class="row justify-content-center">
                                                      <div class="col-7 text-center">
                                                          <h5>You Have Successfully Signed Up</h5>
                                                      </div>
                                                  </div>
                                              </div>
                                          </fieldset>
                                      </form>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
            </section>
        </div>

        <!-- Main Footer -->
        <footer class="main-footer">
            <strong>Copyright &copy; 2020-<?php echo date('Y');?> <a href="https://pressensi.com">Pressensi.com</a>.</strong>
            All rights reserved.
        </footer>
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->
    <!-- Bootstrap -->
    <script src="<?php echo base_url();?>assets/admin/js/pages/forms/form-wizard.js"></script>
    <script src="<?php echo base_url();?>assets/admin/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="<?php echo base_url();?>assets/admin/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?php echo base_url();?>assets/admin/js/adminlte.js"></script>

    <!-- OPTIONAL SCRIPTS -->
    <script src="<?php echo base_url();?>assets/admin/js/demo.js"></script>

    <!-- PAGE PLUGINS -->
    <!-- jQuery Mapael -->
    <script src="<?php echo base_url();?>assets/admin/plugins/jquery-mousewheel/jquery.mousewheel.js"></script>
    <script src="<?php echo base_url();?>assets/admin/plugins/raphael/raphael.min.js"></script>
    <script src="<?php echo base_url();?>assets/admin/plugins/jquery-mapael/jquery.mapael.min.js"></script>
    <script src="<?php echo base_url();?>assets/admin/plugins/jquery-mapael/maps/usa_states.min.js"></script>
    <script src="<?php echo base_url();?>assets/admin/plugins/daterangepicker/moment.min.js"></script>
    <!-- ChartJS -->
    <script src="<?php echo base_url();?>assets/admin/plugins/chart.js/Chart.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/admin/js/highchart.js"></script>
    <script src="<?php echo base_url(); ?>assets/admin/js/highchart_3d.js"></script>
    <script src="<?php echo base_url();?>assets/admin/plugins/highcharts/code/modules/exporting.js"></script>
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js"></script>

    <script src="<?php echo base_url();?>assets/admin/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/daterangepicker/moment.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/daterangepicker/daterangepicker.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>assets/admin/plugins/multi-select/js/jquery.multi-select.js"></script>
    <script src="<?php echo base_url();?>assets/admin/plugins/forms/form-validation.js"></script>
    <script src="<?php echo base_url();?>assets/admin/plugins/jquery-validation/jquery.validate.js"></script>
    <script src="<?php echo base_url();?>assets/admin/plugins/multi-select/js/jquery.multi-select.js"></script>
    
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://js.arcgis.com/4.21/"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyADOfVTL5FPWtGo207dJZrSZLdafERarwM&libraries=drawing,places&callback=initMap" async defer></script>

    <script>
      var urlInsert = "<?php echo base_url('Administrator/Starter/insert'); ?>";
      var map, drawingManager, marker;
      var all_overlays = [];
      var kordinat = [];

      function initMap() {
          map = new google.maps.Map(document.getElementById('map'), {
              center: {lat: -3.087275, lng: 117.92132699999999},
              zoomControl: true,
              mapTypeControl: false,
              streetViewControl: false,
              fullscreenControl: false,
              zoom: 5
          });

          var card = document.getElementById('pac-input');
          var input = document.getElementById('pac-input');

          map.controls[google.maps.ControlPosition.TOP_RIGHT].push(card);

          var autocomplete = new google.maps.places.Autocomplete(input);
          autocomplete.bindTo('bounds', map);
          autocomplete.setFields(['address_components', 'geometry', 'icon', 'name']);

          marker = new google.maps.Marker({
          map: map,
          anchorPoint: new google.maps.Point(0, -29)
          });

          autocomplete.addListener('place_changed', function() {
              marker.setVisible(false);
              var place = autocomplete.getPlace();
              if (!place.geometry) {
                  // User entered the name of a Place that was not suggested and
                  // pressed the Enter key, or the Place Details request failed.
                  window.alert("No details available for input: '" + place.name + "'");
                  return;
              }

              // If the place has a geometry, then present it on a map.
              if (place.geometry.viewport) {
                  map.fitBounds(place.geometry.viewport);
              } else {
                  map.setCenter(place.geometry.location);
                  map.setZoom(17);  // Why 17? Because it looks good.
              }
              marker.setPosition(place.geometry.location);
              marker.setVisible(true);

              var address = '';
              if (place.address_components) {
                  address = [
                      (place.address_components[0] && place.address_components[0].short_name || ''),
                      (place.address_components[1] && place.address_components[1].short_name || ''),
                      (place.address_components[2] && place.address_components[2].short_name || '')
                  ].join(' ');
              }
          });

          drawPoly()
      }

      function setNull() {
          for (var i=0; i < all_overlays.length; i++){
              all_overlays[i].setMap(null);
          }

          all_overlays = [];
          marker.setVisible(false);

          drawPoly();
      }

      function drawPoly() {
          
          drawingManager = new google.maps.drawing.DrawingManager({
              drawingMode: google.maps.drawing.OverlayType.POLYGON,
              drawingControl: false,
              drawingControlOptions: {
                  position: google.maps.ControlPosition.TOP_CENTER,
                  drawingModes: ['polygon']
              },
          });
          drawingManager.setMap(map);

          google.maps.event.addListener(drawingManager, 'polygoncomplete', function (polygon) {
              for (var i=0; i < all_overlays.length; i++){
                  all_overlays[i].setMap(null);
              }

              all_overlays.push(polygon);

              drawingManager.setMap(null);
              var coordinates = (polygon.getPath().getArray());

              for (var i = 0; i < coordinates.length; i++) {
                  var getCoor = {lat : coordinates[i].lat(), lng : coordinates[i].lng()};

                  kordinat.push(getCoor);                
              }
          });
      }

      function logout() {
          swal({
              title: "Logout!",
              text: "Are you sure ?",
              icon: "warning",
              buttons: true,
              dangerMode: true,
          })
          .then((willDelete) => {
              if (willDelete) {
                  window.location = '<?php echo base_url();?>Administrator/login/logout';
              }
          });
      }
    </script>
</body>
</html>