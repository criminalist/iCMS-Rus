<?php

class iPagination {
    public static $config   = array();
    public static $callback = array();

    public static $iPAGES  = NULL;
    public static $pagenav = NULL;
    public static $offset  = NULL;

    public static $total_cache = 'G';
    public static function getTotal($sql,$perpage=10,$nowindex=null) {
        $total_type = $vars['total_cache'] ? 'G' : null;
        $total = self::totalCache($sql,$total_type,iCMS::$config['cache']['page_total']);
        return self::make(array(
            'total_type' => $total_type,
            'total'      => $total,
            'perpage'    => $perpage,
            'nowindex'   => ($nowindex===null?$GLOBALS['page']:$nowindex)
        ));
    }
    //分页数缓存
    public static function totalCache($sql, $type = null,$cachetime=3600) {
        $total = (int) $_GET['total_num'];
        if($type=="G"){
            empty($total) && $total = iDB::value($sql);
        }else{
            $cache_key = 'page_total/'.substr(md5($sql), 8, 16);
            if(empty($total)){
                if (!isset($_GET['page_total_cache'])|| $type === 'nocache'||!$cachetime) {
                    $total = iDB::value($sql);
                    $type === null && iCache::set($cache_key,$total,$cachetime);
                }else{
                    $total = iCache::get($cache_key);
                }
            }
        }
        return (int)$total;
    }
    
    public static function pagenav($total, $perpage = 20, $unit = "Записи", $url = '', $target = '') {
        $conf = array(
            'url'        => $url,
            'target'     => $target,
            'total'      => $total,
            'perpage'    => $perpage,
            'total_type' => 'G',
            'lang'       => iUI::lang(iPHP_APP . ':page'),
        );
        $conf['lang']['item'] = '<li>%s</li>';

        $iPages = new iPages($conf);
        self::$offset = $iPages->offset;
        self::$pagenav = '<ul>' .
        self::$pagenav.= $iPages->show(3);
        self::$pagenav.= "<li> <span class=\"muted\">{$total}{$unit} {$perpage}{$unit}/стр. всего {$iPages->totalpage} стр.</span></li>";
        if ($iPages->totalpage > 50) {
            $url = $iPages->get_url(1);
            self::$pagenav.= "<li> <span class=\"muted\">Перейти к  <input type=\"text\" id=\"pageselect\" style=\"width:24px;height:12px;margin-bottom: 0px;line-height: 12px;\" /> стр. <input class=\"btn btn-small\" type=\"button\" onClick=\"window.location='{$url}&page='+$('#pageselect').val();\" value=\"Перейти\" style=\"height: 22px;line-height: 18px;\"/></span></li>";
        } else {
            self::$pagenav.= "<li> <span class=\"muted\">Перейти к " . $iPages->select() . " странице</span></li>";
        }
        self::$pagenav.= '</ul>';
    }
    
    public static function make($conf) {
        empty($conf['lang']) && $conf['lang'] = iUI::lang(iPHP_APP . ':page');
        empty($conf['unit']) && $conf['unit'] = iUI::lang(iPHP_APP . ':page:list');
        return self::assign($conf);
    }
    public static function assign($conf){
        $obj = new iPages($conf);
        iView::set_iVARS(array(
            'PAGES' => $obj,
            'PAGE'  => $obj->vars()
        ));
        return $obj;
    }
    
    public static function url($iurl){
        if(!empty(iPages::$setting)) return;

        $iurl = (array)$iurl;
        iPages::$setting = array(
            'enable' =>true,
            'url'    =>$iurl['pageurl'],
            'index'  =>$iurl['href'],
            'ext'    =>$iurl['ext']
        );
    }
    
    public static function content($content,$page,$total,$count,$mode=null,$chapterArray=null){
        $pageArray = array();
        $pageurl = $content['iurl']['pageurl'];
        if ($total > 1) {
            $old_page_setting = iPages::$setting;

            $mode && self::url($content['iurl']);
            $conf = array(
                'page_name' => 'p',
                'url'       => $pageurl,
                'total'     => $total,
                'perpage'   => 1,
                'nowindex'  => (int) $_GET['p'],
                'lang'      => iUI::lang(iPHP_APP . ':page'),
            );
            if ($content['chapter']) {
                foreach ((array) $chapterArray as $key => $value) {
                    $conf['titles'][$key + 1] = $value['subtitle'];
                }
            }
            $iPages = new iPages($conf);
            $pageArray['list']  = $iPages->list_page();
            $pageArray['index'] = $iPages->first_page('array');
            $pageArray['prev']  = $iPages->prev_page('array');
            $pageArray['next']  = $iPages->next_page('array');
            $pageArray['endof'] = $iPages->last_page('array');
            $pagenav = $iPages->show(0);
            $pagetext = $iPages->show(10);

            iPages::$setting = $old_page_setting;
            unset($old_page_setting);
        }
        $content_page = array(
            'pn'      => $page,
            'total'   => $total, 
            'count'   => $count,
            'current' => $page,
            'nav'     => $pagenav,
            'url'     => iURL::page_num($pageurl,$_GET['p']),
            'pageurl' => $pageurl,
            'text'    => $pagetext,
            'PAGES'   => $iPages,
            'args'    => iSecurity::escapeStr($_GET['pageargs']),
            'first'   => ($page == "1" ? true : false),
            'last'    => ($page == $count ? true : false),
            'end'     => ($page == $total ? true : false)
        ) + $pageArray;
        unset($pagenav, $pagetext, $iPages, $pageArray);
        return $content_page;
    }
}
