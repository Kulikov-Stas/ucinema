<?php

require_once('modules/base.php');

class config extends base{
	private $returned_content = '';
	
	function  __construct() {
		$this->connectToDb();
	}

	public function preview() {
		// Case for action
		switch (@$_GET['action']) {
			case 'edit':
                            return $this->Edit(@$_POST);
                            break;
                        case 'savepdf':
                            return $this->SavePDF();
                            break;
			default:
                            return $this->ShowExists();
                            break;
		}
	}
	
	protected function ShowExists($type = null) {
		$content = '';
		$content .= '<div id="content" class="config">
		<h2>Конфигурация сайта</h2>
		<form method="POST" action="?mode=config&action=edit">
					';
		$sql = 'SELECT * FROM `config`';
		$res = mysql_query($sql);
		while ($row = mysql_fetch_assoc($res)){
		  if ($row['key'] == 'wyzywig'){
		      $input = '<input class="radio" type="radio" name="wyzywig[]" value="0" '.(($row['value']=='0')?'checked':'').'/> Упрощенный<br />
                        <input class="radio" type="radio" name="wyzywig[]" value="1" '.(($row['value']=='1')?'checked':'').' /> Обычный';
		  }
           else
           $input = '<input type="text" name="'.$row['key'].'" value="'.$row['value'].'">';
           
	    		$content .= '<div class="prop"><div class="left">'.$row['name'].'</div>
                            <div class="right">'.$input.'</div></div>';
		}	
		
		$content .= '
		
		
		<div class="bottom"><input type="submit" class="submit" name="submit_form" value="Сохранить"></div>
				</form>';
                
                $content .= '</div>';
                
		return $content;
	}
	
	protected function Edit($post) {
		$content = '';
        //print_r($post);
		if (isset($post['submit_form'])){
			foreach ($post as $key => $value){
				if ($key != 'submit_form'){					
                     $v =  ($key == 'wyzywig')?$value[0]:$value;
					$sql = 'UPDATE `'.$this->tablename_config.'` SET `value`="'.trim($v).'" WHERE `key`="'.$key.'"';
					if (mysql_query($sql))
						$content .= 'Данные удачно обновлены<br>';
					else 
						$content .= 'Ошибка, разбирайтесь!<br>';	
				}
			}
		}
		echo $content.$this->ShowExists();	
	}
        
        protected function SavePDF()
        {
            $file = '';
            if (!empty($_FILES['uplfile']['tmp_name']))
            {
                $ex = pathinfo($_FILES['uplfile']['name'],PATHINFO_EXTENSION);
                if($ex == 'xls' || $ex == 'txt')
                {
                    $uniqfilename = 'pricelist.'.$ex;
                    if (move_uploaded_file($_FILES['uplfile']['tmp_name'], $_SERVER['DOCUMENT_ROOT'].'/includes/files/'.$uniqfilename))
                    {
                        echo "file is uploaded<br />";
                    }
                }
                else
                {
                    echo '<script>alert("Формат файла неверный!")</script>';
                }
            }

            echo $this->ShowExists();
        }
}

	
?>