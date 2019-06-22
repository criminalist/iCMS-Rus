<?php
/**
* 'Идентификатор маршрута' => array (
*    0 => 'Псевдостатическая ссылка',
*    1 => 'Динамическая ссылка',
*  )
*/
defined('iPHP') OR exit('What are you doing?');

return '{
    "forms":[
        "/forms",
        "api.php?app=forms"
    ],
    "forms:save":[
        "/forms/save",
        "api.php?app=forms&do=save"
    ],
    "forms:id":[
        "/forms/{id}/",
        "api.php?app=forms&id={id}"
    ]
}';