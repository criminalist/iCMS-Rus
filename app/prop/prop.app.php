<?php

class propApp {
	public $methods = array('iCMS');
	public static function value($field=null,$app=null,$sort=true) {
        $app  && $pieces[] = $app;
        $field&& $pieces[] = $field;
        if(empty($pieces)) return false;

        $keys = implode('/', $pieces);
		$propArray 	= iCache::get("prop/{$keys}");
		$propArray && $sort && sort($propArray);
        return $propArray;
	}
    public static function field($field,$app=null) {
        $variable = self::value($field,$app,false);
        $propArray = array();
        if($variable)foreach ($variable as $key => $value) {
            $propArray[$value['val']] = $value;
        }
        return $propArray;
    }
    public static function app($app) {
        return self::value(null,$app,false);
    }
    public static function url($value,$url=null) {
        $query = array();
        $query[$value['field']] = $value['val'];
        return iURL::make($query,$url);
    }
}
