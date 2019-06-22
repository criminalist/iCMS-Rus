<?php

function iCMS_config($vars=null){
	$config = iCMS::$config;
	$vars['name'] && $config = $config[$vars['name']];
	if(isset($vars['key']) && is_array($config)){
		$config = $config[$vars['key']];
	}
	return $config;
}
