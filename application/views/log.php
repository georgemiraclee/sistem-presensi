<!DOCTYPE html>
<html lang="en" class="body-full-height">
    <head>        
        <!-- META SECTION -->
        <title>Log Absensi Pegawai</title>            
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        
        <!-- END META SECTION -->
        
        <!-- CSS INCLUDE -->        
        <link rel="stylesheet" type="text/css" id="theme" href="<?php echo base_url();?>/assets/css/theme-default.css"/>
        <!-- EOF CSS INCLUDE -->                                     
    </head>
    <body>
        
        <div class="login-container" style="background-color: #F5F4F4">
               <br>
            <div class="container">
               <h1 align="center" style="color: Black"> <b>LOG ABSENSI PEGAWAI</b></h1>
            </div>
             
            <div class="col-md-3">
                <div class="panel panel-default">
                                <div class="panel-body profile" style="background: url('https://s-media-cache-ak0.pinimg.com/originals/4f/6d/05/4f6d052bb1b26150115888ea06d4c106.jpg') center center no-repeat;">
                                    <div class="profile-image">
                                        <img src="<?php echo base_url();?>assets/images/member-photos/<?php echo $foto; ?>" alt="Nadia Ali"/>
                                    </div>
                                    <div class="profile-data">
                                        <div class="profile-data-name"><b><?php echo $nama; ?></b></div>
                                        <div class="profile-data-title" style="color: #FFF;"><?php echo $jabatan; ?></div>
                                    </div>                                    
                                </div>                                
                                <div class="panel-body list-group border-bottom">
                                    <a href="#" class="list-group-item active"><span class="fa fa-bar-chart-o"></span> Informasi</a>
                                    <a href="#" data-toggle="modal" data-target="#modal_no_head" class="list-group-item"><span class="fa fa-list-alt"></span> NIP : <?php echo $nip; ?></a>                          
                                    <a href="#" class="list-group-item"><span class="fa fa-users"></span> Unit : <?php echo $unit; ?></a>
                                    <a href="#" class="list-group-item"><span class="fa fa-bookmark"></span> Jabatan : <?php echo $jabatan; ?></a>
                                    <a href="#" class="list-group-item"><span class="fa fa-envelope"></span> Email : <?php echo $email; ?></a>
                                    <a href="#" class="list-group-item"><span class="fa fa-phone"></span> Telepon : <?php echo $telepon; ?></a>
                                    <a href="#" class="list-group-item"><span class="fa fa-home"></span> Alamat : <?php echo $alamat; ?></a>
                                    <a href="#" class="list-group-item"><span class="fa fa-male"></span> Gender : <?php echo $jenis_kelamin; ?></a>
                                </div>
                                
                            </div>          
            </div>
            
            <div class="col-md-9 float-left">
                <!-- START JUSTIFIED TABS -->
                            <div class="panel panel-default tabs">
                                <ul class="nav nav-tabs nav-justified">
                                    <li class="active"><a href="#tab8" data-toggle="tab">Lokasi</a></li>
                                    <li><a href="#tab9" data-toggle="tab">Statistik</a></li>
                                    <li><a href="#tab10" data-toggle="tab">Data Absensi</a></li>
                                </ul>
                                <div class="panel-body tab-content">
                                    <div class="tab-pane " id="tab10">
                                         <table class="table datatable">
                                            <thead>
                                                <tr>
                                                    <th>Tanggal</th>
                                                    <th>Waktu Datang</th>
                                                    <th>Waktu istirahat</th>
                                                    <th>Waktu Kembali</th>
                                                    <th>Waktu Pulang</th>
                                                    <th>Keterangan</th>     
                                                </tr>
                                            </thead>
                                                 
                                            <tbody>
                                                <?php foreach ($dt as $key => $value) { 
                                                    if ($value->waktu_datang == null) {
                                                        $value->waktu_datang = '-';
                                                    }
                                                    if ($value->waktu_istirahat == null) {
                                                        $value->waktu_istirahat = '-';
                                                    }
                                                    if ($value->waktu_kembali == null) {
                                                        $value->waktu_kembali = '-';
                                                    }
                                                    if ($value->waktu_pulang == null) {
                                                        $value->waktu_pulang = '-';
                                                    }
                                                    if ($value->keterangan == null) {
                                                        $value->keterangan = '-';
                                                    }
                                                ?>
                                                <tr>
                                                    <th><?php echo $value->created_absensi ?></th>
                                                    <th><?php echo $value->waktu_datang ?></th>
                                                    <th><?php echo $value->waktu_istirahat ?></th>
                                                    <th><?php echo $value->waktu_kembali ?></th>
                                                    <th><?php echo $value->waktu_pulang ?></th>
                                                    <th><?php echo $value->keterangan ?></th>
                                                    
                                                </tr>
                                                 <?php } ?>
                                            </tbody>
                                        </table>              
                                    </div>
                                    <div class="tab-pane" id="tab9">
                                        <div class="col-md-6">
                                            <!-- START Area CHART -->
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title">Statistik Jam Kerja</h3>                                
                                                </div>
                                                <div class="panel-body">
                                                    <div id="container22" style="height: 390px"></div>

                                                </div>
                                            </div>
                                            <!-- END Area CHART -->                        

                                        </div>
                                         <div class="col-md-6">

                                            <!-- START DONUT CHART -->
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title">Statistik Kehadiran</h3>                                
                                                </div>
                                                <div class="panel-body">
                                                    <div id="container21" style="height: 390px"></div>
                                                </div>
                                            </div>
                                            <!-- END DONUT CHART -->                        

                                        </div>
                                    </div>
                                    <div class="tab-pane active" id="tab8">
                                        <div class="col-md-12">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title">Google Map With Markers</h3>
                                                </div>
                                                <div class="panel-body panel-body-map">
                                                    <div id="map" style="width: 100%; height:409px;"></div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>                        
                                </div>
                            </div>                                         
                            <!-- END JUSTIFIED TABS -->

            </div>
            
            
        </div>
         <!-- START PRELOADS -->
       
        <!-- END PRELOADS -->               
           
    <!-- START SCRIPTS -->
        <!-- START PLUGINS -->
        <script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/jquery/jquery.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/jquery/jquery-ui.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/bootstrap/bootstrap.min.js"></script>        
        <!-- END PLUGINS -->
        
        <!-- START THIS PAGE PLUGINS-->        
        <script type='text/javascript' src='<?php echo base_url();?>assets/js/plugins/icheck/icheck.min.js'></script>
        <script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/mcustomscrollbar/jquery.mCustomScrollbar.min.js"></script>
         <script type="text/javascript" src="<?php echo base_url();?>/assets/js/plugins/datatables/jquery.dataTables.min.js"></script>
         <script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins.js"></script>        
        <script type="text/javascript" src="<?php echo base_url();?>assets/js/actions.js"></script>

<script>

      // The following example creates complex markers to indicate beaches near
      // Sydney, NSW, Australia. Note that the anchor is set to (0,32) to correspond
      // to the base of the flagpole.

      function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 12,
          center: {lat: -6.828991, lng: 107.482873}
        });
        

        setMarkers(map);
      }

      // Data for the markers consisting of a name, a LatLng and a zIndex for the
      // order in which these markers should display on top of each other.
      var beaches = [
        <?php echo $lokasi;?>
      ];

      function setMarkers(map) {
        // Adds markers to the map.

        // Marker sizes are expressed as a Size of X,Y where the origin of the image
        // (0,0) is located in the top left of the image.

        // Origins, anchor positions and coordinates of the marker increase in the X
        // direction to the right and in the Y direction down.
        var image = {
          url: 'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png',
          // This marker is 20 pixels wide by 32 pixels high.
          size: new google.maps.Size(20, 32),
          // The origin for this image is (0, 0).
          origin: new google.maps.Point(0, 0),
          // The anchor for this image is the base of the flagpole at (0, 32).
          anchor: new google.maps.Point(0, 32)
        };
        // Shapes define the clickable region of the icon. The type defines an HTML
        // <area> element 'poly' which traces out a polygon as a series of X,Y points.
        // The final coordinate closes the poly by connecting to the first coordinate.
        var shape = {
          coords: [1, 1, 1, 20, 18, 20, 18, 1],
          type: 'poly'
        };
        for (var i = 0; i < beaches.length; i++) {
          var beach = beaches[i];
          var marker = new google.maps.Marker({
            position: {lat: beach[1], lng: beach[2]},
            map: map,
            icon: image,
            shape: shape,
            title: beach[0],
            zIndex: beach[3]
          });
        }
      }
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyADOfVTL5FPWtGo207dJZrSZLdafERarwM&callback=initMap">
    </script>
<!--
         
        <!-- END THIS PAGE PLUGINS--> 
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
        <style type="text/css">
#container22 {
    height: 400px; 
    min-width: 310px; 
    max-width: 800px;
    margin: 0 auto;
}
        </style>
        <style type="text/css">
${demo.css}
        </style>
        <script type="text/javascript">
$(function () {
    Highcharts.chart('container21', {
        chart: {
            type: 'pie',
            options3d: {
                enabled: true,
                alpha: 45
            }
        },
        title: {
            text: 'Data Kehadiran Pegawai'
        },
        subtitle: {
            text: 'Menapilkan statistik kehadiran pegawai selama satu bulan terakhir'
        },
        plotOptions: {
            pie: {
                innerSize: 100,
                depth: 45
            }
        },
        
        series: [{
            name: 'Jumlah hari',
            data: [
                ['Tepat Waktu', <?php echo $kehadiran;?>],
                ['Kesiangan', <?php echo $kesiangan;?>]
                
            ]
        }]
    });
});
        </script>
        <script type="text/javascript">
$(function () {
    Highcharts.chart('container22', {
        chart: {
            type: 'column',
            options3d: {
                enabled: true,
                alpha: 10,
                beta: 25,
                depth: 70
            }
        },
        title: {
            text: 'Data Jam kerja Pegawai'
        },
        subtitle: {
            text: 'Menampilkan grafik lama jam kerja pegawai selama satu minggu terakhir'
        },
        plotOptions: {
            column: {
                depth: 25
            }
        },
        xAxis: {
            categories: ['Senin', 'Selasa', 'Rabu', 'Kamis','Jumat']
        },
        yAxis: {
            title: {
                text: 'Jam Kerja'
            }
        },
        series: [{
            name: 'Jam Kerja',
            data: [8, 7.7 , 8,9,8]
        }]
    });
});
        </script>       
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script src="https://code.highcharts.com/highcharts-3d.js"></script>
        <script src="https://code.highcharts.com/modules/exporting.js"></script>
        <!-- START TEMPLATE -->
  
        
        <script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins.js"></script>        
        <script type="text/javascript" src="<?php echo base_url();?>assets/js/actions.js"></script>
       
        <!-- END TEMPLATE -->
       
    </body>
</html>






