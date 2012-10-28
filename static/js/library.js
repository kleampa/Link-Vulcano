$(document).ready(function() {

	//set active menu
	$('.nav').find('a[href="'+active_menu+'"]').parent().addClass("active");

	//show slots dropdown when a website is selected
	$('#search_link select[name=id_website]').change(function() {
		var val = $(this).val();
		if(val != "") {
		
			$.ajax({
			   type: "POST",
			   url: "index.php?act=_ajax&op=slots",
			   data: "id_website="+val,
			   success: function(msg){
				$('select[name=id_slot]').html(msg);
				$('select[name=id_slot]').show();
			   }
			 });
			
		}
		else {
			$('select[name=id_slot]').hide();
		}
	});
	
});

//add a new website (from modal box)
function add_website() {
	$('#add_website .modal-body .alert').remove();

	var url = $('input[name=url]').val();
	var msg = '';
	
	if(!validateURL(url)) {
		var msg = '<div class="alert alert-danger">You must enter a valid URL!</div>';
		$('#add_website .modal-body').prepend(msg);
	}
	else {
		$.ajax({
		   type: "POST",
		   url: "index.php?act=_ajax&op=add_website",
		   data: "url="+url,
		   success: function(msg){
			$('#add_website .modal-body').prepend(msg);
			$('input[name=url]').val('');
		   }
		 });
	}
	return false;
}

//change link status
function change_status(id_link,el) {
	var el = el;
	var content = el.html();
	var active = (content == '<i class="icon-pause icon-white"></i>') ? 0 : 1;
	
	$.ajax({
		type: "POST",
		url: "index.php?act=_ajax&op=change_status",
		data: "id_link="+id_link+"&active="+active,
		success: function(){
			document.location.reload();
		}
	});
	
}

//validate url
function validateURL(url) {
    return url.match(/^(ht|f)tps?:\/\/[a-z0-9-\.]+\.[a-z]{2,4}\/?([^\s<>\#%"\,\{\}\\|\\\^\[\]`]+)?$/);
}

//check code from website
function checkCode(id_website) {
	$('#check_results').show();
	$.ajax({
		type: "POST",
		url: "index.php?act=_ajax&op=check_code",
		data: "id_website="+id_website,
		success: function(msg){
			$('#check_results').html(msg);
		}
	});
}

//force update
function forceUpdate(id_website) {
	$.ajax({
		type: "POST",
		url: "index.php?act=_ajax&op=force_update",
		data: "id_website="+id_website,
		success: function(msg){
			$('#force_'+id_website+' .modal-body').html(msg);
		}
	});
}