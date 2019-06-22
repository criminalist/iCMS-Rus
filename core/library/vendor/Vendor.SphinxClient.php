<?php

defined('iPHP') OR exit('Oops, something went wrong');
defined('iPHP_LIB') OR exit('iPHP vendor need define iPHP_LIB');
require dirname(__FILE__) .'/SphinxClient/sphinx.class.php';

function SphinxClient($hosts) {
	if(isset($GLOBALS['iSphinx'])){
		$GLOBALS['iSphinx']->init();
	}else{
		if(empty($hosts)){
			return false;
		}

		$GLOBALS['iSphinx'] = new SphinxClient();
		if(strstr($hosts, 'unix:')){
			$hosts	= str_replace("unix://",'',$hosts);
			$GLOBALS['iSphinx']->SetServer($hosts);
		}else{
			list($host,$port)=explode(':',$hosts);
			$GLOBALS['iSphinx']->SetServer($host,(int)$port);
		}
	}
	return $GLOBALS['iSphinx'];
}
