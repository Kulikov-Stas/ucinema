<?php

require_once('modules/base.php');
//require_once('modules/thumb.php');

class setup extends base {
	private $returned_content;
	
	function  __construct() {
		$this->connectToDb();
	}
	
	public function preview() {
		switch (@$_GET['action']){
			case 'install'  : return $this->Sent($_POST['letter']);break;
			default			: return $this->ShowExists();break;	
		}
	}
	
	/**
	 * Show exists 
	 *
	 * @return string
	 * @author Pavel Golovenko
	 */
	protected function ShowExists($type = null) {
		$content =
<<<CONTENT
	<div id="content" class="setup">
	<h2>Просмотр запроса на инсталляцию БД:</h2>
	<form method="POST" action="?mode=setup&action=install" >
		<textarea name="letter" cols="50" style="width:700px; height:500px">
CONTENT;

		$file = file_get_contents('modules/setup/setup.sql');
		$content .= $file;
		$content .=
<<<CONTENT
		</textarea>
		<div class="bottom"><input type="submit" class="submit" value="Install"></div>
	</form>
	</div>
CONTENT;
		
		return $content;
	}
}

?>