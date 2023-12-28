<!-- START BREADCRUMB -->
<ul class="breadcrumb">
    <li><a href="<?php echo base_url();?>DashboardChannel/getDashboard/<?php echo $data_channel->id_channel;?>">Beranda</a></li>
    <li class="active">Perusahaan</li>
</ul>
<!-- END BREADCRUMB --> 
<?php
    $message = $this->session->flashdata('notif');
    if ($message) {
        echo '<div class="col-md-12">
            '.$message.'
        </div>';
    }
?>                    

<!-- PAGE CONTENT WRAPPER -->
<div class="page-content-wrap">
    <div class="CONTAINER">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Data Perusahaan</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h3>
                                <?php echo $data_channel->nama_channel;?>
                                <a href="<?php echo base_url();?>Akun/ubahData"><span class="fa fa-edit" style="color: #A9A7A2" data-toggle="tooltip" data-placement="right" title="Ubah Data"></span></a>
                            </h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <table>
                                        <tr>
                                            <td style="color: #A9A7A2; font-size: 15px;"><span class="fa fa-building"></span></td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td style="font-size: 13px;"><?php echo $data_channel->alamat_channel;?></td>
                                        </tr>
                                        <tr>
                                            <td style="color: #A9A7A2; font-size: 15px;"><span class="fa fa-phone"></span></td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td style="font-size: 13px;"><?php echo $data_channel->telp_channel;?></td>
                                        </tr>
                                        <tr>
                                            <td style="color: #A9A7A2; font-size: 15px;"><span class="fa fa-envelope"></span></td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td style="font-size: 13px;"><?php echo $data_channel->email_channel;?></td>
                                        </tr>
                                        <tr>
                                            <td style="color: #A9A7A2; font-size: 15px;"><span class="fa fa-globe"></span></td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td style="font-size: 13px;"><?php echo $data_channel->website_channel;?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="profile-image" style="position: relative;">
                                <img src="<?php echo base_url();?>assets/images/channel/<?php echo $data_channel->icon_channel;?>" width="200" align="right"/>
                            </div>
                        </div>
                    </div>
                    <br><br>
                    <div class="row">
                        <div class="col-md-2 col-xs-12">
                            <div class="panel-group accordion">
                                <div class="panel-heading" style="background-color: #2986BF">
                                    <p style="font-size: 11px; color: white;" class="panel-title">
                                        <a href="#accOneColOne">
                                            <strong>Jumlah Total Personalia</strong>
                                        </a>
                                    </p>
                                </div>                                
                                <div class="panel-body panel-body-open panel-body-table" id="accOneColOne">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th><center><?php echo $data_channel->jumlah_personalia;?></center></th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>                                
                            </div>
                        </div>
                        <div class="col-md-2 col-xs-12">
                            <div class="panel-group accordion">
                                <div class="panel-heading" style="background-color: #2986BF">
                                    <p style="font-size: 11px; color: white;" class="panel-title">
                                        <a href="#accOneColTwo">
                                            <strong>Jenis Kelamin</strong>
                                        </a>
                                    </p>
                                </div>                                
                                <div class="panel-body panel-body-open panel-body-table" id="accOneColTwo">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Laki-Laki</th>
                                                <th style="background-color: #EBE4D4;">-</th>
                                            </tr>
                                            <tr>
                                                <th>Perempuan</th>
                                                <th style="background-color: #EBE4D4;">-</th>
                                            </tr>
                                            <tr>
                                                <th>Tidak Ada Data</th>
                                                <th style="background-color: #EBE4D4;">-</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>                                
                            </div>
                        </div>
                        <div class="col-md-2 col-xs-12">
                            <div class="panel-group accordion">
                                <div class="panel-heading" style="background-color: #2986BF;">
                                    <p style="font-size: 11px; color: white;" class="panel-title">
                                        <a href="#accOneCol3">
                                            <strong>Status Perkawinan</strong>
                                        </a>
                                    </p>
                                </div>                                
                                <div class="panel-body panel-body-open panel-body-table" id="accOneCol3">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Menikah</th>
                                                <th style="background-color: #EBE4D4;">-</th>
                                            </tr>
                                            <tr>
                                                <th>Belum Menikah</th>
                                                <th style="background-color: #EBE4D4;">-</th>
                                            </tr>
                                            <tr>
                                                <th>Janda</th>
                                                <th style="background-color: #EBE4D4;">-</th>
                                            </tr>
                                            <tr>
                                                <th>Duda</th>
                                                <th style="background-color: #EBE4D4;">-</th>
                                            </tr>
                                            <tr>
                                                <th>Tidak Ada Data</th>
                                                <th style="background-color: #EBE4D4;">-</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-xs-12">
                            <div class="panel-group accordion">
                                <div class="panel-heading" style="background-color: #2986BF">
                                    <p style="font-size: 11px; color: white;" class="panel-title">
                                        <a href="#accOneCol4">
                                            <strong>Agama</strong>
                                        </a>
                                    </p>
                                </div>                                
                                <div class="panel-body panel-body-open panel-body-table" id="accOneCol4">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Islam</th>
                                                <th style="background-color: #EBE4D4;">-</th>
                                            </tr>
                                            <tr>
                                                <th>Protestan</th>
                                                <th style="background-color: #EBE4D4;">-</th>
                                            </tr>
                                            <tr>
                                                <th>Katolik</th>
                                                <th style="background-color: #EBE4D4;">-</th>
                                            </tr>
                                            <tr>
                                                <th>Hindu</th>
                                                <th style="background-color: #EBE4D4;">-</th>
                                            </tr>
                                            <tr>
                                                <th>Budha</th>
                                                <th style="background-color: #EBE4D4;">-</th>
                                            </tr>
                                            <tr>
                                                <th>Khonghucu</th>
                                                <th style="background-color: #EBE4D4;">-</th>
                                            </tr>
                                            <tr>
                                                <th>Tidak Ada Data</th>
                                                <th style="background-color: #EBE4D4;">-</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-xs-12">
                            <div class="panel-group accordion">
                                <div class="panel-heading" style="background-color: #2986BF">
                                    <p style="font-size: 11px; color: white;" class="panel-title">
                                        <a href="#accOneCol6">
                                            <strong>Range Umur Karyawan</strong>
                                        </a>
                                    </p>
                                </div>                                
                                <div class="panel-body panel-body-open panel-body-table" id="accOneCol6">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>< 20 Tahun</th>
                                                <th style="background-color: #EBE4D4;">-</th>
                                            </tr>
                                            <tr>
                                                <th>20 < 30 Tahun</th>
                                                <th style="background-color: #EBE4D4;">-</th>
                                            </tr>
                                            <tr>
                                                <th>30 < 40 Tahun</th>
                                                <th style="background-color: #EBE4D4;">-</th>
                                            </tr>
                                            <tr>
                                                <th>> 40 Tahun</th>
                                                <th style="background-color: #EBE4D4;">-</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>   
        </div>
    </div>
</div>

<audio id="audio-alert" src="<?php echo base_url();?>/assets/audio/alert.mp3" preload="auto"></audio>
<audio id="audio-fail" src="<?php echo base_url();?>/assets/audio/fail.mp3" preload="auto"></audio>

<script type="text/javascript" src="<?php echo base_url();?>/assets/js/plugins/jquery/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>/assets/js/plugins/jquery/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>/assets/js/plugins/bootstrap/bootstrap.min.js"></script>        

<script type='text/javascript' src='<?php echo base_url();?>/assets/js/plugins/icheck/icheck.min.js'></script>
<script type="text/javascript" src="<?php echo base_url();?>/assets/js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js"></script>

<script type="text/javascript" src="<?php echo base_url();?>/assets/js/plugins/datatables/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>/assets/js/plugins/bootstrap/bootstrap-datepicker.js"></script>                
<script type="text/javascript" src="<?php echo base_url();?>/assets/js/plugins/bootstrap/bootstrap-file-input.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>/assets/js/plugins/bootstrap/bootstrap-select.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>/assets/js/plugins/tagsinput/jquery.tagsinput.min.js"></script>

<script type="text/javascript" src="<?php echo base_url();?>/assets/js/settings.js"></script>

<script type="text/javascript" src="<?php echo base_url();?>/assets/js/plugins.js"></script>        
<script type="text/javascript" src="<?php echo base_url();?>/assets/js/actions.js"></script>        

</body>
</html>