<?php



require_once(dirname(__FILE__) . '/inc/core.php');

include(dirname(__FILE__) . '/html/header.php');

include(dirname(__FILE__) . '/html/menu.php'); 


echo $current_id= $_SESSION['userid'];

$vasuser = "SELECT  * FROM user WHERE id = ".$current_id;

$reuser=mysql_query($vasuser);
$weuser=mysql_fetch_assoc($reuser)


?>

				

		</div></div> <!-- End #sidebar -->

		

		<div id="main-content"> <!-- Main Content Section with everything -->

			

			<!-- Page Head -->

			<h2>Welcome <?=$weuser['FirstName'].' '.$weuser['LastName']?></h2>

			

			<!--<ul class="shortcut-buttons-set">--> <!-- Replace the icons URL's with your own -->

								

				<!--<li><a class="shortcut-button" href="users.php"><span>

					<img src="images/icons/pencil.png" alt="icon" /><br />

					Manage Admins

				</span></a></li>

				

				<li><a class="shortcut-button" href="product.php"><span>

					<img src="images/icons/pencil.png" alt="icon" /><br />

					Manage Products

				</span></a></li>	

				

				<li><a class="shortcut-button" href="order.php?key=all"><span>

					<img src="images/icons/pencil.png" alt="icon" /><br />

					Manage Orders

				</span></a></li>-->	

				

			<!--</ul>--><!-- End .shortcut-buttons-set -->

			

			<div class="clear"></div> <!-- End .clear -->

			

			<br>	

					

			<div class="clear"></div> <!-- End .clear -->

			

			

<?php include("html/footer.php"); ?>