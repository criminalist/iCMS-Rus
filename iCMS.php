<?php

define('iPHP',TRUE);
//Название приложения
define('iPHP_APP','iCMS');
//Электронная почта поддержки
define('iPHP_APP_MAIL','support@iCMSdev.com');
//конфигурация
require_once __DIR__.'/config.php';
//Каркасный файл iPHP
require_once __DIR__.'/iPHP/iPHP.php';
//Версия программы
require_once __DIR__.'/core/iCMS.version.php';
//Основной класс
require_once __DIR__.'/core/iCMS.class.php';
//Общие функции программы
require_once __DIR__.'/core/iCMS.func.php';
//Инициализация
iPHP_APP_INIT && iCMS::init();
