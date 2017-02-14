<?php
require_once(dirname(__FILE__) . '/inc/core.php');
include(dirname(__FILE__) . '/html/header.php');
include(dirname(__FILE__) . '/html/menu.php'); 
?>

				

		</div></div> <!-- End #sidebar -->

		

		<div id="main-content"> 
		
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.3.7/js/tether.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/css/bootstrap.min.css">

<form method="post" action="usps_address_verification.php" name="usps_address_verification" id="usps_address_verification">
  <div class="form-group">
    <label for="address_one">Name</label>
    <input type="text" class="form-control" id="payto" aria-describedby="payto" placeholder="Name" name="payto" class="">
    <small id="payto" class="form-text text-muted">Please enter valid address</small>
  </div>
  <div class="form-group">
    <label for="address_one">Address</label>
    <input type="text" class="form-control" id="address_one" aria-describedby="address_one" placeholder="Address" name="address_one">
    <small id="address_one" class="form-text text-muted">Please enter valid address</small>
  </div>
  <div class="form-group">
    <label for="city">City</label>
    <input type="text" class="form-control" id="city" aria-describedby="city" placeholder="City" name="city">
    <small id="city" class="form-text text-muted">Please enter valid city</small>
  </div>
  <div class="form-group">
    <label for="state">State</label>
    <input type="text" class="form-control" id="state" aria-describedby="state" placeholder="state" name="state">
    <small id="state" class="form-text text-muted">Please enter valid state</small>
  </div>
  <div class="form-group">
    <label for="zip">Zip</label>
    <input type="text" class="form-control" id="zip" aria-describedby="zip" placeholder="zip" name="zip">
    <small id="zip" class="form-text text-muted">Please enter valid zip</small>
  </div>
  <div class="form-group">
    <label for="phone">Phone</label>
    <input type="text" class="form-control" id="phone" aria-describedby="phone" placeholder="phone" name="phone">
    <small id="phone" class="form-text text-muted">Please enter valid phone</small>
  </div>

 <button type="submit" class="btn btn-primary" value="verify" name="verify" id="verify">Verify</button>
</form>
<?php
if($_POST['verify']<>''){
require_once('../USPSAddressVerify.php');
// Initiate and set the username provided from usps

$verify = new USPSAddressVerify('810YGTSO2254');
$add = $_POST['address_one'];
$city = $_POST['city'];
$state = $_POST['state'];
$zip = $_POST['zip'];
$name = $_POST['payto'];


$address = new USPSAddress;
$address->setFirmName($name);
$address->setApt('100');
$address->setAddress($add);
$address->setCity($city);
$address->setState($state);
$address->setZip5($zip);
$address->setZip4('');

// Add the address object to the address verify class
$verify->setTestMode(true);

$verify->addAddress($address);

// Perform the request and return result
$verify->verify();
$verify->getArrayResponse();

$verify->isError();
print_r($verify->getErrorMessage());
// See if it was successful



if($verify->isSuccess()) {$message= "address verified";}
else {$message= "invalid address";}

echo "<script>$(document).ready(function(){
             $('#myModal').modal('show')
          });</script>";
}
?>


<!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header"><img src="images/usps.png" />
        </div>
        <div class="modal-body">
          <p style="font-weight:bold; color:#4FAF22; text-transform:capitalize><?=$message?></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
  
</div>

<?php include("html/footer.php"); ?>