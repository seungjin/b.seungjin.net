<?php

class Date
{
	var $timestamp;
	
	//2010-02-23 10:28:12 MST
	public function __construct($timestamp)
	{
		$this->timestamp;
	}
	
	public function toRFC822()
	{
		/*
		<pubDate>Wed, 02 Oct 2002 08:00:00 EST</pubDate>
		<pubDate>Wed, 02 Oct 2002 13:00:00 GMT</pubDate>
		<pubDate>Wed, 02 Oct 2002 15:00:00 +0200</pubDate>
		*/
		
		$h = mktime(0, 0, 0, 10, 31, 2008);
		$d = date("F dS, Y", $h) ;
		$w= date("l", $h) ;
		//echo "$d is on a $w";
		
	}
	
	
	
	
}

?>