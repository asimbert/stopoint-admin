<?php
	$style = $_GET['style'];
	setcookie("style", $style, time()+604800);
	echo $style; 
?>