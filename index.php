<?php
// Включаем вывод ошибок
ini_set('display_errors',1);
error_reporting(E_ALL);

// Подключение файлов
define('ROOT', dirname(__FILE__));
require_once (ROOT.'/components/router.php');

$router = new Router();
$router->run();