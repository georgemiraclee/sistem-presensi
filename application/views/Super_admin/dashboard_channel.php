<!-- START BREADCRUMB -->
                <ul class="breadcrumb">
                    <li><a href="#">Home</a></li>                    
                    <li class="active">Dashboard</li>
                </ul>
                <!-- END BREADCRUMB -->                       
                
                <div class="row">

                        <div class="col-md-12">
                            <!-- START ACCORDION -->        
                            <div class="panel-group accordion">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <h4 class="panel-title panel-danger">
                                            <a href="#accOneColThree" class="float-right panel-title">
                                                Filter <span class="fa fa-filter"></span>
                                            </a>
                                        </h4>
                                    </div> 
                                    <div class="panel-body" id="accOneColThree">
                                        <form method="post" id="formFilter" action="javascript:void(0);">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <h6>SKPD</h6>
                                                    <div style="height: 100px; overflow: auto;">
                                                        <?php foreach ($skpd as $key => $value) {?>
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox" name="skpd[]" value="<?php echo $value->id_unit;?>">
                                                                    <?php echo ucwords($value->nama_unit);?>
                                                                </label>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <h6>Jabatan</h6>
                                                    <div style="height: 100px; overflow: auto;">
                                                        <?php foreach ($jabatan as $key => $value) {?>
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox" name="jabatan[]" value="<?php echo $value->jabatan;?>">
                                                                    <?php echo $value->jabatan;?>
                                                                </label>
                                                            </div>
                                                        <?php }?>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <h6>Jenis Kelamin</h6>
                                                    <div style="height: 100px; overflow: auto;">
                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="checkbox" value="l" name="jenkel[]"> Laki - Laki
                                                            </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="checkbox" value="p" name="jenkel[]"> Perempuan
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <h6>Status</h6>
                                                    <div style="height: 100px; overflow: auto;">
                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="checkbox" value="tepat_waktu" name="status[]"> Tepat Waktu
                                                            </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="checkbox" value="terlambat" name="status[]"> Terlambat
                                                            </label>
                                                        </div>
                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="checkbox" value="tidak_hadir" name="status[]"> Tidak Hadir
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <hr>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <input type="submit" value="Filter" name="" class="btn btn-primary btn float-right">
                                                    <!-- <button >Filter</button> -->
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- END ACCORDION -->                        
                        </div>

                    </div>

                <!-- PAGE CONTENT WRAPPER -->
                <div class="page-content-wrap">                    
                    <!-- <div class="row">
                        <div class="col-md-12">                            
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="panel-title-box">
                                        <h3>Daftar Hadir</h3>
                                    </div>
                                </div>
                                <div class="panel-body panel-body-table">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th width="50%">NIP</th>
                                                    <th width="20%">Nama</th>
                                                    <th width="20%">Waktu Datang</th>
                                                    <th width="30%">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody id="absensi">
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <?php if ($show == 'false') { ?>
                    <div class="row">
                        <div class=" col-md-8">
                            <div class="col-md-12">
                                 <div class="widget widget-primary widget-padding-sm">
                                        <div class="widget-big-int plugin-clock">00:00</div>                            
                                        <div class="widget-subtitle plugin-date">Loading...</div>
                                        <div class="widget-controls">                                
                                            <a href="#" class="widget-control-right"><span class="fa fa-times"></span></a>
                                        </div>                            
                                        <div class="widget-buttons widget-c3">
                                            <div class="col">
                                                <a href="#"><span class="fa fa-clock-o" style="color: grey"></span></a>
                                            </div>
                                            <div class="col">
                                                <a href="#"><span class="fa fa-bell" style="color: grey"></span></a>
                                            </div>
                                            <div class="col">
                                                <a href="#"><span class="fa fa-calendar" style="color: grey"></span></a>
                                            </div>
                                        </div>                            
                                    </div>                   
                            </div>
                            <div class="col-md-4">                        
                                <!-- START WIDGET MESSAGES -->
                                <div class="widget widget-info  widget-item-icon">
                                    <div class="widget-item-left">
                                        <span class="fa fa-check-circle-o"></span>
                                    </div>                             
                                    <div class="widget-data">
                                        <div class="widget-int num-count" id="jumlah_hadir"></div>
                                        <div class="widget-title">Pegawai</div>
                                        <div class="widget-subtitle">Datang Tepat waktu</div>
                                    </div>      
                                </div>                            
                                <!-- END WIDGET MESSAGES -->
                            </div>
                            <div class="col-md-4">                        
                                 <!-- START WIDGET MESSAGES -->
                                <div class="widget widget-item-icon"  style="background-color: #EA951D">
                                    <div class="widget-item-left">
                                        <span class="fa fa-clock-o"></span>
                                    </div>                             
                                    <div class="widget-data">
                                        <div class="widget-int num-count" id="terlambat"></div>
                                        <div class="widget-title">Pegawai</div>
                                        <div class="widget-subtitle">Datang Terlambat</div>
                                    </div>      
                                </div>                            
                                <!-- END WIDGET MESSAGES -->
                            </div>
                            <div class="col-md-4">                        
                                 <!-- START WIDGET MESSAGES -->
                                <div class="widget widget-danger widget-item-icon" >
                                    <div class="widget-item-left">
                                        <span class="fa fa-times-circle-o"></span>
                                    </div>                             
                                    <div class="widget-data">
                                        <div class="widget-int num-count" id="tidak_hadir"></div>
                                        <div class="widget-title">Pegawai</div>
                                        <div class="widget-subtitle">Tidak Datang</div>
                                    </div>      
                                </div>                            
                                <!-- END WIDGET MESSAGES -->
                            </div>
                            <div class="col-md-6">                         
                                <div class="widget" style="background-color: #1C59A9">
                                    <div class="widget-title">Waktu Datang Rata-rata</div>
                                    <div class="widget-subtitle"></div>
                                    <div class="widget-int"  id="waktu_datang"></div>
                                    <div class="widget-controls">
                                        <a href="#" class="widget-control-left"><span class="fa fa-upload"></span></a>
                                        <a href="#" class="widget-control-right"><span class="fa fa-times"></span></a>
                                    </div>
                                </div>
                            </div>
                             <div class="col-md-6">                         
                                <div class="widget" style="background-color: #D2BF28">
                                    <div class="widget-title">Waktu Pulang Rata-rata</div>
                                    <div class="widget-subtitle"></div>
                                    <div class="widget-int"  id="waktu_pulang"></div>
                                    <div class="widget-controls">
                                        <a href="#" class="widget-control-left"><span class="fa fa-upload"></span></a>
                                        <a href="#" class="widget-control-right"><span class="fa fa-times"></span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="panel panel-primary" >
                                <div class="panel-body scroll"  style="max-height: 400px; background:transparent;">     
                                <!-- START CONTENT FRAME RIGHT -->
                                        <div class="list-group list-group-contacts col-md-12 border-bottom">
                                            <div id="pegawai"></div>                                                                   
                                        </div>                                        
                                    <!-- END CONTENT FRAME RIGHT -->                         
                                </div>                                   
                            </div>
                            
                        </div>
                    </div>
                    <?php } ?>
                      <div class="row">
                        <div class="col-md-8">                            
                            <!-- START PROJECTS BLOCK -->
                            <div class="panel panel-default">
                                <div class="panel-body panel-body-table">
                                    <div id="container2"></div>                                    
                                </div>
                            </div>
                            <!-- END PROJECTS BLOCK -->
                        </div>
                        <div class="col-md-4">                            
                            <!-- START PROJECTS BLOCK -->
                            <div class="panel panel-default">
                                <div class="panel-body panel-body-table">
                                    <div id="container3"></div>                                    
                                </div>
                            </div>
                            <!-- END PROJECTS BLOCK -->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">                            
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="panel-title-box">
                                        <h3>Statistik Kehadiran</h3>
                                    </div>
                                </div>
                                <div class="panel-body panel-body-table">
                                    <div class="col-md-12">
                                        <div id="container"></div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>

                        <div class="col-md-7">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Data Posisi Absen</h3>
                                    <div class="float-right" style="width: 200px;">
                                        <input type="text" id="target" class="form-control"/>
                                    </div>                                
                                </div>
                                <div class="panel-body panel-body-map">
                                    <div id="new_google_search_map" style="width: 100%; height: 377px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">                            
                            <!-- START PROJECTS BLOCK -->
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="panel-title-box">
                                        <h3>Statistik Absensi Tahun ini</h3>
                                    </div>
                                </div>
                                <div class="panel-body panel-body-table">
                                    <div id="statistik"></div>                                    
                                </div>
                            </div>
                            <!-- END PROJECTS BLOCK -->
                        </div>
                    </div>
                    <?php if ($show == 'false') {?>
                    <div class="row">
                        <div class="col-md-12">                            
                            <!-- START PROJECTS BLOCK -->
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="panel-title-box">
                                        <h3>Absensi Berdasarkan SKPD</h3>
                                    </div>
                                </div>
                                <div class="panel-body panel-body-table">
                                    <div id="statistikSKPD"></div>                                    
                                </div>
                            </div>
                            <!-- END PROJECTS BLOCK -->
                        </div>
                    </div>
                    <?php } ?>
                    
                </div>
                <!-- END PAGE CONTENT WRAPPER -->                                
            </div>            
            <!-- END PAGE CONTENT -->
        </div>
        <!-- END PAGE CONTAINER -->
       
<script>
    var data = <?php echo $map;?>;
</script>
         <audio id="audio-alert" src="<?php echo base_url();?>/assets/audio/alert.mp3" preload="auto"></audio>
        <audio id="audio-fail" src="<?php echo base_url();?>/assets/audio/fail.mp3" preload="auto"></audio>
        <!-- END PRELOADS -->          
        
    <!-- START SCRIPTS -->
        <!-- START PLUGINS -->
        <script type="text/javascript" src="<?php echo base_url();?>/assets/js/plugins/jquery/jquery.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>/assets/js/plugins/jquery/jquery-ui.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>/assets/js/plugins/bootstrap/bootstrap.min.js"></script>        
        <!-- END PLUGINS -->

        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB-rB6cp32C9kALtCmg_EFiFpLgKwNjEZs&libraries=places&sensor=false"></script>

        <!-- START THIS PAGE PLUGINS-->        
        <script type='text/javascript' src='<?php echo base_url();?>/assets/js/plugins/icheck/icheck.min.js'></script>
        <script type="text/javascript" src="<?php echo base_url();?>/assets/js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>/assets/js/plugins/scrolltotop/scrolltopcontrol.js"></script>

        <script type="text/javascript" src="<?php echo base_url();?>/assets/js/plugins/morris/raphael-min.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>/assets/js/plugins/morris/morris.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>/assets/js/plugins/rickshaw/d3.v3.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>/assets/js/plugins/rickshaw/rickshaw.min.js"></script>
        <script type='text/javascript' src='<?php echo base_url();?>/assets/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js'></script>
        <script type='text/javascript' src='<?php echo base_url();?>/assets/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js'></script>
        <script type="text/javascript" src="<?php echo base_url();?>/assets/js/plugins/owl/owl.carousel.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>/assets/js/plugins/daterangepicker/daterangepicker.js"></script>
        <!-- END THIS PAGE PLUGINS-->        
         <script type="text/javascript" src="<?php echo base_url();?>/assets/js/plugins/datatables/jquery.dataTables.min.js"></script>
          <script type="text/javascript" src="<?php echo base_url();?>/assets/js/plugins/bootstrap/bootstrap-datepicker.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>/assets/js/plugins/bootstrap/bootstrap-datepicker.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>/assets/js/plugins/bootstrap/bootstrap-timepicker.min.js"></script>   
         <script type="text/javascript" src="<?php echo base_url();?>/assets/js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js"></script>             
        <script type="text/javascript" src="<?php echo base_url();?>/assets/js/plugins/bootstrap/bootstrap-file-input.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>/assets/js/plugins/bootstrap/bootstrap-select.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>/assets/js/plugins/tagsinput/jquery.tagsinput.min.js"></script>
        <!-- START TEMPLATE -->
        <script type="text/javascript" src="<?php echo base_url();?>/assets/js/settings.js"></script>
        
        <script type="text/javascript" src="<?php echo base_url();?>/assets/js/plugins.js"></script>        
        <script type="text/javascript" src="<?php echo base_url();?>/assets/js/actions.js"></script> 
        <script type="text/javascript" src="<?php echo base_url();?>/assets/js/demo_maps.js"></script> 

        <script src="<?php echo base_url();?>/assets/js/plugins/highcharts/code/highcharts.js"></script>
        <script src="https://code.highcharts.com/highcharts-3d.js"></script>

        <script src="<?php echo base_url();?>/assets/js/plugins/highcharts/code/modules/exporting.js"></script>      
        <!-- END TEMPLATE -->
    <!-- END SCRIPTS -->         
    </body>
</html>
<style type="text/css">
    .highcharts-credits{
        display: none;
    }
</style>
<script type="text/javascript">
    $(document).ready(function() {

        $('#formFilter').on('submit',(function(e) {
            var url = "<?php echo base_url(); ?>";
            // Get SKPD
            var skpd = document.getElementsByName("skpd[]");
            var newSkpd = "";
            for (var i=0, n=skpd.length;i<n;i++) {
                if (skpd[i].checked) {
                    newSkpd += ","+skpd[i].value;
                }
            }
            if (newSkpd) newSkpd = newSkpd.substring(1);

            //Get JABATAN
            var jabatan = document.getElementsByName("jabatan[]");
            var newjabatan = "";
            for (var i=0, n=jabatan.length;i<n;i++) {
                if (jabatan[i].checked) {
                    newjabatan += ","+jabatan[i].value;
                }
            }
            if (newjabatan) newjabatan = newjabatan.substring(1);

            //Get JENKEL
            var jenkel = document.getElementsByName("jenkel[]");
            var newjenkel = "";
            for (var i=0, n=jenkel.length;i<n;i++) {
                if (jenkel[i].checked) {
                    newjenkel += ","+jenkel[i].value;
                }
            }
            if (newjenkel) newjenkel = newjenkel.substring(1);

            //Get STATUS
            var status = document.getElementsByName("status[]");
            var newstatus = "";
            for (var i=0, n=status.length;i<n;i++) {
                if (status[i].checked) {
                    newstatus += ","+status[i].value;
                }
            }
            if (newstatus) newstatus = newstatus.substring(1);

            url += 'DashboardChannel/getDashboard/'+<?php echo $id_channel;?>+'?skpd='+newSkpd+'&jabatan='+newjabatan+'&jenkel='+newjenkel+'&status='+newstatus;
            window.location = url;
        }));

        var urlSelect = "<?php echo base_url('DashboardChannel/daftarHadir'); ?>";
        <?php if ($show == 'true') { ?>
            urlSelect += '?skpd=<?php echo $this->input->get("skpd");?>&jabatan=<?php echo $this->input->get("jabatan");?>&jenkel=<?php echo $this->input->get("jenkel");?>&status=<?php echo $this->input->get("status");?>';
        <?php } ?>
        var thumbClicked = false;
        function FilterC() {
            thumbClicked = true;
            urlSelect = "<?php echo base_url('DashboardChannel/daftarHadirAasd'); ?>";
            dataAbsensi();
        }

        setInterval(dataAbsensi, 10000); //300000 MS == 5 minutes

        $.ajax({
            method  : 'POST',
            url     : urlSelect,
            success: function(data, status, xhr) {
                var result = JSON.parse(xhr.responseText);
                var dataJumlahHadir = result.jumlah_hadir;
                var dataTidakHadir = result.tidak_hadir;
                var dataTerlambat = result.terlambat;
                var dataPegawai = result.data;
                var dataWaktuDatang = result.waktu_datang;
                var dataWaktuPulang = result.waktu_pulang;

                $('#jumlah_hadir').html(dataJumlahHadir);
                $('#tidak_hadir').html(dataTidakHadir);
                $('#terlambat').html(dataTerlambat);
                $('#pegawai').html(dataPegawai);
                $('#waktu_datang').html(dataWaktuDatang);
                $('#waktu_pulang').html(dataWaktuPulang);

                if (result.status == true) {
                    var dataResult = result.data;
                    var tampil = result.data;
                    $('#absensi').html(tampil);
                }else{
                    $('#absensi').html('');
                }
            },
            error: function(data) {
            }
        });

        function dataAbsensi() {
            $.ajax({
                method  : 'POST',
                url     : urlSelect,
                success: function(data, status, xhr) {
                    var result = JSON.parse(xhr.responseText);
                    var dataJumlahHadir = result.jumlah_hadir;
                    var dataTidakHadir = result.tidak_hadir;
                    var dataTerlambat = result.terlambat;
                    var dataPegawai = result.data;

                    $('#jumlah_hadir').html(dataJumlahHadir);
                    $('#tidak_hadir').html(dataTidakHadir);
                    $('#terlambat').html(dataTerlambat);
                    $('#pegawai').html(dataPegawai);


                    if (result.status == true) {
                        var dataResult = result.data;
                        var tampil = result.data;
                        
                        $('#absensi').html(tampil);
                    }else{
                        $('#absensi').html('');
                    }
                },
                error: function(data) {
                }
            });
        }
    });
</script>

<script type="text/javascript">
$(function () {
    Highcharts.chart('container', {
        chart: {
            type: 'pie',
            options3d: {
                enabled: true,
                alpha: 45,
                beta: 0
            }
        },
        title: {
            text: ''
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                depth: 35,
                dataLabels: {
                    enabled: true,
                    format: '{point.name}'
                }
            }
        },
        series: [{
            type: 'pie',
            name: 'Pegawai',
             data: <?php echo json_encode($performa, JSON_NUMERIC_CHECK); ?>
        }]
    });
});
        </script>

<?php echo $line_chart;?>






