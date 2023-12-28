<link href="<?php echo base_url();?>assets/admin/plugins/ion-rangeslider/css/ion.rangeSlider.css" rel="stylesheet" />
<link href="<?php echo base_url();?>assets/admin/plugins/ion-rangeslider/css/ion.rangeSlider.skinFlat.css" rel="stylesheet" />

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
</style>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-md-12">
          <h1>Data Area <i style="color: red; font-size: 10px;">*Klik pada maps untuk menandai lokasi</i></h1>
        </div>
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>

  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <form enctype="multipart/form-data" id="add-form" method="POST" action="javascript:void(0);">
                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <div class="form-line">
                        <input type="text" name="nama_lokasi" id="nama_lokasi" class="form-control" placeholder="Nama Area" required="">
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-6">
                  <button type="submit" class="btn btn-primary"><span class="fa fa-save"></span> Simpan</button>
                  <a href="javascript:void(0);" class="btn btn-danger" onclick="setNull()"><span class="fa fa-undo-alt"></span> Reset</a>
                  </div>
                </div>
              </form>
            </div>
            <input id="pac-input" class="form-control" type="text" placeholder="Cari">
            <div class="card-body" id="map"></div>
            <input type="text" id="range_01" value=""> <p style="color: red; font-size: 10px;">*Jarak yang tertera dalam satuan meter</p>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyADOfVTL5FPWtGo207dJZrSZLdafERarwM&libraries=drawing,places&callback=initMap" async defer></script>
<script src="<?php echo base_url();?>assets/admin/plugins/ion-rangeslider/js/ion.rangeSlider.js"></script>

<script>
  $(document).ready(function() {
    var x = document.getElementById("range_01");
    x.style.display = "none";

    $('#add-form').on('submit',(function(e) {
      e.preventDefault();
      var formData = new FormData(this);

      if (coordinate == "") {
        swal("Warning!", "Kordinat wajib di isi.", "warning");
      }else{
          var nama_lokasi = document.getElementById("nama_lokasi").value; 
          $.ajax({
            method  : 'POST',
            url     : urlInsert,
            async   : true,
            data    : {
              nama_lokasi: nama_lokasi,
              kordinat: coordinate,
              radius: radius
            },
            success: function(data, status, xhr) {
                try {
                    var result = JSON.parse(xhr.responseText);
                    if (result.status) {
                        swal(result.message, {
                            icon: "success",
                        }).then((acc) => {
                            window.location='<?php echo base_url();?>Administrator/data_jaringan/area';
                        });
                    } else {
                        swal("Warning!", "Terjadi kesalahan sistem", "warning");
                    }
                } catch (e) {
                  swal("Warning!", "Terjadi kesalahan sistem", "warning");
                }
            },
            error: function(data) {
                swal("Warning!", "Terjadi kesalahan sistem", "warning");
            }
          });
      }
    }));
  });
  
  var urlInsert = "<?php echo base_url('Administrator/data_jaringan/insertNew'); ?>";
  var map, drawingManager, marker, cityCircle;
  var all_overlays = [];
  var kordinat = [];
  var coordinate = "";
  var radius = 1;

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

    cityCircle = new google.maps.Circle({});

    autocomplete.addListener('place_changed', function() {
      marker.setVisible(false);
      var place = autocomplete.getPlace();
      if (!place.geometry) {
        window.alert("No details available for input: '" + place.name + "'");
        return;
      }
      placeMarker(place.geometry.location);

      var address = '';
      if (place.address_components) {
        address = [
          (place.address_components[0] && place.address_components[0].short_name || ''),
          (place.address_components[1] && place.address_components[1].short_name || ''),
          (place.address_components[2] && place.address_components[2].short_name || '')
        ].join(' ');
      }
    });

    google.maps.event.addListener(map, 'click', function(event) {
      marker.setVisible(false);
      cityCircle.setMap(null);
      placeMarker(event.latLng);
    });
  }

  function placeMarker(location) {
    map.setZoom(15);
    map.panTo(location);
    marker = new google.maps.Marker({
      position: location, 
      map: map
    });

    setCircle(location);
    coordinate = {lat: location.lat(), lng: location.lng()};

    $("#range_01").ionRangeSlider({
      min: 1,
      max: 1000,
      from: 1
    });
    $("#range_01").on('change', (function(e) {
      cityCircle.setMap(null);
      radius = $("#range_01").val();
      
      setCircle(location);
    }));
  }

  function setCircle(location) {
    cityCircle.setMap(null);
    // Add the circle for this city to the map.
    cityCircle = new google.maps.Circle({
      strokeColor: '#FF0000',
      strokeOpacity: 0.8,
      strokeWeight: 2,
      fillColor: '#FF0000',
      fillOpacity: 0.35,
      map: map,
      center: location,
      radius: Number(radius)
    });
  }

  function setNull() {
    for (var i=0; i < all_overlays.length; i++){
      all_overlays[i].setMap(null);
    }

    all_overlays = [];
    marker.setVisible(false);
    cityCircle.setMap(null);
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
</script>