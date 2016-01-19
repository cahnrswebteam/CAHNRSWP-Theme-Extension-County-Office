jQuery( document ).ready( function($) {

	if ( 'undefined' === typeof map_data ) {
		return;
	}

	if ( 'undefined' !== typeof map_data.coords ) {
		use_coords();
	} else {
		use_address();
	}

	function use_coords() {
		position = new google.maps.LatLng(map_data.coords);
		initialize();
	}

	function use_address() {
		var geocoder = new google.maps.Geocoder();
		geocoder.geocode(
			{ "address": map_data.address },
			function ( results, status ) {
				if ( status == google.maps.GeocoderStatus.OK ) {
					position = results[0].geometry.location;
					initialize();
				}
			}
		);
	}

	function initialize() {

		var map = new google.maps.Map(document.getElementById( 'county-google-map' ), {
			//zoom : parseInt( map_data.zoom ),
			zoom : 16,
			center : position,
			mapTypeId : google.maps.MapTypeId.ROADMAP
		});

		var marker = new google.maps.Marker( {
			map : map,
			title : map_data.title,
			position : position
		});

		var marker_content = '<div style="color: #333; font-size: 13px;">'+
		'<div class="title" style="font-size: 14px; font-weight: bold;">'+map_data.title+'</div>'+
		map_data.desc+
		'<div><a href="https://www.google.com/maps?daddr=' + position + '" target="_blank">Directions &raquo;</a></div>'
		'</div>';

		var infowindow = new google.maps.InfoWindow({
			content: marker_content
		});

		marker.addListener('click', function() {
			infowindow.open(map, marker);
		});

		var center;

		function calculateCenter() {
			center = map.getCenter();
		}

		google.maps.event.addDomListener(map, 'idle', function() {
			calculateCenter();
		});

		google.maps.event.addDomListener(window, 'resize', function() {
			map.setCenter(center);
		});

	}

});