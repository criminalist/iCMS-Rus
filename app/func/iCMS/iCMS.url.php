<?php

function iCMS_url($vars){
    if(isset($vars['url'])){
        $url = $vars['url'];
        unset($vars['url']);
    }
    $ret = $vars['ret'];
    unset($vars['app'],$vars['as'],$vars['ret']);
    $url = iURL::make($vars,$url);
    if($ret){
        echo $url;
    }
    return $url;
}
