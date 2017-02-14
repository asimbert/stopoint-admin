<?php
if($_POST){
    $name = $_POST['form_name'];
    $email = $_POST['form_email'];
	$subject = $_POST['form_subject'];
    $message_body = $_POST['form_msg'];
    //mail("j.andrew.sears@gmail.com", "51 Deep comment from" .$email, $message);
	$to = $email;
	$email_from = "mqbaig1573@gmail.com";
	$subject = $subject;
	$headers = "From: " . strip_tags($email_from) . "\r\n";
	$headers .= "Reply-To: ". strip_tags($to) . "\r\n";
	//$headers .= "CC: susan@example.com\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	
	$message = '<html><body>';
	$message .= '<h3>Dear, '.$name.'!</h3>';
	$message .= '<p>'.$message_body.'</p><br>';
	$message .= '<p>From: STOPOINT</p>';
	$message .= '</body></html>';
 
 	$sent = mail($to, $subject, $message, $headers);
	if($sent){
		echo "email successfully sent"; }
 
 	else{
		echo "email is not sent, there is some error!"; }
 	//header("Location: manage_requests.php");
}
?>