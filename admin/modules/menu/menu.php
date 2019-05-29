<?php

require_once('modules/base.php');

if (isset($_GET['table']) && !empty($_GET['table']))
	$_SESSION['table_menu']  = $_GET['table'];

class menu extends base {
	private $returned_content;
	
	function  __construct() {
		$this->connectToDb();
	}
	
    public function preview() {
		switch (@$_GET['action']){
			case 'create' 	: $this->Create(@$_POST['sent']);	break;
			case 'drop' 	: $this->Drop(@$_GET['table']);	break;
			case 'move' 	: $this->Move(@$_GET['table'], $_GET['id'], $_GET['order'], $_GET['move']);	break;
			case 'edit' 	: 
				$this->Edit(@$_GET['table'], !isset($_GET['id']) || $_GET['id'] == '' ? @$_POST['id'] : @$_GET['id'], @$_POST['sent']);break;
			case 'add' 		: 
				$this->Add(@$_GET['table'], !isset($_GET['id']) || $_GET['id'] == '' ? @$_POST['id'] : @$_GET['id'], @$_POST['sent']);break;	
		//	case 'open' 	: $this->Open(empty($_GET['id']) ? @$_POST['id'] : $_GET['id'], @$_POST['sent']);break;	
			case 'delete' 	: $this->Delete($_GET['table'], $_GET['id']); break;
			default			: return $this->ShowExists();break;	
		}
	}
	
	public function AJAX($AJAXaction) {
		switch ($AJAXaction) {
			case 'change' 		: $this->Change($_GET['table'], $_GET['id'], $_GET['status']); break;
			case 'open'   		: $this->Open($_GET['table'], $_GET['id']); break;
			case 'close'  		: $this->Close(); break;
		//	case 'delete' 		: $this->Delete($_GET['table'], $_GET['id']); break;
			case 'active' 		: $this->ChangeAllowed($_GET['table'], $_GET['id'], $_GET['visible']);break;
			case 'changeTable' 	: $this->ChangeTable($_GET['value']);break;
			default		  		:	break;
		}
	}
	
	// удаляет таблицу из меню     ==============================================----------------
	private function Drop($table){
		$content = '';
		if (!empty($table) && $table != 'defmenu'){
			$res1 = mysql_query('DELETE FROM `'.$this->tablename_basemenu.'` WHERE `menuname`="'.$table.'"');
    		$res2 = mysql_query('DROP TABLE `'.$table.'`');
    		
    		if ($res1 && $res2){
    			$content .= 'Вы успешно удалили таблицу - '.$table;
    			unset($_SESSION['table_menu']);
    		}
    		else	
    			$content .= 'Таблица не удалилась, разбирайтесь';
    			
			$content .= $this->ShowExists();
		}
		
		echo $content;	
	}

	// меняем вьюху в зависимости от выбора таблицы ==--------------------------------------------
	private function ChangeTable($value){
		$content =
<<<CHANGE
		<select name="lview" style="width:420px;">
CHANGE;
		$path = '../design/tables/'.$value;
		if (file_exists($path))	{
			$pdir = opendir($path);
			while (($file = readdir($pdir)) !== false){
				if (is_file($path.'/'.$file)){
					$content .= '<option value="'.$file.'">'.$file.'</option>';
				}
			}
			closedir($pdir);
		}
			
		$content .=
<<<CHANGE
			</select>
CHANGE;
				
		echo $content;
	}

	// создание меню =================================--------------------------------------------
    private function Create($sent = '') {
    	$content = '';
    	if ($sent == 1) {
    		$name = trim($_POST['name']);
    		$rus = trim($_POST['rus']);
    		
    		$res1 = mysql_query('INSERT INTO `'.$this->tablename_basemenu.'` (`menuname`, `rus`) VALUES ("'.$name.'", "'.$rus.'")');
    		$res2 = mysql_query('CREATE TABLE `'.$name.'` 
		    		(`id` INT(11) NOT NULL auto_increment, 
		    		`name` CHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
					`rus` CHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, 
					`sub` INT(11) NOT NULL, 
					`visible` TINYINT(1) NOT NULL DEFAULT 1, 
					`link` CHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci,
					`lview` CHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci,
					`lmain` CHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci,
					`ltable` CHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci,
					`lrecord` CHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci,
					`title` CHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci,
					`keywords` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci,
					`description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci,
					`order` INT(11) NOT NULL, 
					PRIMARY KEY (id))ENGINE='.$this->engine.' DEFAULT CHARSET=utf8');
    		
    		if ($res1 && $res2){
				
    			$path = '../design/menu/'.$name;
				if (!is_dir($path))	{
					mkdir($path);
					
					$menu0_file = fopen($path.'/menu0_file.htm', 'w');
					$str = '<ul class="inner_menu_block"><li><a href="[LINK]">[CAPTION]</a></li></ul>';
					fwrite($menu0_file, $str);
					fclose($menu0_file);
					
					$menu0_file_selected = fopen($path.'/menu0_file_selected.htm', 'w');
					$str = '<ul class="inner_menu_block"><li><a href="[LINK]" style="color:#663300;text-decoration:none">[CAPTION]</a></li></ul>';
					fwrite($menu0_file_selected, $str);
					fclose($menu0_file_selected);
					
					$menu0_folder = fopen($path.'/menu0_folder.htm', 'w');
					$str = '<li><a href="[LINK]">[CAPTION]</a></li>';
					fwrite($menu0_folder, $str);
					fclose($menu0_folder);
					
					$menu0_folder_selected = fopen($path.'/menu0_folder_selected.htm', 'w');
					$str = '<li class="left_menu_block_a"><a href="[LINK]">[CAPTION]</a></li>';
					fwrite($menu0_folder_selected, $str);
					fclose($menu0_folder_selected);
				}
				
				$_SESSION['table_menu'] = $name;
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
<div id="_PopUp" >
	<div align="right" style="margin-right:2px;"><a href="javascript:popuphide('menu');"><img class="close" src="img/icon-close.png" alt=""></a></div>
	<form name="createForm" method="POST" action="?mode=menu&action=create">
	<input name="sent" type="hidden" value="1">
	<h1>Создание меню</h1>
	<div class="prop">
		<div class="left">Имя таблицы в БД:&nbsp;*</div>
		<div class="right inp"><input name="name" type="text" style="width:420px;"></div>
	</div>
	
	<div class="prop">
		<div class="left">Имя таблицы в UCMS:&nbsp;*</div>
		<div class="right inp"><input name="rus" type="text" style="width:420px;"></div>
	</div>
	<div class="bottom"><input type="submit" class="submit" value="Создать"></div>
	</form>
</div>
<script language="JavaScript" type="text/javascript">popup('_PopUp', '300', '150');</script>
CREATE;
		}
    	
    	echo $content;
	}
	
	// удаление ======================================--------------------------------------------
    private function Delete($table, $id) {
    	$content = '';
    	$count_sub = mysql_result(mysql_query('SELECT COUNT(*) FROM `'.$table.'` WHERE `sub`='.$id), 0);
    	if ($count_sub != 0){
    		$content .= '<script>alert("Вы не имеете право удалять пункт меню, в котором есть подпункты!!!")</script>';
    	}
    	else{
	    	$del_order = mysql_result(mysql_query('SELECT `order` FROM `'.$table.'` WHERE `id`='.$id), 0);
	    	$del_sub = mysql_result(mysql_query('SELECT `sub` FROM `'.$table.'` WHERE `id`='.$id), 0);
	    	mysql_query('DELETE FROM `'.$table.'` WHERE id='.$id);
	    	
	    	$max_order = mysql_result(mysql_query('SELECT MAX(`order`) FROM `'.$table.'` WHERE `sub`='.$del_sub), 0);
	    	for ($i = $del_order; $i < $max_order; $i++){
	    	   	$next_id = mysql_result(mysql_query('SELECT `id` FROM `'.$table.'` WHERE `order`='.($i + 1).' AND `sub`='.$del_sub), 0);
		    	mysql_query('UPDATE `'.$table.'` SET `order`='.$i.' WHERE `id`='.$next_id);
	    	}
    	}
       	echo $content.$this->ShowExists();
	}
	
	// меням итем меню в зависимости от открытия или закрытия подменюшек -------------------------
	private function Change($table, $id, $status) {
		$content = '';
		$sql  = 'SELECT *, ';
		$sql .= '(SELECT MAX(`order`) FROM `'.$table.'` WHERE `sub`=(SELECT `sub` FROM `'.$table.'` WHERE id='.$id.')) AS max_order, ';
		$sql .= '(SELECT MIN(`order`) FROM `'.$table.'` WHERE `sub`=(SELECT `sub` FROM `'.$table.'` WHERE id='.$id.')) AS min_order '; 
		$sql .= 'FROM `'.$table.'` ';
		$sql .= 'WHERE `id`='.$id.'';	
	//	echo $sql;
		$res = mysql_query($sql);
		$row = mysql_fetch_assoc($res);

		$content .= '<td align="center" width="3%"><span>'.$row['order'].'</span></td>
					<td align="center" width="3%"><span>'.$row['id'].'</span></td>
					<td align="center" width="11%"><span>';
		if ($status == 'open')							
			$content .= '<a style="color:#3333FF" href="javascript:accordCat(\''.$table.'\', \''.$row['id'].'\', \'close\')">'.$row['name'].'</a>';
		if ($status == 'close')							
			$content .= '<a style="color:#3333FF" href="javascript:accordCat(\''.$table.'\', \''.$row['id'].'\', \'open\')">'.$row['name'].'</a>';
												
		$content .= '   </span></td>
					<td align="center" width="11%"><span>'.$row['rus'].'</span></td>
					<td align="center" width="11%"><span>'.$row['link'].'</span></td>
					<td align="center" width="11%"><span>'.$row['lview'].'</span></td>
					<td align="center" width="11%"><span>'.$row['lmain'].'</span></td>
					<td align="center" width="11%"><span>'.$row['ltable'].'</span></td>
					<td align="center" width="11%"><span>'.$row['lrecord'].'</span></td>
					<td align="center" width="17%"><span>';
		$content .=
'<a href="?mode=menu&table='.$table.'&action=add&id='.$row['id'].'" title="Добавить подпункт"><img src="img/add.gif" border="0"></a>';
		$content .= '
<a href="?mode=menu&table='.$table.'&action=edit&id='.$row['id'].'" title="Редактировать"><img src="img/edit.gif" border="0"></a>';

		if ($row['visible'] == 1) {
				$content .=
'<div id="idallow'.$row['id'].'" style="display:inline">
	<a href="javascript:changeAllowed(\'menu\', \''.$table.'\', \''.$row['id'].'\', \'disallowed\')" title="Запретить к показу">
		<img src="img/allowed.gif" border="0"></a></div>';
		}
		if ($row['visible'] == 0) {
				$content .=
'<div id="idallow'.$row['id'].'" style="display:inline">
	<a href="javascript:changeAllowed(\'menu\', \''.$table.'\', \''.$row['id'].'\', \'allowed\')" title="Разрешить к показу">
		<img src="img/disallowed.gif" border="0"></a></div>';
		}
									
		if ($row['min_order'] != $row['max_order']){
			switch ($row['order']) {
				case $row['min_order']:
					$content .=
'<a href="?mode=menu&table='.$table.'&action=move&id='.$row['id'].'&order='.$row['order'].'&move=down" title="Переместить вниз">
	<img src="img/down.gif" border="0"></a>';
					break;
				case $row['max_order']:
					$content .=
'<a href="?mode=menu&table='.$table.'&action=move&id='.$row['id'].'&order='.$row['order'].'&move=up" title="Переместить вверх">
	<img src="img/up.gif" border="0"></a>';
					break;
				default:
					$content .= '
	<a href="?mode=menu&table='.$table.'&action=move&id='.$row['id'].'&order='.$row['order'].'&move=down" title="Переместить вниз">
		<img src="img/down.gif" border="0"></a>
	<a href="?mode=menu&table='.$table.'&action=move&id='.$row['id'].'&order='.$row['order'].'&move=up" title="Переместить вверх">
		<img src="img/up.gif" border="0"></a>';
																	
			}
		}
		
		$content .= '</span></td>';
		
		echo cp1251_utf8($content);
	}
	
	// открываем подменю =============================--------------------------------------------
	private function Open($table, $id) {
		$content = '<td colspan="10" style="padding:0">
						<table class="ajax_table" width="100%" height="100%" cellpadding="5" cellspacing="0" border="0">';
		$sql = 'SELECT *, ';
		$sql .= '(SELECT MAX(`order`) FROM `'.$table.'` WHERE `sub`='.$id.') AS max_order, ';
		$sql .= '(SELECT MIN(`order`) FROM `'.$table.'` WHERE `sub`='.$id.') AS min_order '; 
		$sql .= 'FROM `'.$table.'` ';
		$sql .= 'WHERE sub='.$id.' ORDER BY `order`';
		$res = mysql_query($sql);
	
		$i = 1;
		while ($row = mysql_fetch_assoc($res)){
			$count_sub = mysql_result(mysql_query('SELECT COUNT(*) FROM `'.$table.'` WHERE sub='.$row['id']), 0);
			$content .= '<tr id="id'.$row['id'].'">
							<td align="center" width="3%"><span>'.$i.'</span></td>
							<td align="center" width="3%"><span>'.$row['id'].'</span></td>
							<td align="center" width="11%"><span>';
			if ($count_sub > 0)
				$content .= '<span>
<a href="javascript:accordCat(\''.$table.'\', \''.$row['id'].'\', \'open\')" style="color:#3333ff;">'.$row['name'].'</a>';
			else 
				$content .= '<span>'.$row['name'];
			$content .= '	</span></td>
							<td align="center" width="11%"><span>'.$row['rus'].'</span></td>
							<td align="center" width="11%"><span>'.$row['link'].'</span></td>
							<td align="center" width="11%"><span>'.$row['lview'].'</span></td>
							<td align="center" width="11%"><span>'.$row['lmain'].'</span></td>
							<td align="center" width="11%"><span>'.$row['ltable'].'</span></td>
							<td align="center" width="11%"><span>'.$row['lrecord'].'</span></td>
							<td align="center" width="17%" style="border-right:none;"><span>';
			if ($count_sub == 0)
				$content .=
'<a href="?mode=menu&table='.$table.'&action=delete&id='.$row['id'].'" onclick="return confirm(\'Удалить элемент?\')" title="Удалить"><img src="img/delete.gif" border="0"></a>';
			$content .=
'<a href="?mode=menu&table='.$table.'&action=add&id='.$row['id'].'" title="Добавить подпункт">
		<img src="img/add.gif" border="0"></a>';
			$content .=
'<a href="?mode=menu&table='.$table.'&action=edit&id='.$row['id'].'" title="Редактировать">
	<img src="img/edit.gif" border="0"></a>';
			if ($row['visible'] == 1) 
				$content .=
'<div id="idallow'.$row['id'].'" style="display:inline">
	<a href="javascript:changeAllowed(\'menu\', \''.$table.'\', \''.$row['id'].'\', \'disallowed\')" title="Запретить к показу">
		<img src="img/allowed.gif" border="0"></a></div>';
									
			if ($row['visible'] == 0) 
				$content .=
'<div id="idallow'.$row['id'].'" style="display:inline">
	<a href="javascript:changeAllowed(\'menu\', \''.$table.'\', \''.$row['id'].'\', \'allowed\')" title="Разрешить к показу">
		<img src="img/disallowed.gif" border="0"></a></div>';

			if ($row['min_order'] != $row['max_order']){
				switch ($row['order']) {
					case $row['min_order']:
						$content .=
	'<a href="?mode=menu&table='.$table.'&action=move&id='.$row['id'].'&order='.$row['order'].'&move=down" title="Переместить вниз">
		<img src="img/down.gif" border="0"></a>';
						break;
					case $row['max_order']:
						$content .=
	'<a href="?mode=menu&table='.$table.'&action=move&id='.$row['id'].'&order='.$row['order'].'&move=up" title="Переместить вверх">
		<img src="img/up.gif" border="0"></a>';
						break;
					default:
						$content .= '
	<a href="?mode=menu&table='.$table.'&action=move&id='.$row['id'].'&order='.$row['order'].'&move=down" title="Переместить вниз">
		<img src="img/down.gif" border="0"></a>
	<a href="?mode=menu&table='.$table.'&action=move&id='.$row['id'].'&order='.$row['order'].'&move=up" title="Переместить вверх">
		<img src="img/up.gif" border="0"></a>';
				}
			}		
			$content .=		'</span></td>
						</tr>';
			if ($count_sub > 0)
				$content .= '<tr id="idsub'.$row['id'].'"></tr>';
			$i++;
		}
		
		$content .= '</table></td>';
		echo cp1251_utf8($content);		
	}
	
	// закрываем подменю =============================--------------------------------------------
	private function Close() {return '';}
	
	// скрыть/показать ======================================-------------------------------------
	private function ChangeAllowed($table, $id, $value) {
		$content = '';
		if ($value == 'allowed') {
			if (mysql_query('UPDATE `'.$table.'` SET `visible`=1 WHERE id='.$id))
				$content .= '<a href="javascript:changeAllowed(\'menu\', \''.$table.'\', \''.$id.'\', \'disallowed\')" title="Запретить к показу">
					<img src="img/allowed.gif" border="0"></a>';
		}
		elseif ($value == 'disallowed') {
			if (mysql_query('UPDATE `'.$table.'` SET `visible`=0 WHERE id='.$id))
				$content .= '<a href="javascript:changeAllowed(\'menu\', \''.$table.'\', \''.$id.'\', \'allowed\')" title="Разрешить к показу">
					<img src="img/disallowed.gif" border="0"></a>';
		}
		
		echo cp1251_utf8($content);
	}
	
	// перемещение ======================================-----------------------------------------
    private function Move($table, $id, $order, $move){
    	if (isset($move) && $move == 'down'){
			mysql_query('UPDATE `'.$table.'` SET `order` = `order` - 1 WHERE `order` = '.($order + 1));
			mysql_query('UPDATE `'.$table.'` SET `order` = `order` + 1 WHERE `id` = '.$id);
		}

		if (isset($move) && $move == 'up'){
			mysql_query('UPDATE `'.$table.'` SET `order` = `order` + 1 WHERE `order` = '.($order - 1));
			mysql_query('UPDATE `'.$table.'` SET `order` = `order` - 1 WHERE `id` = '.$id);
		}
		echo $this->ShowExists();
	}
	
	// редактировать ======================================---------------------------------------
	private function Edit($table, $id, $sent = 0) {
		$content = '';
		if ($sent == 1) {
			$name = trim($_POST['name']);
			$rus  = trim($_POST['rus']);
			$link = trim($_POST['link']);
			$ltable = trim($_POST['ltable']);
			$lview = trim($_POST['lview']);
			$lmain = trim($_POST['lmain']);
			$lrecord = trim($_POST['lrecord']);
			$title = trim($_POST['title']);
			$keywords = trim($_POST['keywords']);
			$description = trim($_POST['description']);
			$error = false;
			if (empty($name) || !preg_match('/^([a-z0-9_])+$/i', $name)) ///^([a-z_])*([a-z])$/i
				$error = true;
			
			if (empty($rus))
				$error = true;
						
			if ($error){
				$content = 'Заполните обязательные поля';
			}
			else{
			
				mysql_query('UPDATE `'.$table.'` 
							SET `name`="'.$name.'", `rus`="'.$rus.'", 
							`link`="'.$link.'", `ltable`="'.$ltable.'", 
							`lmain`="'.$lmain.'", `lview`="'.$lview.'",
							`lrecord`="'.$lrecord.'", `title`="'.$title.'",
							`keywords`="'.$keywords.'", `description`="'.$description.'"  
							WHERE id='.$_POST['id']);
				$content .= $this->ShowExists();
				unset($_POST);
				$sent = 0;
			}
		}
		else {
			$row = mysql_fetch_assoc(mysql_query('SELECT * FROM `'.$table.'` WHERE id='.$id));
			$content .=
<<<EDIT
<div id="_PopUp" >
	<div align="right" style="margin-right:2px;"><a href="javascript:popuphide('menu');"><img class="close" src="img/icon-close.png" alt=""></a></div>
	<form name="editForm" method="POST" action="?mode=menu&table={$table}&action=edit">
	<input name="sent" type="hidden" value="1">
	<input name="id" type="hidden" value="{$row['id']}">
	<h1>Редактирование</h1>
		<div class="prop">
			<div class="left">Name:&nbsp;</div>
			<div class="right inp"><input name="name" type="text" value="{$row['name']}"></div>
		</div>
		<div class="prop">
			<div class="left">Rus:&nbsp;</div>
			<div class="right inp"><input name="rus" type="text" style="width:420px;" value="{$row['rus']}"></div>
		</div>
		<div class="prop">
			<div class="left">Link:&nbsp;</div>
			<div class="right inp"><input name="link" type="text" value="{$row['link']}"></div>
		</div>
		<div class="prop">
			<div class="left">Таблица:&nbsp;</div>
			<div class="right inp"><select name="ltable" style="width:420px;" onchange="changeTableInMenu('{$table}', this)">
EDIT;
		$sql_basetable = 'SELECT `tablename`, `rus` FROM `basetable`';
		$res_basetable = mysql_query($sql_basetable);
		$first_basetable = '';
		while ($row_basetable = mysql_fetch_assoc($res_basetable)){
			if (empty($first_basetable))
				$first_basetable = $row_basetable['tablename'];
			if ($row_basetable['tablename'] == $row['ltable']){	
				$content .= '<option value="'.$row_basetable['tablename'].'" selected>'.$row_basetable['rus'].'</option>';
				$first_basetable = $row_basetable['tablename'];
			}	
			else 
				$content .= '<option value="'.$row_basetable['tablename'].'">'.$row_basetable['rus'].'</option>';	
			
		}	
			
		$content .=
<<<EDIT
			</select></div>
		</div>
		<div class="prop">
			<div class="left">View:&nbsp;</div>
			<div class="right inp"><select name="lview">
EDIT;
		$path = '../design/tables/'.$first_basetable;
		if (file_exists($path))	{
			$pdir = opendir($path);
			while (($file = readdir($pdir)) !== false){
				if (is_file($path.'/'.$file)){
					if ($row['lview'] == $file)	
						$content .= '<option value="'.$file.'" selected>'.$file.'</option>';
					else 
						$content .= '<option value="'.$file.'">'.$file.'</option>';	
				}
			}
			closedir($pdir);
		}
			
		$content .=
<<<EDIT
			</select>
		</div>
	</div>
	<div class="prop">
			<div class="left">Дизайн:&nbsp;</div>
			<div class="right inp"><select name="lmain">
EDIT;
		$path = '../design';
		if (file_exists($path))	{
			$pdir = opendir($path);
			while (($file = readdir($pdir)) !== false){
				if (is_file($path.'/'.$file)){
					if ($row['lmain'] == $file)
						$content .= '<option value="'.$file.'" selected>'.$file.'</option>';
					else 
						$content .= '<option value="'.$file.'">'.$file.'</option>';
				}
			}
			closedir($pdir);
		}
			
		$content .=
<<<EDIT
			</select></div>
		</div>
		<div class="prop">
			<div class="left">Запись:&nbsp;</div>
			<div class="right inp"><input name="lrecord" type="text" style="width:420px;" value="{$row['lrecord']}"></div>
		</div>
		<div class="prop">
			<div class="left">Title:&nbsp;</div>
			<div class="right inp"><input name="title" type="text" style="width:420px;" value="{$row['title']}"></div>
		</div>
		<div class="prop">
			<div class="left">Keywords:&nbsp;</div>
			<div class="right text"><textarea name="keywords" cols="50">{$row['keywords']}</textarea></div>
		</div>
		<div class="prop">
			<div class="left">Description:&nbsp;</div>
			<div class="right text"><textarea name="description" cols="50">{$row['description']}</textarea></div>
		</div>
		<div class="bottom"><input type="submit" class="submit" value="Редактировать"></div>
		
	</form>
</div>
<script language="JavaScript" type="text/javascript">popup('_PopUp', '300', '150');</script>
EDIT;
		}
		echo $content;
	}
	
	// добавить ======================================--------------------------------------------
	private function Add($table, $id, $sent = 0) {
		$content = '';
		if ($sent == 1) {
			
			$name = trim($_POST['name']);
			$rus  = trim($_POST['rus']);
			$lview = trim($_POST['lview']);
			$lmain = trim($_POST['lmain']);
			$ltable = trim($_POST['ltable']);
			$lrecord = trim($_POST['lrecord']);
			$title = trim($_POST['title']);
			$keywords = trim($_POST['keywords']);
			$description = trim($_POST['description']);
		//	var_dump($_POST);
			$error = false;
			if (empty($name) || !preg_match('/^([a-z0-9_])+$/i', $name))
				$error = true;
			
			if (empty($rus))
				$error = true;
						
			if ($error){
				$content = 'Заполните обязательные поля';
			}
			else{
				if ($id != ''){
					if ($id != 0)
						$link = mysql_result(mysql_query('SELECT `link` FROM `'.$table.'` WHERE `id`='.$id), 0);
					$max_order = mysql_result(mysql_query('SELECT MAX(`order`) FROM `'.$table.'` WHERE `sub`='.$id), 0);
					$max_order++;
					
					if (empty($max_order)) $max_order = 1;
					
					if ($id == 0)mysql_query('INSERT INTO `'.$table.'` 
						(`name`, `rus`, `sub`, `visible`, `link`, `lview`, `lmain`, `ltable`, `lrecord`, `title`, `keywords`, `description`, `order`)
								VALUES ("'.$name.'", "'.$rus.'", '.$id.', 1, "/'.$name.'/", "'.$lview.'", "'.$lmain.'", "'.$ltable.'", "'.$lrecord.'", "'.$title.'", "'.$keywords.'", "'.$description.'", '.$max_order.')');	
					
					else 
						mysql_query('INSERT INTO `'.$table.'` 
						(`name`, `rus`, `sub`, `visible`, `link`, `lview`, `lmain`, `ltable`, `lrecord`, `title`, `keywords`, `description`, `order`)
								VALUES ("'.$name.'", "'.$rus.'", '.$id.', 1, "'.$link.$name.'/", "'.$lview.'", "'.$lmain.'", "'.$ltable.'", "'.$lrecord.'", "'.$title.'", "'.$keywords.'", "'.$description.'", '.$max_order.')');	
				}
			}
			unset($_POST);
			$sent = 0;
			
			$content .= $this->ShowExists();
		}
		else {
			//$row = mysql_fetch_assoc(mysql_query('SELECT  FROM `'.$table.'` WHERE id='.$id));
			$content .=
<<<ADD
<div id="_PopUp" >
	<div align="right" style="margin-right:2px;"><a href="javascript:popuphide('menu');"><img class="close" src="img/icon-close.png" alt=""></a></div>
	<form name="addForm" method="POST" action="?mode=menu&table={$table}&action=add">
	<input name="sent" type="hidden" value="1">
	<input name="id" type="hidden" value="{$id}">
	<h1>Добавить подпункт</h1>
	<div class="prop">
		<div class="left">Name:(eng)&nbsp;*</div>
		<div class="right inp"><input name="name" type="text" style="width:420px;"></div>
	</div>
	<div class="prop">
		<div class="left">Rus:&nbsp;*</div>
		<div class="right inp"><input name="rus" type="text" style="width:420px;"></div>
	</div>
	<div class="prop">
		<div class="left">Таблица:&nbsp;</div>
		<div class="right inp"><select name="ltable" style="width:420px;" onchange="changeTableInMenu('{$table}', this)">
ADD;
		$sql_basetable = 'SELECT `tablename`, `rus` FROM `basetable`';
		$res_basetable = mysql_query($sql_basetable);
		$first_basetable = '';
		while ($row_basetable = mysql_fetch_assoc($res_basetable)){
			if (empty($first_basetable))
				$first_basetable = $row_basetable['tablename'];
			$content .= '<option value="'.$row_basetable['tablename'].'">'.$row_basetable['rus'].'</option>';
			
		}	
			
		$content .=
<<<ADD
			</select>
		</div>
	</div>
	<div class="prop">
		<div class="left">View:&nbsp;</div>
		<div class="right inp"><select name="lview" style="width:420px;">
ADD;
		$path = '../design/tables/'.$first_basetable;
		if (file_exists($path))	{
			$pdir = opendir($path);
			while (($file = readdir($pdir)) !== false){
				if (is_file($path.'/'.$file)){
					$content .= '<option value="'.$file.'">'.$file.'</option>';
				}
			}
			closedir($pdir);
		}
			
		$content .=
<<<ADD
			</select>
		</div>
	</div>
	<div class="prop">
		<div class="left">Дизайн:&nbsp;</div>
		<div class="right inp"><select name="lmain" style="width:420px;">
ADD;
		$path = '../design';
		if (file_exists($path))	{
			$pdir = opendir($path);
			while (($file = readdir($pdir)) !== false){
				if (is_file($path.'/'.$file)){
					$content .= '<option value="'.$file.'">'.$file.'</option>';
				}
			}
			closedir($pdir);
		}
			
		$content .=
<<<ADD
			</select></div>
	</div>
	<div class="prop">
		<div class="left">Запись:&nbsp;</div>
		<div class="right inp"><input name="lrecord" type="text" style="width:420px;"></div>
	</div>
	<div class="prop">
		<div class="left">Title:&nbsp;</div>
		<div class="right inp"><input name="title" type="text" style="width:420px;"></div>
	</div>
	<div class="prop">
		<div class="left">Keywords:&nbsp;</div>
		<div class="right text"><textarea name="keywords" cols="50"></textarea></div>
	</div>
	<div class="prop">
		<div class="left">Description:&nbsp;</div>
		<div class="right text"><textarea name="description" cols="50"></textarea></div>
	</div>
	
	<div class="bottom"><input type="submit" class="submit" value="Добавить"></div>
	</form>
</div>
<script language="JavaScript" type="text/javascript">popup('_PopUp', '300', '150');</script>
ADD;
		}
		echo $content;
	}
	
	/**
	 * Show exists 
	 *
	 * @return string
	 * @author Pavel Golovenko
	 */
	protected function ShowExists($type = null) {
		if (isset($_SESSION['table_menu']) && !empty($_SESSION['table_menu']))
			$table = $_SESSION['table_menu'];
		else 
			$table = 'defmenu';
		$content =
<<<CONTENT
<div id="content" class="menu">
<h2>Управление меню</h2>
<div class="prop">
	<div class="left">Текущее меню</div>
	<div class="right inp">
	<form name="selectTableForm" >
								<input type="hidden" name="mode" value="menu">
								<select name="table" style="width:200px" onchange="javascript:document.forms['selectTableForm'].submit()">
CONTENT;
		$sql = 'SELECT * FROM `'.$this->tablename_basemenu.'`';
		$res = mysql_query($sql);
		while ($row = mysql_fetch_assoc($res)){
			if ($row['menuname'] == $table)
				$content .=	'<option value="'.$row['menuname'].'" selected>'.$row['rus'].'</option>';
			else	
				$content .=	'<option value="'.$row['menuname'].'">'.$row['rus'].'</option>';
		}	
		$content .=
<<<CONTENT
							
								</select>
								<!--a href="javascript:document.forms['selectTableForm'].submit()">
									<img height="16" width="16" border="0" title="Выбрать таблицу меню" src="img/icons/m_menu.gif">
								</a-->
							</form>
		<a class="add_razdel add_one_more" href="?mode=menu&action=create">Создать меню</a>
		<a class="del_razdel remove_one" href="?mode=menu&table={$table}&action=drop" onclick="return confirm('Удалить меню - {$table}?')">Удалить меню</a>
	</div>
</div>
<a class="add_rec add_one_more" href="?mode=menu&table={$table}&action=add&id=0">Добавить запись</a>
	<table class="menu" width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td valign="top">{$this->generate_by_type($table)}</td>
		</tr>	
	</table>	
</div>	
CONTENT;
		
		return $content;
	}

	protected function generate_by_type($table) {
		if (!empty($table)){
			$this->returned_content =
<<<CONTENT
		<table class="main-table" width="100%" cellpadding="5" cellspacing="0" border="1" style="border-collapse:collapse">
			<tr class="first-row">
				<th align="center" width="3%"><span style="color:green;font-weight:bold">№</span></th>
				<th align="center" width="3%"><span style="color:green;font-weight:bold">id</span></th>
				<th align="center" width="11%"><span style="color:green;font-weight:bold">name</span></th>
				<th align="center" width="11%"><span style="color:green;font-weight:bold">rus</span></th>
				<th align="center" width="11%"><span style="color:green;font-weight:bold">link</span></th>
				<th align="center" width="11%"><span style="color:green;font-weight:bold">lview</span></th>
				<th align="center" width="11%"><span style="color:green;font-weight:bold">lmain</span></th>
				<th align="center" width="11%"><span style="color:green;font-weight:bold">ltable</span></th>
				<th align="center" width="11%"><span style="color:green;font-weight:bold">lrecord</span></th>
				<th align="center" width="17%"><span style="color:green;font-weight:bold">Операции</span></th>
			</tr>
CONTENT;
			$sql  = 'SELECT *, ';
			$sql .= '(SELECT MAX(`order`) FROM `'.$table.'` WHERE `sub`=0) AS max_order, ';
			$sql .= '(SELECT MIN(`order`) FROM `'.$table.'` WHERE `sub`=0) AS min_order '; 
			$sql .= 'FROM `'.$table.'` ';
		//	$sql .= 'LEFT JOIN `catcar` ON `'.$table.'`.`title` = `catcar`.`id` '; 
			$sql .= 'WHERE `sub`=0 ORDER BY `order`';	
		//	echo $sql;
			$res = mysql_query($sql);
			if (mysql_num_rows($res) > 0){
				while ($row = mysql_fetch_assoc($res)){
					$count_sub = mysql_result(mysql_query('SELECT COUNT(*) FROM `'.$table.'` WHERE sub='.$row['id']), 0);
											
					$this->returned_content .= '<tr id="id'.$row['id'].'">
									<td align="center"><span>'.$row['order'].'</span></td>
									<td align="center"><span>'.$row['id'].'</span></td>
									<td align="center"><span>';
											
					$this->returned_content .= ($count_sub > 0) ? '<a href="javascript:accordCat(\''.$table.'\', \''.$row['id'].'\', \'open\')" style="color:#3333ff;">'.$row['name'].'</a>' : $row['name'];	
														
					$this->returned_content .= '</span></td>
								<td align="center"><span>'.$row['rus'].'</span></td>
								<td align="center"><span>'.$row['link'].'</span></td>
								<td align="center"><span>'.$row['lview'].'</span></td>
								<td align="center"><span>'.$row['lmain'].'</span></td>
								<td align="center"><span>'.$row['ltable'].'</span></td>
								<td align="center"><span>'.$row['lrecord'].'</span></td>
								<td align="center"><span>';
					if ($count_sub == 0)
						$this->returned_content .=
	'<a href="?mode=menu&table='.$table.'&action=delete&id='.$row['id'].'" onclick="return confirm(\'Удалить элемент?\')" title="Удалить"><img src="img/delete.gif" border="0"></a>';
					$this->returned_content .=
	'<a href="?mode=menu&table='.$table.'&action=add&id='.$row['id'].'" title="Добавить подпункт">
		<img src="img/add.gif" border="0"></a>';
					$this->returned_content .=
	'<a href="?mode=menu&table='.$table.'&action=edit&id='.$row['id'].'" title="Редактировать">
		<img src="img/edit.gif" border="0"></a>';
	
					if ($row['visible'] == 1) 
						$this->returned_content .=
	'<div id="idallow'.$row['id'].'" style="display:inline">
		<a href="javascript:changeAllowed(\'menu\', \''.$table.'\', \''.$row['id'].'\', \'disallowed\')" title="Запретить к показу">
			<img src="img/allowed.gif" border="0">
		</a>
	</div>';
										
					if ($row['visible'] == 0) 
						$this->returned_content .=
	'<div id="idallow'.$row['id'].'" style="display:inline">
		<a href="javascript:changeAllowed(\'menu\', \''.$table.'\', \''.$row['id'].'\', \'allowed\')" title="Разрешить к показу">
			<img src="img/disallowed.gif" border="0">
		</a>
	</div>';
				
					if ($row['min_order'] != $row['max_order']){
						switch ($row['order']) {
							case $row['min_order']:
								$this->returned_content .=
	'<a href="?mode=menu&table='.$table.'&action=move&id='.$row['id'].'&order='.$row['order'].'&move=down" title="Переместить вниз">
		<img src="img/down.gif" border="0"></a>';
								break;
							case $row['max_order']:
								$this->returned_content .=
	'<a href="?mode=menu&table='.$table.'&action=move&id='.$row['id'].'&order='.$row['order'].'&move=up" title="Переместить вверх">
		<img src="img/up.gif" border="0"></a>';
								break;
							default:
								$this->returned_content .= '
	<a href="?mode=menu&table='.$table.'&action=move&id='.$row['id'].'&order='.$row['order'].'&move=down" title="Переместить вниз">
		<img src="img/down.gif" border="0"></a>
	<a href="?mode=menu&table='.$table.'&action=move&id='.$row['id'].'&order='.$row['order'].'&move=up" title="Переместить вверх">
		<img src="img/up.gif" border="0"></a>';
						}
					}
					$this->returned_content .= '</span></td></tr>';
											
					if ($count_sub > 0)
						$this->returned_content .= '<tr id="idsub'.$row['id'].'"></tr>';
											
				}
			}
			$this->returned_content .= '</table>';
		}
			
		return $this->returned_content;
	}							
}							
			
?>