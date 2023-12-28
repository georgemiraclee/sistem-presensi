<?php 
    $sess = $this->session->userdata('user'); 
    $channel = $this->db_select->select_all_where('tb_channel','is_aktif = 1');
?>
<!DOCTYPE html>
<html lang="en">
    <head>        
        <!-- META SECTION -->
        <title>Pressensi</title>            
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        
        <link rel="icon" href="<?php echo base_url();?>assets/images/icon/pressensi.png" type="image/png">
        <link rel="shortcut icon" href="<?php echo base_url();?>assets/images/icon/pressensi.png" type="img/x-icon">
        <!-- END META SECTION -->
        
        <!-- CSS INCLUDE -->        
        <link rel="stylesheet" type="text/css" id="theme" href="<?php echo base_url();?>assets/css/theme-default.css"/>
        <link rel="stylesheet" type="text/css" id="theme" href="<?php echo base_url();?>assets/js/plugins/sweetalert/dist/sweetalert.css"/>
        <!-- EOF CSS INCLUDE -->                                    
    </head>
    <body>
        <!-- START PAGE CONTAINER -->
        <div class="page-container">
            
            <!-- START PAGE SIDEBAR -->
            <div class="page-sidebar">
                <!-- START X-NAVIGATION -->
                <ul class="x-navigation">
                    <li class="xn-logo">
                        <a href="index.html">Pressensi</a>
                        <a href="#" class="x-navigation-control"></a>
                    </li>
                    <li class="xn-profile">
                    <li class="xn-profile">
                        <a href="#" class="profile-mini">
                            <img src="https://www.awicons.com/free-icons/download/application-icons/dragon-soft-icons-by-artua.com/png/512/User.png" alt="Foto User"/>
                        </a>
                        <div class="profile">
                            <div class="profile-image">
                                <img src="https://www.awicons.com/free-icons/download/application-icons/dragon-soft-icons-by-artua.com/png/512/User.png" alt="Foto User"/>
                            </div>
                            <div class="profile-data">
                                <div class="profile-data-name"><?php echo ucwords($sess['nama']);?></div>
                            </div>
                        </div>                                                                        
                    </li>                    
                    <li>
                        <a href="<?php echo base_url();?>Data_channel"><span class="fa fa-book"></span> <span class="xn-text">Data Channel</span></a>
                    </li>
                    <?php foreach ($channel as $key => $value) { ?>
                        <li class="xn-openable">
                            <a href="#"><span class="fa fa-suitcase"></span> <span class="xn-text"><?php echo ucwords($value->nama_channel);?></span></a>
                            <ul>
                                <li><a href="<?php echo base_url();?>DashboardChannel/getDashboard/<?php echo $value->id_channel;?>"><span class="fa fa-home"></span> Beranda</a></li>
                                <li><a href="<?php echo base_url();?>Akun/getAkun/<?php echo $value->id_channel;?>"><span class="fa fa-folder-o"></span> Perusahaan</a></li>
                                <li class="xn-openable">
                                    <a href="#"><span class="fa fa-smile-o"></span> Personalia</a>
                                    <ul>
                                        <li><a href="<?php echo base_url();?>Pegawai/getPegawai/<?php echo $value->id_channel;?>">Data Pegawai</a></li>
                                    </ul>
                                </li>
                                <li class="xn-openable">
                                    <a href="#"><span class="fa fa-book"></span> Kehadiran</a>
                                    <ul>
                                        <li><a href="<?php echo base_url();?>Pegawai/getPegawai/<?php echo $value->id_channel;?>">Data Absensi</a></li>
                                        <li><a href="<?php echo base_url();?>Pegawai/getPegawai/<?php echo $value->id_channel;?>">Rekap Absensi</a></li>
                                    </ul>
                                </li>
                                <li><a href="<?php echo base_url();?>Peta_lokasi/showMaps/<?php echo $value->id_channel;?>"><span class="fa fa-map-marker"></span> Maps</a></li>
                                <li class="xn-openable">
                                    <a href="#"><span class="fa fa-cogs"></span> Pengaturan</a>
                                    <ul>
                                        <li><a href="<?php echo base_url();?>Jabatan/getJabatan/<?php echo $value->id_channel;?>">Jabatan</a></li>
                                        <li><a href="<?php echo base_url();?>StatusPegawai/getStatus/<?php echo $value->id_channel;?>">Status Pegawai</a></li>
                                        <li><a href="<?php echo base_url();?>Data_unit/getUnit/<?php echo $value->id_channel;?>">Data Unit</a></li>
                                        <li><a href="<?php echo base_url();?>Data_jaringan/getArea/<?php echo $value->id_channel;?>">Jadwal Kerja</a></li>
                                        <li><a href="<?php echo base_url();?>Data_jaringan/getJaringan/<?php echo $value->id_channel;?>">Wifi</a></li>
                                        <li><a href="<?php echo base_url();?>Data_jaringan/getArea/<?php echo $value->id_channel;?>">Area / Lokasi</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>  
                    <?php } ?>  
                </ul>
                <!-- END X-NAVIGATION -->
            </div>
            <!-- END PAGE SIDEBAR -->
            
            <!-- PAGE CONTENT -->
            <div class="page-content">
                
                <!-- START X-NAVIGATION VERTICAL -->
                <ul class="x-navigation x-navigation-horizontal x-navigation-panel">
                    <!-- TOGGLE NAVIGATION -->
                    <li class="xn-icon-button">
                        <a href="#" class="x-navigation-minimize"><span class="fa fa-dedent"></span></a>
                    </li>
                    <!-- END TOGGLE NAVIGATION -->
                    <!-- SEARCH -->
                    <li class="xn-search">
                        <form role="form">
                            <input type="text" name="search" placeholder="Search..."/>
                        </form>
                    </li>   
                    <!-- END SEARCH -->
                    <!-- SIGN OUT -->
                    <li class="xn-icon-button float-right">
                        <a href="#"><span class="fa fa-gears"></span></a>
                        <a href="#" class="mb-control" data-box="#mb-signout"><span class="fa fa-sign-out"></span></a>
                    </li> 
                    <!-- END SIGN OUT -->
                    
                </ul>
                <!-- END X-NAVIGATION VERTICAL -->

 <!-- MESSAGE BOX-->
        <div class="message-box animated fadeIn" data-sound="alert" id="mb-signout">
            <div class="mb-container">
                <div class="mb-middle">
                    <div class="mb-title"><span class="fa fa-sign-out"></span> Log <strong>Out</strong> ?</div>
                    <div class="mb-content">
                        <p>Anda yakin akan meninggalkan aplikasi ini ?</p>                    
                        <p>Tekan "Tidak" jika ingin tetap pada aplikasi ini. Tekan "Ya" Untuk tetap keluar.</p>
                    </div>
                    <div class="mb-footer">
                        <div class="float-right">
                            <a href="<?php echo base_url();?>Login/logout" class="btn btn-success btn-lg">Ya</a>
                            <button class="btn btn-default btn-lg mb-control-close">Tidak</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END MESSAGE BOX-->