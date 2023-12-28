<!-- Range Slider Css -->
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
                    <h1 class="m-0 text-dark">Tambah Akses Absensi</h1>
                </div>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <form class="form-horizontal" enctype="multipart/form-data" id="add-form" action="javascript:void(0);" method="post">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-12">
                                            <div class="col-md-12">
                                                <div class="form-group form-float">
                                                    <label class="form-label"><i class="text-danger">*</i> Pegawai</label>
                                                    <div class="form-line">
                                                        <select class="form-control" id="user_id" name="user_id" data-live-search="true" disabled>
                                                            <option></option>
                                                            <?php foreach ($list_pegawai as $key => $value) { ?>
                                                                <option 
                                                                <?php if ($value->user_id == $akses_absensi->user_id) { echo 'selected'; }?> value="<?php echo $value->user_id;?>"><?php echo $value->nama_user;?> (<?php echo $value->nip;?>)</option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group form-float">
                                                    <label class="form-label"><i class="text-danger">*</i> Tanggal Berlaku</label>
                                                    <div class="form-line">
                                                        <input type="hidden" name="id_akses_absensi" id="id_akses_absensi" value="<?php echo $akses_absensi->id_akses_absensi;?>" class="datepicker3 form-control" required>
                                                        <input type="date" name="tanggal_akhir" id="tanggal_akhir" value="<?php echo $akses_absensi->tanggal_akhir;?>" class="form-control" required>
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="form-group form-float">
                                                    <label class="form-label"><i class="text-danger">*</i> Lokasi Akses Absensi</label>
                                                    <input id="pac-input" class="form-control" type="text" placeholder="Cari">
                                                    <div class="body" id="map"></div>
                                                    <input type="text" id="range_01" value="" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <div class="float-right">
                                  <button class="btn btn-secondary" type="button" onclick="window.location='<?php echo base_url();?>Leader/akses_absensi'"><span class="fa fa-ban"></span> Batal</button>
                                  <a href="javascript:void(0);" class="btn btn-warning text-white" onclick="setNull()"><span>Reset</span></a>
                                  <button class="btn btn-primary" type="submit"><span class="fa fa-save"></span> Simpan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyADOfVTL5FPWtGo207dJZrSZLdafERarwM&libraries=drawing,places&callback=initMap" async defer></script>
<!-- RangeSlider Plugin Js -->
<script src="<?php echo base_url();?>assets/admin/plugins/ion-rangeslider/js/ion.rangeSlider.js"></script>

<script>
    var map, drawingManager, marker, cityCircle;
    var all_overlays = [];
    var kordinat = [];
    var radius = "<?php echo $data->radius;?>";
    var corLat = "<?php echo $data->lat;?>";
    var corLng = "<?php echo $data->lng;?>";
    var coordinate = {lat: Number(corLat), lng: Number(corLng)};
    var lokasi = {lat: Number(corLat), lng: Number(corLng)};

    function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
            center: {lat: Number(corLat), lng: Number(corLng)},
            zoomControl: true,
            mapTypeControl: false,
            streetViewControl: false,
            fullscreenControl: false,
            zoom: 15
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

        cityCircle = new google.maps.Circle({
        });

        autocomplete.addListener('place_changed', function() {
            marker.setVisible(false);
            var place = autocomplete.getPlace();
            if (!place.geometry) {
                // User entered the name of a Place that was not suggested and
                // pressed the Enter key, or the Place Details request failed.
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
        placeMarker(map.getCenter());

        // drawPoly();
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
            from: radius
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

        // drawPoly();
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

    var urlInsert = "<?php echo base_url('Leader/akses_absensi/update'); ?>";
    $(document).ready(function() {
        var x = document.getElementById("range_01");
        x.style.display = "none";

        $('#example').DataTable();
        $('#add-form').on('submit',(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            if (coordinate == "" && lokasi == "") {
                swal("Warning!", "Kordinat wajib di isi.", "warning");
            }else{
                if (coordinate == "") {
                    coordinate = lokasi;
                }
                if (lokasi == "") {
                    coordinate = coordinate;
                }
                var id_akses_absensi = document.getElementById("id_akses_absensi").value; 
                var user_id = document.getElementById("user_id").value; 
                var tanggal_akhir = document.getElementById("tanggal_akhir").value; 
                $.ajax({
                    method  : 'POST',
                    url     : urlInsert,
                    async   : true,                    
                    data    : {
                        id_akses_absensi: id_akses_absensi,
                        user_id: user_id,
                        tanggal_akhir: tanggal_akhir,
                        kordinat: coordinate,
                        radius: radius
                    },
                    success: function(data, status, xhr) {
                        try {
                            var result = JSON.parse(xhr.responseText);
                            if (result.status == true) {
                                swal(result.message, {
                                    icon: "success",
                                    title: "Success",
                                    text: "Data saved successfully",
                                }).then((acc) => {
                                    window.location='<?php echo base_url();?>Leader/akses_absensi';
                                });
                            } else {
                              swal("Warning!", result.message, "warning");
                            }
                        } catch (e) {
                          swal("Warning!", "Sistem error.", "warning");
                        }
                    },
                    error: function(data) {
                      swal("Warning!", "Sistem error.", "warning");
                    }
                });
            }
        }));
    });
</script>