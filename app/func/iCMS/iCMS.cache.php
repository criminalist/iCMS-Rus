<?php

function iCMS_cache($vars=null){
	if(empty($vars['key'])){
		return false;
	}
	if(isset($vars['value'])){
		$time = isset($vars['time'])?$vars['time']:0;
		iCache::set($vars['key'],$vars['value'],$time);
	}
	$skey = isset($vars['skey'])?$vars['skey']:null;
	return iCache::get($vars['key'],$skey);
}
