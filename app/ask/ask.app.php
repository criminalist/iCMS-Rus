<?php


class askApp extends appsApp {
    public function __construct() {
        parent::__construct('ask');
        $this->add_method('get_answer,add_answer,get_question,add_question,delete');
    }
	public function ACTION_vote() {
		// user::get_cookie() OR iUI::code(0,'iCMS:!login',0,'json');
		$id = (int) $_POST['id'];
		$rootid = (int) $_POST['rootid'];
		$id OR iUI::code(0, 'ask:empty_id', 0, 'json');
		$key = 'ask_good_' . $id;
		$good  = iPHP::get_cookie($key);
		$good && iUI::code(0, 'ask:!good', 0, 'json');
		iDB::query("UPDATE `#iCMS@__ask_data` SET `good`=good+1 WHERE `id`='$id'");
		$rootid && iDB::query("UPDATE `#iCMS@__ask` SET `good`=good+1 WHERE `id`='$rootid'");
		iPHP::set_cookie($key, $_SERVER['REQUEST_TIME'], 86400);
		iUI::code(1, 'ask:good', 0, 'json');
	}

	public function ACTION_delete() {
		user::get_cookie() OR iUI::code(0,'iCMS:!login',0,'json');

		$id     = (int) $_POST['id'];
		$iid    = (int) $_POST['iid'];
		$rootid = (int) $_POST['rootid'];
		$userid = user::$userid;

		$iid OR iUI::code(0, 'ask:empty_iid', 0, 'json');
		$id OR iUI::code(0, 'ask:empty_id', 0, 'json');

		iDB::query("
			DELETE
			FROM
			  `#iCMS@__ask_data`
			WHERE `id` = '$id' and `userid`='$userid';
		");

		askApp::update_count($iid,'-');

		if(isset($_POST['rootid']) && empty($rootid) && $iid && $rootid!=$iid){
			$ask = iDB::row("
				SELECT `cid`,`tags`
				FROM `#iCMS@__ask`
				WHERE `id` = '$iid' and `userid`='$userid'
			");

			iDB::query("
				DELETE
				FROM
				  `#iCMS@__ask`
				WHERE `id` = '$iid' and `userid`='$userid';
			");

			//只删除关联数据 不删除标签
            tag::$remove = false;
            tag::del($ask->tags,'name',$iid);
			categoryAdmincp::update_count($ask->cid,'-');
			$category = categoryApp::get_cahce_cid($ask->cid);

			return iUI::code(1, 'ask:delete_success', $category['url'], 'json');
		}
		iUI::code(1, 'ask:delete_success', 0, 'json');
	}
	public function ACTION_get_question() {
		user::get_cookie() OR iUI::code(0, 'iCMS:!login', 0, 'json');

		$id     = (int) $_POST['id'];
		$iid    = (int) $_POST['iid'];
		$userid = (int) $_POST['userid'];

		if($userid==user::$userid){
			$ask = iDB::row("
				SELECT `cid`,`title`,`tags` FROM `#iCMS@__ask`
				WHERE `id`='".$iid. "'
				AND `userid`='".$userid. "'
				AND `status` ='1' LIMIT 1;",
			ARRAY_A);

			$ask['content'] = iDB::value("
				SELECT `content` FROM `#iCMS@__ask_data`
				WHERE `id`='".$id. "'
				AND `iid`='".$iid. "'
				AND `userid`='".$userid. "'
				AND `status` ='1'
			");

			if($ask){
				$ask['content'] = iSecurity::html_decode($ask['content']);
				$ask['code'] = 1;
				iUI::json($ask);
			}
		}
		iUI::code(0, 'ask:error', $id, 'json');
	}

	public function ACTION_add_question() {
		user::get_cookie() OR iUI::code(0, 'iCMS:!login', 0, 'json');

		$seccode = iSecurity::escapeStr($_POST['seccode']);
		iSeccode::check($seccode, true) OR iUI::code(0, 'iCMS:seccode:error', 'seccode', 'json');

		$id      = (int) $_POST['id'];
		$cid     = (int) $_POST['cid'];
		$_cid    = (int) $_POST['_cid'];
		$_tags   = iSecurity::escapeStr($_POST['_tags']);
		$tags    = (array)$_POST['tags'];
		$tags    = implode(',', $tags);
		$tags    = iSecurity::escapeStr($tags);
		$auth    = iSecurity::escapeStr($_POST['auth']);
		$title   = iSecurity::escapeStr($_POST['title']);
		$content = iSecurity::escapeStr($_POST['content']);

		$title OR iUI::code(0, 'ask:empty_title', 0, 'json');
		$content OR iUI::code(0, 'ask:empty_content', 0, 'json');

		$fwd = iPHP::callback(array("filterApp","run"),array(&$title),false);
		$fwd && iUI::code(0, 'ask:filter_title', 0, 'json');

		$fwd = iPHP::callback(array("filterApp","run"),array(&$tags),false);
		$fwd && iUI::code(0, 'ask:filter_tags', 0, 'json');

		$fwd = iPHP::callback(array("filterApp","run"),array(&$content),false);
		$fwd && iUI::code(0, 'ask:filter_content', 0, 'json');

		$_userid  = auth_decode($auth);
		if($_userid!=user::$userid){
			iUI::code(0, 'ask:user_auth_error', 0, 'json');
		}
		tag::$appid = iCMS_APP_ASK;

		if($id){
			iDB::update('ask', array(
				'cid'     => $cid,
				'title'   => $title,
				'tags'    => $tags,
				'pubdate' => $_SERVER['REQUEST_TIME'],
			),array(
				'id'    => $id,
				'userid' => user::$userid
			));

			iDB::update('ask_data', array(
				'title' => $title,
				'content' => $content,
				'pubdate' => $_SERVER['REQUEST_TIME'],
			),array(
				'rootid' => '0',
				'iid'    => $id,
				'userid' => user::$userid
			));

			($tags||$_tags) && tag::diff($tags,$_tags,user::$userid,$id,$cid);

			iMap::init('category',iCMS_APP_ASK,'cid');

            if($_cid!=$cid) {
                iMap::diff($cid,$_cid,$id);
                categoryAdmincp::update_count($_cid,'-');
                categoryAdmincp::update_count($cid);
            }

			return iUI::code(1, 'ask:question_success', '', 'json');
		}

		$pubdate  = $postime = $lastpost = $_SERVER['REQUEST_TIME'];
		$ip       = iPHP::get_ip();
		$userid   = $lastpostuid = user::$userid;
		$username = $lastposter  = user::$nickname;
		$status   = self::$config['examine'] ? '0' : '1';
		$good     = '0';
		$bad      = '0';
		$replies  = '0';
		$rootid   = '0';
		$postype  = '0';
		$sortnum  = $weight = $_SERVER['REQUEST_TIME'];

		$fields = array(
			'cid', 'pid', 'title', 'tags',
			'username', 'userid', 'pubdate', 'postime', 'lastpost',
			'lastpostuid', 'lastposter', 'replies', 'ip',
			'sortnum','weight','postype', 'status'
		);
		$data   = compact($fields);
		$iid    = iDB::insert('ask', $data);

        iMap::init('category',iCMS_APP_ASK,'cid');
        iMap::add($cid,$iid);
		categoryAdmincp::update_count($cid);

		$tags && tag::add($tags,$userid,$iid,$cid);

		$fields = array('iid','rootid','username', 'userid', 'title', 'content', 'good', 'bad', 'pubdate', 'postime', 'ip', 'status');
		$data   = compact($fields);
		$id     = iDB::insert('ask_data', $data);
		self::$config['examine'] && iUI::code(0, 'ask:examine', $id, 'json');

		$ask = iDB::row("
			SELECT * FROM `#iCMS@__ask`
			WHERE `id`='".$iid. "'
			AND `status` ='1' LIMIT 1;",
		ARRAY_A);
		$category = categoryApp::get_cahce_cid($cid);

		$iurl = iURL::get('ask', array($ask, $category));

		iUI::code(1, 'ask:question_success', $iurl->url, 'json');
	}
	public function ACTION_get_answer() {
		user::get_cookie() OR iUI::code(0, 'iCMS:!login', 0, 'json');

		$id     = (int) $_POST['id'];
		$iid    = (int) $_POST['iid'];
		$userid = (int) $_POST['userid'];

		if($userid==user::$userid){
			$ask = iDB::row("
				SELECT `iid`,`content` FROM `#iCMS@__ask_data`
				WHERE `id`='".$id. "'
				AND `iid`='".$iid. "'
				AND `userid`='".$userid. "'
				AND `status` ='1' LIMIT 1;",
			ARRAY_A);
			if($ask){
				$ask['content'] = iSecurity::html_decode($ask['content']);
				$ask['code'] = 1;
				iUI::json($ask);
			}
		}
		iUI::code(0, 'ask:error', $id, 'json');
	}
	public function ACTION_add_answer() {
		user::get_cookie() OR iUI::code(0, 'iCMS:!login', 0, 'json');

		$seccode = iSecurity::escapeStr($_POST['seccode']);
		iSeccode::check($seccode, true) OR iUI::code(0, 'iCMS:seccode:error', 'seccode', 'json');

		$id      = (int) $_POST['id'];
		$iid     = (int) $_POST['iid'];
		$auth    = iSecurity::escapeStr($_POST['auth']);
		$title   = iSecurity::escapeStr($_POST['title']);
		$content = iSecurity::escapeStr($_POST['content']);

		$iid OR iUI::code(0, 'ask:empty_iid', 0, 'json');
		$content OR iUI::code(0, 'ask:empty_content', 0, 'json');

		$fwd = iPHP::callback(array("filterApp","run"),array(&$content),false);
		$fwd && iUI::code(0, 'ask:filter_content', 0, 'json');

		$_userid  = auth_decode($auth);
		if($_userid!=user::$userid){
			iUI::code(0, 'ask:user_auth_error', 0, 'json');
		}
		if($id){
			iDB::update('ask_data', array(
				'content' => $content,
				'pubdate' => $_SERVER['REQUEST_TIME'],
			),array(
				'id'     => $id,
				'iid'    => $iid,
				'userid' => user::$userid
			));
			return iUI::code(1, 'ask:answer_success', $id, 'json');
		}

		$appid OR $appid = iCMS_APP_ASK;
		$pubdate  = $postime = $lastpost = $_SERVER['REQUEST_TIME'];
		$ip       = iPHP::get_ip();
		$userid   = user::$userid;
		$username = $lastposter = user::$nickname;
		$status   = self::$config['examine'] ? '0' : '1';
		$good     = '0';
		$bad      = '0';
		$rootid   = $iid;

		$fields = array('iid','rootid','username', 'userid', 'title', 'content', 'good', 'bad', 'pubdate', 'postime', 'ip', 'status');
		$data   = compact($fields);
		$id     = iDB::insert('ask_data', $data);

		iDB::update('ask', array(
			'lastpost'    => $lastpost,
			'lastpostuid' => $userid,
			'lastposter'  => $lastposter
		),array('id'=>$iid));

		askApp::update_count($iid,'+');

		$ask = iDB::row("
			SELECT * FROM `#iCMS@__ask`
			WHERE `id`='".$iid. "'
			AND `status` ='1' LIMIT 1;
		");
		//获取@列表
		$at_user_list = userApp::at_user_list($content);
		if($userid!=$ask->userid || $at_user_list){
			$category = categoryApp::get_cahce_cid($ask->cid);
			$iurl = iURL::get('ask', array($ask, $category));
			$link = '<a href="'.$iurl->url.'#'.$id.'" target="_blank">《'.$ask->title.'》</a>';
			$U = user::info($userid, $username);

			if($at_user_list){
				foreach ($at_user_list as $at_uid => $at_nk) {
					if($userid!=$at_uid){
						message::remind(array(
							"receiv_uid"  => $at_uid,
							"receiv_name" => $at_nk,
							"content"     => $U['link'].' 在问题'.$link.'提到了你'
						));
					}
				}
			}

			if($userid!=$ask->userid){
				message::remind(array(
					"receiv_uid"  => $ask->userid,
					"receiv_name" => $ask->username,
					"content"     => $U['link'].' 回复了你的问题'.$link
				));
			}
		}

		self::$config['examine'] && iUI::code(0, 'ask:examine', $id, 'json');

		iUI::code(1, 'ask:answer_success', $id, 'json');
	}

	public function ask($fvar,$page = 1,$field='id', $tpl = true) {
        $ask = $this->get_data($fvar,$field);
        if ($ask === false) return false;
        $id = $ask['id'];

		$vars = array(
			'tag'     => true,
		);

		$ask = $this->value($ask, $vars, $page, $tpl);

		if ($ask === false) {
			return false;
		}

       	self::custom_data($ask,$vars);
		self::hooked($ask);
		askFunc::$interface['iid'] = $id;

		return self::render($ask,$tpl);
	}
	public static function value($ask, $vars = array(), $page = 1, $tpl = false) {
		$category = array();
		$process = self::process($tpl,$category,$ask);
		if ($process === false) return false;

        if($category['mode'] && stripos($ask['url'], '.php?')===false){
            iURL::page_url($ask['iurl']);
        }

		$vars['tag'] && tagApp::get_array($ask,$category['name'],'tags');
		$ask['user']   = user::info($ask['userid'], $ask['username']);
		$ask['poster'] = user::info($ask['lastpostuid'], $ask['lastposter']);
		$ask['content'] = userApp::at_content($ask['content']);

        apps_common::init($ask,'ask',$vars);
        apps_common::link();
        apps_common::comment();
        apps_common::pic();
        apps_common::hits();
        apps_common::param();

		$ask['iid'] = $ask['id'];
		$ask['param'] += array(
			"rootid"   => $ask['rootid'],
			"userid"   => $ask['userid'],
			"username" => $ask['username'],
		);
		return $ask;
	}
    public static function update_count($id,$math='+'){
        $math=='-' && $sql = " AND `replies`>0";
        iDB::query("UPDATE `#iCMS@__ask` SET `replies` = replies".$math."1 WHERE `id` ='$id' {$sql}");
    }
}
