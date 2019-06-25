<?php

class video_resourceAdmincp extends videoAdmincp{
    public function __construct() {
        parent::__construct();
        $this->id       = (int)$_GET['id'];
        $this->video_id = (int)$_GET['video_id'];
    }
    public function do_add() {
        menu::set('add','video');
        $video = iDB::row("SELECT * FROM `#iCMS@__video` WHERE `id` ='".$this->video_id."'",ARRAY_A);
        $rs    = iDB::row("SELECT * FROM `#iCMS@__video_resource` WHERE `id` ='".$this->id."'",ARRAY_A);
        if(empty($rs)){
            $rs = array(
                'video_id' =>$this->video_id,
                'status'  =>1,
                'sortnum' =>time(),
            );
        }
        $fromArray = propApp::field('from','video');
        include admincp::view("resource.add");
    }
    public function do_del($id = NULL, $dialog = true) {
        $id === NULL && $id = $this->id;
        $id OR iUI::alert("请选择要删除的资源");
        $video_id = $this->video_id;
        iDB::query("DELETE FROM `#iCMS@__video_resource` WHERE id='$id' AND `video_id`='$video_id'");
        iUI::success('删除完成','js:1');
    }
    public function do_manage($stype=null) {
        menu::set('manage','video');
        $video = video::row($this->video_id);
        videoApp::$DATA['video'] = $video;

        $video_id    = $this->video_id;
        $sql        = "WHERE `video_id`='$video_id'";
        $_GET['from'] && $sql .= " AND `from`='".$_GET['from']."'";
        isset($_GET['vip']) && $sql .= " AND `vip`='".$_GET['vip']."'";

        $maxperpage = 100;
        $total      = iPagination::totalCache("SELECT count(id) FROM `#iCMS@__video_resource` {$sql}","G");
        iUI::pagenav($total,$maxperpage,"条");
        $rs = iDB::all("SELECT ".video::resource_fields(false,true)." FROM `#iCMS@__video_resource` {$sql} ORDER BY `sortnum` DESC LIMIT ".iPagination::$offset." , {$maxperpage}");
        $_count = count($rs);

        $from = iDB::value("SELECT `from` FROM `#iCMS@__video` WHERE `id`='$video_id' ");
        $fromArray = videoApp::fromArray($from);
        include admincp::view("resource.manage");
    }
    public function do_save($post=null,$dialog=true) {
        $post===null && $post=$_POST;

        $id       = (int)$post['id'];
        $video_id = (int)$post['video_id'];
        $title    = iSecurity::escapeStr($post['title']);
        $from     = iSecurity::escapeStr($post['from']);
        $sortnum  = (int)$post['sortnum'];
        $vip      = (int)$post['vip'];
        $type     = (int)$post['type'];
        $addtime  = str2time($post['addtime']);
        $size     = (int)$post['size'];
        $status   = (int)$post['status'];
        $src      = trim($post['src']);

        if($dialog){
            if($id) $title OR iUI::alert('Заголовок не может быть пустым!');
            $src OR iUI::alert('数据不能为空!');
        }else{
            if($id && empty($title)) return false;
            if(empty($src)) return false;
        }

        $fields = video::resource_fields($id);
        $data   = compact ($fields);
        $data['update'] = time();
        empty($data['addtime']) && $data['addtime'] = time();
        empty($data['sortnum']) && $data['sortnum'] = time();

        if($id){
            iDB::update('video_resource', $data, array('id'=>$id));
            $msg = "资源更新完成!";
        }else{
            $srcArray = explode("\n", $src);
            $id = array();
            foreach ($srcArray as $key => $value) {
                if(strpos($value, '$')===false){
                     $data['src'] = trim($value);
                }else{
                    list($_title,$src,$size) = explode('$', trim($value));
                    $data['src']   = trim($src);
                    $data['size']  = trim($size);
                }
                $data['title'] = trim($_title);
                $title && $data['title'] = sprintf($title,($key+1));
                empty($data['title']) && $data['title'] = sprintf('第%d集',($key+1));

                $check = iDB::value("
                    SELECT `id`
                    FROM `#iCMS@__video_resource`
                    WHERE `title` ='".$data['title']."'
                    AND `video_id` ='$video_id'
                    AND `from` ='$from'
                    AND `type` ='$type'
                ");
                if($dialog){
                    $check && iUI::alert('该资源已经存在');
                }else{
                    if($check) continue;
                }
                $id[] = iDB::insert('video_resource',$data);
            }

            $msg = "新资源添加完成!";
        }
        $this->setFrom($video_id);

        if($dialog){
            iUI::success($msg,'js:1');
        }else{
            return $id;
        }
    }
    public static function setFrom($video_id=''){
        $all = iDB::all("SELECT DISTINCT `from`,`type` FROM `#iCMS@__video_resource` WHERE `video_id`='$video_id'");
        if($all){
            $fromArray = array_column($all, 'type','from');
            $from = addslashes(json_encode($fromArray));
        }
        iDB::update('video', array('from'=>$from), array('id'=>$video_id));
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
