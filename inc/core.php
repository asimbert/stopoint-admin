<?php
/*	
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
	ini_set('memory_limit','2000M');

	ini_set('max_execution',0);

	

	ob_start();

	session_start();

	include(dirname(__FILE__) . '/../classes/class.database.php');

	include(dirname(__FILE__) . '/functions.php');
	include(dirname(__FILE__) . '/helper-functions.php');

define("EMAIL_CREDENTIAL", "Svxgdlj1234!!#");	

	$db = new Database();

	

	$title = getSetting('backend_title');

	$site_url = getSetting('site_url');

	

	

	if(isset($_POST['exportcsv'])){

		$today = date("m/d/Y");   

header('Content-Type: text/csv; charset=utf-8');

header('Content-Disposition: attachment; filename='.$today.'_orders.csv');

$output = fopen('php://output', 'w');

fputcsv($output, array('OrderId','TrackingCode','FedexCode','OrderNumber','OrderDate','OrderReceived','OrderAdjusted','OrderReturned','OrderReleased','OrderPaid','OrderCancelled','OrderAmount','AdjustedAmount','UserName','UserEmail','ShippingAddress','ShippingCity','ShippingState','ShippingCountry','ShippingPostalCode','BillingAddress','BillingCity','BillingState','BillingCountry','BillingPostalCode','UserPhone','UserPaypalEmail','BrandName','FamilyName','ProductModel','ProductCode','ProductCondition','ProductDescription','PaymentMethod','ProductSerial','OrderStatus'));

if($_GET['key'] == 'pending'){



if($_SESSION['datelimit'] != ""){

 $date_limit  = date('Y-m-d', strtotime($_SESSION['datelimit']));

}

if($_SESSION['datelimitto'] !=""){

 $date_limitto = date('Y-m-d', strtotime($_SESSION['datelimitto']));

}

$query = "SELECT order.id as OrderId, order.TrackingCode as TrackingCode, order.FedexCode as FedexCode, product.OrderNumber as OrderNumber, DATE_FORMAT(order.OrderDate, '%m/%d/%Y %H:%i') as OrderDate, DATE_FORMAT(orderstatushistory.datereceived, '%m/%d/%Y %H:%i') as OrderReceived,DATE_FORMAT(orderstatushistory.dateadjusted, '%m/%d/%Y %H:%i') as OrderAdjusted,DATE_FORMAT(orderstatushistory.datereturned, '%m/%d/%Y %H:%i') as OrderReturned,DATE_FORMAT(orderstatushistory.datereleased, '%m/%d/%Y %H:%i') as OrderReleased,DATE_FORMAT(orderstatushistory.datepaid, '%m/%d/%Y %H:%i') as OrderPaid,DATE_FORMAT(orderstatushistory.datecancelled, '%m/%d/%Y %H:%i') as OrderCancelled, order.OrderAmount as OrderAmount,order.AdjustedAmount as AdjustedAmount,concat(user.FirstName, ' ',user.LastName) as UserName, user.EmailAddress as UserEmail, concat(user.S_AddressLine1, ' ' ,user.S_AddressLine2) as ShippingAddress, user.S_City as ShippingCity, user.S_State as ShippingState,user.S_Country as ShippingCountry, user.S_PostalCode as ShippingPostalCode, concat(user.B_AddressLine1, ' ' ,user.B_AddressLine2) as BillingAddress, user.B_City as BillingCity, user.B_State as BillingState,user.B_Country as BillingCountry, user.B_PostalCode as BillingPostalCode, user.Phone as UserPhone, user.PaypalEmail as UserPaypalEmail ,productbrand.Name as BrandName,productfamily.Name as FamilyName, product.ProductModel as ProductModel , product.ProductCode as ProductCode,



 CASE order.Condition 

  WHEN '3' THEN 'Flawless'

  WHEN '2' THEN 'Good'

  WHEN '1' THEN 'Fair'

 ELSE 'Pending'

 END as ProductCondition,

 

 product.Description as ProductDescription,

  CASE user.PaymentMethod 

  WHEN '2' THEN 'Check'

  WHEN '1' THEN 'Paypal'

 ELSE 'Paypal'

 END as PaymentMethod,

 order.ProductSerial as ProductSerial,

 CASE order.OrderStatus 

  WHEN '6' THEN 'Paid'

  WHEN '5' THEN 'Release Payment'

  WHEN '3' THEN 'Adjusted Price'

   WHEN '2' THEN 'Received'

  WHEN '1' THEN 'Pending'

 ELSE 'Pending'

 END as OrderStatus

FROM `order` INNER JOIN `product` ON product.ProductCode=order.ProductId INNER JOIN `orderstatushistory` ON orderstatushistory.orderid=order.id INNER JOIN `user` ON user.id=order.UserId INNER JOIN `productbrand` ON productbrand.id=product.BrandId INNER JOIN `productfamily` ON productfamily.Id=product.FamilyId INNER JOIN `productcategory` ON productcategory.id=product.CategoryId WHERE order.OrderStatus = 1";



if($date_limit != "" && $date_limitto != ""){

$query = $query." AND `order`.`OrderDate` BETWEEN  str_to_date('$date_limit','%Y-%m-%d') AND str_to_date('$date_limitto','%Y-%m-%d') ORDER BY order.OrderDate desc";

}



else if($date_limit != "" && $date_limitto == ""){

$query = $query." AND `order`.`OrderDate` LIKE  '%$date_limit%' ORDER BY order.OrderDate desc";

}



else if($date_limitto != "" && $date_limit == ""){

$query = $query." AND `order`.`OrderDate` LIKE  '%$date_limitto%' ORDER BY order.OrderDate desc";

}



else {

	$query = $query." ORDER BY order.OrderDate desc";

	}


mysql_query('SET SQL_BIG_SELECTS=1') or die(mysql_error());
$rows = mysql_query($query);	

	}

	

else if($_GET['key'] == 'received'){



if($_SESSION['datelimit'] != ""){

 $date_limit  = date('Y-m-d', strtotime($_SESSION['datelimit']));

}

if($_SESSION['datelimitto'] !=""){

 $date_limitto = date('Y-m-d', strtotime($_SESSION['datelimitto']));

}



$query = "SELECT order.id as OrderId, order.TrackingCode as TrackingCode, order.FedexCode as FedexCode, product.OrderNumber as OrderNumber,DATE_FORMAT(order.OrderDate, '%m/%d/%Y %H:%i') as OrderDate,DATE_FORMAT(orderstatushistory.datereceived, '%m/%d/%Y %H:%i') as OrderReceived,DATE_FORMAT(orderstatushistory.dateadjusted, '%m/%d/%Y %H:%i') as OrderAdjusted,DATE_FORMAT(orderstatushistory.datereturned, '%m/%d/%Y %H:%i') as OrderReturned,DATE_FORMAT(orderstatushistory.datereleased, '%m/%d/%Y %H:%i') as OrderReleased,DATE_FORMAT(orderstatushistory.datepaid, '%m/%d/%Y %H:%i') as OrderPaid,DATE_FORMAT(orderstatushistory.datecancelled, '%m/%d/%Y %H:%i') as OrderCancelled, order.OrderAmount as OrderAmount,order.AdjustedAmount as AdjustedAmount,concat(user.FirstName, ' ',user.LastName) as UserName, user.EmailAddress as UserEmail, concat(user.S_AddressLine1, ' ' ,user.S_AddressLine2) as ShippingAddress, user.S_City as ShippingCity, user.S_State as ShippingState,user.S_Country as ShippingCountry, user.S_PostalCode as ShippingPostalCode, concat(user.B_AddressLine1, ' ' ,user.B_AddressLine2) as BillingAddress, user.B_City as BillingCity, user.B_State as BillingState,user.B_Country as BillingCountry, user.B_PostalCode as BillingPostalCode, user.Phone as UserPhone, user.PaypalEmail as UserPaypalEmail ,productbrand.Name as BrandName,productfamily.Name as FamilyName, product.ProductModel as ProductModel , product.ProductCode as ProductCode,

 CASE order.Condition 

  WHEN '3' THEN 'Flawless'

  WHEN '2' THEN 'Good'

  WHEN '1' THEN 'Fair'

 ELSE 'Pending'

 END as ProductCondition,

 product.Description as ProductDescription,

  CASE user.PaymentMethod 

  WHEN '2' THEN 'Check'

  WHEN '1' THEN 'Paypal'

 ELSE 'Paypal'

 END as PaymentMethod,

 order.ProductSerial as ProductSerial,

 CASE order.OrderStatus 

  WHEN '6' THEN 'Paid'

  WHEN '5' THEN 'Release Payment'

  WHEN '3' THEN 'Adjusted Price'

   WHEN '2' THEN 'Received'

  WHEN '1' THEN 'Pending'

 ELSE 'Pending'

 END as OrderStatus

FROM `order` INNER JOIN `product` ON product.ProductCode=order.ProductId INNER JOIN `orderstatushistory` ON orderstatushistory.orderid=order.id INNER JOIN `user` ON user.id=order.UserId INNER JOIN `productbrand` ON productbrand.id=product.BrandId INNER JOIN `productfamily` ON productfamily.Id=product.FamilyId INNER JOIN `productcategory` ON productcategory.id=product.CategoryId WHERE order.OrderStatus = 2";



if($date_limit != "" && $date_limitto != ""){

$query = $query." AND `order`.`OrderDate` BETWEEN  str_to_date('$date_limit','%Y-%m-%d') AND str_to_date('$date_limitto','%Y-%m-%d') ORDER BY order.OrderDate desc";

}



else if($date_limit != "" && $date_limitto == ""){

$query = $query." AND `order`.`OrderDate` LIKE  '%$date_limit%' ORDER BY order.OrderDate desc";

}



else if($date_limitto != "" && $date_limit == ""){

$query = $query." AND `order`.`OrderDate` LIKE  '%$date_limitto%' ORDER BY order.OrderDate desc";

}



else {

	$query = $query." ORDER BY order.OrderDate desc";

	}


mysql_query('SET SQL_BIG_SELECTS=1') or die(mysql_error());
$rows = mysql_query($query);	



	}

	

else if($_GET['key'] == 'adjusted'){

	

	if($_SESSION['datelimit'] != ""){

 $date_limit  = date('Y-m-d', strtotime($_SESSION['datelimit']));

}

if($_SESSION['datelimitto'] !=""){

 $date_limitto = date('Y-m-d', strtotime($_SESSION['datelimitto']));

}



$query = "SELECT order.id as OrderId, order.TrackingCode as TrackingCode,order.FedexCode as FedexCode, product.OrderNumber as OrderNumber, DATE_FORMAT(order.OrderDate, '%m/%d/%Y %H:%i') as OrderDate,DATE_FORMAT(orderstatushistory.datereceived, '%m/%d/%Y %H:%i') as OrderReceived,DATE_FORMAT(orderstatushistory.dateadjusted, '%m/%d/%Y %H:%i') as OrderAdjusted,DATE_FORMAT(orderstatushistory.datereturned, '%m/%d/%Y %H:%i') as OrderReturned,DATE_FORMAT(orderstatushistory.datereleased, '%m/%d/%Y %H:%i') as OrderReleased,DATE_FORMAT(orderstatushistory.datepaid, '%m/%d/%Y %H:%i') as OrderPaid,DATE_FORMAT(orderstatushistory.datecancelled, '%m/%d/%Y %H:%i') as OrderCancelled, order.OrderAmount as OrderAmount,order.AdjustedAmount as AdjustedAmount,concat(user.FirstName, ' ',user.LastName) as UserName, user.EmailAddress as UserEmail, concat(user.S_AddressLine1, ' ' ,user.S_AddressLine2) as ShippingAddress, user.S_City as ShippingCity, user.S_State as ShippingState,user.S_Country as ShippingCountry, user.S_PostalCode as ShippingPostalCode, concat(user.B_AddressLine1, ' ' ,user.B_AddressLine2) as BillingAddress, user.B_City as BillingCity, user.B_State as BillingState,user.B_Country as BillingCountry, user.B_PostalCode as BillingPostalCode, user.Phone as UserPhone, user.PaypalEmail as UserPaypalEmail ,productbrand.Name as BrandName,productfamily.Name as FamilyName, product.ProductModel as ProductModel , product.ProductCode as ProductCode,

 CASE order.Condition 

  WHEN '3' THEN 'Flawless'

  WHEN '2' THEN 'Good'

  WHEN '1' THEN 'Fair'

 ELSE 'Pending'

 END as ProductCondition,

 product.Description as ProductDescription,

  CASE user.PaymentMethod 

  WHEN '2' THEN 'Check'

  WHEN '1' THEN 'Paypal'

 ELSE 'Paypal'

 END as PaymentMethod,

 order.ProductSerial as ProductSerial,

 CASE order.OrderStatus 

  WHEN '6' THEN 'Paid'

  WHEN '5' THEN 'Release Payment'

  WHEN '3' THEN 'Adjusted Price'

   WHEN '2' THEN 'Received'

  WHEN '1' THEN 'Pending'

 ELSE 'Pending'

 END as OrderStatus

FROM `order` INNER JOIN `product` ON product.ProductCode=order.ProductId INNER JOIN `orderstatushistory` ON orderstatushistory.orderid=order.id INNER JOIN `user` ON user.id=order.UserId INNER JOIN `productbrand` ON productbrand.id=product.BrandId INNER JOIN `productfamily` ON productfamily.Id=product.FamilyId INNER JOIN `productcategory` ON productcategory.id=product.CategoryId WHERE order.OrderStatus = 3";



if($date_limit != "" && $date_limitto != ""){

$query = $query." AND `order`.`OrderDate` BETWEEN  str_to_date('$date_limit','%Y-%m-%d') AND str_to_date('$date_limitto','%Y-%m-%d') ORDER BY order.OrderDate desc";

}



else if($date_limit != "" && $date_limitto == ""){

$query = $query." AND `order`.`OrderDate` LIKE  '%$date_limit%' ORDER BY order.OrderDate desc";

}



else if($date_limitto != "" && $date_limit == ""){

$query = $query." AND `order`.`OrderDate` LIKE  '%$date_limitto%' ORDER BY order.OrderDate desc";

}



else {

	$query = $query." ORDER BY order.OrderDate desc";

	}


mysql_query('SET SQL_BIG_SELECTS=1') or die(mysql_error());
$rows = mysql_query($query);



	

	}

	



else if($_GET['key'] == 'returned'){

if($_SESSION['datelimit'] != ""){

 $date_limit  = date('Y-m-d', strtotime($_SESSION['datelimit']));

}

if($_SESSION['datelimitto'] !=""){

 $date_limitto = date('Y-m-d', strtotime($_SESSION['datelimitto']));

}



$query = "SELECT order.id as OrderId, order.TrackingCode as TrackingCode,order.FedexCode as FedexCode, product.OrderNumber as OrderNumber,DATE_FORMAT(order.OrderDate, '%m/%d/%Y %H:%i') as OrderDate,DATE_FORMAT(orderstatushistory.datereceived, '%m/%d/%Y %H:%i') as OrderReceived,DATE_FORMAT(orderstatushistory.dateadjusted, '%m/%d/%Y %H:%i') as OrderAdjusted,DATE_FORMAT(orderstatushistory.datereturned, '%m/%d/%Y %H:%i') as OrderReturned,DATE_FORMAT(orderstatushistory.datereleased, '%m/%d/%Y %H:%i') as OrderReleased,DATE_FORMAT(orderstatushistory.datepaid, '%m/%d/%Y %H:%i') as OrderPaid,DATE_FORMAT(orderstatushistory.datecancelled, '%m/%d/%Y %H:%i') as OrderCancelled, order.OrderAmount as OrderAmount,order.AdjustedAmount as AdjustedAmount,concat(user.FirstName, ' ',user.LastName) as UserName, user.EmailAddress as UserEmail, concat(user.S_AddressLine1, ' ' ,user.S_AddressLine2) as ShippingAddress, user.S_City as ShippingCity, user.S_State as ShippingState,user.S_Country as ShippingCountry, user.S_PostalCode as ShippingPostalCode, concat(user.B_AddressLine1, ' ' ,user.B_AddressLine2) as BillingAddress, user.B_City as BillingCity, user.B_State as BillingState,user.B_Country as BillingCountry, user.B_PostalCode as BillingPostalCode, user.Phone as UserPhone, user.PaypalEmail as UserPaypalEmail ,productbrand.Name as BrandName,productfamily.Name as FamilyName, product.ProductModel as ProductModel , product.ProductCode as ProductCode,



 CASE order.Condition 

  WHEN '3' THEN 'Flawless'

  WHEN '2' THEN 'Good'

  WHEN '1' THEN 'Fair'

 ELSE 'Pending'

 END as ProductCondition,

 product.Description as ProductDescription,

  CASE user.PaymentMethod 

  WHEN '2' THEN 'Check'

  WHEN '1' THEN 'Paypal'

 ELSE 'Paypal'

 END as PaymentMethod,

 order.ProductSerial as ProductSerial,

 CASE order.OrderStatus 

  WHEN '6' THEN 'Paid'

  WHEN '5' THEN 'Release Payment'

  WHEN '3' THEN 'Adjusted Price'

   WHEN '2' THEN 'Received'

  WHEN '1' THEN 'Pending'

 ELSE 'Pending'

 END as OrderStatus

FROM `order` INNER JOIN `product` ON product.ProductCode=order.ProductId INNER JOIN `orderstatushistory` ON orderstatushistory.orderid=order.id INNER JOIN `user` ON user.id=order.UserId INNER JOIN `productbrand` ON productbrand.id=product.BrandId INNER JOIN `productfamily` ON productfamily.Id=product.FamilyId INNER JOIN `productcategory` ON productcategory.id=product.CategoryId WHERE order.OrderStatus = 4";



if($date_limit != "" && $date_limitto != ""){

$query = $query." AND `order`.`OrderDate` BETWEEN  str_to_date('$date_limit','%Y-%m-%d') AND str_to_date('$date_limitto','%Y-%m-%d') ORDER BY order.OrderDate desc";

}



else if($date_limit != "" && $date_limitto == ""){

$query = $query." AND `order`.`OrderDate` LIKE  '%$date_limit%' ORDER BY order.OrderDate desc";

}



else if($date_limitto != "" && $date_limit == ""){

$query = $query." AND `order`.`OrderDate` LIKE  '%$date_limitto%' ORDER BY order.OrderDate desc";

}



else {

	$query = $query." ORDER BY order.OrderDate desc";

	}

	
mysql_query('SET SQL_BIG_SELECTS=1') or die(mysql_error());
$rows = mysql_query($query);	





	}

	

else if($_GET['key'] == 'release'){

	

if($_SESSION['datelimit'] != ""){

 $date_limit  = date('Y-m-d', strtotime($_SESSION['datelimit']));

}

if($_SESSION['datelimitto'] !=""){

 $date_limitto = date('Y-m-d', strtotime($_SESSION['datelimitto']));

}



$query = "SELECT order.id as OrderId, order.TrackingCode as TrackingCode,order.FedexCode as FedexCode, product.OrderNumber as OrderNumber, DATE_FORMAT(order.OrderDate, '%m/%d/%Y %H:%i') as OrderDate,DATE_FORMAT(orderstatushistory.datereceived, '%m/%d/%Y %H:%i') as OrderReceived,DATE_FORMAT(orderstatushistory.dateadjusted, '%m/%d/%Y %H:%i') as OrderAdjusted,DATE_FORMAT(orderstatushistory.datereturned, '%m/%d/%Y %H:%i') as OrderReturned,DATE_FORMAT(orderstatushistory.datereleased, '%m/%d/%Y %H:%i') as OrderReleased,DATE_FORMAT(orderstatushistory.datepaid, '%m/%d/%Y %H:%i') as OrderPaid,DATE_FORMAT(orderstatushistory.datecancelled, '%m/%d/%Y %H:%i') as OrderCancelled, order.OrderAmount as OrderAmount,order.AdjustedAmount as AdjustedAmount,concat(user.FirstName, ' ',user.LastName) as UserName, user.EmailAddress as UserEmail, concat(user.S_AddressLine1, ' ' ,user.S_AddressLine2) as ShippingAddress, user.S_City as ShippingCity, user.S_State as ShippingState,user.S_Country as ShippingCountry, user.S_PostalCode as ShippingPostalCode, concat(user.B_AddressLine1, ' ' ,user.B_AddressLine2) as BillingAddress, user.B_City as BillingCity, user.B_State as BillingState,user.B_Country as BillingCountry, user.B_PostalCode as BillingPostalCode, user.Phone as UserPhone, user.PaypalEmail as UserPaypalEmail ,productbrand.Name as BrandName,productfamily.Name as FamilyName, product.ProductModel as ProductModel , product.ProductCode as ProductCode,



 CASE order.Condition 

  WHEN '3' THEN 'Flawless'

  WHEN '2' THEN 'Good'

  WHEN '1' THEN 'Fair'

 ELSE 'Pending'

 END as ProductCondition,

 product.Description as ProductDescription,

  CASE user.PaymentMethod 

  WHEN '2' THEN 'Check'

  WHEN '1' THEN 'Paypal'

 ELSE 'Paypal'

 END as PaymentMethod,

 order.ProductSerial as ProductSerial,



 CASE order.OrderStatus 

  WHEN '6' THEN 'Paid'

  WHEN '5' THEN 'Release Payment'

  WHEN '3' THEN 'Adjusted Price'

   WHEN '2' THEN 'Received'

  WHEN '1' THEN 'Pending'

 ELSE 'Pending'

 END as OrderStatus

FROM `order` INNER JOIN `product` ON product.ProductCode=order.ProductId INNER JOIN `orderstatushistory` ON orderstatushistory.orderid=order.id INNER JOIN `user` ON user.id=order.UserId INNER JOIN `productbrand` ON productbrand.id=product.BrandId INNER JOIN `productfamily` ON productfamily.Id=product.FamilyId INNER JOIN `productcategory` ON productcategory.id=product.CategoryId WHERE order.OrderStatus = 5";



if($date_limit != "" && $date_limitto != ""){

$query = $query." AND `order`.`OrderDate` BETWEEN  str_to_date('$date_limit','%Y-%m-%d') AND str_to_date('$date_limitto','%Y-%m-%d') ORDER BY order.OrderDate desc";

}



else if($date_limit != "" && $date_limitto == ""){

$query = $query." AND `order`.`OrderDate` LIKE  '%$date_limit%' ORDER BY order.OrderDate desc";

}



else if($date_limitto != "" && $date_limit == ""){

$query = $query." AND `order`.`OrderDate` LIKE  '%$date_limitto%' ORDER BY order.OrderDate desc";

}



else {

	$query = $query." ORDER BY order.OrderDate desc";

	}

	
mysql_query('SET SQL_BIG_SELECTS=1') or die(mysql_error());
	$rows = mysql_query($query);	





	}



else if($_GET['key'] == 'paid'){

if($_SESSION['datelimit'] != ""){

 $date_limit  = date('Y-m-d', strtotime($_SESSION['datelimit']));

}

if($_SESSION['datelimitto'] !=""){

 $date_limitto = date('Y-m-d', strtotime($_SESSION['datelimitto']));

}

$query = "SELECT order.id as OrderId, order.TrackingCode as TrackingCode,order.FedexCode as FedexCode, product.OrderNumber as OrderNumber, DATE_FORMAT(order.OrderDate, '%m/%d/%Y %H:%i') as OrderDate,DATE_FORMAT(orderstatushistory.datereceived, '%m/%d/%Y %H:%i') as OrderReceived,DATE_FORMAT(orderstatushistory.dateadjusted, '%m/%d/%Y %H:%i') as OrderAdjusted,DATE_FORMAT(orderstatushistory.datereturned, '%m/%d/%Y %H:%i') as OrderReturned,DATE_FORMAT(orderstatushistory.datereleased, '%m/%d/%Y %H:%i') as OrderReleased,DATE_FORMAT(orderstatushistory.datepaid, '%m/%d/%Y %H:%i') as OrderPaid,DATE_FORMAT(orderstatushistory.datecancelled, '%m/%d/%Y %H:%i') as OrderCancelled, order.OrderAmount as OrderAmount,order.AdjustedAmount as AdjustedAmount,concat(user.FirstName, ' ',user.LastName) as UserName, user.EmailAddress as UserEmail, concat(user.S_AddressLine1, ' ' ,user.S_AddressLine2) as ShippingAddress, user.S_City as ShippingCity, user.S_State as ShippingState,user.S_Country as ShippingCountry, user.S_PostalCode as ShippingPostalCode, concat(user.B_AddressLine1, ' ' ,user.B_AddressLine2) as BillingAddress, user.B_City as BillingCity, user.B_State as BillingState,user.B_Country as BillingCountry, user.B_PostalCode as BillingPostalCode, user.Phone as UserPhone, user.PaypalEmail as UserPaypalEmail ,productbrand.Name as BrandName,productfamily.Name as FamilyName, product.ProductModel as ProductModel , product.ProductCode as ProductCode,



 CASE order.Condition 

  WHEN '3' THEN 'Flawless'

  WHEN '2' THEN 'Good'

  WHEN '1' THEN 'Fair'

 ELSE 'Pending'

 END as ProductCondition,

 product.Description as ProductDescription,

  CASE user.PaymentMethod 

  WHEN '2' THEN 'Check'

  WHEN '1' THEN 'Paypal'

 ELSE 'Paypal'

 END as PaymentMethod,

 order.ProductSerial as ProductSerial,

 CASE order.OrderStatus 

  WHEN '6' THEN 'Paid'

  WHEN '5' THEN 'Release Payment'

  WHEN '3' THEN 'Adjusted Price'

   WHEN '2' THEN 'Received'

  WHEN '1' THEN 'Pending'

 ELSE 'Pending'

 END as OrderStatus

FROM `order` INNER JOIN `product` ON product.ProductCode=order.ProductId INNER JOIN `orderstatushistory` ON orderstatushistory.orderid=order.id INNER JOIN `user` ON user.id=order.UserId INNER JOIN `productbrand` ON productbrand.id=product.BrandId INNER JOIN `productfamily` ON productfamily.Id=product.FamilyId INNER JOIN `productcategory` ON productcategory.id=product.CategoryId WHERE order.OrderStatus = 6";



if($date_limit != "" && $date_limitto != ""){

$query = $query." AND `order`.`OrderDate` BETWEEN  str_to_date('$date_limit','%Y-%m-%d') AND str_to_date('$date_limitto','%Y-%m-%d') ORDER BY order.OrderDate desc";

}



else if($date_limit != "" && $date_limitto == ""){

$query = $query." AND `order`.`OrderDate` LIKE  '%$date_limit%' ORDER BY order.OrderDate desc";

}



else if($date_limitto != "" && $date_limit == ""){

$query = $query." AND `order`.`OrderDate` LIKE  '%$date_limitto%' ORDER BY order.OrderDate desc";

}



else {

	$query = $query." ORDER BY order.OrderDate desc";

	}
mysql_query('SET SQL_BIG_SELECTS=1') or die(mysql_error());
$rows = mysql_query($query);	





	}

else{

	if($_SESSION['datelimit'] != ""){

 $date_limit  = date('Y-m-d', strtotime($_SESSION['datelimit']));

}

if($_SESSION['datelimitto'] !=""){

 $date_limitto = date('Y-m-d', strtotime($_SESSION['datelimitto']));

}

 $query = "SELECT order.id as OrderId, order.TrackingCode as TrackingCode,order.FedexCode as FedexCode, product.OrderNumber as OrderNumber, DATE_FORMAT(order.OrderDate, '%m/%d/%Y %H:%i') as OrderDate,DATE_FORMAT(orderstatushistory.datereceived, '%m/%d/%Y %H:%i') as OrderReceived,DATE_FORMAT(orderstatushistory.dateadjusted, '%m/%d/%Y %H:%i') as OrderAdjusted,DATE_FORMAT(orderstatushistory.datereturned, '%m/%d/%Y %H:%i') as OrderReturned,DATE_FORMAT(orderstatushistory.datereleased, '%m/%d/%Y %H:%i') as OrderReleased,DATE_FORMAT(orderstatushistory.datepaid, '%m/%d/%Y %H:%i') as OrderPaid,DATE_FORMAT(orderstatushistory.datecancelled, '%m/%d/%Y %H:%i') as OrderCancelled, order.OrderAmount as OrderAmount ,order.AdjustedAmount as AdjustedAmount,concat(user.FirstName, ' ',user.LastName) as UserName, user.EmailAddress as UserEmail, concat(user.S_AddressLine1, ' ' ,user.S_AddressLine2) as ShippingAddress, user.S_City as ShippingCity, user.S_State as ShippingState,user.S_Country as ShippingCountry, user.S_PostalCode as ShippingPostalCode, concat(user.B_AddressLine1, ' ' ,user.B_AddressLine2) as BillingAddress, user.B_City as BillingCity, user.B_State as BillingState,user.B_Country as BillingCountry, user.B_PostalCode as BillingPostalCode, user.Phone as UserPhone, user.PaypalEmail as UserPaypalEmail ,productbrand.Name as BrandName,productfamily.Name as FamilyName, product.ProductModel as ProductModel , product.ProductCode as ProductCode,



 CASE order.Condition 

  WHEN '3' THEN 'Flawless'

  WHEN '2' THEN 'Good'

  WHEN '1' THEN 'Fair'

 ELSE 'Pending'

 END as ProductCondition,

 product.Description as ProductDescription,

  CASE user.PaymentMethod 

  WHEN '2' THEN 'Check'

  WHEN '1' THEN 'Paypal'

 ELSE 'Paypal'

 END as PaymentMethod,

 order.ProductSerial as ProductSerial,

 CASE order.OrderStatus 

  WHEN '6' THEN 'Paid'

  WHEN '5' THEN 'Release Payment'

  WHEN '3' THEN 'Adjusted Price'

  WHEN '2' THEN 'Received'

  WHEN '1' THEN 'Pending'

 ELSE 'Pending'

 END as OrderStatus

 

FROM `order` INNER JOIN `product` ON product.ProductCode=order.ProductId INNER JOIN `orderstatushistory` ON orderstatushistory.orderid=order.id INNER JOIN `user` ON user.id=order.UserId INNER JOIN `productbrand` ON productbrand.id=product.BrandId INNER JOIN `productfamily` ON productfamily.Id=product.FamilyId INNER JOIN `productcategory` ON productcategory.id=product.CategoryId";



if($date_limit != "" && $date_limitto != ""){

$query = $query." WHERE `order`.`OrderDate` BETWEEN  str_to_date('$date_limit','%Y-%m-%d') AND str_to_date('$date_limitto','%Y-%m-%d') ORDER BY order.OrderDate desc";

}



else if($date_limit != "" && $date_limitto == ""){

$query = $query." WHERE `order`.`OrderDate` LIKE  '%$date_limit%' ORDER BY order.OrderDate desc";

}



else if($date_limitto != "" && $date_limit == ""){

$query = $query." WHERE `order`.`OrderDate` LIKE  '%$date_limitto%' ORDER BY order.OrderDate desc";

}



else{

	$query = $query." ORDER BY order.OrderDate desc";

	}

	
mysql_query('SET SQL_BIG_SELECTS=1') or die(mysql_error());
$rows = mysql_query($query);



}



unset($_SESSION['datelimit']);

unset($_SESSION['datelimitto']);

// loop over the rows, outputting them

while ($row = mysql_fetch_assoc($rows)) fputcsv($output, $row);

	exit();

	}

	

	if (isset($_COOKIE['autologin'])) {



		// check the cookie

		$cookie = mysql_real_escape_string($_COOKIE['autologin']);



		$result = $db->select("SELECT username FROM users WHERE cookie='$cookie'");



		if ($result['username'] != "")

			$_SESSION['logged_in'] = true;



	}





	if (!isset($_SESSION['logged_in'])) {



	  	header('Location: https://stopointsxgdlj123sxgdlj123.stopoint.com/login.php');

	   	die();

	} 

	

	

?> 



