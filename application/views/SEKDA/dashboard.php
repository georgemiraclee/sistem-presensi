<style>
  .highcharts-credits{
    display: none;
  }
</style>

<?php
  if ($show == true){
    $keterangan = " Hari Ini";
  }else{
    $keterangan = "";
  }
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <h1 class="m-0 text-dark">
        Dashboard
        <button class="btn btn-primary btn-sm float-right" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
          <span class="fa fa-filter"></span><span class="icon-name">FILTER</span> 
        </button>
      </h1>

      <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
          <div class="collapse" id="collapseExample">
            <div class="well" id="accOneColThree">
              <form method="post" id="formFilter" action="javascript:void(0);">
                <div class="row">
                  <div class="col-md-12">
                    <div class='form-group form-group-sm'>
                      <a href="#" type="button" id="reportrange" class="btn btn-info">
                        <span class="fa fa-calendar-day"></span>
                      </a>
                    </div>
                  </div>
                </div>
                  <div class="row">
                      <input type="hidden" name="dari" id="dari">
                      <input type="hidden" name="sampai" id="sampai">
                      <div class="col-md-3">
                        <h6>JABATAN</h6>
                        <div style="max-height: 200px; overflow: auto;">
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
                        <div style="max-height: 200px; overflow: auto;">
                          <div class="demo-checkbox">
                            <input type="checkbox" id="basic_checkbox_L" value="l" name="jenkel[]"/>
                            <label for="basic_checkbox_L">Laki - Laki</label>
                          </div>
                          <div class="demo-checkbox">
                            <input type="checkbox" id="basic_checkbox_P" value="p" name="jenkel[]"/>
                            <label for="basic_checkbox_P">Perempuan</label>
                          </div>
                        </div>
                      </div>
                        <div class="col-md-3">
                          <h6>STATUS</h6>
                          <div style="max-height: 200px; overflow: auto;">
                            <div class="demo-checkbox">
                              <input type="checkbox" id="basic_checkbox_T" name="status[]"  value="Tepat Waktu"/>
                              <label for="basic_checkbox_T">Tepat Waktu</label>
                            </div>
                            <div class="demo-checkbox">
                              <input type="checkbox" id="basic_checkbox_K" name="status[]"  value="Terlambat"/>
                              <label for="basic_checkbox_K">Terlambat</label>
                            </div>
                            <div class="demo-checkbox">
                              <input type="checkbox" id="basic_checkbox_A" name="status[]"  value="Tidak hadir"/>
                              <label for="basic_checkbox_A">Tidak hadir</label>
                            </div>
                          </div>
                      </div>
                  </div>
                  <div class="row mb-3">
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

      <div class="row mt-3">
        <div class="col-lg-3 col-sm-6 col-md-3" id="tepat_waktu" data-toggle="modal" data-target="#myModal" style="cursor: pointer;">
          <div class="info-box">
            <span class="info-box-icon bg-success elevation-1"><i class="material-icons" style="font-size: 50px;">alarm_on</i></span>
            <div class="info-box-content">
              <span class="info-box-text">TEPAT WAKTU</span>
              <div class="number" id="jumlah_hadir"></div>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-lg-3 col-sm-6 col-md-3" data-toggle="modal" data-target="#myModal" style="cursor: pointer;" id="terlambats">
            <div class="info-box mb-3">
            <span class="info-box-icon bg-warning elevation-1"><i class="material-icons text-white" style="font-size: 50px;">alarm_add</i></span>

            <div class="info-box-content">
                <span class="info-box-text">TERLAMBAT</span>
                <div class="number" id="terlambat"></div>
            </div>
            <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <!-- fix for small devices only -->
        <div class="clearfix hidden-md-up"></div>

        <div class="col-lg-3 col-sm-6 col-md-3" data-toggle="modal" data-target="#myModal" style="cursor: pointer;" id="tidak_hadirs">
            <div class="info-box mb-3">
            <span class="info-box-icon bg-danger elevation-1"><i class="material-icons" style="font-size: 50px;">alarm_off</i></span>

            <div class="info-box-content">
                <span class="info-box-text">TIDAK HADIR</span>
                <div class="number" id="tidak_hadir"></div>
            </div>
            <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <a href="<?php echo base_url();?>Leader/pegawai" class="col-lg-3 col-sm-6 col-md-3" style="text-decoration: none; color: black;">
            <div class="info-box mb-3">
            <span class="info-box-icon bg-info elevation-1"><i class="material-icons" style="font-size: 50px;">check</i></span>

            <div class="info-box-content">
                <span class="info-box-text">JUMLAH PEGAWAI</span>
                <span class="info-box-number"><?php echo $jml_pegawai;?></span>
            </div>
            <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </a>
        <!-- /.col -->
      </div>

      <div class="row clearfix">
        <div class="col-md-5">   
          <div class="card">
            <div class="card-header">
              Statistik Kehadiran
            </div>
            <div class="card-body">
              <div id="container"></div>
            </div>
          </div>                         
        </div>
        <div class="col-md-7">
          <div class="card">
            <div class="card-header">
              Data Posisi Absen
            </div>
            <div class="card-body" id="new_google_search_map" style="height: 398px;">
              <!-- <div id="new_google_search_map" style="width: 100%; height: 398px;"></div> -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- START MODAL -->
<div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="modal-body"></div>
            </div>
        </div>
    </div>
</div>
<!-- END MODAL -->

<script type="text/javascript">
  var path = '<?php echo base_url(); ?>';
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
<!-- end js calendar filter -->
<?php echo $line_chart;?>
<!-- data untuk maps -->
<script>
  var data = <?php echo $map;?>;
</script>
<!-- end data untuk maps -->
<script type="text/javascript">
  $(document).ready(function() {
    $('#formFilter').on('submit',(function(e) {
        var url = "<?php echo base_url(); ?>";
        
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
        url += 'Leader/dashboard'+'?jabatan='+newjabatan+'&jenkel='+newjenkel+'&status='+newstatus+'&dari='+newDari+'&sampai='+newSampai;
          window.location = url;
    }));

    var urlSelect = "<?php echo base_url('Leader/dashboard/daftarHadir');?>";
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

    $('#tepat_waktu').click(function(){
      var kepala = "Tepat Waktu";
      var url = "Leader/Dashboard/listAbsensi";
      var status = "Tepat Waktu";
      loadModal(kepala, url, status);
    });

    $('#terlambats').click(function(){
      var kepala = "Terlambat";
      var url = "Leader/Dashboard/listAbsensi";
      var status = "Terlambat";
      loadModal(kepala, url, status);
    });

    $('#tidak_hadirs').click(function(){
      var kepala = "Tidak Hadir";
      var url = "Leader/Dashboard/listAbsensi";
      var status = "Tidak Hadir";
      loadModal(kepala, url, status);
    });
    
  });

  function loadModal(kepala, url, status) {
      var penandaStatus = status;
      $('#modal-title').html(kepala);
      $('#modal-body').html('');

      $.ajax({
          method  : 'POST',
          url     : path+url,
          async   : true,
          data    : {
              status: status
          },
          success: function(data, status, xhr){
              if (data) {
                  var recs = JSON.parse(xhr.responseText);
                  var dataList = "";
                  for (var i = 0; i < recs.length; i++) {
                      if (recs[i].id_absensi) {
                          if (Number.isInteger(penandaStatus) || penandaStatus == "sakit" || penandaStatus == "cuti" || penandaStatus == "izin") {
                              if (penandaStatus == 901) {
                                  var detail = '<td><a href="<?php echo base_url();?>Leader/lembur" class="btn btn-primary btn-sm"><span class="fa fa-search"></span> Detail</a></td>';
                              }else{
                                  var detail = '<td><a href="<?php echo base_url();?>Leader/cuti/detail_cuti/'+recs[i].id_absensi+'" class="btn btn-primary btn-sm"><span class="fa fa-search"></span> Detail</a></td>';
                              }
                          } else {
                              var detail = '<td><a href="<?php echo base_url();?>Leader/data_absensi/detail/'+recs[i].id_absensi+'" class="btn btn-primary btn-sm"><span class="fa fa-search"></span> Detail</a></td>';
                          }
                      }else{
                          var detail = '<td><span class="badge bg-red">Tidak Hadir</span></td>';
                      }
                      dataList += '<tr>'+
                          '<td>'+(i+1)+'</td>'+
                          '<td>'+recs[i].nip+'</td>'+
                          '<td>'+recs[i].nama_user+'</td>'+
                          detail+
                      '</tr>';
                  }
                  $('#modal-body').append('<div class="table-responsive">'+
                      '<table class="table" id="table2">'+
                          '<thead>'+
                              '<tr>'+
                              '<th>#</th>'+
                              '<th>NIP</th>'+
                              '<th>Nama Pegawai</th>'+
                              '<th>Detail</th>'+
                              '</tr>'+
                          '</thead>'+
                          '<tbody>'+
                          dataList+
                      '</table>'+
                  '</div><script type="text/javascript">$("#table2").DataTable({});<\/script>');
              }
          }
      });
  }

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
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyADOfVTL5FPWtGo207dJZrSZLdafERarwM&libraries=places&sensor=false"></script>
<script src="<?php echo base_url();?>assets/admin/js/pages/widgets/infobox/infobox-4.js"></script>
<script src="<?php echo base_url();?>assets/admin/js/highchart.js"></script>
<script src="<?php echo base_url();?>assets/admin/js/highchart_3d.js"></script>
<script src="<?php echo base_url();?>assets/js/plugins/highcharts/code/modules/exporting.js"></script> 

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
        zoom: 3,
        center: {
            lat: 0.127970,
            lng: 120.841306
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