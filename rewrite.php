<?php

require dirname(__FILE__) . '/iCMS.php';

$REQ  = parse_url(iPHP_REQUEST_URI);
$path = $REQ['path'];
$ext  = pathinfo($path, PATHINFO_EXTENSION);
$ext  = strtolower($ext);
$exts = array(
    "png", "jpg", "jpeg", "gif", "bmp", "webp", "psd", "tif",
    "flv", "swf", "mkv", "avi", "rm", "rmvb", "mpeg", "mpg", "mp4",
    "ogg", "ogv", "mov", "wmv", "webm", "mp3", "wav", "mid", "amr",
    "rar", "zip", "tar", "gz", "7z", "bz2", "cab", "iso",
    "doc", "docx", "xls", "xlsx", "ppt", "pptx", "pdf", "txt", "md", "xml",
    "apk", "ipa",
    "css", "js",
);
/**
 * Статический файл не существует возврат 404
 */
if(in_array($ext, $exts)){
    if(preg_match('@.*?/avatar/\d+/\d+/\d+.jpg@i', $path)){
        iPHP::redirect(iCMS_PUBLIC_URL.'/ui/avatar.gif');
    }
    if(preg_match('@.*?/coverpic/\d+/\d+/\d+.jpg@i', $path)){
        iPHP::redirect(iCMS_PUBLIC_URL.'/ui/coverpic.jpg');
    }
    iPHP::http_status(404, $path.',rewrite 404');
}else{
    $variable = iCMS::$config['router']['config'];
    $config = $preg = array();
    foreach ($variable as $key => $value) {
        if(strpos($value[0],'{') === false && strpos($value[0],'}') === false) {
            $config[$value[0]] = $value[1];
        }else{
            $preg[$key]= $value;
        }
    }
    //Общее соответствие маршрута
    $REQUEST_URI = $config[$path];
    //сопоставлением маршрута класса
    if(empty($REQUEST_URI)){
        foreach ($preg as $key => $value) {
            $uri = $value[0];
            if (stripos($key, 'uid:') === 0) {
                $url = rtrim(iURL::$config['user_url'], '/') . $value[0];
                $uri = parse_url($url,PHP_URL_PATH);
            }
            $replacement = '(?<\\1>\d+)';
            if (strpos($value[0], 'id}') === false) {
                $replacement = '(?<\\1>\w+)';
            }
            $pattern = preg_replace('/\{(\w+)\}/i', $replacement, $uri);
            preg_quote($pattern, '@');
            preg_match_all('@'.$pattern.'@i', $path, $matches);
            if($matches[1][0]){
                // var_dump($pattern,$path,$matches,$value[1]);
                $REQUEST_URI =  $value[1];
                foreach ($matches as $mkey => $mval) {
                    // var_dump($mkey,$mval);
                    $REQUEST_URI = str_replace('{'.$mkey.'}', $mval[0], $REQUEST_URI);
                }
            }
        }
        // var_dump(iPHP_REQUEST_URL,$path,$preg);
    }
    //Применить сопоставление маршрута
    $ext = iCMS::$config['router']['ext'];
    $dir = iCMS::$config['router']['dir'];
    if(empty($REQUEST_URI)){
        $rs    = categoryApp::get_cache('rules');
        $rules = array();
        foreach((array)$rs AS $rule) {
           if($rule)foreach ($rule as $k => $v) {
                $v = str_replace('{EXT}', $ext, $v);
                $v = rtrim($dir,'/').'/'.ltrim($v,'/');

                if($k=='index'||$k=='list'){
                    $k = 'category';
                }else{
                    $pi = pathinfo($v);
                    if(substr($v,-1)=='/'||empty($pi['extension'])){
                        $pi['extension'] = ltrim($ext,'.');
                        $pi['basename'].= '/index_{P}'.$ext;
                    }else{
                        $pi['basename'] = $pi['filename'].'_{P}.'.$pi['extension'];
                    }
                    $pv = $pi['dirname'].'/'.$pi['basename'];
                    $rules[$k][$pv] = strlen($pv);
                }
                $rules[$k][$v] = strlen($v);
           }
        }

        foreach ($rules as $key => $rvalue) {
            foreach ($rvalue as $k => $v) {
                $result[] = builder($key,$k);
            }
        }
        usort($result ,"cmpl");
        krsort($result);

        foreach ($result as $key => $value) {
            preg_match_all('@'.$value[0].'@i', $path, $matches);
            if($matches[1]){
                $REQUEST_URI = preg_replace('@'.$value[0].'@i', $value[1], $path);
                break;
            }
        }
    }
    //Проверка пользовательских ссылок
    if(empty($REQUEST_URI)){
        if(preg_match('@'.$dir.'.*?'.preg_quote($ext).'@', $path)){
            $clink = '['.ltrim($path,'/').']';
            $check = article::check($clink,0,'clink');
            $check && $REQUEST_URI = 'article.php?clink='.$clink;
        }
    }
    if($REQUEST_URI){
        $name  = basename(parse_url($REQUEST_URI,PHP_URL_PATH),'.php');
        // $parts = pathinfo($parse['path']);
        // $name = $parts['filename'];
        _GET($REQUEST_URI,$REQ);
        $name=='api'?
            iCMS::API():
            iCMS::run($name);
    }else{
        if(iPHP_DEBUG && iPHP_TPL_DEBUG){
            trigger_error("Ссылки не найдены <b>{$path}</b>.",E_USER_ERROR);
        }else{
            iPHP::http_status(404, $path.',rewrite 404');
        }
    }
}

function _GET($REQUEST_URI,$REQ){
    $rq = parse_url($REQUEST_URI,PHP_URL_QUERY);
    parse_str($rq, $request);
    parse_str($REQ['query'], $query);
    $_GET = array_merge($request,$query);
    iSecurity::slashes($_GET);
    iWAF::check_data($_GET);
}

function cmpl($a,$b) {
    $al = strlen($a[0]);
    $bl = strlen($b[0]);
    if ( $al  ==  $bl ) {
        return  0 ;
    }
    return ( $al  <  $bl ) ? - 1  :  1 ;
}
function builder($key,$value){
    preg_match_all("/\{(.*?)\}/", $value, $matches);
    $rule   = $value;
    $pieces = array();
    $app    = $key;
    if(strpos($key,':')!==false) {
        list($app,$do) = explode(':', $key);
        $do && $pieces[] = 'do='.$do;
    }
    $i = 1;
    foreach ($matches[1] as $k => $v) {
        $ru   = preg($v,$app);
        $rule = str_replace($matches[0][$k], $ru, $rule);
        $rw   = rewrite($v,$i,$app);
        if($rw){
            $pieces[] = $rw;
            ++$i;
        }
    }
    $rewrite = $app.'.php?'.implode('&', $pieces);
    return array($rule,$rewrite);
}
function rewrite($b,$k,$t){
    switch($b) {
        case 'ID':      $e = 'id=$';break;
        case '0xID':    $e = 'id=$';break;
        case 'CID':
            if($t=='category'){
                $e = 'cid=$';
            }
            break;
        case '0xCID':
            if($t=='category'){
                $e = 'cid=$';
            }
            break;
        case 'CDIR':
            if($t=='category'){
                $e = 'dir=$';
            }
        break;
        case 'NAME':    $e = 'name=$';break;
        case 'TITLE':   $e = 'title=$';break;
        case 'EN_EN':   $e = 'name=$';break;
        case 'TKEY':    $e = 'tkey=$';break;
        case 'LINK':    $e = 'clink=$';break;
        case 'TCID':    $e = 'tcid=$';break;
        case 'P':
            $e = 'page=$';
            if($t=='article'){
                $e = 'p=$';
            }
        break;
    }
    if($e){
        return $e.$k;
    }else{
        return false;
    }
}
function preg($b,$t){
    switch($b) {
        case 'ID':      $e = '(\d+)';break;
        case '0xID':    $e = '(\d+)';break;
        case '0x3ID':   $e = '\d+';break;
        case '0x3,2ID': $e = '\d+';break;
        case 'MD5':     $e = '\w+';break;

        case 'CID':
            if($t=='category'){
                $e = '(\d+)';
            }else{
                $e = '\d+';
            }
        break;
        case '0xCID':
            if($t=='category'){
                $e = '(\d+)';
            }else{
                $e = '\d+';
            }
        break;
        case 'CDIR':
            if($t=='category'){
                $e = '(.+)';
            }else{
                $e = '.+';
            }
            break;
        case 'TIME':    $e = '\d+';break;
        case 'YY':      $e = '\d+';break;
        case 'YYYY':    $e = '\d+';break;
        case 'M':       $e = '\d+';break;
        case 'MM':      $e = '\d+';break;
        case 'D':       $e = '\d+';break;
        case 'DD':      $e = '\d+';break;

        case 'NAME':    $e = '(.*?)';break;
        case 'TITLE':   $e = '(.*?)';break;
        case 'EN_EN':   $e = '(.*?)';break;
        case 'TKEY':    $e = '(.+)';break;
        case 'LINK':    $e = '(.+)';break;

        case 'TCID':    $e = '(\d+)';break;
        case 'P':       $e = '(\d+)';break;
        default:        $e = '.+';
    }
    return $e;
}
