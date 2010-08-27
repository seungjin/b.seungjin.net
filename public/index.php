<?php date_default_timezone_set('America/Phoenix');
echo('<?xml version="1.0" encoding="utf-8"?>');

function get_microtime()
{
	list($usec, $sec) = explode(' ', microtime());
	return ((float)$usec + (float)$sec);
}

$start_time = get_microtime();

// log logic
$timestamp = date('Y-m-d G:i:s');
$timezone = date("e");
$remote_ip = $_SERVER['REMOTE_ADDR'];
$user_agent = $_SERVER['HTTP_USER_AGENT'];
$url = "http://".$_SERVER["SERVER_NAME"].rawurldecode($_SERVER["REQUEST_URI"]);
$http_referer = $_SERVER['HTTP_REFERER'];

$link = mysql_connect('mysql.seungjin.net','seungjin','chickfly81');
mysql_select_db ('seungjin',$link);
if (!$link) {
	die ('could not connect: ' . mysql_error());
}
mysql_query("SET NAMES 'utf8'");

$query = "insert into access_logs (timestamp, timezone, remote_ip, user_agent, url, http_referer) values ( '".$timestamp."','".$timezone."','".$remote_ip."','". $user_agent. "','".mysql_real_escape_string($url)."','".$http_referer."')";
mysql_query($query);
mysql_close($link);


foreach(glob("../lib/*.php") as $class_filename) {
     require_once($class_filename);
}

$request = split("/",urldecode(substr($_SERVER["REQUEST_URI"],1)));

if ( $request[0] != null ) 
{ 
	// validate class

	$class_name = array_shift($request);
	$class = '../lib/'.$class_name.'.php';

	if ( !file_exists($class) ) {
		error_log('framework error: class file not found',0);
		print("invalid url..");
		exit();
	} else {
		eval("\$inst = new ".$class_name."(\$request);");
	}

} else {

	include("./update.php");

} 

$page_time = round((get_microtime() - $start_time), 4);
print("\n\n<!-- Page generated in '$page_time' seconds.-->");
?>
