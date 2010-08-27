<?php

$start_time = get_microtime();


$ch = curl_init($_SERVER['SERVER_NAME']."/Journals");
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_USERAGENT, "www.seungjin.net php/curl requested by " . $_SERVER['REMOTE_ADDR']);
$output = trim(curl_exec($ch));      
curl_close($ch);

$domDocument = new DOMDocument;              
$domDocument->loadXML(str_replace("&","&amp;",$output));

$array = array();
foreach ($domDocument->getElementsByTagName("journal") as $journal) {
	
	$date = is_object($journal->getElementsByTagName("date")->item(0)) ? $journal->getElementsByTagName("date")->item(0)->nodeValue : null;
	$time = is_object($journal->getElementsByTagName("time")->item(0)) ? $journal->getElementsByTagName("time")->item(0)->nodeValue : null;
	$timezone = is_object($journal->getElementsByTagName("timezone")->item(0)) ? $journal->getElementsByTagName("timezone")->item(0)->nodeValue : null;
	$tag = is_object($journal->getElementsByTagName("tag")->item(0)) ? $journal->getElementsByTagName("tag")->item(0)->nodeValue : null;
	$subject = is_object($journal->getElementsByTagName("subject")->item(0)) ? $journal->getElementsByTagName("subject")->item(0)->nodeValue : null;
	$ref = is_object($journal->getElementsByTagName("ref")->item(0)) ? $journal->getElementsByTagName("ref")->item(0)->nodeValue : null;
	
	$array[] = array("date" => $date , "time" => $time , "timezone" => $timezone , "tag" => $tag , "subject" => $subject , "ref" => $ref);
}

?>
<html>
<head>
 <title>SEUNG-JIN KIM</title>
 <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8" >
 <META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
 <!-- Copyright (C) 2005, 2006, 2007, 2008, 2009, 2010 seungjin.net -->
 <!--link rel="alternate" type="application/rss+xml" href="/Journals/form=feed&amp;size=40" title="RSS Feed, seungjin.net" /-->
 <meta name="date" content="<?php echo date("D M j G:i:s T Y"); ?>">
 <meta name="author" content="Kim, Seung-jin">
 <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=yes" />
 <link href="/styles/update.css" rel="stylesheet" type="text/css">
 <script type="text/javascript" src="/scripts/jquery-1.4.2.min.js"></script>
 <script type="text/javascript" src="/scripts/update.js"></script>
</head>
 <body>

  <div id='header'>
   <img src='/images/seungjin.net.small.white.png' />
   <!--img src='/images/ribbon.png' height=22 /-->
   <div id ='line'><hr/></div>
  </div>	
  <div id="journals">
  <?php
	foreach($array as $journal) 
	{
		$date = str_replace("-",".",$journal['date']);
		list($h,$m) = split(":",$journal['time']);
		$time = $h . ":" . $m;
		
		$timezone = $journal['timezone'];
		$subject = $journal['subject'];
		$ref = $journal['ref'];
		
		print("\n<div id=\"journal\">\n");
		print("<div id=\"date\">{$date}</div> <div id=\"time\">{$time}</div> <div id=\"timezone\">{$timezone}</div>\n");
		print("<div id=\"tag\">[{$journal['tag']}]</div>\n");
		if ($ref == null) {
			print("<div id=\"subject\">{$subject}</div>\n");
		} else {
			print("<div id=\"subject\">{$subject} <a href=\"{$ref}\">Link</a></div>\n");
		}
		
		print("</div>\n");
	}
	?>

   	<div id="journals_added"/>

   </div>

   <div id="controller">
    	
		<div id="ajax_loading_img">
			<img src="/images/ajax-loader.gif" width="220" height="19" />
		</div>
		<div id="read_order_updates"><a href='javascript:order_update();'>view order updates</a></div>
     	<div id="read_all_updates"><a href='javascript:all_update();'>view all updates</a></div>   
    </div>
    
   </div>

   <div id="footer">
		<div id="bottom_line"><hr/></div>
	</div>
   <!--div id="rss_feed"><a href="/Journals/form=feed&amp;size=40">RSS feed</a></div-->

 </body>
</html>
<?php
$page_time = round((get_microtime() - $start_time), 4);
print("\n\n<!-- Page generated in '$page_time' seconds.-->");
?>
