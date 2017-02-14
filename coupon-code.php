<?php
include "header.php";
?>

<!-- slider -->
<div class="container">
    <div class="row text-center">
    	<h1 class="sub-heading" style="  color: #44b749;">Coupon Code</h1>
    </div><!-- row --> 
   
	
	<?php
mysql_connect("localhost","stopoint_usr432","axvRN~NATFnB");
mysql_select_db("stopoint_db1");
$query = mysql_query("SELECT * FROM coupon_code ");
while($rows = mysql_fetch_array($query))
{

      $copon_code = $rows['copon_code'];
       $Description = $rows['Description'];
       $Expiration_date = $rows['Expiration_date'];
      
       

?>
  
  <div class="bs-example">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Coupon Code</th>
                <th>Description</th>
                <th>Expiration Date</th>
              
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php  echo $copon_code; ?></td>
                <td><?php echo $Description;?></td>
                <td><?php echo $Expiration_date;?></td>
                
            </tr>
            
        </tbody>
    </table>
</div>
<?php}
   ?>

		

</div><!-- end container --> 
<!-- end slider -->
<br>
<?php
include "footer.php";
?>