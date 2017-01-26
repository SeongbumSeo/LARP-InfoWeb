function showMap(caption) {
	$('#map > h3').html(caption);
	$('#map').foundation('open');
	
	return createMap();
}

function createMap() {
	var mapType = new SanMapType(0, 3, function (zoom, x, y) {
		return x == -1 && y == -1 
		? "images/maps/tiles/map.outer.png" 
		: "images/maps/tiles/map." + zoom + "." + x + "." + y + ".png";
	});
	return SanMap.createMap(document.getElementById('map-canvas'), { '위성': mapType }, 3);
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