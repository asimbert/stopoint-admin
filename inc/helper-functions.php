<?php
require_once('init/init.php');
require_once('PHPMailer-master/PHPMailerAutoload.php');

use Dompdf\Dompdf; 
function send_email($subject, $body, $to_email){
	$mail = new PHPMailer();
	$mail->IsSMTP();
	$mail->SMTPAuth = true;
	$mail->SMTPDebug = 0;
	$mail->SMTPSecure = 'ssl';
	$mail->Host = "smtp.gmail.com";
	$mail->Port = 465; 
	$mail->Username = "stopoint@stopoint.com";  
	$mail->Password = EMAIL_CREDENTIAL;
	$mail->From = "info@stopoint.com";
	$mail->FromName = "STOPOINT";
	$mail->AddAddress($to_email);
	$mail->IsHTML(true);
	$mail->Subject = $subject;
	$mail->Body = $body;
	$mail->XMailer = ' ';
	
	$sent = $mail->Send();
}

function get_emailbody_check_payment($body_element_arr = array()){
	$body = file_get_contents('paid1.html');
	$body = str_replace('%name%', $body_element_arr['firstname'], $body);
	$body = str_replace('%product%', $body_element_arr['description'], $body);
	$body = str_replace('%amount%', $body_element_arr['amount'], $body);
	$body = str_replace('%expiration%',$body_element_arr['order_date'], $body);
	$body = str_replace('%trak%', $body_element_arr['tracking_code'], $body);
	$help = 'https://stopoint.com/help';
	$body = str_replace('%help%', $help, $body);
	
	return $body;
}

function get_emailbody_paypal_payment($body_element_arr = array()){
	$body = file_get_contents('paid.html');
	$body = str_replace('%name%', $body_element_arr['firstname'], $body);
	$body = str_replace('%product%', $body_element_arr['description'], $body);
	$body = str_replace('%amount%', $body_element_arr['amount'], $body);
	$body = str_replace('%expiration%',$body_element_arr['order_date'], $body);
	$body = str_replace('%trak%', $body_element_arr['tracking_code'], $body);
	$help = 'https://stopoint.com/help';
	$body = str_replace('%help%', $help, $body);
	
	return $body;
}

function create_check($check_element_arr = array()){
	//Initialize variables
	
	$checknumber = $check_element_arr['checknumber'];
	require_once('dompdf/autoload.inc.php');	 
	
	$head = get_head_to_create_check();
	$body = get_body_to_create_check($check_element_arr);
	
	// instantiate and use the dompdf class
	$dompdf = new Dompdf();
	$dompdf->loadHtml('
	<html>
	<head>'.
		$head
	.'</head>
	<body>'.
		$body			
	.'</body>
	</html>
	');


	// (Optional) Setup the paper size and orientation
	$dompdf->setPaper(array(0,0, 8.5 * 72, 11 * 72), 'portrait');

	// Render the HTML as PDF
	$dompdf->render();
	$output = $dompdf->output();
	$rab=rand("111111","999999");
	if(!file_exists("pdf/stopointfilesystemsxgdlj123-".$checknumber.".pdf")){
		file_put_contents("pdf/stopointfilesystemsxgdlj123-".$checknumber.".pdf", $output); 
	}
	
	return $body;
}

function create_temp_check($body, $temp_file_name){
	//Initialize variables
	
	require_once('dompdf/autoload.inc.php');	
	
	$head = get_head_to_create_check('page-break-after');
	
	// instantiate and use the dompdf class
	$dompdf = new Dompdf();
	$dompdf->loadHtml('
	<html>
	<head>'.
		$head
	.'</head>
	<body>'.
		$body			
	.'</body>
	</html>
	');


	// (Optional) Setup the paper size and orientation
	$dompdf->setPaper(array(0,0, 8.5 * 72, 11 * 72), 'portrait');

	// Render the HTML as PDF
	$dompdf->render();
	$output = $dompdf->output();
	
	if(!file_exists("pdf/stopointfilesystemsxgdlj123-".$temp_file_name.".pdf")){
		file_put_contents("pdf/stopointfilesystemsxgdlj123-".$temp_file_name.".pdf", $output);
	}
}

function get_head_to_create_check($page_break = ''){
	$head = '<style type="text/css">
	@font-face {
		font-family: "OCR A Extended";
		src: url(../OCRAEXT.TTF) format("truetype");
	}
	@font-face {
		font-family: "Lucida Grande";
		src: url(../Lucida Grande.ttf) format("truetype");
	}
	
	body{
		font-family:"Lucida Grande";
	}
	
	.dollar{
		font-weight:bold;
		font-size:20px;
	}';
	
	if($page_break != ""){
		$head .= '@page check {
		  size: A4 portrait;
		  margin: 2cm;
		}

		.checkPage {
		   page: check;
		   page-break-after: always;
		}';
	}
	
	
	$head .= '</style>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">';
	
	return $head;
}

function get_body_to_create_check($check_element_arr = array()){
	
	$amount = $check_element_arr['price'];
	$s_addressline1 = $check_element_arr['s_addressline1'];
	$s_addressline2 = $check_element_arr['s_addressline2'];
	$s_city = $check_element_arr['s_city'];
	$s_state = $check_element_arr['s_state'];
	$s_postalcode = $check_element_arr['s_postalcode'];
	$s_country = $check_element_arr['s_country'];
	$familyname = $check_element_arr['familyname'];
	$firstname = $check_element_arr['firstname'];
	$lastname = $check_element_arr['lastname'];
	$checknumber = $check_element_arr['checknumber'];
	
	$w = get_amount_in_words($amount);
	
	$address=$s_addressline1;
	if($s_addressline2!="")
	{
		$address=$address.'<br/>'.$s_addressline2;
	}
	
	$body = '<div class="checkPage">
		<table style="margin-left:-16;margin-top:-19">
			<tr>
				<td width="70">
					<img src="images/logo.png" width="90%" style="margin-top:-27">
				</td>
				<td width="180" style="font-size:14px; line-height:0.8;">
					<span style="font-size:16px;">STOPOINT INC</span><br/>
					<span style="font-size:15px;">1175 NE 125TH ST STE 211</span><br/>
					<span style="font-size:15px;">NORTH MIAMI, FL 33161</span><br/>
					<span style="font-size:15px;">(888) 246-4919</span>
				</td>
				<td width="33">
					<br/>
				</td>
				<td width="26" align="center">
					<img src="images/2.jpg" width="90%" style="margin-top:-25;">
				</td>
				<td width="130" style="font-size:13px; line-height:0.8;">
								<span>Wells Fargo</span> <br/>
								12700 BISCAYNE BLVD <br/>
								NORTH MIAMI, FL 33181<br/>
								(305) 507-0858
							
							<p align="right" style="font-size:10px;margin-top:-3px;">
								DATE
								</p>
					
				</td>
				<td width="113" colspan="2">			
				<p style="text-align: right; margin-top:-15; margin-bottom:23;  font-size:17px;">'.$checknumber.'</p>
				<p style="width:100%; border-bottom:2px solid grey; text-align:center; font-size:16px;">'.date('m/d/Y').'</p>
				</td>
			</tr>
			</table>
			<table style="margin-left:-15;margin-top:0">
			<tr>
				<td style="font-size:12px" width="53" align="left">
					PAY TO THE<br/>
					ORDER OF
				</td>
				<td width="405">
					<p style="padding:0 20;margin-top:8; border-bottom:2px solid grey; font-size:16px;">
									  '.$firstname.' '.$lastname.'</p>
				</td>
				<td>
					<span class="dollar">&#36;</span>
				</td>
				<td width="90">
					<p style="margin:0;width:100%;margin-top:8; border-bottom:2px solid grey; float:right; font-size:16px;">**'.$amount.'.00</p>
				</td>
			</tr>
			</table>
			<table style="margin-left:-15;margin-top:0">
			<tr>
				<td  width="525" colspan="4" style="border-bottom:2px solid grey; background:url(r.png); background-repeat:repeat-x;">
					<span style="margin:0;background:white;">'.ucfirst(strtolower($w)).' Dollars & no/100 </span>
				</td>
				<td style="font-size:12px;padding-bottom:-10px;">
				DOLLARS
				</td>
			</tr>
			</table>
			<table style="margin-top:-10;">
			<tr>
				<td width="53">
				</td>
				<td width="140" style="font-size:16px; line-height:0.98;">
					<br/>'.$firstname.' '.$lastname.'<br/>
					'.$address.'<br/>
					'.$s_city.' '.$s_state.' '.$s_postalcode.'<br/>
					'.$s_country.'<br/>
				</td>
				<td width="100">
				</td>
				<td colspan="3">
					<table style="margin-top:15">
					<tr>
						<td align="right" style="font-size:14px;" width="130" >
							VOID AFTER 90 DAYS
						</td>
						<td width="100">
						</td>
					</tr>
					<tr>
						<td colspan="2" >
							<img src="images/a.png" width="125" style="margin-top:-10;margin-bottom:-20; margin-left:110;">
							<p style="width:200; border-bottom:2px solid grey;  margin-left:50;"></p>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<p style="margin-left:110; font-size:9px;">AUTHORIZED SIGNATURE</p>
						</td>
					</tr>
					</table>
				</td>
			</td>
		</table>
		<table style="margin-top:5;">
			<tr>
				<td style="width:110px">
					&nbsp;
				</td>
				<td style="width:410px;font-size:25px; font-family:'."'MICR E13 B;'".'" >
					<span style="margin-top:-3px;font-family:'."'MICR E13 B;'".'">C</span>'.$checknumber.'<span style="font-family:'."'MICR E13 B;'".'">C</span>      <span style="font-family:'."'MICR E13 B;'".'">A</span>063107513<span style="font-family:'."'MICR E13 B;'".'">A</span> 3822204032<span style="font-family:'."'MICR E13 B;'".'">C</span> 
				</td>

			</tr>
		</table>
		<table style="font-size:14px; margin-left:-5; margin-top:15; line-height:0.95">
			<tr>
				<td>
					<br/>
				</td>
			</tr>
			<tr  style="font-size:16px;">
				<td colspan="5">
					STOPOINT INC
				</td>
				<td align="right">
					'.$checknumber.'
				</td>
			</tr>
			<tr style="font-size:13px;">
				<td width="90">
					Date: '.date("M/d/y").'<br/>
					<u>Account/Category</u><br/>
					'.$familyname.'
				</td>
				<td width="125">
					Vendor:'.$firstname.' '.$lastname.'<br/>
					<p align="center" style="margin:0"><u>Amount</u><br/>
					'.$amount.'.00</p>
				</td>
				<td width="110">
					Check Total: $'.$amount.'.00<br/>
					<p align="center" style="margin:0"><u>Memo</u><br/>
					'.$familyname.'</p>
				</td>
				<td width="105">
				</td>
				<td width="50">
					<u>Class/Job</u>
				</td>
				<td width="60">
				</td>
			</tr>
		</table>
		<br/>
		<br/>
		<br/>
		<br/>
		<br/>
		<br/>
		<br/>
		<br/>
		<br/>
		<br/>
		<br/>
		<br/>
		<br/>

		<table style="font-size:14px; margin-left:-5; margin-top:55; line-height:0.95">
			<tr  style="font-size:16px;">
				<td colspan="5">
					STOPOINT INC
				</td>
				<td align="right">
					'.$checknumber.'
				</td>
			</tr>
			<tr style="font-size:13px;">
				<td width="90">
					Date: '.date("M/d/y").'<br/>
					<u>Account/Category</u><br/>
					'.$familyname.'
				</td>
				<td width="125">
					Vendor:'.$firstname.' '.$lastname.'<br/>
					<p align="center" style="margin:0"><u>Amount</u><br/>
					'.$amount.'.00</p>
				</td>
				<td width="110">
					Check Total: $'.$amount.'.00<br/>
					<p align="center" style="margin:0"><u>Memo</u><br/>
					'.$familyname.'</p>
				</td>
				<td width="105">
				</td>
				<td width="50">
					<u>Class/Job</u>
				</td>
				<td width="60">
				</td>
			</tr>
		</table>
	</div>';

	return $body;
}

function get_amount_in_words($price){
	
	$nwords = array( "zero", "one", "two", "three", "four", "five", "six", "seven", "eight", "nine", "ten", "eleven", "twelve", "thirteen","fourteen", "fifteen", "sixteen", "seventeen", "eighteen","nineteen", "twenty", 30 => "thirty", 40 => "forty", 50 => "fifty", 60 => "sixty", 70 => "seventy", 80 => "eighty",90 => "ninety" );
	$w = '';
	
	$x=$price;
	$flag=1;
	if($x < 21)  
		$w .= $nwords[$x];
	else if($x < 100) {
		$w .= $nwords[10 * floor($x/10)];
		$r = fmod($x, 10);
		if($r > 0)
			$w .= ' '. $nwords[$r];
	} else if($x < 1000) {
		$w .= $nwords[floor($x/100)] .' hundred';
		$r = fmod($x, 100);
		if($r > 0)
			$w .= ' ';
		if($r<21)
			$w .= $nwords[$r];
		else {
			$w .= $nwords[10 * floor($r/10)];
			$r = fmod($r, 10);
			if($r > 0)
			$w .= ' '. $nwords[$r];
		}
	}

	return $w;
}

function get_condition_str($condition_id){
	$condition_name = null;
	
	switch ($condition_id) {		
		case 1:
			$condition_name = 'Fair';
			break;
		case 2:
			$condition_name = 'Good';
			break;
		case 3:
			$condition_name = 'Flawless';
			break;
		case 4:
			$condition_name = 'Broken(Yes)';
			break;
		case 5:
			$condition_name = 'Broken(No)';
			break;
		default:
			$condition_name = 'Unkown';
	}
	return $condition_name;
}

function get_order_status_str($status_id){
	$status_name = null;
	
	switch ($status_id) {		
		case 1:
			$status_name = 'Pending';
			break;
		case 2:
			$status_name = 'Received';
			break;
		case 3:
			$status_name = 'Adjusted Price';
			break;
		case 4:
			$status_name = 'Return Order';
			break;
		case 5:
			$status_name = 'Release Payment';
			break;
		case 6:
			$status_name = 'Pay Order';
			break;
		case 7:
			$status_name = 'Cancelled';
			break;
		case 8:
			$status_name = 'Expired Order';
			break;
		case 9:
			$status_name = 'Return Completed';
			break;
		case 10:
			$status_name = 'Activation Lock';
			break;
		case 11:
			$status_name = 'Installment payment';
			break;
		case 12:
			$status_name = 'IMEI Check';
			break;
		case 13:
			$status_name = 'Activation Lock Inspection';
			break;
		case 14:
			$status_name = 'Blacklisted';
			break;
		case 15:
			$status_name = 'Adjusted Price Inspection';
			break;
		case 16:
			$status_name = 'IMEI Inspection';
			break;
		case 17:
			$status_name = 'Recycle';
			break;
		default:
			$status_name = 'Unkown';
	}
	return $status_name;
}

function get_payment_method_str($pm_id){
	$pm_name = null;
	
	switch ($pm_id) {		
		case 1:
			$pm_name = 'PayPal';
			break;
		case 2:
			$pm_name = 'Check';
			break;
		default:
			$pm_name = 'Unkown';
	}
	return $pm_name;
}

//$environment = 'sandbox'; // or 'beta-sandbox' or 'live'.
function paypal_http_post($methodName_, $nvpStr_)
{
 $environment = '';

 // Set up your API credentials, PayPal end point, and API version.
 // How to obtain API credentials:
 // https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_NVPAPIBasics#id084E30I30RO
 $API_UserName = urlencode('payment_api1.stopoint.com');
 $API_Password = urlencode('SDTP5A79SS6VZNXL');
 $API_Signature = urlencode('A0Gqqqp8jRW2lmq0SQmWBK4-ldIEAGRnYTVJ6yGG77f7QwS.65nxua2f');
 $API_Endpoint = "https://api-3t.paypal.com/nvp";
 if("sandbox" === $environment || "beta-sandbox" === $environment)
 {
  $API_Endpoint = "https://api-3t.$environment.paypal.com/nvp";
 }
 $version = urlencode('51.0');

 // Set the curl parameters.
 $ch = curl_init();
 curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
 curl_setopt($ch, CURLOPT_VERBOSE, 1);

 // Turn off the server and peer verification (TrustManager Concept).
 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
 curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
 curl_setopt($ch, CURLOPT_POST, 1);

 // Set the API operation, version, and API signature in the request.

 $nvpreq = "METHOD=$methodName_&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr_";

 // Set the request as a POST FIELD for curl.
 curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq."&".$nvpStr_);

 // Get response from the server.
 $httpResponse = curl_exec($ch);

 if( !$httpResponse)
 {
  echo $methodName_ . ' failed: ' . curl_error($ch) . '(' . curl_errno($ch) .')';
 }

 // Extract the response details.
 $httpResponseAr = explode("&", $httpResponse);

 $httpParsedResponseAr = array();
 foreach ($httpResponseAr as $i => $value)
 {
  $tmpAr = explode("=", $value);
  if(sizeof($tmpAr) > 1)
  {
   $httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
  }
 }

 /*if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr))
 {
  exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
 }*/

 return $httpParsedResponseAr;
}

function add_orderlog($orderid, $orderstatus, $current_orderstatus){
	//$current_orderstatus = orderstatus_by_id($orderid);
	if($current_orderstatus == $orderstatus){
		return;
	}
	$dateadded = date('Y-m-d H:i:s');
	$addedby = $_SESSION['userid'];
	mysql_query("INSERT INTO orderlog (`orderid`,`orderstatus`,`dateadded`,`addedby`) VALUES('$orderid','$orderstatus','$dateadded','$addedby')") or die(mysql_error());
	
}

function orderstatus_by_id($orderid){
	$orderstatus = '';
	$sql = 'SELECT OrderStatus FROM `order` WHERE id=' . $orderid;
	$res = mysql_query($sql) or die(mysql_error());
	if($rows = mysql_fetch_assoc($res)){
		$orderstatus = $rows['OrderStatus'];
	}
	
	return $orderstatus;
}
?>