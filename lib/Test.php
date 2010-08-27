
<?php

class Test {
	
	private $val="4";
	
	function __construct($var) {
		$this->val = $var;
	}
	
	function out() {
		print($this->val);
	}
	
}



?>