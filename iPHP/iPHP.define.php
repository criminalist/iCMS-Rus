<?php

defined('iPHP') OR define('iPHP', TRUE);
//---------------Конфигурация подключения к базе данных------------------
defined('iPHP_DB_TYPE') OR define('iPHP_DB_TYPE','mysql');//Имя базы данных
defined('iPHP_DB_NAME') OR define('iPHP_DB_NAME','iPHP');//Имя базы данных
defined('iPHP_DB_USER') OR define('iPHP_DB_USER','root');//Имя пользователя базы
defined('iPHP_DB_PASSWORD') OR define('iPHP_DB_PASSWORD','');//Пароль к базе
defined('iPHP_DB_HOST') OR define('iPHP_DB_HOST','localhost');//Имя сервера или ip сервера, зачастую по умолчанию localhost
defined('iPHP_DB_PORT') OR define('iPHP_DB_PORT','3306');
defined('iPHP_DB_CHARSET') OR define('iPHP_DB_CHARSET','utf8');//MYSQL编码设置.如果您的程序出现乱码现象,需要设置此项来修复. 请不要随意更改此项,否则将可能导致系统出现乱码现象
defined('iPHP_DB_PREFIX') OR define('iPHP_DB_PREFIX','iPHP_');//Префикс
defined('iPHP_DB_PREFIX_TAG') OR define('iPHP_DB_PREFIX_TAG','#iPHP@__');// Замена префикса имени таблицы SQL
//defined('iPHP_DB_COLLATE') OR define('iPHP_DB_COLLATE', '');
//----------------------------------------
defined('iPHP_KEY') OR define('iPHP_KEY','Jq4UDnkVkcywhv4BgfpcWemBAFKc5khQ');
defined('iPHP_CHARSET') OR define('iPHP_CHARSET','UTF-8');
//---------------Настройки cookie-------------------------
defined('iPHP_COOKIE_DOMAIN') OR define ('iPHP_COOKIE_DOMAIN','');
defined('iPHP_COOKIE_PATH') OR define ('iPHP_COOKIE_PATH','/');
defined('iPHP_COOKIE_PRE') OR define ('iPHP_COOKIE_PRE','iPHP_');
defined('iPHP_COOKIE_TIME') OR define ('iPHP_COOKIE_TIME','31536000');
defined('iPHP_AUTH_IP') OR define ('iPHP_AUTH_IP',true);
defined('iPHP_UAUTH_IP') OR define ('iPHP_UAUTH_IP',false);
//---------------Настройки времени------------------------
defined('iPHP_TIME_ZONE') OR define('iPHP_TIME_ZONE',"Asia/Shanghai");
defined('iPHP_DATE_FORMAT') OR define('iPHP_DATE_FORMAT','Y-m-d H:i:s');
defined('iPHP_TIME_CORRECT') OR define('iPHP_TIME_CORRECT',"0");

defined('iPHP_MULTI_SITE') OR define('iPHP_MULTI_SITE',false);
defined('iPHP_MULTI_DOMAIN') OR define('iPHP_MULTI_DOMAIN',false);

//defined('iPHP_DEBUG') OR define('iPHP_DEBUG',false);
//defined('iPHP_TPL_DEBUG') OR define('iPHP_TPL_DEBUG',false);
//defined('iPHP_URL_404') OR define('iPHP_URL_404','');

define('iPHP_CORE',   __DIR__."/core");
define('iPHP_LIB',    __DIR__."/library");
define('iPHP_VENDOR', __DIR__."/vendor");

define('iPATH',strtr(realpath(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR,'\\','/'));

define('iPHP_APP_DIR',    iPATH."app");
define('iPHP_APP_CORE',   iPATH."core");
define('iPHP_APP_VENDOR', iPATH."vendor");
define('iPHP_APP_LIB',    iPATH."core/library");
define('iPHP_APP_CACHE',  iPATH."cache");
define('iPHP_TPL_DIR',    iPATH."template");
define('iPHP_CONF_DIR',   iPATH."cache/conf");
define('iPHP_TPL_CACHE',  iPATH."cache/template");


defined('iPHP_APP') OR define('iPHP_APP',"iPHP");//Название приложения
defined('iPHP_APP_INIT') OR define('iPHP_APP_INIT',true);//运行初始化
defined('iPHP_APP_DEFINE') OR define('iPHP_APP_DEFINE',null);//自定义运行应用 null/MY_
defined('iPHP_APP_MAIL') OR define('iPHP_APP_MAIL',"master@iiiphp.com");
defined('iPHP_MEMORY_LIMIT') OR define('iPHP_MEMORY_LIMIT', '128M');

defined('iPHP_TPL_VAR') OR define('iPHP_TPL_VAR',iPHP_APP);
defined('iPHP_TPL_FUN') OR define('iPHP_TPL_FUN',iPHP_APP_DIR.'/func');

defined('iPHP_ERROR_HEADER') OR define('iPHP_ERROR_HEADER',true);
defined('iPHP_SHELL') OR define('iPHP_SHELL',PHP_SAPI=='cli'?true:false);
defined('iPHP_PROTOCOL') OR define('iPHP_PROTOCOL',iPHP_APP.'://');
defined('iPHP_CORE_CLASS') OR define('iPHP_CORE_CLASS',
    'iCache,iFileCache,'.
    'iDevice,iURL,'.
    'iFS,iHttp,iPicture,iThumb,Gmagick,'.
    'iDB,iSQL,iMap,iUtils,iDefine,iVendor,'.
    'iUI,iPages,iPagination,iView,iTemplateLite,iQuery,'.
    'iSecurity,iWAF,'.
    'iTranslit,iSeccode'
);
