<?php

defined('iPHP') OR exit('What are you doing?');

class topicAdmincp{
    public $callback = array();
    public $chapter  = false;
    public static $config   = null;
    public static $appid = null;

    public function __construct() {
        self::$appid     = iCMS_APP_TOPIC;
        $this->id        = (int)$_GET['id'];
        $this->dataid    = (int)$_GET['dataid'];
        $this->_postype  = '1';
        $this->_status   = '1';
        self::$config    = iCMS::$config['topic'];
    }

    public function do_config(){
        configAdmincp::app(self::$appid);
    }
    public function do_save_config(){
        configAdmincp::save(self::$appid);
    }
    /**
     * [添加专题]
     */
    public function do_add(){
        $_GET['cid'] && category::check_priv($_GET['cid'],'ca','page');//添加权限
        $rs      = array();
        if($this->id){
            list($rs,$dataRs) = topic::data($this->id,$this->dataid);
            category::check_priv($rs['cid'],'ce','page');//编辑权限
            iPHP::callback(array("apps_meta","get"),array(self::$appid,$this->id));
        }

        $cid = empty($rs['cid'])?(int)$_GET['cid']:$rs['cid'];

        $rs['pubdate'] = get_date($rs['pubdate'],'Y-m-d H:i:s');
        if(empty($this->id)){
            $rs['status']  = "1";
            $rs['postype'] = "1";
            $rs['editor']  = members::$nickname;
            $rs['userid']  = members::$userid;
		}
        iPHP::callback(array("formerApp","add"),array(self::$appid,$rs,true));
        include admincp::view("topic.add");
    }
    public function do_update(){
    	$data = iSQL::update_args($_GET['_args']);
        if($data){
            if(isset($data['pid'])){
                iMap::init('prop',self::$appid,'pid');
                $_pid = topic::value('pid',$this->id);
                iMap::diff($data['pid'],$_pid,$this->id);
            }
            topic::update($data,array('id'=>$this->id));
        }
    	iUI::success('操作成功!','js:1');
    }
    public function do_updateorder(){
        foreach((array)$_POST['sortnum'] as $sortnum=>$id){
            topic::update(compact('sortnum'),compact('id'));
        }
    }
    public function do_batch(){
    	$_POST['id'] OR iUI::alert("请选择要操作的专题");
    	$ids	= implode(',',(array)$_POST['id']);
    	$batch	= $_POST['batch'];
    	switch($batch){
    		case 'order':
		        foreach((array)$_POST['sortnum'] AS $id=>$sortnum) {
                    topic::update(compact('sortnum'),compact('id'));
		        }
		        iUI::success('排序已更新!','js:1');
            break;
            case 'related':
                foreach((array)$_POST['id'] AS $id) {
                    $related = $_POST['id'];
                    $_related = topic::value('related',$id);
                    if($_related){
                        $_related = explode(',', $_related);
                        $related  = array_merge($_related,$related);
                    }
                    $key  =  array_search ($id,$related);
                    if($key!==false)unset($related[$key]);
                    topic::update(compact('related'),compact('id'));
                }
                iUI::success('关联已更新!','js:1');
            break;
            case 'meta':
                foreach((array)$_POST['id'] AS $id) {
                    iPHP::callback(array("apps_meta","save"),array(self::$appid,$id));
                }
                iUI::success('添加完成!','js:1');
            break;
            case 'baiduping':
                foreach((array)$_POST['id'] AS $id) {
                    $msg.= $this->do_baiduping($id,false);
                }
                iUI::success($msg,'js:1');
            break;
    		case 'move':
		        $_POST['cid'] OR iUI::alert("请选择目标栏目!");
                iMap::init('category',self::$appid,'cid');
                $cid = (int)$_POST['cid'];
                category::check_priv($cid,'ca','alert');
		        foreach((array)$_POST['id'] AS $id) {
                    $_cid = topic::value('cid',$id);
                    topic::update(compact('cid'),compact('id'));
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
                foreach((array)$_POST['id'] AS $id) {
                    $_scid = topic::value('scid',$id);
                    topic::update(compact('scid'),compact('id'));
                    iMap::diff($scid,$_scid,$id);
                }
                iUI::success('专题副栏目设置完成!','js:1');
            break;
            case 'prop':
                iMap::init('prop',self::$appid,'pid');
                $pid = implode(',', (array)$_POST['pid']);
                foreach((array)$_POST['id'] AS $id) {
                    $_pid = topic::value('pid',$id);
                    topic::update(compact('pid'),compact('id'));
                    iMap::diff($pid,$_pid,$id);
                }
                iUI::success('专题属性设置完成!','js:1');
    		break;
    		case 'weight':
                $data = array('weight'=>$_POST['mweight']);
    		break;
    		case 'keyword':
    			if($_POST['pattern']=='replace') {
                    $data = array('keywords'=>iSecurity::escapeStr($_POST['mkeyword']));
    			}elseif($_POST['pattern']=='addto') {
		        	foreach($_POST['id'] AS $id){
                        $keywords = topic::value('keywords',$id);
                        $keywords = $keywords?$keywords.','.iSecurity::escapeStr($_POST['mkeyword']):iSecurity::escapeStr($_POST['mkeyword']);
                        topic::update(compact('keywords'),compact('id'));
		        	}
		        	iUI::success('专题关键字更改完成!','js:1');
    			}
    		break;
    		case 'tag':
		     	foreach($_POST['id'] AS $id){
                    $art  = topic::row($id,'tags,cid');
                    $mtag = iSecurity::escapeStr($_POST['mtag']);
                    $tagArray  = explode(',', $art['tags']);
                    $mtagArray = explode(',', $mtag);
			        if($_POST['pattern']=='replace') {
			        }elseif($_POST['pattern']=='addto') {
                        $pieces = array_merge($tagArray,$mtagArray);
                        $pieces = array_unique($pieces);
                        $mtag   = implode(',', $pieces);
			        }

			        $tags = tag::diff($mtag,$art['tags'],members::$userid,$id,$art['cid']);
                    $tags = addslashes($tags);
                    topic::update(compact('tags'),compact('id'));
		    	}
		    	iUI::success('专题标签更改完成!','js:1');
    		break;
    		case 'dels':
    			iUI::$break	= false;
    			iUI::flush_start();
    			$_count	= count($_POST['id']);
				foreach((array)$_POST['id'] AS $i=>$id) {
			     	$msg = $this->del($id);
			        $msg.= $this->del_msg('专题删除完成!');
					$updateMsg	= $i?true:false;
					$timeout	= ($i++)==$_count?'3':false;
					iUI::dialog($msg,'js:parent.$("#id'.$id.'").remove();',$timeout,0,$updateMsg);
		        	iUI::flush();
	   			}
	   			iUI::$break	= true;
				iUI::success('专题全部删除完成!','js:1',3,0,true);
    		break;
    		default:
				$data = iSQL::update_args($batch);
    	}
        $data && topic::batch($data,$ids);
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
        $id OR iUI::alert('请选择要推送的专题!');
        $rs   = topic::row($id);
        $C    = category::get($rs['cid']);
        $iurl = (array)iURL::get('topic',array($rs,$C));
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
            $msg = '推送失败！['.$res->message.']';
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
        $rs = topic::row($id);
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

		$art = topic::row($id,'tags,cid');
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
        topic::update($data ,compact('id'));
		iUI::json(array('code'=>1));
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

        if(members::check_priv("topic.VIEW")){
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

        isset($_GET['userid']) && $uriArray['userid']  = (int)$_GET['userid'];
        isset($_GET['keyword'])&& $uriArray['keyword'] = $_GET['keyword'];
        isset($_GET['tag'])    && $uriArray['tag']     = $_GET['tag'];
        isset($_GET['cid'])    && $uriArray['cid']     = $_GET['cid'];

        list($orderby,$orderby_option) = get_orderby(array(
            'id'         =>"ID",
            'hits'       =>"点击",
            'hits_week'  =>"周点击",
            'hits_month' =>"月点击",
            'good'       =>"顶",
            'postime'    =>"时间",
            'pubdate'    =>"发布时间",
            'comments'   =>"评论数",
        ));
        $maxperpage = $_GET['perpage']>0?(int)$_GET['perpage']:20;

        if($map_where){
            $map_sql = iSQL::select_map($map_where);
            $sql     = ",({$map_sql}) map {$sql} AND `id` = map.`iid`";
        }

        $total = iPagination::totalCache(topic::count_sql($sql),"G");
        iUI::pagenav($total,$maxperpage,"篇专题");

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
                SELECT `id` FROM `#iCMS@__topic` {$sql}
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
                    // SELECT `id` AS aid FROM `#iCMS@__topic` {$sql}
                    // ORDER BY {$orderby} {$limit}
                // ) AS art WHERE `id` = art.aid ";
            // }
            $limit = '';
        }
        $rs = iDB::all("SELECT * FROM `#iCMS@__topic` {$sql} ORDER BY {$orderby} {$limit}");
        $_count = count($rs);
        $propArray = propAdmincp::get("pid",null,'array');
        include admincp::view("topic.manage");
    }
    public function do_save(){
        $tid         = (int)$_POST['topic_id'];
        $cid         = (int)$_POST['cid'];
        $_cid        = iSecurity::escapeStr($_POST['_cid']);
        category::check_priv($cid,($tid?'ce':'ca'),'alert');


        $tcid        = (int)$_POST['tcid'];
        $_tcid       = iSecurity::escapeStr($_POST['_tcid']);
        $scid        = implode(',', (array)$_POST['scid']);
        $_scid       = iSecurity::escapeStr($_POST['_scid']);

        $pid         = implode(',', (array)$_POST['pid']);
        $_pid        = iSecurity::escapeStr($_POST['_pid']);
        $userid      = (int)$_POST['userid'];
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
        $creative    = (int)$_POST['creative'];
        $editor      = iSecurity::escapeStr($_POST['editor']);
        $description = iSecurity::escapeStr($_POST['description']);
        $keywords    = iSecurity::escapeStr($_POST['keywords']);
        $clink       = iSecurity::escapeStr($_POST['clink']);
        $url         = iSecurity::escapeStr($_POST['url']);
        $tpl         = iSecurity::escapeStr($_POST['tpl']);
        $pic         = iSecurity::escapeStr($_POST['pic']);
        $bpic        = iSecurity::escapeStr($_POST['bpic']);
        $mpic        = iSecurity::escapeStr($_POST['mpic']);
        $spic        = iSecurity::escapeStr($_POST['spic']);

        $title       = iSecurity::escapeStr($_POST['title']);
        $stitle      = iSecurity::escapeStr($_POST['stitle']);
        $source      = iSecurity::escapeStr($_POST['source']);
        $author      = iSecurity::escapeStr($_POST['author']);

        $tags        = str_replace('，', ',',iSecurity::escapeStr($_POST['tags']));
        $_tags       = iSecurity::escapeStr($_POST['_tags']);

        if (empty($title)) {
            return iUI::alert('标题不能为空！');
        }

        $pubdate   = str2time($_POST['pubdate']);
        $postype   = $_POST['postype']?$_POST['postype']:0;
        $userid OR $userid = members::$userid;

        $tags && $tags = preg_replace('/<[\/\!]*?[^<>]*?>/is','',$tags);

        if(self::$config['repeatitle'] && topic::check($title,$tid,'title')) {
            return iUI::alert('该标题的专题已经存在!请检查是否重复');
        }

        $category = category::get($cid);
        if(strstr($category->rule['topic'],'{LINK}')!==false && empty($clink)){
            $clink = iTranslit::get($title,self::$config['clink']);
        }

        if($clink && topic::check($clink,$tid,'clink')){
            return iUI::alert('该专题自定义链接已经存在!请检查是否重复');
        }


        (iFS::checkHttp($pic)  && !isset($_POST['pic_http']))  && $pic  = iFS::http($pic);
        (iFS::checkHttp($bpic) && !isset($_POST['bpic_http'])) && $bpic = iFS::http($bpic);
        (iFS::checkHttp($mpic) && !isset($_POST['mpic_http'])) && $mpic = iFS::http($mpic);
        (iFS::checkHttp($spic) && !isset($_POST['spic_http'])) && $spic = iFS::http($spic);
        $haspic  = empty($pic)?0:1;

        $editor OR  $editor = members::$nickname;
        $picdata = filesAdmincp::picdata($pic,$bpic,$mpic,$spic);

        $fields  = topic::fields($tid);

        if(empty($tid)) {
            $postime = $pubdate;
            $mobile  = 0;

            $tid  = topic::insert(compact($fields));
            iPHP::callback(array("spider","callback"),array($this,$tid,'primary'));

            $tags && tag::add($tags,members::$userid,$tid,$cid);

            iMap::init('prop',self::$appid,'pid');
            $pid && iMap::add($pid,$tid);

            iMap::init('category',self::$appid,'cid');
            iMap::add($cid,$tid);
            $scid && iMap::add($scid,$tid);
            $tcid && iMap::add($tcid,$tid);

            $url OR $this->topic_data($tid);

            categoryAdmincp::update_count($cid);
            $tcid && categoryAdmincp::update_count($tcid);

            iPHP::callback(array("apps_meta","save"),array(self::$appid,$tid));
            iPHP::callback(array("formerApp","save"),array(self::$appid,$tid));

            $topic_url = iURL::get('topic',array(array(
                'id'      =>$tid,
                'url'     =>$url,
                'cid'     =>$cid,
                'clink'   =>$clink,
                'pubdate' =>$pubdate
            ),(array)$category))->href;

            // if($status && iCMS::$config['api']['baidu']['sitemap']['sync']){
            //     $msg = $this->do_baiduping($tid,false);
            // }

            if($this->callback['return']){
                return $this->callback['return'];
            }
            if($_GET['callback']=='json'){
                echo json_encode(array(
                    "code"    => '1001',
                    'indexid' => $tid
                ));
                return;
            }
            if(isset($_GET['keyCode'])){
                iUI::success('专题保存成功','url:'.APP_URI."&do=add&id=".$tid);
            }

            $moreBtn = array(
                    array("text" =>"查看该专题","target"=>'_blank',"url"=>$topic_url,"close"=>false),
                    array("text" =>"编辑该专题","url"=>APP_URI."&do=add&id=".$tid),
                    array("text" =>"继续添加专题","url"=>APP_URI."&do=add&cid=".$cid),
                    array("text" =>"返回专题列表","url"=>APP_URI.'&do=manage'),
                    array("text" =>"查看网站首页","url"=>iCMS_URL,"target"=>'_blank')
            );
            iUI::$dialog['modal'] = true;
            iUI::dialog('success:#:check:#:专题添加完成!<br />10秒后返回专题列表'.$msg,'url:'.APP_URI.'&do=manage',10,$moreBtn);
        }else{

	        ($tags||$_tags) && tag::diff($tags,$_tags,members::$userid,$tid,$cid);

            topic::update(compact($fields),array('id'=>$tid));
            $return = iPHP::callback(array("spider","callback"),array($this,$tid,'primary'));

            iMap::init('prop',self::$appid,'pid');
            iMap::diff($pid,$_pid,$tid);
            iMap::init('category',self::$appid,'cid');
            iMap::diff($cid,$_cid,$tid);
            $scid && iMap::diff($scid,$_scid,$tid);

            $url OR $this->topic_data($tid);

            if($_cid!=$cid) {
                categoryAdmincp::update_count($_cid,'-');
                categoryAdmincp::update_count($cid);
            }
            if($_tcid!=$tcid) {
                categoryAdmincp::update_count($_tcid,'-');
                categoryAdmincp::update_count($tcid);
            }

            iPHP::callback(array("apps_meta","save"),array(self::$appid,$tid));
            iPHP::callback(array("formerApp","save"),array(self::$appid,$tid));

            if($this->callback['return']){
                return $this->callback['return'];
            }
            if(isset($_GET['keyCode'])){
                iUI::success('专题保存成功');
            }
            iUI::success('专题编辑完成!<br />3秒后返回专题列表','url:'.APP_URI.'&do=manage');
        }
    }
    public function do_del($id = null,$dialog=true){
        $id===null && $id=$this->id;
        $msg = $this->del($id);
        $msg.= $this->del_msg('专题删除完成!');
        if($dialog){
            $msg.= $this->del_msg('10秒后返回专题列表!');
            iUI::$dialog['modal'] = true;
            iUI::dialog($msg,'js:1');
        }
    }
    public function do_select(){
        $_app = $_GET['_app'];
        admincp::$view['dir'] = $_app;
        admincp::$view['navbar'] = false;
        admincp::$view['foot:begin'] = "topic";

        list($path,$obj)= apps::get_path($_app,'admincp',true);
        if(is_file($path) && method_exists($obj,'do_manage')){
            $admincp = new $obj;
            $admincp->do_manage();
        }
        $app  = apps::get_app($_app);
        if($app['apptype']=="2"){
            $admincp = new contentAdmincp($app,'content');
            $admincp->do_manage();
        }
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
        topic::del_filedata($filename,'filename');
        $msg.= $this->del_msg($pic.'数据删除');
        return $msg;
    }
    public static function del($id,$uid='0',$postype='1') {
        $id = (int)$id;
        $id OR iUI::alert("请选择要删除的专题");
        $uid && $sql="and `userid`='$uid' and `postype`='$postype'";
        $art = topic::row($id,'cid,pic,tags',$sql);
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
        topic::del($id);
        topic::del_data($id);
        $msg.= self::del_msg('专题数据删除');
        categoryAdmincp::update_count($art['cid'],'-');
        $msg.= self::del_msg('栏目数据更新');
        $msg.= self::del_msg('删除完成');
        return $msg;
    }

    public function topic_data($tid=0){
        $id   = (int)$_POST['data_id'];
        $body = apps_meta::post('body');

        $fields = topic::data_fields($id);
        $data   = compact ($fields);

        if($id){
            topic::data_update($data,compact('id'));
        }else{
            $id = topic::data_insert($data);
        }
        iPHP::callback(array("spider","callback"),array($this,$tid,'data'));
    }

    public static function autodesc($data){
        if(self::$config['autodesc'] && self::$config['descLen']) {
            is_array($data) && $bodyText   = implode("\n",$data);
            $bodyText   = str_replace('#--iCMS.PageBreak--#',"\n",$bodyText);
            $bodyText   = str_replace('</p><p>', "</p>\n<p>", $bodyText);

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
    public function set_pic($picurl,$tid,$key='b'){
        $uri = parse_url(iCMS_FS_URL);
        if (stripos($picurl,$uri['host']) !== false){
            $pic = iFS::fp($picurl,'-http');
            list($width, $height, $type, $attr) = @getimagesize(iFS::fp($pic,'+iPATH'));

            $picdata  = topic::value('picdata',$tid);
            $picArray = filesApp::get_picdata($picdata);
            $picdata  = filesAdmincp::picdata($picArray,array($key=>array('w'=>$width,'h'=>$height)));

            $field = 'pic';
            if($key=='b'){
                $haspic = 1;
            }else{
                $field = $key.'pic';
            }

            topic::update(compact('haspic',$field,'picdata'),array('id'=>$tid));
            files::set_map(self::$appid,$tid,$pic,'path');
        }
    }

    public static function _count($where=null){
        $sql = iSQL::where($where,true);
        return iDB::value("SELECT count(*) FROM `#iCMS@__topic` WHERE 1=1 {$sql}");
    }
    public static function app_manage() {
        $manage = array();
        $array = apps::get_array(array("!table"=>0));
        foreach ($array as $key => $value) {
            // var_dump($value);
            list($path,$func)= apps::get_path($value['app'],'func',true);
            list($path2,$admincp)= apps::get_path($value['app'],'admincp',true);
            if(
                is_file($path) && method_exists($func,$value['app'].'_list')
                &&
                is_file($path2) && method_exists($admincp,'do_manage')
            ){
                $manage[]=$value;
            }elseif($value['apptype']=="2"){
                $manage[]=$value;
            }
        }
        return $manage;
    }
}
