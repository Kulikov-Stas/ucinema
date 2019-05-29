<?php

function get_slider_info(){
    global $msi;
    $q = 'SELECT f.linkid,f.slider,f.nameid,LEFT(f.`description`,100) AS descr, f.original,
            MIN(s.`date`) as mindate, MAX(s.`date`) AS maxdate
            FROM film f LEFT JOIN shedule s
            ON s.nameid = f.id
            WHERE f.slider<>"" AND f.visible=1 GROUP BY f.id ORDER BY f.`order`';
     // echo $q;
    return $msi->query($q);
   
}

function bigSlider(){
    $r = get_slider_info();
    $result='';
    if (!$r) return 'ERROR '.__FILE__.__LINE__;
    while ($row = $r->fetch_object())
        $result.='<img src="'.$row->slider.'" alt="" width="1280" height="525" />';
     
   return $result; 
}

function smallSlider(){
    $r = get_slider_info();
    $result='';
    if (!$r) return 'ERROR '.__FILE__.__LINE__;
    while ($row = $r->fetch_object())
        $result.='<div style="cursor:pointer">
                        <a href="/affiche/'.$row->linkid.'/" style="display:none"></a>
						<h3 class="slide-filmname">'.$row->nameid.'</h3>
						<section class="active-info">
							<h1>'.$row->nameid.'</h1>
							<p>'.$row->descr.'</p>
							<section class="date-holder">
								<span class="date-begin">'.$row->mindate.'</span><span class="date-end">'.$row->maxdate.'</span>
							</section>
						</section>
						<img src="'.$row->slider.'" width="229" height="144" />
					</div>';
     
   return $result; 
}

/*
 * function slide_main
 * @param $arg
 */

function slide_main() {
    global $msi;
    $content='';
	    $q = "SELECT f.slider,f.nameid,LEFT(f.`description`,100) AS descr
            FROM film f 
            WHERE f.slider<>'' AND f.visible=1 AND f.linkid = ".$_GET['link1'];
			  $r = $msi->query($q);                                 
			  $row = $r->fetch_assoc();
				$content .= '<img class="main-poster" src="'.$row['slider'].'" alt="" width="1280" height="525" />';
			  return $content;
}

function slide_left() {
    global $msi;
    $content='';
	    $q = "SELECT f.slider,f.nameid,LEFT(f.`description`,100) AS descr
            FROM film f 
            WHERE f.slider<>'' AND f.visible=1 AND f.linkid = ".$_GET['link1']." - 1";
			  $r = $msi->query($q);                                 
			  $row = $r->fetch_assoc();
				$content .= '<img class="another-poster1" src="'.$row['slider'].'" alt="" width="1280" height="525" />';			  return $content;
}

function slide_right() {
    global $msi;
    $content='';
	    $q = "SELECT f.slider,f.nameid,LEFT(f.`description`,100) AS descr
            FROM film f 
            WHERE f.slider<>'' AND f.visible=1 AND f.linkid = ".$_GET['link1']." + 1";
			  $r = $msi->query($q);                                 
			  $row = $r->fetch_assoc();
				$content .= '<img class="another-poster2" src="'.$row['slider'].'" alt="" width="1280" height="525" />';			  return $content;
}

/*
 * function slide_text
 * @param $arg
 */

function slide_text() {
    global $msi;
	$q = 'SELECT film.slide_text FROM film WHERE linkid = '.$_GET['link1'];
			  $r = $msi->query($q);                                 
			  $row = $r->fetch_assoc();
			  return '<h1>'.$row['slide_text'].'</h1>';

}

/*
 * function count_now
 * @param $arg
 */

function count_now() {
    global $msi;
	$q = "SELECT COUNT(*) as count FROM film
        INNER JOIN shedule ON film.id = shedule.nameid
        WHERE unix_timestamp(concat(`date`,' ',`time`)) > unix_timestamp(now())
        AND year(concat(`date`,' ',`time`)) = year(now()) AND week(concat(`date`,' ',`time`), 1) = week(now(), 1)";
			  $r = $msi->query($q);                                 
			  $row = $r->fetch_assoc();
			  return $row['count'];
}

/*
 * function count_next
 * @param $arg
 */

function count_next() {
    global $msi;
	$q = "SELECT COUNT(*) as count FROM film
        INNER JOIN shedule ON film.id = shedule.nameid
        WHERE unix_timestamp(concat(`date`,' ',`time`)) > unix_timestamp(now())
        AND year(concat(`date`,' ',`time`)) = year(now()) AND week(concat(`date`,' ',`time`), 1) = week(now(), 1) + 1";
			  $r = $msi->query($q);                                 
			  $row = $r->fetch_assoc();
			  return $row['count'];
}

/*
 * function count_soon
 * @param $arg
 */

function count_soon() {
    global $msi;
	$q = "SELECT COUNT(*) as count FROM film
        INNER JOIN shedule ON film.id = shedule.nameid
        WHERE unix_timestamp(concat(`date`,' ',`time`)) > unix_timestamp(now())
        AND year(concat(`date`,' ',`time`)) = year(now()) AND week(concat(`date`,' ',`time`), 1) > week(now(), 1) + 1";
			  $r = $msi->query($q);                                 
			  $row = $r->fetch_assoc();
			  return $row['count'];
}

/**
 * function book
 */

function book() {
    global $msi;
    $content='';
	    $q = "SELECT id, link FROM film WHERE linkid = ".$_GET['link1'];
			$r = $msi->query($q);                                 
			$row = $r->fetch_assoc();
			$content = '<form method="get" action="'.$row['link'].'?film-id='.$row['id'].'">
				<button class="hold film-hold" type="submit">Бронировать билеты</button>
				<input type="hidden" name="film-id" value="'.$row['id'].'" />
				    </form>';
			return $content;			
}
?>