$(document).ready(function () {
	$('#profile .show-item-list').on('click', function() {
		showItemData("내 아이템", 2, null);
	});

	$('#profile .show-map').on('click', function() {
		var x = $(this).attr('x');
		var y = $(this).attr('y');
		var map = showMap("내 위치");

		map.setCenter(SanMap.getLatLngFromPos(x, y));
		addMarker(map, x, y, null, null);
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
		switch(parseInt(data)) {
			case 0:
				updateSignedStatus();
				break;
			case 1:
				var i = 1;
				var cnt;
				var skinid;
				var data_splited = data.split('|');

				$('#profile .username').html(data_splited[i++]);
				$('#profile .badge.level').html(data_splited[i++]);
				if(data_splited[i++].length > 0)
					$('#profile .label.party').removeClass('hide').html(data_splited[i-1]);
				else
					$('#profile .label.party').addClass('hide');
				$('#profile .img img').attr('src', 'images/skins/' + (skinid = data_splited[i++]) + '.png');
				$('#profile .health').css('width', data_splited[i++] + '%');
				$('#profile .hunger').css('width', data_splited[i++] + '%');
				$('#profile .show-map').attr('x', data_splited[i++]);
				$('#profile .show-map').attr('y', data_splited[i++]);
				$('#profile .status-details').html(data_splited[i++]);

				new Foundation.Tooltip($('#profile .img img'), { tipText: "스킨 " + skinid });

				var num_vehs = parseInt(data_splited[i++]);
				$('.each-vehicle > div').not($('.hide')).remove();
				for(cnt = 1; data_splited[i] == 'vehicle'; cnt++) {
					i++;
					var block = $('.each-vehicle div.vblock.hide').clone().appendTo('.each-vehicle').removeClass('hide');
					var modelname;

					if(num_vehs == 1)
						block.addClass('large-centered').css('float', 'none');
					else if(cnt == num_vehs)
						block.addClass('end');
					else
						$('.each-vehicle div.vmargin.hide').clone().appendTo('.each-vehicle').removeClass('hide');

					block.find('.show-item-list').attr('vid', data_splited[i++]);
					block.find('.show-item-list').attr('vname', data_splited[i++]);
					block.find('.show-item-list').on('click', function() {
						showItemData($(this).attr('vname'), 3, $(this).attr('vid'));
					});
					block.find('h3').html(modelname = data_splited[i++]);
					block.find('.img img')
						.attr('src', 'http://weedarr.wdfiles.com/local--files/veh/' + data_splited[i++] + '.png');
					block.find('.health').css('width', data_splited[i++] + '%');
					block.find('.fuel').css('width', data_splited[i++] + '%');
					block.find('.status-details').html(data_splited[i++]);

					var obj;
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
			case 2:
				setTimeout("loadPlayerInformation()", 1000);
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
		switch(parseInt(data)) {
			case 0:
				updateSignedStatus();
				break;
			case 1:
				var i = 1;
				var cnt;
				var data_splited = data.split('|');
				var num_items = parseInt(data_splited[i++]);

				$('#items > h3').html(caption);

				$('.item-data > tr').not($('.hide')).remove();
				for(cnt = 1; data_splited[i] == 'item'; cnt++) {
					i++;
					var block = $('.item-data tr.hide').clone().appendTo('.item-data').removeClass('hide');

					block.find('td:first-child').html(data_splited[i++]);
					block.find('td:nth-child(2)').html(data_splited[i++]);
					block.find('td:nth-child(3)').html(data_splited[i++]);
				}

				$('#items').foundation('open');
				break;
		}
	});
}

function showUsageLog() {
	$.ajax({
		type: "get",
		url: "functions/usagelog.php",
		cache: false
	}).done(function(data) {
		switch(parseInt(data)) {
			case 0:
				updateSignedStatus();
				break;
			case 1:
				var i = 1;
				var data_splited = data.split('|');

				$('.usagelog-data > tr').not($('.hide')).remove();
				while(data_splited.length > i+3) {
					var block = $('.usagelog-data tr.hide').clone().appendTo('.usagelog-data').removeClass('hide');

					block.find('td:first-child').css('color', data_splited[i++]);
					block.find('td:first-child').html(data_splited[i++]);
					block.find('td:nth-child(2)').html(data_splited[i++]);
					block.find('td:nth-child(3)').html(data_splited[i++]);
				}

				$('#usagelog').foundation('open');
				break;
		}
	});
}