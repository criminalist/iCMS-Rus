<?php

defined('iPHP') OR exit('What are you doing?');

class videoAdmincp{
    public $callback = array();
    public $chapter  = false;
    public static $config   = null;
    public static $appid = null;

    public function __construct() {
        self::$appid     = iCMS_APP_VIDEO;
        $this->id        = (int)$_GET['id'];
        $this->dataid    = (int)$_GET['dataid'];
        $this->_postype  = '1';
        $this->_status   = '1';
        self::$config    = iCMS::$config['video'];
        tag::$appid      = self::$appid;
        category::$appid = self::$appid;
    }

    public function do_config(){
        configAdmincp::app(self::$appid);
    }
    public function do_save_config(){
        configAdmincp::save(self::$appid);
    }
    /**
     * [添加视频]
     */
    public function do_add(){
        $_GET['cid'] && category::check_priv($_GET['cid'],'ca','page');//添加权限
        $rs = $resource = array();
        if($this->id){
            list($rs,$dRs) = video::data($this->id,$this->dataid);
            category::check_priv($rs['cid'],'ce','page');//编辑权限
            $resource = video::resource($this->id);
            iPHP::callback(array("apps_meta","get"),array(self::$appid,$this->id));
        }

        $cid         = empty($rs['cid'])?(int)$_GET['cid']:$rs['cid'];
        $cata_option = category::priv('ca')->select($cid);
        $cid && $meta_setting = categoryAdmincp::do_config_meta(true,$cid);

        $rs['pubdate'] = get_date($rs['pubdate'],'Y-m-d H:i:s');
        $rs['scores']  = json_decode($rs['scores'],true);

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
        include admincp::view("video.add");
    }
    public function do_update(){
    	$data = iSQL::update_args($_GET['_args']);
        if($data){
            if(isset($data['pid'])){
                iMap::init('prop',self::$appid,'pid');
                $_pid = video::value('pid',$this->id);
                iMap::diff($data['pid'],$_pid,$this->id);
            }
            video::update($data,array('id'=>$this->id));
        }
    	iUI::success('操作成功!','js:1');
    }
    public function do_updateorder(){
        foreach((array)$_POST['sortnum'] as $sortnum=>$id){
            video::update(compact('sortnum'),compact('id'));
        }
    }
    public function do_batch(){
    	list($idArray,$ids,$batch) = iUI::get_batch_args("请选择要操作的视频");
    	switch($batch){
    		case 'order':
		        foreach((array)$_POST['sortnum'] AS $id=>$sortnum) {
                    video::update(compact('sortnum'),compact('id'));
		        }
		        iUI::success('排序已更新!','js:1');
    		break;
            case 'meta':
                foreach($idArray AS $id) {
                    iPHP::callback(array("apps_meta","save"),array(self::$appid,$id));
                }
                iUI::success('添加完成!','js:1');
            break;
            case 'baiduping':
                foreach($idArray AS $id) {
                    $msg.= $this->do_baiduping($id,false);
                }
                iUI::success($msg,'js:1');
            break;
    		case 'move':
		        $_POST['cid'] OR iUI::alert("请选择目标栏目!");
                iMap::init('category',self::$appid,'cid');
                $cid = (int)$_POST['cid'];
                category::check_priv($cid,'ca','alert');
		        foreach($idArray AS $id) {
                    $_cid = video::value('cid',$id);
                    video::update(compact('cid'),compact('id'));
		            if($_cid!=$cid) {
                        iMap::diff($cid,$_cid,$id);
                        categoryAdmincp::update_count($_cid,'-');
                        categoryAdmincp::update_count($cid);
		            }
		        }
		        iUI::success('成功移动到目标栏目!','js:1');
            break;
            case 'scid':
                //$_POST['scid'] OR iUI::alert("请选择目标栏目!");
                iMap::init('category',self::$appid,'cid');
                $scid = implode(',', (array)$_POST['scid']);
                foreach($idArray AS $id) {
                    $_scid = video::value('scid',$id);
                    video::update(compact('scid'),compact('id'));
                    iMap::diff($scid,$_scid,$id);
                }
                iUI::success('视频副栏目设置完成!','js:1');
            break;
            case 'prop':
                iMap::init('prop',self::$appid,'pid');
                $pid = implode(',', (array)$_POST['pid']);
                foreach($idArray AS $id) {
                    $_pid = video::value('pid',$id);
                    video::update(compact('pid'),compact('id'));
                    iMap::diff($pid,$_pid,$id);
                }
                iUI::success('视频属性设置完成!','js:1');
    		break;
    		case 'weight':
                $data = array('weight'=>$_POST['mweight']);
    		break;
    		case 'keyword':
    			if($_POST['pattern']=='replace') {
                    $data = array('keywords'=>iSecurity::escapeStr($_POST['mkeyword']));
    			}elseif($_POST['pattern']=='addto') {
		        	foreach($_POST['id'] AS $id){
                        $keywords = video::value('keywords',$id);
                        $keywords = $keywords?$keywords.','.iSecurity::escapeStr($_POST['mkeyword']):iSecurity::escapeStr($_POST['mkeyword']);
                        video::update(compact('keywords'),compact('id'));
		        	}
		        	iUI::success('视频关键字更改完成!','js:1');
    			}
    		break;
    		case 'tag':
		     	foreach($_POST['id'] AS $id){
                    $art  = video::row($id,'tags,cid');
                    $mtag = iSecurity::escapeStr($_POST['mtag']);
			        if($_POST['pattern']=='replace') {
			        }elseif($_POST['pattern']=='addto') {
			        	$art['tags'] && $mtag = $art['tags'].','.$mtag;
			        }
			        $tags = tag::diff($mtag,$art['tags'],members::$userid,$id,$art['cid']);
                    $tags = addslashes($tags);
                    video::update(compact('tags'),compact('id'));
		    	}
		    	iUI::success('视频标签更改完成!','js:1');
    		break;
    		case 'thumb':
		        foreach($idArray AS $id) {
		            $body	= video::body($id);
                    $picurl = filesAdmincp::remotepic($body,'autopic',$id);
                    $this->set_pic($picurl,$id);
		        }
		        iUI::success('成功提取缩略图!','js:1');
    		break;
    		case 'dels':
    			iUI::$break	= false;
    			iUI::flush_start();
    			$_count	= count($_POST['id']);
				foreach($idArray AS $i=>$id) {
			     	$msg = $this->del($id);
			        $msg.= $this->del_msg('视频删除完成!');
					$updateMsg	= $i?true:false;
					$timeout	= ($i++)==$_count?'3':false;
					iUI::dialog($msg,'js:parent.$("#id'.$id.'").remove();',$timeout,0,$updateMsg);
		        	iUI::flush();
	   			}
	   			iUI::$break	= true;
				iUI::success('视频全部删除完成!','js:1',3,0,true);
    		break;
    		default:
				$data = iSQL::update_args($batch);
    	}
        $data && video::batch($data,$ids);
		iUI::success('操作成功!','js:1');
    }
    /**
     * [百度推送 ]
     * @param  [type]  $id     [description]
     * @param  boolean $dialog [description]
     * @return [type]          [description]
     */
    public function do_baiduping($id = null,$dialog=true){
        $id===null && $id=$this->id;
        $id OR iUI::alert('请选择要推送的视频!');
        $rs   = video::row($id);
        $C    = category::get($rs['cid']);
        $iurl = (array)iURL::get('video',array($rs,$C));
        $urls = array();
        $urls[] = $iurl['href'];
        if($iurl['mobile']['url']){
            $urls[] = $iurl['mobile']['url'];
        }
        $res = plugin_baidu::ping($urls);
        // if($iurl['mip']['url']){
        //     $mip = plugin_baidu::ping($iurl['mip']['url'],'mip');
        // }
        if($res===true){
            $msg = '推送完成';
            $dialog && iUI::success($msg,'js:1');
        }else{
            $msg = '推送失败!['.$res->message.']';
            $dialog && iUI::alert($msg,'js:1');
        }
        if(!$dialog) return $msg.'<br />';
    }
    /**
     * [JSON数据]
     * @return [type] [description]
     */
    public function do_getjson(){
        $id = (int)$_GET['id'];
        $rs = video::row($id);
        iUI::json($rs);
    }
    /**
     * [简易编辑]
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

		$art = video::row($id,'tags,cid');
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
        video::update($data ,compact('id'));
		iUI::json(array('code'=>1));
	}
    /**
     * [查找正文图片]
     * @return [type] [description]
     */
    public function do_findpic(){
        $content = video::body($this->id);
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
                        $rs[] = array(
                            'id'       => 'path@'.$filepath,
                            'path'     => rtrim($pf['dirname'],'/').'/',
                            'filename' => $pf['filename'],
                            'size'     => @filesize($rpath),
                            'time'     => @filectime($rpath),
                            'ext'      => $pf['extension']
                        );
                    }
                }
                // echo "<hr />";
            }
            $_count = count($rs);
        }
        include admincp::view("files.manage","files");
    }
    /**
     * [正文预览]
     * @return [type] [description]
     */
    public function do_preview(){
		echo video::body($this->id);
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
            'inbox'   =>'0',//草稿
            'normal'  =>'1',//正常
            'trash'   =>'2',//回收站
            'examine' =>'3',//待审核
            'off'     =>'4',//未通过
        );
        $map_where = array();
        //status:[0:草稿][1:正常][2:回收][3:待审核][4:不合格]
        //postype: [0:用户][1:管理员]
        $stype && $this->_status = $stype_map[$stype];
        if(isset($_GET['pt']) && $_GET['pt']!=''){
            $this->_postype = (int)$_GET['pt'];
        }
        if(isset($_GET['status'])){
            $this->_status = (int)$_GET['status'];
        }
        $sql = "WHERE `status`='{$this->_status}'";
        $this->_postype==='all' OR $sql.= " AND `postype`='{$this->_postype}'";

        if(members::check_priv("video.VIEW")){
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

        $cp_cids = category::check_priv('CIDS','cs');//取得所有有权限的栏目ID

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

        if($_GET['keywords']) {
            $kws = $_GET['keywords'];
            switch ($_GET['st']) {
                case "title": $sql.=" AND `title` REGEXP '{$kws}'";break;
                case "tag":   $sql.=" AND `tags` REGEXP '{$kws}'";break;
                case "source":$sql.=" AND `source` REGEXP '{$kws}'";break;
                case "weight":$sql.=" AND `weight`='{$kws}'";break;
                case "id":
                $kws = str_replace(',', "','", $kws);
                $sql.=" AND `id` IN ('{$kws}')";
                break;
                case "tkd":   $sql.=" AND CONCAT(title,keywords,description) REGEXP '{$kws}'";break;
            }
        }

        $_GET['title']     && $sql.=" AND `title` like '%{$_GET['title']}%'";
        $_GET['tag']       && $sql.=" AND `tags` REGEXP '[[:<:]]".preg_quote(rawurldecode($_GET['tag']),'/')."[[:>:]]'";
        $_GET['starttime'] && $sql.=" AND `pubdate`>='".str2time($_GET['starttime'].(strpos($_GET['starttime'],' ')!==false?'':" 00:00:00"))."'";
        $_GET['endtime']   && $sql.=" AND `pubdate`<='".str2time($_GET['endtime'].(strpos($_GET['endtime'],' ')!==false?'':" 23:59:59"))."'";
        $_GET['post_starttime'] && $sql.=" AND `postime`>='".str2time($_GET['post_starttime'].(strpos($_GET['post_starttime'],' ')!==false?'':" 00:00:00"))."'";
        $_GET['post_endtime']   && $sql.=" AND `postime`<='".str2time($_GET['post_endtime'].(strpos($_GET['post_endtime'],' ')!==false?'':" 23:59:59"))."'";
        isset($_GET['pic'])&& $sql.=" AND `haspic` ='".($_GET['pic']?1:0)."'";

        isset($_GET['userid']) && $uri_array['userid']  = (int)$_GET['userid'];
        isset($_GET['keyword'])&& $uri_array['keyword'] = $_GET['keyword'];
        isset($_GET['tag'])    && $uri_array['tag']	    = $_GET['tag'];
        isset($_GET['pt'])     && $uri_array['pt']      = $_GET['pt'];
        isset($_GET['cid'])    && $uri_array['cid']     = $_GET['cid'];
		$uri_array	&& $uri = http_build_query($uri_array);

        list($orderby,$orderby_option) = get_orderby(array(
            'id'         =>"ID",
            'hits'       =>"点击",
            'hits_week'  =>"周点击",
            'hits_month' =>"月点击",
            'good'       =>"顶",
            'postime'    =>"时间",
            'pubdate'    =>"Время публикации",
            'comments'   =>"评论数",
        ));

        $maxperpage = $_GET['perpage']>0?(int)$_GET['perpage']:20;

        if($map_where){
            $map_sql = iSQL::select_map($map_where);
            $sql     = ",({$map_sql}) map {$sql} AND `id` = map.`iid`";
        }

        $total = iPagination::totalCache(video::count_sql($sql),"G");
        iUI::pagenav($total,$maxperpage,"篇视频");

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
                SELECT `id` FROM `#iCMS@__video` {$sql}
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
                    // SELECT `id` AS aid FROM `#iCMS@__video` {$sql}
                    // ORDER BY {$orderby} {$limit}
                // ) AS art WHERE `id` = art.aid ";
            // }
            $limit = '';
        }
        $rs = iDB::all("SELECT * FROM `#iCMS@__video` {$sql} ORDER BY {$orderby} {$limit}");
        $_count = count($rs);
        $propArray = propAdmincp::get("pid",null,'array');
        include admincp::view("video.manage");
    }
    public function do_save(){
        $video_id    = (int)$_POST['video_id'];
        $cid         = (int)$_POST['cid'];
        category::check_priv($cid,($video_id?'ce':'ca'),'alert');


        $userid      = (int)$_POST['userid'];
        $ucid        = (int)$_POST['ucid'];
        $scid        = implode(',', (array)$_POST['scid']);
        $pid         = implode(',', (array)$_POST['pid']);
        $status      = (int)$_POST['status'];
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
        $bpic        = iSecurity::escapeStr($_POST['bpic']);
        $mpic        = iSecurity::escapeStr($_POST['mpic']);
        $spic        = iSecurity::escapeStr($_POST['spic']);
        $editor      = iSecurity::escapeStr($_POST['editor']);
        $description = iSecurity::escapeStr($_POST['description']);
        $keywords    = iSecurity::escapeStr($_POST['keywords']);
        $tags        = str_replace(',', ',',iSecurity::escapeStr($_POST['tags']));
        $clink       = iSecurity::escapeStr($_POST['clink']);
        $url         = iSecurity::escapeStr($_POST['url']);
        $tpl         = iSecurity::escapeStr($_POST['tpl']);
        $creative    = (int)$_POST['creative'];

        $alias    = str_replace(array(',',' / ','/'), ',',iSecurity::escapeStr($_POST['alias']));
        $enname   = iSecurity::escapeStr($_POST['enname']);
        $star     = iSecurity::escapeStr($_POST['star']);
        $remark   = iSecurity::escapeStr($_POST['remark']);
        $year     = iSecurity::escapeStr($_POST['year']);
        $version  = iSecurity::escapeStr($_POST['version']);
        $language = iSecurity::escapeStr($_POST['language']);
        $area     = iSecurity::escapeStr($_POST['area']);
        $ser      = iSecurity::escapeStr($_POST['ser']);
        $release  = iSecurity::escapeStr($_POST['release']);
        $time     = iSecurity::escapeStr($_POST['time']);
        $total    = iSecurity::escapeStr($_POST['total']);
        $company  = iSecurity::escapeStr($_POST['company']);
        $tv       = iSecurity::escapeStr($_POST['tv']);
        $score    = (int)$_POST['score'];
        $scorenum = (int)$_POST['scorenum'];

        $actor     = str_replace(array(',',' / ','/'), ',',iSecurity::escapeStr($_POST['actor']));
        $_actor    = iSecurity::escapeStr($_POST['_actor']);
        $director  = str_replace(array(',',' / ','/'), ',',iSecurity::escapeStr($_POST['director']));
        $_director = iSecurity::escapeStr($_POST['_director']);
        $attrs     = str_replace(array(',',' / ','/'), ',',iSecurity::escapeStr($_POST['attrs']));
        $_attrs    = iSecurity::escapeStr($_POST['_attrs']);

        $genre  = implode(',', (array)$_POST['genre']);
        $_genre = iSecurity::escapeStr($_POST['_genre']);

        $cycle  = addslashes(json_encode((array)$_POST['cycle']));
        $scores  = addslashes(json_encode((array)$_POST['scores']));

        if (empty($title)) {
            return iUI::alert('Заголовок не может быть пустым!');
        }
        if (empty($cid)) {
            return iUI::alert('Выберите категорию для привязки');
        }

        $pubdate   = str2time($_POST['pubdate']);
        $postype   = $_POST['postype']?$_POST['postype']:0;
        $userid OR $userid = members::$userid;
        $tags && $tags = preg_replace('/<[\/\!]*?[^<>]*?>/is','',$tags);

        if(self::$config['filter'] && is_array(self::$config['filter'])) {
            foreach (self::$config['filter'] as $fkey => $fvalue) {
                list($field,$text) = explode(':', $fvalue);
                if($fwd = iPHP::callback(array("filterApp","run"),array(&${$field}),false)){
                    return iUI::alert($text.'中包含 ['.$fwd.'] 被系统屏蔽的字符,请重新填写。');
                }
            }
        }

        if(self::$config['repeatitle'] && video::check($title,$video_id,'title')) {
            return iUI::alert('该标题的视频已经存在!请检查是否重复');
        }

        $category = category::get($cid);
        if(strstr($category->rule['video'],'{LINK}')!==false && empty($clink)){
            $clink = iTranslit::get($title,self::$config['clink']);
        }

        if($clink && video::check($clink,$video_id,'clink')){
            return iUI::alert('该视频自定义链接已经存在!请检查是否重复');
        }

        if(empty($description)) {
            $description = $this->autodesc($body);
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

        $fields  = video::fields($video_id);

        if(empty($video_id)) {
            $postime = $pubdate;
            $mobile  = 0;

            $video_id  = video::insert(compact($fields));

            iPHP::callback(array("spider","callback"),array($this,$video_id,'primary'));

            $tags    && tag::field('tags')->add($tags,members::$userid,$video_id,$cid);
            $actor   && tag::field('actor')->add($actor,members::$userid,$video_id,$cid);
            $director&& tag::field('director')->add($director,members::$userid,$video_id,$cid);
            $attrs   && tag::field('attrs')->add($attrs,members::$userid,$video_id,$cid);
            $genre   && tag::field('genre')->add($genre,members::$userid,$video_id,$cid);

            // $genre && iMap::init('prop',self::$appid,'genre')->add($genre,$video_id);

            iMap::init('prop',self::$appid,'pid');
            $pid && iMap::add($pid,$video_id);

            iMap::init('category',self::$appid,'cid');
            iMap::add($cid,$video_id);
            $scid && iMap::add($scid,$video_id);

            $url OR $this->video_data($video_id,$haspic);

            categoryAdmincp::update_count($cid);
            iPHP::callback(array("apps_meta","save"),array(self::$appid,$video_id));
            iPHP::callback(array("formerApp","save"),array(self::$appid,$video_id));

            $video_url = iURL::get('video',array(array(
                'id'      =>$video_id,
                'url'     =>$url,
                'cid'     =>$cid,
                'clink'   =>$clink,
                'pubdate' =>$pubdate
            ),(array)$category))->href;

            if($status && iCMS::$config['api']['baidu']['sitemap']['sync']){
                $msg = $this->do_baiduping($video_id,false);
            }
            if($this->callback['return']){
                return $this->callback['return'];
            }
            if($this->callback['save:return']){
                $this->callback['indexid'] = $video_id;
                return $this->callback['save:return'];
            }

            if($_GET['callback']=='json'){
                echo json_encode(array(
                    "code"    => '1001',
                    'indexid' => $video_id
                ));
                return;
            }
            if(isset($_GET['keyCode'])){
                iUI::success('Видео успешно сохранено','url:'.APP_URI."&do=add&id=".$video_id);
            }

            $moreBtn = array(
                    array("text" =>"查看该视频","target"=>'_blank',"url"=>$video_url,"close"=>false),
                    array("text" =>"编辑该视频","url"=>APP_URI."&do=add&id=".$video_id),
                    array("text" =>"继续添加视频","url"=>APP_URI."&do=add&cid=".$cid),
                    array("text" =>"返回视频列表","url"=>$REFERER_URL),
                    array("text" =>"查看网站首页","url"=>iCMS_URL,"target"=>'_blank')
            );
            iUI::$dialog['modal'] = true;
            iUI::dialog('success:#:check:#:Видео успешно добавлено!<br /> Вернуться к списку видео через 10 секунд'.$msg,'url:'.$REFERER_URL,10,$moreBtn);
        }else{

            ($tags||$_tags)        && tag::field('tags')->diff($tags,$_tags,members::$userid,$video_id,$cid);
            ($actor||$_actor)      && tag::field('actor')->diff($actor,$_actor,members::$userid,$video_id,$cid);
            ($director||$_director)&& tag::field('director')->diff($director,$_director,members::$userid,$video_id,$cid);
            ($attrs||$_attrs)      && tag::field('attrs')->diff($attrs,$_attrs,members::$userid,$video_id,$cid);
            ($genre||$_genre)      && tag::field('genre')->diff($genre,$_attrs,members::$userid,$video_id,$cid);

            video::update(compact($fields),array('id'=>$video_id));

            $return = iPHP::callback(array("spider","callback"),array($this,$video_id,'primary'));

            // iMap::init('prop',self::$appid,'genre');
            // iMap::diff($genre,$_genre,$video_id);

            iMap::init('prop',self::$appid,'pid');
            iMap::diff($pid,$_pid,$video_id);

            iMap::init('category',self::$appid,'cid');
            iMap::diff($cid,$_cid,$video_id);
            $scid && iMap::diff($scid,$_scid,$video_id);

            $url OR $this->video_data($video_id,$haspic);

            if($_cid!=$cid) {
                categoryAdmincp::update_count($_cid,'-');
                categoryAdmincp::update_count($cid);
            }
            iPHP::callback(array("apps_meta","save"),array(self::$appid,$video_id));
            iPHP::callback(array("formerApp","save"),array(self::$appid,$video_id));

            if($this->callback['save:return']){
                $this->callback['indexid'] = $video_id;
                return $this->callback['save:return'];
            }
            if(isset($_GET['keyCode'])){
                iUI::success('视频保存成功');
            }
            iUI::success('视频编辑完成!<br />3秒后返回视频列表','url:'.$REFERER_URL);
        }
    }
    public function do_del($id = null,$dialog=true){
        $id===null && $id=$this->id;
        $msg = $this->del($id);
        $msg.= $this->del_msg('视频删除完成!');
        if($dialog){
            $msg.= $this->del_msg('10秒后返回视频列表!');
            iUI::$dialog['modal'] = true;
            iUI::dialog($msg,'js:1');
        }
    }
    public function do_purge(){
        iUI::success('请自行编写清理代码');
    }

    public static function del_msg($str){
        return iUI::msg('success:#:check:#:'.$str.'<hr />',true);
    }
    public function del_pic($pic){
        //$thumbfilepath    = gethumb($pic,'','',false,true,true);
        iFS::del(iFS::fp($pic,'+iPATH'));
        $msg    = $this->del_msg($pic.'删除');
//      if($thumbfilepath)foreach($thumbfilepath as $wh=>$fp) {
//              iFS::del(iFS::fp($fp,'+iPATH'));
//              $msg.= $this->del_msg('缩略图 '.$wh.' 文件删除');
//      }
        $filename   = iFS::info($pic)->filename;
        video::del_filedata($filename,'filename');
        $msg.= $this->del_msg($pic.'数据删除');
        return $msg;
    }
    public static function del($id,$uid='0',$postype='1') {
        $id = (int)$id;
        $id OR iUI::alert("请选择要删除的视频");
        $uid && $sql="and `userid`='$uid' and `postype`='$postype'";
        $art = video::row($id,'cid,pic,tags',$sql);
        category::check_priv($art['cid'],'cd','alert');

        $fids   = files::index_fileid($id,self::$appid);
        $pieces = files::delete_file($fids);
        files::delete_fdb($fids,$id,self::$appid);
        $msg.= self::del_msg(implode('<br />', $pieces).' 文件删除');
        $msg.= self::del_msg('Удаление связанных файлов');

        if($art['tags']){
            //只删除关联数据 不删除标签
            tag::$remove = false;
            $msg.= tag::del($art['tags'],'name',$id);
        }

        iMap::del_data($id,self::$appid,'category');
        iMap::del_data($id,self::$appid,'prop');

        commentAdmincp::delete($id,self::$appid);
        $msg.= self::del_msg('Данные комментария удалены');
        video::del($id);
        video::del_data($id);
        video_spiderAdmincp::video_spider_del($id);
        $msg.= self::del_msg('视频数据删除');
        categoryAdmincp::update_count($art['cid'],'-');
        $msg.= self::del_msg('栏目数据更新');
        $msg.= self::del_msg('删除完成');
        return $msg;
    }

    public function video_data($video_id=0,$haspic=0){
        $_POST['resource'] && video_resourceAdmincp::save($_POST['resource'],$video_id);
        $_POST['story'] && video_storyAdmincp::save($_POST['story'],$video_id);

        $photo = $_POST['photo'];
        $body  = $_POST['body'];
        $id   = (int)$_POST['data_id'];
        $body = preg_replace(array('/<script.+?<\/script>/is','/<form.+?<\/form>/is'),'',$body);
        isset($_POST['dellink']) && $body = preg_replace("/<a[^>].*?>(.*?)<\/a>/si", "\\1",$body);
        self::$config['autoformat'] && $body = addslashes(autoformat($body));

        $fields = video::data_fields($id);
        $data   = compact ($fields);

        if($id){
            video::data_update($data,compact('id'));
        }else{
            $id = video::data_insert($data);
        }

        iPHP::callback(array("spider","callback"),array($this,$video_id,'data'));
        isset($_POST['iswatermark']) && files::$watermark_enable = false;

        if(isset($_POST['remote'])){
            $body = filesAdmincp::remotepic($body,true,$video_id);
            $body = filesAdmincp::remotepic($body,true,$video_id);
            $body = filesAdmincp::remotepic($body,true,$video_id);
            if($body && $id){
                video::data_update(array('body'=>$body),compact('id'));
            }
        }

        if(isset($_POST['autopic']) && empty($haspic)){
            if($picurl = filesAdmincp::remotepic($body,'autopic',$video_id)){
                $this->set_pic($picurl,$video_id);
                $haspic = true;
            }
        }
        files::set_file_iid($body,$video_id,self::$appid);
    }
    public static function autodesc($body){
        if(self::$config['autodesc'] && self::$config['descLen']) {
            is_array($body) && $bodyText = implode("\n",$body);
            $bodyText = str_replace('#--iCMS.PageBreak--#',"\n",$body);
            $bodyText = str_replace('</p><p>', "</p>\n<p>", $bodyText);

            $textArray = explode("\n", $bodyText);
            $pageNum   = 0;
            $resource  = array();
            foreach ($textArray as $key => $p) {
                $text = preg_replace(array('/<[\/\!]*?[^<>]*?>/is','/\s*/is'),'',$p);
                // $pageLen   = strlen($resource);
                // $output    = implode('',array_slice($textArray,$key));
                // $outputLen = strlen($output);
                $output    = implode('',$resource);
                $outputLen = strlen($output);
                if($outputLen>self::$config['descLen']){
                    // $pageNum++;
                    // $resource[$pageNum] = $p;
                    break;
                }else{
                    $resource[]= $text;
                }
            }
            $description = implode("\n", $resource);
            $description = csubstr($description,self::$config['descLen']);
            $description = addslashes(trim($description));
            $description = str_replace('#--iCMS.PageBreak--#','',$description);
            $description = preg_replace('/^[\s|\n|\t]{2,}/m','',$description);
            unset($bodyText);
            return $description;
        }
    }
    public function set_pic($picurl,$video_id,$key='b'){
        if (stripos($picurl,iCMS_FS_HOST) !== false){
            $pic = iFS::fp($picurl,'-http');
            list($width, $height, $type, $attr) = @getimagesize(iFS::fp($pic,'+iPATH'));

            $picdata  = video::value('picdata',$video_id);
            $picArray = filesApp::get_picdata($picdata);
            $picdata  = filesAdmincp::picdata($picArray,array($key=>array('w'=>$width,'h'=>$height)));

            $field = 'pic';
            if($key=='b'){
                $haspic = 1;
            }else{
                $field = $key.'pic';
            }

            video::update(compact('haspic',$field,'picdata'),array('id'=>$video_id));
            files::set_map(self::$appid,$video_id,$pic,'path');
        }
    }

    public function check_pic($body,$video_id=0){
        // global $status;
        // if($status!='1'){
        //     return;
        // }
        $body = stripcslashes($body);
        $p_array = files::preg_img($body);

        foreach((array)$p_array as $key =>$url) {
            $url = trim($url);
            $filpath = iFS::fp($url, 'http2iPATH');
            // var_dump($filpath);
            list($owidth, $oheight, $otype) = @getimagesize($filpath);
            if(empty($otype)){
                // var_dump($filpath,$otype);
                if($video_id){
                    iDB::update('video',array('status'=>'2'),array('id'=>$video_id));
                    echo $video_id." status:2\n";
                }
                return true;
            }
        }
        return false;
    }
    public static function _count($where=null){
        $sql = iSQL::where($where,true);
        return iDB::value("SELECT count(*) FROM `#iCMS@__video` WHERE 1=1 {$sql}");
    }
}
