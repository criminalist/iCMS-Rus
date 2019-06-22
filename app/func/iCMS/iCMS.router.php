<?php

function iCMS_router($vars){
	if(empty($vars['url'])){
		echo 'javascript:;';
		return;
	}
	$router = $vars['url'];
	unset($vars['url'],$vars['app']);
	$url = iURL::router($router);
	$vars['query'] && $url = iURL::make($vars['query'],$url);

	if($url && !iFS::checkHttp($url) && $vars['host']){
		$url = rtrim(iCMS_URL,'/').'/'.ltrim($url, '/');;
	}
	echo $url?$url:'javascript:;';
}
