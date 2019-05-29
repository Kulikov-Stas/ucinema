<?php

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('Europe/Moscow');
$hacker = 'Пошел в зад, ебаный ХАЦКЕР!!!';
define('ADMIN_DIR', str_replace("\\", "/", getcwd()));

// Параметры БД
$dbLocation	 = 'localhost';
$dbUser		 = 'any';
$dbPassword	 = 'any';
$dbName 	 = 'ucinema';

// Параметры системы администрирования
$admin_default_mode = 'razdel'; #Режим панели администрирования по умолчанию

// Параметры 
$default_page = 'home'; #Режим сайта по умолчанию

?>