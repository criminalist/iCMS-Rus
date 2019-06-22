<?php

//---------------Конфигурация подключения к базе данных------------------
define('iPHP_DB_TYPE','mysql');//Тип базы данных, поддерживаются mysql, sqlite (SQLite3)
define('iPHP_DB_HOST','localhost');//Имя сервера или ip сервера, зачастую по умолчанию localhost
define('iPHP_DB_PORT','3306'); //Порт базы данных
define('iPHP_DB_USER','icms');//Имя пользователя базы
define('iPHP_DB_PASSWORD','icms');//Пароль к базе
define('iPHP_DB_NAME','icms'); //Имя базы данных
define('iPHP_DB_PREFIX','icms_');// Префикс имени таблицы
define('iPHP_DB_CHARSET','utf8');//Кодировка базы MYSQL, рекомендуется оставить по умолчанию в UTF8
define('iPHP_DB_PREFIX_TAG','#iCMS@__');//Замена префикса имени таблицы SQL
//define('iPHP_DB_COLLATE', 	'');
//----------------------------------------
define('iPHP_KEY','enPks6TLPmxf3zEbC4S5VjhkRkeLvZ88sqrTjEpxY8mCTf8bSGx9q62Ycy8RpHC9');
define('iPHP_CHARSET','utf-8');
//---------------Настройки cookie-------------------------
define('iPHP_COOKIE_DOMAIN','');
define('iPHP_COOKIE_PATH','/');
define('iPHP_COOKIE_PRE','iCMS');
define('iPHP_COOKIE_TIME','86400');
define('iPHP_AUTH_IP',true);
define('iPHP_UAUTH_IP',false);
//---------------Настройки времени------------------------
define('iPHP_TIME_ZONE',"Asia/Shanghai");
define('iPHP_DATE_FORMAT','Y-m-d H:i:s');
//define('iPHP_TIME_CORRECT',"0"); //Динамическая настройка времени
//---------------Шаблон------------------------
define('iPHP_TPL_VAR','iCMS');//Тег шаблона
//---------------DEBUG------------------------
//define('iPHP_DEBUG',false);
//define('iPHP_TPL_DEBUG',false);
//define('iPHP_URL_404','');
//---------------Режим мультисайт-----------------------
//define('iPHP_MULTI_SITE',true);
//define('iPHP_MULTI_DOMAIN',true);
