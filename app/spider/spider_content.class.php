<?php

defined('iPHP') OR exit('Oops, something went wrong');

class spider_content {
    public static $hash    = null;
    public static $match_data = null;

    
    /**
     * 抓取资源
     * @param  [string] $html      [抓取结果]
     * @param  [array] $data      [数据项]
     * @param  [array] $rule      [规则]
     * @param  [array] $responses [已经抓取资源]
     * @return [array]           [返回处理结果]
     */
    public static function crawl($html,$data,$rule,$responses) {
        @set_time_limit(0);

        if(trim($data['rule'])===''){
            return '';
        }
        $name = $data['name'];
        if (spider::$dataTest) {
            echo'<b>['.$name.']规则:</b>'.iSecurity::escapeStr($data['rule'])."<br />";
        }
        /**
         * 在数据项里调用之前采集的数据[DATA@name][DATA@name.key]
         */
        if(strpos($data['rule'], '[DATA@')!==false){
            $content = spider_tools::getDATA($responses,$data['rule']);
            if(is_array($content)){
                return $content;
            }else{
                $data['rule'] = $content;
            }
        }
        /**
         * 在数据项里调用采集规则RULE@规则id@url@checker
         */
        if(strpos($data['rule'], 'RULE@')!==false){
            list($_rid,$_urls,$_checker) = explode('@', str_replace('RULE@', '',$data['rule']));
            empty($_urls) && $_urls = trim($html);
            $_nocheck = ($_checker ==='false'?true:false);//是否检测采集过
            if (spider::$dataTest) {
                print_r('<b>使用[rid:'.$_rid.']规则抓取</b>:'.$_urls);
                echo "<hr />";
            }
            return spider_urls::crawl('DATA@RULE',false,$_rid,$_urls,null,$_nocheck);
        }
        /**
         * RAND@10,0
         * 返回随机数
         */
        if(strpos($data['rule'], 'RAND@')!==false){
            $random = str_replace('RAND@', '',$data['rule']);
            list($length,$numeric) = explode(',', $random);
            return random($length, empty($numeric)?0:1);
        }
        if($data['rule']==='<%content%>'){
            $content = $html;
        }else{
            $contentArray = array();
            self::$match_data = null;
            if(is_array($html)){
                foreach ($html as $hkey => $_html) {
                    $contentArray[] = spider_content::match($_html,$data,$rule);
                }
                $content = implode('#--iCMS.PageBreak--#', $contentArray);
                unset($_html);
            }else{
                self::$hash        = array();
                $_content          = spider_content::match($html,$data,$rule);
                $cmd5              = md5($_content);
                $contentArray[]    = $_content;
                self::$hash[$cmd5] = spider::$url;
                $data['page'] && self::page_data($html,$data,$rule,$contentArray);
                $content = implode('#--iCMS.PageBreak--#', $contentArray);
                // $content = $contentArray;
                unset($_content);
            }
            unset($contentArray);
        }
        unset($html);
        //遍历 Пример:FOREACH@<p><img src="[KEY@source]" />[KEY@add_intro]</p>
        //
        if(strpos($data['rule'], 'FOREACH@')!==false){
            $data_rule = str_replace('FOREACH@', '', $data['rule']);
            preg_match_all('!.*?\[KEY@([\w-_]+)\].*?!ism', $data_rule,$matchs);
            $variable = array();
            foreach ((array)$content as $key => $value) {
                foreach ((array)$matchs[1] as $i => $k) {
                    if(isset($value[$k])){
                        $variable[$key][$k] = $value[$k];
                    }
                }
            }
            foreach ((array)$matchs[1] as $i => $k) {
                $search[] = '[KEY@'.$k.']';
            }
            $contentArray = array();
            foreach ($variable as $key => $value) {
                $contentArray[] = str_replace($search, $value, $data_rule);
            }
            $content = implode('#--iCMS.PageBreak--#', $contentArray);
            unset($contentArray,$variable);
        }

        // if (spider::$dataTest) {
        //     print_r('<b>['.$name.']匹配结果:</b><div style="max-height:300px;overflow-y: scroll;">'.htmlspecialchars($content).'</div>');
        //     echo "<hr />";
        // }
        
        $content = spider_process::run($content,$data,$rule);

        if (spider::$callback['content'] && is_callable(spider::$callback['content'])) {
            $content = call_user_func_array(spider::$callback['content'],array($content,$data));
        }
        if (spider::$dataTest) {
            echo "<hr/>";
        }
        return $content;
    }
    
    public static function page_data($html,$data,$rule,&$contentArray){
        if(empty($rule['page_url'])){
            $rule['page_url'] = $rule['list_url'];
        }
        if (empty(spider::$allHtml)) {
            $page_url_array = array();
            $page_area_rule = trim($rule['page_area_rule']);
            if($page_area_rule){
                if(strpos($page_area_rule, 'DOM::')!==false){
                    if (spider::$dataTest) {
                        echo "<b>分页规则:</b>phpQuery<br />";
                    }
                    iPHP::vendor('phpQuery');
                    $responses= str_replace('&nbsp;', '', $responses);
                    $doc      = phpQuery::newDocumentHTML($html,'UTF-8');
                    $pq_dom   = str_replace('DOM::','', $page_area_rule);
                    $pq_array = phpQuery::pq($pq_dom);
                    foreach ($pq_array as $pn => $pq_val) {
                        $href = phpQuery::pq($pq_val)->attr('href');
                        if($href){
                            if($rule['page_url_rule']){
                                if(strpos($rule['page_url_rule'], '<%')!==false){
                                    $page_url_rule = spider_tools::pregTag($rule['page_url_rule']);
                                    if (!preg_match('|' . $page_url_rule . '|is', $href)){
                                        continue;
                                    }
                                }else{
                                    $cleanhref = spider_tools::dataClean($rule['page_url_rule'],$href);
                                    if($cleanhref){
                                        $href = $cleanhref;
                                        unset($cleanhref);
                                    }else{
                                        continue;
                                    }
                                }
                            }
                            $href = str_replace('<%url%>',$href, $rule['page_url']);
                            $page_url_array[$pn] = spider_tools::url_complement($rule['__url__'],$href);
                        }
                    }
                    phpQuery::unloadDocuments($doc->getDocumentID());
                }else{
                    if (spider::$dataTest) {
                        echo "<b>分页规则:</b>Регулярное выражение<br />";
                    }
                    $page_area_rule = spider_tools::pregTag($page_area_rule);
                    if ($page_area_rule) {
                        preg_match('|' . $page_area_rule . '|is', $html, $matches, $PREG_SET_ORDER);
                        $page_area = $matches['content'];
                    } else {
                        $page_area = $html;
                    }
                    if($rule['page_url_rule']){
                        $page_url_rule = spider_tools::pregTag($rule['page_url_rule']);
                        preg_match_all('|' .$page_url_rule. '|is', $page_area, $page_url_matches, PREG_SET_ORDER);
                        foreach ($page_url_matches AS $pn => $row) {
                            $href = str_replace('<%url%>', $row['url'], $rule['page_url']);
                            $page_url_array[$pn] = spider_tools::url_complement($rule['__url__'],$href);
                            gc_collect_cycles();
                        }
                    }
                    unset($page_area);
                }
            }else{ // Логический режим
                if (spider::$dataTest) {
                    echo "<b>分页规则:</b>Логический режим<br />";
                }
                if($rule['page_url_parse']=='<%url%>'){
                    $page_url = str_replace('<%url%>',$rule['__url__'],$rule['page_url']);
                }else{
                    $page_url_rule = spider_tools::pregTag($rule['page_url_parse']);
                    preg_match('|' . $page_url_rule . '|is', $rule['__url__'], $matches, $PREG_SET_ORDER);
                    $page_url = str_replace('<%url%>', $matches['url'], $rule['page_url']);
                }
                if (stripos($page_url,'<%step%>') !== false){
                    for ($pn = $rule['page_no_start']; $pn <= $rule['page_no_end']; $pn = $pn + $rule['page_no_step']) {
                        $pno = $pn;
                        if($rule['page_no_fill']){
                            $pno = sprintf("%0".$rule['page_no_fill']."s",$pn);
                        }
                        $page_url_array[$pn] = str_replace('<%step%>', $pno, $page_url);
                        gc_collect_cycles();
                    }
                }
            }
            //URL去重清理
            if($page_url_array){
                $page_url_array = array_filter($page_url_array);
                $page_url_array = array_unique($page_url_array);
                $puk = array_search($rule['__url__'],$page_url_array);
                if($puk!==false){
                    unset($page_url_array[$puk]);
                }
            }

            if (spider::$dataTest) {
                // echo "<b>分页规则:</b>Логический режим<br />";
                echo "<b>内容页网址:</b>".$rule['__url__'] . "<br />";
                echo "<b>ПЕЙДЖИНГ региональных правил:</b>".iSecurity::escapeStr($page_area_rule). "<br />";
                echo "<b>分页网址提取规则:</b>".iSecurity::escapeStr($page_url_rule). "<br />";
                echo "<b>分页合成:</b>".$rule['page_url'] . "<br />";
                echo "<b>分页列表:</b><pre>";
                print_r($page_url_array);
                echo "</pre>";
            }

            if($data['page']){
                spider::$content_right_code = ($data['dom']?'DOM::':'').$data['rule'];
            }
            $rule['page_url_right'] && spider::$content_right_code = trim($rule['page_url_right']);
            spider::$content_error_code = trim($rule['page_url_error']);
            if(spider::$dataTest){
                echo "<b>有效分页特征码:</b>";
                echo iSecurity::escapeStr(spider::$content_right_code);
                echo "<br />";
                echo "<b>无效分页特征码:</b>";
                echo iSecurity::escapeStr(spider::$content_error_code);
                echo "<br />";
            }

            $rule['proxy']          && spider::$curl_proxy = $rule['proxy'];
            $rule['data_charset']   && spider::$charset = $rule['data_charset'];
            $rule['data_user_agent']&& spider::$useragent = $rule['data_user_agent'];

            $pageurl = array();

            foreach ($page_url_array AS $pukey => $purl) {
                //usleep(100);
                $phtml = spider_tools::remote($purl);
                if (empty($phtml)) {
                    break;
                }
                $md5 = md5($phtml);
                if($pageurl[$md5]){
                    if (spider::$dataTest) {
                        echo "<b>{$purl}此分页已采过</b><br />";
                    }
                    continue;
                }
                $check_content_code = spider_tools::check_content_code($phtml,'error');
                if ($check_content_code === false) {
                    unset($check_content_code,$phtml);
                    if (spider::$dataTest) {
                        echo "<b>找到无效分页特征码,中止其它分页采集</b><br />";
                    }
                    break;
                }

                $check_content_code = spider_tools::check_content_code($phtml,'right');
                if ($check_content_code === false) {
                    unset($check_content_code,$phtml);
                    if (spider::$dataTest) {
                        echo "<b>未找到有效分页特征码,中止其它分页采集</b><br />";
                    }
                    break;
                }

                $_content = spider_content::match($phtml,$data,$rule);
                $cmd5     = md5($_content);
                $_purl    = self::$hash[$cmd5];
                if($_purl){
                    if (spider::$dataTest) {
                        echo "<b>发现[{$purl}]正文与[{$_purl}]相同,跳过本页采集</b><br />";
                    }
                    continue;
                }

                $contentArray[]        = $_content;
                $pageurl[$md5]         = $purl;
                self::$hash[$cmd5]     = $purl;
                spider::$allHtml[$md5] = $phtml;
            }
            gc_collect_cycles();
            unset($check_content_code,$phtml);

            if (spider::$dataTest) {
                echo "<b>最终分页列表:</b><pre>";
                print_r($pageurl);
                echo "</pre>";
            }
        }else{
            foreach ((array)spider::$allHtml as $ahkey => $phtml) {
                $contentArray[] = spider_content::match($phtml,$data,$rule);
            }
        }
    }
    
    public static function match($html,$data,$rule){
        $match_hash = array();
        if($data['dom']){
            iPHP::vendor('phpQuery');
            spider::$dataTest && $_GET['pq_debug'] && phpQuery::$debug =1;
            $html = preg_replace(array('/<script.+?<\/script>/is','/<style.+?<\/style>/is'),'',$html);
            $doc  = phpQuery::newDocumentHTML($html,'UTF-8');
            if(strpos($data['rule'], '@')!==false){
                //div.class@data-attr@fun
                //a.link@href
                list($content_dom,$content_attr,$content_fun) = explode("@", $data['rule']);
                empty($content_fun) && $content_fun = 'attr';
            }else{
                list($content_dom,$content_fun,$content_attr) = explode("\n", $data['rule']);
            }
            $content_dom  = trim($content_dom);
            $content_fun  = trim($content_fun);
            $content_attr = trim($content_attr);
            $content_fun OR $content_fun = 'html';
            if ($data['multi']) {
                $conArray = array();
                $_content = null;
                foreach ($doc[$content_dom] as $doc_key => $doc_value) {
                    if($content_attr){
                        $_content = phpQuery::pq($doc_value)->$content_fun($content_attr);
                    }else{
                        $_content = phpQuery::pq($doc_value)->$content_fun();
                    }

                    $cmd5 = md5($_content);
                    if($match_hash[$cmd5]){
                        continue;
                    }
                    if ($data['trim']) {
                        $_content = trim($_content);
                    }
                    if(empty($_content)){
                        $cmd5 = 'empty('.$doc_key.')';
                    }else{
                        $conArray[$doc_key]  = $_content;
                    }
                    $match_hash[$cmd5] = true;
                }
                $content = implode('#--iCMS.PageBreak--#', $conArray);
                if (spider::$dataTest) {
                    echo "<b>多条匹配结果:</b><pre style='max-height:120px;overflow-y: scroll;'>";
                    print_r(array_map('htmlspecialchars', $conArray));
                    echo "</pre>";
                    echo "<b>返回结果:</b><pre style='max-height:120px;overflow-y: scroll;'>";
                    print_r(htmlspecialchars($content));
                    echo "</pre>";
                }
                unset($conArray,$_content,$match_hash);
            }else{
                if($content_attr){
                    $content = $doc[$content_dom]->$content_fun($content_attr);
                }else{
                    $content = $doc[$content_dom]->$content_fun();
                }
                if (spider::$dataTest) {
                    echo "<b>[".$data['name']."]匹配结果:</b><pre style='max-height:120px;overflow-y: scroll;'>";
                    print_r(htmlspecialchars($content));
                    echo "</pre>";
                }
            }

            phpQuery::unloadDocuments($doc->getDocumentID());
            unset($doc);
        }else{
            if(trim($data['rule'])=='<%content%>'){
                $content = $html;
            }else{
                $data_rule = spider_tools::pregTag($data['rule']);
                if (preg_match('/(<\w+>|\.\*|\.\+|\\\d|\\\w)/i', $data_rule)) {
                    if ($data['multi']) {
                        preg_match_all('|' . $data_rule . '|is', $html, $matches, PREG_SET_ORDER);
                        $conArray = array();
                        foreach ((array) $matches AS $mkey => $mat) {
                            $cmd5 = md5($mat['content']);
                            if($match_hash[$cmd5]){
                                break;
                            }
                            $mat['content'] = trim($mat['content']);
                            if(empty($mat['content'])){
                                $cmd5 = 'empty('.$mkey.')';
                            }else{
                                $conArray[$mkey] = $mat['content'];
                            }
                            $match_hash[$cmd5] = true;
                            foreach ($mat as $key => $value) {
                                if(!is_numeric($key)){
                                    self::$match_data[$mkey][$key] = trim($value);
                                }
                            }
                        }
                        if (spider::$dataTest) {
                            echo "<b>[".$data['name']."]多条匹配结果:</b><pre>";
                            var_dump(iSecurity::escapeStr(self::$match_data));
                            echo "</pre>";
                        }
                        $content = implode('#--iCMS.PageBreak--#', $conArray);
                        unset($conArray,$match_hash);
                    } else {
                        preg_match('|' . $data_rule . '|is', $html, $matches);
                        $content = $matches['content'];
                        foreach ($matches as $key => $value) {
                            if(!is_numeric($key)){
                                 self::$match_data[$key] = trim($value);
                            }
                        }
                        if (spider::$dataTest) {
                            echo "<b>[".$data['name']."]匹配结果:</b><pre>";
                            var_dump(iSecurity::escapeStr(self::$match_data));
                            echo "</pre>";
                        }
                    }
                    if(self::$match_data && preg_match('/<%content(\d*)%>/i', $data['cleanbefor'])){
                        $content = self::replace_content($data['cleanbefor']);
                    }
                } else {
                    $content = $data['rule'];
                }
            }
        }
        return $content;
    }
    public static function replace_content(&$replace){
        //规则后:[<%content%><%content2%>]
        $pieces  = explode("\n", $replace);
        $content = array();
        foreach ($pieces as $pk => $pv) {
            if(preg_match('/\[.*?\]/i', $pv)){
                unset($pieces[$pk]);
                $_content = trim($pv,"[]\n\r");
                foreach (self::$match_data as $ckey => $cvalue) {
                    if(is_array($cvalue)){
                        $_content = trim($pv,"[]\n\r");
                        foreach ($cvalue as $ck => $cv) {
                            $_content = str_replace("<%{$ck}%>", $cv, $_content);
                        }
                        $content[] = $_content;
                    }else{
                        $_content = str_replace("<%{$ckey}%>", $cvalue, $_content);
                        $content  = $_content;
                    }
                }
            }
        }
        $replace = implode("\n", $pieces);
        is_array($content) && $content = implode("\n#--iCMS.PageBreak--#\n", $content);
        self::$match_data = null;
        return $content;
    }
}