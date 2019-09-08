function showMap(caption) {
	var map = createMap();
	addDefaultMarkers(map);

	$('#map > h3').html(caption);
	$('#map > .goback-button').addClass('hide');
	$('#map > .goback-button').attr('command', null);
	$('#map > .goback-button').unbind('click');
	$('#map').foundation('open');

	return map;
}

function createMap() {
	// 일반 지도
	var mapType_Normal = new SanMapType(0, 4, function(zoom, x, y) {
		return x == -1 && y == -1
		? "images/maps/tiles/normal.outer.png"
		: "images/maps/tiles/normal." + zoom + "." + x + "." + y + ".png";
	});
	// 위성 지도
	var mapType_Satellite = new SanMapType(0, 3, function (zoom, x, y) {
		return x == -1 && y == -1 
		? "images/maps/tiles/satellite.outer.png" 
		: "images/maps/tiles/satellite." + zoom + "." + x + "." + y + ".png";
	});

	return SanMap.createMap(document.getElementById('map-canvas'), {
		'일반': mapType_Normal,
		'위성': mapType_Satellite
	}, 3, null, false, '일반');
}

function addDefaultMarkers(map) {
	$.ajax({
		type: 'post',
		url: 'functions/map.php',
		cache: false,
		data: {
			excludecitymap: 0,
			excludeusermap: 0
		}
	}).done(function(json) {
		var data = JSON.parse(json);

		for (var i = 0; i < data.length; i++) {
			var position = data[i].Pos.split(',');
			var marker = 'marker_pin_32';
			
			if (data[i].Type === 'user') {
				marker = 'marker_favorite_32';
			}
			
			addMarker(map, position[0], position[1], marker, {
				labelContent: data[i].Name,
				labelClass: 'map-label label-default'
			});
		}
	});
}

function addMarker(map, x, y, icon, config) {
	var infoWindow = config.content === undefined ? null : new google.maps.InfoWindow({ content: config.content });
	var markerConfig = Object.assign({
		map: map,
		position: SanMap.getLatLngFromPos(x, y),
		icon: 'images/maps/markers/' + icon + '.png'
	}, config);
	var marker = new MarkerWithLabel(markerConfig);
	google.maps.event.addListener(marker, 'click', function() {
		map.setCenter(marker.position);
		if(infoWindow != null)
			infoWindow.open(map, marker);
	});
}