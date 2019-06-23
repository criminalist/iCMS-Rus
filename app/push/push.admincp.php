<?php

class pushAdmincp{
    public $appid = null;
    public $callback = array();
    public function __construct() {
        $this->appid = iCMS_APP_PUSH;
        $this->id    = (int)$_GET['id'];
    }
    public function do_add(){
        $id = (int)$_GET['id'];
        $rs = array();
        $_GET['title'] 	&& $rs['title']	= $_GET['title'];
        $_GET['pic'] 	&& $rs['pic']	= $_GET['pic'];
        $_GET['url'] 	&& $rs['url']	= $_GET['url'];

        $_GET['title2']	&& $rs['title2']= $_GET['title2'];
        $_GET['pic2'] 	&& $rs['pic2']	= $_GET['pic2'];
        $_GET['url2'] 	&& $rs['url2']	= $_GET['url2'];

        $_GET['title3']	&& $rs['title3']= $_GET['title3'];
        $_GET['pic3'] 	&& $rs['pic3']	= $_GET['pic3'];
        $_GET['url3'] 	&& $rs['url3']	= $_GET['url3'];

        $id && $rs	= iDB::row("SELECT * FROM `#iCMS@__push` WHERE `id`='$id' LIMIT 1;",ARRAY_A);
        empty($rs['editor']) && $rs['editor'] = members::$nickname;
        empty($rs['uid']) && $rs['uid'] = members::$userid;
        $rs['addtime']	= $id?get_date(0,"Y-m-d H:i:s"):get_date($rs['addtime'],'Y-m-d H:i:s');
        $cid			= empty($rs['cid'])?(int)$_GET['cid']:$rs['cid'];

        // iPHP::callback(array("apps_meta","get"),array($this->appid,$this->id));
        iPHP::callback(array("formerApp","add"),array($this->appid,$rs,true));

    	include admincp::view("push.add");
    }

    public function do_iCMS(){
    	admincp::$APP_METHOD="domanage";
    	$this->do_manage();
    }
    public function do_manage($doType=null) {
        $cid        = (int)$_GET['cid'];
        $pcid        = (int)$_GET['pcid'];
        $sql        = " where ";
        switch($doType){ //status:[0:草稿][1:正常][2:回收][3:审核][4:不合格]
        	case 'inbox'://草稿
        		$sql.="`status` ='0'";
        		// if(members::$data->gid!=1){
        		// 	$sql.=" AND `userid`='".members::$userid."'";
        		// }
        		$position="草稿";
        	break;
         	case 'trash'://回收站
        		$sql.="`status` ='2'";
        		$position="回收站";
        	break;
         	case 'examine'://审核
        		$sql.="`status` ='3'";
        		$position="已审核";
        	break;
         	case 'off'://未通过
        		$sql.="`status` ='4'";
        		$position="未通过";
        	break;
       		default:
	       		$sql.=" `status` ='1'";
		       	$cid && $position=category::get($cid)->name;
		}

        if($_GET['keywords']) {
			$sql.=" AND CONCAT(title,title2,title3) REGEXP '{$_GET['keywords']}'";
        }

        $sql.=category::search_sql($cid);
        $sql.=category::search_sql($pcid,'pcid');

        isset($_GET['nopic'])&& $sql.=" AND `haspic` ='0'";
        $_GET['starttime']   && $sql.=" and `addtime`>=UNIX_TIMESTAMP('".$_GET['starttime']." 00:00:00')";
        $_GET['endtime']     && $sql.=" and `addtime`<=UNIX_TIMESTAMP('".$_GET['endtime']." 23:59:59')";

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

        isset($_GET['uid']) && $uri.='&userid='.(int)$_GET['uid'];
        isset($_GET['keyword'])&& $uri.='&keyword='.$_GET['keyword'];
        isset($_GET['pid'])    && $uri.='&pid='.$_GET['pid'];
        isset($_GET['cid'])    && $uri.='&cid='.$_GET['cid'];
        isset($_GET['pcid'])    && $uri.='&pcid='.$_GET['pcid'];
        (isset($_GET['pid']) && $_GET['pid']!='-1') && $uri.='&pid='.$_GET['at'];

        $orderby    =$_GET['orderby']?$_GET['orderby']:"id DESC";
        $maxperpage = $_GET['perpage']>0?(int)$_GET['perpage']:20;
        $total      = iPagination::totalCache("SELECT count(*) FROM `#iCMS@__push` {$sql}","G");
        iUI::pagenav($total,$maxperpage,"条记录");
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
                SELECT `id` FROM `#iCMS@__push` {$sql}
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
        $rs     = iDB::all("SELECT * FROM `#iCMS@__push` {$sql} ORDER BY {$orderby} {$limit}");
        $_count = count($rs);
        $propArray = propAdmincp::get("pid",null,'array');

        include admincp::view("push.manage");
    }
    public function do_save(){
        $id     = (int)$_POST['id'];
        $uid    = (int)$_POST['uid'];
        $rootid = (int)$_POST['rootid'];
        $cid    = implode(',', (array)$_POST['cid']);
        $pcid   = implode(',', (array)$_POST['pcid']);
        $pid    = implode(',', (array)$_POST['pid']);
        $_cid   = iSecurity::escapeStr($_POST['_cid']);
        $_pcid  = iSecurity::escapeStr($_POST['_pcid']);
        $_pid   = iSecurity::escapeStr($_POST['_pid']);

        $editor  = iSecurity::escapeStr($_POST['editor']);
        $sortnum = (int)$_POST['sortnum'];
        $pubdate = str2time($_POST['pubdate']);



        $title        = iSecurity::escapeStr($_POST['title']);
        $pic          = iSecurity::escapeStr($_POST['pic']);
        $description  = iSecurity::escapeStr($_POST['description']);
        $url          = iSecurity::escapeStr($_POST['url']);

        $title2       = iSecurity::escapeStr($_POST['title2']);
        $pic2         = iSecurity::escapeStr($_POST['pic2']);
        $description2 = iSecurity::escapeStr($_POST['description2']);
        $url2         = iSecurity::escapeStr($_POST['url2']);

        $title3       = iSecurity::escapeStr($_POST['title3']);
        $pic3         = iSecurity::escapeStr($_POST['pic3']);
        $description3 = iSecurity::escapeStr($_POST['description3']);
        $url3         = iSecurity::escapeStr($_POST['url3']);

        iFS::$force_ext = "jpg";
        (iFS::checkHttp($pic)  && !isset($_POST['pic_http']))  && $pic  = iFS::http($pic);
        (iFS::checkHttp($pic2) && !isset($_POST['pic2_http'])) && $pic2 = iFS::http($pic2);
        (iFS::checkHttp($pic3) && !isset($_POST['pic3_http'])) && $pic3 = iFS::http($pic3);

		empty($uid) && $uid=members::$userid;
        empty($title) && iUI::alert('1.Введите название');
        empty($cid) && iUI::alert('Выберите категорию');

        $haspic	= empty($pic)?0:1;

        $status	= 1;
        $fields = array('cid', 'pcid', 'rootid', 'pid', 'haspic', 'editor', 'uid',
            'title', 'pic', 'url', 'description',
            'title2', 'pic2', 'url2', 'description2',
            'title3', 'pic3', 'url3', 'description3',
            'sortnum', 'pubdate','hits', 'status');

        $data   = compact ($fields);

        if(empty($id)) {
            $data['postime']  = $pubdate;
            $id = iDB::insert('push',$data);

            iMap::init('prop',$this->appid,'pid');
            $pid && iMap::add($pid,$id);

            iMap::init('category',$this->appid,'cid');
            iMap::add($cid,$id);
            $pcid && iMap::add($pcid,$id);
            $pcid && categoryAdmincp::update_count($pcid);

            $msg = '推荐添加完成';
        }else{
			iDB::update('push', $data, array('id'=>$id));
            iMap::init('prop',$this->appid,'pid');
            iMap::diff($pid,$_pid,$id);

            iMap::init('category',$this->appid,'cid');
            iMap::diff($cid,$_cid,$id);
            iMap::diff($pcid,$_pcid,$id);

            if($_pcid!=$pcid) {
                categoryAdmincp::update_count($_pcid,'-');
                categoryAdmincp::update_count($pcid);
            }
            $msg = '推荐更新完成!';
        }
        // iPHP::callback(array("apps_meta","save"),array($this->appid,$id));
        iPHP::callback(array("formerApp","save"),array($this->appid,$id));
        iPHP::callback(array("spider","callback"),array($this,$id));

        if($this->callback['return']){
            return $this->callback['return'];
        }

        iUI::success($msg,'url:'.APP_URI);
    }


    public function do_del($id = null,$dialog=true){
    	$id===null && $id=$this->id;
		$id OR iUI::alert('请选择要删除的推荐');
		iDB::query("DELETE FROM `#iCMS@__push` WHERE `id` = '$id'");
		$dialog && iUI::success('推荐删除完成','js:parent.$("#id'.$id.'").remove();');
    }
    public function do_batch(){
        list($idArray,$ids,$batch) = iUI::get_batch_args("请选择要删除的推荐");
    	switch($batch){
    		case 'dels':
				iUI::$break	= false;
	    		foreach($idArray AS $id){
	    			$this->do_del($id,false);
	    		}
	    		iUI::$break	= true;
				iUI::success('全部删除完成!','js:1');
    		break;
            case 'move':
                $_POST['cid'] OR iUI::alert("请选择目标栏目!");
                iMap::init('category',$this->appid,'cid');
                $cid = (int)$_POST['cid'];
                foreach($idArray AS $id) {
                    $_cid = iDB::value("SELECT `cid` FROM `#iCMS@__push` where `id` ='$id'");
                    iDB::update("push",compact('cid'),compact('id'));
                    if($_cid!=$cid) {
                        iMap::diff($cid,$_cid,$id);
                    }
                }
                iUI::success('成功移动到目标栏目!','js:1');
            break;
            case 'mvpcid':
                $_POST['pcid'] OR iUI::alert("请选择目标分类!");
                iMap::init('category',$this->appid,'cid');
                $pcid = (int)$_POST['pcid'];
                foreach($idArray AS $id) {
                    $_pcid = iDB::value("SELECT `pcid` FROM `#iCMS@__push` where `id` ='$id'");
                    iDB::update("push",compact('pcid'),compact('id'));
                    if($_pcid!=$pcid) {
                        iMap::diff($pcid,$_pcid,$id);
                        categoryAdmincp::update_count($_pcid,'-');
                        categoryAdmincp::update_count($pcid);
                    }
                }
                iUI::success('成功移动到目标分类!','js:1');
            break;
            case 'prop':
                iMap::init('prop',$this->appid,'pid');
                $pid = implode(',', (array)$_POST['pid']);
                foreach((array)$_POST['id'] AS $id) {
                    $_pid = iDB::value("SELECT pid FROM `#iCMS@__push` WHERE `id`='$id'");;
                    iDB::update("push",compact('pid'),compact('id'));
                    iMap::diff($pid,$_pid,$id);
                }
                iUI::success('属性设置完成!','js:1');
            break;
		}
	}
}
