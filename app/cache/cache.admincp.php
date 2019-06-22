<?php

class cacheAdmincp{
    public $appAdmincp = array('configAdmincp','propAdmincp','filterAdmincp','keywordsAdmincp');
    public function __construct() {}
   
    public function do_all(){
        $this->do_app();
        foreach ($this->appAdmincp as $key => $acp) {
            iPHP::callback(array($acp,'cache'));
        }
        $this->do_menu(false);
        $this->do_category(false);
        $this->do_article_category(false);
        $this->do_tag_category(false);
        $this->do_filecache(false);
        $this->do_tpl(false);
        iUI::success('Кеш успешно очищен');
    }
   
    public function do_iCMS($dialog=true){
		if (in_array($_GET['acp'], $this->appAdmincp)) {
	    	$acp = $_GET['acp'];
	    	iPHP::callback(array($acp,'cache'));
	    	$dialog && iUI::success('Успешно обновлено');
		}
    }
   
    public function do_menu($dialog=true){
    	menu::cache();
    	$dialog && iUI::success('Успешно обновлено','js:1');
    }
    
    public function do_category($dialog=true){
        echo '<iframe src="'.__ADMINCP__.'=category&do=cache&dialog=0"></iframe>';
    }
    
    public function do_article_category($dialog=true){
        echo '<iframe src="'.__ADMINCP__.'=article_category&do=cache&dialog=0"></iframe>';
    }
   
    public function do_tag_category($dialog=true){
        echo '<iframe src="'.__ADMINCP__.'=tag_category&do=cache&dialog=0"></iframe>';
    }
    
    public function do_tpl($dialog=true){
    	iView::clear_tpl();
    	$dialog && iUI::success('Полная очистка прошла успешно');
    }
    
    public function do_article_count($dialog=true){
        $categoryAdmincp = new article_categoryAdmincp();
    	$categoryAdmincp->do_recount(false);
    	$dialog && iUI::success('Успешно обновлено');
    }
    
    public function do_app($dialog=true){
        apps::cache();
    }
    public function do_filecache($dialog=true){
        if(iCMS::$config['cache']['engine']=='file'){
            @set_time_limit(0);
            $prefix = iCache::prefix();
            iCache::$handle->clear_all($prefix);
            $dialog && iUI::success('Завершена очистка кеша классов файлов с истекшим сроком');
        }
    }
    public function do_cleanall($dialog=true){
        $this->do_filecache();
    }
    public static function test($config){
        set_error_handler(function($errno, $errstr, $errfile, $errline){
            $errno = $errno & error_reporting();
            if($errno == 0) return;

            $cache = $_POST['config']['cache'];
            $encode = mb_detect_encoding($errstr, array("ASCII","UTF-8","WINDOWS-1251"));
            $errstr= mb_convert_encoding($errstr,'UTF-8',$encode);
            iUI::$dialog['width'] = "550";
            iUI::dialog(
                "warning:#:warning:#:
                系统缓存配置出错!<br />
                请确认服务器是否支持".$cache['engine']."或者".$cache['engine']."服务器是否正常运行
                <hr />{$errstr}",
            'js:1', 30000000);
        },E_ALL & ~E_NOTICE);

        $cache = iCache::init($config,true);
        $cache->set('cache_test',1);
        $cache->delete('cache_test');
    }
    public static function clean_cache() {
        include admincp::view("cache.clean","cache");
    }
}
