<?php
/**
* '路由标识' => array (
*    0 => '伪静态链接',
*    1 => '动态链接',
*  )
*/
defined('iPHP') OR exit('What are you doing?');

return '{
    "favorite":[
        "/favorite",
        "api.php?app=favorite"
    ],
    "favorite:id":[
        "/favorite/{id}/",
        "api.php?app=favorite&id={id}"
    ]
}';