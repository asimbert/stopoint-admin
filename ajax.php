<?php

/*
* this file handles the ajax requests
*/

require_once(dirname(__FILE__) . '/classes/class.database.php');

$db = new Database();

if (isset($_GET['gallery_id']) && isset($_GET['image'])) {
	$db->update('gallery',array('id' => $_GET['gallery_id'],'cover' => $_GET['image']),"id='$_GET[gallery_id]'") or die($db->error());
	echo "UPDATE gallery SET gallery_id='$_GET[gallery_id]',cover='$_GET[image]' WHERE id='$_GET[gallery_id]'";
}

if ($_GET['action'] == 'removePhoto' && isset($_GET['image_id'])) {
	
	$db->delete('images',"id='$_GET[image_id]'");
}

?>