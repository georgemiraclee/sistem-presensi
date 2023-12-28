<?php 

$pengaturan = $this->Db_select->select_all_row('tb_setting');

?>

<!DOCTYPE html>

<html>



<head>

    <meta charset="UTF-8">

    <meta http-equiv="X-UA-Compatible" content="IE=Edge">

    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <title><?php echo $pengaturan->nama_app;?></title>

    <!-- Favicon-->

    <link rel="icon" href="<?php echo base_url();?>assets/images/icon/4.png" type="image/x-icon">



    <!-- Google Fonts -->

    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">



    <!-- Bootstrap Core Css -->

    <link href="<?php echo base_url();?>assets/admin/plugins/bootstrap/css/bootstrap.css" rel="stylesheet">



    <!-- JQuery DataTable Css -->

    <link href="<?php echo base_url();?>assets/admin/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">



    <!-- Waves Effect Css -->

    <link href="<?php echo base_url();?>assets/admin/plugins/node-waves/waves.css" rel="stylesheet" />



    <!-- Animation Css -->

    <link href="<?php echo base_url();?>assets/admin/plugins/animate-css/animate.css" rel="stylesheet" />

    <link href="<?php echo base_url();?>assets/admin/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />

    <link href="<?php echo base_url();?>assets/admin/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css" rel="stylesheet">

    <link href="<?php echo base_url();?>assets/admin/plugins/nouislider/nouislider.min.css" rel="stylesheet" />



    <!-- Morris Chart Css-->

    <link href="<?php echo base_url();?>assets/admin/plugins/morrisjs/morris.css" rel="stylesheet" />



    <!-- Custom Css -->

    <link href="<?php echo base_url();?>assets/admin/css/style.css" rel="stylesheet">



    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->

    <link href="<?php echo base_url();?>assets/admin/css/themes/all-themes.css" rel="stylesheet" />

    <link href="<?php echo base_url();?>assets/js/plugins/fontawesome/css/font-awesome.min.css" rel="stylesheet" />

    <link rel="stylesheet" type="text/css" id="theme" href="<?php echo base_url();?>assets/js/plugins/sweetalert/dist/sweetalert.css"/>

    <link href="<?php echo base_url();?>assets/admin/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet" />



    <!-- Jquery Core Js -->

    <script src="<?php echo base_url();?>assets/admin/plugins/jquery/jquery.min.js"></script>

    <script type="text/javascript" src="<?php echo base_url();?>/assets/js/plugins/sweetalert/dist/sweetalert.min.js"></script>



    <!-- Colorpicker Css -->

    <link href="<?php echo base_url();?>assets/admin/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.css" rel="stylesheet" />



    <!-- Dropzone Css -->

    <link href="<?php echo base_url();?>assets/admin/plugins/dropzone/dropzone.css" rel="stylesheet">



    <!-- Multi Select Css -->

    <link href="<?php echo base_url();?>assets/admin/plugins/multi-select/css/multi-select.css" rel="stylesheet">



    <!-- Bootstrap Spinner Css -->

    <link href="<?php echo base_url();?>assets/admin/plugins/jquery-spinner/css/bootstrap-spinner.css" rel="stylesheet">

    

    <!-- DATERANGEPICKER -->

    <link rel="stylesheet" type="text/css" media="all" href="<?php echo base_url();?>assets/js/plugins/daterangepicker/daterangepicker.css" />

    <script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/daterangepicker/moment.js"></script>

    <script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/daterangepicker/daterangepicker.js"></script>



    

    <script src="<?php echo base_url();?>assets/admin/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>

</head>



<body class="theme-pressensi">

    <!-- Page Loader -->

    <div class="page-loader-wrapper">

        <div class="loader">

            <div class="preloader">

                <div class="spinner-layer pl-red">

                    <div class="circle-clipper left">

                        <div class="circle"></div>

                    </div>

                    <div class="circle-clipper right">

                        <div class="circle"></div>

                    </div>

                </div>

            </div>

            <p>Please wait...</p>

        </div>

    </div>

    <!-- #END# Page Loader -->

    <!-- Overlay For Sidebars -->

    <div class="overlay"></div>

    <nav class="navbar">

        <div class="container-fluid">

            <div class="navbar-header">

                <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>

                <a href="javascript:void(0);" class="bars"></a>

                <a class="navbar-brand" href="javascript:void(0);"><img style="margin-top: -15px;" src="<?php echo base_url();?>assets/images/icon/<?php echo $pengaturan->icon;?>" width='120px'></a>

            </div>

            <div class="collapse navbar-collapse" id="navbar-collapse">

                <ul class="nav navbar-nav navbar-right">

                    <li class="float-right">

                        <a href="javascript:void(0);" onclick="logout()">

                            <i class="material-icons">exit_to_app</i>

                        </a>

                    </li>

                </ul>

            </div>

        </div>

    </nav>

    <!-- #Top Bar -->

    <section>

        <!-- Left Sidebar -->

        <aside id="leftsidebar" class="sidebar">

            <!-- User Info -->

            <div class="user-info">

                <div class="image">

                    <img src="<?php echo base_url();?>assets/admin/images/user.png" width="48" height="48" alt="User" />

                </div>

                <div class="info-container">

                    <!-- <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">John Doe</div>

                    <div class="email">john.doe@example.com</div>

                    <div class="btn-group user-helper-dropdown">

                        <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>

                        <ul class="dropdown-menu float-right">

                            <li><a href="javascript:void(0);"><i class="material-icons">person</i>Profile</a></li>

                            <li role="seperator" class="divider"></li>

                            <li><a href="javascript:void(0);"><i class="material-icons">group</i>Followers</a></li>

                            <li><a href="javascript:void(0);"><i class="material-icons">shopping_cart</i>Sales</a></li>

                            <li><a href="javascript:void(0);"><i class="material-icons">favorite</i>Likes</a></li>

                            <li role="seperator" class="divider"></li>

                            <li><a href="javascript:void(0);"><i class="material-icons">input</i>Sign Out</a></li>

                        </ul>

                    </div> -->

                </div>

            </div>

            <!-- #User Info -->

            <!-- Menu -->

            <div class="menu">

                <ul class="list">

                    <li>

                        <a href="<?php echo base_url();?>SO/Dashboard">

                            <i class="material-icons">home</i>

                            <span>Beranda</span>

                        </a>

                    </li>

                    

                    <!-- <li>

                        <a href="<?php echo base_url();?>SO/akun">

                            <i class="material-icons">folder_open</i>

                            <span>Data Pemerintahan</span>

                        </a>

                    </li> -->

                    <li>

                        <a href="javascript:void(0);" class="menu-toggle">

                            <i class="material-icons">book</i>

                            <span>Kehadiran</span>

                        </a>

                        <ul class="ml-menu">

                            <li><a href="<?php echo base_url();?>SO/data_absensi"><span>Data Absensi</span></a></li>

                            <li><a href="<?php echo base_url();?>SO/cuti"><span>Data Pengajuan</span></a></li>

                            <li><a href="<?php echo base_url();?>SO/lembur"><span>Lembur</span></a></li>

                        </ul>

                    </li>

                    <li>

                        <a href="<?php echo base_url();?>SO/akses_absensi">

                            <i style="font-size: 20px;" class="fa fa-users fa-md fa-fw"></i>

                            <span>Akses Absensi Pegawai</span>

                        </a>

                    </li>

                    <li>

                        <a href="<?php echo base_url();?>SO/Saldo">

                            <i style="font-size: 20px;" class="fa fa-credit-card fa-md fa-fw"></i>

                            <span>Potongan & Tunjangan Kinerja</span>

                        </a>

                    </li>

                    <li>

                        <a href="<?php echo base_url();?>SO/simulasi">

                            <i style="font-size: 20px;" class="fa fa-bank fa-md fa-fw"></i>

                            <span>Simulasi Pemotongan TPP</span>

                        </a>

                    </li>

                </ul>

            </div>

            <!-- #Menu -->

            <!-- Footer -->

            <!-- <div class="legal">

                <div class="copyright">

                    &copy; 2016 - 2017 <a href="javascript:void(0);">AdminBSB - Material Design</a>.

                </div>

                <div class="version">

                    <b>Version: </b> 1.0.5

                </div>

            </div> -->

            <!-- #Footer -->

        </aside>

        <!-- #END# Left Sidebar -->

        <!-- Right Sidebar -->

        <aside id="rightsidebar" class="right-sidebar">

            <ul class="nav nav-tabs tab-nav-right" role="tablist">

                <li role="presentation" class="active"><a href="#skins" data-toggle="tab">SKINS</a></li>

                <li role="presentation"><a href="#settings" data-toggle="tab">SETTINGS</a></li>

            </ul>

            <div class="tab-content">

                <div role="tabpanel" class="tab-pane fade in active in active" id="skins">

                    <ul class="demo-choose-skin">

                        <li data-theme="red" class="active">

                            <div class="red"></div>

                            <span>Red</span>

                        </li>

                        <li data-theme="pink">

                            <div class="pink"></div>

                            <span>Pink</span>

                        </li>

                        <li data-theme="purple">

                            <div class="purple"></div>

                            <span>Purple</span>

                        </li>

                        <li data-theme="deep-purple">

                            <div class="deep-purple"></div>

                            <span>Deep Purple</span>

                        </li>

                        <li data-theme="indigo">

                            <div class="indigo"></div>

                            <span>Indigo</span>

                        </li>

                        <li data-theme="blue">

                            <div class="blue"></div>

                            <span>Blue</span>

                        </li>

                        <li data-theme="light-blue">

                            <div class="light-blue"></div>

                            <span>Light Blue</span>

                        </li>

                        <li data-theme="cyan">

                            <div class="cyan"></div>

                            <span>Cyan</span>

                        </li>

                        <li data-theme="teal">

                            <div class="teal"></div>

                            <span>Teal</span>

                        </li>

                        <li data-theme="green">

                            <div class="green"></div>

                            <span>Green</span>

                        </li>

                        <li data-theme="light-green">

                            <div class="light-green"></div>

                            <span>Light Green</span>

                        </li>

                        <li data-theme="lime">

                            <div class="lime"></div>

                            <span>Lime</span>

                        </li>

                        <li data-theme="yellow">

                            <div class="yellow"></div>

                            <span>Yellow</span>

                        </li>

                        <li data-theme="amber">

                            <div class="amber"></div>

                            <span>Amber</span>

                        </li>

                        <li data-theme="orange">

                            <div class="orange"></div>

                            <span>Orange</span>

                        </li>

                        <li data-theme="deep-orange">

                            <div class="deep-orange"></div>

                            <span>Deep Orange</span>

                        </li>

                        <li data-theme="brown">

                            <div class="brown"></div>

                            <span>Brown</span>

                        </li>

                        <li data-theme="grey">

                            <div class="grey"></div>

                            <span>Grey</span>

                        </li>

                        <li data-theme="blue-grey">

                            <div class="blue-grey"></div>

                            <span>Blue Grey</span>

                        </li>

                        <li data-theme="black">

                            <div class="black"></div>

                            <span>Black</span>

                        </li>

                    </ul>

                </div>

                <div role="tabpanel" class="tab-pane fade" id="settings">

                    <div class="demo-settings">

                        <p>GENERAL SETTINGS</p>

                        <ul class="setting-list">

                            <li>

                                <span>Report Panel Usage</span>

                                <div class="switch">

                                    <label><input type="checkbox" checked><span class="lever"></span></label>

                                </div>

                            </li>

                            <li>

                                <span>Email Redirect</span>

                                <div class="switch">

                                    <label><input type="checkbox"><span class="lever"></span></label>

                                </div>

                            </li>

                        </ul>

                        <p>SYSTEM SETTINGS</p>

                        <ul class="setting-list">

                            <li>

                                <span>Notifications</span>

                                <div class="switch">

                                    <label><input type="checkbox" checked><span class="lever"></span></label>

                                </div>

                            </li>

                            <li>

                                <span>Auto Updates</span>

                                <div class="switch">

                                    <label><input type="checkbox" checked><span class="lever"></span></label>

                                </div>

                            </li>

                        </ul>

                        <p>ACCOUNT SETTINGS</p>

                        <ul class="setting-list">

                            <li>

                                <span>Offline</span>

                                <div class="switch">

                                    <label><input type="checkbox"><span class="lever"></span></label>

                                </div>

                            </li>

                            <li>

                                <span>Location Permission</span>

                                <div class="switch">

                                    <label><input type="checkbox" checked><span class="lever"></span></label>

                                </div>

                            </li>

                        </ul>

                    </div>

                </div>

            </div>

        </aside>

        <!-- #END# Right Sidebar -->

    </section>



<script>

    function logout() {

      swal(result.message, {
        icon: "success",
      }).then((acc) => {
        window.location = '<?php echo base_url();?>Administrator/login/logout';
      });
    }

</script>