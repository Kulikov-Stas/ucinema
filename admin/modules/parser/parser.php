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
				<td colspan="3"><p style="color:#f00;">Загружаемый файл должен быть txt или csv форматов!</p></td>
			</tr>
			<tr>
				<td>Выберите файл для парсинга</td>
				<td><input name="browse" type="file"></td>
				<td><input type="submit" name="submit_file" value="Выбрал"></td>
			</tr>
		</table>		
	</form>
	<h2>Текст файла:</h2>
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
					<div class="bottom"><input class="submit" type="submit" value="Парсить" name="submit_parse"></div>					
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
	
	/**************************** Парсим ************************************/
	if (isset($_POST['submit_parse'])) {
		$content = ''; $err = ''; $count_query = 0;
		$del_ras = true; $del_ctg = true; $del_tov = true;
		$what_tovar = '';
		if (isset($_POST['content']) && !empty($_POST['content'])){
			
			$lines = explode(chr(13), trim($_POST['content']));
			foreach ($lines as $line) {
		   		$row = explode(';', $line);
		    	
		   		unset($row[7]);
		   		
		   		// чистим каждое поле ================================-------------------------
		   		foreach ($row as $key => $value) $row[$key] = trim($value);
		   		
		   		// INSERT `rasdel` ============================---------------------------------
		    	if (!empty($row[0]) && !empty($row[1]) && $row[2] == '' && $row[3] == '' && $row[4] == '' && $row[5] == '' && $row[6] == '')	{
		    		
		    		$rname = htmlspecialchars($row[0]); $rid = htmlspecialchars($row[1]);
		    		
		    		if ($del_ras && empty($err)){
		    			$del_ras = false;
		    			$sql_ras = 'DELETE FROM `rasdel`';
		    			
		    			if (!mysql_query($sql_ras))				$err .= 'Не могу выполнить DELETE в таблице rasdel, разбирайтесь<br>';
		    			else $count_query++;	
		    		}
		    		
		    		if (!$del_ras && empty($err)){
		    			$what_tovar = 'rasdel';
		    			
		    			$sql_ras = 'INSERT INTO `rasdel` (id, linkid, nameid, visible) VALUE ('.$rid.', "'.$rid.'", "'.$rname.'", 1)';
		    			
		    			if (!mysql_query($sql_ras))				$err .= 'Не могу выполнить INSERT в таблице rasdel id - '.$rid.', разбирайтесь<br>';	
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

			    			if (!mysql_query($sql_ctg))			$err .= 'Не могу выполнить DELETE в таблице category, разбирайтесь<br>';
			    			else $count_query++;	
			    		}
			    		
			    		if (!$del_ctg && empty($err)){
			    			$what_tovar = 'category';
			    			
			    			$sql_ctg = 'INSERT INTO `category` (id, linkid, nameid, visible, rasid) VALUE ('.$cid.', "'.$cid.'", "'.$cname.'", 1, '.$rid.')';
			    			
			    			if (!mysql_query($sql_ctg ))		$err .= 'Не могу выполнить INSERT в таблице category id - '.$cid.', разбирайтесь<br>';	
			    			else $count_query++;
			    		}			
		    		}
		    		// INSERT `tovar` ============================---------------------------------
		    		elseif (!empty($what_tovar)) {
		    			
		    			$tname = htmlspecialchars($row[0]); $tid = htmlspecialchars($row[1]);
		    			
		    			if ($del_tov && empty($err)){
			    			$del_tov = false;
			    			$sql_tov = 'DELETE FROM `tovar`';

			    			if (!mysql_query($sql_tov))			$err .= 'Не могу выполнить DELETE в таблице tovar, разбирайтесь<br>';	
			    			else $count_query++;
			    		}
			    		
			    		if (!$del_tov && empty($err)){
			    			switch ($what_tovar){
			    				case 'rasdel':	
			    				//	if ($row['2'] == $rname && $row['3'] == $rid){
				    					$sql_tov = 'INSERT INTO `tovar` (id, linkid, nameid, visible, rasid, cost, valuta, unit) VALUE ('.$tid.', "'.$tid.'", "'.$tname.'", 1, '.$rid.', '.$row[6].', "'.$row[5].'", "'.$row[4].'")';
				    					
				    					if (!mysql_query($sql_tov))		$err .= 'Не могу выполнить INSERT в таблице tovar id - '.$tid.', разбирайтесь<br>';	
				    					else $count_query++;
			    				//	}
			    				//	else{
			    				//		$err .= 'Ошибка в структуре данных для парсинга id = '.$tid.', разбирайтесь<br>';
			    				//	}
			    					break;
			    				case 'category':
			    				//	if ($row['2'] == $cname && $row['3'] == $cid){	
				    					$sql_tov = 'INSERT INTO `tovar` (id, linkid, nameid, visible, rasid, catid, cost, valuta, unit) VALUE ('.$tid.', "'.$tid.'", "'.$tname.'", 1, '.$rid.', '.$cid.', '.$row[6].', "'.$row[5].'", "'.$row[4].'")';
				    					
				    					if (!mysql_query($sql_tov))		$err .= 'Не могу выполнить INSERT в таблице tovar id - '.$tid.', разбирайтесь<br>';	
				    					else $count_query++;
				    			//	}
			    				//	else{
			    				//		$err .= 'Ошибка в структуре данных для парсинга id = '.$tid.', разбирайтесь<br>';
			    				//	}	
			    					break;
			    			}		
			    		}
		    		}
		    		else{
		    			$err .= 'Некуда записывать - товар без категории и раздела, разбирайтесь';
		    		}
		    	
		    	}
		    }
			
		}
		else{
			$err .= 'Не хватает условий для парсинга!!!';
		}
		
		if (!empty($err))
			$content = $err.' выполнено '.$count_query.' запроса';
		else 
			$content .= 'Парсинг выполнен успешно ('.$count_query.' запросов), можете приступать к работе с новой БД';
			
		echo $content;
	}
-->