<?php

defined('iPHP') OR exit('What are you doing?');

class askAdmincp{
    public $appid = null;
    public $callback = array();
    public function __construct() {
        $this->appid = iCMS_APP_ASK;
        $this->id = (int)$_GET['id'];
        category::$appid = $this->appid;
        tag::$appid = $this->appid;
    }
    public function do_config(){
        configAdmincp::app($this->appid);
    }
    public function do_save_config(){
        configAdmincp::save($this->appid);
    }

    public function do_add(){
        $this->id && $rs = iDB::row("SELECT * FROM `#iCMS@__ask` WHERE `id`='$this->id' LIMIT 1;",ARRAY_A);
        if(empty($rs)){
            $rs['status'] = '1';
        }else{
            $rs['content'] = iDB::value("SELECT `content` FROM `#iCMS@__ask_data` WHERE `iid`='$this->id' and `rootid`='0'");
        }
        iPHP::callback(array("apps_meta","get"),array($this->appid,$this->id));
        iPHP::callback(array("formerApp","add"),array($this->appid,$rs,true));
        include admincp::view('ask.add');
    }
    public function do_update(){
        if($this->id){
            $data = iSQL::update_args($_GET['_args']);
            $data && iDB::update("ask",$data,array('id'=>$this->id));
            iUI::success('操作成功!','js:1');
        }
    }
    public function do_answer(){
        menu::set('manage');

        $iid = (int)$_GET['iid'];
        $que = iDB::row("SELECT * FROM `#iCMS@__ask` WHERE `id`='$iid' LIMIT 1;",ARRAY_A);
        $category = categoryApp::get_cahce_cid($que['cid']);
        $iurl = iURL::get('ask', array($que, $category));
        $que['url'] = $iurl->url;

        $sql = "WHERE 1=1";
        $_GET['iid']          && $sql.= " AND `iid`='".(int)$_GET['iid']."'";
        isset($_GET['status']) && $sql.= " AND `status`='".$_GET['status']."'";
        $_GET['userid']&& $sql.= " AND `userid`='".(int)$_GET['userid']."'";
        $_GET['ip']    && $sql.= " AND `ip`='".$_GET['ip']."'";

        if($_GET['keywords']) {
            $sql.="  AND CONCAT(username,title) REGEXP '{$_GET['keywords']}'";
        }

        $maxperpage = $_GET['perpage']>0?(int)$_GET['perpage']:20;
        $total      = iPagination::totalCache("SELECT count(*) FROM `#iCMS@__ask_data` {$sql}","G");
        iUI::pagenav($total,$maxperpage,"条回复");
        $rs     = iDB::all("SELECT * FROM `#iCMS@__ask_data` {$sql} order by id ASC LIMIT ".iPagination::$offset." , {$maxperpage}");
        $_count = count($rs);
        include admincp::view("ask.answer");
    }
    public function do_edit(){
        menu::set('add');
        $this->id && $rs = iDB::row("SELECT * FROM `#iCMS@__ask_data` WHERE `id`='$this->id' LIMIT 1;",ARRAY_A);
        if(empty($rs)){
            $rs['status'] = '1';
        }
        include admincp::view('ask.edit');
    }
    public function do_answer_save(){
        $id       = (int)$_POST['id'];
        $iid      = (int)$_POST['iid'];
        $rootid   = (int)$_POST['rootid'];
        $title    = iSecurity::escapeStr($_POST['title']);
        $username = iSecurity::escapeStr($_POST['uname']);
        $userid   = iSecurity::escapeStr($_POST['uid']);
        $ip       = iSecurity::escapeStr($_POST['ip']);
        $status   = (int)$_POST['status'];
        $good     = (int)$_POST['good'];
        $bad      = (int)$_POST['bad'];
        $pubdate  = str2time($_POST['pubdate']);
        $postime  = str2time($_POST['postime']);
        $content  = iSecurity::escapeStr($_POST['content']);

        $fields = array(
            'iid', 'rootid', 'username', 'userid', 'title', 'content', 'good', 'bad', 'pubdate', 'postime', 'ip', 'status'
        );

        $data   = compact ($fields);

        if(empty($id)){
            iDB::insert('ask_data', $data);
            $msg ='回复添加完成';
        }else{
            iDB::update('ask_data',$data,array('id'=> $id));
            $msg = '回复更新完成';
        }

        iUI::success($msg,'js:1');
    }
    /**
     * Удалить回复
     */
    public function do_delete(){
        $id     = (int) $_GET['id'];
        $iid    = (int) $_GET['iid'];
        $rootid = (int) $_GET['rootid'];

        if (empty($iid)) {
            return iUI::alert('iid不能为空！');
        }
        if (empty($id)) {
            return iUI::alert('id不能为空！');
        }
        iDB::query("
            DELETE
            FROM
              `#iCMS@__ask_data`
            WHERE `id` = '$id';
        ");

        askApp::update_count($iid,'-');

        if(empty($rootid) && $iid && $rootid!=$iid){
            $que = iDB::row("
                SELECT `cid`,`tags`
                FROM `#iCMS@__ask`
                WHERE `id` = '$iid'
            ");

            iDB::query("
                DELETE
                FROM
                  `#iCMS@__ask`
                WHERE `id` = '$iid';
            ");

            //只删除关联数据 不删除标签
            tag::$remove = false;
            tag::del($que->tags,'name',$iid);
            categoryAdmincp::update_count($que->cid,'-');
        }
        iUI::success("回复删除",'js:parent.$("#id-'.$id.'").remove();');
    }
    public function do_iCMS(){
    	admincp::$APP_METHOD="domanage";
    	$this->do_manage();
    }
    public function do_inbox(){
        $this->do_manage("inbox");
    }
    public function do_trash(){
        $this->do_manage("trash");
    }
    public function do_manage($stype='normal'){
        $stype_map = array(
            'inbox'   =>'0',//草稿
            'normal'  =>'1',//正常
            'trash'   =>'2',//回收站
            'examine' =>'3',//待审核
            'off'     =>'4',//未通过
        );
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


        $cid    = (int)$_GET['cid'];
        $pid    = (int)$_GET['pid'];
        $rootid = (int)$_GET['rootid'];

        $_GET['keywords'] && $sql.=" AND CONCAT(title,tags,userid,username) REGEXP '{$_GET['keywords']}'";

        $sql.= category::search_sql($cid);
        $_GET['starttime'] && $sql.=" AND `pubdate`>='".str2time($_GET['starttime'].(strpos($_GET['starttime'],' ')!==false?'':" 00:00:00"))."'";
        $_GET['endtime']   && $sql.=" AND `pubdate`<='".str2time($_GET['endtime'].(strpos($_GET['endtime'],' ')!==false?'':" 23:59:59"))."'";
        $_GET['post_starttime'] && $sql.=" AND `postime`>='".str2time($_GET['post_starttime'].(strpos($_GET['post_starttime'],' ')!==false?'':" 00:00:00"))."'";
        $_GET['post_endtime']   && $sql.=" AND `postime`<='".str2time($_GET['post_endtime'].(strpos($_GET['post_endtime'],' ')!==false?'':" 23:59:59"))."'";

        $_GET['field'] && $sql.=" AND `field` ='".$_GET['field']."'";
        if(isset($_GET['pid']) && $pid!='-1'){
            $uri_array['pid'] = $pid;
            if($_GET['pid']==0){
                $sql.= " AND `pid`=''";
            }else{
                iMap::init('prop',$this->appid,'pid');
                $map_where = iMap::where($pid);
            }
        }
        if($map_where){
            $map_sql = iSQL::select_map($map_where);
            $sql     = ",({$map_sql}) map {$sql} AND `id` = map.`iid`";
        }

        list($orderby,$orderby_option) = get_orderby(array(
            'id'       =>"ID",
            'pubdate'  =>"Время публикации",
            'lastpost' =>"最后发贴时间",
            'replies'  =>"回复数",
        ));
        $maxperpage = $_GET['perpage']>0?(int)$_GET['perpage']:20;
        $total		= iPagination::totalCache("SELECT count(*) FROM `#iCMS@__ask` {$sql}","G");
        iUI::pagenav($total,$maxperpage,"个问题");
        $limit  = 'LIMIT '.iPagination::$offset.','.$maxperpage;
        if($map_sql||iPagination::$offset){
            if(iPagination::$offset > 1000 && $total > 2000 && iPagination::$offset >= $total/2) {
                $_offset = $total-iPagination::$offset-$maxperpage;
                if($_offset < 0) {
                    $_offset = 0;
                }
                $orderby = "id ASC";
                $limit = 'LIMIT '.$_offset.','.$maxperpage;
            }
            $ids_array = iDB::all("
                SELECT `id` FROM `#iCMS@__ask` {$sql}
                ORDER BY {$orderby} {$limit}
            ");
            if(isset($_offset)){
                $ids_array = array_reverse($ids_array, TRUE);
                $orderby   = "id DESC";
            }
            $ids   = iSQL::values($ids_array);
            $ids   = $ids?$ids:'0';
            $sql   = "WHERE `id` IN({$ids})";
            $limit = '';
        }
        $rs     = iDB::all("SELECT * FROM `#iCMS@__ask` {$sql} ORDER BY {$orderby} {$limit}");
        $_count = count($rs);
        $propArray = propAdmincp::get("pid",null,'array');
    	include admincp::view("ask.manage");
    }

    public function do_question_save(){
        $id          = (int)$_POST['id'];
        $cid         = (int)$_POST['cid'];
        $pid         = implode(',', (array)$_POST['pid']);
        $_cid        = iSecurity::escapeStr($_POST['_cid']);
        $_pid        = iSecurity::escapeStr($_POST['_pid']);
        $_tags       = iSecurity::escapeStr($_POST['_tags']);

        $title       = iSecurity::escapeStr($_POST['title']);
        $tags        = str_replace('，', ',',iSecurity::escapeStr($_POST['tags']));

        $username    = iSecurity::escapeStr($_POST['uname']);
        $userid      = iSecurity::escapeStr($_POST['uid']);
        $lastposter  = iSecurity::escapeStr($_POST['lastposter']);
        $lastpostuid = iSecurity::escapeStr($_POST['lastpostuid']);
        $replies     = (int)$_POST['replies'];
        $ip          = iSecurity::escapeStr($_POST['ip']);
        $description = iSecurity::escapeStr($_POST['description']);
        $clink       = iSecurity::escapeStr($_POST['clink']);
        $tpl         = iSecurity::escapeStr($_POST['tpl']);
        $pic         = iSecurity::escapeStr($_POST['pic']);
        $description = iSecurity::escapeStr($_POST['description']);
        $keywords    = iSecurity::escapeStr($_POST['keywords']);

        $status      = (int)$_POST['status'];
        $sortnum     = (int)$_POST['sortnum'];
        $weight      = (int)$_POST['weight'];

        $hits       = (int)$_POST['hits'];
        $hits_today = (int)$_POST['hits_today'];
        $hits_yday  = (int)$_POST['hits_yday'];
        $hits_week  = (int)$_POST['hits_week'];
        $hits_month = (int)$_POST['hits_month'];
        $favorite   = (int)$_POST['favorite'];
        $comments   = (int)$_POST['comments'];
        $good       = (int)$_POST['good'];
        $bad        = (int)$_POST['bad'];

        $pubdate     = str2time($_POST['pubdate']);
        $postime     = str2time($_POST['postime']);
        $lastpost    = str2time($_POST['lastpost']);
        $postype     = $_POST['postype']?$_POST['postype']:0;

        $content     = iSecurity::escapeStr($_POST['content']);


        if (empty($title)) {
            return iUI::alert('Заголовок не может быть пустым！');
        }
        if (empty($cid)) {
            return iUI::alert('Выберите категорию для привязки');
        }
        (iFS::checkHttp($pic)  && !isset($_POST['pic_http']))  && $pic  = iFS::http($pic);
        $haspic = empty($pic)?0:1;

        // if(empty($description)) {
        //     $a = array(
        //         'markdown' => true,
        //     );
        //     $md_content = iPHP::callback(array("plugin_markdown","HOOK"),array(implode('', (array)$content),&$a));
        //     empty($md_content) && $md_content = $content;
        //     $description = $this->autodesc($md_content);
        // }

        $fields = array(
            'cid', 'pid',
            'keywords', 'haspic', 'pic', 'description',
            'title', 'tags', 'username', 'userid', 'pubdate', 'postime', 'lastpost', 'lastpostuid', 'lastposter', 'replies', 'ip',
            'clink', 'tpl', 'hits', 'hits_today', 'hits_yday', 'hits_week', 'hits_month', 'favorite', 'comments', 'good', 'bad', 'sortnum', 'weight',
            'creative', 'mobile', 'postype', 'status'
        );

        $data   = compact ($fields);

		if(empty($id)){
            $id = iDB::insert('ask',$data);

            $answer = compact(array('iid','rootid','username', 'userid', 'title', 'content', 'good', 'bad', 'pubdate', 'postime', 'ip', 'status'));
            $answer['iid'] = $id;
            iDB::insert('ask_data', $answer);

            $tags && tag::add($tags,$userid,$id,$cid);

            iMap::init('prop',$this->appid,'pid');
            $pid && iMap::add($pid,$id);

            iMap::init('category',$this->appid,'cid');
            iMap::add($cid,$id);

            $msg ='问题添加完成';
		}else{
            iDB::update('ask', $data, array('id'=>$id));
            iDB::update('ask_data', array(
                'title'   => $title,
                'content' => $content,
                'pubdate' => $pubdate,
                'postime' => $postime,
                'good'    => $good,
                'bad'     => $bad,
                'ip'      => $ip,
                'status'  => $status,
            ),array(
                'rootid' => '0',
                'iid'    => $id,
                'userid' => $userid
            ));

            iMap::init('prop',$this->appid,'pid');
            iMap::diff($pid,$_pid,$id);

            iMap::init('category',$this->appid,'cid');
            iMap::diff($cid,$_cid,$id);

            ($tags||$_tags) && tag::diff($tags,$_tags,$userid,$id,$cid);

            $msg = '问题更新完成';
		}
        iPHP::callback(array("apps_meta","save"),array($this->appid,$id));
        iPHP::callback(array("formerApp","save"),array($this->appid,$id));
        iPHP::callback(array("spider","callback"),array($this,$id));

        if($this->callback['return']){
            return $this->callback['return'];
        }

        iUI::success($msg,"url:".APP_URI);
    }

    public function check_spider_data(&$data,$old,$key,$value){
        if($old[$key]){
            if($value){
                $data[$key] = $value;
            }else{
                unset($data[$key]);
            }
        }
    }
    public function do_cache(){
    	iUI::success("问题缓存更新成功");
    }
    public function do_del($id = null,$dialog=true){
    	$id===null && $id=$this->id;
    	$this->del($id);
    	$dialog && iUI::success("问题删除成功",'js:parent.$("#id'.$id.'").remove();');
    }
    public function do_batch(){
        list($idArray,$ids,$batch) = iUI::get_batch_args("请选择要操作的问题");

    	switch($batch){
    		case 'dels':
				iUI::$break	= false;
	    		foreach($idArray AS $id){
	    			$this->do_del($id,false);
	    		}
	    		iUI::$break	= true;
				iUI::success('问题全部删除完成!','js:1');
    		break;
    		case 'move':
		        $_POST['cid'] OR iUI::alert("请选择目标栏目!");
                iMap::init('category',$this->appid,'cid');
		        $cid = (int)$_POST['cid'];
		        foreach($idArray AS $id) {
                    $_cid = iDB::value("SELECT `cid` FROM `#iCMS@__ask` where `id` ='$id'");
                    iDB::update("ask",compact('cid'),compact('id'));
		            if($_cid!=$cid) {
                        iMap::diff($cid,$_cid,$id);
                        categoryAdmincp::update_count($_cid,'-');
                        categoryAdmincp::update_count($cid);
		            }
		        }
		        iUI::success('成功移动到目标栏目!','js:1');
    		break;
    		case 'prop':
                iMap::init('prop',$this->appid,'pid');
                $pid = implode(',', (array)$_POST['pid']);
                foreach((array)$_POST['id'] AS $id) {
                    $_pid = iDB::value("SELECT pid FROM `#iCMS@__ask` WHERE `id`='$id'");;
                    iDB::update("ask",compact('pid'),compact('id'));
                    iMap::diff($pid,$_pid,$id);
                }
                iUI::success('属性设置完成!','js:1');
    		break;
    		case 'weight':
		        $weight	=(int)$_POST['mweight'];
		        $sql	="`weight` = '$weight'";
    		break;
            case 'tpl':
                $tpl = iSecurity::escapeStr($_POST['mtpl']);
                $sql = "`tpl` = '$tpl'";
            break;
    		case 'keyword':
    			if($_POST['pattern']=='replace') {
    				$sql	="`keywords` = '".iSecurity::escapeStr($_POST['mkeyword'])."'";
    			}elseif($_POST['pattern']=='addto') {
		        	foreach($idArray AS $id){
                        $keywords = iDB::value("SELECT keywords FROM `#iCMS@__ask` WHERE `id`='$id'");
                        $sql      ="`keywords` = '".($keywords?$keywords.','.iSecurity::escapeStr($_POST['mkeyword']):iSecurity::escapeStr($_POST['mkeyword']))."'";
				        iDB::query("UPDATE `#iCMS@__ask` SET {$sql} WHERE `id`='$id'");
		        	}
		        	iUI::success('关键字更改完成!','js:1');
    			}
    		break;
    		case 'tag':
                foreach($_POST['id'] AS $id){
                    $que  = iDB::row("SELECT `tags`,`cid` FROM `#iCMS@__ask` WHERE `id`='$id'",ARRAY_A);
                    $mtag = iSecurity::escapeStr($_POST['mtag']);
                    $tagArray  = explode(',', $que['tags']);
                    $mtagArray = explode(',', $mtag);
                    if($_POST['pattern']=='replace') {
                    }elseif($_POST['pattern']=='addto') {
                        $pieces = array_merge($tagArray,$mtagArray);
                        $pieces = array_unique($pieces);
                        $mtag   = implode(',', $pieces);
                    }

                    $tags = tag::diff($mtag,$que['tags'],members::$userid,$id,$que['cid']);
                    $tags = addslashes($tags);
                    iDB::update('ask',compact('tags'),compact('id'));
                }
                iUI::success('标签更改完成!','js:1');
    		break;
    		default:
                if(strpos($batch, ':')){
                    $data = iSQL::update_args($batch);
                    foreach($idArray AS $id) {
                        $data && iDB::update("tag",$data,array('id'=>$id));
                    }
                    iUI::success('操作成功!','js:1');
                }else{
                    iUI::alert('请选择要操作项!','js:1');
                }

		}
        $sql && iDB::query("UPDATE `#iCMS@__ask` SET {$sql} WHERE `id` IN ($ids)");
		iUI::success('操作成功!','js:1');
	}
    public function del($iid=null){
        $ask = iDB::row("
            SELECT `tags`,`pid`,`cid` FROM `#iCMS@__ask`
            WHERE `id`='".$iid. "'
            AND `status` ='1' LIMIT 1;",
        ARRAY_A);

        if($ask['tags']){
            //只删除关联数据 不删除标签
            tag::$remove = false;
            tag::del($ask['tags'],'name',$id);
        }

        iMap::del_data($id,$this->appid,'category');
        iMap::del_data($id,$this->appid,'prop');

        categoryAdmincp::update_count($ask['cid'],'-');

        iDB::query("DELETE FROM `#iCMS@__ask_data` WHERE `iid` = '$iid';");
        iDB::query("DELETE FROM `#iCMS@__ask` WHERE `id`='$iid';");
    }
    public static function _count(){
        return iDB::value("SELECT count(*) FROM `#iCMS@__ask`");
    }
}
