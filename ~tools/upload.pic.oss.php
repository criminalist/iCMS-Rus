#!/usr/bin/env php
<?php
require dirname(__file__).'/../iCMS.php';
set_time_limit(0);
ini_set('memory_limit','512M');

define('RES_ROOTPATH', '/data/www/ooxx.com/res/');
define('RES_DOMAIN_HTTP', 'http://res.ooxx.com/');
define('RES_DOMAIN_HTTPS', 'http://res.ooxx.com/');
define('OSS_DOMAIN_HTTP', 'http://img1.ooxx.com/');
define('ARTICLE_URL', "http://www.ooxx.com/article/%08s.html");

$OSS = new files_cloud_AliYunOSS(array (
        'domain' => 'ooxx.oss-cn-hangzhou.aliyuncs.com',//OSS外网域名
        'Bucket' => 'Bucket 名称',//空间名称
        'AccessKey' => 'AccessKey',
        'SecretKey' => 'SecretKey',
));


$where  = "haspic='1' AND `status`='1'";
$start  = 0;
$preNum = 10000;
$max    = iDB::value("
    SELECT count(id) FROM `#iCMS@__article`
    WHERE {$where} order by id desc;"
);
$page   = ceil($max/$preNum);
iDB::query("set interactive_timeout=24*3600");

for ($i=0; $i <=$page; $i++) {
    $offset = $i*$preNum;
    run($offset,$preNum);
}
function run($offset=0,$preNum=10){
    global $where,$OSS;
    echo "LIMIT {$offset},{$preNum}\n";
    $ids_array   = iDB::all("
        SELECT id FROM #iCMS@__article
        WHERE {$where}
        order by id desc
        LIMIT {$offset},{$preNum}
    ");
    $ids       = iSQL::values($ids_array);
    $ids       = $ids?$ids:'0';
    $where_sql = "WHERE a.id IN({$ids})";

    $article   = iDB::all("
        SELECT a.id,a.cid,a.title,d.body,d.id as adid
        FROM #iCMS@__article as a
        LEFT JOIN #iCMS@__article_data AS d
        ON a.id = d.aid
        {$where_sql}
    ");

    foreach ((array)$article as $key => $row) {
        $aid   = $row['id'];
        $adid  = $row['adid'];
        $body  = $row['body'];
        $pics = filesApp::get_content_pics($body);

        if($pics){
            $upload = 0;
            $replace = $pics;
            $delArray = array();
            foreach ($pics as $key => $value) {
                $filePath = str_replace(RES_DOMAIN_HTTP, '', $value);
                $filePath = str_replace(RES_DOMAIN_HTTPS, '', $filePath);
                if(!iFS::checkHttp($filePath)){
                    $fileRootPath = RES_ROOTPATH.$filePath;
                    if(is_file($fileRootPath)){
                        $json = $OSS->_upload_file($fileRootPath,$filePath);
                        $a = json_decode($json,true);
                        if(empty($a['error']) &&$a['msg']['status']==200){
                            $replace[$key] = OSS_DOMAIN_HTTP.$filePath;
                            $delArray[$key] = $fileRootPath;
                            echo OSS_DOMAIN_HTTP.$filePath.PHP_EOL;
                            // iFS::rm($fileRootPath);
                            $upload++;
                        }
                    }else{
                        if(strpos($value,OSS_DOMAIN_HTTP)===false){
                            $object = $OSS->is_object_exist($OSS->conf['Bucket'],$filePath);
                            if($object->status==200){
                                $replace[$key] = OSS_DOMAIN_HTTP.$filePath;
                                $delArray[$key] = $fileRootPath;
                                echo OSS_DOMAIN_HTTP.$filePath.PHP_EOL;
                                $upload++;
                            }
                        }else{

                        }
                    }
                }
            }
            if($upload){
                echo 'upload ('.$upload.') OK'.PHP_EOL;

                $body = str_replace($pics, $replace, $body);
                iDB::update('article_data',
                    array('body'=>addslashes($body)),
                    array('id'=>$adid)
                );
                echo sprintf(ARTICLE_URL,$aid).PHP_EOL.PHP_EOL;

                if($delArray)foreach ($delArray as $key => $value) {
                    iFS::rm($value);
                }
            }
        }
    }
}
