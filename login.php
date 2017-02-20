<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
session_start();
ob_start();
require_once('init/init.php');
require_once(dirname(__FILE__) . '/classes/class.database.php');
require_once(dirname(__FILE__) . '/inc/functions.php');

e_reporting(); // error reporting ALL
$db = new Database();

// getting backend settings;

$settings = $db->select("SELECT value FROM settings WHERE setting='backend_title'") or die($db->error());

if (isset($_SESSION['logged_in']))
	header("Location: index.php");
	
$error_msg = '';


if (!empty($_POST['submit'])) {
	
	if (empty($_POST['username']) || empty($_POST['password'])) 
		$error_msg = 'Username and password are required.';
	else {		
	
		//Google reCaptcha response		
		//If it returns 1, recaptcha is verified. Otherwise recaptcha failed to verify.		
		//$recaptcha_verified = verify_recaptcha($_POST['g-recaptcha-response']);
		if(ENVIRONMENT === 'PRODUCTION'){	
			$reURL = 'https://www.google.com/recaptcha/api/siteverify';	
			$reResponse = $_POST['g-recaptcha-response'];	
			$reSubmission = json_decode(file_get_contents($reURL."?secret=".RECAPTCHA_SECRET_KEY."&response=".$reResponse), true);				
			if($reSubmission['success'] != 1){			
				$error_msg = 'Please verify captcha.';		
			}
		}
		$user = sanitize($_POST['username']);
		$password = sanitize($_POST['password']);

		$result = $db->select("SELECT Password FROM user WHERE EmailAddress = '$user' AND UserType != 'User'");

		if ($result['Password'] != "") {
			
			$valid = ($password == $result['Password']) ? 1 : 0;
		
			if ($valid == 0)
				$error_msg = 'Invalid user or password';
		}
		else
			$error_msg = 'Invalid user or password';
		
		
		if ($error_msg == '') {
			
			  $_SESSION['logged_in'] = true;
			
			  // get all the details of the user
			
			  $details = $db->select("SELECT * FROM user WHERE EmailAddress='$user' AND UserType != 'User'");
			  $_SESSION['user_info'] = $details;
			  $_SESSION['userid'] = $details['id'];
			  $_SESSION['UserType'] = $details['UserType'];
			  $_SESSION['EmailAddress'] = $details['EmailAddress'];
		  
			// setting cookie (for 30 days) if remember is checked 
			if(isset($_POST['remember'])) {
			
				 $cookie_value = md5($user . uniqid(time()));
				 setcookie('autologin',$cookie_value,time() + (3600 * 24 * 30));
				 
				 $db->query("UPDATE users SET cookie='$cookie_value' WHERE username='$user' AND UserType != 'User'") or die($db->error());
			}
			
			header("Location: index.php");
		}	
	}
}
?>

<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		
		<title><?=$settings['value']?></title>
		
		<!--                       CSS                       -->
	  
		<!-- Reset Stylesheet -->
		<link rel="stylesheet" href="css/reset.css" type="text/css" media="screen" />
	  
		<!-- Main Stylesheet -->
		<link rel="stylesheet" href="css/style.css" type="text/css" media="screen" />
		
		<?php 
			if(!empty($_COOKIE['style']))
			 	$style = $_COOKIE['style'];
			else 
				$style = 'green';
		?>
		
		<link id="stylesheet" rel="stylesheet" href="css/<?php echo $style; ?>.css" type="text/css" media="screen" />
		
		<!-- Invalid Stylesheet. This makes stuff look pretty. Remove it if you want the CSS completely valid -->
		<link rel="stylesheet" href="css/invalid.css" type="text/css" media="screen" />	
		
	 		
		<!-- Internet Explorer Fixes Stylesheet -->
		
		<!--[if lte IE 7]>
			<link rel="stylesheet" href="css/ie.css" type="text/css" media="screen" />
		<![endif]-->
		
		<!--                       Javascripts                       -->
	  
		<!-- jQuery -->
		<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
				
		<!-- Internet Explorer .png-fix -->
		
		<!--[if IE 6]>
			<script type="text/javascript" src="js/DD_belatedPNG_0.0.7a.js"></script>
			<script type="text/javascript">
				DD_belatedPNG.fix('.png_bg, img, li');
			</script>
		<![endif]-->
		<?php if(ENVIRONMENT === 'PRODUCTION'){ ?>
		<script src='https://www.google.com/recaptcha/api.js'></script>
		<?php } ?>
	</head>
  
	<body id="login">
		
		<div id="login-wrapper" class="png_bg">
			<div id="login-top">
			
				<h3 style="color: #fff;"><?=$settings['value']?></h3>
				<br>
				<br>
			</div>
			
			<div id="login-content">
				
				<?php if ($error_msg) { ?>
				<div class="notification error png_bg">
                        <div>
                              <?=$error_msg?>
                         </div>
                 </div>
				<?php } ?>
               

				<form method="POST" action="login.php">
					<br>
					<p>
						<label>Email</label>
						<input class="text-input" type="text" name="username" />
					</p>
					<div class="clear"></div>
					<p>
						<label>Password</label>
						<input class="text-input" type="password" name="password" />
					</p>					<div class="clear"></div>					
					
					<?php if(ENVIRONMENT === 'PRODUCTION'){ ?>
					<div class="g-recaptcha" data-sitekey="6Lc6NAkUAAAAAK3PH-gqxAGGDnWXwk2uBCS8-ujN"></div>					
					<?php } ?>
					
					<div class="clear"></div>					
					<p id="remember-password">
						<input type="checkbox"  name="remember"/>Remember me
					</p>
					<div class="clear"></div>
					<p>
						<input class="button" type="submit" value="Log in" name="submit"/>
					</p>			
				</form>
			
			</div> <!-- End #login-content -->
		
		</div> <!-- End #login-wrapper -->
		
  </body>
  
</html>
