<?php

function iCMS_pages($vars){
	$c = iPages::$config;
	if(isset($vars['url'])){
		$c['url'] = $vars['url'];
		if(strtolower($vars['url'])==='self'){
			$c['url'] = $_SERVER['REQUEST_URI'];
		}
	}
	$query = array('page'=>'{P}');
	$vars['query'] && $query = array_merge($query,$vars['query']);

	iPages::$setting['index'] = iURL::make(array('page'=>null),$c['url']);
	$c['url'] = iURL::make($query,$c['url']);

	return iPagination::assign($c);
}
