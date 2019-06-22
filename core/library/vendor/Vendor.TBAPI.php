<?php

defined('iPHP') OR exit('Oops, something went wrong');
defined('iPHP_LIB') OR exit('iPHP vendor need define iPHP_LIB');

require dirname(__FILE__) .'/TBAPI/tbapi.class.php';

function TBAPI() {
	if(isset($GLOBALS['TBAPI'])) return $GLOBALS['TBAPI'];

	$GLOBALS['TBAPI'] = new TBAPI;
	return $GLOBALS['TBAPI'];
}
