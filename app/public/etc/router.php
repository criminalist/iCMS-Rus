<?php
/**
* 'Идентификатор маршрута' => array (
*    0 => 'Псевдостатическая ссылка',
*    1 => 'Динамическая ссылка',
*  )
*/
defined('iPHP') OR exit('What are you doing?');

return '{
    "public:seccode":[
        "/public/seccode",
        "api.php?app=public&do=seccode"
    ],
    "public:agreement":[
        "/public/agreement",
        "api.php?app=public&do=agreement"
    ]
}';