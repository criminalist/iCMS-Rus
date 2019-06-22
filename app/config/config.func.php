<?php

class configFunc{
    public static function config_get($vars=null){
        if(empty($vars['name'])){
            return;
        }
        $config = iCMS::$config[$vars['name']];
        if(isset($vars['key']) && is_array($config)){
            $config = $config[$vars['key']];
        }
        return $config;
    }
}
