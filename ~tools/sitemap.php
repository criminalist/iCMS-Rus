#!/usr/bin/env php
<?php
define('iPHP_DEBUG', true);
define('iPHP_TPL_DEBUG', true);
require_once(dirname(__FILE__).'/../iCMS.php');
error_reporting(E_ALL ^ E_NOTICE);
ini_set('memory_limit','2048M');

$sitemap_dir  = 'sitemap';
$sitemap_path = iFS::path(iPATH.iCMS::$config['router']['dir'].$sitemap_dir);
$sitemap_url  = iCMS_URL.'/'.$sitemap_dir;

iView::$gateway ='html';

$xml = iView::fetch('/tools/sitemap.baidu.category.htm');
iFS::mkdir($sitemap_path);
iFS::write($sitemap_path.'/category.xml',$xml);

$tag = iView::fetch('/tools/sitemap.baidu.tag.htm');
iFS::mkdir($sitemap_path);
iFS::write($sitemap_path.'/tag.xml',$tag);

$category   = iDB::all("
	SELECT `cid`
	FROM `#iCMS@__category`
	WHERE `rootid`='0' and `status` ='1' and `appid` ='1'
	ORDER BY cid ASC
");

foreach ((array)$category as $key => $cat) {
	iView::assign('cid',(int)$cat['cid']);
	$xml = iView::fetch('/tools/sitemap.baidu.htm');
    $xmlPath = $sitemap_path.'/'.$cat['cid'].'.xml';
	iFS::write($xmlPath,$xml);
	echo $xmlPath.PHP_EOL;
	echo $sitemap_url.'/'.$cat['cid'].'.xml'.PHP_EOL;
}
echo $sitemap_url.'/category.xml'.PHP_EOL;
echo $sitemap_url.'/tag.xml'.PHP_EOL;
