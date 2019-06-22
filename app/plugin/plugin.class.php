<?php

class plugin{
    public static $flag = array();
    public static function init($class=null) {
        $class = str_replace('plugin_', '', $class);
        $class && self::$flag[$class] = true;
    }
    public static function library($file) {
        $path = iPHP_APP_DIR . '/'.__CLASS__.'/library/'.$file.'.php';
        iPHP::import($path);
    }
    public static function import($file) {
        $path = iPHP_APP_DIR . '/'.__CLASS__.'/'.$file.'.php';
        iPHP::import($path);
    }
}

