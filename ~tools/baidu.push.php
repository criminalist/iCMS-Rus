#!/usr/bin/env php
<?php
define('iPHP_DEBUG',true);
set_time_limit(0);
require dirname(__file__).'/../iCMS.php';
ini_set('memory_limit','512M');

$lastIdFile = __DIR__.'/baidu.push.txt';
file_exists($lastIdFile) OR file_put_contents($lastIdFile, 0);
$lastId  = file_get_contents($lastIdFile);

//站长平台推送配置
define('BAIDU_ACCESS_TOKEN','access_token');
define('PC_HOST','www.ooxx.com');
define('MO_HOST','m.ooxx.com');

$start   = 0;
$preNum  = 200;
$where   = "`status`='1' ";
// $where.= " AND `pubdate`>='".strtotime(date("Ymd").'000001')."'"; //只提交当天的文章
$max     = iDB::value("SELECT count(id) FROM `#iCMS@__article` where {$where} and `id`>$lastId;");
$page    = ceil($max/$preNum);

for ($i=0; $i <=$page; $i++) {
    baidu_push($preNum);
}

function baidu_push($preNum=10){
    global $where,$lastId,$lastIdFile;

    $rs   = iDB::all("
        SELECT `id` FROM `#iCMS@__article`
        where {$where} and `id`>$lastId
        order by id ASC
        limit 0,{$preNum};
    ");

    $murls      = array();
    $urls       = array();
    $ids        = array();

    foreach ((array)$rs as $key => $a) {
        $id     = $a['id'];
        $rs     = article::row($id);
        $C      = category::get($rs['cid']);
        $iurl   = (array)iURL::get('article',array($rs,$C));
        $lastId = $id;
        $urls[] = $iurl['href'];
        plugin_baidu::RPC2($iurl['href']);
        if($iurl['mobile']['url']){
            $murls[] = $iurl['mobile']['url'];
            plugin_baidu::RPC2($iurl['mobile']['url']);
        }
        $ids[]  = $id;
    }
    iCMS::$config['api']['baidu']['sitemap']['site'] = PC_HOST;
    iCMS::$config['api']['baidu']['sitemap']['access_token'] = BAIDU_ACCESS_TOKEN;
    $wflag = plugin_baidu::ping($urls);
    

    iCMS::$config['api']['baidu']['sitemap']['site'] = MO_HOST;
    iCMS::$config['api']['baidu']['sitemap']['access_token'] = BAIDU_ACCESS_TOKEN;
    $mflag = plugin_baidu::ping($murls);

    if($wflag || $mflag){
        print_r(implode(',', $ids));
        echo PHP_EOL;
        print_r(plugin_baidu::$out);
        file_put_contents($lastIdFile, $lastId);
    }else{
        print_r(plugin_baidu::$out);
        exit;
    }
    echo PHP_EOL.PHP_EOL.PHP_EOL;
}
