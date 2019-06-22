#!/usr/bin/env php
<?php

define('iPHP_DEBUG',true);
set_time_limit(0);
require dirname(__file__).'/../iCMS.php';

files::init(array('userid'=> '1'));

ini_set('memory_limit','1024M');
iDB::$show_errors            = true;
iHttp::$CURLOPT_TIMEOUT        = "30";
iHttp::$CURLOPT_CONNECTTIMEOUT = "3";
iHttp::$CURLOPT_USERAGENT      = 'Baiduspider-image+(+http://www.baidu.com/search/spider.htm)';

$where  = "1=1";
$start  = 0;
$preNum = 100;
$max    = iDB::value("
    SELECT count(id) FROM `#iCMS@__files`
    WHERE {$where} order by id desc;"
);
$page   = ceil($max/$preNum);
iDB::query("set interactive_timeout=24*3600");
echo $page.PHP_EOL;

for ($i=0; $i <=$page; $i++) {
    $offset = ($i*$preNum);
    run($offset,$preNum);
}


function run($offset=0,$preNum=10){
    global $where;
    echo 'offset='.$offset."\n";
    $ids_array   = iDB::all("
        SELECT id FROM `#iCMS@__files`
        WHERE {$where}
        order by id desc
        LIMIT {$offset},{$preNum}
    ");
    $ids       = iSQL::values($ids_array);
    $ids       = $ids?$ids:'0';
    $where_sql = "WHERE id IN({$ids})";

    $filedata   = iDB::all("SELECT * FROM `#iCMS@__files` {$where_sql}");

    foreach ((array)$filedata as $key => $row) {
        $FilePath = $row['path'].$row['filename'].'.'.$row['ext'];
        $FileRootPath = iFS::fp($FilePath,'+iPATH');
        echo $FilePath.PHP_EOL;
        list($owidth, $oheight, $otype) = @getimagesize($FileRootPath);
        if(empty($otype)){
            if($row['ofilename']){
                iFS::$CALLABLE['write'] = array('files_cloud','upload');
                $data                     = iHttp::remote($row['ofilename']);
                iFS::mkdir(dirname($FileRootPath));
                iFS::write($FileRootPath,$data);
                echo $row['ofilename'].PHP_EOL;
                echo iFS::fp($FilePath,'+http')."...√".PHP_EOL;
            }else{
                echo $row['id']."...×".PHP_EOL;
            }
            echo PHP_EOL.PHP_EOL;
        }else{
            echo $row['id']."...√".PHP_EOL;
        }
    }
}

