<?php

class video_storyAdmincp extends videoAdmincp{
    public function __construct() {
        parent::__construct();
        $this->id       = (int)$_GET['id'];
        $this->video_id = (int)$_GET['video_id'];
    }
    public function do_add() {
        menu::set('add','video');
        $video = iDB::row("SELECT * FROM `#iCMS@__video` WHERE `id` ='".$this->video_id."'",ARRAY_A);
        $rs    = iDB::row("SELECT * FROM `#iCMS@__video_story` WHERE `id` ='".$this->id."'",ARRAY_A);
        if(empty($rs)){
            $rs = array(
                'video_id' =>$this->video_id,
                'status'  =>1,
                'sortnum' =>time(),
            );
        }
        include admincp::view("story.add");
    }
    public function do_del($id = NULL, $dialog = true) {
        $id === NULL && $id = $this->id;
        $id OR iUI::alert("请选择要删除的集情");
        $video_id = $this->video_id;
        iDB::query("DELETE FROM `#iCMS@__video_story` WHERE id='$id' AND `video_id`='$video_id'");
        iUI::success('删除完成','js:1');
    }
    public function do_manage($stype=null) {
        menu::set('manage','video');
        $video = video::row($this->book_id);
        videoApp::$DATA['video'] = $video;

        $video_id    = $this->video_id;
        $sql        = "WHERE `video_id`='$video_id'";
        $maxperpage = 100;
        $total      = iPagination::totalCache("SELECT count(id) FROM `#iCMS@__video_story` {$sql}","G");
        iUI::pagenav($total,$maxperpage,"集");
        $rs = iDB::all("SELECT ".video::story_fields(false,false,true)." FROM `#iCMS@__video_story` {$sql} ORDER BY `sortnum` DESC LIMIT ".iPagination::$offset." , {$maxperpage}");
        $_count = count($rs);
        include admincp::view("story.manage");
    }
    public function do_save($post=null,$dialog=true) {
        $post===null && $post=$_POST;

        $id       = (int)$post['id'];
        $video_id = (int)$post['video_id'];
        $title    = iSecurity::escapeStr($post['title']);
        $sortnum  = (int)$post['sortnum'];
        $vip      = (int)$post['vip'];
        $addtime  = str2time($post['addtime']);
        $status   = (int)$post['status'];
        $content  = $post['content'];

        if($dialog){
            $title OR iUI::alert('Заголовок не может быть пустым!');
            $content OR iUI::alert('内容不能为空!');
        }else{
            if(empty($title)) return false;
            if(empty($content)) return false;
        }

        $fields = video::story_fields($id);
        $data   = compact ($fields);

        if($id){
            iDB::update('video_story', $data, array('id'=>$id));
            $msg = "剧情更新完成!";
        }else{
            iDB::value("
                SELECT `id`
                FROM `#iCMS@__video_story`
                WHERE `title` ='$title' AND `video_id` ='$video_id'"
            ) && iUI::alert('该剧情已经存在');
            $id = iDB::insert('video_story',$data);
            iDB::update('video_story', array('sortnum'=>time()), array('id'=>$id));
            $msg = "新剧情添加完成!";
        }
        if($dialog){
            iUI::success($msg,'js:1');
        }else{
            return $chapter_id;
        }
    }
    public static function save($post,$video_id){
        if($post){
            $self = new self();
            if(is_array($post[0])){
                foreach ($post as $key => $value) {
                    $value['video_id'] = $video_id;
                    $self->do_save($value,false);
                }
            }else{
                $post['video_id'] = $video_id;
                $self->do_save($post,false);
            }
        }
    }
}
