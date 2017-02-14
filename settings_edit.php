<?php

require_once(dirname(__FILE__) . '/inc/core.php');
require_once(dirname(__FILE__) . '/classes/class.table_form.php');

$db = new Database();

$general_settings = getSettings("general");
$image_settings = getSettings("image");
$email_settings = getSettings("email");

if (isset($_GET['saved']))
	$action_msg = show_notification('The settings were saved succesfully',"success",true);

if (isset($_POST['general_submit'])) {
	
	foreach($general_settings as $setting) {
					
		if ($_POST[$setting['setting']] == "" && $setting['html_type'] != 'checkbox')
			$errors .= $setting['name'] . ' is required <br>';
		
	}		

	if (!isset($_POST['cache']))
		$_POST['cache'] = 'no';
	else
		$_POST['cache'] = 'yes';
	
	if ($errors)
		$action_msg = show_notification($errors,"error",true);
	else {
		
		updateSettings($general_settings);
		header("Location: settings_edit.php?saved");
		
	}
}

if (isset($_POST['email_submit'])) {
		
	updateSettings($email_settings);
	header("Location: settings_edit.php?saved");
	
}


if (isset($_POST['image_submit'])) {
	
	foreach($image_settings as $setting) {
					
		if ($_POST[$setting['setting']] == "")
			$errors .= $setting['name'] . ' is required <br>';
		
	}		

	if ($errors)
		$action_msg = show_notification($errors,"error",true);
	else {
		updateSettings($image_settings);
		header("Location: settings_edit.php?saved");
	}
}
	
include(dirname(__FILE__) . '/html/header.php');
include(dirname(__FILE__) . '/html/menu.php');

?>				

<div id="main-content"> <!-- Main Content Section with everything -->
			
			<!-- Page Head -->
			<a href="<?=$_SERVER['PHP_SELF']?>"><h2>Settings</h2></a>
						
			<?php if ($action_msg) echo $action_msg; ?>
			<br>		
			<div class="clear"></div> <!-- End .clear -->
			
			<div class="content-box"><!-- Start Content Box -->
					
				<div class="content-box-header">
					
					<h3>General Settings</h3>
					
					<div class="clear"></div>
					
				</div> <!-- End .content-box-header -->
				
				<div class="content-box-content">
					
							<form action="settings_edit.php" method="post">

								<fieldset> <!-- Set class to "column-left" or "column-right" on fieldsets to divide the form into columns -->

									<?php foreach ($general_settings as $setting): ?>
											<p>
												<h4><?php echo $setting['name']?></h4>
												
												<?php if ($setting['html_type'] == 'text'): ?>
													<input type="text" name="<?php echo $setting['setting']; ?>" class="text-input small-input" value="<?php echo $setting['value'] ?>">
												<?php endif; ?>
												<?php if ($setting['html_type'] == 'checkbox'): ?>
													<input type="checkbox" name="<?php echo $setting['setting']; ?>" <?php if ($setting['value'] == 'yes') echo 'checked'; ?>>
												<?php endif; ?>
												
											</p>
									<?php endforeach;?>
									
									<br>
									<p>
										<input class="button" type="submit"  name="general_submit" value="Submit" />
									</p>

								</fieldset>

								<div class="clear"></div><!-- End .clear -->

							</form>
					
				</div> <!-- End .content-box-content -->
				
			</div> <!-- End .content-box -->

				<div class="content-box <?php if (!isset($_GET['saved'])) echo 'closed-box'; ?>"><!-- Start Content Box -->
					
					<div class="content-box-header">

						<h3>Theme Settings</h3>

						<div class="clear"></div>

					</div> <!-- End .content-box-header -->

					<div class="content-box-content">

									<fieldset> <!-- Set class to "column-left" or "column-right" on fieldsets to divide the form into columns -->
										<div id="style-switcher" style="display: inline;"> 
												<h4>Click on image to change theme style</h4>
												<br>
																			<div style="background-color: #459300 ;width:30px;height:30px;margin-right: 10px;float: left;" id="green" onClick="javascript:changeTheme('green');">
																			</div >	
																			<div class="bla" style="background-color: #950000 ;width:30px;height:30px;margin-right: 10px;float: left;" id="red" onClick="javascript:changeTheme('red');">
																			</div>
																			<div class="bla"  style="background-color: #004994;width:30px;height:30px;margin-right: 10px;float: left;" id="blue" onClick="javascript:changeTheme('blue');">
																			</div>
										 </div>
										<div class="clear"></div><!-- End .clear -->
										<br><br>
										<div id="theme_msg" style="display:none;">
											<?php echo show_notification('Theme was saved succesfully',"success",true); ?>
										</div>
								</fieldset>

									<div class="clear"></div><!-- End .clear -->
					</div> <!-- End .content-box-content -->
				</div> <!-- End .content-box -->

				<div class="content-box <?php if (!isset($_GET['saved'])) echo 'closed-box'; ?>"><!-- Start Content Box -->
					
					<div class="content-box-header">

						<h3>E-mail Settings</h3>

						<div class="clear"></div>

					</div> <!-- End .content-box-header -->

					<div class="content-box-content">
								
								<form action="settings_edit.php" method="post">
									<fieldset> <!-- Set class to "column-left" or "column-right" on fieldsets to divide the form into columns -->
										
										<?php foreach ($email_settings as $setting): ?>
										<p>
											<h4><?php echo $setting['name']; ?></h4>
											<input type="text" name="<?php echo $setting['setting']; ?>" class="text-input small-input" value="<?php echo $setting['value']; ?>">
										<?php endforeach;?>
										</p>
										<br>
										<p>
											<input class="button" type="submit"  name="email_submit" value="Submit" />
										</p>
									</fieldset>
									
								</form>

									<div class="clear"></div><!-- End .clear -->
					</div> <!-- End .content-box-content -->
				</div> <!-- End .content-box -->
			
<?php include("html/footer.php"); ?>