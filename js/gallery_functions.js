function setAlbumCover(gallery_id,image) {
	
	$.get('ajax.php',{ gallery_id: gallery_id, image: image }, function(data) { console.log (data) });

	showNotification('cover_set','The image was set succesfully as album cover','success',true);
}

function deletePhoto(image_id) {
	
	$.get('ajax.php',{ action: 'removePhoto', image_id: image_id }, function(data) { console.log(data) });
	
	$('#image_' + image_id).fadeOut(1000).remove();
	$('#delete_image_' + image_id).fadeOut(1000).remove();
	
}