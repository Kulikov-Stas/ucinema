<?php
header("Content-Type: text/xml");
echo '<?xml version="1.0" encoding="windows-1251"?>';
require_once('./config.php');

function getFilm(){
    global $msi;
    $url = 'http://'.$_SERVER['HTTP_HOST'];
    $q = 'SELECT `id` , CONCAT_WS("/","'.$url.'",`linkid`) AS link , `nameid`, language FROM film WHERE id IN
                                                            (SELECT nameid FROM shedule WHERE `date`>= DATE(NOW()) AND visible=1) 
                                                            AND visible=1 AND isfilm=1';
    $r = $msi->query($q);
    if (!$r)
     die($q.' '.__FILE__.__LINE__);
    $res = '';
    while ($row = $r->fetch_object())
     $res.=format($row,'film');
    
 echo '<films>'.$res.'</films>';
}

function getTimes(){
   global $msi;
   $q = 'SELECT s.`date`, s.`time`, s.nameid,s.cost FROM shedule s 
   JOIN film f 
   ON  f.id=s.nameid
   WHERE s.`date`>= DATE(NOW()) AND f.visible=1 AND f.isfilm=1 ORDER BY s.`date` ASC,s.`time` ASC'; 
   $r = $msi->query($q);
    if (!$r)
     die($q.' '.__FILE__.__LINE__);
    $res = '';
    $d='';
   for ($i = 0;;++$i){
   $row = $r->fetch_object();
   if (!$row) break;
   
       if ($i === 0)
            $res='<day date="'.$row->date.'">'.format($row,'times'); 
        if ($d == $row->date)    
            $res.=format($row,'times');          
        else
             $res.='</day><day date="'.$row->date.'">'.format($row,'times');  
          
       $d = $row->date;
      
       }
   
   echo '<showtimes>'.$res.'</day></showtimes>';
    
}


function format($row,$type){
   return ($type=='film')?'<film id="'.$row->id.'" url="'.$row->link.'" lang="'.$row->language.'">
            <title>'.$row->nameid.'</title>
            </film>':
            '<show date="'.$row->date.'" time="'.$row->time.'" film-id="'.$row->nameid.'" price="'.$row->cost.'" technology="none"></show>';
            
}

/*================================*/



echo '<u-cinema>';
getFilm();
getTimes();
echo '</u-cinema>';
?>