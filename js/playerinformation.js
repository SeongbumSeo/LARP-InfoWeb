const tooltipSelector = 'aria-describedby';

$(document).ready(function () {
	$('#profile .show-item-list').on('click', function() {
		showItemData("내 아이템", 2, null);
	});

	$('#profile .show-map').on('click', function() {
		if(!$('#profile .show-map').hasClass('disabled')) {
			var x = $(this).attr('x');
			var y = $(this).attr('y');
			var map = showMap("내 위치");

			map.setCenter(SanMap.getLatLngFromPos(x, y));
			addMarker(map, x, y, null, null);
		}
	});

	$('#profile .show-usagelog').on('click', function() {
		showUsageLog();
	});
});

function loadPlayerInformation() {
	$.ajax({
		type: "get",
		url: "functions/playerinfo.php",
		cache: false
	}).done(function(data) {
		switch(data) {
			case "Session Error":
				updateSignedStatus();
				break;
			case "No Data":
				setTimeout("loadPlayerInformation()", 1000);
				break;
			default:
				var skinid;
				var obj;
				var player = $(data).find('Player');

				$('#profile .username').html(player.find('Username').text());
				$('#profile .badge.level').html(player.find('Level').text());
				if(player.find('Party').text().length > 0)
					$('#profile .label.party').removeClass('hide').html(player.find('Party').text());
				else
					$('#profile .label.party').addClass('hide');
				$('#profile .img img').attr('src', 'images/skins/' + (skinid = parseInt(player.find('Skin').text())) + '.png');
				$('#profile .health').css('width', parseFloat(player.find('Health').text()) + '%');
				$('#profile .hunger').css('width', parseFloat(player.find('Hunger').text()) + '%');
				$('#profile .show-map').attr('x', player.find('PositionX').text());
				$('#profile .show-map').attr('y', player.find('PositionY').text());
				$('#profile .status-details').html(
					"<div>" + player.find('Age').text() + player.find('PhoneNumber').text() + player.find('Origin').text() + "</div>" +
					"<div>" + player.find('Money').text() + player.find('Bank').text() + player.find('Bankbook').text() + "</div>" + 
					"<div>" + player.find('FactionName').text() + player.find('Job').text() + "</div>" +
					"<div>" + player.find('Warns').text() + player.find('Praises').text() + "</div>" + 
					"<div>" + player.find('Location').text() + "</div>"
				);

				obj = $('#profile .show-map');
				$('#'+obj.attr(tooltipSelector)).remove();
				if(parseInt(player.find('Trackable').text()) != 1) {
					obj.addClass('disabled');
					new Foundation.Tooltip(obj, { tipText: "추적 불가능한 위치에 있습니다." });
				} else if(obj.hasClass('disabled'))
					obj.removeClass('disabled');

				obj = $('#profile .img img');
				$('#'+obj.attr(tooltipSelector)).remove();
				new Foundation.Tooltip(obj, { tipText: "스킨 " + skinid });

				var blocks = $('.each-vehicle div.vblock').not($('.hide'));
				blocks.each(function() {
					$('#'+$(this).find('.show-map').attr('aria-describedby')).remove();
					$('#'+$(this).find('.img img').attr('aria-describedby')).remove();
					$('#'+$(this).find('h3').attr('aria-describedby')).remove();
					$('#'+$(this).find('.fuel').parent().attr('aria-describedby')).remove();
					$('#'+$(this).find('.health').parent().attr('aria-describedby')).remove();
					$(this).remove();
				});

				var num_vehs = parseInt(player.find('NumVehicles').text());
				for(var i = 0; i < num_vehs; i++) {
					var vehicle = $(data).find('Vehicle' + i);
					var block = $('.each-vehicle div.vblock.hide').clone().appendTo('.each-vehicle').removeClass('hide');
					var modelname;

					if(num_vehs == 1)
						block.addClass('large-centered').css('float', 'none');
					else if(i == num_vehs)
						block.addClass('end');
					else
						$('.each-vehicle div.vmargin.hide').clone().appendTo('.each-vehicle').removeClass('hide');

					block.find('.show-item-list').attr('vid', vehicle.find('ID').text());
					block.find('.show-item-list, .show-map').attr('vcaption', vehicle.find('Caption').text());
					block.find('h3').html(modelname = vehicle.find('Modelname').text());
					block.find('.img img')
						.attr('src', 'http://weedarr.wdfiles.com/local--files/veh/' + vehicle.find('Model').text() + '.png');
					block.find('.health').css('width', parseFloat(vehicle.find('Health').text()) + '%');
					block.find('.fuel').css('width', parseFloat(vehicle.find('Fuel').text()) + '%');
					block.find('.show-map').attr('x', vehicle.find('PositionX').text());
					block.find('.show-map').attr('y', vehicle.find('PositionY').text());
					block.find('.status-details').html(
						vehicle.find('NumberPlate').text() +
						vehicle.find('Engine').text() +
						vehicle.find('Active').text() +
						vehicle.find('Locked').text() +
						vehicle.find('BlowedCnt').text() +
						vehicle.find('Location').text()
					);

					block.find('.show-item-list').on('click', function() {
						showItemData($(this).attr('vcaption'), 3, $(this).attr('vid'));
					});

					switch(parseInt(vehicle.find('Trackable').text())) {
						case 1:
							block.find('.show-map').on('click', function() {
								var x = $(this).attr('x');
								var y = $(this).attr('y');
								var map = showMap($(this).attr('vcaption'));

								map.setCenter(SanMap.getLatLngFromPos(x, y));
								addMarker(map, x, y, null, null);
							});
							break;
						case 2:
							obj = block.find('.show-map').addClass('disabled');
							new Foundation.Tooltip(obj, { tipText: "GPS가 부착되지 않은 차량입니다." });
							break;
						case 3:
							obj = block.find('.show-map').addClass('disabled');
							new Foundation.Tooltip(obj, { tipText: "추적 불가능한 위치에 있습니다." });
							break;
					}

					obj = block.find('.img img');
					new Foundation.Tooltip(obj, { tipText: modelname });
					obj = block.find('h3').addClass('top');
					new Foundation.Tooltip(obj, { tipText: "기종" });
					obj = block.find('.fuel').parent().addClass('top');
					new Foundation.Tooltip(obj, { tipText: "연료" });
					obj = block.find('.health').parent();
					new Foundation.Tooltip(obj, { tipText: "체력" });
				}
				break;
		}
	});
}

function showItemData(caption, status, statusdata) {
	var cmd;

	if(status == 2)
		cmd = 0;
	else if(status == 3)
		cmd = 1;

	$.ajax({
		type: "post",
		url: "functions/item.php",
		cache: false,
		data: {
			cmd: cmd,
			vid: statusdata
		}
	}).done(function(data) {
		switch(data) {
			case "Session Error":
				updateSignedStatus();
				break;
			default:
				$('#items > h3').html(caption);
				$('.item-data > tr').not($('.hide')).remove();

				var num_items = parseInt($(data).find('NumItems').text());
				for(var i = 0; i < num_items; i++) {
					var item = $(data).find('Item' + i);
					var block = $('.item-data tr.hide').clone().appendTo('.item-data').removeClass('hide');

					block.find('td:first-child').html(item.find('ID').text());
					block.find('td:nth-child(2)').html(item.find('Amount').text());
					block.find('td:nth-child(3)').html(item.find('Name').text());
				}

				$('#items').foundation('open');
				break;
		}
	}).fail(function() {
		alert("아이템 데이터를 불러오지 못하였습니다.");
	});
}

function showUsageLog() {
	$.ajax({
		type: "get",
		url: "functions/usagelog.php",
		cache: false
	}).done(function(data) {
		switch(data) {
			case "Session Error":
				updateSignedStatus();
				break;
			default:
				$('.usagelog-data > tr').not($('.hide')).remove();

				var num_rows = parseInt($(data).find('NumRows').text());
				for(var i = 0; i < num_rows; i++) {
					var row = $(data).find('Row' + i);
					var block = $('.usagelog-data tr.hide').clone().appendTo('.usagelog-data').removeClass('hide');

					block.find('td:first-child').css('color', row.find('TypeColor').text());
					block.find('td:first-child').html(row.find('Type').text());
					block.find('td:nth-child(2)').html(row.find('Contents').text());
					block.find('td:nth-child(3)').html(row.find('Date').text());
				}

				$('#usagelog').foundation('open');
				break;
		}
	}).fail(function() {
		alert("이용 로그를 불러오지 못하였습니다.");
	});
}