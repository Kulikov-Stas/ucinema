<?php

$dbserver	 = 'localhost';
$dbuser	 = 'any';
$dbpass 	 = 'any';
$dbname 	 = 'ucinema';

$msi = new mysqli($dbserver, $dbuser, $dbpass, $dbname);

$days = array('�����������', '�����������', '�������', '�����',
		'�������', '�������', '�������');

if (mysqli_connect_errno()) { 
   printf("����������� � ������� MySQL ����������. ��� ������: %s\n", mysqli_connect_error()); 
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

	$month_arr = array('01'=>'������','02'=>'�������','03'=>'�����','04'=>'������','05'=>'���','06'=>'����','07'=>'����','08'=>'�������','09'=>'��������','10'=>'�������','11'=>'������','12'=>'�������');
        $month = array('01'=>'���','02'=>'����','03'=>'�����','04'=>'���','05'=>'���','06'=>'����','07'=>'����','08'=>'���','09'=>'����','10'=>'���','11'=>'����','12'=>'���');
	$weekday_arr = array('Sunday'=>'�����������','Monday'=>'�����������','Tuesday'=>'�������','Wednesday'=>'�����','Thursday'=>'�������','Friday'=>'�������','Saturday'=>'�������');
  
  
  
    
?>
