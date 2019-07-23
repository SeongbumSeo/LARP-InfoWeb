$(document).ready(function() {
	Notice.getNotice(Notice.URL_NOTICE, '.notice-list');
	Notice.getNotice(Notice.URL_UPDATE, '.update-list');
});

function Notice() { }

Notice.MAX_NOTICES = 10;
Notice.URL_NOTICE = "http://cafe.daum.net/_c21_/bbs_list?grpid=1GYY4&mgrpid=&fldid=F0AS";
Notice.URL_UPDATE = "http://cafe.daum.net/_c21_/bbs_list?grpid=1GYY4&mgrpid=&fldid=F41s";

Notice.getNotice = function(url, selector) {
	$.ajax({
		type: "post",
		url: "functions/gethtmlcontents.php",
		cache: false,
		data: {
			url: url
		}
	}).done(function(data) {
		var tbody = $(data).find('table.bbsList tbody');
		var cnt = 0;

		for(var i = 1; cnt < Notice.MAX_NOTICES; i++) {
			var row = tbody.find('tr:nth-child(' + i + ')');

			if(row.length === 0)
				break;
			else if(row.find('td.subject_guide').length !== 0) 
				continue;
			else if(row.find('td.headcate span.headcont').text() == "종료")
				continue;
			else if(row.find('td.num img').length !== 0)
				continue;
			else {
				var block = $(selector).find('tr.hide').clone().appendTo(selector).removeClass('hide');
				var subject = row.find('td.subject').html();
				var date = row.find('td.date').html();
				
				subject = subject.split("<a href=\"javascript:;\"")[0];
				subject = subject.replace("href=\"", "href=\"http://cafe.daum.net");
				subject = subject.replace("<a ", "<a target=\"_blank\" ");
				subject = subject.replace("color=\"#000000\"", "");

				block.find('td:first-child').html(subject);
				block.find('td:nth-child(2)').html(date);

				cnt++;
			}
		}

	});
}