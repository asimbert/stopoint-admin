<?php
 
require 'thumb_config.php';
require 'thumb_functions.php';
 
if(isset($_FILES['fupload'])) {
     
    if(preg_match('/[.](jpg)|(gif)|(png)$/', $_FILES['fupload']['name'])) {
         
        $filename = $_FILES['fupload']['name'];
        $source = $_FILES['fupload']['tmp_name'];   
        $target = $path_to_image_directory . $filename;
         
        move_uploaded_file($source, $target);
         
        createThumbnail($filename);     
    }
}
?>
 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
 
<head>
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
    <meta name="author" content="" />
    <title>Dynamic Thumbnails</title>
</head>
 
<body>
    <h1>Upload A File, Man!</h1>
    <form enctype="multipart/form-data" action="<?php print $_SERVER['PHP_SELF'] ?>" method="post">
        <input type="file" name="fupload" />
        <input type="submit" value="Go!" />
    </form>
</body>
</html>