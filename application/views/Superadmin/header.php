<?php 
  $pengaturan = $this->Db_select->select_all_row('tb_setting');
?>
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

            <!-- Sidebar -->
            <div class="sidebar">
            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                    with font-awesome or any other icon font library -->
                    <li class="nav-item">
                      <a href="<?php echo base_url();?>Superadmin/dashboard" class="nav-link">
                        <i class="nav-icon fa fa-home"></i>
                        <p>Beranda</p>
                      </a>
                    </li>
                    <li class="nav-item has-treeview">
                      <a href="#" class="nav-link">
                        <i class="nav-icon fa fa-briefcase"></i>
                        <p>Channel <i class="fas fa-angle-left right"></i></p>
                      </a>
                      <ul class="nav nav-treeview">
                        <li class="nav-item">
                          <a href="<?php echo base_url();?>Superadmin/channel" class="nav-link">
                          <p>Data Channel</p>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a href="<?php echo base_url();?>Superadmin/channel/admin_channel" class="nav-link">
                          <p>Admin Channel</p>
                          </a>
                        </li>
                      </ul>
                    </li>
                    <li class="nav-item">
                      <a href="<?php echo base_url();?>Superadmin/subscribe" class="nav-link">
                      <i class="nav-icon fas fa-envelope-open-text"></i>
                      <p>List Email Subscribe</p>
                      </a>
                    </li>
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>