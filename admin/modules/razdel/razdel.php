<?php
//ini_set('display_errors', 1);
//error_reporting(E_ALL);
require_once('modules/base.php');

if (!isset($_SESSION['sort']))
	$_SESSION['sort'] = '_short';

if (isset($_GET['table']) && !empty($_GET['table'])){
	if ($_GET['table'] != @$_SESSION['table_razdel'])
		$_SESSION['sort'] = '_short';

	$_SESSION['table_razdel'] = $_GET['table'];
}

class razdel extends base {
	private $table;
	private $returned_content;
	private $page;
	private $limit;
	private $count;


	function  __construct() {
		$this->connectToDb();

		$this->table = (isset($_SESSION['table_razdel']) && !empty($_SESSION['table_razdel'])) ? $_SESSION['table_razdel'] : 'pages';
		$this->page = (!isset ($_GET['page']) || empty($_GET['page'])) ? 1 : $_GET['page'];
		$this->limit = $this->get_config('admin_pager');
		$this->count = $this->count_table();
	}



    public function preview() {
		switch (@$_GET['action']){
			case 'struct' 	: $this->Struct(isset($_GET['table']) ? $_GET['table'] : $_POST['table'], @$_POST['sent']);	break;
			case 'create' 	: $this->Create(@$_POST['sent']);	break;
			case 'drop' 	: $this->Drop(@$_GET['table']);	break;
			case 'move' 	: $this->Move(@$_GET['table'], $_GET['id'], $_GET['order'], $_GET['move']);	break;
			case 'edit' 	:
				$this->Edit(@$_GET['table'], !isset($_GET['id']) || $_GET['id'] == '' ? @$_POST['id'] : @$_GET['id'], @$_POST['sent']);break;
			case 'add' 		:
				$this->Add(@$_GET['table'], @$_POST['sent']);break;
			case 'delete' 	: $this->Delete($_GET['table'], $_GET['id']);break;
			default			: return $this->ShowExists();break;
		}
	}

	public function AJAX($AJAXaction) {
		switch ($AJAXaction) {
			case 'deleteFile'		: $this->DeleteFile($_POST['table'], $_POST['id'], $_POST['fname']); break;
			case 'deleteStructItem'	: $this->DeleteStructItem($_GET['table'], $_GET['id']); break;
			case 'createSelect' 	: $this->CreateSelect(); break;
			case 'active' 			: $this->ChangeAllowed($_GET['table'], $_GET['id'], $_GET['visible']);break;
			default		  			: break;
		}
	}

	// удаление конкретного item для структуры (таблиц _prop)=================================--------------------------
     private function DeleteStructItem($table, $id) {
    	$name = mysql_result(mysql_query('SELECT fname FROM `'.$table.'_prop` WHERE id='.$id), 0);
    	$sql_delete = 'DELETE FROM `'.$table.'_prop` WHERE id='.$id.' LIMIT 1';
    	if (mysql_query($sql_delete)){
    		$sql_drop = 'ALTER TABLE `'.$table.'` DROP `'.$name.'`';
    		mysql_query($sql_drop);
    		// ALTER TABLE `banner_prop`  AUTO_INCREMENT =8
    	}

	}

	// формирование select для структуры (таблиц _prop)=================================--------------------------------
    private function CreateSelect() {
    	$content = '';
    	$sql = 'SELECT id, typename FROM `'.$this->tablename_fieldtypes.'` ORDER BY `id`';
		$res = mysql_query($sql);
		while ($row = mysql_fetch_assoc($res))
			$content .= '<option value="'.$row['id'].'">'.$row['typename'].'</option>';

    	echo cp1251_utf8($content);
    }

    // редактор для структуры (таблиц _prop)=================================-------------------------------------------
    private function Struct($table, $sent = '') {
    	$content = $error = '';
    	if ($sent == 1) {
    		$name       = $_POST['name'];
    		$ftype      = $_POST['ftype'];
    		$name_ucms  = $_POST['name_ucms'];
    		$properties = $_POST['properties'];
    	//	if (isset($_POST['visible']))
    	//		$visible 	= $_POST['visible'];
    	//	var_dump($_POST);
    		foreach ($name as $key=>$value){
				//if (!isset($visible[$key]))    $visible[$key] = 0;

				if (!empty($value) && $value != 'id' && $value != 'order' && $value != 'linkid' && $value != 'nameid' && $value != 'visible'){
					if (empty($name_ucms[$key]))
						$name_ucms[$key] = $value;

					$res = mysql_query('SELECT `fname`, `ftype` FROM `'.$table.'_prop` WHERE `id`='.$key.' LIMIT 1');
					if (mysql_num_rows($res) > 0)
						$old = mysql_fetch_assoc($res);

					$type = mysql_result(mysql_query('SELECT `realtype` FROM `'.$this->tablename_fieldtypes.'` WHERE `id`='.$ftype[$key].' LIMIT 1'), 0);

					// for type SET
					if ($ftype[$key] == 11){
						$props = explode('&', $properties[$key]);
						$str_set = '(';
						foreach ($props as $vprop)
							$str_set .= '"'.$vprop.'",';
						$str_set = substr($str_set, 0, strlen($str_set) - 1);
						$str_set .= ')';
						$type .= $str_set;
					}

					$sql = 'REPLACE `'.$table.'_prop` (`id`, `fname`, `ftype`, `rus`, `properties`)
							VALUES ('.$key.',
									"'.mysql_real_escape_string(trim($value)).'",
									'.trim($ftype[$key]).',
									"'.mysql_real_escape_string(trim($name_ucms[$key])).'",
									"'.mysql_real_escape_string(trim($properties[$key])).'")';

					if (mysql_query($sql)){
						$sql_alter = '';
						switch (mysql_affected_rows()){
							//insert
							case 1 :
								$sql_alter = 'ALTER TABLE `'.$table.'` ADD `'.$value.'` '.$type;
								break;
							//update
							case 2 :
								if ($old['fname'] != $value || $old['ftype'] != $ftype[$key])
									$sql_alter = 'ALTER TABLE `'.$table.'` CHANGE `'.$old['fname'].'` `'.$value.'` '.$type;

								break;
						}
						if (!empty($sql_alter) && !mysql_query($sql_alter))
							$error .= $sql_alter.'<br>Безуспешно ALTER по = '.$value.'<br>';
					}
					else
						$error .= $sql.'<br>Безуспешно REPLACE по id = '.$key.'<br>';
				}
    		}


			$content .= $error.$this->ShowExists();
			$sent = 0;
		}
		else {
			$sql = 'SELECT * FROM `'.$table.'_prop` ORDER BY `id`';
			$res = mysql_query($sql);

			$content .=
<<<STRUCT
<div id="PopUp" >
	<div  align="right" style="margin-right:2px;"><a href="javascript:popuphide('razdel', '{$this->page}');"><img class="close" src="img/icon-close.png" alt=""></a></div>
	<form name="createForm" method="POST" action="?mode=razdel&action=struct&page={$this->page}">
	<input name="sent" type="hidden" value="1">
	<input name="table" type="hidden" value="{$table}">
	<h1>Структура</h1>
	<table class="table" id="tableStruct" border="0" cellspacing="0" cellpadding="1" class="common" align="center">
	<tr>
		<td>
			Название в ucms:&nbsp;*
		</td>
		<td>
			 Имя поля:&nbsp;*
		</td>
		<td>
			Тип:&nbsp;*
		</td>
		<td>
			Свойство:
		</td>
		<td></td>
		<td></td>
	</tr>
STRUCT;
			$last_id = 0;
		//	$fieldtypes_typename = array();
		//	$fieldtypes_id = array();
		//	$is_fieldtypes = true;
			while ($row = mysql_fetch_assoc($res)){
				$content .=
	'<tr id="structItem'.$row['id'].'">
		<td>';
				if ($row['fname'] != 'id' && $row['fname'] != 'order' && $row['fname'] != 'linkid' && $row['fname'] != 'nameid' && $row['fname'] != 'visible')
					$content .= '<div class="t_input"><input name="name_ucms['.$row['id'].']" type="text"  value="'.$row['rus'].'"></div>';
				else
					$content .= '<div class="t_input"><input name="name_ucms['.$row['id'].']" type="text"  value="'.$row['rus'].'" readonly></div>';
		$content .= '</td><td>';

				if ($row['fname'] != 'id' && $row['fname'] != 'order' && $row['fname'] != 'linkid' && $row['fname'] != 'nameid' && $row['fname'] != 'visible')
					$content .= '<div class="t_input"><input name="name['.$row['id'].']" type="text"  value="'.$row['fname'].'"></div>';
				else
					$content .= '<div class="t_input"><input name="name['.$row['id'].']" type="text"  value="'.$row['fname'].'" readonly></div>';
		$content .= '</td><td>';

				if ($row['fname'] != 'id' && $row['fname'] != 'order' && $row['fname'] != 'linkid' && $row['fname'] != 'nameid' && $row['fname'] != 'visible')
					$content .= '<div class="t_input"><select name="ftype['.$row['id'].']" ></div>';
				else
					$content .= '<div class="t_input"><select name="ftype['.$row['id'].']"  disabled>';


				$sql_type = 'SELECT id, typename FROM `'.$this->tablename_fieldtypes.'` ORDER BY `id`';
				$res_type = mysql_query($sql_type);
				while ($row_type = mysql_fetch_assoc($res_type)){
					if ($row['ftype'] == $row_type['id'])
						$content .= '<option value="'.$row_type['id'].'" selected>'.$row_type['typename'].'</option>';
					else
						$content .= '<option value="'.$row_type['id'].'">'.$row_type['typename'].'</option>';
				}
			//	$is_fieldtypes = false;

				$content .= '</select></div>
		</td>
		<td>';
				if ($row['fname'] != 'id' && $row['fname'] != 'order' && $row['fname'] != 'linkid' && $row['fname'] != 'nameid' && $row['fname'] != 'visible')
					$content .= '<div class="t_input"><input name="properties['.$row['id'].']" type="text"  value="'.$row['properties'].'"></div>';
				else
					$content .= '<div class="t_input"><input name="properties['.$row['id'].']" type="text"  value="'.$row['properties'].'" readonly></div>';
		$content .= '</td><td>';

		// Редактор - Отображать
		/*
				if ($row['fname'] != 'id' && $row['fname'] != 'order' && $row['fname'] != 'linkid' && $row['fname'] != 'nameid' && $row['fname'] != 'visible'){
					if ($row['ftype'] == 2)
						$content .= ($row['visible'] == 1) ? '<input type="checkbox" name="visible['.$row['id'].']" value="1" title="Отображать" checked>' : '<input type="checkbox" name="visible['.$row['id'].']" value="1" title="Отображать" >';
				}
				else
					$content .= ($row['visible'] == 1) ? '<input type="checkbox" name="visible['.$row['id'].']" value="1" title="Отображать" checked disabled>' : '<input type="checkbox" name="visible['.$row['id'].']" value="1" title="Отображать" disabled>';
		$content .= '</td><td>';
		*/

				if ($row['fname'] != 'id' && $row['fname'] != 'order' && $row['fname'] != 'linkid' && $row['fname'] != 'nameid' && $row['fname'] != 'visible')
					$content .= '<a title="Удалить" href="javascript:delStructItem(\''.$table.'\', \''.$row['id'].'\')">
								<img border="0" src="img/delete.gif">
							</a>';
				$content .= '</td>
	</tr>';
				$last_id = $row['id'];
			}

			$content .=
<<<STRUCT
	</table><table border="0" cellspacing="0" cellpadding="1" class="common" align="center"></table>
	<div class="left"><input type="button" onclick="addStruct({$last_id})" value="Добавить поле" class="add_one_more" ></div>
	<div class="bottom"><input type="submit" class="submit" value="Сохранить" ></div>
	</form>
</div>
<script language="JavaScript" type="text/javascript">popup('PopUp', '300', '200');</script>
STRUCT;
		}

    	echo $content;
	}

	// удаляет таблицу из разделов==============================================----------------
	private function Drop($table){
		$content = '';
		if (!empty($table) && $table != 'pages'){
			$res1 = mysql_query('DELETE FROM `'.$this->tablename_basetable.'` WHERE `tablename`="'.$table.'"');
    		$res2 = mysql_query('DROP TABLE `'.$table.'`');
    		$res3 = mysql_query('DROP TABLE `'.$table.'_defsec`');
    		$res4 = mysql_query('DROP TABLE `'.$table.'_prop`');

    		if ($res1 && $res2 && $res3 && $res4){
    			$content .= 'Вы успешно удалили таблицу - '.$table.'<br>';
    			$content .= 'Вы успешно удалили таблицу - '.$table.'_defsec<br>';
    			$content .= 'Вы успешно удалили таблицу - '.$table.'_prop<br>';
    			unset($_SESSION['table_razdel']);
    		}
    		else
    			$content .= 'Таблица не удалилась, разбирайтесь';

			$content .= $this->ShowExists();
		}

		echo $content;
	}

	// создание раздела =================================--------------------------------------------
    private function Create($sent = '') {
    	$content = '';
    	if ($sent == 1) {
    		$name = trim($_POST['name']);
    		$rus = trim($_POST['rus']);
    		// регистрируем новую таблицу
    		$res1 = mysql_query('INSERT INTO `'.$this->tablename_basetable.'` (`tablename`, `proptable`, `rus`)
    							VALUES ("'.$name.'", "'.$name.'_prop", "'.$rus.'")');
    		// создаём новую таблицу
    		$res2 = mysql_query('CREATE TABLE `'.$name.'`
		    		(`id` INT(11) NOT NULL auto_increment PRIMARY KEY,
		    		`order` INT(11) NOT NULL,
		    		`linkid` INT(11) UNIQUE NOT NULL,
		    		`nameid` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci,
					`visible` TINYINT(1) NOT NULL DEFAULT 1)
					ENGINE='.$this->engine.' DEFAULT CHARSET=utf8');
    		// создаём таблицу _defsec
    		$res3 = mysql_query('CREATE TABLE `'.$name.'_defsec`
		    		(`id` INT(11) NOT NULL auto_increment PRIMARY KEY,
		    		`num` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci,
		    		`query` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
		    		`vname` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
					`vucms` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
					`vsite` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
					`vtype` INT(11))
					ENGINE='.$this->engine.' DEFAULT CHARSET=utf8');
    		// создаём таблицу _prop
    		$res4 = mysql_query('CREATE TABLE `'.$name.'_prop`
		    		(`id` INT(11) NOT NULL auto_increment PRIMARY KEY,
		    		`fname` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci UNIQUE NOT NULL,
		    		`ftype` INT(11) NOT NULL,
		    		`rus` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
					`properties` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci,
					`visible` tinyint(1) NOT NULL default 1)
					ENGINE='.$this->engine.' DEFAULT CHARSET=utf8');

    		if ($res1 && $res2 && $res3 && $res4){

    			mysql_query('INSERT INTO `'.$name.'_defsec`
    					VALUES (1, "nothing", "SELECT * FROM `'.$name.'` ORDER BY `order`", "_short", "id,visible,nameid,linkid", "nameid", 1)');

    			mysql_query('INSERT INTO `'.$name.'_prop`
    					VALUES (1, "id", 1, "Системное поле", "", 1),
    							(2, "order", 4, "Порядок", "", 1),
    							(3, "linkid", 4, "Ссылка", "", 0),
    							(4, "nameid", 2, "Название", "", 1),
    							(5, "visible", 12, "Отображать", "1", 0)');

    			$path = '../design/tables/'.$name;
				if (!is_dir($path))	{
					mkdir($path);

					$short_file = fopen($path.'/short.html', 'w');
				//	$str = '<ul class="inner_menu_block"><li><a href="[LINK]">[CAPTION]</a></li></ul>';
				//	fwrite($menu0_file, $str);
					fclose($short_file);

					$long_file = fopen($path.'/long.html', 'w');
				//	$str = '<ul class="inner_menu_block"><li><a href="[LINK]" style="color:#663300;text-decoration:none">[CAPTION]</a></li></ul>';
				//	fwrite($menu0_file_selected, $str);
					fclose($long_file);
				}

				$_SESSION['table_razdel'] = $name;
    			$content .= 'Вы успешно создали таблицу - '.$name;
    		}
    		else
    			$content .= 'Таблица не создалась, разбирайтесь';

			$content .= $this->ShowExists();
			$sent = 0;
		}
		else {
			$content .=
<<<CREATE
<div id="PopUp" >
	<div align="right" style="margin-right:2px;"><a  href="javascript:popuphide('razdel', '{$this->page}');"><img class="close" src="img/icon-close.png" alt=""></a></div>

	<form name="createForm" method="POST" action="?mode=razdel&action=create&page={$this->page}">
	<input name="sent" type="hidden" value="1">
	<h1>Создание</h1>
	<div class="prop">
		<div class="left">Имя таблицы в БД:&nbsp;*</div>
		<div class="right inp"><input name="name" type="text" style="width:420px;"></div>
	</div>
	<div class="prop">
		<div class="left">Имя таблицы в UCMS:&nbsp;*</div>
		<div class="right inp"><input name="rus" type="text" style="width:420px;"></div>
	</div>
	<div class="bottom"><input class="submit" type="submit" value="Создать"></div>
	</form>
</div>
<script language="JavaScript" type="text/javascript">popup('PopUp', '300', '150');</script>
CREATE;
		}

    	echo $content;
	}

	// удаление ======================================--------------------------------------------
    private function Delete($table, $id) {
    	$del_order = mysql_result(mysql_query('SELECT `order` FROM `'.$table.'` WHERE `id`='.$id), 0);
    	mysql_query('DELETE FROM `'.$table.'` WHERE id='.$id);

    	$max_order = mysql_result(mysql_query('SELECT MAX(`order`) FROM `'.$table.'`'), 0);
    	for ($i = $del_order; $i < $max_order; $i++){
    	   	$next_id = mysql_result(mysql_query('SELECT `id` FROM `'.$table.'` WHERE `order`='.($i + 1)), 0);
	    	mysql_query('UPDATE `'.$table.'` SET `order`='.$i.' WHERE `id`='.$next_id);
    	}

    	echo $this->ShowExists();
	}

	// скрыть/показать ======================================-------------------------------------
	private function ChangeAllowed($table, $id, $value) {
		$content = '';
		if ($value == 'allowed') {
			if (mysql_query('UPDATE `'.$table.'` SET `visible`=1 WHERE id='.$id))
				$content .= '<a href="javascript:changeAllowed(\'razdel\', \''.$table.'\', \''.$id.'\', \'disallowed\')" title="Запретить к показу">
					<img src="img/allowed.gif" border="0"></a>';
		}
		elseif ($value == 'disallowed') {
			if (mysql_query('UPDATE `'.$table.'` SET `visible`=0 WHERE id='.$id))
				$content .= '<a href="javascript:changeAllowed(\'razdel\', \''.$table.'\', \''.$id.'\', \'allowed\')" title="Разрешить к показу">
					<img src="img/disallowed.gif" border="0"></a>';
		}

		echo cp1251_utf8($content);
	}

	// перемещение ======================================-----------------------------------------
    private function Move($table, $id, $order, $move){
    	$query = mysql_result(mysql_query('SELECT `query` FROM `'.$table.'_defsec` WHERE `vname` = "'.$_SESSION['sort'].'"'), 0);
		$res = mysql_query($query);
		$orders = $ids = array();

		while ($row = mysql_fetch_assoc($res)){
			$orders[] = $row['order'];
			$ids[] = $row['id'];
		}
	//		echo $order.' '.$id.'<br>';
		$keyid = array_search($id, $ids);
		if (isset($move) && $move == 'down'){
		//	echo $orders[$keyid + 1].' '.$ids[$keyid + 1].'<br>';
			mysql_query('UPDATE `'.$table.'` SET `order` = '.$orders[$keyid + 1].' WHERE id = '.$id);
			mysql_query('UPDATE `'.$table.'` SET `order` = '.$order.' WHERE id = '.$ids[$keyid + 1]);
		}

		if (isset($move) && $move == 'up'){
		//	echo $orders[$keyid - 1].' '.$ids[$keyid - 1].'<br>';
			mysql_query('UPDATE `'.$table.'` SET `order` = '.$orders[$keyid - 1].' WHERE id = '.$id);
			mysql_query('UPDATE `'.$table.'` SET `order` = '.$order.' WHERE id = '.$ids[$keyid - 1]);
		}

		echo $this->ShowExists();
	}

	// редактировать ======================================---------------------------------------
	private function Edit($table, $id, $sent = 0)
 	{
		$content = '';

		if ($sent == 1) {
			$sql_sets = '';
			// разбираемся с загружеными файлами
			$sql = 'SELECT `fname`, `properties` FROM `'.$table.'_prop` WHERE `ftype` = 8';
			$res = mysql_query($sql);
			while ($row = mysql_fetch_assoc($res)) {
				// убиваем все поля с типом картинка из POST для не затирания старого значения
				unset($_POST[$row['fname']]);

				if (isset($_FILES[$row['fname']]) && !empty($_FILES[$row['fname']]) && !empty($_FILES[$row['fname']]['name'])){
					$mimes = array();
					$ext_prop = explode('&', $row['properties']);
					foreach ($ext_prop as $value)
						array_push($mimes, $this->GetMIMEType($value));

					if ($_FILES[$row['fname']]['error'] == 0 &&
					   in_array($_FILES[$row['fname']]['type'], $mimes)
					/*
					   ($_FILES[$row['fname']]['type'] == 'image/gif' ||
						$_FILES[$row['fname']]['type'] == 'image/png' ||
						$_FILES[$row['fname']]['type'] == 'image/x-png' ||
						$_FILES[$row['fname']]['type'] == 'image/jpeg' ||
						$_FILES[$row['fname']]['type'] == 'image/pjpeg')*/){
						if (is_uploaded_file($_FILES[$row['fname']]['tmp_name'])){
							$ex = end(explode('.',$_FILES[$row['fname']]['name']));
							$filename = $this->generate_uid().'.'.$ex;

							if (!is_dir('../siteimg/'.$table))
								mkdir('../siteimg/'.$table);

							if (move_uploaded_file($_FILES[$row['fname']]['tmp_name'], '../siteimg/'.$table.'/'.$filename)){
								$src = '/siteimg/'.$table.'/'.$filename;
								// new version
								if ($this->version == 'new'){
									$sql_sets .= '`'.$row['fname'].'`="'.$src.'",';
								}
								// old version
								if ($this->version == 'old'){
									$image = "<img border='0' alt='' src='".$src."'>";
									$image = htmlentities($image);
									$sql_sets .= '`'.$row['fname'].'`="'.$image.'",';
								}
							}
							else {
								$content .= 'Не могу переместить файл<br>';
							}
						}
						else {
							$content .= 'Не могу загрузить файл<br>';
						}
					}
					else {
						$content .= 'Файл не соответствует формату<br>';
					}
				}
			}

			// всё остальное
			$max_linkid = mysql_result(mysql_query('SELECT MAX(`order`) FROM `shedule`'), 0);
			for($fsi=1;$fsi<=$_POST['film_sched_count'];$fsi++)
			{
				if(isset($_POST["sched_id"][$fsi]) and $_POST["sched_id"][$fsi] !== '')
				{
					if($_POST['todelete'][$fsi]==0)
					{
						if($_POST["film_cost"][$fsi] !== '' and $_POST["film_date"][$fsi] !== '' and $_POST["film_time"][$fsi]!== '')
						mysql_query("update shedule set `cost`='".$_POST["film_cost"][$fsi]."', `date`='".$_POST["film_date"][$fsi]."', `time`='".$_POST["film_time"][$fsi]."', `visible`= '".$_POST["film_sched_visible"][$fsi]."' where id=".$_POST["sched_id"][$fsi]);
					}
					else
					mysql_query('delete from shedule where id='.$_POST["sched_id"][$fsi].';');
				}
				else
				if($_POST["film_cost"][$fsi] !== '' and $_POST["film_date"][$fsi] !== '' and $_POST["film_time"][$fsi]!== '')
				{
					$max_linkid++;
					mysql_query("insert into shedule (`nameid`, `linkid`, `order`, `cost`, `date`, `time`, `visible`) values('".$id."','".$max_linkid."','".$max_linkid."','".$_POST["film_cost"][$fsi]."','".$_POST["film_date"][$fsi]."','".$_POST["film_time"][$fsi]."','".$_POST["film_sched_visible"][$fsi]."');");
				}
			}
			unset($_POST['todelete']);
            unset($_POST['film_sched_count']);
			unset($_POST["film_cost"]);
			unset($_POST["film_time"]);
			unset($_POST["film_date"]);
			unset($_POST["film_sched_visible"]);
			unset($_POST["sched_id"]);

			foreach ($_POST as $key => $value){
				if ($key != 'sent'){
					if (is_array($value)){
						$sql_sets .= '`'.$key.'`="';
						foreach ($value as $val)
							$sql_sets .= $val.'*';

						$sql_sets = substr($sql_sets, 0, strlen($sql_sets) - 1);
						$sql_sets .= '",';
					}
					else{
						$sql_sets .= '`'.$key.'`="'.addslashes($value).'",';
					}
				}
			}

			$sql_sets = substr($sql_sets, 0, strlen($sql_sets) - 1);

			// разбираемся с Столбец - множественный
			$sql = 'SELECT `fname` FROM `'.$table.'_prop` WHERE `ftype` = 14';
			$res = mysql_query($sql);
			while ($row = mysql_fetch_assoc($res)) {
				if (!isset($_POST[$row['fname']]))
					$sql_sets .= ', `'.$row['fname'].'`="" ';
			}

			$sql = 'UPDATE `'.$table.'` SET '.$sql_sets.' WHERE id='.$id;

			if (mysql_query($sql))
				$content .= 'Редактирование выполнилось успешно';
			else
				$content .= $sql.'<br>Редактирование не выполнилось, разбирайтесь';

			$content .= $this->ShowExists();
			$sent = 0;
		}
		else {
			$sql_prop = 'SELECT `fname`, `ftype`, `rus`, `properties` FROM `'.$table.'_prop` ORDER BY `id`';
			$res_prop = mysql_query($sql_prop);

			$sql = 'SELECT * FROM `'.$table.'` WHERE `id`='.$id.' LIMIT 1';
			$res = mysql_query($sql);
			$row = mysql_fetch_assoc($res);
			$content .=
<<<EDIT

<div id="PopUp" >
	<div align="right" style="margin-right:2px;"><a  href="javascript:popuphide('razdel', '{$this->page}');"><img class="close" src="img/icon-close.png" alt=""/></a></div>
	<form name="editForm" method="POST" action="?mode=razdel&table={$table}&action=edit&page={$this->page}" enctype="multipart/form-data">
	<h1>Редактирование</h1>
	<input name="sent" type="hidden" value="1">
	<input name="id" type="hidden" value="{$id}">






EDIT;

			while ($row_prop = mysql_fetch_assoc($res_prop)){
				// id и order не добавляем
				if ($row_prop['ftype'] != 1 && $row_prop['fname'] != 'order')
					$content .= $this->tr4Edit($row[$row_prop['fname']], $row_prop['fname'], $row_prop['ftype'], $row_prop['rus'], $row_prop['properties']);
			}


			if($table == 'film')
			$content .= $this->film_schedule($id);

			$content .=
<<<EDIT

			<div class="bottom"><input class="submit" type="submit" value="Редактировать" ></div>

	</form>
</div>
<script language="JavaScript" type="text/javascript">popup('PopUp', '400', '300');</script>
<script>
tinyMCE.init({
	// General options
	mode : "textareas",
	theme : "advanced",
    language : "ru",
	skin : "custom",
	plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,imagemanager",
	extended_valid_elements : "iframe[name|src|framespacing|border|frameborder|scrolling|title|height|width],object[declare|classid|codebase|data|type|codetype|archive|standby|height|width|usemap|name|tabindex|align|border|hspace|vspace]",
	force_br_newlines : true,
	forced_root_block : '',
	// Theme options
EDIT;
if (!$this->GetConfig('wyzywig'))
$content.='
            theme_advanced_buttons1 : "save,bold,italic,underline,strikethrough,sub,sup,|,justifyleft,justifycenter,justifyfull,justifyright,|,outdent,indent,|,bullist,numlist,|,forecolor,backcolor,pasteword,|,advhr,link,image,code,charmap",
            theme_advanced_buttons2 : "",
            ';
else
$content.='
	theme_advanced_buttons1 : "save,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
	theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
	theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
	theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,spellchecker,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,blockquote,pagebreak,|,insertfile,insertimage",';
$content .=
<<<EDIT
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
	theme_advanced_statusbar_location : "bottom",
	theme_advanced_resizing : true,

	theme_simple_toolbar_location : "top",
	theme_simple_toolbar_align : "left",
	theme_simple_statusbar_location : "bottom",
	theme_simple_resizing : true,

	// Example content CSS (should be your site CSS)
	//content_css : "/admin/includes/css/main.css",

	// Drop lists for link/image/media/template dialogs
	template_external_list_url : "js/template_list.js",
	external_link_list_url : "js/link_list.js",
	external_image_list_url : "js/image_list.js",
	media_external_list_url : "js/media_list.js",
	// Replace values for the template plugin
	template_replace_values : {
		username : "Some User",
		staffid : "991234"
	}
});
</script>
EDIT;
		}
		echo $content;
	}

	private function tr4Edit($value, $fname, $ftype, $rus, $properties){
		$content =
<<<TR
			<div class="prop"><div class="left">{$rus}:</div>
TR;

		switch ($ftype){
			// Системная строка
			case 1 :
			// Строка
			case 2 :
			// Почта
			case 3 :
			// Целое число
			case 4 :
			// Дробное число
			case 10 :
				$value = htmlspecialchars($value);
				$content .=
<<<TR
			<div class="right inp"><input name="{$fname}" type="text" value="{$value}"></div></div>
TR;
				break;
			// Дата
			case 5 : $content .=
<<<TR
			<div class="right inp"><input id="buttonPicker1" name="{$fname}" type="text" " value="{$value}"></div></div>
TR;
				break;
			// Время
			case 6 :$content .=
<<<TR
			<div class="right inp"><input id="begintime" name="{$fname}" type="time" value="{$value}"></div></div>
TR;
				break;
			// Текст
			case 7 : $content .=
<<<TR
			<div class="right text"><textarea name="{$fname}" cols="50">{$value}</textarea></div></div>
TR;
				break;
			// Картинка
			case 8 :
				$thumb = '<span style="color:red"><img src="/admin/img/no-image.png" alt=""/></span>';
				if (!empty($value)){
					$image_ext = array('png', 'jpg', 'gif');

					if ($this->version == 'old'){
						$pattern="/src='(.*\/([^\/]*))'/U";
						preg_match($pattern, $value, $match,PREG_OFFSET_CAPTURE);
						//var_dump($match);
						$ext = end(explode('.', $match[1][0]));
					}
					else
						$ext = end(explode('.', $value));
				// Для разного рода файлов
					if (in_array(strtolower($ext), $image_ext))
						$thumb = stripcslashes($this->create_thumbs($_SESSION['table_razdel'], $value, 150, 150, $this->version,'/admin/img/no-image.png')).
								'<a class="remove_one" title="Удалить файл" onclick="DelFile(\''.$_GET['table'].'\', \''.$_GET['id'].'\', \''.$fname.'\')" href="javascript:void(0)"
									style="float: right; margin: 50px 10px 0 0;">Удалить</a>';
					else
						$thumb = $value.
						'<a class="remove_one" title="Удалить файл" onclick="DelFile(\''.$_GET['table'].'\', \''.$_GET['id'].'\', \''.$fname.'\')" href="javascript:void(0)"
									style="float: right; margin: 0 10px 0 0;">Удалить/a>';
				}

				$ex = str_replace('&', ';', $properties);
				$content =
<<<TR

	<div class="prop">
		<div class="left">{$rus}:<br>($ex)</div>
		<div class="right upl_file">
			<div class="fileform">
				<div class="selectbutton"></div>
				<span>{$value}</span>
				<input id="upload" type="file" name="{$fname}" />
			</div>
			<div class="upl_img" id="imagetd_{$fname}" >{$thumb}</div>
		</div>
	</div>

TR;
				break;
			// Дата-время
			case 9 : $content .=
<<<TR
			<div class="right date-time"><input id="buttonPicker1" name="{$fname}" type="text" value="{$value}"></div></div>
TR;
				break;
			// Список SET
			case 11 : $content .=
<<<TR
			<div class="right select"><select name="{$fname}">
TR;
				$props = explode('&', $properties);
				foreach ($props as $vprops){
					if ($value == $vprops)
						$content .= '<option value="'.$vprops.'" selected>'.$vprops.'</option>';
					else
						$content .= '<option value="'.$vprops.'">'.$vprops.'</option>';
				}
				$content .=
<<<TR
			</select></div></div>
TR;
				break;
			// Логический
			case 12 :
				if ($value == 1)
					$content .=
<<<TR
			<div class="right boolean"><input name="{$fname}" type="radio" style="float:none;" value="1" checked>Да
			<input name="{$fname}" type="radio" style="float:none;" value="0">Нет</div></div>

TR;
				else
					$content .=
<<<TR
			<div class="right boolean"><input name="{$fname}" type="radio" style="float:none;" value="1">Да
			<input name="{$fname}" type="radio" style="float:none;" value="0" checked>Нет</div></div>

TR;
				break;
			// Столбец
			case 13 : $content .=
<<<TR
			<div class="right">
			<select name="{$fname}" >
			<option value="0"> - </option>
TR;
				$props = explode('&', $properties);
				if ($isMenu = mysql_result(mysql_query('SELECT COUNT(*) FROM `basemenu` WHERE `menuname` = "'.$props[0].'"'), 0))
					$content .= $this->_ShowSelectMenu($value, $props, 0);
				else{
					$sql = 'SELECT id, `'.$props[1].'` FROM `'.$props[0].'`';
					$res = mysql_query($sql);
					while ($row = mysql_fetch_assoc($res)){
						$content .= '<option value="'.$row['id'].'" '.(($value == $row['id']) ? 'selected' : '').'>'.$row[$props[1]].'</option>';
					}
				}

				$content .=
<<<TR
			</select>
			</div></div>
TR;
				break;
				// Столбец - множественный
			case 14 : $content .=
<<<TR
			<div class="right inp multiple">
			<div class="multiple_clear"></div>
			<select name="{$fname}[]" multiple size="6" >
			<option value="0"> - </option>
TR;
				$props = explode('&', $properties);
				if ($isMenu = mysql_result(mysql_query('SELECT COUNT(*) FROM `basemenu` WHERE `menuname` = "'.$props[0].'"'), 0))
					$content .= $this->_ShowSelectMenu($value, $props, 0);
				else{
					$sql = 'SELECT id, `'.$props[1].'` FROM `'.$props[0].'` WHERE `visible`=1 ORDER BY `order`';

					$res = mysql_query($sql);
					$values = explode('*', $value);
					while ($row = mysql_fetch_assoc($res)){

						$content .= '<option value="'.$row['id'].'"';
						foreach ($values as $val){
							if ($row['id'] == $val)
								$content .= ' selected';

						}
						$content .= '>'.$row[$props[1]].'</option>';

					}
				}

				$content .=
<<<TR
			</select>
			</div></div>
TR;
				break;
		}

		$content .=
<<<TR
TR;

		return $content;
	}

	private function _ShowSelectMenu($value, $props, $sub, $nbsp = '')
	{
		$content = '';

		$rcolor = mt_rand(0, 200);
		$gcolor = mt_rand(0, 200);
		$bcolor = mt_rand(0, 200);

		$sql = 'SELECT id, `'.$props[1].'` FROM `'.$props[0].'` WHERE sub = '.$sub.' ORDER BY `order`';
		$res = mysql_query($sql);
		$values = explode('*', $value);
		while ($row = mysql_fetch_assoc($res)){
			$count = mysql_result(mysql_query('SELECT COUNT(*) FROM `'.$props[0].'` WHERE `sub` = '.$row['id']), 0);
			$content .= '<option style="color:RGB('.$rcolor.','.$gcolor.','.$bcolor.')" value="'.$row['id'].'"';

			foreach ($values as $val)
				if ($row['id'] == $val)
					$content .= ' selected';

			$content .= '>'.$nbsp.$row[$props[1]].'</option>';
			if ($count > 0)	{
				$nbsp_sub = $nbsp.'&nbsp;&nbsp;';
				$content .= $this->_ShowSelectMenu($value, $props, $row['id'], $nbsp_sub);
			}

		}

		return $content;
	}

	private function DeleteFile($table, $id, $fname){
		$content = '';
    	$sql = 'UPDATE `'.$table.'` SET `'.$fname.'` = "" WHERE `id`='.$id;
		if (mysql_query($sql))
			$content = '<span style="color:red">No file</span>';

    	echo cp1251_utf8($content);
	}


	// добавить ======================================--------------------------------------------
	private function Add($table, $sent = 0)
        {
		$content = '';

		if ($sent == 1) {
			$sql_values = '';
			$sql_fields = '';
			foreach ($_POST as $key => $value){
				if ($key != 'sent' && !empty($value)){
					$sql_fields .= '`'.$key.'`,';
					if (is_array($value)){
						$sql_values .= '"';
						foreach ($value as $val)
							$sql_values .= $val.'*';

						$sql_values = substr($sql_values, 0, strlen($sql_values) - 1);
						$sql_values .= '",';
					}
					else{
						$sql_values .= '"'.addslashes($value).'",';
					}
				}
			}
			// разбираемся с загружеными файлами
			$sql = 'SELECT `fname`, `properties` FROM `'.$table.'_prop` WHERE `ftype` = 8';
			$res = mysql_query($sql);
			while ($row = mysql_fetch_assoc($res)) {
				if (isset($_FILES[$row['fname']]) && !empty($_FILES[$row['fname']]) && !empty($_FILES[$row['fname']]['name'])){
					$mimes = array();
					$ext_prop = explode('&', $row['properties']);
					foreach ($ext_prop as $value)
						array_push($mimes, $this->GetMIMEType($value));

					if ($_FILES[$row['fname']]['error'] == 0 &&
					   in_array($_FILES[$row['fname']]['type'], $mimes)
						/*($_FILES[$row['fname']]['type'] == 'image/gif' ||
						$_FILES[$row['fname']]['type'] == 'image/png' ||
						$_FILES[$row['fname']]['type'] == 'image/x-png' ||
						$_FILES[$row['fname']]['type'] == 'image/jpeg' ||
						$_FILES[$row['fname']]['type'] == 'image/pjpeg')*/){
						if (is_uploaded_file($_FILES[$row['fname']]['tmp_name'])){
							$ex = end(explode('.',$_FILES[$row['fname']]['name']));
							$filename = $this->generate_uid().'.'.$ex;

							if (!is_dir('../siteimg/'.$table))
								mkdir('../siteimg/'.$table);

							if (move_uploaded_file($_FILES[$row['fname']]['tmp_name'], '../siteimg/'.$table.'/'.$filename)){
								$src = '/siteimg/'.$table.'/'.$filename;
								// new version
								if ($this->version == 'new'){
									$sql_fields .= '`'.$row['fname'].'`,';
									$sql_values .= '"'.$src.'",';
								}
								// old version
								if ($this->version == 'old'){
									$image = "<img border='0' alt='' src='".$src."'>";
									$image = htmlentities($image);
									$sql_fields .= '`'.$row['fname'].'`,';
									$sql_values .= '"'.$image.'",';
								}
							}
							else {
								$content .= 'Не могу переместить файл<br>';
							}
						}
						else {
							$content .= 'Не могу загрузить файл<br>';
						}
					}
					else {
						$content .= 'Файл не соответствует формату<br>';
					}
				}
			}

			$sql_fields = substr($sql_fields, 0, strlen($sql_fields) - 1);
			$sql_values = substr($sql_values, 0, strlen($sql_values) - 1);

			// вычисляем max order
			$max_order = mysql_result(mysql_query('SELECT MAX(`order`) FROM `'.$table.'`'), 0);
			$max_order++;
			if (empty($max_order)) $max_order = 1;

			$sql_fields = '`order`,'.$sql_fields;
			$sql_values = $max_order.','.$sql_values;

			$sql = 'INSERT INTO `'.$table.'` ('.$sql_fields.') VALUES ('.$sql_values.')';

			if (mysql_query($sql))
				$content .= 'обавление выполнилось успешно';
			else
				$content .= $sql.'<br>Добавление не выполнилось, разбирайтесь';


//			unset($_POST);
			$sent = 0;

			$content .= $this->ShowExists();
		}
		else {
			$sql = 'SELECT `fname`, `ftype`, `rus`, `properties` FROM `'.$table.'_prop` ORDER BY `id`';
			$res = mysql_query($sql);

			$content .=
<<<ADD
<div id="PopUp" >
	<div  align="right" style="margin-right:2px;"><a href="javascript:popuphide('razdel', '{$this->page}');"><img class="close" src="img/icon-close.png" alt=""></a></div>
	<form name="addForm" method="POST" action="?mode=razdel&table={$table}&action=add&page={$this->page}" enctype="multipart/form-data">
	<input name="sent" type="hidden" value="1">
	<h1>Добавить запись</h1>
ADD;

			while ($row = mysql_fetch_assoc($res)){
				// id и order не добавляем
				if ($row['ftype'] != 1 && $row['fname'] != 'order')
					$content .= $this->tr4Add($row['fname'], $row['ftype'], $row['rus'], $row['properties']);
			}
echo '<h1>ТУТ:'.$this->GetConfig('wyzywig').'</h1>';
			$content .=
<<<ADD

	<div class="bottom"><input class="submit" type="submit" value="Добавить" ></div>
	</form>
</div>
<script language="JavaScript" type="text/javascript">popup('PopUp', '300', '200');</script>
<script>
tinyMCE.init({
	// General options
	mode : "textareas",
	theme : "advanced",
    language : "ru",
	plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,imagemanager",
	extended_valid_elements : "iframe[name|src|framespacing|border|frameborder|scrolling|title|height|width],object[declare|classid|codebase|data|type|codetype|archive|standby|height|width|usemap|name|tabindex|align|border|hspace|vspace]",
	force_br_newlines : true,
	forced_root_block : '',
		// Theme options
ADD;
if (!$this->GetConfig('wyzywig'))
$content.='
            theme_advanced_buttons1 : "save,|,bold,italic,underline,strikethrough,sub,sup,|,justifyleft,justifycenter,justifyright,justifyfull,outdent,indent,|,bullist,numlist,|,forecolor,backcolor,pasteword,|,advhr,link,image,code,charmap",
            theme_advanced_buttons2 : "",
            ';
else
$content.='
	theme_advanced_buttons1 : "save,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
	theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
	theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
	theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,spellchecker,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,blockquote,pagebreak,|,insertfile,insertimage",';
$content .=
<<<ADD
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
	theme_advanced_statusbar_location : "bottom",
	theme_advanced_resizing : true,

	// Example content CSS (should be your site CSS)
	//content_css : "/includes/css/main.css",

	// Drop lists for link/image/media/template dialogs
	template_external_list_url : "js/template_list.js",
	external_link_list_url : "js/link_list.js",
	external_image_list_url : "js/image_list.js",
	media_external_list_url : "js/media_list.js",
	// Replace values for the template plugin
	template_replace_values : {
		username : "Some User",
		staffid : "991234"
	}
});
</script>
ADD;
		}
		echo $content;
	}

	private function next_field($table, $field)
	{
		$max = mysql_result(mysql_query('SELECT MAX(`'.$field.'`) FROM `'.$table.'`'), 0);
		return ++$max;
	}

	private function tr4Add($fname, $ftype, $rus, $properties){
		$content =
<<<TR
	<div class="prop">
	<div class="left">{$rus}:</div>
TR;
		$linkid = $this->next_field($_GET['table'], 'linkid');
		switch ($ftype){
			// Системная строка, Строка, Почта, Целое число
			case 1 :
			case 2 :
			case 3 :
			case 4 :
                            if($this->table == 'production' && $fname == 'articul_site')
                            {
                                $art_site = intval(mysql_result(mysql_query('SELECT MAX(articul_site) FROM `production`'), 0));
                                if($art_site == 0){$art_site = '1000001';}
                                else{$art_site++;}
                            }
				$value = '';
				if ($fname == 'linkid'){
					$max_id = mysql_result(mysql_query('SELECT MAX(`id`) FROM `'.$_GET['table'].'`'), 0);
					$max_id++;
					if (empty($max_id)) $max_id = 1;
					$value = $max_id;
				}
				$content .=
'
			<div class="right inp"><input name="'.$fname.'" type="text" '.($fname == 'linkid' ? 'value="'.$linkid.'"' : ($fname == 'articul_site' ? 'value="'.$art_site.'"' : '')).'></div></div>
';
				break;
			// Дата
			case 5 : $content .=
<<<TR
			<div class="right"><input id="buttonPicker1" name="{$fname}" type="text"></div></div>
TR;
				break;
			// Время
			case 6 :$content .=
<<<TR
			<div class="right inp"><input id="begintime" name="{$fname}" type="time""></div></div>
TR;
				break;
			// Текст
			case 7 : $content .=
<<<TR
			<div class="right text"><textarea name="{$fname}"></textarea></div></div>
TR;
				break;
			// Файл
			case 8 :
				$ex = str_replace('&', ';', $properties);
				$content =
<<<TR
			<div class="prop"><div class="left">{$rus}:<br>($ex)</div>
			<div class="right"><input name="{$fname}" type="file" size="61"></input></div></div>
			<!--<div class="right">
			<div class="fileform">
				<div class="selectbutton"></div>
				<span>Здесь будет адр.</span>
				<input id="upload" type="file" name="{$fname} size="61"" />
			</div>
			</div>-->
TR;
				break;
			// Дата-время
			case 9 : $content .=
<<<TR
			<div class="right inp date-time"><input id="buttonPicker1" name="{$fname}" type="text" ></div><div class="right inp date-time time"><input name="{$fname}-time" type="text" ></div></div>
TR;
				break;
			// Дробное число
			case 10 : $content .=
<<<TR
			<div class="right"><input name="{$fname}" type="text" ></div></div>
TR;
				break;
			// Список SET
			case 11 : $content .=
<<<TR
			<div class="right">
			<select name="{$fname}" >
TR;
				$props = explode('&', $properties);
				foreach ($props as $vprops){
					$content .= '<option value="'.$vprops.'">'.$vprops.'</option>';
				}
				$content .=
<<<TR
			</select>
			</div></div>
TR;
				break;
			// Логический
			case 12 :
				if ($properties == 1)
					$content .=
<<<TR
			<div class="right boolean">
			<input name="{$fname}" type="radio" style="float:none;" value="1" checked>Да
			<input name="{$fname}" type="radio" style="float:none;" value="0">Нет
			</div></div>
TR;
				else
					$content .=
<<<TR
			<div class="right boolean">
			<input name="{$fname}" type="radio" style="float:none;" value="1">Да
			<input name="{$fname}" type="radio" style="float:none;" value="0">Нет
			</div></div>
TR;
				break;
			// Столбец
			case 13 : $content .=
<<<TR
			<div class="right">
			<select name="{$fname}">
			<option value="0"> - </option>
TR;
				$props = explode('&', $properties);
				if ($isMenu = mysql_result(mysql_query('SELECT COUNT(*) FROM `basemenu` WHERE `menuname` = "'.$props[0].'"'), 0))
					$content .= $this->_ShowSelectMenu('', $props, 0);
				else{
					$sql = 'SELECT id, `'.$props[1].'` FROM `'.$props[0].'`';
					$res = mysql_query($sql);
					while ($row = mysql_fetch_assoc($res)){
						$content .= '<option value="'.$row['id'].'">'.$row[$props[1]].'</option>';
					}
				}
				/*
				$props = explode('&', $properties);
				$sql = 'SELECT id, `'.$props[1].'` FROM `'.$props[0].'` WHERE `visible`=1';
				$res = mysql_query($sql);
				while ($row = mysql_fetch_assoc($res)){
					$content .= '<option value="'.$row['id'].'">'.$row[$props[1]].'</option>';
				}
				*/
				$content .=
<<<TR
			</select></div></div>
TR;
				break;
				// Столбец - множественный
			case 14 : $content .=
<<<TR
			<div class="right">
			<select name="{$fname}[]" multiple size="6" >
			<option value="0"> - </option>
TR;
				$props = explode('&', $properties);
				if ($isMenu = mysql_result(mysql_query('SELECT COUNT(*) FROM `basemenu` WHERE `menuname` = "'.$props[0].'"'), 0))
					$content .= $this->_ShowSelectMenu('', $props, 0);
				else{
					$sql = 'SELECT id, `'.$props[1].'` FROM `'.$props[0].'` WHERE `visible`=1 ORDER BY `order`';
					$res = mysql_query($sql);
					while ($row = mysql_fetch_assoc($res)){
						$content .= '<option value="'.$row['id'].'">'.$row[$props[1]].'</option>';
					}
				}
				/*
				$props = explode('&', $properties);
				$sql = 'SELECT id, `'.$props[1].'` FROM `'.$props[0].'` WHERE `visible`=1';
				$res = mysql_query($sql);
				while ($row = mysql_fetch_assoc($res)){
					$content .= '<option value="'.$row['id'].'">'.$row[$props[1]].'</option>';
				}
				*/
				$content .=
<<<TR
			</select></div></div>
TR;
				break;
		}

		$content .=
<<<TR
		</td>
	</tr>
TR;

		return $content;
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
<div id="content" class="razdel">
<h2>Управление разделами</h2>
<div class="prop">
	<div class="left">Текущий раздел</div>
	<div class="right inp">
	<form name="selectTableForm"  action="?mode=razdel&table={$this->table}&page={$this->page}">
								<input type="hidden" name="mode" value="razdel">
								<select name="table" onchange="javascript:document.forms['selectTableForm'].submit()">
CONTENT;
		$sql = 'SELECT * FROM `'.$this->tablename_basetable.'` ORDER BY `rus`';
		$res = mysql_query($sql);
		while ($row = mysql_fetch_assoc($res)){
			if ($row['tablename'] == $this->table)
				$content .=	'<option value="'.$row['tablename'].'" selected>'.$row['rus'].'</option>';
			else
				$content .=	'<option value="'.$row['tablename'].'">'.$row['rus'].'</option>';
		}

        		// управление сортировкой по спискам
		if (isset($_POST['sort']) && !empty($_POST['sort'])) $_SESSION['sort'] = $_POST['sort'];
		$memo = '<tr>
						<td width="5px"></td>

						<td valign="center">
							<form name="selectSortForm" method="POST" action="?mode=razdel&table='.$this->table.'&page='.$this->page.'">
									<select name="sort" style="width:200px" onchange="this.form.submit()">';

		$sql_sort = 'SELECT vname, query FROM `'.$this->table.'_defsec`';
		$res_sort = mysql_query($sql_sort);
		while ($row_sort = mysql_fetch_assoc($res_sort)){
			if ($_SESSION['sort'] == $row_sort['vname'])
				$memo .= '<option value="'.$row_sort['vname'].'" selected>'.$row_sort['vname'].'</option>';
			else
				$memo .= '<option value="'.$row_sort['vname'].'">'.$row_sort['vname'].'</option>';
		}
		$memo .= '			</select>
							</form>
						</td>
						<td width="5px"></td>
					</tr>';


		$content .=
<<<CONTENT

								</select>
								<!--a href="javascript:document.forms['selectTableForm'].submit()">
									<img height="16" width="16" border="0" title="Выбрать таблицу разделов" src="img/icons/m_menu.gif">
								</a-->
							</form>
		<a class="struct_razdel" href="?mode=razdel&table={$this->table}&action=struct&page={$this->page}"><img src="img/settings.png" alt="" /></a>
		<a class="add_razdel add_one_more" href="?mode=razdel&action=create&page={$this->page}">Создать раздел</a>
		<a class="del_razdel remove_one" href="?mode=razdel&table={$this->table}&action=drop&page={$this->page}" onclick="return confirm('Удалить раздел - {$this->table}?')">Удалить раздел</a>
	</div>
</div>
<div class="prop sort_right">
CONTENT;
$content.='
	<div class="left">Сортировка</div>
	<div class="right inp">'.$memo.'


	</div>
</div>
	<a class="add_rec add_one_more" href="?mode=razdel&table='.$this->table.'&action=add&page='.$this->page.'">Добавить запись</a>';
<<<CONTENT

	<table class="menu" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td height="29">
				<table class="category-controls" width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td width="5px"></td>
						<td></td>
						<td>

						</td>
						<td align="right"><img border="0" alt="" src="img/add.gif"></td>
						<td align="right" width="100px"></td>
						<td width="5px"></td>
					</tr>
CONTENT;



                // сортировка по галереям
               /* if($this->table === 'photo')
                {
                    if (isset($_POST['sgallery']) && !empty($_POST['sgallery'])) $_SESSION['sgallery'] = $_POST['sgallery'];
                    $content .= '<tr>
						<td width="5px"></td>
						<td><font class="aname" style="color:blue">Сортировка по галереям</font></td>
						<td valign="center">
							<form name="frmGalSort" method="POST" action="?mode=razdel&table='.$this->table.'&page='.$this->page.'">
									<select name="sgallery" style="width:200px" onchange="this.form.submit()">';

                    $sql_sort = 'SELECT id, nameid FROM `gallery`';
                    $res_sort = mysql_query($sql_sort);
                    while ($row_sort = mysql_fetch_assoc($res_sort)){
                            if (isset($_SESSION['sgallery']) && $_SESSION['sgallery'] == $row_sort['id'])
                                    $content .= '<option value="'.$row_sort['id'].'" selected>'.$row_sort['nameid'].'</option>';
                            else
                                    $content .= '<option value="'.$row_sort['id'].'">'.$row_sort['nameid'].'</option>';
                    }
                    $content .= '			</select>
                                                            </form>
                                                    </td>
                                                    <td width="5px"></td>
                                            </tr>';
                }*/
                // сортировка по опросам
               /* if($this->table === 'answers')
                {
                    if (isset($_POST['sinterview']) && !empty($_POST['sinterview'])) $_SESSION['sinterview'] = $_POST['sinterview'];
                    $content .= '<tr>
						<td width="5px"></td>
						<td><font class="aname" style="color:blue">Сортировка по опросам</font></td>
						<td valign="center">
							<form name="frmIntrSort" method="POST" action="?mode=razdel&table='.$this->table.'&page='.$this->page.'">
									<select name="sinterview" style="width:200px" onchange="this.form.submit()">';

                    $sql_sort = 'SELECT id, nameid FROM `interview`';
                    $res_sort = mysql_query($sql_sort);
                    while ($row_sort = mysql_fetch_assoc($res_sort)){
                            if (isset($_SESSION['sinterview']) && $_SESSION['sinterview'] == $row_sort['id'])
                                    $content .= '<option value="'.$row_sort['id'].'" selected>'.$row_sort['nameid'].'</option>';
                            else
                                    $content .= '<option value="'.$row_sort['id'].'">'.$row_sort['nameid'].'</option>';
                    }
                    $content .= '			</select>
                                                            </form>
                                                    </td>
                                                    <td width="5px"></td>
                                            </tr>';
                }*/


		$content .=
<<<CONTENT
				</table>
			</td>
		</tr>

	</table>
		<div class="pager">
			{$this->Pager($this->count, $this->limit, $this->page)}
			{$this->generate_by_type($this->table)}
			{$this->Pager($this->count, $this->limit, $this->page)}
		</div>

</div>
CONTENT;

		return $content;
	}

	protected function count_table()
	{
		$query = mysql_result(mysql_query('SELECT `query` FROM `'.$this->table.'_defsec` WHERE `vname` = "'.$_SESSION['sort'].'"'), 0);
		$res = mysql_query($query);
		return mysql_num_rows($res);
	}

	protected function Pager($count, $limit, $page)
	{
		$cont = '';

		if (ceil($count/$limit) > 1){
			for ($i = 1; $i <= ceil($count/$limit); $i++){
				$cont .=  ($i == $page) ? '<span>'.$i.'</span> ' : '<a href="/admin/?mode=razdel&page='.$i.'">'.$i.'</a> ';
			}
		}

		return $cont;
	}

	protected function get_config($key){
		return mysql_result(mysql_query('SELECT `value` FROM `config` WHERE `key`="'.$key.'"'), 0);
	}

	protected function generate_by_type($table) {
		if (!empty($table)){
		//	$sql_prop = 'SELECT * FROM `'.$table.'_prop` WHERE `visible` = 1 AND `ftype` = 2';
		//	$res_prop = mysql_query($sql_prop);
			$this->returned_content =
<<<CONTENT
		<table class="main-table" width="100%" cellpadding="5" cellspacing="0" border="1" style="border-collapse:collapse">
			<tr class="first-row">
				<th align="center" width="3%"><span style="color:green;font-weight:bold">№</span></th>
				<th align="center" width="3%"><span style="color:green;font-weight:bold">id</span></th>
				<th align="center"><span style="color:green;font-weight:bold">Название</span></th>
CONTENT;
			/*
			$sql = '';
			$fnames = array();
			while ($row_prop = mysql_fetch_assoc($res_prop)){
				$sql .= $row_prop['fname'].', ';
				array_push($fnames, $row_prop['fname']);
				$this->returned_content .= '<td align="center"><span style="color:green;font-weight:bold">'.$row_prop['rus'].'</span></td>';
			}
			*/
			$this->returned_content .=
<<<CONTENT
				<th align="center" width="17%"><span style="color:green;font-weight:bold">Операции</span></th>
			</tr>
CONTENT;
			$query = '';
                        if($table === 'photo')
                        {
                            if(isset($_SESSION['sgallery']))
                            {$query = 'SELECT * FROM `'.$table.'` WHERE `gallery` = "'.$_SESSION['sgallery'].'"';}
                            else
                            {$query = 'SELECT * FROM `'.$table.'`';}
                        }
                        if($table === 'answers')
                        {
                            if(isset($_SESSION['sinterview']))
                            {$query = 'SELECT * FROM `'.$table.'` WHERE `interview_id` = "'.$_SESSION['sinterview'].'"';}
                            else
                            {$query = 'SELECT * FROM `'.$table.'`';}
                        }
                        else
                        {
                            $query = mysql_result(mysql_query('SELECT `query` FROM `'.$table.'_defsec` WHERE `vname` = "'.$_SESSION['sort'].'"'), 0);
                        }
                        $res = mysql_query($query.' LIMIT '.($this->limit * ($this->page - 1)).', '.$this->limit);
                        $number = mysql_num_rows($res);

			if ($number > 0){
				$i = 1;
                //echo $query;
				while ($row = mysql_fetch_assoc($res)){
					$this->returned_content .=
<<<CONTENT
	<tr id="id{$row['id']}">
		<td align="center"><span>{$row['order']}</span></td>
		<td align="center"><span>{$row['id']}</span></td>
CONTENT;
			//		foreach ($fnames as $k => $v)
			if ($table === 'shedule'){
			$resulbka = mysql_result(mysql_query('SELECT `nameid` FROM film WHERE id = '.$row['nameid']), 0);
				$this->returned_content .= '<td align="center"><span>'.$resulbka.'</span></td>';
			}
			else {
				$this->returned_content .= '<td align="center"><span>'.$row['nameid'].'</span></td>';
			}

					$this->returned_content .=
<<<CONTENT
		<td align="center"><span>
CONTENT;


					$this->returned_content .=
	'<a href="?mode=razdel&table='.$table.'&action=delete&id='.$row['id'].'&page='.$this->page.'" onclick="return confirm(\'Удалить?\')" title="Удалить"><img src="img/delete.gif" border="0"></a>
	<a href="?mode=razdel&table='.$table.'&action=edit&id='.$row['id'].'&page='.$this->page.'" title="Редактировать">
		<img src="img/edit.gif" border="0"></a>';

					if ($row['visible'] == 1)
						$this->returned_content .=
	'<div id="idallow'.$row['id'].'" style="display:inline">
		<a href="javascript:changeAllowed(\'razdel\', \''.$table.'\', \''.$row['id'].'\', \'disallowed\')" title="Запретить к показу">
			<img src="img/allowed.gif" border="0">
		</a>
	</div>';

					if ($row['visible'] == 0)
						$this->returned_content .=
	'<div id="idallow'.$row['id'].'" style="display:inline">
		<a href="javascript:changeAllowed(\'razdel\', \''.$table.'\', \''.$row['id'].'\', \'allowed\')" title="Разрешить к показу">
			<img src="img/disallowed.gif" border="0">
		</a>
	</div>';

					if ($number != 1){
						switch ($i){
							case 1 :
								$this->returned_content .=
		'<a href="?mode=razdel&table='.$table.'&action=move&id='.$row['id'].'&order='.$row['order'].'&move=down&page='.$this->page.'" title="пїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅ пїЅпїЅпїЅпїЅ">
			<img src="img/down.gif" border="0"></a>';
								break;
							case $number :
								$this->returned_content .=
		'<a href="?mode=razdel&table='.$table.'&action=move&id='.$row['id'].'&order='.$row['order'].'&move=up&page='.$this->page.'" title="пїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅ пїЅпїЅпїЅпїЅпїЅ">
			<img src="img/up.gif" border="0"></a>';
								break;
							default:
								$this->returned_content .= '
		<a href="?mode=razdel&table='.$table.'&action=move&id='.$row['id'].'&order='.$row['order'].'&move=down&page='.$this->page.'" title="пїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅ пїЅпїЅпїЅпїЅ">
			<img src="img/down.gif" border="0"></a>
		<a href="?mode=razdel&table='.$table.'&action=move&id='.$row['id'].'&order='.$row['order'].'&move=up&page='.$this->page.'" title="пїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅпїЅ пїЅпїЅпїЅпїЅпїЅ">
			<img src="img/up.gif" border="0"></a>';
						}
					}
        if (isset($row['filmname'])){
          $row['filmname'] = iconv('windows-1251','utf-8',$row['filmname']);
          $row['filmname'] = urlencode($row['filmname']);
          
          }
        if (isset($row['description'])){
          $row['description'] = urlencode(iconv('windows-1251','utf-8',$row['description']));  
        }
        if (isset($row['image1'])){
            $row['image1'] = 'http://'.$_SERVER['HTTP_HOST'].$row['image1'];
        }
          
      	$this->returned_content .=(isset($_GET['table']) && $_GET['table']=='shedule')?
	'<div id="send'.$row['id'].'" style="display:inline">
      <form method="POST" action="http://dev.tickets.od.ua/tickets/UcinemaTickets" >
      <textarea name="data" style="display:none">'.json_encode($row).'</textarea>
		<button type="submit"  title="Синхронизировать расписание с tickets.od.ua">
			<img src="img/tickets.png" border="0">
		</button>
        </form>
	</div>':'';


					$this->returned_content .= '</span></td></tr>';
					$i++;
				}
			}
			$this->returned_content .= '</table>';
		}

		return $this->returned_content;
	}

	function film_schedule($id)
	{
		$last_id = 0;
		$r = mysql_query('select id,date, time, cost from shedule where nameid ='.$id.' order by date');
		$ret_str = '<div class="prop"><table id="film_schedule" class="film_sched">';
		while($row = mysql_fetch_assoc($r))
		{
			$last_id++;
			$ret_str .= '<tr id="schedTR'.$last_id.'">';
			$ret_str .= '<td>Дата: <div class="right inp"><input style="width: 120px!important;" id="buttonPicker'.$last_id.'" type="text" name="film_date['.$last_id.']" style="width:100px;" value="'.$row['date'].'"></div></td>';
			$ret_str .= '<td>Время: <input id="begintime" type="time" name="film_time['.$last_id.']" style="width:100px;" value="'.$row['time'].'"></td>';
			$ret_str .= '<td>Стоимость: <input type="text" name="film_cost['.$last_id.']" style="width:100px;" value="'.$row['cost'].'"></td>';
			$ret_str .= '<td>Отображать: <input name="film_sched_visible['.$last_id.']" type="radio" style="float:none;" value="1" checked="">Да <input name="film_sched_visible['.$last_id.']" type="radio" style="float:none;" value="0">Нет<input type="hidden" name="sched_id['.$last_id.']" value="'.$row['id'].'"><input type="hidden" name="todelete['.$last_id.']" value="0"></td>';
			$ret_str .= '<td><img border="0" src="img/delete.gif" onclick="delSched('.$last_id.')"></td>';
			$ret_str .= '</tr>';
			$ret_str .= "<script>ini_datepick('buttonPicker'+$last_id)</script>";
		}
		$ret_str .= '</table>';
		$ret_str .= '<input type="hidden" id="film_sched_count" name="film_sched_count" value="'.$last_id.'">';
		$ret_str .= '<input type="button" onclick="addSched('.$last_id.')" value="Добавить расписание" style="width:200px;"></div>';
		return($ret_str);
	}
}

?>