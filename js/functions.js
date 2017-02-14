function changeTheme(css) {
	$.get('style-switcher.php?style=' + css,function(data){
			$('#stylesheet').attr('href','css/' + data + '.css');
			$('#theme_msg').show("slow");
	});
}

function closeNotification() {
	
		$('.close').parent().fadeTo(400, 0, function () { // Links with the class "close" will close parent
					$(this).slideUp(400);
		});
		return false;
}

function showNotification(div,msg,type,close) {
	
	$('#' + div).html('<div class="notification '+type+' png_bg"> \
		<a href="#" class="close"><img src="images/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a> \
		<div> \
		'+msg+' \
		</div> \
	</div>').show("slow");
	
	if (close == true)
		setTimeout(function(){ closeNotification(); }, 2000);
}