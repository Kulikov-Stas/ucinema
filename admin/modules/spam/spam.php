<?php

require_once('modules/base.php');

class spam extends base {
	private $returned_content;
	
	function  __construct() {
		$this->connectToDb();
	}

	public function preview() {
		// Case for action
		switch (@$_GET['action']) {
			case 'sent' : return $this->Sent($_POST['letter']);break;
			default     : return $this->ShowExists();break;
		}
	}
	
	protected function ShowExists($type = null) {
		$content = 
<<<CONTENT
	<div id="content" class="spam">
	<h2>����� ������ ��������:</h2>
	<form method="POST" action="?mode=spam&action=sent">
		<textarea name="letter" cols="50" style="width:700px; height:500px"></textarea>
		<div class="bottom"><input class="submit" type="submit" value="��������"></div>
		<script>
			tinyMCE.init({
			mode: 'textareas',
			theme: 'advanced',
			theme_advanced_toolbar_location: 'top',
		//	theme_advanced_statusbar_location: 'bottom',
			theme_advanced_resizing: true
			});
		</script>
	</form>
	</div>
CONTENT;
		return $content;
	}
	
	private function Sent($letter){
		$content = '';
		
		$to = '';
		// ��������� ������ ������� ����... 
		$sql = 'SELECT `nameid` FROM `'.$this->tablename_mailer.'` WHERE `active` = 1';
		$res = mysql_query($sql);
		$num = mysql_num_rows($res);
		$row = mysql_fetch_assoc($res);
		$to .= $row['nameid'];
		while ($row = mysql_fetch_assoc($res))
			$to .= $row['nameid'].','; 
		
		$to = substr($to, 0, strlen($to) - 1);
		// ��������� ��������� ������  	
		$subject = $this->GetConfig('subject');//'�������� �� EuroManagement';
			
		// ��������� �������������� ��������� ������ 
		$headers  = 'Content-type: text/html; charset=windows-1251';
		//$headers .= "From: Birthday Reminder <birthday@example.com>\r\n";
		//$headers .= "Bcc: birthday-archive@example.com\r\n";
		
		if (mail($to, $subject, $letter, $headers)) {
		    echo '<h3>�������� ������� ����������!</h3>';
		} 
		else {
		    echo '<h3>�������� ��������� ������!</h3>';
		}
		
		echo $content.$this->ShowExists();
	}
}
?>