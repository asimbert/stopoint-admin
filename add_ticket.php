<?php
require_once(dirname(__FILE__) . '/inc/core.php');
require_once(dirname(__FILE__) . '/classes/class.table.php');
if(isset($_POST['submit'])){
	$sql="SELECT * FROM  `languages`  where  `iso_639-1`='$_POST[language_id]'";
$roww=mysql_query($sql);
$row = mysql_fetch_assoc($roww);
$sql2="SELECT * FROM  `language`  where  lang_id=$row[id]";
$roww2=mysql_query($sql2);
$row2 = mysql_fetch_assoc($roww2);
$lang_id=$row2['id'];


foreach( $_POST as $key => $n ) {
	//print_r($_POST);
	//echo $key;
	//echo $n;
	//exit;
	$flag=0;
	if($n!=$_POST['language_id'] && $n!=$_POST['submit']&& $n!=$_POST['feature_id']){
	//if(false){	
		 $pr="SELECT * from property_features where `language_id`=$lang_id and feature_id=$key";
	
		$pr=mysql_query($pr);
		  $num_rows = mysql_num_rows($pr);
   if($num_rows>=1){
	   
	   $rowqa = mysql_fetch_assoc($pr);
	  $up="UPDATE `property_features` SET `title` = '$n' where `language_id`=$lang_id and feature_id=$key";
		//exit;
		mysql_set_charset("UTF8");
	   mysql_query($up);
	   header('Content-type: text/html; charset=utf-8');
	   $flag=1;
	   }
		
if($flag==0){
	
	
	$sqlaas="SELECT * FROM property_features where id=$key";
								$rowwaas=mysql_query($sqlaas);
								$rowaas = mysql_fetch_assoc($rowwaas);
								
		//print_r($pr);
		//exit;
		//echo $ins="insert into property_features` ( `language_id`, `feature_id`, `title`) VALUES ($lang_id, $key, '$n')";
		
//header('Content-type: text/html; charset=UTF-8');
		$ins="INSERT INTO `property_features` (`title`, `is_searchable`, `type_id`, `language_id`, `feature_id`) VALUES ('$n', '$rowaas[is_searchable]',$rowaas[type_id], $lang_id, $key)";
		//exit;
		mysql_query("SET NAMES 'utf8'");
	//	mysql_set_charset("UTF8");
		//header('Content-type: text/html; charset=utf-8');
		mysql_query($ins);
		
		$flag=0;
}
		
 // print "The name is ".$n." and email is ".$key.", thank you\n";
  }
}
	//exit;
  header("Location: $url?edited=sucess&a_id=$_POST[feature_id]&p_id=$lang_id");
	}




include(dirname(__FILE__) . '/html/header.php');
include(dirname(__FILE__) . '/html/menu.php');
?>



		<div id="main-content"> <!-- Main Content Section with everything -->
			
			<!-- Page Head -->
			<a href="<?=$_SERVER['PHP_SELF']?>"><h2>Property Features Translation</h2></a>
			
			<br>	
			<div style="padding: 4px;width: auto;">
			<?php //$table->search();?>
			<?php //$table->display_records();?>
			</div>			
			<br>
			<br>
			<br>
			<br>
			<?php if (isset($_GET['edited'])){ ?>
            <div class="notification success png_bg">
			<a href="#" class="close"><img src="images/icons/cross_grey_small.png" title="Close this notification" alt="close"></a>
			<div>
			The entries were Edited succesfully
			</div>
		</div>
        <?php } ?>
			<br>		
			<div class="clear"></div> <!-- End .clear -->
			
			<div class="content-box"><!-- Start Content Box -->
				
				<div id="dialog" title="Delete this item?" style="display: none;">
					<p>Are you sure you want to delete this entry?</p>
				</div>
				
				<div class="content-box-header">
					
					<h3>Select Language from dropdown</h3>
					<div class="clear"></div>
					
                    <div style="margin: 15px 0px 7px 24px;">
                    
                    <br>
                    </div>

				</div> <!-- End .content-box-header -->
				
				<div class="content-box-content">
						
					<!--	<a href="<?//=edit_page()?>" class="link_button" style="font-size: 20px;">Add entry</a>-->
						<br>
						<br>
						<form name="actions" method="POST" action="<?=$_SERVER['PHP_SELF']?>">
                       <div id="loading" style="width: 90%; padding-bottom:10%;
text-align: center;
padding-top: 7%; display:none"> <img src="images/loader.gif"  alt="loader"/></div>
						<table id="tabli">
						
							<thead>
								<tr>
								   <th>Name</th>
                                   <th>Value</th>
                                   

								  
								</tr>
								
							</thead>
					 
							<tbody> <?php
								$sql="SELECT * from property_features where language_id=0";
								$roww=mysql_query($sql);
								$_SESSION['type_count']=0;
								$sd=1;$sdu=1;
								while ($row = mysql_fetch_assoc($roww)) { ?>
								
									
								<tr>
									<td><?php echo $row['title'];?></td>
                                    <?php 
									// $sas="SELECT value FROM `features_values` where feature_id=$row[id] and property_id=$_GET[p_id]";  
									//$rowwa=mysql_query($sas); $rowq = mysql_fetch_assoc($rowwa)  ?>
                                    <input type="hidden" value="<?php echo $row['title'];?>" name="<?php echo $row['id'];?>" id="th_<?php echo $sdu; $sdu++;?>" disabled/>
									<td><input type="text" value="" name="<?php echo $row['id'];?>" class="tc_<?php echo $row['id']?>" id="tr_<?php echo $sd; $sd++;?>" disabled/><?php  $_SESSION['type_count']++;?></td>
									
                                    <td>
										&nbsp;
										<!-- <a href="<?//=edit_url(urlencode($table->current_id)); ?>" title="Edit"><img src="<?php //echo $base_url; ?>/images/icons/pencil.png" alt="Edit" /></a>
										
										<a class="confirm_link" id="delete_link" href="<?//=$_SERVER['PHP_SELF']?>?delete=<?php //echo urlencode($table->current_id);?>" title="Delete"><img src="<?php //echo $base_url; ?>/images/icons/cross.png" alt="Delete" /></a>-->
									
									</td>
								</tr>
										<input type="hidden" name="feature_id" value="<?php echo $row['id'];?>"/>						
								<?php } ?>
							</tbody>
							
								<tfoot>
									<tr>
										<td colspan="6">
											<div class="bulk-actions align-left">
                                            <input type="hidden" id='language_id' name="language_id" value=""/>
                                            
                                            <input type="submit" class="button" value="Submit" name="submit"></input>
												<!--<select name="action_type">
													<option value="noaction">Choose an action...</option>
													<option value="delete">Delete</option>
												</select>
												-->
											</div>
											</form>
											
											<div class="pagination">
												<?php //$table->pagination(); ?>
											</div> <!-- End .pagination -->
											<div class="clear"></div>
										</td>
									</tr>
								</tfoot>
							
						</table>
	
				</div> <!-- End .content-box-content -->
				
			</div> <!-- End .content-box -->


			
<?php include("html/footer.php"); ?>