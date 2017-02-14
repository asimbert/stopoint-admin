<?php

 require("/home/leaderse/public_html/helper/class.phpmailer.php");

function send_text_message($message_to,$message_from_email,$message_from_name,$subject,$text_file_name,$replace_tags_array)
{
  $text_message = file_get_contents($text_file_name);

  if (count($replace_tags_array))
  {
   $text_message = strtr($text_message,$replace_tags_array);
  }  
  

  //$success = mail($message_to, $subject, $body, $headers);

  $mail = new PHPMailer();

  //$mail->IsSMTP();                                   // send via SMTP
  $mail->IsSendmail();

  //$mail->Host     = "chrisleader.com"; // SMTP servers

  $mail->From     = $message_from_email;
  $mail->FromName = $message_from_name;
  $mail->AddReplyTo($message_from_email,$message_from_name);
  $mail->AddAddress($message_to);
  //$mail->WordWrap = 50;
  $mail->IsHTML(false);

  $mail->Subject  =  $subject;
  $mail->Body     =  $text_message;

  if(!$mail->Send())
  {
    //echo "Mailer Error: " . $mail->ErrorInfo;
    return false;

  }
  return true;


  return $success;
}



function send_html_message($message_to,$message_from_email,$message_from_name,$subject,$message_body,$replace_tags_array)
{


  $html_message = $message_body;  //file_get_contents($html_file_name);
  $text_message = strip_tags(str_replace('<br>',"\r\n",$html_message));

  if (count($replace_tags_array))
  {
   $html_message = strtr($html_message,$replace_tags_array);
   $text_message = strtr($text_message,$replace_tags_array);
  }


  $mail = new PHPMailer();

  //$mail->IsSMTP();                                   // send via SMTP
  $mail->IsSendmail();

  //$mail->Host     = "chrisleader.com"; // SMTP servers

  $mail->From     = $message_from_email;
  $mail->FromName = $message_from_name;
  $mail->AddReplyTo($message_from_email,$message_from_name);
  $mail->AddAddress($message_to);
  $mail->WordWrap = 50;                              // set word wrap
  $mail->IsHTML(true);                               // send as HTML

  $mail->Subject  =  $subject;
  $mail->Body     =  $html_message;
  $mail->AltBody  =  $text_message;

  if(!$mail->Send())
  {
    //echo "Mailer Error: " . $mail->ErrorInfo;
    return false;

  }
  return true;

}

?>
