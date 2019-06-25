<?php

class topicApp extends appsApp {
    public function __construct() {
        parent::__construct('topic');
    }

	public function topic($fvar,$page = 1,$field='id', $tpl = true) {
        $topic = $this->get_data($fvar,$field);
        if ($topic === false) return false;
        $id = $topic['id'];

		$topic_data = iDB::row("
            SELECT `body`
            FROM `#iCMS@__topic_data`
            WHERE tid='" . (int) $id . "'
        ", ARRAY_A);

		$vars = array(
			'tag'  => true,
			'user' => true,
		);
		$topic = $this->value($topic, $topic_data, $vars, $page, $tpl);
        unset($topic_data);
		if ($topic === false) {
			return false;
		}

        self::custom_data($topic,$vars);
		self::hooked($topic);
		return self::render($topic,$tpl);
	}
	public static function value($topic, $data = "", $vars = array(), $page = 1, $tpl = false) {
        $category = array();
        $process = self::process($tpl,$category,$topic);
        if ($process === false) return false;

        $body = json_decode($data['body'],true);
        if($body){
            $topic['data'] = $body;
            foreach ($body as $key => $value) {
                if($value['data']){
                    $_data = array();
                    $_ids = array();
                    foreach ($value['data'] as $k => $v) {
                        list($_id,$_title) = explode('#@#', $v);
                        $_data[$_id] = $_title;
                        $_ids[] = $_id;
                    }
                    $topic['data'][$key]['data'] = $_data;
                    $topic['data'][$key]['ids'] = $_ids;
                }
                unset($_data,$_ids);
            }
            unset($body);
            // var_dump($topic['data']);
        }

		$vars['tag'] && tagApp::get_array($topic,$category['name'],'tags');

        apps_common::init($topic,'topic',$vars);
        apps_common::link();
        apps_common::text2link();
        apps_common::user();
        apps_common::comment();
        apps_common::pic();
        apps_common::hits();
        apps_common::param();
		return $topic;
	}

}
