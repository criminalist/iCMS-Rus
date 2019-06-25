<?php

class video {
    public static function fields($id=0){
        $fields  = array(
            'cid', 'scid','ucid','pid',
            'title', 'stitle','keywords', 'tags', 'description',
            'editor', 'userid',
            'haspic','pic','bpic','mpic','spic',
            'pubdate', 'url','clink',
            'hits','hits_today','hits_yday','hits_week','hits_month','favorite','comments', 'good', 'bad',
            'sortnum','weight', 'postype', 'creative', 'tpl','status',
            'alias','enname','star','remark','genre','actor','director','attrs','year','version','language',
            'area','cycle','ser','release','time','total','company','tv','score','scorenum','scores'
        );

        if(empty($id)){ //新增
            $_fields = array('mobile','postime');
            $fields  = array_merge ($fields,$_fields);
        }

        return $fields;
    }

    public static function count_sql($sql=''){
        return "SELECT count(*) FROM `#iCMS@__video` {$sql}";
    }

    public static function check($value,$id=0,$field='title'){
        $sql = "SELECT `id` FROM `#iCMS@__video` where `{$field}` = '$value'";
        $id && $sql.=" AND `id` !='$id'";
        return iDB::value($sql);
    }

    public static function value($field='id',$id=0){
        if(empty($id)){
            return;
        }
        return iDB::value("SELECT {$field} FROM `#iCMS@__video` WHERE `id`='$id';");
    }
    public static function row($id=0,$field='*',$sql=''){
        return iDB::row("SELECT {$field} FROM `#iCMS@__video` WHERE `id`='$id' {$sql} LIMIT 1;",ARRAY_A);
    }
    public static function data($id=0,$sdid=0,$userid=0){
        $userid && $sql = " AND `userid`='$userid'";
        $rs    = iDB::row("SELECT * FROM `#iCMS@__video` WHERE `id`='$id' {$sql} LIMIT 1;",ARRAY_A);
        if($rs){
            $video_id   = $rs['id'];
            $sdsql = $sdid?" AND `id`='{$sdid}'":'';
            $sdrs  = iDB::row("SELECT * FROM `#iCMS@__video_data` WHERE `video_id`='$video_id' {$sdsql}",ARRAY_A);
        }
        return array($rs,$sdrs);
    }
    public static function resource($id=0){
        $all = iDB::all("SELECT * FROM `#iCMS@__video_resource` WHERE `video_id`='$id'");
        foreach ($all as $key => $value) {
            $resource[$value['type']][$value['id']] = $value;
            $resource['ids'][] = $value['id'];
        }
        return $resource;
    }

    public static function body($id=0){
        return iDB::value("SELECT * FROM `#iCMS@__video_data` WHERE `video_id`='$id'");
    }

    public static function batch($data,$ids){
        if(empty($ids)){
            return;
        }
        foreach ( array_keys($data) as $k ){
            $bits[] = "`$k` = '$data[$k]'";
        }
        iDB::query("UPDATE `#iCMS@__video` SET " . implode( ', ', $bits ) . " WHERE `id` IN ($ids)");
    }
    public static function insert($data){
        return iDB::insert('video',$data);
    }
    public static function update($data,$where){
        return iDB::update('video',$data,$where);
    }
// --------------------------------------------------
    public static function data_fields($update=false){
        $fields  = array('photo', 'body');
        $update OR $fields  = array_merge ($fields,array('video_id'));
        return $fields;
    }
    public static function data_insert($data){
        return iDB::insert('video_data',$data);
    }
    public static function data_update($data,$where){
        return iDB::update('video_data',$data,$where);
    }

    public static function del($id){
        iDB::query("DELETE FROM `#iCMS@__video` WHERE id='$id'");
    }
    public static function del_data($id){
        iDB::query("DELETE FROM `#iCMS@__video_data` WHERE `video_id`='$id'");
    }
    // --------------------------------------------------
    public static function story_fields($update=false,$content=true,$select=false){
        $fields  = array('video_id', 'title','addtime', 'sortnum', 'vip', 'status');
        $update OR $fields   = array_merge ($fields,array('id'));
        $content && $fields  = array_merge ($fields,array('content'));
        $select && $fields = '`'.implode('`,`', $fields).'`';
        return $fields;
    }
    public static function resource_fields($update=false,$select=false){
        $fields  = array('video_id', 'title', 'src', 'from', 'size', 'vip', 'sortnum', 'addtime', 'update', 'type', 'status');
        $update OR $fields   = array_merge ($fields,array('id'));
        $select && $fields = '`'.implode('`,`', $fields).'`';
        return $fields;
    }
}

