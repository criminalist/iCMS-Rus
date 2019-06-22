#!/usr/local/php/bin/php
<?php
/**
 * iCMS - i Content Management System
 * Copyright (c) 2007-2012 idreamsoft.com iiimon Inc. All rights reserved.
 *
 * @author coolmoo <idreamsoft@qq.com>
 * @site http://www.idreamsoft.com
 * @licence http://www.idreamsoft.com/license.php
 * @version 6.0.0
 * @$Id: tag.update.php 156 2013-03-22 13:40:07Z coolmoo $
 */
require dirname(__file__).'/../iCMS.php';

$rs   = iDB::all("SELECT `id`,`name` FROM `#iCMS@__tag` where tkey=''");


foreach ((array)$rs as $key => $a) {
	$id   = $a['id'];
	$name = $a['name'];
	$tkey = iPinyin::get($name,iCMS::$config['tag']['tkey']);
	iDB::query("update `#iCMS@__tag` set `tkey`='$tkey' where `id`='$id';");
	echo $id.$name.'  '.$tkey.PHP_EOL;
}
