<?php

class userAdmincp{
    public $groupAdmincp = null;
    public function __construct() {
        $this->appid        = iCMS_APP_USER;
        $this->uid          = (int)$_GET['id'];
        $this->groupAdmincp = new groupAdmincp(0);
    }
    public function do_config(){
        configAdmincp::app($this->appid);
    }
    public function do_save_config(){
        foreach ((array)$_POST['config']['open'] as $key => $value) {
            if($value['appid'] && $value['appkey']){
                $_POST['config']['open'][$key]['enable'] = true;
            }
        }

        configAdmincp::save($this->appid);
    }
    public function do_update(){
        $data = iSQL::update_args($_GET['_args']);
        $data && iDB::update('user',$data,array('uid'=>$this->uid));
        iUI::success('Успешно выполнено!','js:1');
    }
    public function do_add(){
        if($this->uid) {
            $rs = iDB::row("SELECT * FROM `#iCMS@__user` WHERE `uid`='$this->uid' LIMIT 1;");
            $rs && $userdata = iDB::row("SELECT * FROM `#iCMS@__user_data` WHERE `uid`='$this->uid' LIMIT 1;");
        }
        iPHP::callback(array("formerApp","add"),array($this->appid,(array)$rs,true));
        iPHP::callback(array("apps_meta","get"),array($this->appid,$this->uid));
        include admincp::view("user.add");
    }
    /**
     * [Логин пользователя]
     * @return [type] [description]
     */
    public function do_login(){
        if($this->uid) {
            $user = iDB::row("SELECT * FROM `#iCMS@__user` WHERE `uid`='$this->uid' LIMIT 1;",ARRAY_A);
            user::set_cookie($user['username'],$user['password'],$user);
            $url = iURL::router(array('uid:home',$this->uid));
            iPHP::redirect($url);
        }
    }
    public function do_iCMS(){
        $sql = "WHERE 1=1";
        $pid = $_GET['pid'];

        if($_GET['wxappid']) {
            $sql.=" AND `username` like '%@{$_GET['wxappid']}'";
        }
        if($_GET['keywords']) {
            $sql.=" AND CONCAT(username,nickname) REGEXP '{$_GET['keywords']}'";
        }

        $_GET['gid'] && $sql.=" AND `gid`='{$_GET['gid']}'";
        if(isset($_GET['status']) && $_GET['status']!==''){
            $sql.=" AND `status`='{$_GET['status']}'";
        }
        $_GET['regip'] && $sql.=" AND `regip`='{$_GET['regip']}'";
        $_GET['loginip'] && $sql.=" AND `lastloginip`='{$_GET['loginip']}'";

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
            $sql     = ",({$map_sql}) map {$sql} AND `uid` = map.`iid`";
        }
        list($orderby,$orderby_option) = get_orderby(array(
            'uid'        =>"UID",
            'hits'       =>"Просмотры",
            'hits_week'  =>"Просмотры за неделю",
            'hits_month' =>"Просмотры за месяц",
            'fans'       =>"Понравилось",
            'follow'     =>"Подписчики",
            'article'    =>"Кол-во постов",
            'favorite'   =>"В избранном",
            'comments'   =>"Кол-во комменатриев",
        ));

        $maxperpage = $_GET['perpage']>0?(int)$_GET['perpage']:20;
        $total      = iPagination::totalCache("SELECT count(*) FROM `#iCMS@__user` {$sql}","G");
        iUI::pagenav($total,$maxperpage,"个用户");
        $limit  = 'LIMIT '.iPagination::$offset.','.$maxperpage;
        if($map_sql||iPagination::$offset){
            $ids_array = iDB::all("
                SELECT `uid` FROM `#iCMS@__user` {$sql}
                ORDER BY {$orderby} {$limit}
            ");
            $ids   = iSQL::values($ids_array,'uid');
            $ids   = $ids?$ids:'0';
            $sql   = "WHERE `uid` IN({$ids})";
            $limit = '';
        }
        $rs     = iDB::all("SELECT * FROM `#iCMS@__user` {$sql} ORDER BY {$orderby} {$limit}");
        $_count = count($rs);
        $propArray = propAdmincp::get("pid",null,'array');
        include admincp::view("user.manage");
    }
    public function do_save(){
        $uid      = (int)$_POST['uid'];
        $pid      = implode(',', (array)$_POST['pid']);
        $_pid     = iSecurity::escapeStr($_POST['_pid']);
        $user     = iSecurity::escapeStr($_POST['user']);
        $userdata = iSecurity::escapeStr($_POST['userdata']);
        $username = iSecurity::escapeStr($user['username']);
        $nickname = iSecurity::escapeStr($user['nickname']);
        $password = iSecurity::escapeStr($user['password']);
        unset($user['password']);

        $username OR iUI::alert('Введите имя пользователя');
        preg_match("/^[\w\-\.]+@[\w\-]+(\.\w+)+$/i",$username) OR iUI::alert('Неверный формат имени пользователя');
        $nickname OR iUI::alert('Введите логин пользователя');

        $user['regdate']       = str2time($user['regdate']);
        $user['lastlogintime'] = str2time($user['lastlogintime']);
        $user['pid']           = $pid;

       if(empty($uid)) {
            $password OR iUI::alert('Введите пароль');
            $user['password'] = md5($password);
            iDB::value("SELECT `uid` FROM `#iCMS@__user` where `username` ='$username' LIMIT 1") && iUI::alert('Имя пользователя уже существует');
            iDB::value("SELECT `uid` FROM `#iCMS@__user` where `nickname` ='$nickname' LIMIT 1") && iUI::alert('Логин уже занят другим пользователем');
            $uid = iDB::insert('user',$user);
            iMap::init('prop',iCMS_APP_USER,'pid');
            $pid && iMap::add($pid,$uid);
            $msg = "Аккаунт спешно создан!";
        }else {
            iDB::value("SELECT `uid` FROM `#iCMS@__user` where `username` ='$username' AND `uid` !='$uid' LIMIT 1") && iUI::alert('Имя пользователя уже существует');
            iDB::value("SELECT `uid` FROM `#iCMS@__user` where `nickname` ='$nickname' AND `uid` !='$uid' LIMIT 1") && iUI::alert('Логин уже занят другим пользователем');
            $password && $user['password'] = md5($password);
            iDB::update('user', $user, array('uid'=>$uid));
            iMap::init('prop',iCMS_APP_USER,'pid');
            iMap::diff($pid,$_pid,$uid);
            if(iDB::value("SELECT `uid` FROM `#iCMS@__user_data` where `uid`='$uid' LIMIT 1")){
                iDB::update('user_data', $userdata, array('uid'=>$uid));
            }else{
                $userdata['uid'] = $uid;
                iDB::insert('user_data',$userdata);
            }
            $msg = "Аккаунт успешно изменен!";
        }
        iPHP::callback(array("apps_meta","save"),array($this->appid,$uid));
        iPHP::callback(array("formerApp","save"),array($this->appid,$uid));
        iUI::success($msg,'url:'.APP_URI);
    }
    public function do_batch(){
    	list($idArray,$ids,$batch) = iUI::get_batch_args("Выберите пользователя");
    	switch($batch){
            case 'prop':
                iMap::init('prop',iCMS_APP_USER,'pid');

                $pid = implode(',', (array)$_POST['pid']);
                foreach($idArray AS $id) {
                    $_pid = iDB::value("SELECT `pid` FROM `#iCMS@__user` where `uid`='$id' LIMIT 1");
                    iDB::update('user',compact('pid'),array('uid'=>$id));
                    iMap::diff($pid,$_pid,$id);
                }
                iUI::success('Настройка свойства пользователя завершена!','js:1');

            break;
    		case 'dels':
                iUI::$break = false;
	    		foreach($idArray AS $id){
	    			$this->do_del((int)$id,false);
	    		}
                iUI::$break = true;
				iUI::success('Выбранные пользователи успешно удалены!','js:1');
    		break;
		}
	}
    public function do_del($uid = null,$dialog=true){
    	$uid===null && $uid=$this->uid;
		$uid OR iUI::alert('Выберите хотя бы одного пользователя которого вы хотите удалить');
        iDB::query("DELETE FROM `#iCMS@__user` WHERE `uid` = '$uid'");
        iDB::query("DELETE FROM `#iCMS@__user_category` WHERE `uid` = '$uid'");
        iDB::query("DELETE FROM `#iCMS@__user_data` WHERE `uid` = '$uid'");
        iDB::query("DELETE FROM `#iCMS@__user_follow` WHERE `uid` = '$uid'");
        iDB::query("DELETE FROM `#iCMS@__user_openid` WHERE `uid` = '$uid'");
        iDB::query("DELETE FROM `#iCMS@__user_report` WHERE `uid` = '$uid'");
        iDB::query("DELETE FROM `#iCMS@__user` WHERE `uid` = '$uid'");
        if(iDB::check_table('user_cdata')){
            iDB::query("DELETE FROM `#iCMS@__user_cdata` WHERE `user_id` = '$uid'");
        }
        // iMap::del_data($uid,iCMS_APP_USER,'prop');
		$dialog && iUI::success('Удаление пользователя завершено','js:parent.$("#id'.$uid.'").remove();');
    }
    public static function _count(){
        return iDB::value("SELECT count(*) FROM `#iCMS@__user`");
    }
}
