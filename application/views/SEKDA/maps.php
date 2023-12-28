<section class="content">

        <div class="container-fluid">

            <div class="row clearfix">

    <div class="row">

        <div class="col-md-12">

                    <div class="panel panel-default">

                        <div class="panel-heading">

                            <h3 class="panel-title">Data Posisi Absen</h3>

                            <div class="float-right" style="width: 200px;">

                                <input type="hidden" id="target" class="form-control"/>

                            </div>                                

                        </div>

                        <div class="panel-body panel-body-map">

                            <div id="new_google_search_map" style="width: 100%; height: 458px;"></div>

                        </div>

                    </div>

                </div>

    </div>

</div>

        </div>

    </section>



<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB-rB6cp32C9kALtCmg_EFiFpLgKwNjEZs&libraries=places"></script>

<script>

    var data = <?php echo $map;?>;

</script>

    <script>

$(document).ready(function(){

    var cords, marker;

    var map = new google.maps.Map(document.getElementById('new_google_search_map'), {

        zoom: 12,

        center: {

            lat: -6.893817, 

            lng: 107.609212

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

            title: dataPhoto.waktu

        });    

    }
    var flightPlanCoordinates = <?php echo $line;?>;
        var flightPath = new google.maps.Polyline({
          path: flightPlanCoordinates,
          geodesic: true,
          strokeColor: '#FF0000',
          strokeOpacity: 1.0,
          strokeWeight: 2
        });

        flightPath.setMap(map);

});

</script>