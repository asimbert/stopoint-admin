<?php

require_once(dirname(__FILE__) . '/inc/core.php');
require_once(dirname(__FILE__) . '/classes/class.table_form.php');
require_once(dirname(__FILE__) . '/classes/class.table.php');

$table_name = 'messages';

$action = sanitize($_GET['action']);
$id = sanitize($_GET['id']);
$form = new table_form($table_name, "form");
$form1 = new table_form($table_name, "form");

if($action == "edit" && $id) {
	$row = $form->get_row("Id",$id);
	$form->setValues($row);
}

$form->add_hidden('Id');
$form->add_readonly('Name : ', 'FromId');
//$form->add_readonly('Email : ', 'Email');
$form->add_readonly('Date : ', 'Date');
$form->add_readonly('Subject : ', 'Subject');
$form->add_readonly('Message : ', 'Comments');

$form1->add_textarea('Message : ', 'form_msg');

$vasuser = "SELECT * FROM `user` WHERE id = ".$row['FromId'];
$reuser=mysql_query($vasuser);
$weuser=mysql_fetch_assoc($reuser);

//$form->add_text('Password : ', 'Password');
//$form->add_dropdown('User Type','UserType',array(array('name'=>'SuperAdmin','value'=>'Super Admin'),array('name'=>'SubAdmin','value'=>'Sub Admin')));
//$form->add_text('Date of Birth : ', 'dob');
/*$form->add_text('Address 1 : ', 'S_AddressLine1');
$form->add_text('Address 2 : ', 'S_AddressLine2');
$form->add_text('City : ', 'S_City');
$form->add_text('State : ', 'S_State');
$form->add_text('Postal Code : ', 'S_PostalCode');
$form->add_text('Country : ', 'S_Country');
$form->add_text('Phone : ', 'Phone');
$form->add_text('Paypal Email : ', 'PaypalEmail');
$form->add_dropdown('Is Newsletter','IsNewsletter',array(array('name'=>'Subscribed','value'=>'1'),array('name'=>'Not Subscribed','value'=>'0')));
$form->add_dropdown('Payment Method','PaymentMethod',array(array('name'=>'Paypal','value'=>'1'),array('name'=>'check','value'=>'2')));*/
//$form->add_dropdown('Active','isactive',array(array('name'=>'Yes','value'=>'Yes'),array('name'=>'No','value'=>'No')));


//$form->add_text('Company : ', 'company');
//$form->add_select('Category','category_id','categories','id','name','name');
//$form->add_dropdown('State/Province : ','state',$states);
//$form->add_image('Photo','photo_testimonial');	
//$form->add_textarea('Content : ', 'content',$row['content']);
//$form->add_hidden('photo');

if($action == "edit" && $id) {
	$row = $form->get_row("Id",$id);
	$form->setValues($row);
}

$fields = array('Name','Email','Subject','Comments');

if (isset($_POST['submit'])) {

	$url = url_page();	

	if (isset($_GET['p']))
		$p = $_GET['p'];
	else
		$p = 1;
		
	if ($id) {
        	$form->setPostFields($fields,'id', $id);
			header("Location: $url?saved&p=$p&sort=id&desc");
	}
	else {
		
		
    
		$form->setPostFields($fields,'Id','',true);
			
			if(isset($_POST['checkboxvar'])){
  if (is_array($_POST['checkboxvar'])) {
    foreach($_POST['checkboxvar'] as $value){
		
		mysql_query("INSERT INTO userroles (`UserId`,`RoleId`) VALUES('$name','$value')") or die(mysql_error());
      
    }
  }
	}
	
			header("Location: $url?added&sort=id&desc");	
	}
}

if (isset($_POST['submit_reply'])) {
	if($_POST){
	$fromid = $_POST['fromid'];
	$toid = $_POST['toid'];
	$orderid = $_POST['orderid'];
    $name = $_POST['form_name'];
    $email = $_POST['form_email'];
	$subject = $_POST['form_subject'];
    $message_body = $_POST['form_msg'];
	
	if($message_body!=''){
		//echo "Hello"; exit;
		mysql_query("INSERT INTO messages (`FromId`,`ToId`,`OrderId`,`Subject`,`Comments`,`IsRead`) VALUES('$toid','$fromid','$orderid','$subject','$message_body',1)") or die(mysql_error());
	}
	
	$to = $email;
	$email_from = "support@stopoint.com";
	$subject = $subject;
	$headers = "From: STOPOINT <".$email_from.">\r\n";
	//$headers .= "Reply-To: ". strip_tags($to) . "\r\n";
	//$headers .= "CC: susan@example.com\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	
	$message = '<html><body>';
	$message .= '<h4>Dear, '.$name.'!</h4>';
	$message .= '<p>'.$message_body.'</p><br>';
	$message .= '<p>Thanks</p>';
	$message .= '<p>From: STOPOINT</p>';
	$message .= '</body></html>';
 
 	$sent = mail($to, $subject, $message, $headers);
	if($sent){
		$action_msg = show_notification("Email successfully sent","success",true); 
		}
 
 	else{
		$action_msg = show_notification("Email is not sent, there is some error!","error",true); 
		}
}
}
	
include(dirname(__FILE__) . '/html/header.php');
include(dirname(__FILE__) . '/html/menu.php');

?>
<script src="js/jquery.js"></script>
<script src="js/jquery-ui.js"></script>	
<link href="js/jquery-ui.css" rel="stylesheet">
  		
<script type="text/javascript">
var test=$.noConflict();

test(document).ready(function() {
	test( "#UserType" )
  .change(function () {
	  if(this.value == 'Sub Admin'){
	  	test.ajax({
      url: "get_roles.php",
      type: "POST",
      data: {"q":this.value},
	  dataType: 'text',
 
      success: function(data){
		  
		  test("#secctioni").html(data);
		 // alert(data);
		            
      },
      error:function(){
		  alert('error');
      }   
		
    }); 
	  }
  // alert(this.value);
  });
});
</script>
<div id="main-content"> <!-- Main Content Section with everything -->
			
			<!-- Page Head -->
			<a href=""><h2>Messages</h2></a>
						
			<?php if ($action_msg) echo $action_msg; ?>
			<br>		
			<div class="clear"></div> <!-- End .clear -->
			
			<div class="content-box"><!-- Start Content Box -->
					
				<div class="content-box-header">
					
					<h3>View Inbox Details</h3>
					
					<div class="clear"></div>
					
				</div> <!-- End .content-box-header -->
				
				<div class="content-box-content">
					
							<form action="" method="post">

								<fieldset> <!-- Set class to "column-left" or "column-right" on fieldsets to divide the form into columns -->

									<?php while ($field = $form->get_field()) {	?>
										<p>
											<label style="font-size:14px; font-weight:bold"><?=$form->show_label($field)?></label>
                                            <?php
											//print_r($field);
											if($field[name]== 'Name : '){
											?>
                                            <span style="font-size:14px"><?=$weuser['FirstName'].' '.$weuser['LastName']?></span>
                                            <?php
											}else{
											?>
                                            <span style="font-size:14px"><?=$form->show_input($field)?></span>
                                            <?php
											}
											?>
										</p>
										<?php } ?>
									<!--<p>
                                    	<a href="manage_requests.php" class="button">Back</a>
										<input class="button" type="submit"  name="submit" value="Submit" />
									</p>-->

								</fieldset>

								<div class="clear"></div><!-- End .clear -->

							</form>
                            <script>
								$(document).ready(function(){
    								$("button").click(function(){
        								$(".reply").toggle();
    								});
								});
							</script>
                            <button class="button">Reply</button>
							<div class="reply" style="display:none;">
                            <form name="form1" id="reply" method="post" action="" style="border: 1px solid #ccc; padding:15px; margin-top:10px;">
                            
                               <input type="hidden" class="hidden" name="fromid" id="fromid" value="<?=$row['FromId']?>"  />
                               <input type="hidden" class="hidden" name="toid" id="toid" value="<?=$row['ToId']?>"  />
                               <input type="hidden" class="hidden" name="orderid" id="orderid" value="<?=$row['OrderId']?>"  />
                               <label style="display: block; padding: 10px 0px 10px; font-weight: bold; font-size: 14px;" for="form_name">Name :</label>
                               <input readonly="readonly" class="text-input" style="width:25%" name="form_name" id="form_name" type="text" value="<?=$weuser['FirstName'].' '.$weuser['LastName']?>" >
                            
                              <label style="display: block; padding: 10px 0px 10px; font-weight: bold; font-size: 14px;" for="form_email">Email :</label>
                              <input readonly="readonly" class="text-input" style="width:25%" name="form_email" id="form_email" type="email" value="<?=$weuser['EmailAddress']?>" >
                              <label style="display: block; padding: 10px 0px 10px; font-weight: bold; font-size: 14px;" for="form_subject">Subject :</label>
                              <input class="text-input" style="width:37%" name="form_subject" id="form_subject" type="text" value="RE:<?=$row['Subject']?>" >
                            <?php while ($field = $form1->get_field()) {	?>
								<p>
									<label style="font-size:14px; font-weight:bold"><?=$form1->show_label($field)?></label>
                                    <span style="font-size:14px"><?=$form1->show_input($field)?></span>
								</p>
							<?php } ?>  
                            <input id="submit" class="button" name="submit_reply" type="submit" value="Send">
							</form>
							</div>
                            
				</div> <!-- End .content-box-content -->
				
			</div> <!-- End .content-box -->
			
<?php include("html/footer.php"); ?>