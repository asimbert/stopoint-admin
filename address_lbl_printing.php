<?php 
error_reporting(0);
ob_start();
session_start();

require_once('init/init.php');
?>

<style>
.alert-danger
{   color: #E10000;
    text-align: center;
    background-color: #FFD9D9;
    width: 48%;
    padding: 20px;
    margin: 0 auto;
	border: 1px solid #E10000;
	font-family: arial;
	}
</style>
<?php
if($_POST['simple_spc']<>'')
	{
		$form_company=$_POST['q3_companyName'];
		$from_first_name=$_POST['q4_name_first'];
		$from_last_name=$_POST['q4_name_last'];
		$from_addresss_one=$_POST['q5_address_addr_line1'];
		$from_address_two=$_POST['q5_address_addr_line2'];
		$from_city=$_POST['q5_address_city'];
		$from_state=$_POST['q5_address_state'];
		$from_zip_code=$_POST['q5_address_postal'];
		$from_phone=$_POST['q6_phoneNumber_phone'];
		
		$to_company=$_POST['q10_companyName10'];
		$to_first_name=$_POST['q11_name11_first'];
		$to_last_name=$_POST['q11_name11_last'];
		$to_address_one=$_POST['q12_address12_addr_line1'];
		$to_address_two=$_POST['q12_address12_addr_line2'];
		$to_state=$_POST['q12_address12_state'];
		$to_city=$_POST['q12_address12_city'];
		$to_zip_code=$_POST['q12_address12_postal'];
		$to_phone=$_POST['q13_phoneNumber13_phone'];
			
		$to_address=$to_address_one." ".$to_address_two;	
		$to_name=$to_first_name." ".$to_last_name;
		
		require_once('USPSAddressVerify.php');
		$verify = new USPSAddressVerify(USPS_ADDRESS_VERIFY_KEY);
		
		$address = new USPSAddress;
		$address->setFirmName($to_name);
		$address->setApt(USPS_ADDRESS_VERIFY_APT);
		$address->setAddress($to_address);
		$address->setCity($to_city);
		$address->setState($to_state);
		$address->setZip5($to_zip_code);
		$address->setZip4('');
	
		
		//$verify->setTestMode(true);
		$verify->addAddress($address);
		
		$verify->verify();
		$verify->getArrayResponse();
		
		$verify->isError();
		
		if($verify->isSuccess()) 
			{
				
		
					// From address verification
					$from_verify = new USPSAddressVerify(USPS_ADDRESS_VERIFY_KEY);
					$from_name=$from_first_name." ".$from_last_name;
					$f_address=$from_addresss_one." ".$from_address_two;
					
					$from_address = new USPSAddress;
					$from_address->setFirmName($from_name);
					$from_address->setApt(USPS_ADDRESS_VERIFY_APT);
					$from_address->setAddress($f_address);
					$from_address->setCity($from_city);
					$from_address->setState($from_state);
					$from_address->setZip5($from_zip_code);
					$from_address->setZip4('');
					
					//$verify->setTestMode(true);
					$from_verify->addAddress($from_address);
					
					$from_verify->verify();
					$from_verify->getArrayResponse();
					
					$from_verify->isError();
					
					
					if($from_verify->isSuccess()) 
					
						{
							$_SESSION['to_name']=$to_name;
							$_SESSION['to_address']=$to_address;
							$_SESSION['to_city']=$to_city;
							$_SESSION['to_state']=$to_state;
							$_SESSION['to_zip_code']=$to_zip_code;
							$_SESSION['to_phone']=$to_phone;
							$_SESSION['to_company']=$to_company;
							
							$_SESSION['from_name']=$from_name;
							$_SESSION['f_address']=$f_address;
							$_SESSION['from_city']=$from_city;
							$_SESSION['from_state']=$from_state;
							$_SESSION['from_zip_code']=$from_zip_code;
							$_SESSION['from_phone']=$from_phone;
							$_SESSION['form_company']=$form_company;
							
							$link=CONFIG_BASE_URL . "/address_lbl_generation.php?to_company=$to_company&to_name=$to_name&to_address=$to_address&to_city=$to_city&to_state=$to_state&to_zip_code=$to_zip_code&to_phone=$to_phone&from_name=$from_name&from_address=$f_address&from_city=$from_city&from_state=$from_state&from_zip_code=$from_zip_code&from_phone=$from_phone&form_company=$form_company";
							//echo "<a href='".$link."'>Download Now</a>";
						}
					else {?> <div class="alert-danger"><strong>Error! in From Address :</strong> <? print_r($from_verify->getErrorMessage()); $eflag=true;?></div><? }

			}
		else {?> <div class="alert-danger"><strong>Error! in To Address</strong> <? print_r($verify->getErrorMessage()); $eflag=true;?></div><? }
		
	}
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="alternate" type="application/json+oembed" href="https://www.jotform.com/oembed/?format=json&amp;url=http%3A%2F%2Fwww.jotform.com%2Fform%2F63113145415445" title="oEmbed Form"><link rel="alternate" type="text/xml+oembed" href="https://www.jotform.com/oembed/?format=xml&amp;url=http%3A%2F%2Fwww.jotform.com%2Fform%2F63113145415445" title="oEmbed Form">
<meta property="og:title" content="Label Printing" >
<meta property="og:url" content="https://www.jotform.me/form/63113145415445" >
<meta property="og:description" content="Please click the link to complete this form.">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<meta name="HandheldFriendly" content="true" />
<title>Label Printing</title>
<link href="https://cdn.jotfor.ms/static/formCss.css?3.3.15599" rel="stylesheet" type="text/css" />
<link type="text/css" rel="stylesheet" href="https://cdn.jotfor.ms/css/styles/nova.css?3.3.15599" />
<link type="text/css" media="print" rel="stylesheet" href="https://cdn.jotfor.ms/css/printForm.css?3.3.15599" />
<link type="text/css" rel="stylesheet" href="https://cdn.jotfor.ms/themes/CSS/566a91c2977cdfcd478b4567.css?"/>
<style type="text/css">
    .form-label-left{
        width:150px !important;
    }
    .form-line{
        padding-top:12px;
        padding-bottom:12px;
    }
    .form-label-right{
        width:150px !important;
    }
    body, html{
        margin:0;
        padding:0;
        background:false;
    }

    .form-all{
        margin:0px auto;
        padding-top:0px;
        width:690px;
        color:#555 !important;
        font-family:"Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", Verdana, sans-serif;
        font-size:14px;
    }
    .form-radio-item label, .form-checkbox-item label, .form-grading-label, .form-header{
        color: #555;
    }
	.form-line-error .form-error-message {display:none;}
</style>

<script src="https://cdn.jotfor.ms/static/prototype.forms.js" type="text/javascript"></script>
<script src="https://cdn.jotfor.ms/static/jotform.forms.js?3.3.15599" type="text/javascript"></script>
<script src="https://js.jotform.com/vendor/postMessage.js?3.3.15599" type="text/javascript"></script>
<script src="https://js.jotform.com/WidgetsServer.js?v=1478527048058" type="text/javascript"></script>
<script type="text/javascript">
   JotForm.init(function(){
	JotForm.clearFieldOnHide="disable";
	JotForm.onSubmissionError="jumpToFirstError";
   });
</script>
<style>
.form-header-group{border:none;}
</style>
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

<form class="jotform-form" action="<?=$_SERVER['PHP_SELF']?>" method="post" name="form_63113145415445" id="63113145415445" accept-charset="utf-8">
  <input type="hidden" name="formID" value="63113145415445" />
  <div class="form-all">
    <ul class="form-section page-section">
      <li id="cid_1" class="form-input-wide" data-type="control_head">
        <div class="form-header-group" style="border-bottom:1px solid #666666">
          <div class="header-text httal htvam">
            <h2 id="header_1" class="form-header">
              Label Printing
            </h2>
          </div>
        </div>
      </li>
      <li id="cid_8" class="form-input-wide" data-type="control_head">
        <div class="form-header-group">
          <div class="header-text httal htvam">
            <h2 id="header_8" class="form-header">
              From
            </h2>
          </div>
        </div>
      </li>

      <li class="form-line jf-required" data-type="control_textbox" id="id_3">
        <label class="form-label form-label-left form-label-auto" id="label_3" for="input_3">
          Company Name
          <span class="form-required">
            
          </span>
        </label>
        <div id="cid_3" class="form-input jf-required">
          <input type="text" class=" form-textbox" data-type="input-textbox" id="input_3" name="q3_companyName" size="20" value="<? if($_POST['q3_companyName']<>''){echo $_POST['q3_companyName'];}?>" />
        </div>
      </li>
      <li class="form-line jf-required" data-type="control_fullname" id="id_4">
        <label class="form-label form-label-left form-label-auto" id="label_4" for="input_4">
          Name
          <span class="form-required">
            *
          </span>
        </label>
        <div id="cid_4" class="form-input jf-required">
          <span class="form-sub-label-container" style="vertical-align: top;">
            <input class="form-textbox validate[required" type="text" size="10" name="q4_name_first" id="first_4" value="<? if($_POST['q4_name_first']<>''){echo $_POST['q4_name_first'];}?>" />
            <label class="form-sub-label" for="first_4" id="sublabel_first" style="min-height: 13px;"> First Name </label>
          </span>
          <span class="form-sub-label-container" style="vertical-align: top;">
            <input class="form-textbox validate[required" type="text" size="15" name="q4_name_last" id="last_4" value="<? if($_POST['q4_name_last']<>''){echo $_POST['q4_name_last'];}?>"/>
            <label class="form-sub-label" for="last_4" id="sublabel_last" style="min-height: 13px;"> Last Name </label>
          </span>
        </div>
      </li>
      <li class="form-line jf-required" data-type="control_address" id="id_5">
        <label class="form-label form-label-left form-label-auto" id="label_5" for="input_5">
          Address
          <span class="form-required">
            *
          </span>
        </label>
        <div id="cid_5" class="form-input jf-required">
          <table summary="" undefined class="form-address-table" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td colspan="2">
                <span class="form-sub-label-container" style="vertical-align: top;">
                  <input class="form-textbox validate[required] form-address-line" type="text" name="q5_address_addr_line1" id="input_5_addr_line1" value="<? if($_POST['q5_address_addr_line1']<>''){echo $_POST['q5_address_addr_line1'];}?>"/>
                  <label class="form-sub-label" for="input_5_addr_line1" id="sublabel_5_addr_line1" style="min-height: 13px;"> Street Address </label>
                </span>
              </td>
            </tr>
            <tr>
              <td colspan="2">
                <span class="form-sub-label-container" style="vertical-align: top;">
                  <input class="form-textbox form-address-line" type="text" name="q5_address_addr_line2" id="input_5_addr_line2" size="46" value="<? if($_POST['q5_address_addr_line2']<>''){echo $_POST['q5_address_addr_line2'];}?>"/>
                  <label class="form-sub-label" for="input_5_addr_line2" id="sublabel_5_addr_line2" style="min-height: 13px;"> Street Address Line 2 </label>
                </span>
              </td>
            </tr>
            <tr>
              <td width="50%">
                <span class="form-sub-label-container" style="vertical-align: top;">
                  <input class="form-textbox validate[required] form-address-city" type="text" name="q5_address_city" id="input_5_city" size="21" value="<? if($_POST['q5_address_city']<>''){echo $_POST['q5_address_city'];}?>"/>
                  <label class="form-sub-label" for="input_5_city" id="sublabel_5_city" style="min-height: 13px;"> City </label>
                </span>
              </td>
              <td>
                <span class="form-sub-label-container" style="vertical-align: top;">
                  <input class="form-textbox validate[required] form-address-state" type="text" name="q5_address_state" id="input_5_state" size="22" value="<? if($_POST['q5_address_state']<>''){echo $_POST['q5_address_state'];}?>"/>
                  <label class="form-sub-label" for="input_5_state" id="sublabel_5_state" style="min-height: 13px;"> State / Province </label>
                </span>
              </td>
            </tr>
            <tr>
              <td width="50%">
                <span class="form-sub-label-container" style="vertical-align: top;">
                  <input class="form-textbox validate[required] form-address-postal" type="text" name="q5_address_postal" id="input_5_postal" size="10" value="<? if($_POST['input_5_postal']<>''){echo $_POST['input_5_postal'];}?>"/>
                  <label class="form-sub-label" for="input_5_postal" id="sublabel_5_postal" style="min-height: 13px;"> Postal / Zip Code </label>
                </span>
              </td>
              <td>
                <span class="form-sub-label-container" style="vertical-align: top;">
                  <select class="form-dropdown validate[required] form-address-country" defaultcountry="" name="q5_address_country" id="input_5_country">
                    <option value="United States"> United States </option>                
                  </select>
                  <label class="form-sub-label" for="input_5_country" id="sublabel_5_country" style="min-height: 13px;"> Country </label>
                </span>
              </td>
            </tr>
          </table>
        </div>
      </li>
      <li class="form-line jf-required" data-type="control_phone" id="id_6">
        <label class="form-label form-label-left form-label-auto" id="label_6" for="input_6">
          Phone Number
          <span class="form-required">
            *
          </span>
        </label>
        <div id="cid_6" class="form-input jf-required">
          <span class="form-sub-label-container" style="vertical-align: top;">
            <input class="form-textbox validate[required" type="tel" name="q6_phoneNumber_phone" id="input_6_phone" size="8" value="<? if($_POST['q6_phoneNumber_phone']<>''){echo $_POST['q6_phoneNumber_phone'];}?>">
            <label class="form-sub-label" for="input_6_phone" id="sublabel_phone" style="min-height: 13px;"> Phone Number </label>
          </span>
        </div>
      </li>
      <li id="cid_9" class="form-input-wide" data-type="control_head">
        <div class="form-header-group">
          <div class="header-text httal htvam">
            <h2 id="header_9" class="form-header">
              To
            </h2>
          </div>
        </div>
      </li>
      <li class="form-line jf-required" data-type="control_textbox" id="id_10">
        <label class="form-label form-label-left form-label-auto" id="label_10" for="input_10">
          Company Name
          <span class="form-required">          
          </span>
        </label>
        <div id="cid_10" class="form-input jf-required">
          <input type="text" class=" form-textbox" data-type="input-textbox" id="input_10" name="q10_companyName10" size="20" value="<? if($_POST['q10_companyName10']<>''){echo $_POST['q10_companyName10'];}?>" />
        </div>
      </li>
      <li class="form-line jf-required" data-type="control_fullname" id="id_11">
        <label class="form-label form-label-left form-label-auto" id="label_11" for="input_11">
          Name
          <span class="form-required">
            *
          </span>
        </label>
        <div id="cid_11" class="form-input jf-required">
          <span class="form-sub-label-container" style="vertical-align: top;">
            <input class="form-textbox validate[required" type="text" size="10" name="q11_name11_first" id="first_11" value="<? if($_POST['q11_name11_first']<>''){echo $_POST['q11_name11_first'];}?>"/>
            <label class="form-sub-label" for="first_11" id="sublabel_first" style="min-height: 13px;"> First Name </label>
          </span>
          <span class="form-sub-label-container" style="vertical-align: top;">
            <input class="form-textbox validate[required" type="text" size="15" name="q11_name11_last" id="last_11" value="<? if($_POST['q11_name11_last']<>''){echo $_POST['q11_name11_last'];}?>"/>
            <label class="form-sub-label" for="last_11" id="sublabel_last" style="min-height: 13px;"> Last Name </label>
          </span>
        </div>
      </li>
      <li class="form-line jf-required" data-type="control_address" id="id_12">
        <label class="form-label form-label-left form-label-auto" id="label_12" for="input_12">
          Address
          <span class="form-required">
            *
          </span>
        </label>
        <div id="cid_12" class="form-input jf-required">
          <table summary="" undefined class="form-address-table" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td colspan="2">
                <span class="form-sub-label-container" style="vertical-align: top;">
                  <input class="form-textbox validate[required] form-address-line" type="text" name="q12_address12_addr_line1" id="input_12_addr_line1" value="<? if($_POST['q12_address12_addr_line1']<>''){echo $_POST['q12_address12_addr_line1'];}?>"/>
                  <label class="form-sub-label" for="input_12_addr_line1" id="sublabel_12_addr_line1" style="min-height: 13px;"> Street Address </label>
                </span>
              </td>
            </tr>
            <tr>
              <td colspan="2">
                <span class="form-sub-label-container" style="vertical-align: top;">
                  <input class="form-textbox form-address-line" type="text" name="q12_address12_addr_line2" id="input_12_addr_line2" size="46" value="<? if($_POST['q12_address12_addr_line2']<>''){echo $_POST['q12_address12_addr_line2'];}?>"/>
                  <label class="form-sub-label" for="input_12_addr_line2" id="sublabel_12_addr_line2" style="min-height: 13px;"> Street Address Line 2 </label>
                </span>
              </td>
            </tr>
            <tr>
              <td width="50%">
                <span class="form-sub-label-container" style="vertical-align: top;">
                  <input class="form-textbox validate[required] form-address-city" type="text" name="q12_address12_city" id="input_12_city" size="21" value="<? if($_POST['q12_address12_city']<>''){echo $_POST['q12_address12_city'];}?>"/>
                  <label class="form-sub-label" for="input_12_city" id="sublabel_12_city" style="min-height: 13px;"> City </label>
                </span>
              </td>
              <td>
                <span class="form-sub-label-container" style="vertical-align: top;">
                  <input class="form-textbox validate[required] form-address-state" type="text" name="q12_address12_state" id="input_12_state" size="22" value="<? if($_POST['q12_address12_state']<>''){echo $_POST['q12_address12_state'];}?>"/>
                  <label class="form-sub-label" for="input_12_state" id="sublabel_12_state" style="min-height: 13px;"> State / Province </label>
                </span>
              </td>
            </tr>
            <tr>
              <td width="50%">
                <span class="form-sub-label-container" style="vertical-align: top;">
                  <input class="form-textbox validate[required] form-address-postal" type="text" name="q12_address12_postal" id="input_12_postal" size="10" value="<? if($_POST['q12_address12_postal']<>''){echo $_POST['q12_address12_postal'];}?>"/>
                  <label class="form-sub-label" for="input_12_postal" id="sublabel_12_postal" style="min-height: 13px;"> Postal / Zip Code </label>
                </span>
              </td>
              <td>
                <span class="form-sub-label-container" style="vertical-align: top;">
                  <select class="form-dropdown validate[required] form-address-country" defaultcountry="" name="q12_address12_country" id="input_12_country">
                    <option value="United States"> United States </option>
                  </select>
                  <label class="form-sub-label" for="input_12_country" id="sublabel_12_country" style="min-height: 13px;"> Country </label>
                </span>
              </td>
            </tr>
          </table>
        </div>
      </li>
      <li class="form-line jf-required" data-type="control_phone" id="id_13">
        <label class="form-label form-label-left form-label-auto" id="label_13" for="input_13">
          Phone Number
          <span class="form-required">
            *
          </span>
        </label>
        <div id="cid_13" class="form-input jf-required">
          <span class="form-sub-label-container" style="vertical-align: top;">
            <input class="form-textbox validate[required" type="tel" name="q13_phoneNumber13_phone" id="input_13_phone" size="8" value="<? if($_POST['q13_phoneNumber13_phone']<>''){echo $_POST['q13_phoneNumber13_phone'];}?>">
            <label class="form-sub-label" for="input_13_phone" id="sublabel_phone" style="min-height: 13px;"> Phone Number </label>
          </span>
        </div>
      </li>
      <li class="form-line" data-type="control_button" id="id_2">
        <div id="cid_2" class="form-input-wide">
          <div style="margin-left:156px" class="form-buttons-wrapper">
            <img src="images/lbl-generate.png" onclick="lbl_genrate();" style="cursor:pointer" />
			<? if($_POST['simple_spc']<>''){ if($eflag<>'true'){?>
			<a href="<?=$link?>" target="_blank"><img src="images/lbl-download.png" /></a>
			<? }}?>
          </div>
        </div>
      </li>
      <li style="display:none">
        Should be Empty:
        <input type="text" name="website" value="" />
      </li>
    </ul>
  </div>
  <script>
		function lbl_genrate() {
			document.forms['form_63113145415445'].submit()
		}
		</script>
  <script>
  JotForm.showJotFormPowered = false;
  </script>
  <input type="hidden" id="simple_spc" name="simple_spc" value="63113145415445" />
  <script type="text/javascript">
  document.getElementById("si" + "mple" + "_spc").value = "63113145415445-63113145415445";
  </script>
  <script src="https://cdn.jotfor.ms/js/widgetResizer.js?REV=3.3.15599" type="text/javascript"></script>
</form>
 <style>#main-content ul li{ background:none;}</style>
</div>
<?php include("html/footer.php"); ?>
</body>
</html>