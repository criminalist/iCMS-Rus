<?php


defined('iPHP') OR exit('Oops, something went wrong');
define('iPHP_WAF_CSRF', true);

class spider_postAdmincp {
	public function __construct() {
		spiderAdmincp::init($this);
	}
	public function do_batch() {
		list($idArray,$ids,$batch) = iUI::get_batch_args();
		switch ($batch) {
		case 'del':
			iDB::query("delete from `#iCMS@__spider_post` where `id` IN($ids);");
		break;
		default:
			iUI::alert('Ошибка параметра!', 'js:1');
		}
		iUI::success('Все успешно удалено!', 'js:1');
	}
	/**
	 * [发布模块管理]
	 * @return [type] [description]
	 */
	public function do_manage() {
		if ($_GET['keywords']) {
			$sql = " WHERE CONCAT(name,app,post) REGEXP '{$_GET['keywords']}'";
		}
		list($orderby,$orderby_option) = get_orderby();
		$maxperpage = $_GET['perpage'] > 0 ? (int) $_GET['perpage'] : 20;
		$total = iPagination::totalCache( "SELECT count(*) FROM `#iCMS@__spider_post` {$sql}", "G");
		iUI::pagenav($total, $maxperpage, "个模块");
		$rs = iDB::all("SELECT * FROM `#iCMS@__spider_post` {$sql} order by {$orderby} LIMIT " . iPagination::$offset . " , {$maxperpage}");
		$_count = count($rs);
		include admincp::view("post.manage");
	}
	/**
	 * [复制发布模块]
	 * @return [type] [description]
	 */
	public function do_copy() {
		iDB::query("INSERT INTO `#iCMS@__spider_post` (`name`, `app`, `post`, `fun`)
 SELECT `name`, `app`, `post`, `fun` FROM `#iCMS@__spider_post` WHERE id = '$this->poid'");
		$poid = iDB::$insert_id;
		iUI::success('复制完成,编辑此规则', 'url:' . APP_URI . '&do=add&poid=' . $poid);
	}
	/**
	 * [删除发布模块]
	 * @return [type] [description]
	 */
	public function do_del() {
		$this->poid OR iUI::alert("Выберите элемент для удаления");
		iDB::query("delete from `#iCMS@__spider_post` where `id` = '$this->poid';");
		iUI::success('Успешно удалено', 'js:1');
	}
	/**
	 * [添加发布模块]
	 * @return [type] [description]
	 */
	public function do_add() {
		$this->poid && $rs = iDB::row("SELECT * FROM `#iCMS@__spider_post` WHERE `id`='$this->poid' LIMIT 1;", ARRAY_A);
		include admincp::view("post.add");
	}
	/**
	 * [保存发布模块]
	 * @return [type] [description]
	 */
	public function do_save() {
		$id = (int) $_POST['id'];
		$name = trim($_POST['name']);
		$app = iSecurity::escapeStr($_POST['app']);
		$post = trim($_POST['post']);
		$fun = trim($_POST['fun']);

		$fields = array('name', 'app', 'fun', 'post');
		$data = compact($fields);
		if ($id) {
			iDB::update('spider_post', $data, array('id' => $id));
		} else {
			iDB::insert('spider_post', $data);
		}
		iUI::success('Успешно сохранено', 'url:' . APP_URI . '&do=manage');
	}

}
