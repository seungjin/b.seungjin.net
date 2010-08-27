<?php

class Journals extends Database
{
	var $PDOStatement = null;
	
	public function __construct($request)
	{
		$form = "xml";
		$begin = 0;
		$size = 100;
		
		if ( sizeof($request) > 0) {
			$args = split("&",$request[0]);
			foreach ( $args as $element ) 
			{
				$args2 = split("=",$element);
			
				switch ($args2[0]) {
					case "form":
						$form = $args2[1];
						break;
					
					case "begin":
						$begin = $args2[1];
						break;
					
					case "size":
						$size = $args2[1];
						break;
				
				}
			}
		}
		
		$dbh = new PDO('mysql:host='.$this->host.';dbname='.$this->dbname,$this->username,$this->password);
		$dbh->beginTransaction();
		$dbh->exec("SET @i = -1;");
		$dbh->exec("SET NAMES 'utf8'");
		$this->PDOStatement = $dbh->query('SELECT * FROM (SELECT @i:=@i+1 as myrow, id, date, time, timezone, tag, subject, ref from journals where publishing_code = \'1\' order by date desc, time desc, id desc) AS A WHERE myrow >= '.$begin.' limit '.$size) ;		
		$dbh->commit();
		
		switch ($form) {
			case "xml":
				$this->toXML();
				break;
			case "feed":
				$this->toFeed();
				break;
		}
		
	}
	
	public function toXML()
	{	
		$resultXML = "<seungjin>\n<journals>\n";
		foreach ($this->PDOStatement as $row)
		{
			$subject = str_replace("&","&amp;",$row['subject']);
			$subject = str_replace(">","&gt;",$subject);
			$subject = str_replace("<","&lt;",$subject);
			$subject = str_replace("\"","&quot;",$subject);
			$subject = str_replace("'","&#39;",$subject);
			
			$ref = str_replace("&","&amp;",$row['ref']); 
			$ref = str_replace(">","&gt;",$ref); 
			$ref = str_replace("<","&lt;",$ref); 
			$ref = str_replace("\"","&quot;",$ref); 
			$ref = str_replace("'","&apos;",$ref); 
			
			$tag = str_replace("&","&amp;",$row['tag']);
			$tag = str_replace(">","&gt;",$tag); 
			$tag = str_replace("<","&lt;",$tag); 
			$tag = str_replace("\"","&quot;",$tag); 
			$tag = str_replace("'","&apos;",$tag);
			
			$resultXML .= "<journal>\n <id>{$row['id']}</id>\n <date>{$row['date']}</date>\n <time>{$row['time']}</time>\n <timezone>{$row['timezone']}</timezone>\n <tag>{$tag}</tag>\n <subject>{$subject}</subject>\n <ref>{$ref}</ref>\n</journal>\n";
			
		}
		$resultXML .= "</journals>\n";
		$resultXML .= "</seungjin>";
		
		header('Content-type: text/xml');
		print($resultXML);
		
	}
	
	public function toFeed()
	{	
		$feed = "<rss xmlns:atom=\"http://www.w3.org/2005/Atom\" version=\"2.0\" xmlns:georss=\"http://www.georss.org/georss\">\n";
		$feed .= "<channel>\n";
		$feed .= "<title>Seung-jin Kim | Journal</title>\n";
		$feed .= "<link>http://www.seungjin.net</link>\n";
		$feed .= "<atom:link type=\"application/rss+xml\" href=\"http://".$_SERVER['SERVER_NAME']."/Journals/form=feed&amp;size=40\" rel=\"self\"/>\n";
		$feed .= "<description>www.seungjin.net Feed</description>\n";
		$feed .= "<language>ko</language>\n";
		$feed .= "<ttl>40</ttl>\n";
		
		foreach ($this->PDOStatement as $row)
		{
			$subject = str_replace("&","&amp;",$row['subject']);
			$subject = str_replace(">","&gt;",$subject);
			$subject = str_replace("<","&lt;",$subject);
			$subject = str_replace("\"","&quot;",$subject);
			$subject = str_replace("'","&apos;",$subject);
			
			$ref = str_replace("&","&amp;",$row['ref']); 
			$ref = str_replace(">","&gt;",$ref); 
			$ref = str_replace("<","&lt;",$ref); 
			$ref = str_replace("\"","&quot;",$ref); 
			$ref = str_replace("'","&apos;",$ref);
			
			$feed .= "<item>\n<title>[{$row['tag']}] {$subject}</title>\n<description>[{$row['tag']}] {$subject}</description>\n<pubDate>{$row['date']} {$row['time']} {$row['timezone']}</pubDate>\n<link>{$ref}</link>\n</item>\n";
			
		}
		
		$feed .= "</channel>\n";
		$feed .= "</rss>\n";
		header('Content-type: application/rss+xml');
		echo $feed;
	}
	
}


?>
