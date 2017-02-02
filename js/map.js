function showMap(caption) {
	$('#map > h3').html(caption);
	$('#map > .goback-button').addClass('hide');
	$('#map > .goback-button').attr('command', null);
	$('#map > .goback-button').unbind('click');
	$('#map').foundation('open');
	
	return createMap();
}

function createMap() {
	var mapType_Normal = new SanMapType(0, 4, function(zoom, x, y) {
		return x == -1 && y == -1
		? "images/maps/tiles/normal.outer.png"
		: "images/maps/tiles/normal." + zoom + "." + x + "." + y + ".png";
	});
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

function addMarker(map, x, y, icon, content) {
	var infoWindow = content == null ? null : new google.maps.InfoWindow({ content: content });
	var marker = new google.maps.Marker({
		position: SanMap.getLatLngFromPos(x, y),
		map: map,
		icon: icon == null ? "images/icons/placeholder.png" : icon
	});
	google.maps.event.addListener(marker, 'click', function() {
		map.setCenter(marker.position);
		if(infoWindow != null)
			infoWindow.open(map, marker);
	});
}