<?php

defined('iPHP') OR exit('What are you doing?');

class spider_process {
    public static $groupMaps = array(
        '0x01' =>'Общее',
        '0x02' =>'转义',
        '0x03' =>'Страницы',
        '0x04' =>'Парсинг/декодирование',
        '0x05' =>'Генерация / кодирование',
        '0x06' =>'Строки',
        '0x07' =>'Спец обработки'
    );
    public static $helperMaps = array(
        'dataclean'               => array(null,'Очистка данных','Сбор данных после сбора правил'),
        'trim'                    => array('0x01','Удаление пробелов и переносов строк','Удаление пробелов и переносов строк'),
        'format'                  => array('0x01','HTML форматирование','HTML форматирование'),
        'cleanhtml'               => array('0x01','Удаление HTML разметки','Удаление HTML разметки'),
        'img_absolute'            => array('0x01','图片地址补全','图片地址补全'),
        'array'                   => array('0x01','Возвращает массив','Возвращает массив'),
        'url_absolute'            => array('0x01','Завершение URL','Завершение URL'),
        'capture'                 => array('0x01','抓取结果','抓取并直接返回结果'),
        'download'                => array('0x01','Скачать','Загрузите и сохраните файл'),
        'filter'                  => array('0x01','Фильтрация слово','Фильтрация слово'),
        'array_filter_empty'      => array('0x01','Очистить пустой массив','Очистить пустой массив'),
        'clean_cn_blank'          => array('0x01','Очистка пустых китайских символов (пробелов)','Очистка пустых китайских символов (пробелов)'),
        'array_reverse'           => array('0x01','Возвращает массив с элементами в обратном порядке','Функция array_reverse() берёт массив array и возвращает новый массив, порядок элементов в котором обратный исходному, сохраняя ключи, если параметр preserve_keys равен TRUE.'),

        'stripslashes'            => array('0x02','Удаляет экранирующие бэкслэши','Возвращает строку после удаления экранированной обратной косой черты'),
        'addslashes'              => array('0x02','Экранирует спецсимволы в строке','Возвращает строку с обратной косой чертой'),
        'htmlspecialchars_decode' => array('0x02','Преобразует HTML-сущности обратно','Эта функция является обратной к htmlspecialchars(). Она преобразует специальные HTML-сущности обратно в соответствующие символы.'),
        'htmlspecialchars'        => array('0x02','Преобразует символы в HTML сущности','Преобразует специальные символы в HTML сущности'),
        'xml2array'               => array('0x02','XML в массив','XML переводит в массив'),

        'mergepage'               => array('0x03','Слияние страниц','Слияние страниц'),
        'autobreakpage'           => array('0x03','Разбивать страницу на части','Разбивать страницу на части для постраничного вывода'),

        'urldecode'               => array('0x04','解码 URL 字符串(urldecode)','解码 URL 字符串(urldecode)'),
        'rawurldecode'            => array('0x04','解码 URL 字符串(rawurldecode)','解码 URL 字符串(rawurldecode)'),
        'parse_str'               => array('0x04','URL字符串解析(parse_str)','URL字符串解析(parse_str)'),
        'json_decode'             => array('0x04','JSON解码(json_decode) ','JSON解码(json_decode) '),
        'base64_decode'           => array('0x04','base64 解码(base64_decode) ','base64 解码(base64_decode) '),
        'auth_decode'             => array('0x04','解密(auth_decode) ','解密(auth_decode) '),

        'urlencode'               => array('0x05','URL-кодирует строку','URL-кодирует строку (urlencode)'),
        'rawurlencode'            => array('0x05','编码 URL 字符串(rawurlencode)','编码 URL 字符串(rawurlencode)'),
        'http_build_query'        => array('0x05','Массив в строку URL','Сформировать URL-закодированные строки запроса(http_build_query)'),
        'json_encode'             => array('0x05','JSON编码(json_encode) ','JSON编码(json_encode) '),
        'auth_encode'             => array('0x05','加密(auth_encode) ','加密(auth_encode) '),

        'array_explode'           => array('0x06','Строка=>Массив','Разбивает строку на подстроки'),
        'array_implode'           => array('0x06','Массив=>Строка','Объединяет элементы массива в строку'),

        '@check_urls'             => array('0x07','Проверка ссылок','Независимая проверка, ссылка сохранена в новой таблице'),
        '@collect_urls'           => array('0x07','收集链接','收集其它链接'),
    );
    public static function getArray(){
        $array = array();
        foreach (self::$helperMaps as $key => $value) {
            $gn = self::$groupMaps[$value[0]];
            $gn && $array[$gn][$key] = $value;
        }
        return $array;
    }
    public static function run($content,$data,$rule){
        if($data['process']){
            spider::$dataTest && print "<b>Обработка данных:</b><br />";
            foreach ($data['process'] as $key => $value) {
                if(!is_array($value)){
                    continue;
                }
                
                
                //@check_urls
                if(substr($value['helper'], 0,1)=='@'){
                    $sk = substr($value['helper'],1);
                    $value[$sk]   = true;
                    $sfuncArray[] = $value;
                    continue;
                }
                if(spider::$dataTest){
                    $hNo = $key+1;
                    echo $hNo.'# '.$value['helper'];
                    if($value['helper']=='dataclean'){
                        echo '('.htmlspecialchars($value['rule']).')';
                    }
                    echo '<br />';
                }
                $value[$value['helper']] = true;
                if(is_array($content)){
                    foreach ($content as $idx => $con) {
                        $content[$idx] = self::helper($con,$value,$rule);
                    }
                }else{
                    $content = self::helper($content,$value,$rule);
                }
                if($content===null){
                    return null;
                }
            }
        }
        is_array($content) && $content = array_filter($content);

        if($sfuncArray)foreach ($sfuncArray as $key => $value) {
            spider::$dataTest && print ($hNo+1).'# '.$value['helper'].'<br />';
            $content = self::helper_func($content,$value,$rule);
        }

        return $content;
    }
    public static function helper($content,$process,$rule){
        if ($process['dataclean']) {
            $content = spider_tools::dataClean($process['rule'], $content);
        }
        if ($process['stripslashes']) {
            $content = stripslashes($content);
        }
        if ($process['addslashes']) {
            $content = addslashes($content);
        }
        if ($process['base64_encode']) {
            $content = base64_encode($content);
        }
        if ($process['base64_decode']) {
            $content = base64_decode($content);
        }
        if ($process['urlencode']) {
            $content = urlencode($content);
        }
        if ($process['urldecode']) {
            $content = urldecode($content);
        }
        if ($process['rawurlencode']) {
            $content = rawurlencode($content);
        }
        if ($process['rawurldecode']) {
            $content = rawurldecode($content);
        }
        if ($process['parse_str']) {
            $content = parse_url_qs($content);
        }
        if ($process['http_build_query'] && is_array($content)) {
            $content = http_build_query($content);
        }
        if ($process['trim']) {
            if(is_array($content)){
                $content = array_map('trim', $content);
            }else{
                $content = str_replace('&nbsp;','',trim($content));
            }
        }
        if($process['json_encode'] && is_array($content)){
            $content = json_encode($content);
        }
        if ($process['json_decode']) {
            $content = json_decode($content,true);
            if(is_null($content)){
                return spider_error::msg(
                    'JSON ERROR:'.json_last_error_msg(),
                    'content.json_decode.error',
                    $name,$rule['__url__']
                );
            }
        }
        if ($process['htmlspecialchars_decode']) {
            $content = htmlspecialchars_decode($content);
        }
        if ($process['htmlspecialchars']) {
            $content = htmlspecialchars($content);
        }
        if ($process['cleanhtml']) {
            $content = preg_replace('/<[\/\!]*?[^<>]*?>/is', '', $content);
        }
        if ($process['format'] && $content) {
            $content = autoformat($content);
        }
        if ($process['nl2br'] && $content) {
            $content = nl2br($content);
        }
        if ($process['url_absolute'] && $content) {
            $content = spider_tools::url_complement($rule['__url__'],$content);
        }
        if ($process['img_absolute'] && $content) {
            $content = spider_tools::img_url_complement($content,$rule['__url__']);
        }
        if ($process['capture']) {
            $content && $content = spider_tools::remote($content);
        }
        if ($process['download']) {
            $content && $content = iFS::http($content);
        }

        if ($process['autobreakpage'] && $content) {
            $content = spider_tools::autoBreakPage($content);
        }
        if ($process['mergepage'] && $content) {
            $content = spider_tools::mergePage($content);
        }
        if ($process['filter']) {
            $fwd = iPHP::callback(array("filterApp","run"),array(&$content),false);
            if($fwd){
                return spider_error::msg(
                    'Содержит ['.$fwd.'] символы, которые маскируются системой!',
                    'content.filter',
                    $name,$rule['__url__']
                );
            }
        }
        if ($process['empty']) {
            $empty = spider_tools::real_empty($content);
            if(empty($empty)){
                return spider_error::msg(
                    'набор правил не может быть пустым. Текущий результат сканирования пуст! Пожалуйста, проверьте правильность правила!',
                    'content.empty',
                    $name,$rule['__url__']
                );
            }
            unset($empty);
        }
        if ($process['xml2array']) {
            $content = iUtils::xmlToArray($content);
        }
        if($process['array']){
            if(strpos($content, '#--iCMS.PageBreak--#')!==false){
                $content = explode('#--iCMS.PageBreak--#', $content);
            }
            if(empty($content)){
                return null;
            }
            return (array)$content;
        }
        if($process['clean_cn_blank']){
            $_content = htmlentities($content);
            $content  = str_replace(array('&#12288;','&amp;#12288;'),'', $_content);
            unset($_content);
        }
        if($process['array_filter_empty']){
            if(is_array($content)){
                $content = array_filter($content);
            }else{
                if(strpos($content, '#--iCMS.PageBreak--#')!==false){
                    $content = explode('#--iCMS.PageBreak--#', $content);
                    $content = array_filter($content);
                }
            }
        }
        if($process['array_reverse']){
            if(is_array($content)){
                $content = array_reverse($content);
            }else{
                if(strpos($content, '#--iCMS.PageBreak--#')!==false){
                    $content = explode('#--iCMS.PageBreak--#', $content);
                    $content = array_reverse($content);
                }
            }
        }
        if(($process['implode']||$process['array_implode']) && is_array($content)){
            $content = implode(',', $content);
        }
        if($process['explode'] && is_string($content)){
            $content = explode(',', $content);
        }
        return $content;
    }
    public static function helper_func($content,$process,$rule){
        //@check_urls
        if ($process['check_urls']) {
            $content && $content = spider_tools::check_urls($content);
        }
        //@collect_urls
        if ($process['collect_urls']) {
            $content && $content = spider_tools::collect_urls($content);

        }
        return $content;
    }
}
