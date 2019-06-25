<?php

class videoApp extends appsApp {
    public static $typeMap = array(array('play','Онлайн проигрыватель'),array('down','Скачать'));

    public function __construct() {
        parent::__construct('video');
        $this->add_method('story,play,down');
    }

	public function video($fvar,$page = 1,$field='id', $tpl = true) {
        $video = $this->get_data($fvar,$field);
        if ($video === false) return false;
        $id = $video['id'];


        $video_data = iDB::row("
            SELECT photo,body
            FROM `#iCMS@__video_data`
            WHERE `video_id`='" . (int) $id . "'
        ", ARRAY_A);

		$vars = array(
            'tag'       => true,
            'user'      => true,
            '_actor'    => true,
            '_director' => true,
            '_attrs'    => true,
            '_genre'    => true,
		);

		$video = $this->value($video, $video_data, $vars, $page, $tpl);
		if ($video === false) return false;
        unset($video_data);
		self::custom_data($video,$vars);
		self::hooked($video);
		return self::render($video,$tpl);
	}
    public function do_play($tpl=null){
        $id===null && $id = (int)$_GET['id'];
        $player = iDB::row("SELECT * FROM `#iCMS@__video_resource` WHERE `id` ='".$id."'",ARRAY_A);
        $player = self::res_value($player);
        $tpl===null && $tpl = self::$DATA['category']['template']['video:play'];
        $player['param'] = array(
            'id'       =>$player['id'],
            'video_id' =>$player['video_id'],
            'src'      =>$player['src'],
            'url'      =>$player['url'],
            'title'    =>$player['title'],
            'vip'      =>$player['vip'],
        );
        $player['js'] = iCMS_PUBLIC_URL.'/video/'.$player['from'].'.js';
        $player['iframe'] = iCMS_PUBLIC_URL.'/video/'.$player['from'].'.html';
        self::unData();
        return self::render($player,$tpl,'player','video');
    }
    public function do_story($tpl=null){
        $id===null && $id = (int)$_GET['id'];
        $story = iDB::row("SELECT * FROM `#iCMS@__video_story` WHERE `id` ='".$id."'",ARRAY_A);
        $story = self::story_value($story);
        $tpl===null && $tpl = self::$DATA['category']['template']['video:story'];
        self::unData();
        return self::render($story,$tpl,'story','video');
    }
	public static function value($video, $data = "", $vars = array(), $page = 1, $tpl = false) {
        $category = array();
        $process = self::process($tpl,$category,$video);
        if ($process === false) return false;

		if ($data) {
            $video['photo'] = filesApp::get_content_pics($data['photo']);
            $video['body'] = $data['body'];
		}
        $video['from_array'] = self::fromArray($video['from']);

        $vars['tag']      && tagApp::get_array($video,$category['name'],'tags');
        $vars['_actor']   && tagApp::get_array($video,null,'actor');
        $vars['_director']&& tagApp::get_array($video,null,'director');
        $vars['_attrs']   && tagApp::get_array($video,null,'attrs');
        $vars['_genre']   && tagApp::get_array($video,null,'genre');

        $video['score'] && $video['score']  = sprintf("%01.1f", $video['score']/10);
        $video['scores'] = json_decode($video['scores'],true);

        apps_common::init($video,'video',$vars);
        apps_common::link();
        apps_common::user();
        apps_common::comment();
        apps_common::pic();
        apps_common::hits();
        apps_common::param();

		return $video;
	}
    public static function fromArray($json){
        $fromArray = array();
        if($json){
            $array = json_decode($json,true);
            $propArray = propApp::field('from','video');
            foreach ($array as $from => $type) {
                $tkey = self::$typeMap[$type][0];
                $fromArray[$tkey][] = (array)$propArray[$from]+(array)self::$typeMap[$type];
            }
        }
        return $fromArray;
    }
    public static function res_value($res){
        if(is_array($res)){
            $video_id = $res['video_id'];
            if(empty(self::$DATA['video'])){
                self::$DATA['video'] = iView::get_vars('video');
                if(empty(self::$DATA['video'])){
                    $self = new self();
                    self::$DATA['video'] = $self->video($video_id,1,'id',null);
                }
            }
            if(empty(self::$DATA['category'])){
                self::$DATA['category'] = categoryApp::get_cache_cid(self::$DATA['video']['cid']);
            }

            iURL::$data['video'] = self::$DATA['video'];
            $res['type']==0 && $type = 'play';
            $res['type']==1 && $type = 'down';
            $res['iurl'] = (array)iURL::get('video:'.$type,array($res,self::$DATA['category']));
            $res['url']  = $res['iurl']['url'];

            if(empty(self::$DATA['prop'])){
                self::$DATA['prop'] = propApp::field('from','video');
            }
            $res['from_array'] = self::$DATA['prop'][$res['from']];

        }
        return $res;
    }
    public static function story_value($story){
        if(is_array($story)){
            $video_id = $story['video_id'];
            if(empty(self::$DATA['video'])){
                self::$DATA['video'] = iView::get_vars('video');
                if(empty(self::$DATA['video'])){
                    $self = new self();
                    self::$DATA['video'] = $self->video($video_id,1,'id',null);
                }
            }
            if(empty(self::$DATA['category'])){
                self::$DATA['category'] = categoryApp::get_cache_cid(self::$DATA['video']['cid']);
            }
            iURL::$data['video'] = self::$DATA['video'];
            $story['iurl'] = (array)iURL::get('video:story',array($story,self::$DATA['category']));
            $story['url']  = $story['iurl']['href'];
            $story['link'] = '<a href="'.$story['url'].'" class="video_story_link" target="_blank">'.$story['title'].'</a>';
        }
        return $story;
    }
    public static function data($ids=0){
        return apps_common::data($ids,'video','video_id');
    }
}
