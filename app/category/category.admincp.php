<?php

defined('iPHP') OR exit('Oops, something went wrong');

class categoryAdmincp {
    public $callback         = array();
    protected $category_uri  = APP_URI;
    protected $category_furi = APP_FURI;
    protected $category_name = "Узел";
    /**
     *  模板
     */
    protected $category_template = array(
        'index'   =>array('Главная','{iTPL}/category.index.htm'),
        'list'    =>array('Список','{iTPL}/category.list.htm'),
    );
    /**
     *  Правила URL
     */
    protected $category_rule = array(
        'index'   => array('Главная','/{CDIR}/','{CID},{0xCID},{CDIR},{Hash@CID},{Hash@0xCID}'),
        'list'    => array('Список','/{CDIR}/index_{P}{EXT}','{CID},{0xCID},{CDIR},{Hash@CID},{Hash@0xCID}'),
    );
    /**
     *  Параметры формирования URL
     */
    protected $category_rule_list = array(
        'tag' => array(
            array('----'),
            array('{TKEY}','Идентификация тега'),
            array('{RUS}','Имя тега (китайский)'),
            array('{NAME}','Имя тега'),
            array('----'),
            array('{TCID}','ID категории',false),
            array('{TCDIR}','Категория',false),
        ),
    );

    protected $_app         = 'content';
    protected $_app_name    = '内容';
    protected $_app_table   = 'content';
    protected $_app_cid     = 'cid';
    protected $_view_add    = 'category.add';
    protected $_view_manage = 'category.manage';
    protected $_view_tpl_dir = null;
    public static $sappid = null;

    public function __construct($appid = null,$dir=null) {
        // self::$sappid    = apps::id(__CLASS__);
        $this->cid       = (int)$_GET['cid'];
        $this->appid     = null;
        $appid          && $this->appid = $appid;
        $_GET['appid']  && $this->appid = (int)$_GET['appid'];
        $this->_view_tpl_dir = $dir;
        category::$appid = $this->appid;
    }

    public function do_add($default=null){
        if($this->cid) {
            category::check_priv($this->cid,'e','page');
            $rs		= iDB::row("SELECT * FROM `#iCMS@__category` WHERE `cid`='$this->cid' LIMIT 1;",ARRAY_A);
            $rootid	= $rs['rootid'];
            $rs['rule']     = json_decode($rs['rule'],true);
            $rs['template'] = json_decode($rs['template'],true);
            $rs['config']   = json_decode($rs['config'],true);
            iPHP::callback(array("apps_meta","get"),array(iCMS_APP_CATEGORY,$this->cid,false));
        }else {
            $rootid = (int)$_GET['rootid'];
            $rootid && category::check_priv($rootid,'a','page');
        }
        if(empty($rs)) {
            $rs = array(
                'pid'       => '0',
                'status'    => '1',
                'config' => array(
                    'ucshow'  => '1',
                    'send'    => '1',
                    'examine' => '1',
                ),
                'sortnum'   => '0',
                'mode'      => '0',
                'htmlext'   => iCMS::$config['router']['ext'],
            );
	        if($rootid){
                $rootRs = iDB::row("SELECT * FROM `#iCMS@__category` WHERE `cid`='".$rootid."' LIMIT 1;",ARRAY_A);
                $rs['htmlext']  = $rootRs['htmlext'];
                $rs['rule']     = json_decode($rootRs['rule'],true);
                $rs['template'] = json_decode($rootRs['template'],true);
                $rs['config']   = json_decode($rootRs['config'],true);
	        }
            if($default){
                $rs = array_merge($rs,(array)$default);
            }
        }

        iPHP::callback(array("formerApp","add"),array(iCMS_APP_CATEGORY,$rs,true));
        include admincp::view($this->_view_add,$this->_view_tpl_dir);
    }
    public function do_save(){
        $appid        = $this->appid===null?(int)$_POST['appid']:$this->appid;
        $cid          = (int)$_POST['cid'];
        $rootid       = (int)$_POST['rootid'];
        $status       = (int)$_POST['status'];
        $sortnum      = (int)$_POST['sortnum'];
        $mode         = (int)$_POST['mode'];
        $pid          = implode(',', (array)$_POST['pid']);
        $_pid         = iSecurity::escapeStr($_POST['_pid']);
        $_rootid_hash = iSecurity::escapeStr($_POST['_rootid_hash']);
        $name         = iSecurity::escapeStr($_POST['name']);
        $subname      = iSecurity::escapeStr($_POST['subname']);
        $domain       = iSecurity::escapeStr($_POST['domain']);
        $htmlext      = iSecurity::escapeStr($_POST['htmlext']);
        $url          = iSecurity::escapeStr($_POST['url']);
        $password     = iSecurity::escapeStr($_POST['password']);
        $pic          = iSecurity::escapeStr($_POST['pic']);
        $mpic         = iSecurity::escapeStr($_POST['mpic']);
        $spic         = iSecurity::escapeStr($_POST['spic']);
        $dir          = iSecurity::escapeStr($_POST['dir']);
        $title        = iSecurity::escapeStr($_POST['title']);
        $keywords     = iSecurity::escapeStr($_POST['keywords']);
        $description  = iSecurity::escapeStr($_POST['description']);
        $rule         = iSecurity::escapeStr($_POST['rule']);
        $template     = iSecurity::escapeStr($_POST['template']);
        $config       = (array)iSecurity::escapeStr($_POST['config']);
        $creator      = iSecurity::escapeStr($_POST['creator']);
        $userid       = (int)$_POST['userid'];

        if($_rootid_hash){
            $_rootid = auth_decode($_rootid_hash);
            if($rootid!=$_rootid){
                return iUI::alert('Illegal data submission!!');
            }else{
                category::check_priv($_rootid,'a','alert');
            }
        }

        if($cid && $cid==$rootid){
            return iUI::alert('不能以自身做为上级'.$this->category_name);
        }
        if (empty($name)) {
            return iUI::alert($this->category_name.'Имя не может быть пустым!');
        }

        if($mode=="2"){
            foreach ($rule as $key => $value) {
                $CR = $this->category_rule[$key];
                $CRKW = explode(',', $CR[2]);
                $cr_check = true;
                foreach ($CRKW as $i => $crk) {
                    $crk = str_replace(array('{','}'), '', $crk);
                    if(strpos($value,$crk) !== FALSE){
                        $cr_check = false;
                    }
                }
                if($cr_check && empty($domain) && $key!='tag'){
                    return iUI::alert('伪静态模式'.$CR[0].'规则必需含有'.$CR[2].'其中之一,否则将无法解析');
                }
            }
        }

        //Мета-атрибут содержимого
        if($config['meta']){
            $meta = array();
            foreach($config['meta'] AS $mk=>$meta){
                if($meta['name']){
                    $meta['key'] OR $meta['key'] = strtolower(iTranslit::get($meta['name']));
                    if(!preg_match("/[a-zA-Z0-9_\-]/",$meta['key'])){
                        return iUI::alert('Может состоять только из английских букв, цифр или _-. Если оставить это поле пустым, оно будет заполнено именем транслитом.');
                    }
                    $meta['key'] = trim($meta['key']);
                    $config['meta'][$mk] = $meta;
                }
            }
        }

        $rule     = addslashes(json_encode($rule));
        $template = addslashes(json_encode($template));
        $config   = addslashes(json_encode($config));

        iMap::init('prop',iCMS_APP_CATEGORY,'pid');

        $fields = array(
            'rootid','pid','appid','sortnum','name','subname','password',
            'title','keywords','description','dir',
            'mode','domain','url','pic','mpic','spic','htmlext',
            'userid','creator',
            'rule','template','config','status');
        $data   = compact ($fields);

        if(empty($cid)) {
            category::check_priv($rootid,'a','alert');
            $nameArray = explode("\n",$name);
            $_count    = count($nameArray);
        	foreach($nameArray AS $nkey=>$_name){
        		$_name	= trim($_name);
                if(empty($_name)) continue;

                if($_count=="1"){
                    if(empty($dir) && empty($url)) {
                        $dir = strtolower(iTranslit::get($_name));
                    }
                }else{
                    empty($url) && $dir = strtolower(iTranslit::get($_name));
                }
                $mode=="2" && $this->check_dir($dir,$this->appid,$url);
                $data['name']     = $_name;
                $data['dir']      = $dir;
                $data['addtime']  = time();
                $data['count']    = '0';
                $data['comments'] = '0';
                $cid = iDB::insert('category',$data);
                iDB::update('category', array('sortnum'=>$cid), array('cid'=>$cid));
                iPHP::callback(array("apps_meta","save"),array(iCMS_APP_CATEGORY,$cid));
                iPHP::callback(array("formerApp","save"),array(iCMS_APP_CATEGORY,$cid));
                $pid && iMap::add($pid,$cid);
            }
            $msg = $this->category_name." успешно создан, не забудьте обновить кеш!";
        }else {
            if(empty($dir) && empty($url)) {
                $dir = strtolower(iTranslit::get($name));
            }
            category::check_priv($cid,'e','alert');
            $mode=="2" && $this->check_dir($dir,$this->appid,$url,$cid);
            $data['dir'] = $dir;
            iDB::update('category', $data, array('cid'=>$cid));
            iPHP::callback(array("apps_meta","save"),array(iCMS_APP_CATEGORY,$cid));
            iPHP::callback(array("formerApp","save"),array(iCMS_APP_CATEGORY,$cid));
            iMap::diff($pid,$_pid,$cid);
            $msg = $this->category_name."Редактирование завершено! Пожалуйста, не забудьте обновить кэш!";
        }
        $this->cache_item($cid);

        // $this->config();
        iPHP::callback(array("spider","callback"),array($this,$cid));
        if($this->callback['return']){
            return $this->callback['return'];
        }
        if($this->callback['save:return']){
            $this->callback['indexid'] = $cid;
            return $this->callback['save:return'];
        }

        iUI::success($msg,'url:'.$this->category_uri);
    }

    public function do_update(){
    	foreach((array)$_POST['name'] as $cid=>$name){
    		$name	= iSecurity::escapeStr($name);
			iDB::query("UPDATE `#iCMS@__category` SET `name` = '$name',`sortnum` = '".(int)$_POST['sortnum'][$cid]."' WHERE `cid` ='".(int)$cid."' LIMIT 1");
	    	//$this->cache_item($cid);
    	}
    	iUI::success('Успешно обновлено');
    }
    public function do_batch(){
        list($idArray,$ids,$batch) = iUI::get_batch_args("Выберите операцию для ".$this->category_name);
        switch($batch){
            case 'move':
                $tocid = (int)$_POST['tocid'];
                foreach($idArray as $k=>$cid){
                    $tocid!=$cid && iDB::query("UPDATE `#iCMS@__category` SET `rootid` ='$tocid' WHERE `cid` ='$cid'");
                }
                iUI::success('Обновление завершено!','js:1');
            break;
           case 'appid':
                $toappid = (int)$_POST['toappid'];
                foreach($idArray as $k=>$cid){
                    iDB::query("UPDATE `#iCMS@__category` SET `appid` ='$toappid' WHERE `cid` ='$cid'");
                }
                iUI::success('Обновление завершено!','js:1');
            break;
            case 'merge':
                $tocid = (int)$_POST['tocid'];
                foreach($idArray as $k=>$cid){
                    if($tocid!=$cid){
                        $this->merge($tocid,$cid);
                        $this->do_del($cid,false);
                    }
                }
                $this->update_app_count($tocid);
                // $this->cache(true,$this->appid);
                iUI::success('Обновление завершено!','js:1');
            break;
            case 'dir':
                $mdir = iSecurity::escapeStr($_POST['mdir']);
                if($_POST['pattern']=='replace') {
                    $sql = "`dir` = '$dir'";
                }
                if($_POST['pattern']=='addtobefore'){
                    $sql = "`dir` = CONCAT('{$mdir}',dir)";
                }
                if($_POST['pattern']=='addtoafter'){
                    $sql = "`dir` = CONCAT(dir,'{$mdir}')";
                }
                foreach($idArray as $k=>$cid){
                    $sql && iDB::query("UPDATE `#iCMS@__category` SET {$sql} WHERE `cid` ='".(int)$cid."' LIMIT 1");
                }
                iUI::success('Изменение каталога завершено!','js:1');
            break;
            case 'mkdir':
                foreach($idArray as $k=>$cid){
                    $name = iSecurity::escapeStr($_POST['name'][$cid]);
                    $dir  = iTranslit::get($name);
                    $this->check_dir($dir,$this->appid,null,$cid);
                    iDB::query("UPDATE `#iCMS@__category` SET `dir` = '$dir' WHERE `cid` ='".(int)$cid."' LIMIT 1");
                }
                iUI::success('Обновление завершено!','js:1');
            break;
            case 'update':
                foreach($idArray as $k=>$cid){
                    iDB::update('category',
                        array(
                            'name'    => iSecurity::escapeStr($_POST['name'][$cid]),
                            'dir'     => iSecurity::escapeStr($_POST['dir'][$cid]),
                            'sortnum' => intval($_POST['sortnum'][$cid]),
                        ),
                        array('cid'=>(int)$cid)
                    );
                }
                iUI::success('Обновление завершено!','js:1');
            break;
            case 'status':
                $val = (int)$_POST['status'];
                $sql ="`status` = '$val'";
            break;
            case 'mode':
                $val = (int)$_POST['mode'];
                $sql ="`mode` = '$val'";
            break;
            case 'rule':
                $rule = iSecurity::escapeStr($_POST['rule']);
                $rule = addslashes(json_encode($rule));
                $sql  ="`rule` = '$rule'";
            break;
            case 'template':
                $template = iSecurity::escapeStr($_POST['template']);
                $template = addslashes(json_encode($template));
                $sql  ="`template` = '$template'";
            break;
            case 'recount':
                foreach($idArray as $k=>$cid){
                    $this->update_app_count($cid);
                }
                iUI::success('Успешно выполнено!','js:1');
            break;
            case 'dels':
                iUI::$break = false;
                foreach($idArray AS $cid){
                    category::check_priv($cid,'d','alert');
                    $this->do_del($cid,false);
                    //$this->cache_item($cid);
                }
                iUI::$break    = true;
                iUI::success('Успешно удалено!','js:1');
            break;
        }
        $sql && iDB::query("UPDATE `#iCMS@__category` SET {$sql} WHERE `cid` IN ($ids)");
        // $this->cache(true,$this->appid);
        iUI::success('Успешно выполнено!','js:1');
    }
    /**
     * [Обновить сортировку]
     * @return [type] [description]
     */
    public function do_updateorder(){
    	foreach((array)$_POST['sortnum'] as $sortnum=>$cid){
            iDB::query("UPDATE `#iCMS@__category` SET `sortnum` = '".intval($sortnum)."' WHERE `cid` ='".intval($cid)."' LIMIT 1");
	    	//$this->cache_item($cid);
    	}
    }
    public function do_iCMS(){
        $tabs = iPHP::get_cookie(admincp::$APP_NAME.'_tabs');
        $tabs=="list"?$this->do_list():$this->do_tree();
    }
    /**
     * [树模式]
     * @return [type] [description]
     */
    public function do_tree() {
        menu::$url = __ADMINCP__.'='.admincp::$APP_NAME;
        admincp::$APP_DO = 'tree';
        include admincp::view($this->_view_manage,$this->_view_tpl_dir);
    }
    /**
     * [列表模式]
     * @return [type] [description]
     */
    public function do_list(){
        menu::$url = __ADMINCP__.'='.admincp::$APP_NAME;
        admincp::$APP_DO = 'list';
        $sql  = " WHERE 1=1";
        if($this->appid){
            $sql.= " AND `appid`='{$this->appid}'";
            $apps = apps::get($this->appid);
        }
        $cids = category::check_priv('CIDS','s');
        $sql.= iSQL::in($cids,'cid');

        if($_GET['keywords']) {
            if($_GET['st']=="name") {
                $sql.=" AND `name` REGEXP '{$_GET['keywords']}'";
            }else if($_GET['st']=="appid") {
                $sql.=" AND `appid`='{$_GET['keywords']}'";
            }else if($_GET['st']=="cid") {
                $sql.=" AND `cid` REGEXP '{$_GET['keywords']}'";
            }else if($_GET['st']=="tkd") {
                $sql.=" AND CONCAT(name,title,keywords,description) REGEXP '{$_GET['keywords']}'";
            }
        }
        if(isset($_GET['rootid']) &&$_GET['rootid']!='-1') {
            $sql.=" AND `rootid`='{$_GET['rootid']}'";
        }
        if(isset($_GET['status']) &&$_GET['status']!='') {
            $sql.=" AND `status`='{$_GET['status']}'";
        }
        list($orderby,$orderby_option) = $this->get_orderby();

        $maxperpage = $_GET['perpage']>0?(int)$_GET['perpage']:50;
        $total      = iPagination::totalCache("SELECT count(*) FROM `#iCMS@__category` {$sql}","G");
        iUI::pagenav($total,$maxperpage);
        $rs     = iDB::all("SELECT * FROM `#iCMS@__category` {$sql} order by {$orderby} LIMIT ".iPagination::$offset." , {$maxperpage}");
        $_count = count($rs);
        include admincp::view($this->_view_manage,$this->_view_tpl_dir);
    }
    public function do_copy(){
        iDB::query("
            INSERT INTO `#iCMS@__category` (
               `name`,`dir`,
               `rootid`, `pid`, `appid`, `userid`, `creator`,
               `subname`, `sortnum`, `password`, `title`, `keywords`, `description`,
               `url`, `pic`, `mpic`, `spic`, `count`, `mode`, `domain`, `htmlext`,
               `rule`, `template`,`config`, `comments`, `status`, `addtime`
            ) SELECT
                CONCAT(`name`,'副本'),CONCAT(`dir`,'fuben'),
                `rootid`, `pid`, `appid`, `userid`, `creator`,
                `subname`, `sortnum`, `password`, `title`, `keywords`, `description`,
                `url`, `pic`, `mpic`, `spic`, `count`, `mode`, `domain`, `htmlext`,
                `rule`, `template`,`config`,`comments`, `status`, `addtime`
            FROM `#iCMS@__category`
            WHERE cid = '$this->cid'");
        $cid = iDB::$insert_id;
        iUI::success('Копирование завершено, отредактируйте '.$this->category_name, 'url:' . APP_URI . '&do=add&cid=' . $cid);

    }
    public function do_del($cid = null,$dialog=true){
        $cid===null && $cid=(int)$_GET['cid'];
        category::check_priv($cid,'d','alert');
        $msg    = 'Выберите для удаления '.$this->category_name.'!';

        if(category::is_root($cid)) {
            $msg = 'Пожалуйста, удалите это сначала '.$this->category_name.' 下的子'.$this->category_name.'!';
        }else {
            $this->del_content($cid);
            iDB::query("DELETE FROM `#iCMS@__category` WHERE `cid` = '$cid'");
            iMap::del_data($cid,$this->appid,'category','node');
            iMap::del_data($cid,iCMS_APP_CATEGORY,'prop');
            category::cache_del($cid);
            $msg = 'Успешно удалено!';
        }
        $this->do_cache(false);
        $dialog && iUI::success($msg,'js:parent.$("#'.$cid.'").remove();');
    }
    public function do_ajaxtree(){
		$expanded=$_GET['expanded']?true:false;
	 	echo $this->tree((int)$_GET["root"],$expanded);
    }
    public function do_recount($dialog=true){
        $rs = iDB::all("SELECT `cid` FROM `#iCMS@__category` where `appid`='$this->appid'");
        foreach ((array)$rs as $key => $value) {
            $this->update_app_count($value['cid']);
        }
        $dialog && iUI::success('Успешно обновлено');
    }
    /**
     * [Получить настройки мета-свойств контента]
     * @return [type] [description]
     */
    public static function do_config_meta($ret=false,$cid=null){
        $cid===null && $cid = (int)$_GET['cid'];
        if($cid){
            $category = category::get($cid);
            $meta     = $category->config['meta'];
            if($ret) return $meta;
            iUI::json($meta);
        }
    }
    public function do_cache($dialog=true){
        @set_time_limit(0);
        categoryAdmincp::config();
        $total = category::total($this->appid);
        if($total>500){
            $_GET['total']  = $total;
            $_GET['_appid'] = $this->appid;
            $this->do_cache_burst();
        }else{
            category::cache($this->appid);
        }
        isset($_GET['dialog']) && $dialog = $_GET['dialog'];
        $dialog && iUI::success('Успешно обновлено');
    }
    /**
     * [Пакетное обновление кеша[NOPRIV]]
     * @param  [type] $total [description]
     * @return [type]        [description]
     */
    public function do_cache_burst(){
        @set_time_limit(0);
        iDB::query("set interactive_timeout=24*3600");

        $num      = 100;
        $appid    = (int)$_GET['_appid'];
        $page     = (int)$_GET['page'];
        $total    = (int)$_GET['total'];
        $flag     = $_GET['flag'];
        $offset   = $page*$num;

        empty($flag) && $flag = 'tmp';

        $config = array();
        if($flag === 'stop'){
            
            $config['stop'] = array(
                'msg'  =>'<div class="alert alert-info">Кеш '.$this->category_name.' успешно обновлен</div>',
                'time' =>'5',
            );
        }else{
            $callback = array(
                array('category','cache_burst'),
                array($appid,$offset,$num,$flag)
            );
            $map = array(
                'tmp'    => array('gold','生成'.$this->category_name.'临时缓存'),
                'gold'   => array('delete','生成'.$this->category_name.'缓存'),
                'delete' => array('common','清理'.$this->category_name.'临时缓存'),
                'common' => array('stop','Создать общий кэш'),
            );

            $next = $map[$flag][0];
            
            $config['step'] = array(
                'title'    =>'<div class="alert">正在'.$map[$flag][1].'</div>',
                'callback' =>$callback,
                'url'      =>'do=cache_burst&_appid='.$appid.'&flag='.$flag,
                'msg'      =>array($this->category_name,'个')
            );
            
            $config['next'] = array(
                'url'  =>'do=cache_burst&_appid='.$appid.'&flag='.$next.'&total='.$total,
                'msg'  =>'<hr /><div class="alert alert-info">准备进行'.$map[$next][1].'</div>',
                'time' =>'3',
            );
            if($flag === 'common'){
                $total = $num = 1;
                $config['step']['msg'] = array('操作','个');
                $config['next']['msg'] = '';
            }
        }
        iUI::loop($total,$num,$config);
    }
    public function cache_item($cid){
        $C = iDB::row("SELECT * FROM `#iCMS@__category` WHERE `cid`='$cid' LIMIT 1;",ARRAY_A);
        category::cache_item($C);

        $C = category::data($C);
        category::cache_item($C,'C');
        iCache::delete('category/'.$C['cid']);
    }

    public static function tree_unset($C){
        unset(
            $C->rule,$C->template,
            $C->description,$C->keywords,
            $C->password,$C->mpic,$C->spic,
            $C->title,$C->subname,$C->iurl,$C->dir,
            $C->htmlext,$C->config,$C->comments
        );
        return $C;
    }
    public function tree($cid = 0,$expanded=false,$ret=false){
        category::$priv = 's';
        $array      = array();
        $cid_array  = (array)category::get_cid($cid);
        $cate_array = (array)category::get($cid_array);
        foreach($cid_array AS $root=>$_cid) {
            $C = (array)$cate_array[$_cid];
            $a = array('id'=>$C['cid'],'data'=>$C);
            if(category::get_cid($C['cid'])){
                if($expanded){
                    $a['hasChildren'] = false;
                    $a['expanded']    = true;
                    $a['children']    = $this->tree($C['cid'],$expanded,$ret);
                }else{
                    $a['hasChildren'] = true;
                }
            }
            $a && $array[] = $a;
        }
        if($ret||($expanded && $cid)){
            return $array;
        }

        return $array?json_encode($array):'[]';
    }
    // public function tree($cid = 0,$expanded=false,$ret=false){
    //     $tree = array();
    //     $rootid = iCache::get('category/rootid');
    //     foreach((array)$rootid[$cid] AS $root=>$_cid) {
    //         $C = $this->cache_get($_cid);
    //         $C['iurl'] = (array) iURL::get('category',$C);
    //         $C['href'] = $C['iurl']['href'];
    //         $C = $this->tree_unset($C);
    //         $C['CP_ADD']  = category::check_priv($C['cid'],'a')?true:false;
    //         $C['CP_EDIT'] = category::check_priv($C['cid'],'e')?true:false;
    //         $C['CP_DEL']  = category::check_priv($C['cid'],'d')?true:false;

    //         $a = array('id'=>$C['cid'],'data'=>$C);
    //         if($rootid[$C['cid']]){
    //             if($expanded){
    //                 $a['hasChildren'] = false;
    //                 $a['expanded']    = true;
    //                 $a['children']    = $this->tree($C['cid'],$expanded,$ret);
    //             }else{
    //                 $a['hasChildren'] = true;
    //             }
    //         }
    //         $a && $tree[] = $a;
    //     }
    //     if($ret||($expanded && $cid)){
    //         return $tree;
    //     }

    //     //var_dump($html);
    //     return $tree?json_encode($tree):'[]';
    // }

    public function check_dir(&$dir,$appid,$url,$cid=0){
        if(empty($url)){
            // $sql ="SELECT `dir` FROM `#iCMS@__category` where `dir` ='$dir' AND `appid`='$appid'";
            // $cid && $sql.=" AND `cid` !='$cid'";
            $sql = "SELECT count(`cid`) FROM `#iCMS@__category` where `dir` ='$dir' ";
            $cid && $sql.=" AND `cid` !='$cid'";
            $hasDir = iDB::value($sql);
            if($hasDir){
                $count = iDB::value("SELECT count(`cid`) FROM `#iCMS@__category` where `dir` LIKE '{$dir}-%'");
                $dir = $dir.'-'.($count+1);
            }
       }

        // iDB::value($sql) && empty($url) && iUI::alert('该'.$this->category_name.'静态目录已经存在!<br />请重新填写(URL规则设置->静态目录)');
    }

    public static function del_app_data($appid=null){
        category::del_app_data($appid);
    }
    
    public function del_content($cid){

    }
    public function get_orderby(){
        return get_orderby(array(
            'cid'     =>"CID",
            'sortnum' =>"Сортировка",
            'dir'     =>"Каталог",
            'count'   =>"Количество записей",
        ));
    }
    public function merge($tocid,$cid){
        iDB::query("UPDATE `#iCMS@__".$this->_app_table."` SET `".$this->_app_cid."` ='$tocid' WHERE `".$this->_app_cid."` ='$cid'");
        tag::merge($tocid,$cid);
        //iDB::query("UPDATE `#iCMS@__push` SET `cid` ='$tocid' WHERE `cid` ='$cid'");
        iDB::query("UPDATE `#iCMS@__prop` SET `cid` ='$tocid' WHERE `cid` ='$cid'");
    }

    public function update_app_count($cid){
        $cc = iDB::value("SELECT count(*) FROM `#iCMS@__".$this->_app_table."` where `".$this->_app_cid."`='$cid'");
        iDB::query("UPDATE `#iCMS@__category` SET `count` ='$cc' WHERE `cid` ='$cid'");
    }

    public static function update_count($cid,$math='+'){
        category::update_count($cid,$math);
    }
    public static function _count($where=null){
        $sql = iSQL::where($where,true);
        return iDB::value("SELECT count(*) FROM `#iCMS@__category` WHERE 1=1 {$sql}");
    }
    public function batchbtn(){
        $ul = '<li><a data-toggle="batch" data-action="mode"><i class="fa fa-cogs"></i> Доступ</a></li>';
        $ul.='<li class="divider"></li>';
        $ul.='<li><a data-toggle="batch" data-action="rule"><i class="fa fa-link"></i> URL правила</a></li>';
        $ul.='<li><a data-toggle="batch" data-action="template"><i class="fa fa-columns"></i> Настройка шаблона</a></li>';

        // foreach ($this->category_rule as $key => $value) {
        //     $ul.='<li><a data-toggle="batch" data-action="rule_'.$key.'"><i class="fa fa-link"></i> '.$value[0].'规则</a></li>';
        // }
        // $ul.='<li class="divider"></li>';
        // foreach ($this->category_template as $key => $value) {
        //     $ul.='<li><a data-toggle="batch" data-action="template_'.$key.'"><i class="fa fa-columns"></i> '.$value[0].'模板</a></li>';
        // }
        return $ul;
    }
    public static function config($domain=null){
        if(empty($domain)){
            $rs  = iDB::all("
                SELECT `cid`,`domain`
                FROM `#iCMS@__category`
                WHERE `domain`!='' and `status`='1'
            ");
            foreach((array)$rs AS $C) {
                $domain[$C['domain']] = $C['cid'];
            }
        }

        configAdmincp::set(array(
            'domain'=>$domain
        ),'category',iCMS_APP_CATEGORY,false);

        configAdmincp::cache();
    }

}

