<?php

class topic {
    public static function fields($id=0){
        $fields  = array('cid', 'scid','tcid','pid',
            'title', 'stitle','keywords', 'tags', 'description','source',
            'author', 'editor', 'userid',
            'haspic','pic','bpic','mpic','spic', 'picdata',
            'related', 'pubdate', 'url','clink',
            'hits','hits_today','hits_yday','hits_week','hits_month','favorite','comments', 'good', 'bad',
            'sortnum','weight', 'postype', 'creative','tpl','status');

        if(empty($id)){ //新增
            $_fields = array('postime');
            $fields  = array_merge ($fields,$_fields);
        }

        return $fields;
    }

    public static function count_sql($sql=''){
        return "SELECT count(*) FROM `#iCMS@__topic` {$sql}";
    }
    public static function check($value,$id=0,$field='title'){
        $sql = "SELECT `id` FROM `#iCMS@__topic` where `{$field}` = '$value'";
        $id && $sql.=" AND `id` !='$id'";
        return iDB::value($sql);
    }

    public static function value($field='id',$id=0){
        if(empty($id)){
            return;
        }
        return iDB::value("SELECT {$field} FROM `#iCMS@__topic` WHERE `id`='$id';");
    }
    public static function row($id=0,$field='*',$sql=''){
        return iDB::row("SELECT {$field} FROM `#iCMS@__topic` WHERE `id`='$id' {$sql} LIMIT 1;",ARRAY_A);
    }
    public static function data($id=0,$data_id=0,$userid=0){
        $userid && $sql = " AND `userid`='$userid'";
        $rs    = iDB::row("SELECT * FROM `#iCMS@__topic` WHERE `id`='$id' {$sql} LIMIT 1;",ARRAY_A);
        if($rs){
            $tid   = $rs['id'];
            $data_sql = "SELECT * FROM `#iCMS@__topic_data` WHERE `tid`='$tid'";
            $data_id && $data_sql.= " AND `id`='{$data_id}'";
            $data  = iDB::row($data_sql,ARRAY_A);
            $data['body'] = json_decode($data['body'],true);
        }
        return array($rs,(array)$data);
    }
    public static function body($id=0){
        $body = iDB::value("SELECT body FROM `#iCMS@__topic_data` WHERE tid='$id'");
        return $body;
    }

    public static function batch($data,$ids){
        if(empty($ids)){
            return;
        }
        foreach ( array_keys($data) as $k ){
            $bits[] = "`$k` = '$data[$k]'";
        }
        iDB::query("UPDATE `#iCMS@__topic` SET " . implode( ', ', $bits ) . " WHERE `id` IN ($ids)");
    }
    public static function insert($data){
        return iDB::insert('topic',$data);
    }
    public static function update($data,$where){
        return iDB::update('topic',$data,$where);
    }
// --------------------------------------------------
    public static function data_fields($update=false){
        $fields  = array('body');
        $update OR $fields  = array_merge ($fields,array('tid'));
        return $fields;
    }
    public static function data_insert($data){
        return iDB::insert('topic_data',$data);
    }
    public static function data_update($data,$where){
        return iDB::update('topic_data',$data,$where);
    }

    public static function del($id){
        iDB::query("DELETE FROM `#iCMS@__topic` WHERE id='$id'");
    }
    public static function del_data($id,$f='tid'){
        iDB::query("DELETE FROM `#iCMS@__topic_data` WHERE `$f`='$id'");
    }
}

