<?php

if (!isset($_SESSION['logged_in'])){
	header("Location: login.php");
}

require_once(dirname(__FILE__) . '/inc/core.php');
require_once(dirname(__FILE__) . '/classes/class.table_form.php');

$user_id = $_SESSION['userid'];
$resource_name = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);
$role_id = 0;

if($resource_name === 'index.php'){
	$role_id = 0;
	
}else if($resource_name === 'coupon-add.php' || $resource_name === 'coupon-add'){
	$role_id = 48;
	
}else if($resource_name === 'coupon-manage.php' || $resource_name === 'coupon-manage'){
	$role_id = 49;
	
}else if($resource_name === 'product.php' || $resource_name === 'product'){
	$cat_id = $_REQUEST['catid'];
	if($cat_id == 1){	//Cellphone
		$role_id = 26;
	}else if($cat_id == 2){		//Computers
		$role_id = 27;
	}else if($cat_id == 3){		//Tablets
		$role_id = 28;
	}else if($cat_id == 4){		//TV
		$role_id = 29;
	}else if($cat_id == 5){		//Watches
		$role_id = 30;
	}else if($cat_id == 6){		//Gadgets
		$role_id = 31;
	}
	
	
}else if($resource_name === 'order.php' || $resource_name === 'order'){
	$key = $_REQUEST['key'];
	if($key === 'all'){
		$role_id = 14;
	}else if($key === 'pending'){
		$role_id = 15;
	}else if($key === 'received'){
		$role_id = 16;
	}else if($key === 'adjusted'){
		$role_id = 17;
	}else if($key === 'returned'){
		$role_id = 18;
	}else if($key === 'release'){
		$role_id = 19;
	}else if($key === 'paid'){
		$role_id = 20;
	}else if($key === 'cancelled'){
		$role_id = 21;
	}else if($key === 'expired'){
		$role_id = 22;
	}else if($key === 'returncompleted'){
		$role_id = 42;
	}else if($key === 'activation'){
		$role_id = 43;
	}else if($key === 'imei'){
		$role_id = 54;
	}else if($key === 'installment'){
		$role_id = 55;
	}else if($key === 'activation-lock'){
		$role_id = 56;
	}else if($key === 'blacklisted'){
		$role_id = 57;
	}
	
}else if($resource_name === 'ordertransaction.php' || $resource_name === 'ordertransaction'){
	$role_id = 23;
	
}else if($resource_name === 'manage_requests.php' || $resource_name === 'manage_requests'){
	$role_id = 34;
	
}else if($resource_name === 'sitepages.php' || $resource_name === 'sitepages'){
	$role_id = 32;
	
}else if($resource_name === 'testimonials.php' || $resource_name === 'testimonials'){
	$role_id = 35;
	
}else if($resource_name === 'import.php' || $resource_name === 'import'){
	$role_id = 39;
	
}else if($resource_name === 'email_subscriber.php' || $resource_name === 'email_subscriber'){
	$role_id = 33;
	
}else if($resource_name === 'users_edit.php' || $resource_name === 'users_edit'){		//Add new admin
	$role_id = 24;
	
}else if($resource_name === 'users.php' || $resource_name === 'users'){		//View admin
	$role_id = 25;
	
}else if($resource_name === 'inbox.php' || $resource_name === 'inbox'){
	$role_id = 36;
	
}else if($resource_name === 'outbox.php' || $resource_name === 'outbox'){
	$role_id = 37;
	
}else if($resource_name === 'settings.php' || $resource_name === 'settings'){
	$role_id = 41;
	
}else if($resource_name === 'pressrelease.php' || $resource_name === 'pressrelease'){
	$role_id = 52;
	
}else if($resource_name === 'coupon_code.php' || $resource_name === 'coupon_code'){
	$role_id = 53;
	
}else if($resource_name === 'customers.php' || $resource_name === 'customers'){	//View Users
	$role_id = 60;
	
}else if($resource_name === 'paid-order.php' || $resource_name === 'paid-order'){
	$payment_method = isset($_REQUEST['payment_method']) && $_REQUEST['payment_method'] !== ""?$_REQUEST['payment_method']:2;
	if($payment_method == 2){
		$role_id = 62;
	}else if($payment_method == 1){
		$role_id = 63;
	}
	
}else if($resource_name === 'bulk-check-payment.php' || $resource_name === 'bulk-check-payment'){
	$role_id = 62;
	
}else if($resource_name === 'bulk-paypal-payment.php' || $resource_name === 'bulk-paypal-payment'){
	$role_id = 63;
	
}

if($role_id > 0){
	$coupon=mysql_query("SELECT * FROM `userroles` WHERE UserId=$user_id AND RoleId = $role_id") or die(mysql_error());

	$length = mysql_num_rows($coupon);
	if($user_id != 1416 && $length == 0){
		header("Location: unauthorised-access.php");
	}	
}

?>