var map, cords, marker;
var markers = [];
var infowindow = [];

function initMap() {
	map = new google.maps.Map(document.getElementById('map'), {
		zoom: 5,
    center: {lat: 0.372271, lng: 114.449444},
    mapTypeId: google.maps.MapTypeId.TERRAIN,
    streetViewControl: false,
    fullscreenControl: false,
    styles:[
      {
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
        ]
      },
      {
        "featureType": "road.highway",
        "stylers": [
          {
            "hue": "#FFC200"
          },
          {
            "saturation": -61.8
          },
          {
            "lightness": 45.599999999999994
          },
          {
            "gamma": 1
          }
        ]
      },
      {
        "featureType": "road.arterial",
        "stylers": [
          {
            "hue": "#FF0300"
          },
          {
            "saturation": -100
          },
          {
            "lightness": 51.19999999999999
          },
          {
            "gamma": 1
          }
        ]
      },
      {
        "featureType": "road.local",
        "stylers": [
          {
            "hue": "#FF0300"
          },
          {
            "saturation": -100
          },
          {
            "lightness": 52
          },
          {
            "gamma": 1
          }
        ]
      },
      {
        "featureType": "water",
        "stylers": [
          {
            "hue": "#0078FF"
          },
          {
            "saturation": -13.200000000000003
          },
          {
            "lightness": 2.4000000000000057
          },
          {
            "gamma": 1
          }
        ]
      },
      {
        "featureType": "poi",
        "stylers": [
          {
            "hue": "#00FF6A"
          },
          {
            "saturation": -1.0989010989011234
          },
          {
            "lightness": 11.200000000000017
          },
          {
            "gamma": 1
          }
        ]
      }
    ]
	});
}

function filterKategori(ketagori) {
	var cek = document.getElementById('kategori'+ketagori).checked;

	if (cek == true) {
    addMarkerNew(ketagori);
  }else{
    removeMarker(ketagori);
  }
}

function addMarker(item) {
	for (var i = 0; i < data.length; i++) {
		var dataPhoto = data[i];
		if (dataPhoto.jenis_kelamin == item) {
			var latLng = new google.maps.LatLng(dataPhoto.lat,dataPhoto.lng);

			var marker = new google.maps.Marker({
				map: map,
				position: latLng,
				kategori: item,
				lat: dataPhoto.lat,
				lon: dataPhoto.lng,
				id: dataPhoto.id
			});
		
			markers.push(marker);
      const waktuDatang = dataPhoto.waktu_datang ? moment(dataPhoto.waktu_datang).format('HH:mm') : '-';
      const waktuPulang = dataPhoto.waktu_pulang ? moment(dataPhoto.waktu_pulang).format('HH:mm') : '-';
		
			var contentString = "<div id='"+dataPhoto.id+"' style='width:150px;'>\
        <div style='margin:20px 0px 0px 20px;'>\
          <div style='margin-top:-10px; margin-bottom:10px;'>\
            <i class='glyphicon glyphicon-user'></i>"+dataPhoto.nama_user+"<br>\
            <p>"+moment(dataPhoto.waktu_datang).format('DD MMMM YYYY')+"</p>\
            <small class='text-muted'>Jam Masuk : "+waktuDatang+"</small><br>\
            <small class='text-muted'>Jam Keluar : "+waktuPulang+"</small><br>\
          </div>\
        </div>\
      </div>";
			var j = markers.length - 1;
		
			infowindow[j] = new google.maps.InfoWindow({
				content: contentString  
			});
		
			google.maps.event.addListener(markers[j],'click', (function(marker_window,data_window,info_window){
				return function() {
					info_window.open(map,marker_window);
				};
			})(markers[j],dataPhoto.id,infowindow[j]));	
		}
	}
}

function addMarkerNew(item) {
	for (var i = 0; i < data.length; i++) {
		var dataPhoto = data[i];
		if (dataPhoto.kategori == item) {
			var latLng = new google.maps.LatLng(dataPhoto.lat,dataPhoto.lng);

			var marker = new google.maps.Marker({
				map: map,
				position: latLng,
				kategori: item,
				lat: dataPhoto.lat,
				lon: dataPhoto.lng,
				id: dataPhoto.id
			});
		
			markers.push(marker);
      const waktuDatang = dataPhoto.waktu_datang ? moment(dataPhoto.waktu_datang).format('HH:mm') : '-';
      const waktuPulang = dataPhoto.waktu_pulang ? moment(dataPhoto.waktu_pulang).format('HH:mm') : '-';
		
      var contentString = "<div id='"+dataPhoto.id+"' style='width:150px;'>\
        <div style='margin:20px 0px 0px 20px;'>\
          <div style='margin-top:-10px; margin-bottom:10px;'>\
            <i class='glyphicon glyphicon-user'></i>"+dataPhoto.nama_user+"<br>\
            <p>"+moment(dataPhoto.waktu_datang).format('DD MMMM YYYY')+"</p>\
            <small class='text-muted'>Jam Masuk : "+waktuDatang+"</small><br>\
            <small class='text-muted'>Jam Keluar : "+waktuPulang+"</small><br>\
          </div>\
        </div>\
      </div>";
			var j = markers.length - 1;
		
			infowindow[j] = new google.maps.InfoWindow({
				content: contentString  
			});
		
			google.maps.event.addListener(markers[j],'click', (function(marker_window,data_window,info_window){ 
				return function() {
					info_window.open(map,marker_window);
				};
			})(markers[j],dataPhoto.id,infowindow[j]));	
		}
	}
}

function removeMarker(item) { 
	var z = 0;
	var arr = markers;
	
	for (var i = 0; i < markers.length; i++) {
		markers[i].setMap(null);
	}
	markers = [];

	for (var i = 0; i < arr.length; i++) {
		var mark = 1;
		var ary = arr[i];
		if(ary.kategori == item){
			mark = 0;
		}

		if (mark == 1) {
      var latLng = new google.maps.LatLng(ary.lat,ary.lon);
      var marker = new google.maps.Marker({
        map: map,
        position: latLng,
        kategori: ary.kategori,
        lat: ary.lat,
        lon: ary.lon,
        id: ary.id
      });

      markers.push(marker);

      var contentString = "<div id='"+ary.id+"' style='width:150px;'></div>";

      infowindow[z] = new google.maps.InfoWindow({
        content: contentString  
      });

      google.maps.event.addListener(markers[z],'click', (function(marker_window,data_window,info_window){ 
        return function() {
          info_window.open(map,marker_window);
          $('#'+data_window).load(domain+'Administrator/maps/info_window/'+data_window);
        };
      })(markers[z],ary.id,infowindow[z]));

      z++;
    }
	}
}