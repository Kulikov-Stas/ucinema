<?php

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('Europe/Moscow');
$hacker = '����� � ���, ������ ������!!!';
define('ADMIN_DIR', str_replace("\\", "/", getcwd()));

// ��������� ��
$dbLocation	 = 'localhost';
$dbUser		 = 'any';
$dbPassword	 = 'any';
$dbName 	 = 'ucinema';

// ��������� ������� �����������������
$admin_default_mode = 'razdel'; #����� ������ ����������������� �� ���������

// ��������� 
$default_page = 'home'; #����� ����� �� ���������

?>