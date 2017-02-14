<?php

require_once('/home/stopoint/public_html/stopointsxgdlj123/allow-access.php');

$base_url = "https://www.stopoint.com/stopointsxgdlj123";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=8" >
		
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<title><?php $settings = $db->select("SELECT value FROM settings WHERE setting='backend_title'") or die($db->error()); ?><?=$settings['value']?></title>
			  
		<!-- Reset Stylesheet -->
		<link rel="stylesheet" href="<?php echo $base_url; ?>/css/reset.css" type="text/css" media="screen,print" />
	  
		<!-- Main Stylesheet -->
		<link rel="stylesheet" href="<?php echo $base_url; ?>/css/style.css" type="text/css" media="screen,print" />
		<!-- Print Stylesheet -->
		<link rel="stylesheet" href="<?php echo $base_url; ?>/css/styleprint.css" type="text/css" media="print" />
        
        <link rel="stylesheet" href="<?php echo $base_url; ?>/css/jRating.jquery.css" type="text/css" />

		<style type="text/css">
            .datasSent, .serverResponse{margin-top:20px;width:470px;height:73px;border:1px solid #F0F0F0;background-color:#3c383d;padding:10px;float:left;margin-right:10px}
            .datasSent{width:200px;position:fixed;left:680px;top:0}
            .serverResponse{position:fixed;left:680px;top:100px}
            .datasSent p, .serverResponse p {font-style:italic;font-size:12px}
            .exemple{margin-top:15px;}
            .clr{clear:both}
            pre {margin:0;padding:0}
            .notice {background-color:#F4F4F4;color:#666;border:1px solid #CECECE;padding:10px;font-weight:bold;width:600px;font-size:12px;margin-top:10px}
        </style>
		
		<?php 
			if(!empty($_COOKIE['style']))
			 	$style = $_COOKIE['style'];
			else 
				$style = 'green';
		?>
		
		<link id="stylesheet" rel="stylesheet" href="<?php echo $base_url; ?>/css/<?php echo $style; ?>.css" type="text/css" media="screen" />
		
		<!-- Invalid Stylesheet. This makes stuff look pretty. Remove it if you want the CSS completely valid -->
		<link rel="stylesheet" href="<?php echo $base_url; ?>/css/invalid.css" type="text/css" media="screen" />	
		<link rel="stylesheet" href="<?php echo $base_url; ?>/css/jquery-ui-1.7.2.custom.css" type="text/css" media="screen" />
	    <link rel="stylesheet" href="<?php echo $base_url; ?>/uploadify/uploadify.css" type="text/css" media="screen" />
	
		<!-- Internet Explorer Fixes Stylesheet -->
		
		<!--[if lte IE 7]>
			<link rel="stylesheet" href="resources/css/ie.css" type="text/css" media="screen" />
		<![endif]-->
		
		<script type="text/javascript" src="<?php echo $base_url; ?>/js/jquery.min.js"></script>
		<script type="text/javascript" src="<?php echo $base_url; ?>/js/simpla.jquery.configuration.js"></script>
		<script type="text/javascript" src="<?php echo $base_url; ?>/js/facebox.js"></script>
		<!--<script type="text/javascript" src="<?php echo $base_url; ?>/js/ui.core.js"></script>
		<script type="text/javascript" src="<?php echo $base_url; ?>/js/ui.dialog.js"></script>-->
		<script type="text/javascript" src="<?php echo $base_url; ?>/js/jquery-ui-1.7.3.custom.min.js"></script>
		<script type="text/javascript" src="<?php echo $base_url; ?>/js/functions.js"></script>
		
		<script type="text/javascript" src="<?php echo $base_url; ?>/js/swfobject.js"></script>
		<script type="text/javascript" src="<?php echo $base_url; ?>/uploadify/jquery.uploadify.min.js"></script>
        
        <script type="text/javascript" src="<?php echo $base_url; ?>/js/jRating.jquery.js"></script>
        <script type="text/javascript">
			$(document).ready(function(){
				$('.basic').jRating();
				
				$('.rb-rating').jRating({
					isDisabled : true
				});
				
				$('.rating').jRating({
				length:5,
				decimalLength:0.1
			});
			});
		</script>
        

		<!--[if IE 6]>
			<script type="text/javascript" src="<?php echo $base_url; ?>/js/DD_belatedPNG_0.0.7a.js"></script>
			<script type="text/javascript">
				DD_belatedPNG.fix('.png_bg, img, li');
			</script>
		<![endif]-->
		
		<script type="text/javascript" src="<?php echo $base_url; ?>/js/cms_dialog.js"></script>
		
		<?php if (strstr($_SERVER['SCRIPT_NAME'],"_edit.php")): ?>
			<!--<script src="<?php echo $base_url; ?>/js/nicEdit.js" type="text/javascript"></script>
			<script type="text/javascript">
			bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });
			</script>-->
			<script type="text/javascript" src="<?php echo $base_url; ?>/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
			<script type="text/javascript">
			$(document).ready(function() {
						tinyMCE.init({
						// General options
						mode : "textareas",
						theme : "advanced",
						plugins : "style,layer,advlink,preview,contextmenu,paste,nonbreaking,autoresize",
						// Theme options
						theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,link,unlink,|styleselect,formatselect,|,table,removeformat,code",
						theme_advanced_buttons2 : "",
						theme_advanced_buttons3 : "",
						theme_advanced_buttons4 : "",
						theme_advanced_toolbar_location : "top",
						theme_advanced_toolbar_align : "left",
						theme_advanced_statusbar_location : "bottom",
						theme_advanced_resizing : true,
						forced_root_block : "div"
						});
			});
			</script>
		<?php endif;?>
</head>
	
<body>
		<?php
			$baseurl = "https://www.stopoint.com/";
		?>
        <div id="body-wrapper"> <!-- Wrapper for the radial gradient background -->
		
		<div id="sidebar">
			<div id="sidebar-wrapper"> <!-- Sidebar with logo and menu -->
			
			<h1 id="sidebar-title"><?php echo $title['value']; ?></h1>
		  
			<!-- Logo (221px wide) -->
			<a href="<?php echo $base_url; ?>">
				<br>
				<br>
                
               
				<img id="logo" src="../stopointsxgdlj123/images/logo.png" style="width: 79%;
display: block;
margin-left: 25px;">
				</a>
		  
			<!-- Sidebar Profile links -->
			<div id="profile-links">
				<a href="<?php echo $site_url['value']; ?>" title="View the Site" target="_blank">View the Site</a> | <a href="logout.php" title="Log Out">Log Out</a>
			</div>
