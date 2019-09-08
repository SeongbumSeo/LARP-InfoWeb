var tooltipSelector = 'aria-describedby';

$(document).ready(function () {
	$('#profile .show-item-list').on('click', function() {
		showItemData("내 아이템", 2, null);
	});

	$('#profile .show-map').on('click', function() {
		if(!$('#profile .show-map').hasClass('disabled')) {
			var x = parseFloat($(this).attr('x'));
			var y = parseFloat($(this).attr('y'));
			var y = parseFloat($(this).attr('y'));
			var map = showMap("내 위치");

			map.setCenter(SanMap.getLatLngFromPos(x, y));
			addMarker(map, x, y, 'marker_user_32', {
				labelContent: '내 위치',
				labelClass: 'map-label label-destination'
			});
		}
	});

	$('#profile .show-usagelog').on('click', function() {
		showUsageLog();
	});
});

function loadPlayerInformation() {
	$.ajax({
		type: "get",
		url: "functions/playerinformation.php",
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
				var json = JSON.parse(data);

				$('#profile .username').html(json.Player.Username);
				$('#profile .badge.level').html(json.Player.Level);
				/*if(json.Player.Party.length > 0)
					$('#profile .label.party').removeClass('hide').html(json.Player.Party);
				else*/
				$('#profile .label.party').addClass('hide');
				$('#profile .img img').attr('src', 'images/skins/' + (skinid = parseInt(json.Player.Skin)) + '.png');
				$('#profile .health').css('width', parseFloat(json.Player.Health) + '%');
				$('#profile .hunger').css('width', parseFloat(json.Player.Hunger) + '%');
				$('#profile .show-map').attr('x', json.Player.PositionX);
				$('#profile .show-map').attr('y', json.Player.PositionY);
				$('#profile .status-details').html(
					"<div>" + json.Player.Age + json.Player.PhoneNumber + json.Player.Origin + "</div>" +
					"<div>" + json.Player.Money + json.Player.Bank + json.Player.Bankbook + "</div>" + 
					"<div>" + json.Player.FactionName + json.Player.Job + "</div>" +
					"<div>" + json.Player.Warns + json.Player.Praises + "</div>" + 
					"<div>" + json.Player.Location + "</div>"
				);

				obj = $('#profile .show-map');
				$('#'+obj.attr(tooltipSelector)).remove();
				if(parseInt(json.Player.Trackable) != 1) {
					obj.addClass('disabled');
					new Foundation.Tooltip(obj, { tipText: "추적 불가능한 위치에 있습니다." });
				} else if(obj.hasClass('disabled'))
					obj.removeClass('disabled');
				obj = $('#profile .img img');
				$('#'+obj.attr(tooltipSelector)).remove();
				new Foundation.Tooltip(obj, { tipText: "스킨 " + skinid });
				obj = $('#profile .hunger').parent();
				$('#'+obj.attr(tooltipSelector)).remove();
				new Foundation.Tooltip(obj, { tipText: "허기 " + json.Player.Hunger + "/100" });
				obj = $('#profile .health').parent();
				$('#'+obj.attr(tooltipSelector)).remove();
				new Foundation.Tooltip(obj, { tipText: "체력 " + json.Player.Health + "/" + json.Player.MaxHealth });

				var blocks = $('.each-vehicle div.vblock').not($('.hide'));
				blocks.each(function() {
					$('#'+$(this).find('.show-map').attr(tooltipSelector)).remove();
					$('#'+$(this).find('.img img').attr(tooltipSelector)).remove();
					$('#'+$(this).find('h3').attr(tooltipSelector)).remove();
					$('#'+$(this).find('.fuel').parent().attr(tooltipSelector)).remove();
					$('#'+$(this).find('.health').parent().attr(tooltipSelector)).remove();
					$(this).remove();
				});

				var num_vehs = json.Vehicle.length;
				$('#vehicles').toggleClass('hide', (num_vehs == 0));
				for(var i = 0; i < num_vehs; i++) {
					var block = $('.each-vehicle div.vblock.hide').clone().appendTo('.each-vehicle').removeClass('hide');
					var modelname;

					if(num_vehs == 1)
						block.addClass('large-centered').css('float', 'none');
					else if(i == num_vehs)
						block.addClass('end');
					else
						$('.each-vehicle div.vmargin.hide').clone().appendTo('.each-vehicle').removeClass('hide');

					block.find('.button-group').attr('vid', json.Vehicle[i].ID);
					block.find('.button-group').attr('vcaption', json.Vehicle[i].Caption);
					block.find('h3').html(modelname = json.Vehicle[i].Modelname);
					block.find('.img img')
						.attr('src', 'http://weedarr.wdfiles.com/local--files/veh/' + json.Vehicle[i].Model + '.png');
					block.find('.health').css('width', parseFloat(json.Vehicle[i].Health/10) + '%');
					block.find('.fuel').css('width', parseFloat(json.Vehicle[i].Fuel) + '%');
					block.find('.show-map').attr('x', json.Vehicle[i].PositionX);
					block.find('.show-map').attr('y', json.Vehicle[i].PositionY);
					block.find('.status-details').html(
						json.Vehicle[i].NumberPlate +
						json.Vehicle[i].Engine +
						json.Vehicle[i].Active +
						json.Vehicle[i].Locked +
						json.Vehicle[i].BlowedCnt_ +
						json.Vehicle[i].Location
					);

					block.find('.show-item-list').on('click', function() {
						showItemData($(this).parent().attr('vcaption'), 3, $(this).parent().attr('vid'));
					});
					block.find('.show-carblowlog').on('click', function() {
						showCarBlowLog($(this).parent().attr('vcaption'), $(this).parent().attr('vid'));
					});

					switch(parseInt(json.Vehicle[i].Trackable)) {
						case 1:
							block.find('.show-map').on('click', function() {
								var x = $(this).attr('x');
								var y = $(this).attr('y');
								var map = showMap($(this).parent().attr('vcaption'));

								map.setCenter(SanMap.getLatLngFromPos(x, y));
								addMarker(map, x, y, 'marker_placeholder_32', json.Vehicle[i].NumberPlate, null);
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
					new Foundation.Tooltip(obj, { tipText: "연료 " + parseInt(json.Vehicle[i].Fuel) + "/100" });
					obj = block.find('.health').parent();
					new Foundation.Tooltip(obj, { tipText: "체력 " + json.Vehicle[i].Health + "/" + (1000-50*json.Vehicle[i].BlowedCnt) });
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

				var json = JSON.parse(data);
				for(var i = 0; i < json.length; i++) {
					var block = $('.item-data tr.hide').clone().appendTo('.item-data').removeClass('hide');

					block.find('td:first-child').html(json[i].ID);
					block.find('td:nth-child(2)').html(json[i].Amount);
					block.find('td:nth-child(3)').html(json[i].Name);
				}
				if(i == 0)
					$('.item-data').append("<tr><td colspan=\"3\">아이템이 없습니다.</td></tr>");

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

				var json = JSON.parse(data);
				for(var i = 0; i < json.length; i++) {
					var block = $('.usagelog-data tr.hide').clone().appendTo('.usagelog-data').removeClass('hide');

					block.find('td:first-child').css('color', json[i].TypeColor);
					block.find('td:first-child').html(json[i].Type);
					block.find('td:nth-child(2)').html(json[i].Contents);
					block.find('td:nth-child(3)').html(json[i].Date);
				}
				if(i == 0)
					$('.item-data').append("<tr><td colspan=\"3\">이용 로그가 비어있습니다.</td></tr>");

				$('#usagelog').foundation('open');
				break;
		}
	}).fail(function() {
		alert("이용 로그를 불러오지 못하였습니다.");
	});
}

function showCarBlowLog(caption, vid) {
	$.ajax({
		type: "post",
		url: "functions/carblowlog.php",
		cache: false,
		data: {
			vid: vid
		}
	}).done(function(data) {
		switch(data) {
			case "Session Error":
				updateSignedStatus();
				break;
			default:
				$('#carblowlog > h3').html(caption);
				$('.carblowlog-data > tr').not($('.hide')).remove();

				var json = JSON.parse(data);
				for(var i = 0; i < json.length; i++) {
					var block = $('.carblowlog-data tr.hide').clone().appendTo('.carblowlog-data').removeClass('hide');

					block.find('td:first-child').html(json[i].User);
					block.find('td:nth-child(2)').html(json[i].Location);
					block.find('td:nth-child(3)').html(json[i].Time);

					var html;
					if(parseInt(json[i].Trackable) == 1) {
						var button = block.find('td:nth-child(2)');

						button.addClass('.show-carblowlog-map');
						button.css('cursor', 'pointer');
						button.attr('x', json[i].PositionX);
						button.attr('y', json[i].PositionY);
						button.attr('caption', caption);
						button.attr('vid', vid);
						button.on('click', function() {
							var x = $(this).attr('x');
							var y = $(this).attr('y');
							var map = showMap($(this).attr('caption') + " 블로우 위치");

							map.setCenter(SanMap.getLatLngFromPos(x, y));
							addMarker(map, x, y, 'marker_placeholder_32', json.Vehicle[i].NumberPlate, null);

							var command = "showCarBlowLog(\"" + $(this).attr('caption') + "\", " + $(this).attr('vid') + ");";
							$('#map > .goback-button').removeClass('hide');
							$('#map > .goback-button').attr('command', command);
							$('#map > .goback-button').on('click', function() {
								eval($(this).attr('command'));
							});
						});
					}
				}
				if(i == 0)
					$('.carblowlog-data').append("<tr><td colspan=\"3\">차량 블로우 로그가 비어있습니다.</td></tr>");

				$('#carblowlog').foundation('open');
				break;
		}
	}).fail(function() {
		alert("차량 블로우 로그를 불러오지 못하였습니다.");
	});
}