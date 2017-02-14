<?php

function escape($string) {
	return mysql_real_escape_string($string);
}

function unescape($string) {
	return stripslashes($string);
}

function e_reporting() {
	 error_reporting(E_ALL);
	 ini_set("display_errors", 1);
}

// display the div for a specific type of notification

function show_notification($msg,$type,$noecho = false) {
	
	$output = '
		<div class="notification ' . $type . ' png_bg">
			<a href="#" class="close"><img src="images/icons/cross_grey_small.png" title="Close this notification" alt="close" /></a>
			<div>
			' . $msg .'
			</div>
		</div>
	';
	
	if ($noecho == true)
		return $output;	
	else
		echo $output;
}

/*
* URL Manipulation functions
*/

function edit_page() {
	
	$string = str_replace(".","_edit.",$_SERVER['PHP_SELF']);
	echo $string;
}

function view_page() {
	
	$string = str_replace(".","_view.",$_SERVER['PHP_SELF']);
	echo $string;
}

function edit_url($id,$p = 1) {
	
	if ($p)
		return edit_page() . '?action=edit&id=' . $id . '&p=' . $p;
	else
		return edit_page() . '?action=edit&id=' . $id;
	
}

function view_url($id) {
	return view_page() . '?action=view&id=' . $id;
}

function url_page() {
	$string = str_replace("_edit.",".",$_SERVER['PHP_SELF']);
	
	return $string;
}

function feature_url($id) {
	$string = $_SERVER['PHP_SELF'].'?action=feature&id=' . $id;;
	echo $string;
}

function getTitle($action) {
	if ($action == "edit")
		return 'Edit';
	else
		return 'Add';
}

/* end of URL Manipulation functions */


/*
* General Settings functions
*/

function getSettings($type) {
	
	global $db;
	
	$settings = $db->selectMultiple("SELECT * FROM settings WHERE type='$type' ORDER BY sort_order");
	
	return $settings;
}

function getSetting($setting) {
	global $db;
	
	$settings = $db->select("SELECT * FROM settings WHERE setting='$setting'");
	
	return $settings;
	
}


function updateSettings($settings) {
	
	global $db;
	
	foreach($settings as $setting) {
	
		$field = $setting['setting'];
		$value = sanitize($_POST[$field]);
		
		$db->query("UPDATE settings SET value='$value' WHERE setting='$field'");
	}
}

/* function that returns extension and name of a file */

function getFileExt($file) {
	
	$array['name'] = substr($file,0,strpos($file,"."));
	$array['extension'] = strrchr($file,".");
	
	return $array;
}

function short_text($value) {
	
	if (strlen($value) > 100)
		$value = substr($value,0,130) . "..";
	
	return stripslashes(strip_tags($value));
}

/* regex functions */

function getString($text, $s, $e) {
	
	$sp = strpos($text, $s, 0) + strlen($s);
	$ep = strpos($text, $e, 0);
	
	return substr($text, $sp, $ep - $sp);
}

function getLastVersion($url_id) {
	
	global $db;
	
	$data = $db->select("SELECT version FROM urls WHERE id='$url_id'");
	
	return $data['version'];
	
}

function sanitize($data) {

	$data = htmlspecialchars($data);

	if(get_magic_quotes_gpc())
		$data = stripslashes($data);

	$data = mysql_real_escape_string($data);

	return $data;
}

function stripslashes_deep($value) {
	
	if (strstr($value,"\\"))
		stripslashes_deep($value);

    return $value;
}

function escape_regex($str) {

	$str = str_replace("\\\\\\","\\",$str);
	$str = str_replace("\\\\","\\",$str);

	return $str;
}

function championStat($stat) {
	
	if (strstr($stat,'/'))
		$tmp = explode('/',$stat);
	
	$stats = array();
	$stats['total'] = $tmp[0];
	$stats['per_level'] = $tmp[1];
	
	return $stats;
}

   /**
     * get youtube video ID from URL
     *
     * @param string $url
     * @return string Youtube video id or FALSE if none found.
     */
    function youtube_id_from_url($url) {
        $pattern = '%^# Match any youtube URL
    (?:https?://)?  # Optional scheme. Either http or https
    (?:www\.)?      # Optional www subdomain
    (?:             # Group host alternatives
      youtu\.be/    # Either youtu.be,
    | youtube\.com  # or youtube.com
      (?:           # Group path alternatives
        /embed/     # Either /embed/
      | /v/         # or /v/
      | .*v=        # or /watch\?v=
      )             # End path alternatives.
    )               # End host alternatives.
    ([\w-]{10,12})  # Allow 10-12 for 11 char youtube id.
    ($|\?|&).*         # if additional parameters are also in query string after video id.
    $%x'
        ;
        $result  = preg_match($pattern, $url, $matches);
        if (false !== $result) {
            return $matches[1];
        }
        return false;
    }

?>