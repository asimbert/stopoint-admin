<?php



require_once(dirname(__FILE__) . '/inc/core.php');

require_once(dirname(__FILE__) . '/classes/class.table_form.php');



$table_name = 'user';



$action = sanitize($_GET['action']);

$id = sanitize($_GET['id']);

$form = new table_form($table_name, "form");



if($action == "edit" && $id) {

	$row = $form->get_row("id",$id);

	$form->setValues($row);

}



$form->add_hidden('Id');

//$form->add_text('Person Name : ', 'person_name');

$form->add_text('First Name : ', 'FirstName');

$form->add_text('Last_Name : ', 'LastName');

$form->add_text('Email : ', 'EmailAddress');

$form->add_text('Password : ', 'Password');

$form->add_dropdown('User Type','UserType',array(array('name'=>'SuperAdmin','value'=>'Super Admin'),array('name'=>'SubAdmin','value'=>'Sub Admin')));

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

	$row = $form->get_row("id",$id);

	$form->setValues($row);

}



$fields = array('FirstName','LastName','EmailAddress','Password','UserType');



if (isset($_POST['submit'])) {



	$url = url_page();	



	if (isset($_GET['p']))

		$p = $_GET['p'];

	else

		$p = 1;

		



	if ($id) {

        	$form->setPostFields($fields,'id', $id);

			

			

			if(isset($_POST['checkboxvar'])){

  if (is_array($_POST['checkboxvar'])) {

	  mysql_query("DELETE FROM `userroles` WHERE UserId=".$id) or die(mysql_error());

    foreach($_POST['checkboxvar'] as $value){

		mysql_query("INSERT INTO userroles (`UserId`,`RoleId`) VALUES('$id','$value')") or die(mysql_error());

	

    }

  }

	}

			

			

			

			

			header("Location: $url?saved&p=$p&sort=id&desc");

	}

	else {

		

		

    

		$form->setPostFields($fields,'id','',true);

	

	$vas = "SELECT MAX(id) as id from user WHERE UserType='Sub Admin'";



$re=mysql_query($vas);

$we=mysql_fetch_assoc($re);

$userid = $we['id'];



			if(isset($_POST['checkboxvar'])){

  if (is_array($_POST['checkboxvar'])) {

    foreach($_POST['checkboxvar'] as $value){

		

		mysql_query("INSERT INTO userroles (`UserId`,`RoleId`) VALUES('$userid','$value')") or die(mysql_error());

      

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

      data: {"q":""},

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

		if(this.value == 'Super Admin'){
		
		test('#secctioni').empty();
			
		}
  // alert(this.value);

  });

});

</script>

<?php

if($_GET['action'] == "edit"){

	?>

    <script type="text/javascript">

	test(document).ready(function() {

	

	  

	  	test.ajax({

      url: "get_roles.php",

      type: "POST",

      data: {"q":"<?php echo $_GET['id'] ?>"},

	  dataType: 'text',

 

      success: function(data){

		  

		  test("#secctioni").html(data);

		 // alert(data);

		            

      },

      error:function(){

		  alert('error');

      }   

		

    }); 

	  

  // alert(this.value);

  

});

	</script>

    <?php

}

 ?>

<div id="main-content"> <!-- Main Content Section with everything -->

			

			<!-- Page Head -->

			<a href="<?=$_SERVER['PHP_SELF']?>"><h2>Users</h2></a>

						

			<?php if ($action_msg) echo $action_msg; ?>

			<br>		

			<div class="clear"></div> <!-- End .clear -->

			

			<div class="content-box"><!-- Start Content Box -->

					

				<div class="content-box-header">

					

					<h3><?php echo getTitle($action); ?> Users</h3>

					

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

                                         <?php if($field['id']=='UserType'){ ?> <div id="secctioni"></div> <?php } ?>

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