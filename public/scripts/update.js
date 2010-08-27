
var size = 100; // init size
var node = '';

order_update = function() {
	$('#ajax_loading_img').css({'display':'inline'});
	$.get('/Journals/form=xml&begin='+size+'&size=100', function(data) {
		size = size + 100;
		$(data).find('journal').each(function() {
			var date = $(this).find("date").text().replace(/-/g,".");
			var time = $(this).find("time").text();
			var timezone = $(this).find("timezone").text();
			var tag = $(this).find("tag").text();
			var subject = $(this).find("subject").text();
			var ref_source = $(this).find("ref").text();
			
			var ref = '';
			if ( ref_source != '' ) {
				ref = '<a href="'+ref_source+'">Link</a>';
			}
			
			node = node + '<div id="journal">\
			<div id="date">'+date+'</div> <div id="time">'+time+'</div> <div id="timezone">'+timezone+'</div>\
			<div id="tag">['+tag+']</div>\
			<div id="subject">' + subject + ' ' + ref + '</div>\
			</div>';
		})
		$('#journals_added').append(node);
		$('#ajax_loading_img').css({'display':'none'});
		node = '';
	});
}

all_update = function() {
	$('#ajax_loading_img').css({'display':'inline'});
	$.get('/Journals/form=xml&begin='+size+'&size=4294967295', function(data) {
		
		$(data).find('journal').each(function() {
			var date = $(this).find("date").text().replace(/-/g,".");
			var time = $(this).find("time").text();
			var timezone = $(this).find("timezone").text();
			var tag = $(this).find("tag").text();
			var subject = $(this).find("subject").text();
			var ref_source = $(this).find("ref").text();
			
			var ref = '';
			if ( ref_source != '' ) {
				ref = '<a href="'+ref_source+'">Link</a>';
			}
			
			node = node + '<div id="journal">\
			<div id="date">'+date+'</div> <div id="time">'+time+'</div> <div id="timezone">'+timezone+'</div>\
			<div id="tag">['+tag+']</div>\
			<div id="subject">' + subject + ref + '</div>\
			</div>';
			
			$('#controller').remove();
		})
		$('#journals_added').append(node);
		$('#ajax_loading_img').css({'display':'none'});
	});
}
