<?php

require_once(dirname(__FILE__) . '/inc/core.php');
require_once(dirname(__FILE__) . '/classes/class.table_form.php');
if(isset($_POST['newsletter'])){
	$sqlo="SELECT * FROM `newsletter`";
	$we=mysql_query($sqlo);
	$re=mysql_fetch_assoc($we);
	$re['content']=strip_tags($re['content']);
	$headers = 'From: Monaco@info.com' . "\r\n" .
    'Reply-To: Monaco@info.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
// In case any of our lines are larger than 70 characters, we should use wordwrap()
$message = "Subject : $re[title]\n\nMessage : $re[content]";

// Send
$sqloq="SELECT * FROM site_users where subscribe_for_newsletter='Yes'";
	$weq=mysql_query($sqloq);
	while( $req=mysql_fetch_assoc($weq)){
		$email=$req['email'];
mail($email, 'Monaco Property', $message,$headers);
	}
//exit;
	}

$_GET['action']='edit';
$_GET['id']=1;
$table_name = 'newsletter';

$action = sanitize($_GET['action']);
$id = sanitize($_GET['id']);
$form = new table_form($table_name, "form");

if($action == "edit" && $id) {
	$row = $form->get_row("id",$id);
	$form->setValues($row);
}

$form->add_hidden('id');

$form->add_text('Title : ', 'title');

$form->add_textarea('Content : ', 'content',$row['content']);


if($action == "edit" && $id) {
	$row = $form->get_row("id",$id);
	$form->setValues($row);
}

$fields = array('title','content');

if (isset($_POST['submit'])) {

	$url = url_page();	
	
	$ur=explode('newsletter',$url);
	 $url=$ur[0].'newsletter_edit.php';

	if (isset($_GET['p']))
		$p = $_GET['p'];
	else
		$p = 1;
		
	if ($id) {
        	$form->setPostFields($fields,'id', $id);
			header("Location: $url?saved&p=$p&sort=id&desc");
	}
	else {
			$form->setPostFields($fields,'id','',true);
			header("Location: $url?added&sort=id&desc");	
	}
}
	
include(dirname(__FILE__) . '/html/header.php');
include(dirname(__FILE__) . '/html/menu.php');

?>			
<script type="text/javascript">
$(document).ready(function() {
	
	<?php if ($row['photo']): $row['photo'] = strip_tags($row['photo']); ?>
		$('#photo_testimonial').after('<img src="/images/testimonials/<?php echo $row[photo]; ?>" border="0">');
	<?php endif; ?>
	
	$("#photo_testimonial").uploadify({
		'uploader'       : '/uploadify.swf',
		'script'         : '/uploadify.php?page=testimonials',
		'cancelImg'      : 'images/cancel.png',
		'auto'           : true,
		'multi'          : true,

		'onComplete'	 : function(event,queueID,fileObj,response) {

			$('#photo').val(response);
			$('#photo_testimonial').after('<p><img src="/images/testimonials/'+response+'" border="0"></p>');
			
			return true;

		 }
	});
});
</script>
<div id="main-content"> <!-- Main Content Section with everything -->
			
			<!-- Page Head -->
			<a href="<?=$_SERVER['PHP_SELF']?>"><h2>NewsLetter</h2></a>
						
			<?php if ($action_msg) echo $action_msg; ?>
			<br>		
			<div class="clear"></div> <!-- End .clear -->
			
			<div class="content-box"><!-- Start Content Box -->
					
				<div class="content-box-header">
					
					<h3><?php echo getTitle($action); ?> NewsLetter</h3>
					
					<div class="clear"></div>
					
				</div> <!-- End .content-box-header -->
				
				<div class="content-box-content">
					
							<form action="" method="post">

								<fieldset> <!-- Set class to "column-left" or "column-right" on fieldsets to divide the form into columns -->

									<?php while ($field = $form->get_field()) {	?>
										<p>
											<label><?=$form->show_label($field)?></label>
											<?=$form->show_input($field)?>
										</p>
											<?php } ?>
									<p>
										<input class="button" type="submit"  name="submit" value="Submit" /></fieldset></form>
                                        <form  action="" method="post">
                                        <input class="button" type="submit"  name="newsletter" value="Send Newsletter" onclick="return confirm('Are you sure you want Send Newsletter to all Subscribed Users?');"/>
                                        </form>
									</p>

								

								<div class="clear"></div><!-- End .clear -->

							
					
				</div> <!-- End .content-box-content -->
				
			</div> <!-- End .content-box -->
			
<?php include("html/footer.php"); ?>