<?php

defined('iPHP') OR exit('Oops, something went wrong');
defined('APP_URI') OR define('APP_URI','admincp.php?app=article');

class articleAdmincp{
    public $callback = array();
    public $chapter  = false;
    public static $config   = null;
    public static $appid = null;

    public function __construct() {
        self::$appid     = iCMS_APP_ARTICLE;
        $this->id        = (int)$_GET['id'];
        $this->dataid    = (int)$_GET['dataid'];
        $this->_postype  = '1';
        $this->_status   = '1';
        self::$config    = iCMS::$config['article'];
        tag::$appid      = self::$appid;
        category::$appid = self::$appid;

        //iCMS::$config['plugin']['baidu']['xzh']['appid'] = 'appid';
        //iCMS::$config['plugin']['baidu']['xzh']['token'] = 'token';

    }

    public function do_config(){
        configAdmincp::app(self::$appid);
    }
    public function do_save_config(){
        configAdmincp::save(self::$appid);
    }
    /**
     * [Добавить статью]
     */
    public function do_add(){
        $_GET['cid'] && category::check_priv($_GET['cid'],'ca','page');
        $rs        = array();
        $bodyArray = array();
        if($this->id){
            list($rs,$adRs) = article::data($this->id,$this->dataid);
            category::check_priv($rs['cid'],'ce','page');
            if($adRs){
                if($rs['chapter']){
                    foreach ($adRs as $key => $value) {
                        $adIdArray[$key] = $value['id'];
                        $cTitArray[$key] = $value['subtitle'];
                        $bodyArray[$key] = $value['body'];
                    }
                }else{
                    $adRs['body'] = htmlspecialchars($adRs['body']);
                    self::$config['editor'] = $rs['markdown']?true:false;
                    $adIdArray = array($adRs['id']);
                    $bodyArray = explode('#--iCMS.PageBreak--#',$adRs['body']);
                }
            }
            iPHP::callback(array("apps_meta","get"),array(self::$appid,$this->id));
        }
        $bodyCount = count($bodyArray);
        $bodyCount OR $bodyCount = 1;
        $cid         = empty($rs['cid'])?(int)$_GET['cid']:$rs['cid'];
        $cata_option = category::priv('ca')->select($cid);
        $cid && $meta_setting = categoryAdmincp::do_config_meta(true,$cid);

        $rs['pubdate'] = get_date($rs['pubdate'],'Y-m-d H:i:s');
        $rs['markdown'] &&  self::$config['markdown'] = "1";
        if(empty($this->id)){
            $rs['status']  = "1";
            $rs['postype'] = "1";
            $rs['editor']  = members::$nickname;
            $rs['userid']  = members::$userid;
		}

        iPHP::callback(array("formerApp","add"),array(self::$appid,$rs,true));
        if(isset($_GET['ui_editor'])){
            self::$config['markdown'] = ($_GET['ui_editor']=='markdown')?"1":"0";
        }
        include admincp::view("article.add");
    }
    public function do_update(){
    	$data = iSQL::update_args($_GET['_args']);
        if($data){
            if(isset($data['pid'])){
                iMap::init('prop',self::$appid,'pid');
                $_pid = article::value('pid',$this->id);
                iMap::diff($data['pid'],$_pid,$this->id);
            }
            article::update($data,array('id'=>$this->id));
        }
    	iUI::success('Успешно выполнено!','js:1');
    }
    public function do_updateorder(){
        foreach((array)$_POST['sortnum'] as $sortnum=>$id){
            article::update(compact('sortnum'),compact('id'));
        }
    }
    public function do_batch(){
        list($idArray,$ids,$batch) = iUI::get_batch_args("Выберите документ");
    	switch($batch){
    		case 'order':
		        foreach((array)$_POST['sortnum'] AS $id=>$sortnum) {
                    article::update(compact('sortnum'),compact('id'));
		        }
		        iUI::success('Сортировка обновлена!','js:1');
            break;
            case 'meta':
                foreach((array)$_POST['id'] AS $id) {
                    iPHP::callback(array("apps_meta","save"),array(self::$appid,$id));
                }
                iUI::success('Успешно добавлено!','js:1');
            break;
            case 'purge':
                @set_time_limit(0);
                @header('Cache-Control: no-cache');
                iUI::flush_start();
                iUI::$break = false;
                $_count = count($_POST['id']);
                foreach((array)$_POST['id'] as $i => $id) {
                    $this->do_purge($id,false);
                    $updateMsg = $i ? true : false;
                    $timeout = ($i++) == $_count ? '3' : false;
                    iUI::dialog($id.'Очистка завершена!', 'js:void(0)', $timeout, 0, $updateMsg);
                    iUI::flush();
                }
                iUI::dialog('success:#:check:#:Очистка завершена!', 0, 3, 0, true);
                // iUI::success('Очистка завершена!','js:1');
            break;
            case 'baiduping':
                foreach((array)$_POST['id'] AS $id) {
                    $msg.= $this->do_baiduping($id,false);
                }
                iUI::success($msg,'js:1');
            break;
            case 'baiduping_original':
                foreach((array)$_POST['id'] AS $id) {
                    $this->do_baiduping_original($id,false);
                }
                iUI::success('Нажмите, чтобы завершить!','js:1');
            break;
            case 'baidu_xzh':
                foreach((array)$_POST['id'] AS $id) {
                    $this->do_baidu_xzh($id,false);
                }
                iUI::success('Нажмите, чтобы завершить!','js:1');
            break;
    		case 'move':
		        $_POST['cid'] OR iUI::alert("请选择目标栏目!");
                iMap::init('category',self::$appid,'cid');
                $cid = (int)$_POST['cid'];
                category::check_priv($cid,'ca','alert');
		        foreach((array)$_POST['id'] AS $id) {
                    $_cid = article::value('cid',$id);
                    article::update(compact('cid'),compact('id'));
		            if($_cid!=$cid) {
                        iMap::diff($cid,$_cid,$id);
                        categoryAdmincp::update_count($_cid,'-');
                        categoryAdmincp::update_count($cid);
		            }
		        }
		        iUI::success('Успешно перемещено!','js:1');
            break;
            case 'scid':
                //$_POST['scid'] OR iUI::alert("请选择目标栏目!");
                iMap::init('category',self::$appid,'cid');
                $scid = implode(',', (array)$_POST['scid']);
                foreach((array)$_POST['id'] AS $id) {
                    $_scid = article::value('scid',$id);
                    article::update(compact('scid'),compact('id'));
                    iMap::diff($scid,$_scid,$id);
                }
                iUI::success('文章副栏目设置完成!','js:1');
            break;
            case 'prop':
                iMap::init('prop',self::$appid,'pid');
                $pid = implode(',', (array)$_POST['pid']);
                foreach((array)$_POST['id'] AS $id) {
                    $_pid = article::value('pid',$id);
                    article::update(compact('pid'),compact('id'));
                    iMap::diff($pid,$_pid,$id);
                }
                iUI::success('Настройка свойст успешно завершено!','js:1');
    		break;
    		case 'weight':
                $data = array('weight'=>$_POST['mweight']);
    		break;
            case 'status':
                $data = array('status'=>$_POST['mstatus']);
            break;
            case 'postype':
                $data = array('postype'=>$_POST['mpostype']);
            break;

    		case 'keyword':
    			if($_POST['pattern']=='replace') {
                    $data = array('keywords'=>iSecurity::escapeStr($_POST['mkeyword']));
    			}elseif($_POST['pattern']=='addto') {
		        	foreach($_POST['id'] AS $id){
                        $keywords = article::value('keywords',$id);
                        $keywords = $keywords?$keywords.','.iSecurity::escapeStr($_POST['mkeyword']):iSecurity::escapeStr($_POST['mkeyword']);
                        article::update(compact('keywords'),compact('id'));
		        	}
		        	iUI::success('Изменение ключевого слова документа завершено!','js:1');
    			}
    		break;
    		case 'tag':
		     	foreach($_POST['id'] AS $id){
                    $art  = article::row($id,'tags,cid');
                    $mtag = iSecurity::escapeStr($_POST['mtag']);
                    $tagArray  = $art['tags']?explode(',', $art['tags']):array();
                    $mtagArray = explode(',', $mtag);
			        if($_POST['pattern']=='replace') {
                    }elseif($_POST['pattern']=='delete') {
                        foreach ($mtagArray as $key => $value) {
                            $tk = array_search($value, $tagArray);
                            if($tk!==false){
                                unset($tagArray[$tk]);
                            }
                        }
                        $mtag   = implode(',', $tagArray);
                    }elseif($_POST['pattern']=='addto') {
                        $pieces = array_merge($tagArray,$mtagArray);
                        $pieces = array_unique($pieces);
                        $mtag   = implode(',', $pieces);
			        }
                    $mtag = ltrim($mtag,',');
                    $tags = tag::diff($mtag,$art['tags'],members::$userid,$id,$art['cid']);
                    $tags = addslashes($tags);
                    article::update(compact('tags'),compact('id'));
		    	}
		    	iUI::success('Теги успешно изменены!','js:1');
    		break;
    		case 'thumb':
		        foreach((array)$_POST['id'] AS $id) {
		            $body	= article::body($id);
                    $picurl = filesAdmincp::remotepic($body,'autopic');
                    $this->set_pic($picurl,$id);
		        }
		        iUI::success('Эскизы успешно извлечены!','js:1');
    		break;
    		case 'dels':
                set_time_limit(0);
    			iUI::$break	= false;
    			iUI::flush_start();
    			$_count	= count($_POST['id']);
				foreach((array)$_POST['id'] AS $i=>$id) {
			     	$msg = $this->del($id);
			        // $msg.= $this->del_msg('文章删除完成!');
					$updateMsg	= $i?true:false;
					$timeout	= ($i++)==$_count?'3':false;
					iUI::dialog($msg,'js:parent.$("#id'.$id.'").remove();',$timeout,0,$updateMsg);
		        	iUI::flush();
	   			}
	   			iUI::$break	= true;
				iUI::success('文章全部删除完成!','js:1',3,0,true);
    		break;
            case 'quick_dels':
                set_time_limit(0);
                iUI::$break = false;
                iUI::flush_start();
                $_count = count($_POST['id']);
                foreach((array)$_POST['id'] AS $i=>$id) {
                    $msg = $this->del_art($id);
                    // $msg.= $this->del_msg('文章删除完成!');
                    $updateMsg  = $i?true:false;
                    $timeout    = ($i++)==$_count?'3':false;
                    iUI::dialog($msg,'js:parent.$("#id'.$id.'").remove();',$timeout,0,$updateMsg);
                    iUI::flush();
                }
                iUI::$break = true;
                iUI::success('文章全部删除完成!','js:1',3,0,true);
            break;
    		default:
				$data = iSQL::update_args($batch);
    	}
        $data && article::batch($data,$ids);
		iUI::success('Успешно выполнено!','js:1');
    }
    /**
     * [百度推送 ]
     * @param  [type]  $id     [description]
     * @param  boolean $dialog [description]
     * @return [type]          [description]
     */
    public function do_baiduping($id = null,$dialog=true){
        $id===null && $id=$this->id;
        $id OR iUI::alert('Выберите публикацию!');
        $rs   = article::row($id);
        $C    = category::get($rs['cid']);
        $iurl = (array)iURL::get('article',array($rs,$C));
        $urls = array();
        $urls[] = $iurl['href'];
        if($iurl['mobile']['url']){
            $urls[] = $iurl['mobile']['url'];
            $m_rpc = plugin_baidu::RPC2($iurl['mobile']['url']);
        }
        $res = plugin_baidu::ping($urls);
        $p_rpc = plugin_baidu::RPC2($iurl['href']);
        // if($iurl['mip']['url']){
        //     $mip = plugin_baidu::ping($iurl['mip']['url'],'mip');
        // }
        if($res===true){
            $msg = 'Нажмите, чтобы завершить';
            $dialog && iUI::success($msg,'js:1');
        }else{
            $msg = 'Не удалось отправить! ['.$res->message.']';
            $dialog && iUI::alert($msg,'js:1');
        }
        if(!$dialog) return $msg.'<br />';
    }
    public function do_check(){
        $id    = (int)$_GET['id'];
        $title = $_GET['title'];
        if(self::$config['repeatitle'] && article::check($title,$id,'title')) {
            iUI::code(0,'Заголовок уже существует!');
        }else{
            iUI::code(1);
        }
    }

    public function do_baiduping_original($id = null,$dialog=true){
        $id===null && $id=$this->id;
        $id OR iUI::alert('Выберите публикацию!');
        $rs   = article::row($id);
        $C    = category::get($rs['cid']);
        $iurl = (array)iURL::get('article',array($rs,$C));
        $urls = array();
        $urls[] = $iurl['href'];
        if($iurl['mobile']['url']){
            $urls[] = $iurl['mobile']['url'];
        }
        $res = plugin_baidu::ping($urls,'original');

        if($res===true){
            $msg = 'Нажмите, чтобы завершить';
            $dialog && iUI::success($msg,'js:1');
        }else{
            $msg = 'Не удалось отправить! ['.$res->message.']';
            $dialog && iUI::alert($msg,'js:1');
        }
        if(!$dialog) return $msg.'<br />';
    }
    public function do_baidu_xzh($id = null,$dialog=true){
        $id===null && $id=$this->id;
        $id OR iUI::alert('Выберите публикацию!');
        $rs   = article::row($id);
        $C    = category::get($rs['cid']);
        $iurl = (array)iURL::get('article',array($rs,$C));
        $urls = array($iurl['mobile']['url']);
        $res = plugin_baidu::xzh($urls,'realtime',$out);
        if($res){
            $msg = 'Нажмите, чтобы завершить';
            $dialog && iUI::success($msg,'js:1');
        }else{
            $msg = 'Не удалось отправить! ['.$out['message'].']';
            $dialog && iUI::alert($msg,'js:1');
        }
        if(!$dialog) return $msg.'<br />';
    }
    
    public function do_getjson(){
        $id = (int)$_GET['id'];
        $rs = article::row($id);
        iUI::json($rs);
    }
    /**
     * [Быстрое редактирование]
     * @return [type] [description]
     */
     public function do_edit(){
        $id          = (int)$_POST['id'];
        $cid         = (int)$_POST['cid'];
        $pid         = (int)$_POST['pid'];
        $source      = iSecurity::escapeStr($_POST['source']);
        $title       = iSecurity::escapeStr($_POST['title']);
        $tags        = iSecurity::escapeStr($_POST['tags']);
        $description = iSecurity::escapeStr($_POST['description']);

		$art = article::row($id,'tags,cid');
		if($tags){
			$tags = tag::diff($tags,$art['tags'],members::$userid,$id,$art['cid']);
		    $tags = addslashes($tags);
        }
        $data = compact('cid','pid','title','tags','description');
		if($_POST['status']=="1"){
            $data['status'] = 1;
		}
		if($_POST['statustime']=="1"){
            $data['status']  = 1;
            $data['pubdate'] = time();
		}
        article::update($data ,compact('id'));
		iUI::json(array('code'=>1));
	}
    
    public function do_findpic($id=null){
        $id===null && $id=$this->id;
        $rs = $this->getContentPics($id);
        $_count = count($rs);
        include admincp::view("files.manage","files");
    }
    public static function getContentPics($id=null){
        $content  = article::body($id);
        $picArray = array();
        if($content){
            $content = stripslashes($content);
            $array   = files::preg_img($content);
            $fArray  = array();
            foreach ($array as $key => $value) {
                $value = trim($value);
                // echo $value.PHP_EOL;
                if (stripos($value,iCMS_FS_HOST) !== false){
                    $filepath = iFS::fp($value,'-http');
                    $rpath    = iFS::fp($value,'http2iPATH');
                   if($filepath){
                        $pf   = pathinfo($filepath);
                        $picArray[] = array(
                            'id'       => 'path@'.$filepath,
                            'rootpath' => $rpath,
                            'path'     => rtrim($pf['dirname'],'/').'/',
                            'filename' => $pf['filename'],
                            'size'     => @filesize($rpath),
                            'time'     => @filectime($rpath),
                            'ext'      => $pf['extension']
                        );
                    }
                }
            }
        }
        return $picArray;
    }

    /**
     * [Предварительный просмотр текста]
     * @return [type] [description]
     */
    public function do_preview(){
		echo article::body($this->id);
    }
    public function do_iCMS(){
    	admincp::$APP_DO="manage";
    	$this->do_manage();
    }
    public function do_inbox(){
    	$this->do_manage("inbox");
    }
    public function do_trash(){
        $this->_postype = 'all';
    	$this->do_manage("trash");
    }
    public function do_user(){
        $this->_postype = 0;
        $this->do_manage();
    }
    public function do_examine(){
        $this->_postype = 0;
        $this->do_manage("examine");
    }
    public function do_off(){
        $this->_postype = 0;
        $this->do_manage("off");
    }

    public function do_manage($stype='normal') {
        $cid = (int)$_GET['cid'];
        $pid = $_GET['pid'];
        //$stype OR $stype = admincp::$APP_DO;
        $stype_map = array(
            'inbox'   =>'0',//Входящие публикации
            'normal'  =>'1',//Опубликован
            'trash'   =>'2',//Корзина
            'examine' =>'3',//В ожидании
            'off'     =>'4',//Отказ
        );
        $map_where = array();
        //status:[0:草稿][1:正常][2:回收][3:待审核][4:不合格]
        //postype: [0:用户][1:管理员]
        $stype && $this->_status = $stype_map[$stype];

        $sql = 'WHERE 1=1';
        if(is_numeric($_GET['postype'])){
            $this->_postype = (int)$_GET['postype'];
        }
        if(is_numeric($_GET['status'])){
            $this->_status = (int)$_GET['status'];
        }
        is_numeric($this->_postype) && $sql.=" AND `postype` ='".$this->_postype."'";
        is_numeric($this->_status) && $sql.=" AND `status` ='".$this->_status."'";

        if(members::check_priv("article.VIEW")){
            $_GET['userid'] && $sql.= iSQL::in($_GET['userid'],'userid');
        }else{
            $sql.= iSQL::in(members::$userid,'userid');
        }

        if(isset($_GET['pid']) && $pid!='-1'){
            $uri_array['pid'] = $pid;
            if(empty($_GET['pid'])){
                $sql.= " AND `pid`=''";
            }else{
                iMap::init('prop',self::$appid,'pid');
                $map_where+=iMap::where($pid);
            }
        }

        $cp_cids = category::check_priv('CIDS','cs');//Получить все привилегированные id категорий

        if($cp_cids) {
            if(is_array($cp_cids)){
                if($cid){
                    array_search($cid,$cp_cids)===false && admincp::permission_msg('Категория [cid:'.$cid.']',$ret);
                }else{
                    $cids = $cp_cids;
                }
            }else{
                $cids = $cid;
            }
            if($_GET['sub'] && $cid){
                $cids = categoryApp::get_cids($cid,true);
                array_push ($cids,$cid);
            }
            if($_GET['scid'] && $cid){
                iMap::init('category',self::$appid,'cid');
                $map_where+= iMap::where($cids);
            }else{
                $sql.= iSQL::in($cids,'cid');
            }
        }else{
            $sql.= iSQL::in('-1','cid');
        }
        if($_GET['hidden']) {
            $hidden = categoryApp::get_cache('hidden');
            $hidden && $sql.= iSQL::in($hidden, 'cid', 'not');
        }
        if($_GET['keywords']) {
            $kws = $_GET['keywords'];
            switch ($_GET['st']) {
                case "title": $sql.=" AND `title` REGEXP '{$kws}'";break;
                case "tag":   $sql.=" AND `tags` REGEXP '{$kws}'";break;
                case "source":$sql.=" AND `source` REGEXP '{$kws}'";break;
                case "clink":$sql.=" AND `clink` REGEXP '{$kws}'";break;
                case "weight":$sql.=" AND `weight`='{$kws}'";break;
                case "id":
                $kws = str_replace(',', "','", $kws);
                $sql.=" AND `id` IN ('{$kws}')";
                break;
                case "tkd":   $sql.=" AND CONCAT(title,stitle,keywords,description) REGEXP '{$kws}'";break;
            }
        }

        $_GET['title']     && $sql.=" AND `title` like '%{$_GET['title']}%'";
        $_GET['tag']       && $sql.=" AND `tags` REGEXP '[[:<:]]".preg_quote(rawurldecode($_GET['tag']),'/')."[[:>:]]'";
        $_GET['starttime'] && $sql.=" AND `pubdate`>='".str2time($_GET['starttime'].(strpos($_GET['starttime'],' ')!==false?'':" 00:00:00"))."'";
        $_GET['endtime']   && $sql.=" AND `pubdate`<='".str2time($_GET['endtime'].(strpos($_GET['endtime'],' ')!==false?'':" 23:59:59"))."'";
        $_GET['post_starttime'] && $sql.=" AND `postime`>='".str2time($_GET['post_starttime'].(strpos($_GET['post_starttime'],' ')!==false?'':" 00:00:00"))."'";
        $_GET['post_endtime']   && $sql.=" AND `postime`<='".str2time($_GET['post_endtime'].(strpos($_GET['post_endtime'],' ')!==false?'':" 23:59:59"))."'";
        isset($_GET['pic'])&& $sql.=" AND `haspic` ='".($_GET['pic']?1:0)."'";

        isset($_GET['userid']) && $uriArray['userid']  = (int)$_GET['userid'];
        isset($_GET['keyword'])&& $uriArray['keyword'] = $_GET['keyword'];
        isset($_GET['tag'])    && $uriArray['tag']     = $_GET['tag'];
        isset($_GET['postype'])&& $uriArray['postype'] = $_GET['postype'];
        isset($_GET['cid'])    && $uriArray['cid']     = $_GET['cid'];

        list($orderby,$orderby_option) = get_orderby(array(
            'id'         =>"ID",
            'hits'       =>"Просмотры",
            'hits_week'  =>"Просмотры за неделю",
            'hits_month' =>"Просмотры за месяц",
            'good'       =>"顶",
            'postime'    =>"Дата и время",
            'pubdate'    =>"Дата обновления",
            'comments'   =>"Кол-во комменатриев",
        ));

        $maxperpage = $_GET['perpage']>0?(int)$_GET['perpage']:20;

        if($map_where){
            $map_sql = iSQL::select_map($map_where);
            $sql     = ",({$map_sql}) map {$sql} AND `id` = map.`iid`";
        }
        if(self::$config['total_num']){
            $total = (int)self::$config['total_num'];
        }else{
            $total = iPagination::totalCache(article::count_sql($sql),"G");
        }

        iUI::pagenav($total,$maxperpage,"Публикации");

        $limit = 'LIMIT '.iPagination::$offset.','.$maxperpage;

        if($map_sql||iPagination::$offset){
            if(iPagination::$offset > 1000 && $total > 2000 && iPagination::$offset >= $total/2) {
                $_offset = $total-iPagination::$offset-$maxperpage;
                if($_offset < 0) {
                    $_offset = 0;
                }
                $orderby = "id ASC";
                $limit = 'LIMIT '.$_offset.','.$maxperpage;
            }
        // if($map_sql){
            $ids_array = iDB::all("
                SELECT `id` FROM `#iCMS@__article` {$sql}
                ORDER BY {$orderby} {$limit}
            ");
            if(isset($_offset)){
                $ids_array = array_reverse($ids_array, TRUE);
                $orderby   = "id DESC";
            }

            $ids = iSQL::values($ids_array);
            $ids = $ids?$ids:'0';
            $sql = "WHERE `id` IN({$ids})";
            // }else{
                // $sql = ",(
                    // SELECT `id` AS aid FROM `#iCMS@__article` {$sql}
                    // ORDER BY {$orderby} {$limit}
                // ) AS art WHERE `id` = art.aid ";
            // }
            $limit = '';
        }
        $rs = iDB::all("SELECT * FROM `#iCMS@__article` {$sql} ORDER BY {$orderby} {$limit}");
        $_count = count($rs);
        $propArray = propAdmincp::get("pid",null,'array');
        include admincp::view("article.manage");
    }
    public function do_save(){
        $aid         = (int)$_POST['article_id'];
        $cid         = (int)$_POST['cid'];
        category::check_priv($cid,($aid?'ce':'ca'),'alert');


        $userid      = (int)$_POST['userid'];
        $ucid        = (int)$_POST['ucid'];
        $scid        = implode(',', (array)$_POST['scid']);
        $pid         = implode(',', (array)$_POST['pid']);
        $status      = (int)$_POST['status'];
        $_chapter    = (int)$_POST['chapter'];
        $sortnum     = (int)$_POST['sortnum'];
        $weight      = (int)$_POST['weight'];

        $hits        = (int)$_POST['hits'];
        $hits_today  = (int)$_POST['hits_today'];
        $hits_yday   = (int)$_POST['hits_yday'];
        $hits_week   = (int)$_POST['hits_week'];
        $hits_month  = (int)$_POST['hits_month'];
        $favorite    = (int)$_POST['favorite'];
        $comments    = (int)$_POST['comments'];
        $good        = (int)$_POST['good'];
        $bad         = (int)$_POST['bad'];

        $_cid        = iSecurity::escapeStr($_POST['_cid']);
        $_pid        = iSecurity::escapeStr($_POST['_pid']);
        $_scid       = iSecurity::escapeStr($_POST['_scid']);
        $_tags       = iSecurity::escapeStr($_POST['_tags']);
        $title       = iSecurity::escapeStr($_POST['title']);
        $stitle      = iSecurity::escapeStr($_POST['stitle']);
        $pic         = iSecurity::escapeStr($_POST['pic']);
        $mpic        = iSecurity::escapeStr($_POST['mpic']);
        $spic        = iSecurity::escapeStr($_POST['spic']);
        $source      = iSecurity::escapeStr($_POST['source']);
        $author      = iSecurity::escapeStr($_POST['author']);
        $editor      = iSecurity::escapeStr($_POST['editor']);
        $description = iSecurity::escapeStr($_POST['description']);
        $keywords    = iSecurity::escapeStr($_POST['keywords']);
        $clink       = iSecurity::escapeStr($_POST['clink']);
        $url         = iSecurity::escapeStr($_POST['url']);
        $tpl         = iSecurity::escapeStr($_POST['tpl']);
        $body        = (array)$_POST['body'];
        $creative    = (int)$_POST['creative'];
        $markdown    = (int)$_POST['markdown'];

        if(is_array($_POST['tags'])){
            $_POST['tags'] = array_filter($_POST['tags']);
            $_POST['tags'] = array_unique($_POST['tags']);
            $_POST['tags'] = implode(',', $_POST['tags']);
        }
        $tags = str_replace(',', ',',iSecurity::escapeStr($_POST['tags']));

        is_array($_POST['related']) && $related = json_encode($_POST['related']);

        if (empty($title)) {
            return iUI::alert('Заголовок не может быть пустым!');
        }
        if (empty($cid)) {
            return iUI::alert('Выберите категорию');
        }
        if(empty($body) && empty($url)){
            return iUI::alert('Содержание не может быть пустым!');
        }

        $pubdate   = str2time($_POST['pubdate']);
        $postype   = $_POST['postype']?$_POST['postype']:0;
        $userid OR $userid = members::$userid;
        $tags && $tags = preg_replace('/<[\/\!]*?[^<>]*?>/is','',$tags);

        if(self::$config['filter'] && is_array(self::$config['filter']) && !isset($_POST['nofilter'])) {
            foreach (self::$config['filter'] as $fkey => $fvalue) {
                list($field,$text) = explode(':', $fvalue);
                if($fwd = iPHP::callback(array("filterApp","run"),array(&${$field}),false)){
                    return iUI::alert($text.'中包含['.$fwd.']被系统屏蔽的字符,请重新填写.');
                }
            }
        }

        if(self::$config['repeatitle'] && article::check($title,$aid,'title')) {
            return iUI::alert('Заголовок уже существует!');
        }

        $category = category::get($cid);
        if(strstr($category->rule['article'],'{LINK}')!==false && empty($clink)){
            $clink = iTranslit::get($title,self::$config['clink']);
        }

        if($clink && article::check($clink,$aid,'clink')){
            return iUI::alert('Пользовательская ссылка уже существует, попробуйте другое название!.');
        }

        if(empty($description) && empty($url)) {
            if($_POST['markdown']){
                $md_body = iPHP::callback(array("plugin_markdown","HOOK"),array(implode('', (array)$body),&$_POST));
                empty($md_body) && $md_body = $body;
                $description = $this->autodesc($md_body);
            }else{
                $description = $this->autodesc($body);
            }
        }

        (iFS::checkHttp($pic)  && !isset($_POST['pic_http']))  && $pic  = iFS::http($pic);
        (iFS::checkHttp($bpic) && !isset($_POST['bpic_http'])) && $bpic = iFS::http($bpic);
        (iFS::checkHttp($mpic) && !isset($_POST['mpic_http'])) && $mpic = iFS::http($mpic);
        (iFS::checkHttp($spic) && !isset($_POST['spic_http'])) && $spic = iFS::http($spic);

        $haspic   = empty($pic)?0:1;

        $REFERER_URL = $_POST['REFERER'];
        if(empty($REFERER_URL)||strstr($REFERER_URL, '=save')){
        	$REFERER_URL= APP_URI.'&do=manage';
        }

        $editor OR  $editor = members::$nickname;

        $picdata = '';
        $fields  = article::fields($aid);

        if(empty($aid)) {
            $postime = $pubdate;
            $chapter = 0;
            $mobile  = 0;

            $aid  = article::insert(compact($fields));
            iPHP::callback(array("spider","callback"),array($this,$aid,'primary'));

            if($tags){
                if(isset($_POST['tag_status'])){
                    tag::$add_status = $_POST['tag_status'];
                }
                tag::add($tags,members::$userid,$aid,$cid);
            }

            iMap::init('prop',self::$appid,'pid');
            $pid && iMap::add($pid,$aid);

            iMap::init('category',self::$appid,'cid');
            iMap::add($cid,$aid);
            $scid && iMap::add($scid,$aid);

            $url OR $this->article_data($body,$aid,$haspic,$_chapter);
            categoryAdmincp::update_count($cid);
            iPHP::callback(array("apps_meta","save"),array(self::$appid,$aid));
            iPHP::callback(array("formerApp","save"),array(self::$appid,$aid));

            $article_url = iURL::get('article',array(array(
                'id'      =>$aid,
                'url'     =>$url,
                'cid'     =>$cid,
                'clink'   =>$clink,
                'pubdate' =>$pubdate
            ),(array)$category))->href;

            if($status && iCMS::$config['api']['baidu']['sitemap']['sync']){
                $msg = $this->do_baiduping($aid,false);
            }

            if($this->callback['return']){
                return $this->callback['return'];
            }
            if($this->callback['save:return']){
                $this->callback['indexid'] = $aid;
                return $this->callback['save:return'];
            }
            if($_GET['callback']=='json'){
                echo json_encode(array(
                    "code"    => '1001',
                    'indexid' => $aid
                ));
                return;
            }
            if(isset($_GET['keyCode'])){
                iUI::success('文章保存成功','url:'.APP_URI."&do=add&id=".$aid);
            }

            $moreBtn = array(
                    array("text" =>"Посмотреть документ","target"=>'_blank',"url"=>$article_url,"close"=>false),
                    array("text" =>"Редактировать","url"=>APP_URI."&do=add&id=".$aid),
                    array("text" =>"Добавить еще","url"=>APP_URI."&do=add&cid=".$cid),
                    array("text" =>"Вернуться назад","url"=>$REFERER_URL),
                    array("text" =>"На главную","url"=>iCMS_URL,"target"=>'_blank')
            );
            iUI::$dialog['modal'] = true;
            iUI::dialog('success:#:check:#:Документ успешно создан!<br />Вернуться к списку документов через 10 секунд '.$msg,'url:'.$REFERER_URL,10,$moreBtn);
        }else{
            isset($_POST['ischapter']) OR $chapter = 0;

	        ($tags||$_tags) && tag::diff($tags,$_tags,members::$userid,$aid,$cid);

            $picdata = filesAdmincp::picdata($pic,$mpic,$spic);

            article::update(compact($fields),array('id'=>$aid));
            $return = iPHP::callback(array("spider","callback"),array($this,$aid,'primary'));

            iMap::init('prop',self::$appid,'pid');
            iMap::diff($pid,$_pid,$aid);
            iMap::init('category',self::$appid,'cid');
            iMap::diff($cid,$_cid,$aid);
            $scid && iMap::diff($scid,$_scid,$aid);

            $url OR $this->article_data($body,$aid,$haspic,$_chapter);

            if($_cid!=$cid) {
                categoryAdmincp::update_count($_cid,'-');
                categoryAdmincp::update_count($cid);
            }
            iPHP::callback(array("apps_meta","save"),array(self::$appid,$aid));
            iPHP::callback(array("formerApp","save"),array(self::$appid,$aid));

            if($this->callback['return']){
                return $this->callback['return'];
            }

            if($this->callback['save:return']){
                $this->callback['indexid'] = $aid;
                return $this->callback['save:return'];
            }
            if(isset($_GET['keyCode'])){
                iUI::success('文章保存成功');
            }
            iUI::success('文章编辑完成!<br />3秒后返回文章列表','url:'.$REFERER_URL);
        }
    }
    public function do_del($id = null,$dialog=true){
        $id===null && $id=$this->id;
        $msg = $this->del($id);
        $msg.= $this->del_msg('文章删除完成!');
        if($dialog){
            $msg.= $this->del_msg('10秒后返回文章列表!');
            iUI::$dialog['modal'] = true;
            iUI::dialog($msg,'js:1');
        }
    }
    public function do_purge($id=null,$return=false){
        $id===null && $id=$_GET['id'];
        $article = article::row($id);
        $category = categoryApp::category($article['cid'], false);
        $iurl = (array)iURL::get('article',array($article,$category));

        foreach ($iurl as $key => $value) {
            if(is_array($value) && isset($value['url'])){
                $url  = $value['url'];
                $p    = parse_url($value['url']);
                $url  = str_replace($p['host'],$p['host'].'/~cc', $value['url']);
                $purl = str_replace($p['host'],$p['host'].'/~cc', $value['pageurl']);
                $msg.= $this->fopen_url($url);
                $msg.= $this->fopen_url($url,true);
                for($i=2;$i<100;$i++){
                    $purl  = str_replace('{P}', $i, $purl);
                    $msg.= $this->fopen_url($purl);
                    $msg.= $this->fopen_url($purl,true);
                }
            }
        }
        if($dialog) return $msg;
        echo $msg;
    }

    public static function del_msg($str){
        return iUI::msg('success:#:check:#:'.$str.'<hr style="width:200px;"/>',true);
    }
    public function del_pic($pic){
        //$thumbfilepath    = gethumb($pic,'','',false,true,true);
        iFS::del(iFS::fp($pic,'+iPATH'));
        $msg    = $this->del_msg($pic.'Удалить');
//      if($thumbfilepath)foreach($thumbfilepath as $wh=>$fp) {
//              iFS::del(iFS::fp($fp,'+iPATH'));
//              $msg.= $this->del_msg('缩略图 '.$wh.' 文件删除');
//      }
        $filename   = iFS::info($pic)->filename;
        article::del_filedata($filename,'filename');
        $msg.= $this->del_msg($pic.'Данные удалены');
        return $msg;
    }
    public static function del_art($id,$uid='0',$postype='1') {
        $id = (int)$id;
        $id OR iUI::alert("请选择要删除的文章");
        article::del($id);
        article::del_data($id);
        $msg.= self::del_msg($id.' Публикация удалена');
        return $msg;
    }
    public static function del($id,$uid='0',$postype='1') {
        $id = (int)$id;
        $id OR iUI::alert("请选择要删除的文章");
        $uid && $sql="and `userid`='$uid' and `postype`='$postype'";
        $art = article::row($id,'cid,pic,tags',$sql);
        category::check_priv($art['cid'],'cd','alert');

        $fids   = files::index_fileid($id,self::$appid);
        $pieces = files::delete_file($fids);
        files::delete_fdb($fids,$id,self::$appid);
        $msg.= self::del_msg(implode('<br />', $pieces).' 文件删除');
        $msg.= self::del_msg('Удаление связанных файлов');

        if($art['tags']){
            tag::$remove = false;
            $msg.= tag::del($art['tags'],'name',$id);
        }

        iMap::del_data($id,self::$appid,'category');
        iMap::del_data($id,self::$appid,'prop');

        commentAdmincp::delete($id,self::$appid);
        $msg.= self::del_msg('Данные комментария удалены');
        article::del($id);

        article::del_data($id);
        $msg.= self::del_msg($id.' Публикация удалена');
        categoryAdmincp::update_count($art['cid'],'-');
        $msg.= self::del_msg('Данные категории обновлены');
        $msg.= self::del_msg('Успешно удалено');
        return $msg;
    }

    public function article_data($bodyArray,$aid=0,$haspic=0,$_chapter=0){
        if($_POST['_data_id']){
            $_data_id = stripslashes($_POST['_data_id']);
            $_data_id = json_decode($_data_id,true);
            $_count   = count($_data_id);
        }
        if(isset($_POST['ischapter']) || is_array($_POST['data_id'])){
            $adidArray    = (array)$_POST['data_id'];
            $chaptertitle = $_POST['chaptertitle'];
            $chapter      = $_POST['chapternum'];
            empty($chapter) && $chapter = count($bodyArray);
            foreach ($bodyArray as $key => $body) {
                if(is_array($body)){
                    $body['body'] && $this->body($body['body'],$body['subtitle'],$aid,null,$haspic);
                }else{
                    $adid     = (int)$adidArray[$key];
                    $subtitle = iSecurity::escapeStr($chaptertitle[$key]);
                    $this->body($body,$subtitle,$aid,$adid,$haspic);
                }
            }
            if(is_array($_data_id)){
                $diff = array_diff_values($adidArray,$_data_id);
                if($diff['-'])foreach ($diff['-'] as $_i => $_id) {
                    $_id = (int)$_id;
                    article::del_data($_id,'id');
                }
            }
            article::update(compact('chapter'),array('id'=>$aid));
        }else{
            $adid     = (int)$_POST['data_id'];
            $subtitle = iSecurity::escapeStr($_POST['subtitle']);
            $body     = implode('#--iCMS.PageBreak--#',$bodyArray);
            $adid     = $this->body($body,$subtitle,$aid,$adid,$haspic);

            if(is_array($_data_id)){
                $dkey = array_search($adid, $_data_id);
                if($dkey!==false && $_chapter){
                    unset($_data_id[$dkey]);
                   
                    if($_data_id)foreach ($_data_id as $_id) {
                        $_id = (int)$_id;
                        $_id && article::del_data($_id,'id');
                    }
                }
            }

        }
        iPHP::callback(array("spider","callback"),array($this,$aid,'data'));
    }
    public function body($body,$subtitle,$aid=0,$id=0,&$haspic=0){
        // $body = preg_replace(array('/<script.+?<\/script>/is','/<form.+?<\/form>/is'),'',$body);
        isset($_POST['dellink']) && $body = preg_replace("/<a[^>].*?>(.*?)<\/a>/si", "\\1",$body);

        if($_POST['markdown']){
        }else{
            self::$config['autoformat'] && $body = addslashes(autoformat($body));
        }
        if(self::$config['emoji']=='unicode'){
            $body = preg_replace('/\\\ud([8-9a-f][0-9a-z]{2})/i','\\\\\ud$1',json_encode($body));
            $body = json_decode($body);
            $body = preg_replace('/\\\ud([8-9a-f][0-9a-z]{2})/i','\\\\\ud$1',$body);
        }else if(self::$config['emoji']=='clean'){
            $body = preg_replace('/\\\ud([8-9a-f][0-9a-z]{2})/i','',json_encode($body));
            $body = json_decode($body);
        }

        $fields = article::data_fields($id);
        $data   = compact ($fields);

        if($id){
            article::data_update($data,compact('id'));
        }else{
            $id = article::data_insert($data);
        }

        isset($_POST['iswatermark']) && files::$watermark_enable = false;

        if($_POST['remote']){
            $body = filesAdmincp::remotepic($body,true);
            $body = filesAdmincp::remotepic($body,true);
            $body = filesAdmincp::remotepic($body,true);
            if($body && $id){
                article::data_update(array('body'=>$body),compact('id'));
            }
        }
        
        if($_POST['autopic']){
            $autopic = filesAdmincp::remotepic($body,'autopic');
            if($autopic){
                $sizeMap = array('b','m','s');
                foreach ($sizeMap as $key => $size) {
                    $autopic[$key] && $this->set_pic($autopic[$key],$aid,$size);
                }
            }
        }
        files::set_file_iid($body,$aid,self::$appid);
        return $id;
    }
    public static function autodesc($body){
        if(iCMS::$config['article']['autodesc'] && iCMS::$config['article']['descLen']) {
            $bodyText = is_array($body)?implode("\n",$body):$body;
            $bodyText = str_replace('#--iCMS.PageBreak--#',"\n",$bodyText);
            $bodyText = str_replace('</p><p>', "</p>\n<p>", $bodyText);

            $textArray = explode("\n", $bodyText);
            $pageNum   = 0;
            $resource  = array();
            foreach ($textArray as $key => $p) {
                $text = preg_replace(array('/<[\/\!]*?[^<>]*?>/is','/\s{2,}/is'),'',$p);
                // $pageLen   = strlen($resource);
                // $output    = implode('',array_slice($textArray,$key));
                // $outputLen = strlen($output);
                $output    = implode('',$resource);
                $outputLen = strlen($output);
                if($outputLen>iCMS::$config['article']['descLen']){
                    // $pageNum++;
                    // $resource[$pageNum] = $p;
                    break;
                }else{
                    $resource[]= $text;
                }
            }
            $description = implode("\n", $resource);
            $description = csubstr($description,iCMS::$config['article']['descLen']);
            $description = addslashes(trim($description));
            $description = str_replace('#--iCMS.PageBreak--#','',$description);
            $description = preg_replace('/^[\s|\n|\t]{2,}/m','',$description);
            unset($bodyText);
            return $description;
        }
    }
    public function set_pic($picurl,$aid,$key='b'){
        if(is_array($picurl)){
            $sizeMap = array('b','m','s');
            foreach ($sizeMap as $key => $size) {
                $picurl[$key] && $this->set_pic($picurl[$key],$aid,$size);
            }
            return;
        }
        if (stripos($picurl,iCMS_FS_HOST) !== false){
            $field = 'pic';
            if($key=='b'){
                $haspic = 1;
            }else{
                $field = $key.'pic';
            }
            $check  = article::value($field,$aid);
            if($check) return;

            $pic = iFS::fp($picurl,'-http');
            list($width, $height, $type, $attr) = @getimagesize(iFS::fp($pic,'+iPATH'));

            $picdata  = article::value('picdata',$aid);
            $picArray = filesApp::get_picdata($picdata);
            $picdata  = filesAdmincp::picdata($picArray,array($key=>array('w'=>$width,'h'=>$height)));


            $data = compact('haspic','picdata');
            $data[$field] = $pic;
            article::update($data,array('id'=>$aid));
            files::set_map(self::$appid,$aid,$pic,'path');
        }
    }

    public function check_pic($body,$aid=0){
        // global $status;
        // if($status!='1'){
        //     return;
        // }
        $p_array = files::preg_img($body);

        foreach((array)$p_array as $key =>$url) {
            $url = trim($url);
            $filpath = iFS::fp($url, 'http2iPATH');
            // var_dump($filpath);
            list($owidth, $oheight, $otype) = @getimagesize($filpath);
            if(empty($otype)){
                // var_dump($filpath,$otype);
                if($aid){
                    iDB::update('article',array('status'=>'2'),array('id'=>$aid));
                    echo $aid." status:2\n";
                }
                return true;
            }
        }
        return false;
    }
    public static function _count($where=null){
        $sql = iSQL::where($where,true);
        return iDB::value("SELECT count(*) FROM `#iCMS@__article` WHERE 1=1 {$sql}");
    }
    function fopen_url($url,$mo=false) {
        $uri=parse_url($url);
        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, $url);
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT,2);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($curl_handle, CURLOPT_FAILONERROR,1);
        curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER,0);
        curl_setopt($curl_handle, CURLOPT_SSL_VERIFYHOST,0);
        curl_setopt($curl_handle, CURLOPT_REFERER,$uri['scheme'].'://'.$uri['host']);
        if($mo){
            curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; Android 4.2.1; en-us; Nexus 5 Build/JOP40D) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.166 Mobile Safari/535.19');
        }else{
            curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/532.0 (KHTML, like Gecko) Chrome/3.0.195.38 Safari/532.0');
        }
        $file_content = curl_exec($curl_handle);
        $info  = curl_getinfo($curl_handle);
        curl_close($curl_handle);
        if($info['http_code']=="200" && strpos($file_content, 'Successful purge')!==false){
            return $file_content;
        }
        return false;
    }
}
