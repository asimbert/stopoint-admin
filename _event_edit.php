<?php

require_once(dirname(__FILE__) . '/inc/core.php');
require_once(dirname(__FILE__) . '/classes/class.table_form.php');

$table_name = 'event';

$action = sanitize($_GET['action']);
$id = sanitize($_GET['id']);
$form = new table_form($table_name, "form");

if($action == "edit" && $id) {
	$row = $form->get_row("id",$id);
	$form->setValues($row);
}

$form->add_hidden('id');
//$form->add_text('Person Name : ', 'person_name');
$form->add_text('Name : ', 'name');
$form->add_select('Artist','artist_id','artist','id','name','name');
$form->add_select('Venue','venue_id','venue','id','name','name');
$form->add_dropdown('Event Type','type',array(array('name'=>'Ticketing System','value'=>'Ticketing System'),array('name'=>'General Entry','value'=>'General Entry')));
$form->add_textarea('Description : ', 'description',$row['description']);
$form->add_text('Date : ', 'date');


//$form->add_text('Company : ', 'company');
//$form->add_select('Category','category_id','categories','id','name','name');
//$form->add_dropdown('State/Province : ','state',$states);
//$form->add_image('Photo','photo_testimonial');	
//$form->add_textarea('Content : ', 'content',$row['content']);
//$form->add_hidden('photo');

if($action == "edit" && $id) {
	$row = $form->get_row("id",$id);
	$form->setValues($row);
}

$fields = array('name','artist_id','venue_id','description','date','type','auditorium_id');

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
			$form->setPostFields($fields,'id','',true);
			header("Location: $url?added&sort=id&desc");	
	}
}
	
include(dirname(__FILE__) . '/html/header.php');
include(dirname(__FILE__) . '/html/menu.php');

?>
<script src="js/jquery.js"></script>
<script src="js/jquery-ui.js"></script>	
<link href="js/jquery-ui.css" rel="stylesheet">
  <script>
  $(function() {
	$('#date').datetimepicker({
		dateFormat: "yy-mm-dd",
	timeFormat: 'HH:mm:ss',
	stepHour: 2,
	stepMinute: 10,
	stepSecond: 10
});
});
  </script>
   			
<script type="text/javascript">
$(document).ready(function() {
	$( "#venue_id" )
  .change(function () {
	  
	  	$.ajax({
      url: "get_auditoriums.php",
      type: "POST",
      data: {"q":this.value},
	  dataType: 'text',
 
      success: function(data){
		  
		  $("#secctioni").html(data);
		 // alert(data);
		            
      },
      error:function(){
		  alert('error');
      }   
    }); 
	
  // alert(this.value);
  })
	
	<?php if ($row['photo']): $row['photo'] = strip_tags($row['photo']); ?>
		$('#photo_testimonial').after('<img src="<?php echo $base_url; ?>/images/testimonials/<?php echo $row[photo]; ?>" border="0">');
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
			<a href="<?=$_SERVER['PHP_SELF']?>"><h2>Events</h2></a>
						
			<?php if ($action_msg) echo $action_msg; ?>
			<br>		
			<div class="clear"></div> <!-- End .clear -->
			
			<div class="content-box"><!-- Start Content Box -->
					
				<div class="content-box-header">
					
					<h3><?php echo getTitle($action); ?> Events</h3>
					
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
                                        <?php if($field['id']=='venue_id'){ ?> <div id="secctioni"></div> <?php } ?>
											<?php } ?>
                                            
									<p>
										<input class="button" type="submit"  name="submit" value="Submit" />
									</p>

								</fieldset>

								<div class="clear"></div><!-- End .clear -->

							</form>
					
				</div> <!-- End .content-box-content -->
				
			</div> <!-- End .content-box -->
			
<?php include("html/footer.php"); ?>