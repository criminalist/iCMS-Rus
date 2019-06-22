<?php

function iCMS_lang($vars){
	if(empty($vars['key']))return;

	echo iUI::lang($vars['key']);
}
