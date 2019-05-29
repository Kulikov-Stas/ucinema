<?php

error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 1);
if ($_SERVER['REQUEST_URI'] == '/affiche/'){
header("Location: http://".$_SERVER['HTTP_HOST']."/");
}

$dbh=mysql_connect ('localhost', 'any', 'any') or die ('I cannot connect to the database because: ' . mysql_error());
mysql_select_db ('ucinema');
mysql_query("SET NAMES `cp1251`;");
	
require_once('./for_php/config.php');
//require_once('./for_php/xmlbuilder.php');
require_once('./for_php/paging.php');
//require_once('./for_php/forms.php');

include_once('./includes/default.php');
require_once('./includes/thumbs/ThumbLib.inc.php');;
	
require_once('./for_php/functions.php');

$gpage = @$_GET['page'];
$glink1 = @$_GET['link1'];
$glink2 = @$_GET['link2'];

//global $defContent;

	function cut_str($str, $pos) {
	//	echo '<br><br>'.$str;
		$str = strip_tags(html_entity_decode($str));
		if (!empty($str)){
			if (strlen($str) < $pos)
				return $str;
			else	
				return substr($str, 0, strpos($str, ' ', $pos));
		}
		else 
			return '';
	}
	
	function GetSiteDate($date)
	{
		$dates = explode('-', $date);
		if (isset($dates[2]) && isset($dates[1]) && isset($dates[0]))
			return $dates[2].'.'.$dates[1].'.'.$dates[0];
	}
	
        function alert($str)
        {
            echo '<script>alert("'.$str.'");</script>';
        }
        
    function getUri()
    {
        $str = $_SERVER['REQUEST_URI'];
        if(strpos($str,'?'))
        {$str = substr($str,0,strpos($str,'?'));} 
        return explode('/',trim($str,'/'));
    }
        
        function redirect($url)
        {
            echo '<script>document.location = "'.$url.'";</script>';
        }
        
        function show_msg($id, $msg)
        {
            return '<script>
                window.onload = function()
                {
                    el = document.getElementById("'.$id.'");
                    if(el != null)
                    {el.textContent = "'.$msg.'";}
                }
                </script>';
        }
        
	function get_config($key){
		return mysql_result(mysql_query('SELECT `value` FROM `config` WHERE `key`="'.$key.'"'), 0);
	}

	function get_month_str_rus($n){
    	$month = array('','€нвар€','феврал€','марта','апрел€','ма€','июн€','июл€','августа','сент€бр€','окт€бр€','но€бр€','декабр€');
      	return $month[$n];
    }
    
        function CreateThumb($sizex, $sizey, $image, $folder)
	{
		$filethumb = false;

		if ($sizex > 0 && $sizey > 0 && !empty($image) && file_exists('.'.$image) && !empty($folder)){
			if (!is_dir('./siteimg/thumbs/'.$folder))
				mkdir('./siteimg/thumbs/'.$folder);
			
			$ex = end(explode('.', $image));
			$filename = end(explode('/', $image));
			$filethumb = '/siteimg/thumbs/'.$folder.'/'.$sizex.'_'.$sizey.'_'.$filename;
				
			if (!file_exists('.'.$filethumb)){
				$thumb = PhpThumbFactory::create('.'.$image);
				$thumb->adaptiveResize($sizex, $sizey);
				$thumb->save('.'.$filethumb, $ex);
			}
		}
		
		return $filethumb;
	}
	
	function CreateThumb2($sizex, $sizey, $image, $folder)
	{
		$filethumb = false;

		if ($sizex > 0 && $sizey > 0 && !empty($image) && file_exists('.'.$image) && !empty($folder)){
			if (!is_dir('./siteimg/thumbs/'.$folder))
				mkdir('./siteimg/thumbs/'.$folder);
			
			$ex = end(explode('.', $image));
			$filename = end(explode('/', $image));
			$filethumb = '/siteimg/thumbs/'.$folder.'/'.$sizex.'_'.$sizey.'_'.$filename;
				
			if (!file_exists('.'.$filethumb)){
				$thumb = PhpThumbFactory::create('.'.$image);
				$thumb->resize($sizex, $sizey);
				$thumb->save('.'.$filethumb, $ex);
			}
		}
		
		return $filethumb;
	}
		
	function get_furl() {
		$str = trim($_SERVER['REQUEST_URI']);
		$pagem = array();
		if (preg_match("/\/[^\/]+\/!([0-9]*)\/{0,1}/iS", $_SERVER['REQUEST_URI'], $pagem)){
			$str=str_ireplace('page'.$pagem[1],'',$str);
		}
		if(strrpos($str,'/') != (strlen($str)-1)){
			$str=$str.'/';
		}
		if($pos=strpos($str,'?'))	{
			$str=substr($str,0,$pos);
		}
		
		return $str;
	}	
	
	function get_sub($strfile)
	{
		$boo=preg_match_all("/\[ADD.*\"(.+)\".+\"(.+)\".*\]/sU",$strfile,$match,PREG_SET_ORDER|PREG_OFFSET_CAPTURE);
		if($boo==0)
			return '0';
		$content=file_get_contents("./design/tables/".$match[0][1][0]."/".$match[0][2][0].".html");
		///
		if($match[0][2][0]=='dshort'||$match[0][2][0]=='sshort')
		{
			$adder=$_SERVER['REQUEST_URI'];
			$madder=explode('/',$adder); 
			if($madder[count($madder)-2]=='catalog')
			{
				$content=str_ireplace('[VIEW]',2,$content);
			}else if($madder[count($madder)-3]=='catalog')
			{
				$content=str_ireplace('[VIEW]',$madder[count($madder)-2],$content);
			}
		}
		///
		if($match[0][2][0]!='dlong')
			$strfile=substr_replace($strfile,$content,$match[0][0][1],strlen($match[0][0][0]));
		else
			$strfile=substr_replace($strfile,'',$match[0][0][1],strlen($match[0][0][0]));
		return $strfile;
	}
	
        function error404()
        {
            header($_SERVER['SERVER_PROTOCOL']." 404 Not Found");
            require_once './err/nopage.html';
        }
        
	function create_group($strfile,$folder)
	{
		//[STARTGROUP table="table" view="view" cols="2" tablestyle="tstyle" begin="1" end="10"]
		//[ENDGROUP]
		$boo=preg_match("/\[STARTGROUP[^\]]+\](.+)\[ENDGROUP\]/sU",$strfile,$match,PREG_OFFSET_CAPTURE);
		//echo $boo=preg_match("/STARTGROUP/sU",$strfile,$match,PREG_OFFSET_CAPTURE);
		if($boo==0)
			return '0';
		$content=substr($strfile,0,$match[0][1]);
		$replace=$match[0][0];
		$ereplace=$match[1][0];
		preg_match_all("|\"(.+)\"|U",$replace,$match1,PREG_SET_ORDER);
		$table=$match1[0][1];
		$sel='select link from defmenu where ltable like "'.$table.'"';
		$que=mysql_query($sel);
		$pre='';
		$adder=$_SERVER['REQUEST_URI'];
		if(substr($adder,-1,1)!='/')
				$adder.='/';
		$adder=substr($adder,strpos($adder,'/',1));
		if($no=mysql_num_rows($que)>0)
			$ro=mysql_fetch_assoc($que);
		else 
		{
			$sel='select * from basemenu where menuname not like "defmenu"';
			$r=mysql_query($sel);
			$n=mysql_num_rows($r);
			for ($i=0; $i<$n; ++$i)
			{
				$row2=mysql_fetch_assoc($r);
				$qq='select link from `'.$row2['menuname'].'` where ltable like "'.$table.'" and link like "'.$adder.'"';
				$rr=mysql_query($qq);
				if($nn=mysql_num_rows($rr)>0)
				{
					$pre='/'.$row2['menuname'];
					$ro=mysql_fetch_assoc($rr);
					break;
				}else 
				{
					$adder=@substr($adder,0,strrpos($adder,'/',-2)+1);
					$qq='select link from `'.$row2['menuname'].'` where ltable like "'.$table.'" and link like "'.$adder.'"';
					$rr=mysql_query($qq);
					if($nn=mysql_num_rows($rr)>0)
					{
						$pre='/'.$row2['menuname'];
						$ro=mysql_fetch_assoc($rr);
						break;
					}else $ro['link']='/news/';
				}
			}
		}
		$view=$match1[1][1];
		$cols=$match1[2][1];
		$tclass=$match1[3][1];
		$begin=(int)$match1[4][1]-1;
		$i=$begin;
		$end=(int)$match1[5][1];
		if((int)$match1[5][1]==0)
		$end=10000;
		$po=$end;
		$vcount=0;
		$content.='<table class="'.$tclass.'">';
		
		//set_magic_quotes_runtime(1);
		preg_match_all("|\[VALUE.+\"(.+)\".*\]|U",$ereplace,$match2,PREG_SET_ORDER|PREG_OFFSET_CAPTURE);
		preg_match_all("|\[PHPVALUE.+\"(.+)\".*\]|U",$ereplace,$match7,PREG_SET_ORDER|PREG_OFFSET_CAPTURE);
		preg_match_all("|\[IMAGE.+\"(.+)\".+\"(.+)\"\]|U",$ereplace,$match6,PREG_SET_ORDER|PREG_OFFSET_CAPTURE);
		preg_match_all("|\[PHPIMAGE.+\"(.+)\".+\"(.+)\"\]|U",$ereplace,$match5,PREG_SET_ORDER|PREG_OFFSET_CAPTURE);
		preg_match_all("|\[LINK.+\"(.+)\".*\]|U",$ereplace,$match3,PREG_SET_ORDER|PREG_OFFSET_CAPTURE);
		preg_match_all("|\[PAGES.*(.+\"(.+)\".*\"(.+)\")*\]|U",$strfile,$match4,PREG_SET_ORDER|PREG_OFFSET_CAPTURE);
		$q='select count(id) as num from `'.$table.'` where visible=1';
		$result=mysql_query($q);
		$row=mysql_fetch_assoc($result);
		$nnn=$row['num'];
		$q='select num,query from `'.$table.'_defsec` where vname like "'.$view.'";';
		$result=mysql_query($q);
		if(mysql_num_rows($result)>0)
		{
			$row=mysql_fetch_assoc($result);
		//	if($row['num']!='nothing')
		//	{
		//		$secarr=unserialize($row['num']);
				$qr=$row['query'];
				if(isset($_GET['s']))
					$qr=str_ireplace('order','where nameid like "%'.$_GET['s'].'%" order',$qr);
				//echo $qr;
				$result1=mysql_query($qr);
				//$m_num=mysql_num_rows($result1);
				$m_num=$nnn;
				if($m_num>0)
				{
					if(isset($match4[0][2][0]))
					{
						$limit=$match4[0][2][0];
						$page=substr($_SERVER['REQUEST_URI'],strrpos($_SERVER['REQUEST_URI'],'/')+1);
						if(!preg_match('/^![0-9]+/i',$page))
							$count=$begin;
						else
							$count=(int)substr($page,1)+$begin;
						//////////////////////////////////////
						$arsize=$end<$m_num?$end-$begin:$m_num-$begin;
						$inter=floor($arsize/$limit)+1;
						$strpages='<table width="10"><tr>';
						for($g=0; $g<$inter; ++$g)
						{
							if($arsize>$g*$limit)
							{
								//$strpages.='<td width="10"><a href="javascript: document.forms[\'pagesform\'].elements[\'s\'].value=\''.($g*$limit).'\'; document.forms[\'pagesform\'].submit();"';
								if($page=='!'.($g*$limit))
								{
									$strpages.='<td width="10" ';
									if(isset($match4[0][3][0]))
										$strpages.=' class="'.$match4[0][3][0].'_selected"';
									$strpages.='>'.($g+1).'</td>';}
								else
								{
									$strpages.='<td width="10"><a href="./p'.($g*$limit).'"';
									if(isset($match4[0][3][0]))
										$strpages.=' class="'.$match4[0][3][0].'"';
									$strpages.='>'.($g+1).'</a></td>';}
							}
						}
						$strpages.='</tr></table>';
						//////////////////////////////////////
					}
					if(isset($count))
					{
						$i=$count;
						$po=$i+$limit;
					}
					//echo $i.' '.' '.$end.' '.$po;
					while ($i<=$end&&$i<$po) 
					{
						if(@mysql_data_seek($result1,$i))
						{
							$row1=mysql_fetch_assoc($result1);
							if($row1['visible']=='1')
							{
								if($vcount%$cols==0)
									$content.='<tr>';
								++$vcount;
								$tempstr=$ereplace;
								for($j=sizeof($match2)-1; $j>=0; --$j)
								{
									$tquery='select ftype from `'.$table.'_prop` where fname like "'.$match2[$j][1][0].'"';
									$tres=mysql_query($tquery);
									$trow=mysql_fetch_assoc($tres);
									$ss=htmlspecialchars_decode($row1[$match2[$j][1][0]]);
									if($trow['ftype']==7)
									{
										$ttn=strpos($match2[$j][0][0],'%')+1;
										$ttt=substr($match2[$j][0][0],$ttn,strpos($match2[$j][0][0],'%', $ttn+1)-$ttn);
										$ss=preg_replace("/<.*>/U",'',$ss);
										if((int)$ttt<strlen($ss))
											$ss=substr($ss,0,strpos($ss,' ',(int)$ttt));
									}
									$tempstr=substr_replace($tempstr,$ss,$match2[$j][0][1],strlen($match2[$j][0][0]));
								}
								for($j=sizeof($match7)-1; $j>=0; --$j)
								{
									$tquery='select ftype from `'.$table.'_prop` where fname like "'.$match7[$j][1][0].'"';
									$tres=mysql_query($tquery);
									$trow=mysql_fetch_assoc($tres);
									$ss=htmlspecialchars_decode($row1[$match7[$j][1][0]]);
									if($trow['ftype']==7)
									{
										$ttn=strpos($match7[$j][0][0],'%')+1;
										$ttt=substr($match7[$j][0][0],$ttn,strpos($match7[$j][0][0],'%', $ttn+1)-$ttn);
										$ss=preg_replace("/<.*>/U",'',$ss);
										if((int)$ttt<strlen($ss))
											$ss=substr($ss,0,strpos($ss,' ',(int)$ttt));
									}
									$tempstr=substr_replace($tempstr,addslashes($ss),$match7[$j][0][1],strlen($match7[$j][0][0]));
								}
								for($j=sizeof($match5)-1; $j>=0; --$j)
								{
									$m_size=explode(',',$match5[$j][2][0]);
									if($row1[$match5[$j][1][0]]!='')
									{
										$str=create_thumbs($table, $view ,$row1[$match5[$j][1][0]],$m_size[0],$m_size[1],$m_num);
	
										$tempstr=str_replace($match5[$j][0][0],$str,$tempstr);
									}else 
									{
										$tempstr=str_replace($match5[$j][0][0],'',$tempstr);
									}
								}
								for($j=sizeof($match6)-1; $j>=0; --$j)
								{
									$m_size=explode(',',$match6[$j][2][0]);
									if($row1[$match6[$j][1][0]]!='')
									{
										if(!isset($m_size[2])||$m_size[2]==0)
											$str=create_thumbs($table, $view ,$row1[$match6[$j][1][0]],$m_size[0],$m_size[1],$m_num,0);
										else 
											$str=create_thumbs($table, $view ,$row1[$match6[$j][1][0]],$m_size[0],$m_size[1],$m_num,1);
										//$str=create_thumbs($table, $view ,$row1[$match6[$j][1][0]],$m_size[0],$m_size[1],$m_num);
										$tempstr=str_replace($match6[$j][0][0],stripslashes($str),$tempstr);
									}else 
									{
										$tempstr=str_replace($match6[$j][0][0],'',$tempstr);
									}
								}
								for($j=sizeof($match3)-1; $j>=0; --$j)
								{
									if($table=='cat_eng')
									{
										if($row1['linkid']=='hard')
											$str="/ferrum/";
										else if($row1['linkid']=='desk')
											$str="/saledesk/";
										else
										{
											$str="/catalog/".$row1['linkid']."/";
											//$str="javascript: link('".'/catalog/'.$row1['linkid']."/',1, '".$match3[$j][1][0]."');";
										}
									}else
									if(isset($limit))
									{
										//$str="javascript: link('".$pre.$ro['link'].$row1['linkid']."/',".(floor(($i-$begin)/$limit)*$limit).", '".$match3[$j][1][0]."');";
										$str=$pre.$ro['link'].$row1['linkid'].'/';
									}else
									{
										$str="./".$row1['linkid'];
										//$str="javascript: link('".$pre.$ro['link'].$row1['linkid']."/',1, '".$match3[$j][1][0]."');";
									}	//
									$tempstr=str_replace($match3[$j][0][0],$str,$tempstr);
								}
								$content.='<td>'.$tempstr.'</td>';
								if($vcount%$cols==0)
									$content.='</tr>';
							}else {++$po;}
						}
						++$i;		
					}
				}
		//	}
		}
		if($vcount%$cols!=0)
		{
			if($vcount>$cols)
			while ($vcount%$cols!=0)
			{
				$content.='<td></td>';
				++$vcount;
			}
			$content.='</tr>';
		}
		$content.='</table>';
		$content.=substr($strfile,$match[0][1]+strlen($match[0][0]));
		if(!strstr($content,'pagesform')&&sizeof($match4)>0)
			$content=''.$content;
		for($j=sizeof($match4)-1; $j>=0; --$j)
		{
			$content=str_replace($match4[$j][0][0],$strpages,$content, $e);
		}
		return  $content;
	}
	
function img_resize_thumb($src, $dest, $width, $height, $rgb=0xFFFFFF, $quality=100)
{
 $src=$_SERVER['DOCUMENT_ROOT'].$src;
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
	function create_thumbs($table, $view, $img, $width, $height, $count, $new=0)
	{
		//$img=stripcslashes($img);
		//echo 'img 1'.$img.'1';
		$path='./siteimg/thumbs/tables';
		if(!is_dir($path))
		{
			mkdir($path);
		}
		$path.='/'.$table;
		if(!is_dir($path))
		{
			mkdir($path);
		}
		$path.='/'.$view;
		if(!is_dir($path))
		{
			mkdir($path);
		}
		$img=html_entity_decode($img);
		$pattern="/src='(.*\/([^\/]*))'/U";
		preg_match($pattern,$img, $match,PREG_OFFSET_CAPTURE);
		preg_match("/image\('(.*)'/U",$img, $match1,PREG_OFFSET_CAPTURE);
		if(!isset($match1[1][0]))
			$src=$match[1][0];
		else
			$src=$match1[1][0];
		$tarr=explode('/',$src);
		$nname='';
		for ($i=2; $i<count($tarr)-1; ++$i)
		{
			$nname.=$tarr[$i].'_';
		}
		$file=$path.'/'.$nname.$match[2][0];
		if(!file_exists($file))
		{
			img_resize_thumb($src,$file,$width,$height);
		}else
		{
			$size = getimagesize($file);
			if($width!=$size[0]&&$height!=$size[1])
			{
				unlink($file);
				img_resize_thumb($src,$file,$width,$height);
			}
		}
		if($new==1)
		{
			
			$size = getimagesize('.'.$src);
		return addslashes(str_ireplace($match[0][0],'src="'.substr($file,1).'" 
		style="cursor:hand;" onclick="javascript:image(\''.$src.'\',\'\','.$size[0].','.$size[1].');"',$img));
		}else
		{
			return addslashes(str_ireplace($match[0][0],'src="'.substr($file,1).'"',$img));
		}
	
	}
	
	function pager($page, $count, $limit, $link){
		$content = '';
		if (empty($page)) $page = 1;
		
		$pages = ceil($count / $limit);
		if ($pages >= 2){
			$content .= '<ul>';
		//	$content .= '	<p>—траницы: ';
			// ѕереходы на соседние страницы слева и справа
			$content .= ($page != 1) ? '<li><a href="'.$link.'!'.($page - 1).'" class="button">предыдуща€</a></li>' : '<li><a href="javascript:void(0)" class="button">предыдуща€</a></li>';	
			
		//	$content .= '</p>';
			
		//	$content .= '<p class="numb_navi">';
			
			// ≈ще 10 страниц слева
		//	if ($page > 10)
		//		$content .= '<span><a href="'.$link.'!'.($page - ($page%10) - 9).'">≈ще 10 страниц</a></span>';
			
			// если возникнет необходимость показа всех страниц
		//	for ($i = 1; $i <= $pages; $i++)
		//    	if ($i >= $page - ($page%10) + 1 && $i <= $page - ($page%10) + 10)
		//			$content .= ($page == $i) ?	'<a href="'.$link.'p'.$i.'" class="cur">'.$i.'</a>' : '<a href="'.$link.'p'.$i.'">'.$i.'</a>';
		    
			for ($i = 1; $i <= $pages; $i++)
		    	$content .= ($page == $i) ?	'<li><a href="'.$link.'!'.$i.'" class="button active">'.$i.'</a></li>' : '<li><a href="'.$link.'!'.$i.'" class="button">'.$i.'</a></li>';
			
		    $content .= ($pages != $page) ? '<li><a href="'.$link.'!'.($page + 1).'" class="button">дальше</a></li>' : '<li><a href="javascript:void(0)" class="button">дальше</a></li>';
		    	
			// определ€ем предел цикла
		//	$limitfor = ($pages < ($page - (($page%10 == 0) ? 10 : $page%10) + 10)) ? $pages : ($page - (($page%10 == 0) ? 10 : $page%10) + 10);
			
			// цикл начинаем с того дес€тка в диапазон которого попадает $page - текуща€ страница
		//	for ($i = ($page - (($page%10 == 0) ? 10 : $page%10) + 1); $i <= $limitfor; $i++)
		//		$content .= '<a href="'.$link.'!'.$i.'" '.(($page == $i) ? 'class="cur"' : '').'>'.$i.'</a>';
				
		    // ≈ще 10 страниц справа
		//    if ($page - (($page%10 == 0) ? 10 : $page%10) + 10 < $pages)	
        //		$content .= '<span><a href="'.$link.'!'.($page - (($page%10 == 0) ? 10 : $page%10) + 11).'">≈ще 10 страниц</a></span>';
        	
		//	$content .= '</p>';
			$content .= '</ul>';
		}
		
		return $content;
	}
	
	function create_short($strfile,$folder)
	{
		$boo=preg_match("/\[START[^\]]+\](.+)\[END\]/sU",$strfile,$match,PREG_OFFSET_CAPTURE);
		if($boo==0)
			return '0';
		$content=substr($strfile,0,$match[0][1]);
		$replace=$match[0][0];
		$ereplace=$match[1][0];
		preg_match_all("|\"(.+)\"|U",$replace,$match1,PREG_SET_ORDER);
		$table=$match1[0][1];
		$sel='select link from defmenu where ltable like "'.$table.'"';
		$que=mysql_query($sel);
		$pre='';
		$adder=$_SERVER['REQUEST_URI'];
		if(substr($adder,-1,1)!='/')
			$adder.='/';
		
		$adder=substr($adder,strpos($adder,'/',1));
		
		if($no=mysql_num_rows($que)>0)
			$ro=mysql_fetch_assoc($que);
		else 
		{
			$sel='select * from basemenu where menuname not like "defmenu"';
			$r=mysql_query($sel);
			$n=mysql_num_rows($r);
			for ($i=0; $i<$n; ++$i)
			{
				$row2=mysql_fetch_assoc($r);
				$qq='select link from `'.$row2['menuname'].'` where ltable like "'.$table.'" and link like "'.$adder.'"';
				$rr=mysql_query($qq);
				if($nn=mysql_num_rows($rr)>0)
				{
					$pre='/'.$row2['menuname'];
					$ro=mysql_fetch_assoc($rr);
					break;
				}else 
				{
					$adder=@substr($adder,0,strrpos($adder,'/',-2)+1);
					$qq='select link from `'.$row2['menuname'].'` where ltable like "'.$table.'" and link like "'.$adder.'"';
					$rr=mysql_query($qq);
					if($nn=mysql_num_rows($rr)>0)
					{
						$pre='/'.$row2['menuname'];
						$ro=mysql_fetch_assoc($rr);
						break;
					}else 
						$ro['link']='/news/';
				}
			}
		}
		$view=$match1[1][1];
		$begin=(int)$match1[2][1]-1;
		$i=$begin;
		$end=(int)$match1[3][1];
		if((int)$match1[3][1]==0)
		$end=10000;
		$po=$end;
		//set_magic_quotes_runtime(1);
		preg_match_all("|\[VALUE.+\"(.+)\".*\]|U",$ereplace,$match2,PREG_SET_ORDER|PREG_OFFSET_CAPTURE);
		preg_match_all("|\[MYDATE.+\"(.+)\".*\]|U",$ereplace,$match_date,PREG_SET_ORDER|PREG_OFFSET_CAPTURE);
		preg_match_all("|\[PHPVALUE.+\"(.+)\".*\]|U",$ereplace,$match7,PREG_SET_ORDER|PREG_OFFSET_CAPTURE);
		preg_match_all("|\[IMAGE.+\"(.+)\".+\"(.+)\"\]|U",$ereplace,$match6,PREG_SET_ORDER|PREG_OFFSET_CAPTURE);
		preg_match_all("|\[PHPIMAGE.+\"(.+)\".+\"(.+)\"\]|U",$ereplace,$match5,PREG_SET_ORDER|PREG_OFFSET_CAPTURE);
		preg_match_all("|\[LINK.+\"(.+)\".*\]|U",$ereplace,$match3,PREG_SET_ORDER|PREG_OFFSET_CAPTURE);
		preg_match_all("|\[PAGES.*(.+\"(.+)\".*\"(.+)\")*\]|U",$strfile,$match4,PREG_SET_ORDER|PREG_OFFSET_CAPTURE);
		
		$isCatalogQuery = false;
		
		$link = preg_replace('^![0-9]+^', '', $_SERVER['REQUEST_URI']);
		
		if ($table == 'catalog' && $view == '_short'){
			$vname = preg_replace('^![0-9]*^', '', $_SERVER['REQUEST_URI']);
			$cleanlink = substr($vname, 4);
		//	echo 'SELECT id FROM `cat` WHERE link = "'.$cleanlink.'" AND visible = 1';
			$idmenu[] = mysql_result(mysql_query('SELECT id FROM `cat` WHERE link = "'.$cleanlink.'" AND visible = 1'), 0);
			$sql = 'SELECT id FROM `cat` WHERE sub = '.$idmenu[0].' AND visible = 1';
			$res = mysql_query($sql);
			while ($row = mysql_fetch_assoc($res))
				array_push($idmenu, $row['id']);
			if (is_array($idmenu))
				$isCatalogQuery = true;	
		//	$q='select num,query from `'.$table.'_defsec` where vname like "'.$vname.'";';
		}
		else		
			$q='select num,query from `'.$table.'_defsec` where vname like "'.$view.'";';
		
	//	echo $q;	
		if (!$isCatalogQuery)
			$result=mysql_query($q);
			
		if(isset($result) && mysql_num_rows($result)>0 || $isCatalogQuery)
		{
			if (!$isCatalogQuery) 
				$row = mysql_fetch_assoc($result);
			else {
				$catalogQuery = 'SELECT * FROM catalog WHERE visible=1 AND (';
				$cquery = '';
				foreach ($idmenu as $v){
					$cquery .= 'razdel = "'.$v.'" OR ';
				}
				$cquery = substr($cquery, 0, -4);
				$catalogQuery .= $cquery.') ORDER BY `order`';
			}	
			
			$result1 = ($isCatalogQuery) ? mysql_query($catalogQuery) : mysql_query($row['query']);
			$m_num = mysql_num_rows($result1);
			if($m_num>0)
				{
					if(isset($match4[0][2][0]))
					{
                       $limit = $match4[0][2][0];
						
						if (preg_match('|![0-9]+|i', $_SERVER['REQUEST_URI'], $pagem)){
							$page = intval(substr($pagem[0], 1));
						}	
						else {
							$page = 1;
						}
						
						//$limit = get_config('limit_pager_catalog');
						
						$strpages = pager($page, $m_num, $limit, $link);	
					}
					/*
					if(isset($count))
					{
						$i=$count;
						$po=$i+$limit;
					}
					*/
					if (isset($page)){
						$i = $page * $limit - $limit;
						$po = $i + $limit;
					}
					while ($i<=$end&&$i<$po) 
					{
						if(@mysql_data_seek($result1,$i))
						{
						$row1=mysql_fetch_assoc($result1);
						if($row1['visible']=='1')
						{
							$tempstr=$ereplace;
							for($j=sizeof($match2)-1; $j>=0; --$j)
							{
								$tquery='select ftype from `'.$table.'_prop` where fname like "'.$match2[$j][1][0].'"';
								$tres=mysql_query($tquery);
								$trow=mysql_fetch_assoc($tres);
								$ss=htmlspecialchars_decode($row1[$match2[$j][1][0]]);
								
								if($trow['ftype']==7 && $table != 'catalog')
								{
									$ttn=strpos($match2[$j][0][0],'%')+1;
									$ttt=substr($match2[$j][0][0],$ttn,strpos($match2[$j][0][0],'%', $ttn+1)-$ttn);
									$ss=preg_replace("/<.*>/U",'',$ss);
									if((int)$ttt<strlen($ss))
										$ss=substr($ss,0,strpos($ss,' ',(int)$ttt));
								}
								
								$tempstr=substr_replace($tempstr,$ss,$match2[$j][0][1],strlen($match2[$j][0][0]));
							}
							
							for($j=sizeof($match_date)-1; $j>=0; --$j)
							{
								$my_date = explode('-', $row1[$match_date[$j][1][0]]);
								$my_day = $my_date[2];
								$my_month = (int)$my_date[1];
								$my_year = $my_date[0];
								$my_month = get_month_str_rus($my_month);
							    
								$ss = $my_day.' '.$my_month.', '.$my_year;
								
								$tempstr=substr_replace($tempstr,$ss,$match_date[$j][0][1],strlen($match_date[$j][0][0]));
							}
							for($j=sizeof($match7)-1; $j>=0; --$j)
							{
								$tquery='select ftype from `'.$table.'_prop` where fname like "'.$match7[$j][1][0].'"';
								$tres=mysql_query($tquery);
								$trow=mysql_fetch_assoc($tres);
								$ss=htmlspecialchars_decode($row1[$match7[$j][1][0]]);
								if($trow['ftype']==7)
								{
									$ttn=strpos($match7[$j][0][0],'%')+1;
									$ttt=substr($match7[$j][0][0],$ttn,strpos($match7[$j][0][0],'%', $ttn+1)-$ttn);
									$ss=preg_replace("/<.*>/U",'',$ss);
									if((int)$ttt<strlen($ss))
										$ss=substr($ss,0,strpos($ss,' ',(int)$ttt));
								}
								$tempstr=substr_replace($tempstr,addslashes($ss),$match7[$j][0][1],strlen($match7[$j][0][0]));
							}
							for($j=sizeof($match5)-1; $j>=0; --$j)
							{
								$m_size=explode(',',$match5[$j][2][0]);
								if($row1[$match5[$j][1][0]]!='')
								{
									$str=create_thumbs($table, $view ,$row1[$match5[$j][1][0]],$m_size[0],$m_size[1],$m_num);

									$tempstr=str_replace($match5[$j][0][0],$str,$tempstr);
								}else 
								{
									$tempstr=str_replace($match5[$j][0][0],'',$tempstr);
								}
							}
							for($j=sizeof($match6)-1; $j>=0; --$j)
							{
								$m_size=explode(',',$match6[$j][2][0]);
								if($row1[$match6[$j][1][0]]!='')
								{
									if(!isset($m_size[2])||$m_size[2]==0)
										$str=create_thumbs($table, $view ,$row1[$match6[$j][1][0]],$m_size[0],$m_size[1],$m_num,0);
									else 
										$str=create_thumbs($table, $view ,$row1[$match6[$j][1][0]],$m_size[0],$m_size[1],$m_num,1);
									//$str=create_thumbs($table, $view ,$row1[$match6[$j][1][0]],$m_size[0],$m_size[1],$m_num);
									$tempstr=str_replace($match6[$j][0][0],stripslashes($str),$tempstr);
								}else 
								{
									$tempstr=str_replace($match6[$j][0][0],'',$tempstr);
								}
							}
							for($j=sizeof($match3)-1; $j>=0; --$j)
							{
								if(isset($limit))
								{
									//$str="javascript: link('".$pre.$ro['link'].$row1['linkid']."/',".(floor(($i-$begin)/$limit)*$limit).", '".$match3[$j][1][0]."');";
									$str=$pre.$ro['link'].$row1['linkid'].'/';
								}
								else
								{
									//$str="javascript: link('".$pre.$ro['link'].$row1['linkid']."/',1, '".$match3[$j][1][0]."');";
									$str=$pre.$ro['link'].$row1['linkid'].'/';
								}
								
								//echo $pre.'!'.$ro['link'].'!'.$row1['linkid'].' ';	
								$tempstr=str_replace($match3[$j][0][0],$str,$tempstr);
							}
							$content.=$tempstr;
							}
						}
						++$i;		
					}
				}
		//	}
		}
		$content.=substr($strfile,$match[0][1]+strlen($match[0][0]));
		if(!strstr($content,'pagesform'))
			$content=''.$content;
		for($j=sizeof($match4)-1; $j>=0; --$j)
		{
			$content=str_replace($match4[$j][0][0],@$strpages,$content, $e);
		}
		return  $content;
	}
	
	function create_long($strfile,$note, $folder)
	{
		//echo $note.' '.$folder;
		preg_match_all("|\[VALUE.+\"(.+)\"\]|U",$strfile,$match2,PREG_SET_ORDER|PREG_OFFSET_CAPTURE);
		preg_match_all("|\[IMAGE.+\"(.+)\".+\"(.+)\"\]|U",$strfile,$match5,PREG_SET_ORDER|PREG_OFFSET_CAPTURE);
		preg_match_all("|\[PHPVALUE.+\"(.+)\"\]|U",$strfile,$match7,PREG_SET_ORDER|PREG_OFFSET_CAPTURE);
		preg_match_all("|\[PHPIMAGE.+\"(.+)\".+\"(.+)\"\]|U",$strfile,$match6,PREG_SET_ORDER|PREG_OFFSET_CAPTURE);
		$query='select * from `'.$folder.'` where linkid='.$note;
                //var_dump($query);
		$result=mysql_query($query);
		if($result && mysql_num_rows($result))
		{
			$row=mysql_fetch_assoc($result);
			{
				for($j=sizeof($match2)-1; $j>=0; --$j)
				{
					$strfile=substr_replace($strfile,htmlspecialchars_decode($row[$match2[$j][1][0]]),$match2[$j][0][1],strlen($match2[$j][0][0]));
				}
				for($j=sizeof($match7)-1; $j>=0; --$j)
				{
					$strfile=str_replace($match7[$j][0][0],addslashes(htmlspecialchars_decode($row[$match7[$j][1][0]])),$strfile);
					//$strfile=str_replace($strfile,htmlspecialchars_decode($row[$match7[$j][1][0]]),$match7[$j][0][1],strlen($match7[$j][0][0]));
				}
				
				for($j=count($match5)-1; $j>=0; --$j)
				{
					if($row[$match5[$j][1][0]]!='')
					{
						$m_size=explode(',',$match5[$j][2][0]);
						if(!isset($m_size[2])||$m_size[2]==0)
							$str=create_thumbs($folder, $note ,$row[$match5[$j][1][0]],$m_size[0],$m_size[1],1,0);
						else 
							$str=create_thumbs($folder, $note ,$row[$match5[$j][1][0]],$m_size[0],$m_size[1],1,1);
						$strfile=str_replace($match5[$j][0][0],stripslashes($str),$strfile);
					}else 
					{
						$strfile=str_replace($match5[$j][0][0],'',$strfile);
					}
				}
				for($j=count($match6)-1; $j>=0; --$j)
				{
					if($row[$match6[$j][1][0]]!='')
					{
						$m_size=explode(',',$match6[$j][2][0]);
						$str=create_thumbs($folder, $note ,$row[$match6[$j][1][0]],$m_size[0],$m_size[1],1);
						$strfile=str_replace($match6[$j][0][0],$str,$strfile);
					}else 
					{
						$strfile=str_replace($match6[$j][0][0],'',$strfile);
					}
				}
			}
		}else 
		{
			//header('Location: /err/nopage.html');
		}
		return  $strfile;
	}
	function create_m_rec($menu, $design,$level,$i)
	{
		$str=$menu.$i;
		global $$str;
		$kk=$_SERVER['REQUEST_URI'];
		$tt=0;
					//$all[]=$_SERVER['REQUEST_URI'];
					while ($kk!='')
					{

						$kk=substr($kk,0,strrpos($kk,'/'));
						$all[$tt++]=$kk.'/';
						//echo '<span style="color:red">'.$all[$tt-1].'</span>';
					}

		if(isset($$str))
		{
			clearstatcache();
			$temp=$level;
			do{
				$path_1='./design/menu/'.$design.'/menu'.$temp.'_file.htm';
				--$temp;
			}while(!file_exists($path_1)&&$temp>-2);
			$temp=$level;
			do{
				$path_2='./design/menu/'.$design.'/menu'.$temp.'_file_selected.htm';
				--$temp;
			}while(!file_exists($path_2)&&$temp>-2);
			if($temp==-2)
				$path_2=$path_1;
			$temp=$level;
			do{
				$path_3='./design/menu/'.$design.'/menu'.$temp.'_folder.htm';
				--$temp;
			}while(!file_exists($path_3)&&$temp>-2);
			if($temp==-2)
				$path_3=$path_1;
			$temp=$level;
			do{
				$path_4='./design/menu/'.$design.'/menu'.$temp.'_folder_selected.htm';
				--$temp;
			}while(!file_exists($path_4)&&$temp>-2);
			if($temp==-2)
				$path_4=$path_2;
			static $result_str;
			if($i==0)
			{
				$result_str='';
			}
			$extr=0;
			foreach ($$str as $key=>$value)
			{
				
				if(count($$str)>5&&$design=='defmenu1')
				{
					
					$arr=explode('/*/',$value);
					if(stristr($arr[6],'http://')||stristr($arr[6],'ftp://')||stristr($arr[6],'mailto://'))
					{
						$lpath=$arr[6];
					}else 
					{
						$lpath=$arr[5];
						if($menu!='defmenu')
						$lpath='/'.$menu.$lpath;
					}
					$tstr=$menu.$arr[0];
					global $$tstr;
					
					if(isset($$tstr))
					{						
						$d1= '<div id="'.$arr[1].'" style="LEFT: 13px;TOP: 104px; VISIBILITY: hidden; WIDTH: 179px; POSITION: absolute;" onmouseover="this.style.visibility=\'visible\'" onmouseout="this.style.visibility=\'hidden\'">
								<table width="100%" cellSpacing="0" cellPadding="0" border="0">
									<tr><td height="3" colspan="2"></td></tr><tr>';			
							if($design=='defmenu1')
							$result_str=$result_str.$d1;
							$des=file_get_contents('./design/menu/'.$design.'/menu1_mult.htm');
							$des=str_replace('[LINK]',$lpath,$des);
							$des=str_replace('[HTITLE]',$arr[9],$des);
							$des=str_replace('[PROMPTING]',$arr[10],$des);
							$result_str.=str_replace('[CAPTION]',$arr[2],$des);
	//////////////			
							if($extr%2==1)
								$result_str.='</tr><tr>';								
							++$extr;		
							if($design!='downmenu')		
								create_m_rec($menu,$design,$level+1,$arr[0]);
							else if($all[1]=='/'.$arr[1].'/'&&$design!='defmenu')
							{
								create_m_rec($menu,$design,$level+1,$arr[0]);}
							$d2= '</tr><tr>
										<td height="10" style="background:URL(/siteimg/sub_niz.gif) no-repeat top left" colspan=2></td>
									</tr>
								</table></div>';
							if($design=='defmenu1')
							$result_str.=$d2;						
					}else 
					{
						$des=file_get_contents('./design/menu/'.$design.'/menu1_mult.htm');
						$des=str_replace('[LINK]',$lpath,$des);
						$des=str_replace('[HTITLE]',$arr[9],$des);
						$des=str_replace('[VIEW]',$arr[1],$des);
						$des=str_replace('[PROMPTING]',$arr[10],$des);
						$result_str.=str_replace('[CAPTION]',$arr[2],$des);
							if($extr%2==1)
								$result_str.='</tr><tr>';								
							++$extr;		
					}
				}
				else 
				{
					$arr=explode('/*/',$value);
					if(stristr($arr[6],'http://')||stristr($arr[6],'ftp://')||stristr($arr[6],'mailto://'))
					{
						$lpath=$arr[6];
					}else 
					{
						$lpath=$arr[5];
						if($menu!='defmenu')
						$lpath='/'.$menu.$lpath;
					}
					$tstr=$menu.$arr[0];
					global $$tstr;
					if(isset($$tstr))
					{
						$d1= '<div id="'.$arr[1].'" style="LEFT: 13px;TOP: 104px; VISIBILITY: hidden; WIDTH: 100px; POSITION: absolute;" onmouseover="this.style.visibility=\'visible\'" onmouseout="this.style.visibility=\'hidden\'">
								<table width="100%" cellSpacing="0" cellPadding="0" border="0">
									<tr><td height="3" ';
						if(count($$tstr)>5)
							$d1.='colspan="2"></td></tr><tr>';
						else $d1.=' ></td></tr>';			
							if($design=='defmenu1')
							$result_str=$result_str.$d1;
							$des=file_get_contents($path_4);
							$des=str_replace('[LINK]',$lpath,$des);
							$des=str_replace('[HTITLE]',$arr[9],$des);
							$des=str_replace('[PROMPTING]',$arr[10],$des);
							$result_str.=str_replace('[CAPTION]',$arr[2],$des);
	//////////////											
							if($design!='downmenu')		
								create_m_rec($menu,$design,$level+1,$arr[0]);
							else
							{
								if(!isset($all[1]))
								$all[1]='/salon/';
							 if($all[1]=='/'.$arr[1].'/'&&$design!='defmenu'||'/models/'=='/'.$arr[1].'/'&&$all[1]=='/catalog/')
							{
								create_m_rec($menu,$design,$level+1,$arr[0]);}
							} 
							//$d2= '</tr><tr>
								//		<td height="10" style="background:URL(/siteimg/sub_niz.gif) no-repeat top left" ';
						if(count($$tstr)>5)
							$d2='</tr><tr>
										<td height="10" style="background:URL(/siteimg/sub_niz.gif) no-repeat top left" colspan="2"';
						else
							$d2='<tr>
										<td height="10" style="background:URL(/siteimg/sub_niz.gif) no-repeat top left" colspan="2"';
						
							$d2.=' ></td></tr></table></div>';
							if($design=='defmenu1')
							$result_str.=$d2;						
					}else 
					{
						//echo 'j';
						if($_SERVER['REQUEST_URI']==$lpath)
							$des=file_get_contents($path_2);
						else 
							$des=file_get_contents($path_1);
						$des=str_replace('[LINK]',$lpath,$des);
//						$des=str_replace('[HTITLE]',$arr[9],$des);
//						$des=str_replace('[VIEW]',$arr[1],$des);
//						$des=str_replace('[PROMPTING]',$arr[10],$des);
						$result_str.=str_replace('[CAPTION]',$arr[2],$des);			
					}
				}			
			}
			if($i==0)
			{
				return $result_str;
			}
		}
	}
	function create_menu($strfile)
	{
		preg_match_all("|\[MENU.+\"(.+)\".+\"(.+)\"\]|U",$strfile,$match,PREG_SET_ORDER|PREG_OFFSET_CAPTURE);
		for($i=sizeof($match)-1; $i>=0; --$i)
		{
			$query='select * from `'.$match[$i][1][0].'` order by `order`';
			$result=mysql_query($query);
			$n=mysql_num_rows($result);
			for ($j=0; $j<$n; ++$j)
			{
				$row=mysql_fetch_assoc($result);
				$q='select id from `'.$match[$i][1][0].'` where sub='.$row['sub'];
				$r=mysql_query($q);
				$n1=mysql_num_rows($r);
				if($row['visible']==1)
				{
					$pre=$match[$i][1][0];
					$temp=$pre.$row['sub'];
					global $$temp;
					if(!isset($$temp))
						$$temp=array();
					if(count($$temp)<$n1)
					array_push($$temp,$row['id'].'/*/'.$row['name'].'/*/'.$row['rus'].'/*/'.$row['sub'].'/*/'.$row['visible'].'/*/'.$row['link'].'/*/'.$row['lview'].'/*/'.$row['lmain'].$row['ltable'].'/*/'.$row['lrecord'].'/*/'.$row['title']);
				}
			}
			$str=create_m_rec($match[$i][1][0],$match[$i][2][0],0,0);
			$strfile=substr_replace($strfile,$str,$match[$i][0][1],strlen($match[$i][0][0]));
		}
		return $strfile;
	}
	function create_content($defTable, $defView, $defDesign, $defNote,$def_navigation_separator)
	{
            global $defContent;
		$curfile = file_get_contents('./design/'.$defDesign);
		$folder = $defTable;
		$vfile = $defView;
		$note = $defNote;
		$path = './design/tables/'.$folder.'/'.$vfile;
		$strfile = file_get_contents($path);
/////////////////////////////////////		
		$str = $_SERVER['REQUEST_URI'];
		if(substr($str,-1)!='/')
			$str.='/';
		$q = 'select * from defmenu where link like "'.$str.'"';
		$r = mysql_query($q);
		$n = mysql_num_rows($r);
		if($n>0)
		{
			$row=mysql_fetch_assoc($r);
			if (trim($row['keywords'])!='')
				$curfile = str_replace('[KEYWORDS]',$row['keywords'],$curfile);
			if(trim($row['description'])!='')
				$curfile = str_replace('[DESCRIPTION]',$row['description'],$curfile);
			if(trim($row['title'])!='')
				$curfile = str_replace('[TITLE]',$row['title'],$curfile);
		}
		else {
			$t1=@substr($str,0,strrpos($str,'/',-2)+1);
			$t2=@substr($str,strrpos($str,'/',-2)+1,-1);
			$q='select * from defmenu where link like "'.$t1.'"';
			$r=mysql_query($q);
			$n=mysql_num_rows($r);
			if($n>0)
			{
				$row=mysql_fetch_assoc($r);
				
				require_once('includes/keys.php');
				
			//	$curfile = Keys::getInstance()->title_keywords_description($curfile, $_GET['page'], $_GET['link1']);				
			//	$curfile = Keys::getInstance()->tkd4right($curfile);
								
				if(trim($row['keywords'])!='')
					$curfile=str_replace('[KEYWORDS]',$row['keywords'],$curfile);
				if(trim($row['description'])!='')
					$curfile=str_replace('[DESCRIPTION]',$row['description'],$curfile);
				if(trim($row['title'])!='')
					$curfile=str_replace('[TITLE]',$row['title'],$curfile);
					
			}else
			{
				$npos=strpos($str,'/',1);
				$sname=substr($str,1,$npos-1);
				$q='select menuname from basemenu where menuname like "'.$sname.'"';
				$r=mysql_query($q);
				$n=mysql_num_rows($r);
				if($n>0)
				{
					$surl=substr($str,$npos);
					$q='select * from '.$sname.' where link like "'.$surl.'"';
					$r=mysql_query($q);
					$n=mysql_num_rows($r);
					if($n>0)
					{
						$row=mysql_fetch_assoc($r);
							if(trim($row['keywords'])!='')
								$curfile=str_replace('[KEYWORDS]',$row['keywords'],$curfile);
							if(trim($row['description'])!='')
								$curfile=str_replace('[DESCRIPTION]',$row['description'],$curfile);
							if(trim($row['title'])!='')
								$curfile=str_replace('[TITLE]',$row['title'],$curfile);
					}else 
					{
						$row=mysql_fetch_assoc($r);
						
						// дл€ каталога невест
						require_once('includes/keys.php');
						//$curfile = Keys::getInstance()->title_keywords_description($curfile, 'catalog', $_GET['link3']);
				
						if(trim($row['keywords'])!='')
							$curfile=str_replace('[KEYWORDS]',$row['keywords'],$curfile);
						if(trim($row['description'])!='')
							$curfile=str_replace('[DESCRIPTION]',$row['description'],$curfile);
						if(trim($row['title'])!='')
							$curfile=str_replace('[TITLE]',$row['title'],$curfile);
					}
				}
			}
		}		
////////////////////////////////////
		while(true)
		{
			$b=get_sub($strfile);
			if($b=='0')
				break;
			$strfile=$b;
		}
		while (true)
		{
				$content=create_group($strfile,$folder);
				if($content=='0')
					break;
				$strfile=$content;
		}
		while (true)
		{
				$content=create_short($strfile,$folder);
				if($content=='0')
					break;
				$strfile=$content;
		}
	
		if($note!='')
			$strfile=create_long($strfile,$note, $folder);
		$curfile=str_replace('[CONTENT]',$strfile,$curfile);
		preg_match("|\[TITLE.+\"(.+)\"\]|U",$curfile,$brr,PREG_OFFSET_CAPTURE);
		if(isset($brr[1][0]))
		{
			$title=$brr[1][0];
			$curfile=substr_replace($curfile,'',$brr[0][1],strlen($brr[0][0]));
			$curfile=str_replace('[MTITLE]',$title,$curfile);
		}else 
		{
			$curfile=str_replace('[MTITLE]','',$curfile);
		}
	//	if (empty($note))
			preg_match_all("|\[NAVIGATION.+\"(.+)\"\]|U",$curfile,$crr,PREG_SET_ORDER|PREG_OFFSET_CAPTURE);
		if(isset($crr[0][0][0]))
		{
			$navigation = preg_replace('^(p[0-9])*^', '', $_SERVER['REQUEST_URI']);
			if(substr($navigation,-1,1)!='/')
				$navigation.='/';
			$navi=explode('/',$navigation);
			$resnavi='';
			$reshref=$_SERVER['HTTP_HOST'];
			$q='select * from defmenu where link like "'.$navigation.'"';
			$r=mysql_query($q);
			$n=mysql_num_rows($r);
			if($n>0)
			{
				$size=sizeof($navi);
				for($i=1; $i<$size-1; ++$i)
				{
					$q1='select rus from defmenu where name like "'.$navi[$i].'"';
					$r1=mysql_query($q1);
					$row1=mysql_fetch_assoc($r1);
					$reshref.='/'.$navi[$i];
					if($i!=$size-2)
						$resnavi.='<a href="http://'.$reshref.'/" class="'.$crr[0][1][0].'">'.$row1['rus'].'</a>'.$def_navigation_separator;
					else 
						$resnavi.=$row1['rus'];
				}
			}else 
			{
				if($navi[1]!='')
				{
					$n1='/';
					$size=sizeof($navi);
					for ($i=1; $i<$size-2; ++$i)
						$n1.=$navi[$i].'/';
					$q='select * from defmenu where link like "'.$n1.'"';
					$r=mysql_query($q);
					$n=mysql_num_rows($r);
					if($n>0)
					{
						$row=mysql_fetch_assoc($r);
						for($i=1; $i<$size-1; ++$i)
						{
							if($i!=$size-2)
							{
								$q1='select rus from defmenu where name like "'.$navi[$i].'"';
								$r1=mysql_query($q1);
								$row1=mysql_fetch_assoc($r1);
								$reshref.='/'.$navi[$i];
								$resnavi.='<a href="http://'.$reshref.'/" class="'.$crr[0][1][0].'">'.$row1['rus'].'</a>'.$def_navigation_separator;
							}else
							{
								$q1='select nameid from `'.$row['ltable'].'` where linkid like "'.$navi[$i].'"';
								$r1=mysql_query($q1);
								$row1=mysql_fetch_assoc($r1);
								$resnavi.=substr($resnavi,0,-strlen($def_navigation_separator));
								//$resnavi.=$row1['nameid'];
							} 
						}
					}else 
					{
						$n1='/';
						$size=sizeof($navi);
						for ($i=2; $i<$size-1; ++$i)
							$n1.=$navi[$i].'/';
						$q='select * from `'.$navi[1].'` where link like "'.$n1.'"';
						if($r=mysql_query($q))
							$n=mysql_num_rows($r);
						else $n=0;
						if($n>0)
						{
							$reshref.='/'.$navi[1];
							$row=mysql_fetch_assoc($r);
							for($i=2; $i<$size-1; ++$i)
							{
								$q1='select rus from `'.$navi[1].'` where name like "'.$navi[$i].'"';
								$r1=mysql_query($q1);
								$row1=mysql_fetch_assoc($r1);
								$reshref.='/'.$navi[$i];
								if($i!=$size-2)
								{
									//echo ' '.$reshref;
									$resnavi .= ' <a href="http://'.$reshref.'/">'.$row1['rus'].'</a>'.$def_navigation_separator;
								}else
								{
									$resnavi .= ' <a href="#" class="'.$crr[0][1][0].'">'.$row1['rus'].'</a>';
								} 
							}								
						}else 
						{
							$n1='/';
							$size=sizeof($navi);
							for ($i=2; $i<$size-2; ++$i)
								$n1.=$navi[$i].'/';
							$q='select * from `'.$navi[1].'` where link like "'.$n1.'"';
							$r=mysql_query($q);
							$n=mysql_num_rows($r);
							if($n>0)
							{
								$reshref.='/'.$navi[1];
								$row=mysql_fetch_assoc($r);
								for($i=2; $i<$size-1; ++$i)
								{
									if($i!=$size-2)
									{
										$q1='select rus from `'.$navi[1].'` where name like "'.$navi[$i].'"';
										$r1=mysql_query($q1);
										$row1=mysql_fetch_assoc($r1);
										$reshref.='/'.$navi[$i];
										$resnavi.='<a href="http://'.$reshref.'/" class="'.$crr[0][1][0].'">'.$row1['rus'].'</a>'.$def_navigation_separator;
									}else
									{
										$q1='select nameid from `'.$row['ltable'].'` where linkid like "'.$navi[$i].'"';
										$r1=mysql_query($q1);
										$row1=mysql_fetch_assoc($r1);
										//$resnavi.=$row1['nameid'];
										$resnavi=substr($resnavi,0,-strlen($def_navigation_separator));
									} 
								}								
							}
						}
					}
				}
			}
			$curfile=substr_replace($curfile,$resnavi,$crr[0][0][1],strlen($crr[0][0][0]));
		}
		$curfile=create_menu($curfile);
		while(true)
		{
			$b=get_sub($curfile);
			if($b=='0')
				break;
			$curfile=$b;
		}
		while (true)
		{
			$content=create_group($curfile,$folder);
			if($content=='0')
				break;
			$curfile=$content;
		}
		$curfile=create_menu($curfile);
		while (true)
		{
			$content=create_short($curfile,$folder);
			if($content=='0')
				break;
			$curfile=$content;
		}
				
		global $defKeyWords;
		global $defDescription;
		global $defHeadTitle;
		/*
		global $defAbstract;
		global $defVw96;
		global $defComments;
		*/
		global $defLinks;
		
		if ($_SERVER['REQUEST_URI'] == '/'){
//			$curfile = str_replace('[KEYWORDS]', mysql_result(mysql_query('SELECT keywords FROM `defmenu` WHERE name = "home"'), 0), $curfile);
//			$curfile = str_replace('[DESCRIPTION]', mysql_result(mysql_query('SELECT description FROM `defmenu` WHERE name = "home"'), 0), $curfile);
//			$curfile = str_replace('[TITLE]', mysql_result(mysql_query('SELECT title FROM `defmenu` WHERE name = "home"'), 0), $curfile);
		}
		
		$curfile=str_replace('[KEYWORDS]',$defKeyWords,$curfile);
		$curfile=str_replace('[DESCRIPTION]',$defDescription,$curfile);
		$curfile=str_replace('[TITLE]',$defHeadTitle,$curfile);
	
		/*
		$curfile=str_replace('[ABSTRACT]',$defAbstract,$curfile);
		$curfile=str_replace('[VW96]',$defVw96,$curfile);
		$curfile=str_replace('[COMMENTS]',$defComments,$curfile);
		*/
		$curfile=str_replace('[LINKS]',$defLinks,$curfile);
		preg_match_all("|<\?php (.*)\?>|sU",$curfile,$marr,PREG_SET_ORDER|PREG_OFFSET_CAPTURE);
	//	var_dump($marr);
		$i1=0;
		if(isset($marr[0][0][0]))
		{
			$tstr=substr($curfile,0,$marr[$i1][0][1]);
			echo $tstr;
			$count=count($marr);
			for(; $i1<$count-1; ++$i1)
			{
				$phpstr=$marr[$i1][1][0];
				eval($phpstr);
				$l=$marr[$i1][0][1]+strlen($marr[$i1][0][0]);
				echo substr($curfile,$l,$marr[$i1+1][0][1]-$l);
				
			}
			$ts=$marr[$i1][0][1]+strlen($marr[$i1][0][0]);			
			eval($marr[$i1][1][0]);
			echo substr($curfile,$ts);
		}
		else 
			echo $curfile;

		
	}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	

	$str=$_SERVER['REQUEST_URI'];
        ///////////////////////////////////////////////  for paging!
        if(strpos($str,'?'))
        {$str = substr($str,0,strpos($str,'?'));} 
        ///////////////////////////////////////////////  end for paging
        
        ///////////////////////////////////////////////  for lang
        $tbl = '`defmenu`';
        if(strpos($str,'/en') !== false)
        {
            $str = str_replace('/en','',$str);
            $tbl = '`en`';
        }
        if(strpos($str,'/ua') !== false)
        {
            $str = str_replace('/ua','',$str);
            $tbl = '`ua`';
        }
        /////////////////////////////////////////////// 
        
	/*
	if(strstr($str,'events/p'))
	{
		$str='/events/';
	}
	if($pos=strpos($str,'?'))
	{
		$str=substr($str,0,$pos);
	}
	*/
	if($str=='/')
	{
		create_content($defTable, $defView, $defDesign, $defNote,$def_navigation_separator);
	}
        //elseif($str=='/en/')
        //{
        //    create_content('pages', 'en_first.html', 'en_main.html', $defNote,$def_navigation_separator);
	//}
        else 
	{
		if(substr($str,-1)!='/')
			$str.='/';
		$q='select * from ' .$tbl. ' where link like "'.$str.'"';
		$r=mysql_query($q);
		$n=mysql_num_rows($r);
		if($n>0)
		{
			$row=mysql_fetch_assoc($r);
			create_content($row['ltable'], $row['lview'], $row['lmain'], $row['lrecord'],$def_navigation_separator);
		}else 
		{
			$t1=substr($str,0,strrpos($str,'/',-2)+1);
			$t2=substr($str,strrpos($str,'/',-2)+1,-1);
			$q='select * from ' .$tbl. ' where link like "'.$t1.'"';
			$r=mysql_query($q);
			$n=mysql_num_rows($r);
			if($n>0)
			{
				$row=mysql_fetch_assoc($r);
				if(!preg_match("/![0-9]*/",$t2))
				{
					
					$q1='select linkid from `'.$row['ltable'].'` where linkid like "'.$t2.'"';
					$r1=mysql_query($q1);
					$n=mysql_num_rows($r1);
					if($n>0)
					{
						if(isset($_POST['design']))
							$view=$_POST['design'];
						else
						{
								$view='long';
						}
						create_content($row['ltable'], $view.'.html',$row['lmain'], $t2,$def_navigation_separator);
					}else 
					{
                                            error404();
                                            exit();
                                            if(isset($_POST['design']))
                                                    $view=$_POST['design'];
                                            else
                                            {
                                                    if($t1=='/catalog/')
                                                            $view='dshort';
                                                    else
                                                            $view='long';
                                            }
                                            create_content($row['ltable'], $view.'.html',$row['lmain'], $t2,$def_navigation_separator);
					}
				}else 
				{
					//echo $row['ltable'].'/'.$row['lview'].'/'.$row['lmain'].'/'.$row['lrecord'];
					create_content($row['ltable'], $row['lview'], $row['lmain'], $row['lrecord'],$def_navigation_separator);
				}
			}else
			{
				$npos=strpos($str,'/',1);
				$sname=substr($str,1,$npos-1);
				$q='select menuname from basemenu where menuname like "'.$sname.'"';
				$r=mysql_query($q);
				$n=mysql_num_rows($r);
				$surl=substr($str,$npos);
				if($n>0)
				{
					
					$q='select * from '.$sname.' where link like "'.$surl.'"';
					$r=mysql_query($q);
					$n=mysql_num_rows($r);
					if($n>0)
					{
						$row=mysql_fetch_assoc($r);
						create_content($row['ltable'], $row['lview'], $row['lmain'], $row['lrecord'],$def_navigation_separator);
					}else 
					{
						$t1=substr($surl,0,strrpos($surl,'/',-2)+1);
						$t2=substr($surl,strrpos($surl,'/',-2)+1,-1);
						$q='select * from '.$sname.' where link like "'.$t1.'"';
						$r=mysql_query($q);
						$n=mysql_num_rows($r);
						if($n>0)
						{
							$row=mysql_fetch_assoc($r);
							if(preg_match('/^!.+/i',$t2))
							{
								//echo ' =='.$row['lview'].'== ';
								create_content($row['ltable'], $row['lview'], $row['lmain'], $row['lrecord'],$def_navigation_separator);
							}else 
							{
								$q1='select linkid from '.$row['ltable'].' where linkid like "'.$t2.'"';
								$r1=mysql_query($q1);
								$n=mysql_num_rows($r1);
								if($n>0)
								{
									if(isset($_POST['design'])){
										$view=$_POST['design'];
										create_content($row['ltable'], $view.'.html', $row['lmain'], $t2,$def_navigation_separator);
									}
									else{
										$view='mlong';
										create_content($row['ltable'], $view.'.html', 'en_main.html', $t2,$def_navigation_separator); // inner_pages
									}
								}
                                                                else
                                                                {
                                                                    error404();
                                                                }
							}
						}
                                                else 
						{
						    error404();
						}
					}
				}else 
				{
					//echo $surl.' '.$sname;
					if($sname=='catalog')
					{
						$linkid=explode('/',$surl);
                                                var_dump($linkid);
						$sel='select * from '.$sname.' where linkid like "'.$linkid[2].'"';
						$qur=mysql_query($sel);
						if($n=mysql_num_rows($qur)>0)
						{
							
								create_content($sname, 'dlong.html', 'work.html', $linkid[2],$def_navigation_separator);
							
						}
					}
					//echo $sel='select * from '.$sname.' where linkid like"'.str_replace('/','',$surl).'"';
					/*$qur=mysql_query($sel);
					if($n=mysql_num_rows($qur)>0)
					{
						
							create_content($sname, 'long.html', 'main_catalog.html', str_replace('/','',$surl),$def_navigation_separator);
						
					}*/
				}
			}
		}
	}
?>								