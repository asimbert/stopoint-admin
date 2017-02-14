<?php
require_once(dirname(__FILE__) . '/inc/core.php');
require_once(dirname(__FILE__) . '/classes/class.table_form.php');

$db = new Database();

$queryall = "SELECT * from setting_msg WHERE id = '1'";
$resultall = mysql_query($queryall);
$resultuser = mysql_fetch_array($resultall);



if (isset($_POST['general_submit'])) {

    $updaterecord = mysql_query("UPDATE setting_msg SET `text_content` = '".$_POST['text_content']."', `text_banner` = '".$_POST['text_banner']."' WHERE id=1") or die(mysql_error());
        header("Location: settings.php?saved");
}






include(dirname(__FILE__) . '/html/header.php');
include(dirname(__FILE__) . '/html/menu.php');
?>				

<div id="main-content"> <!-- Main Content Section with everything -->

    <!-- Page Head -->
    <a href="<?= $_SERVER['PHP_SELF'] ?>"><h2>Settings</h2></a>

<?php if ($action_msg) echo $action_msg; ?>
    <br>		
    <div class="clear"></div> <!-- End .clear -->

    <div class="content-box"><!-- Start Content Box -->

        <div class="content-box-header">

            <h3>General Settings</h3>

            <div class="clear"></div>

        </div> <!-- End .content-box-header -->

        <div class="content-box-content">

            <form action="settings.php" method="post">

                <fieldset> <!-- Set class to "column-left" or "column-right" on fieldsets to divide the form into columns -->

                    <p>
                    <h4>Overview Message</h4>
                    <textarea id="text_content" rows="5" cols="5" name="text_content" class="text"><?=$resultuser['text_content']?></textarea>
                    </p>

                    <br>
              
                    <p>
                    <h4>Text</h4>
                    <textarea id="text_banner" rows="5" cols="5" name="text_banner" class="text" maxlength="255"><?=$resultuser['text_banner']?></textarea>
                    </p>

                    <br>
              
                    <p>
                        <input class="button" type="submit"  name="general_submit" value="Submit" />
                    </p>

                </fieldset>

                <div class="clear"></div><!-- End .clear -->

            </form>

        </div> <!-- End .content-box-content -->

    </div> <!-- End .content-box -->



<?php include("html/footer.php"); ?>