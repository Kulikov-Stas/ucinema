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
    
    $subject = 'Письмо с сайта '.$_SERVER['HTTP_HOST'];
    $message = "Пункт отправления: $departure<br />
                    Пункт прибытия: $arrival<br />
                    Наименование: $name<br />
                    Общий вес груза: $weight<br />
                    Тип транспорта: $transport<br />
                    Тип упаковки: $pack<br />
                    Компания: $company<br />
                    Контактное лицо: $name<br />
                    Телефон: $phone<br />
                    Эл. почта: $email<br />
                    Примечания: $text";
    
    $admin = msi_result('SELECT `value` FROM `config` WHERE `key` = "email_admin"');
    $umail = new u_mail();
    if($umail->sendmail($name, $email, $admin, $subject, $message))
    {
        $mjs = new myjson();
        return $mjs->fill(array('mes'=>'eee'));
    }
}
?>
