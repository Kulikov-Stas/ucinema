<?php
require_once 'u_mail.php';
require_once 'myjson.php';
require_once 'config.php';

if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')
{
    if(isset($_POST['action']))
    {
        switch($_POST['action'])
        {
            case 'calculation':
                echo Calculate();
                break;
        }
    }
    
}

function Calculate()
{
    $departure = iconv('UTF-8', 'cp1251', strip_tags($_POST['departure']));
    $arrival = iconv('UTF-8', 'cp1251', strip_tags($_POST['arrival']));
    $name = iconv('UTF-8', 'cp1251', strip_tags($_POST['name']));
    $weight = iconv('UTF-8', 'cp1251', strip_tags($_POST['weight']));
    $transport = iconv('UTF-8', 'cp1251', strip_tags($_POST['transport']));
    $pack = iconv('UTF-8', 'cp1251', strip_tags($_POST['pack']));
    $company = iconv('UTF-8', 'cp1251', strip_tags($_POST['company']));
    $client = iconv('UTF-8', 'cp1251', strip_tags($_POST['client']));
    $phone = iconv('UTF-8', 'cp1251', strip_tags($_POST['phone']));
    $email = iconv('UTF-8', 'cp1251', strip_tags($_POST['email']));
    $text = iconv('UTF-8', 'cp1251', strip_tags($_POST['text']));
    
    $subject = '������ � ����� '.$_SERVER['HTTP_HOST'];
    $message = "����� �����������: $departure<br />
                    ����� ��������: $arrival<br />
                    ������������: $name<br />
                    ����� ��� �����: $weight<br />
                    ��� ����������: $transport<br />
                    ��� ��������: $pack<br />
                    ��������: $company<br />
                    ���������� ����: $name<br />
                    �������: $phone<br />
                    ��. �����: $email<br />
                    ����������: $text";
    
    $admin = msi_result('SELECT `value` FROM `config` WHERE `key` = "email_admin"');
    $umail = new u_mail();
    if($umail->sendmail($name, $email, $admin, $subject, $message))
    {
        $mjs = new myjson();
        return $mjs->fill(array('mes'=>'eee'));
    }
}
?>
