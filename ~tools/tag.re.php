#!/usr/bin/env php
<?php
require dirname(__file__).'/../iCMS.php';
ini_set('memory_limit','512M');
$start  = 0;
$preNum = 1000;
$count  = iDB::value("SELECT count(`id`) FROM `#iCMS@__article` where `status`='1' ;");
$page   = ceil($count/$preNum);
tag::$appid = iCMS_APP_ARTICLE;

iDB::query('TRUNCATE TABLE `#iCMS@__tags`');
iDB::query('TRUNCATE TABLE `#iCMS@__tags_map`');

for ($i=0; $i <=$page; $i++) {
	$offset = $i*$preNum;
	run($offset,$preNum);
}
function run($offset=0,$preNum=10){
	echo 'offset='.$offset."\n";

	$article   = iDB::all("SELECT `id`,`cid`,`tags` FROM `#iCMS@__article` where `status`='1' order by id asc limit {$offset},{$preNum};");

	foreach ((array)$article as $key => $a) {
		$aid  = $a['id'];
		$cid  = $a['cid'];
		$tags = $a['tags'];
		$tags && tag::add($tags,1,$aid,$cid);
		unset($article[$key]);
		echo date("Y-m-d H:i:s ")."article[$aid] tags=>".$tags.PHP_EOL;
	}
	unset($article);
}
