<?php
//require_once('./design/tables/_for_php/for_lang.php');
class Keys {
	private static $_instance = null;

    public static function getInstance(){
        if (!self::$_instance instanceof Keys)
            self::$_instance = new Keys();
		
        return self::$_instance;
    }
    
	public function title_keywords_description($curfile, $table, $linkid){
	//	global $glink3;
		if (!empty($linkid)){
			$sql = 'SELECT title, keywords, description FROM `'.$table.'` WHERE `linkid` = '.$linkid.' LIMIT 1';
			$res = mysql_query($sql);
			$row = mysql_fetch_assoc($res);
							
			$curfile = str_replace('[KEYWORDS]', strip_tags(html_entity_decode($row['keywords'])), $curfile);
			$curfile = str_replace('[DESCRIPTION]', strip_tags(html_entity_decode($row['description'])), $curfile);
			$curfile = str_replace('[TITLE]', strip_tags(html_entity_decode($row['title'])), $curfile);
		}
		return $curfile;
	}
	
	public function tkd4right($curfile){
		global $glink1;
		global $gpage;
		if (!empty($gpage) && $gpage == 'right' && !empty($glink1)){
			$sql = 'SELECT title, keywords, description FROM `right` WHERE `linkid` = '.$glink1.' LIMIT 1';
			$res = mysql_query($sql);
			$row = mysql_fetch_assoc($res);
							
			$curfile = str_replace('[KEYWORDS]', strip_tags(html_entity_decode($row['keywords'])), $curfile);
			$curfile = str_replace('[DESCRIPTION]', strip_tags(html_entity_decode($row['description'])), $curfile);
			$curfile = str_replace('[TITLE]', strip_tags(html_entity_decode($row['title'])), $curfile);
		}
		return $curfile;
	}
}
?>