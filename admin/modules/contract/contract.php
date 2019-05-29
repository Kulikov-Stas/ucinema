<?php

require_once('modules/base.php');
//require_once('modules/thumb.php');

class contract extends base {
	private $returned_content;
	
	function  __construct() {
		$this->connectToDb();
	}
	
	public function preview() {
		switch (@$_GET['action']){
			default			: return $this->ShowExists();break;	
		}
	}
}

?>