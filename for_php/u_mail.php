<?php
/*
 * Class for sending mail
 */
class u_mail{

    #������ ���������� 
     private		$to;
     private		$headers;
     private		$subject;
     private		$message;

    #������� �������� ����� 
    public function sendmail($fromname,$from,$to,$subject,$message)
    {
        $send_charset = 'windows-1251';
        $data_charset = 'utf-8';
        if(substr(PHP_OS, 0, 3) == 'WIN')
        $n = "\r\n";
        else
        $n = "\n";
        $this->headers  =   'Content-type: text/html; charset="windows-1251"'. $n;
        $this->headers .=   'From: '.'=?Windows-1251?B?'.base64_encode($fromname).'?= <'.$from.'>'. $n;		
        $this->headers .=   'MIME-Version: 1.0'. $n; 
        $this->headers .=   'Date: '. date('D, d M Y H:i:s O') . $n;
        $this->to       =   $to;
        $this->subject	=   '=?Windows-1251?B?'.base64_encode($subject).'?=';
        $this->message	=   $message;
        return mail($this->to, $this->subject, $this->message, $this->headers);
    }

    #������������� ���������� ������
    public function __construct()
    {}
}

if(isset($_POST['booking']) && $_POST['booking'])
{
    $admin = 'mirnij@bk.ru';
    $fromname = $_POST['txtName'];
    $from = $_POST['txtEmail'];
    $subject = '������������';
    $message = '���� ������: ' . $_POST['txtDateIn'] . '<br />' .
           '���� ������: ' . $_POST['txtDateOut'] . '<br / >' .
           '���-�� �������: ' . $_POST['txtNumAmount'] . '<br / >' .
           '���������� ������� ����: ' . $_POST['txtChildPl'] . '<br / >' .
           '��������� ����������: ' . $_POST['txtCategory'] . '<br / >' .
           '���: ' . $_POST['txtName'] . '<br / >' .
           '������, �����: ' . $_POST['txtCountry'] . ', ' . $_POST['txtCity'] . '<br />' .
           '�������: ' . $_POST['txtPhone'] . '<br / >' .
           'Email: ' . $_POST['txtEmail'];
    $umail = new u_mail();
    $umail->sendmail($fromname, $from, $admin, $subject, $message);
    $umail->sendmail('������������� ����� "������"', $admin, $from, $subject, '��� ������ �� ������������ ���������. � ���� �������� �� ���������� ������ ��������.');
}
?>
