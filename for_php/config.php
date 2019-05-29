<?php

$dbserver	 = 'localhost';
$dbuser	 = 'any';
$dbpass 	 = 'any';
$dbname 	 = 'ucinema';

$msi = new mysqli($dbserver, $dbuser, $dbpass, $dbname);

$days = array('Âîñêðåñåíüå', 'Ïîíåäåëüíèê', 'Âòîðíèê', 'Ñðåäà',
		'×åòâåðã', 'Ïÿòíèöà', 'Ñóááîòà');

if (mysqli_connect_errno()) { 
   printf("Ïîäêëþ÷åíèå ê ñåðâåðó MySQL íåâîçìîæíî. Êîä îøèáêè: %s\n", mysqli_connect_error()); 
   exit; 
}

function msi_result($Q)
{
    global $msi;
    $r = $msi->query($Q);
    if($r && $r->num_rows)
    {
        $row = $r->fetch_row();
        return $row[0];
    }
    return null;
}

	$month_arr = array('01'=>'ßÍÂÀÐß','02'=>'ÔÅÂÐÀËß','03'=>'ÌÀÐÒÀ','04'=>'ÀÏÐÅËß','05'=>'ÌÀß','06'=>'ÈÞÍß','07'=>'ÈÞËß','08'=>'ÀÂÃÓÑÒÀ','09'=>'ÑÅÍÒßÁÐß','10'=>'ÎÊÒßÁÐß','11'=>'ÍÎßÁÐß','12'=>'ÄÅÊÀÁÐß');
        $month = array('01'=>'ÿíâ','02'=>'ôåâð','03'=>'ìàðòà','04'=>'àïð','05'=>'ìàÿ','06'=>'èþíÿ','07'=>'èþëÿ','08'=>'àâã','09'=>'ñåíò','10'=>'îêò','11'=>'íîÿá','12'=>'äåê');
	$weekday_arr = array('Sunday'=>'ÂÎÑÊÐÅÑÅÍÜÅ','Monday'=>'ÏÎÍÅÄÅËÜÍÈÊ','Tuesday'=>'ÂÒÎÐÍÈÊ','Wednesday'=>'ÑÐÅÄÀ','Thursday'=>'×ÅÒÂÅÐÃ','Friday'=>'ÏßÒÍÈÖÀ','Saturday'=>'ÑÓÁÁÎÒÀ');
  
  
  
    
?>
