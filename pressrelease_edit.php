<?php

require_once(dirname(__FILE__) . '/inc/core.php');
require_once(dirname(__FILE__) . '/classes/class.table_form.php');

$table_name = 'pressrelease';

$action = sanitize($_GET['action']);
$id = sanitize($_GET['id']);
$form = new table_form($table_name, "form");

if($action == "edit" && $id) {
	$row = $form->get_row("Id",$id);
	$form->setValues($row);
}

$form->add_hidden('Id');
//$form->add_text('Person Name : ', 'person_name');
$form->add_text('Title : ', 'title');
//$form->add_text('Photo : ', 'UserId');
$form->add_text('Source : ', 'source');
$form->add_text('Url : ', 'url');
$form->add_text('Date Posted : ', 'dateposted');


if($action == "edit" && $id) {
	$row = $form->get_row("Id",$id);
	$form->setValues($row);
}

$fields = array('title','source','url','dateposted');

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
		
		
    	//$_POST['dateposted'] = date('F d,Y');
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
			<a href=""><h2>Press Release</h2></a>
						
			<?php if ($action_msg) echo $action_msg; ?>
			<br>		
			<div class="clear"></div> <!-- End .clear -->
			
			<div class="content-box"><!-- Start Content Box -->
					
				<div class="content-box-header">
					
					<h3>View Press Releases Details</h3>
					
					<div class="clear"></div>
					
				</div> <!-- End .content-box-header -->
				
				<div class="content-box-content">
					
							<form action="" method="post">

								<fieldset> <!-- Set class to "column-left" or "column-right" on fieldsets to divide the form into columns -->

									<?php while ($field = $form->get_field()) {	?>
										<p>
											<span style="font-size:16px; font-weight:bold;"><?=$form->show_label($field)?></span>
											<?php
                                            if($field[name]== 'Product : '){
												$vas = "SELECT * FROM `product` WHERE id = ( SELECT ProductId FROM `order` WHERE id = ".$row['OrderId'].")";
												$re=mysql_query($vas);
												$we=mysql_fetch_assoc($re);
											?>
                                            <span style="font-size:14px"><?=$we['Description']?></span>
                                            <?php
											}else{
											?>
                                            <span style="font-size:14px"><?=$form->show_input($field)?></span>
                                            <?php
											}
											?>
										</p>
										<?php } ?>
									<p>
                                    	<!--<a href="testimonials.php" class="button">Back</a>-->
										<input class="button" type="submit"  name="submit" value="Submit" />
									</p>

								</fieldset>

								<div class="clear"></div><!-- End .clear -->

							</form>
					
				</div> <!-- End .content-box-content -->
				
			</div> <!-- End .content-box -->
			
<?php include("html/footer.php"); ?><link rel="stylesheet" href="<?php echo $base_url; ?>/css/jquery-ui.css"><script src="https://code.jquery.com/jquery-1.12.4.js"></script><script src="<?php echo $base_url; ?>/js/jquery-ui.js"></script><script>  $( function() {    $( "#dateposted" ).datepicker({		appendText: "(MM dd,yy)",		dateFormat: "MM dd,yy"	});  } );  </script>  