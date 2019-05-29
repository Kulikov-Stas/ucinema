<?php

require_once('config.php');
	
class base {
	private $mysql_charset	= 'cp1251';
	
	protected $engine		= 'MyISAM';//InnoDB 
	
	// Define tablenames
	protected $tablename_mailer			= 'mailer';
	protected $tablename_ausers			= 'ausers';
	protected $tablename_basemenu			= 'basemenu';
	protected $tablename_baseproperties		= 'baseproperties';
	protected $tablename_config			= 'config';
	protected $tablename_basetable			= 'basetable';
	protected $tablename_defmenu			= 'defmenu';
	protected $tablename_fieldtypes			= 'fieldtypes';

	/**
     * Email validation regular expression
     *
     * @var string
     */
    protected $_sEmailTpl = "^([-!#\$%&'*+./0-9=?A-Z^_`a-z{|}~\177])+@([-!#\$%&'*+/0-9=?A-Z^_`a-z{|}~\177]+\\.)+[a-zA-Z]{2,6}\$";


	protected $version						= 'new';//version admin area	
	
	public $error = 0;
	
	final protected function connectToDb() {
		global $dbUser;
		global $dbName;
		global $dbPassword;
		global $dbLocation;
		
		$dbconnect = mysql_connect($dbLocation, $dbUser, $dbPassword);
   		if (!$dbconnect) $this->error = 1;
   		
   		$dbselect = mysql_select_db($dbName, $dbconnect);
   		if (!$dbselect) $this->error = 1;
		
		mysql_query("set character_set_client='".$this->mysql_charset."'");
		mysql_query("set character_set_results='".$this->mysql_charset."'");
		mysql_query("set collation_connection='".$this->mysql_charset."_general_ci'");
	}

	/**
	 * Generates unique 32-digits ID
	 *
	 * @return string
	 * @author Pavel Golovenko
	 */
	final protected function generate_uid() {
		return md5(uniqid(mt_rand(),true));
	}

	/**
	 * Return template from templates dir for parse
	 *
	 * @param string $template name of the file with template
	 * @return string content of the template
	 * @author Pavel Golovenko
	 */
	final protected function get_template($template) {
		$filename = 'templates/'.$template.'.htpl';
		$file = fopen($filename, "r");
		$contents = fread($file, filesize($filename));
		fclose($file);
		return $contents;
	}

	final protected function assign_to_template(&$template, $where, $what) {
		$template = str_replace('%'.$where.'%', $what, $template);
	}

	final protected function apply_template(&$template, $replaceArray) {
		foreach ($replaceArray as $key => $value) {
			$template = str_replace('%'.$key.'%', $value, $template);
		}
		$this->clear_template_data($template);
	}

	final protected function clear_template_data(&$template) {
		$template = preg_replace('/%.*%/', '', $template);
	}


	final protected function imagecreatefromfile($path) {
		$info = @getimagesize($path);
		if(!$info) {
			return false;
		}
		if ($info['mime'] == 'image/bmp') {
			return false;
		}
		else {
			$functions = array(
				IMAGETYPE_GIF => 'imagecreatefromgif',
				IMAGETYPE_JPEG => 'imagecreatefromjpeg',
				IMAGETYPE_PNG => 'imagecreatefrompng',
				IMAGETYPE_WBMP => 'imagecreatefromwbmp',
				IMAGETYPE_XBM => 'imagecreatefromwxbm'
			);
			if(!$functions[$info[2]]) {
				return false;
			}
			if(!function_exists($functions[$info[2]])) {
				return false;
			}
		return $functions[$info[2]]($path);
    	}
	}

	final protected function ImageResize($image, $size_x, $size_y, $fill_color='') {
		$image_x = imageSX($image);
		$image_y = imageSY($image);
		// ?? ???? ??????? ?????? ???????????, ?????? ?? ????? ????????
		if ($size_y == 0) {
			$k = $image_x/$size_x; #??????????? ??????????
			$aw = $size_x;
			$ah = $image_y/$k;
			$aw = round($aw);
			$ah = round($ah);
		}
		// ?? ???? ??????? ?????? ???????????, ?????? ?? ????? ????????
		elseif ($size_x == 0) {
			$k = $image_y/$size_y; #??????????? ??????????
			$aw = $image_x/$k;
			$ah = $size_y;
			$aw = round($aw);
			$ah = round($ah);
		}
		// ?? ???? ??????? ?????? ? ?????? ???????????, ? ?????????? ?????????? ??????????? ?? ????????? ?? ??????? ???????? ????????
		else {
			// ?????? ???? ??????????
			if ($image_x > $image_y) {
				$k = $image_x/$size_x; #??????????? ??????????
				$aw = $size_x;
				$ah = $image_y/$k;
				$aw = round($aw);
				$ah = round($ah);
			}
			else {
				$k = $image_y/$size_y; #??????????? ??????????
				$aw = $image_x/$k;
				$ah = $size_y;
				$aw = round($aw);
				$ah = round($ah);
			}
			// ?????? ???? ??????????
			if ($aw > $size_x) {
				$k = $aw/$size_x; #??????????? ??????????
				$aw = $size_x;
				$ah = $size_y/$k;
				$aw = round($aw);
				$ah = round($ah);
			}
			if ($ah > $size_y) {
				$k = $ah/$size_y; #??????????? ??????????
				$aw = $size_x/$k;
				$ah = $size_y;
				$aw = round($aw);
				$ah = round($ah);
			}
		}
		// ??????? ???????????
		$im = imagecreatetruecolor($aw, $ah);
		imagecopyresampled($im, $image, 0, 0, 0, 0, $aw, $ah, $image_x, $image_y);
		if ($fill_color != '') {
			$im_tmp = imagecreatetruecolor($size_x, $size_y);
			// ???? ???????
			switch ($fill_color) {
				case 'white':
					$bgcolor = imagecolorallocate($im_tmp, 255, 255, 255);
					imagefill($im_tmp, 0, 0, $bgcolor);
				break;
				case 'black':
					$bgcolor = imagecolorallocate($im_tmp, 0, 0, 0);
					imagefill($im_tmp, 0, 0, $bgcolor);
				break;
			}
			imagecopy($im_tmp, $im, (($size_x-$aw)/2), (($size_y-$ah)/2), 0, 0, $aw, $ah);
			return $im_tmp;
		}
		return $im;
	}
	
	final private function img_resize_thumb($src, $dest, $width, $height, $rgb=0xFFFFFF, $quality=100){
	 	$src = $_SERVER['DOCUMENT_ROOT'].$src;
		if (!file_exists($src)) return false;
		$size = getimagesize($src);
		if ($size === false) return false;
		  $format = strtolower(substr($size['mime'], strpos($size['mime'], '/')+1));
		  $icfunc = "imagecreatefrom" . $format;
		  if (!function_exists($icfunc)) return false;
		  if($width=='')
		  {
		  	$width=$size[0];
		  }
		  if($height=='')
		  {
		  	$height=$size[1];
		  }
		  if($size[0]<=$width&&$size[1]<=$height)
		  {
		  	$new_width=$size[0];
		  	$new_height=$size[1];
		  }else 
		  {
			  $x_ratio = $width / $size[0];
			  $y_ratio = $height / $size[1];
			  $ratio       = min($x_ratio, $y_ratio);
			  $use_x_ratio = ($x_ratio == $ratio);
			  $new_width   = $use_x_ratio  ? $width  : floor($size[0] * $ratio);
			  $new_height  = !$use_x_ratio ? $height : floor($size[1] * $ratio);
		  }
		  $isrc = $icfunc($src);
		  $idest = imagecreatetruecolor($new_width, $new_height);
		  imagefill($idest, 0, 0, $rgb);
		  imagecopyresampled($idest, $isrc, 0, 0, 0, 0,
		  $new_width, $new_height, $size[0], $size[1]);
		  if($dest!='')
		  	imagejpeg($idest, $dest, $quality);
		  else
		  {
		  	header("Content-type: ".$size['mime']);
		  	$nfunc="image".$format;
		  	$nfunc($idest);
		  }
		  imagedestroy($isrc);
		  imagedestroy($idest);
		  return true;
	}

	final protected function create_thumbs($dest_folder, $img, $width, $height, $version,$default=''){
		$path = '../siteimg/thumbs';
		if (!is_dir($path))		mkdir($path);
		$path .= '/'.$dest_folder;
		if (!is_dir($path))		mkdir($path);
		
		if ($version == 'old'){
			$img = html_entity_decode($img);
			preg_match("/src='(.*\/([^\/]*))'/U", $img, $match, PREG_OFFSET_CAPTURE);
			$src = $match[1][0];
			$tarr = explode('/', $src);
			
			$nname = $tarr[count($tarr) - 1];
			$file = $path.'/'.$width.'_'.$height.'_'.$nname;
			if(!file_exists($file))
	  	$this->img_resize_thumb($src, $file, $width, $height);
			else{
				$size = getimagesize($file);
				if($width != $size[0] && $height != $size[1]){
					unlink($file);
					$this->img_resize_thumb($src, $file, $width, $height);
				}
			}
			return addslashes(str_ireplace($match[0][0], 'src="'.$file.'"', $img));
		}
		if ($version == 'new'){
			$tarr = explode('/', $img);
			$nname = $tarr[count($tarr) - 1];
				
			$file = $path.'/'.$width.'_'.$height.'_'.$nname;
			if(!file_exists($file))
				$this->img_resize_thumb($img, $file, $width, $height);
			else{
				$size = getimagesize($file);
				if($width != $size[0] && $height != $size[1]){
					unlink($file);
					$this->img_resize_thumb($img, $file, $width, $height);
				}
			}
            if (!file_exists($file))
             $file = $default;
			return '<img src="'.$file.'" border="0" alt="">';
		}
	}
	
	// получаем значение из таблицы config по ключу
	function GetConfig($key){
		return mysql_result(mysql_query('SELECT `value` FROM `config` WHERE `key`="'.$key.'"'), 0);
	}
	
	function GetMIMEType($ext){
	 	//$ext = end(explode('.',$file));
	 	foreach (file("modules/mime.types") as $line)
	  		if (preg_match('/^([^#]\S+)\s+.*'.$ext.'.*$/', $line, $m))
	   			return $m[1];
	  		
		return 'application/octet-stream';
	} 
	
	private function next_field($table, $field)
	{
		$max = mysql_result(mysql_query('SELECT MAX(`'.$field.'`) FROM `'.$table.'`'), 0);
		return ++$max;
	}
	
	/*********************************************************************
	 * ¬озвращает обрезанную строку до определЄнной позиции пробела 
	 *
	 * @param string $str - строка
	 * @param int $pos - позици€ пробеза
	 * @return string - ¬озвращает обрезанную строку до определЄнной позиции пробела
	 * 
	 * @author Pavel Golovenko
	 */
	final protected function cut_str($str, $pos) {
	//	echo '<br><br>'.$str;
		$str = preg_replace('/(<\/?)(\w+)([^>]*>)/e', '', $str);
		
		return substr($str, 0, $pos);
		/*
		$str = strip_tags(html_entity_decode($str));
		if (!empty($str)){
			if (strlen($str) < $pos)
				return $str;
			else	
				return substr($str, 0, strpos($str, ' ', $pos));
		}
		else 
			return '';
		*/	
	}
}
?>