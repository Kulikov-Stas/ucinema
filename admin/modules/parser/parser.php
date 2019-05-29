<?php

require_once('modules/base.php');

class parser extends base {
	private $returned_content;
	
	function  __construct() {
		$this->connectToDb();
	}

	public function preview() {
		// Case for action
		switch (@$_GET['action']) {
			case 'parsing' : return $this->Parsing();break;
			case 'showfile' : return $this->ShowExists();break;
			default     : return $this->ShowExists();break;
		}
	}
	
	protected function ShowExists($type = null) {
		$content = 
<<<CONTENT
	<div id="content" class="parser">
	<form method="POST" action="?mode=parser&action=showfile" enctype="multipart/form-data">
		<table>
			<tr>
				<td colspan="3"><p style="color:#f00;">����������� ���� ������ ���� txt ��� csv ��������!</p></td>
			</tr>
			<tr>
				<td>�������� ���� ��� ��������</td>
				<td><input name="browse" type="file"></td>
				<td><input type="submit" name="submit_file" value="������"></td>
			</tr>
		</table>		
	</form>
	<h2>����� �����:</h2>
				<form method="POST">
					<table>
						<tr><td colspan="2"><textarea name="content" cols="106" rows="40">
CONTENT;

	if (!isset($_POST['submit_parse']) && !isset($_POST['content']) && isset($filename) && is_file('database/'.$filename)){
		$fcontents = file('database/'.$filename);
		foreach ($fcontents as $line) {
    		$content .= $line;
		}
	}
	elseif (isset($_POST['submit_parse']) && isset($_POST['content']) && !empty($_POST['content'])) {
		$content .= trim($_POST['content']);
	}	
	$content .=
<<<CONTENT
							</textarea></td></tr>
	  					
	  				</table>
					<div class="bottom"><input class="submit" type="submit" value="�������" name="submit_parse"></div>					
				</form>
				</div>
CONTENT;
		echo $content;
	}
}
?>
<!--	
	if (isset($_POST['submit_file'])){	
		if (isset($_FILES['browse']) && !empty($_FILES['browse'])){
			if ($_FILES['browse']['error'] == 0 && ($_FILES['browse']['type'] == 'text/plain' || $_FILES['browse']['type'] == 'application/vnd.ms-excel')){
				if (is_uploaded_file($_FILES['browse']['tmp_name'])){
					$date = date('d_m_y', time());
					$filename = 'db_'.$date;
					move_uploaded_file($_FILES['browse']['tmp_name'], 'database/'.$filename);
				}
			}
		}
	}
	
	/**************************** ������ ************************************/
	if (isset($_POST['submit_parse'])) {
		$content = ''; $err = ''; $count_query = 0;
		$del_ras = true; $del_ctg = true; $del_tov = true;
		$what_tovar = '';
		if (isset($_POST['content']) && !empty($_POST['content'])){
			
			$lines = explode(chr(13), trim($_POST['content']));
			foreach ($lines as $line) {
		   		$row = explode(';', $line);
		    	
		   		unset($row[7]);
		   		
		   		// ������ ������ ���� ================================-------------------------
		   		foreach ($row as $key => $value) $row[$key] = trim($value);
		   		
		   		// INSERT `rasdel` ============================---------------------------------
		    	if (!empty($row[0]) && !empty($row[1]) && $row[2] == '' && $row[3] == '' && $row[4] == '' && $row[5] == '' && $row[6] == '')	{
		    		
		    		$rname = htmlspecialchars($row[0]); $rid = htmlspecialchars($row[1]);
		    		
		    		if ($del_ras && empty($err)){
		    			$del_ras = false;
		    			$sql_ras = 'DELETE FROM `rasdel`';
		    			
		    			if (!mysql_query($sql_ras))				$err .= '�� ���� ��������� DELETE � ������� rasdel, ������������<br>';
		    			else $count_query++;	
		    		}
		    		
		    		if (!$del_ras && empty($err)){
		    			$what_tovar = 'rasdel';
		    			
		    			$sql_ras = 'INSERT INTO `rasdel` (id, linkid, nameid, visible) VALUE ('.$rid.', "'.$rid.'", "'.$rname.'", 1)';
		    			
		    			if (!mysql_query($sql_ras))				$err .= '�� ���� ��������� INSERT � ������� rasdel id - '.$rid.', ������������<br>';	
		    			else $count_query++;
		    		}		
		    		
		    	}
		    	
		    	if (!empty($row[0]) && !empty($row[1]) && !empty($row[2]) && !empty($row[3])){
					
		    		// INSERT `category` ============================---------------------------------
		    		if ($row[4] == '' && $row[5] == '' && $row[6] == ''){
		    			
		    			$cname = htmlspecialchars($row[0]); $cid = htmlspecialchars($row[1]);
		    			
		    			if ($del_ctg && empty($err)){
			    			$del_ctg = false;
			    			$sql_ctg = 'DELETE FROM `category`';

			    			if (!mysql_query($sql_ctg))			$err .= '�� ���� ��������� DELETE � ������� category, ������������<br>';
			    			else $count_query++;	
			    		}
			    		
			    		if (!$del_ctg && empty($err)){
			    			$what_tovar = 'category';
			    			
			    			$sql_ctg = 'INSERT INTO `category` (id, linkid, nameid, visible, rasid) VALUE ('.$cid.', "'.$cid.'", "'.$cname.'", 1, '.$rid.')';
			    			
			    			if (!mysql_query($sql_ctg ))		$err .= '�� ���� ��������� INSERT � ������� category id - '.$cid.', ������������<br>';	
			    			else $count_query++;
			    		}			
		    		}
		    		// INSERT `tovar` ============================---------------------------------
		    		elseif (!empty($what_tovar)) {
		    			
		    			$tname = htmlspecialchars($row[0]); $tid = htmlspecialchars($row[1]);
		    			
		    			if ($del_tov && empty($err)){
			    			$del_tov = false;
			    			$sql_tov = 'DELETE FROM `tovar`';

			    			if (!mysql_query($sql_tov))			$err .= '�� ���� ��������� DELETE � ������� tovar, ������������<br>';	
			    			else $count_query++;
			    		}
			    		
			    		if (!$del_tov && empty($err)){
			    			switch ($what_tovar){
			    				case 'rasdel':	
			    				//	if ($row['2'] == $rname && $row['3'] == $rid){
				    					$sql_tov = 'INSERT INTO `tovar` (id, linkid, nameid, visible, rasid, cost, valuta, unit) VALUE ('.$tid.', "'.$tid.'", "'.$tname.'", 1, '.$rid.', '.$row[6].', "'.$row[5].'", "'.$row[4].'")';
				    					
				    					if (!mysql_query($sql_tov))		$err .= '�� ���� ��������� INSERT � ������� tovar id - '.$tid.', ������������<br>';	
				    					else $count_query++;
			    				//	}
			    				//	else{
			    				//		$err .= '������ � ��������� ������ ��� �������� id = '.$tid.', ������������<br>';
			    				//	}
			    					break;
			    				case 'category':
			    				//	if ($row['2'] == $cname && $row['3'] == $cid){	
				    					$sql_tov = 'INSERT INTO `tovar` (id, linkid, nameid, visible, rasid, catid, cost, valuta, unit) VALUE ('.$tid.', "'.$tid.'", "'.$tname.'", 1, '.$rid.', '.$cid.', '.$row[6].', "'.$row[5].'", "'.$row[4].'")';
				    					
				    					if (!mysql_query($sql_tov))		$err .= '�� ���� ��������� INSERT � ������� tovar id - '.$tid.', ������������<br>';	
				    					else $count_query++;
				    			//	}
			    				//	else{
			    				//		$err .= '������ � ��������� ������ ��� �������� id = '.$tid.', ������������<br>';
			    				//	}	
			    					break;
			    			}		
			    		}
		    		}
		    		else{
		    			$err .= '������ ���������� - ����� ��� ��������� � �������, ������������';
		    		}
		    	
		    	}
		    }
			
		}
		else{
			$err .= '�� ������� ������� ��� ��������!!!';
		}
		
		if (!empty($err))
			$content = $err.' ��������� '.$count_query.' �������';
		else 
			$content .= '������� �������� ������� ('.$count_query.' ��������), ������ ���������� � ������ � ����� ��';
			
		echo $content;
	}
-->