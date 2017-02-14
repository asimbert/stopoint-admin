<?php

require_once(dirname(__FILE__) . '/inc/core.php');
require_once(dirname(__FILE__) . '/classes/class.table_form.php');

$table_name = 'event_category';

$action = sanitize($_GET['action']);
$id = sanitize($_GET['id']);
$form = new table_form($table_name, "form");

if($action == "edit" && $id) {
	$row = $form->get_row("id",$id);
	$form->setValues($row);
}

$form->add_hidden('id');
$form->add_text('Name : ', 'name');	

if($action == "edit" && $id) {
	$row = $form->get_row("id",$id);
	$form->setValues($row);
}

$fields = array('name');

if (isset($_POST['submit'])) {

	$url = url_page();	
				
	if ($id) {
        	$form->setPostFields($fields,'id', $id);
			header("Location: $url?saved");	
	}
	else {
			$form->setPostFields($fields,'id','',true);
			header("Location: $url?added");	
	}
}
	
include(dirname(__FILE__) . '/html/header.php');
include(dirname(__FILE__) . '/html/menu.php');

?>			
<div id="main-content"> <!-- Main Content Section with everything -->
			
			<!-- Page Head -->
			<a href="<?=$_SERVER['PHP_SELF']?>"><h2>Categories</h2></a>
						
			<?php if ($action_msg) echo $action_msg; ?>
			<br>		
			<div class="clear"></div> <!-- End .clear -->
			
			<div class="content-box"><!-- Start Content Box -->
					
				<div class="content-box-header">
					
					<h3><?php echo getTitle($action); ?> Categories</h3>
					
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
										<input class="button" type="submit"  name="submit" value="Submit" />
									</p>

								</fieldset>

								<div class="clear"></div><!-- End .clear -->

							</form>
					
				</div> <!-- End .content-box-content -->
				
			</div> <!-- End .content-box -->
			
<?php include("html/footer.php"); ?>