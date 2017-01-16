$(document).ready(function () {
	$('#profile .show-item-list').on('click', function() {
		showItemData("내 아이템", 2);
	});
});

function loadPlayerInformation() {
	$.ajax({
		type: "get",
		url: "functions/playerinfo.php",
		cache: false
	}).done(function(data) {
		data_splited = data.split('|');
		if(data_splited[0] == 0)
			updateSignedStatus();
		else if(data_splited[0] == 1) {
			var i = 1;
			var cnt;

			$('#profile .username').html(data_splited[i++]);
			$('#profile .badge.level').html(data_splited[i++]);
			if(data_splited[i++].length > 0)
				$('#profile .label.party').removeClass('hide').html(data_splited[i-1]);
			else
				$('#profile .label.party').addClass('hide');
			$('#profile .img img').attr('src', 'images/skins/' + data_splited[i++] + '.png');
			$('#profile .health').css('width', data_splited[i++] + '%');
			$('#profile .hunger').css('width', data_splited[i++] + '%');
			$('#profile .status-details').html(data_splited[i++]);

			var num_vehs = parseInt(data_splited[i++]);
			$('.each-vehicle > div').not($('.hide')).remove();
			for(cnt = 1; data_splited[i] == 'vehicle'; cnt++) {
				i++;
				var block = $('.each-vehicle div.vblock.hide').clone().appendTo('.each-vehicle').removeClass('hide');

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
				block.find('h3').html(data_splited[i++]);
				block.find('.img img')
					.attr('src', 'http://weedarr.wdfiles.com/local--files/veh/' + data_splited[i++] + '.png');
				block.find('.health').css('width', data_splited[i++] + '%');
				block.find('.fuel').css('width', data_splited[i++] + '%');
				block.find('.status-details').html(data_splited[i++]);
			}
		}
		else if(data_splited[0] == 2)
			setTimeout("loadPlayerInformation()", 1000);
	});
}

function showItemData(caption, status, statusdata=null) {
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
		data_splited = data.split('|');
		if(data_splited[0] == 0)
			updateSignedStatus();
		else if(data_splited[0] == 1) {
			var i = 1;
			var cnt;
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

			$("#items").foundation('open');
		}
	});
}