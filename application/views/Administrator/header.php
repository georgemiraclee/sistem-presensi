<?php
  $sess = $this->session->userdata('user');
  $hour = strtotime(date('H:i'));
  if ($hour >= strtotime("00:00") && $hour <= strtotime("10:59")) {
    $wording = "Selamat Pagi";
  } else if ($hour >= strtotime("11:00") && $hour <= strtotime("13:59")) {
    $wording = "Selamat Siang";
  } else if ($hour >= strtotime("14:00") && $hour <= strtotime("18:29")) {
    $wording = "Selamat Sore";
  } else {
    $wording = "Selamat Malam";
  }
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
    <link rel="stylesheet" href="<?php echo base_url();?>assets/admin/css/custom.css">
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
    <link rel="stylesheet" href="https://js.arcgis.com/4.21/esri/themes/light/main.css">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <link href="<?php echo base_url();?>assets/admin/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet" />
    <script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/daterangepicker/moment.js"></script>
    <script src="<?php echo base_url();?>assets/admin/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>

    <style>
        table.dataTable thead .sorting_asc {
            background-image: none !important;
        }
        table.dataTable thead .sorting {
            background-image: none !important;
        }
        table.dataTable thead .sorting_desc {
            background-image: none !important;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                    <?php echo $wording;?>, <?php echo $sess['nama'];?> <i class="fa fa-angle-down"></i>
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
                <div class="text-center">
                    <img src="<?php echo base_url();?>landingpage/assets/img/logo-white-flat.png" alt="Logo Pressensi" style="max-height: 33px; width: auto;">
                </div>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                    with font-awesome or any other icon font library -->
                    <li class="nav-item">
                        <a href="<?php echo base_url();?>Administrator/dashboard" class="nav-link <?php echo $menu['main'] == 'beranda' ? 'active' : '';?>">
                            <i class="nav-icon fa fa-home"></i>
                            <p>Beranda</p>
                        </a>
                    </li>
                    <?php if (!isset($sess['role_access'])) { ?>
                      <li class="nav-item has-treeview <?php echo $menu['main'] == 'pengaturan' ? 'menu-open' : '';?>">
                          <a href="#" class="nav-link <?php echo $menu['main'] == 'pengaturan' ? 'active' : '';?>">
                              <i class="nav-icon fa fa-cogs"></i>
                              <p>
                                  Pengaturan
                                  <i class="fas fa-angle-left right"></i>
                              </p>
                          </a>
                          <ul class="nav nav-treeview">
                              <li class="nav-item">
                                  <a href="<?php echo base_url();?>Administrator/jabatan" class="nav-link <?php echo $menu['child'] == 'pengaturan_jabatan' ? 'active' : '';?>">
                                  <p>Jabatan</p>
                                  </a>
                              </li>
                              <li class="nav-item">
                                  <a href="<?php echo base_url();?>Administrator/struktur" class="nav-link <?php echo $menu['child'] == 'pengaturan_stuktur' ? 'active' : '';?>">
                                  <p>Struktur Organisasi</p>
                                  </a>
                              </li>
                              <?php if ($this->session->userdata('user')['akses'] == 'admin_utama') { ?>
                                  <li class="nav-item">
                                      <a href="<?php echo base_url();?>Administrator/data_jaringan/wifi" class="nav-link <?php echo $menu['child'] == 'pengaturan_wifi' ? 'active' : '';?>">
                                      <p>Wifi</p>
                                      </a>
                                  </li>
                                  <li class="nav-item">
                                      <a href="<?php echo base_url();?>Administrator/data_jaringan/area" class="nav-link <?php echo $menu['child'] == 'pengaturan_lokasi' ? 'active' : '';?>">
                                      <p>Area / Lokasi</p>
                                      </a>
                                  </li>
                              <?php } ?>
                              <li class="nav-item">
                                  <a href="<?php echo base_url();?>Administrator/unit" class="nav-link <?php echo $menu['child'] == 'pengaturan_dept' ? 'active' : '';?>">
                                  <p>Data Unit Kerja</p>
                                  </a>
                              </li>
                              <li class="nav-item">
                                  <a href="<?php echo base_url();?>Administrator/jadwal_kerja" class="nav-link <?php echo $menu['child'] == 'pengaturan_pola' ? 'active' : '';?>">
                                  <p>Tipe Pola Kerja</p>
                                  </a>
                              </li>
                              <li class="nav-item">
                                  <a href="<?php echo base_url();?>Administrator/pengaturan_cuti" class="nav-link <?php echo $menu['child'] == 'pengaturan_cuti' ? 'active' : '';?>">
                                  <p>Batasan Cuti</p>
                                  </a>
                              </li>
                              <li class="nav-item">
                                  <a href="<?php echo base_url();?>Administrator/status_pengajuan" class="nav-link <?php echo $menu['child'] == 'pengaturan_status_pengajuan' ? 'active' : '';?>">
                                  <p>Status Pengajuan</p>
                                  </a>
                              </li>
                              <li class="nav-item">
                                  <a href="<?php echo base_url();?>Administrator/status_pegawai" class="nav-link <?php echo $menu['child'] == 'pengaturan_status_pegawai' ? 'active' : '';?>">
                                  <p>Status Pegawai</p>
                                  </a>
                              </li>
                              <li class="nav-item">
                                <a href="<?php echo base_url();?>Administrator/tipe_reimburse" class="nav-link <?php echo $menu['child'] == 'pengaturan_tipe_reimburse' ? 'active' : '';?>">
                                  <p>Tipe Reimburse</p>
                                </a>
                              </li>
                          </ul>
                      </li>
                    <?php } ?>
                    <li class="nav-item has-treeview <?php echo $menu['main'] == 'personalia' ? 'menu-open' : '';?>">
                        <a href="#" class="nav-link <?php echo $menu['main'] == 'personalia' ? 'active' : '';?>">
                            <i class="nav-icon fa fa-users"></i>
                            <p>Personalia<i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                        <li class="nav-item">
                                <a href="<?php echo base_url();?>Administrator/pola_kerkar" class="nav-link <?php echo $menu['child'] == 'personalia_pola' ? 'active' : '';?>">
                                    <p>Pola Kerja Staff</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo base_url();?>Administrator/pegawai" class="nav-link <?php echo $menu['child'] == 'personalia_staff' ? 'active' : '';?>">
                                    <p>Data Staff</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo base_url();?>Administrator/history_pengunduran_diri" class="nav-link <?php echo $menu['child'] == 'personalia_pengunduran' ? 'active' : '';?>">
                                    <p>Pengunduran Diri</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo base_url();?>Administrator/kinerja_pegawai" class="nav-link <?php echo $menu['child'] == 'personalia_kinerja' ? 'active' : '';?>">
                                    <p>Kinerja Staff</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item has-treeview <?php echo $menu['main'] == 'kehadiran' ? 'menu-open' : '';?>">
                        <a href="#" class="nav-link <?php echo $menu['main'] == 'kehadiran' ? 'active' : '';?>">
                            <i class="nav-icon fa fa-book"></i>
                            <p>Kehadiran<i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?php echo base_url();?>Administrator/data_absensi" class="nav-link <?php echo $menu['child'] == 'kehadiran_absensi' ? 'active' : '';?>">
                                    <p>Data Absensi</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo base_url();?>Administrator/absensi/listAbsensi" class="nav-link <?php echo $menu['child'] == 'kehadiran_absen_ulang' ? 'active' : '';?>">
                                    <p>Absen Ulang</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo base_url('Administrator/data_absensi/rekap');?>" class="nav-link <?php echo $menu['child'] == 'kehadiran_rekap' ? 'active' : '';?>">
                                    <p>Rekap Absensi</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item has-treeview <?php echo $menu['main'] == 'pengajuan' ? 'menu-open' : '';?>">
                        <a href="#" class="nav-link <?php echo $menu['main'] == 'pengajuan' ? 'active' : '';?>">
                            <i class="nav-icon fa fa-bookmark"></i>
                            <p>Pengajuan<i class="fas fa-angle-left right"></i><span id="alert" class="right badge badge-danger">!</span></p>
                        </a>
                        <ul class="nav nav-treeview">
                          <li class="nav-item">
                            <a href="<?php echo base_url();?>Administrator/cuti" class="nav-link <?php echo $menu['child'] == 'pengajuan_cuti' ? 'active' : '';?>">
                              <p>Cuti, Izin & Sakit <span id="alert" class="right badge badge-danger">!</span></p>
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="<?php echo base_url();?>Administrator/lembur" class="nav-link <?php echo $menu['child'] == 'pengajuan_lembur' ? 'active' : '';?>">
                              <p>Lembur<span id="alert" class="right badge badge-danger">!</span></p>
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="<?php echo base_url();?>Administrator/reimburse" class="nav-link <?php echo $menu['child'] == 'pengajuan_reimburse' ? 'active' : '';?>">
                              <p>Reimburse <span id="alert" class="right badge badge-danger">!</span></p>
                            </a>
                          </li>
                        </ul>
                    </li>
                    <!-- <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-crown"></i>
                            <p>Ranking<i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?php echo base_url();?>Administrator/rangking" class="nav-link">
                                    <p>Pegawai</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?php echo base_url();?>Administrator/rangking/department" class="nav-link">
                                    <p>Unit Kerja</p>
                                </a>
                            </li>
                        </ul>
                    </li> -->
                    <li class="nav-item">
                        <a href="<?php echo base_url();?>Administrator/event" class="nav-link <?php echo $menu['main'] == 'kalender' ? 'active' : '';?>">
                        <i class="nav-icon far fa-calendar-alt"></i>
                        <p>Kalender Perusahaan</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo base_url();?>Administrator/maps" class="nav-link <?php echo $menu['main'] == 'map' ? 'active' : '';?>">
                        <i class="nav-icon fas fa-map-marked-alt"></i>
                        <p>Maps</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo base_url();?>Administrator/pengumuman" class="nav-link <?php echo $menu['main'] == 'pengumuman' ? 'active' : '';?>">
                        <i class="nav-icon fas fa-bullhorn"></i>
                        <p>Buat Pengumuman</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo base_url();?>Administrator/akses_absensi" class="nav-link <?php echo $menu['main'] == 'akses_absensi' ? 'active' : '';?>">
                        <i class="nav-icon fas fa-map-pin"></i>
                        <p>Akses Absensi</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo base_url();?>Administrator/dispensasi" class="nav-link <?php echo $menu['main'] == 'dispensasi' ? 'active' : '';?>">
                        <i class="nav-icon fas fa-business-time"></i>
                        <p>Dispensasi</p>
                        </a>
                    </li>
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>
        <script type="text/javascript">
            var alert = document.querySelector('alert');

                    function updateBadge(value) {
                    badge.innerHTML = value;
                }
         function alert() {
           $.ajax({
                url:"<?php echo base_url() ?>Administrator/lembur/select",
                method: 'post',
                dataType: 'json',
                success: function(data)
                {
                    if (result.status == true) {
            var dataResult = result.data;
            var date = formatDate(dataResult.tanggal_lembur);
            if (dataResult.status == 0) {
              var stat = "<span class='label label-warning'>Pending</span>";
              document.getElementById('acc').style.visibility = 'visible';
              document.getElementById('reject').style.visibility = 'visible';
            }
            if (dataResult.status == 1) {
              var stat = "<span class='label label-success'>ACC</span>";
              document.getElementById('acc').style.visibility = 'hidden';
              document.getElementById('reject').style.visibility = 'hidden';
            }
            if (dataResult.status == 2) {
              var stat = "<span class='label label-danger'>Ditolak</span>";
              document.getElementById('acc').style.visibility = 'hidden';
              document.getElementById('reject').style.visibility = 'hidden';
            }
                }}
            });
          }  
   </script>