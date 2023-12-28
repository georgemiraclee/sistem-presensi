<section class="content">
    <div class="container-fluid">
        <!-- filter -->
        <?php
        if ($show == true){
            $keterangan = " Hari Ini";
        }else{
            $keterangan = "";
        }
        ?>
        <div class="block-header">
            <h2><span class="float-left"> Dashboard</span> <button class="btn bg-indigo btn-xs float-right" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                        <i class="material-icons">filter_list</i><span class="icon-name">FILTER</span> 
                </button></h2>
                    
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="collapse" id="collapseExample">
                    <div class="well" id="accOneColThree">
                        <form method="post" id="formFilter" action="javascript:void(0);">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class='form-group form-group-sm'>
                                        <a type="button" id="reportrange" class="btn btn-info">
                                            <i class="material-icons">event</i>&nbsp;
                                            <span></span> 
                                            <b class="caret"></b>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <input type="hidden" name="dari" id="dari">
                                <input type="hidden" name="sampai" id="sampai">
                                <div class="col-md-3">
                                    <h6>SKPD</h6>
                                    <div style="height: 200px; overflow: auto;">
                                        <?php foreach ($skpd as $key => $value) {?>
                                           <div class="demo-checkbox">
                                                 <input type="checkbox" id="1basic_checkbox_<?php echo $value->id_unit;?>" name="skpd[]" value="<?php echo $value->id_unit;?>" />
                                                <label for="1basic_checkbox_<?php echo $value->id_unit;?>"> <?php echo ucwords($value->nama_unit);?></label>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <h6>JABATAN</h6>
                                    <div style="height: 200px; overflow: auto;">
                                        <?php foreach ($jabatan as $key => $value) {?>
                                           <div class="demo-checkbox">
                                                 <input type="checkbox" id="2basic_checkbox_<?php echo $value->id_jabatan;?>" name="jabatan[]" value="<?php echo $value->id_jabatan;?>" />
                                                <label for="2basic_checkbox_<?php echo $value->id_jabatan;?>"> <?php echo ucwords($value->nama_jabatan);?></label>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <h6>JENIS KELAMIN</h6>
                                    <div style="height: 200px; overflow: auto;">
                                        <div class="demo-checkbox">
                                            <input type="checkbox" id="basic_checkbox_L" value="l" name="jenkel[]"/>
                                            <label for="basic_checkbox_L">Laki - Laki</label>
                                             <input type="checkbox" id="basic_checkbox_P" value="p" name="jenkel[]"/>
                                            <label for="basic_checkbox_P">Perempuan</label>
                                        </div>
                                    </div>
                                </div>
                                 <div class="col-md-3">
                                    <h6>STATUS</h6>
                                    <div style="height: 200px; overflow: auto;">
                                        <div class="demo-checkbox">
                                            <input type="checkbox" id="basic_checkbox_T" value="1" name="status[]"/>
                                                <label for="basic_checkbox_T">Tepat Waktu</label>
                                            <input type="checkbox" id="basic_checkbox_K" value="2" name="status[]"/>
                                                <label for="basic_checkbox_K">Kesiangan</label>
                                            <input type="checkbox" id="basic_checkbox_A" value="3" name="status[]"/>
                                                <label for="basic_checkbox_A">Tidak hadir</label>
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
        </div>
        <br>
        <!-- #END# filter -->
        
         <!-- Infobox -->
         <div class="row clearfix">
            <div class="row">  
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="card bg-indigo">
                                <div class="header">
                                    <div class="waktu">
                                        <h1 align="center" style="color: white" ><?php 
                                            date_default_timezone_set("Asia/Jakarta");
                                             echo date("h:i:s") ?></h1>
                                            <h4 align="center" style="color: white"><?php echo date("d F Y") ?></h4>
                                    </div>
                                </div>  
                            </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-8 col-xs-12">
                            <div class="info-box bg-green hover-zoom-effect">
                                <div class="icon">
                                    <i class="material-icons" style="color: white">alarm_on</i>
                                </div>
                                <div class="content">
                                    <div class="text">TEPAT WAKTU</div>
                                    <div class="number" id="jumlah_hadir"></div>
                                </div>
                            </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-8 col-xs-12">
                            <div class="info-box bg-deep-orange hover-zoom-effect">
                                <div class="icon">
                                    <i class="material-icons" style="color: white">alarm_add</i>
                                </div>
                                <div class="content">
                                    <div class="text">terlambat</div>
                                    <div class="number" id="terlambat"></div>
                                </div>
                            </div>
                    </div>
                        <div class="col-lg-3 col-md-4 col-sm-8 col-xs-12">
                            <div class="info-box bg-pink hover-zoom-effect">
                                <div class="icon">
                                    <i class="material-icons" style="color: white">alarm_off</i>
                                </div>
                                <div class="content">
                                    <div class="text">TIDAK HADIR</div>
                                    <div class="number" id="tidak_hadir"></div>
                                </div>
                            </div>
                    </div>
                        <div class="col-lg-3 col-md-4 col-sm-8 col-xs-12">
                            <div class="info-box bg-blue hover-zoom-effect">
                                <div class="icon">
                                    <i class="material-icons" style="color: white">face</i>
                                </div>
                                <div class="content">
                                    <div class="text">Jumlah Pegawai</div>
                                    <div class="number" data-from="0" data-to="<?php echo $jml_pegawai;?>" data-speed="10" data-fresh-interval="20"> <?php echo $jml_pegawai;?></div>
                                </div>
                            </div>
                    </div>                    
            </div>  
         </div>
        
        <!-- #END# Infobox -->
        <!-- chart and list absen -->
        <div class="row clearfix">
            <div class="row">
                <div class="col-md-8">                            
                    <!-- START PROJECTS BLOCK -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="panel-title-box">
                                <h3 class="panel-title">Statistik Jam Masuk</h3>
                            </div>
                        </div>
                        <div class="panel-body panel-body-table">
                            <div id="container2"></div>                                    
                        </div>
                    </div>
                    <!-- END PROJECTS BLOCK -->
                </div>
                <div class="col-md-4" >                            
                     <div class="card" style="max-height: 470px" >
                        <div class="menu">
                            <div class="list" style="max-height: 470px">
                                <p id="pegawai"></p>
                                
                            </div>
                        </div>
                       </div>  
                 </div>
            </div>
        </div>

        <div class="row clearfix">
            <div class="row">
                <div class="col-md-5">                            
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="panel-title-box">
                                <h3 class="panel-title">Statistik Kehadiran</h3>
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
                                <input type="hidden" id="target" class="form-control"/>
                            </div>                                
                        </div>
                        <div class="panel-body panel-body-map">
                            <div id="new_google_search_map" style="width: 100%; height: 398px;"></div>
                        </div>
                    </div>
                </div>
            </div> 
        </div>
        <div class="row clearfix">
            <div class="row">
                <div class="col-md-12">                            
                    <!-- START PROJECTS BLOCK -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="panel-title-box">
                                <h3 class="panel-title">Statistik Absensi Harian</h3>
                            </div>
                        </div>
                        <div class="panel-body panel-body-table">
                            <div id="statistik"></div>                                    
                        </div>
                    </div>
                    <!-- END PROJECTS BLOCK -->
                </div>
            </div>
        </div>
        
        <div class="row clearfix">
            <div class="row">
                <div class="col-md-7">                            
                    <!-- START PROJECTS BLOCK -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="panel-title-box">
                                <h3 class="panel-title">Absensi Berdasarkan SKPD</h3>
                            </div>
                        </div>
                        <div class="panel-body panel-body-table">
                            <div id="statistikSKPD"></div>                                    
                        </div>
                    </div>
                    <!-- END PROJECTS BLOCK -->
                </div>
                <div class="col-md-5" style="height: 480px;">
                    <!-- START PROJECTS BLOCK -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="panel-title-box">
                                <h3 class="panel-title">Gender</h3>
                            </div>
                        </div>
                        <div class="panel-body panel-body-table">
                            <div id="container3"></div>                                    
                        </div>
                    </div>
                     <!-- END PROJECTS BLOCK -->
                </div>
            </div>
        </div>
                
        
        </div>
</section>

<script type="text/javascript">
    var dari = "<?php echo $this->input->get('dari'); ?>";
    var sampai = "<?php echo $this->input->get('sampai'); ?>";

    function cb(start, end) {
        if (start._d == "Invalid Date" && end._d == "Invalid Date") {
            $('#dari').val('');
            $('#sampai').val('');
        } else {
            dari = start.format('YYYY-MM-DD');
            sampai = end.format('YYYY-MM-DD');

            $('#dari').val(dari);
            $('#sampai').val(sampai);
        }
    }
    $(document).ready(function(){
        if (dari != "" && sampai != "") {
            var start = moment(dari);
            var end = moment(sampai);            

            $('#reportrange span').html(start.format('D-M-YYYY') + ' sampai ' + end.format('D-M-YYYY'));
        } else {
            var today = moment().format('MM/DD/YYYY');
            var newtoday = moment().subtract(6, 'days');
            newtoday = newtoday.format('MM/DD/YYYY');

            var start = today;
            var end = newtoday;
        }

        $('#reportrange').daterangepicker({
            ranges: {
                'Hari Ini': [moment(), moment()],
                'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Data 7 Hari': [moment().subtract(6, 'days'), moment()],
                'Data 30 Hari': [moment().subtract(29, 'days'), moment()],
                'Data Bulan Ini': [moment().startOf('month'), moment().endOf('month')],
                'Data Bulan Lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            "locale": {
                "direction": "ltr",
                "format": "MM/DD/YYYY",
                "separator": " - ",
                "applyLabel": "Apply",
                "cancelLabel": "Cancel",
                "fromLabel": "From",
                "toLabel": "To",
                "customRangeLabel": "Custom",
                "daysOfWeek": [
                    "Su",
                    "Mo",
                    "Tu",
                    "We",
                    "Th",
                    "Fr",
                    "Sa"
                ],
                "monthNames": [
                    "Januari",
                    "Februari",
                    "Maret",
                    "April",
                    "Mei",
                    "Juni",
                    "Juli",
                    "Agustus",
                    "September",
                    "Oktober",
                    "November",
                    "Desember"
                ],
                "firstDay": 1
            },
            "startDate": start,
            "endDate": end
        }, cb);
        
    });
</script>
<?php echo $line_chart;?>
<script>
    var data = <?php echo $map;?>;
</script>
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

            var newDari = document.getElementById("dari").value;
            var newSampai = document.getElementById("sampai").value;

            url += 'SO/dashboard'+'?skpd='+newSkpd+'&jabatan='+newjabatan+'&jenkel='+newjenkel+'&status='+newstatus+'&dari='+newDari+'&sampai='+newSampai;
             window.location = url;
        }));

        var urlSelect = "<?php echo base_url('SO/dashboard/daftarHadir');?>";
        <?php if ($show == 'true') { ?>
            urlSelect += '?skpd=<?php echo $this->input->get("skpd");?>&jabatan=<?php echo $this->input->get("jabatan");?>&jenkel=<?php echo $this->input->get("jenkel");?>&status=<?php echo $this->input->get("status");?>';
        <?php } ?>

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
                var dataJumlahPegawai = result.jml_pegawai;

                $('#jumlah_hadir').html(dataJumlahHadir);
                $('#tidak_hadir').html(dataTidakHadir);
                $('#terlambat').html(dataTerlambat);
                $('#pegawai').html(dataPegawai);
                $('#jml_pegawai').html(dataJumlahPegawai);

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
                    var dataJumlahPegawai = result.jml_pegawai;

                    $('#jumlah_hadir').html(dataJumlahHadir);
                    $('#tidak_hadir').html(dataTidakHadir);
                    $('#terlambat').html(dataTerlambat);
                    $('#pegawai').html(dataPegawai);
                    $('#jml_pegawai').html(dataJumlahPegawai);


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
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB-rB6cp32C9kALtCmg_EFiFpLgKwNjEZs&libraries=places&sensor=false"></script>
<script src="<?php echo base_url(); ?>assets/admin/js/pages/widgets/infobox/infobox-4.js"></script>
<script src="<?php echo base_url();?>/assets/js/plugins/highcharts/code/highcharts.js"></script>
<script src="https://code.highcharts.com/highcharts-3d.js"></script>
<script src="<?php echo base_url();?>/assets/js/plugins/highcharts/code/modules/exporting.js"></script> 
<style>
    .highcharts-credits{
        display: none;
    }
</style>
<script>
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
<script>
$(document).ready(function(){
    var cords, marker;
    var map = new google.maps.Map(document.getElementById('new_google_search_map'), {
        zoom: 12,
        center: {
            lat: -6.295157, 
            lng: 106.707010
        },
        mapTypeId: google.maps.MapTypeId.TERRAIN,
        styles:[{
            "featureType": "landscape",
            "stylers": [
            {
            "hue": "#FFBB00"
            },
            {
            "saturation": 43.400000000000006
            },
            {
            "lightness": 37.599999999999994
            },
            {
            "gamma": 1
            }
        ]},{
            "featureType": "road.highway",
            "stylers": [{
                "hue": "#FFC200"
            },{
                "saturation": -61.8
            },{
                "lightness": 45.599999999999994
            },{
                "gamma": 1
            }]
        },{
            "featureType": "road.arterial",
            "stylers": [{
                "hue": "#FF0300"
            },{
                "saturation": -100
            },{
                "lightness": 51.19999999999999
            },{
                "gamma": 1
            }]
        },{
            "featureType": "road.local",
            "stylers": [{
                "hue": "#FF0300"
            },{
                "saturation": -100
            },{
                "lightness": 52
            },{
                "gamma": 1
            }]
        },{
            "featureType": "water",
            "stylers": [{
                "hue": "#0078FF"
            },{
                "saturation": -13.200000000000003
            },{
                "lightness": 2.4000000000000057
            },{
                "gamma": 1
            }]
        },{
            "featureType": "poi",
            "stylers": [{
                "hue": "#00FF6A"
            },{
                "saturation": -1.0989010989011234
            },{
                "lightness": 11.200000000000017
            },{
                "gamma": 1
            }]
        }]
    });

    var input = (document.getElementById('target'));

    var searchBox = new google.maps.places.SearchBox(input);
    var markers = [];

    google.maps.event.addListener(searchBox, 'places_changed', function() {
        var places = searchBox.getPlaces();

        for (var i = 0, marker; marker = markers[i]; i++) {
            marker.setMap(null);
        }

        markers = [];
        var bounds = new google.maps.LatLngBounds();
        for (var i = 0, place; place = places[i]; i++) {
            var image = {
                url: place.icon,
                size: new google.maps.Size(71, 71),
                origin: new google.maps.Point(0, 0),
                anchor: new google.maps.Point(17, 34),
                scaledSize: new google.maps.Size(25, 25)
            };

            var marker = new google.maps.Marker({
                map: map,
                icon: image,
                title: place.name,
                position: place.geometry.location
            });

            markers.push(marker);

            bounds.extend(place.geometry.location);
        }

        map.fitBounds(bounds);
    });

    google.maps.event.addListener(map, 'bounds_changed', function() {
        var bounds = map.getBounds();
        searchBox.setBounds(bounds);
    });

    for (var i = 0; i < data.length; i++) {
        var dataPhoto = data[i];
        cords = new google.maps.LatLng(dataPhoto.lat, dataPhoto.lng);
        marker = new google.maps.Marker({
            position: cords, 
            map: map, 
            title: dataPhoto.nama_user+' ('+dataPhoto.created_history_absensi+')'
        });    
    }
});
</script>