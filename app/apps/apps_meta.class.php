<?php


class apps_meta {
    public static $data = null;
    public static function table($app,$check=true){
        if(is_numeric($app)){
            $a   = apps::get($app);
            $app = $a['app'];
        }
        empty($app) && trigger_error('APP name is empty!',E_USER_ERROR);
        $table = $app.'_meta';

        if($check){
            if(iCMS::$config['apps:meta'][$table]){
                return $table;
            }else{
                return false;
            }
        }else{
            return $table;
        }
    }
    public static function data($app,$ids){
        $table = self::table($app);

        if(empty($ids)||empty($table)) return array();

        list($ids,$is_multi)  = iSQL::multi_var($ids);
        $sql  = iSQL::in($ids,'id',false,true);
        $data = array();
        $rs   = iDB::all("SELECT * FROM `#iCMS@__{$table}` where {$sql}");
        if($rs){
            $_count = count($rs);
            for ($i=0; $i < $_count; $i++) {
                $data[$rs[$i]['id']]['meta'] = json_decode($rs[$i]['data'],true);
            }
            $is_multi OR $data = $data[$ids];
        }

        if(empty($data)){
            return;
        }
        return $data;
    }
    public static function post($pkey='metadata',$out='json'){
        $metadata = iSecurity::escapeStr($_POST[$pkey]);
        if($metadata){
            $_metadata = array();
            foreach($metadata AS $mdk=>$md){
                if(is_array($md)){
                    if($md['name'] && empty($md['key'])){
                        $md['key'] = strtolower(iPinyin::get($md['name']));
                    }
                    preg_match("/^[a-zA-Z0-9_\-\.]+$/",$md['key']) OR iUI::alert('字段名不能为空,Может состоять только из английских букв, цифр или символов _-');
                    $md['key'] = trim($md['key']);
                    $_metadata[$md['key']] = $md;
                }else{
                    $_metadata[$mdk] = array('name'=>$mdk,'key'=>$mdk,'value'=>$md);
                }
            }
            if($out=='json'){
                $metadata = addslashes(cnjson_encode($_metadata));
            }else{
                $metadata = $_metadata;
            }
        }
        return $metadata;
    }
    public static function table_array($app){
        $table = self::create_table($app);
        return array($table=>array($table,'id',null,'动态属性'));
    }
    public static function create_table($app,$create=true){
        $table = self::table($app,false);

        if($create && !iDB::check_table($table)){
            iDB::query("
                CREATE TABLE `#iCMS@__{$table}` (
                  `id` int(10) unsigned NOT NULL,
                  `data` mediumtext NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=".iPHP_DB_CHARSET
            );
            self::config($table);
        }
        return $table;
    }
    public static function toArray($data,$index=true){
        is_array($data) OR $data = json_decode($data,true);

        if(!$index) return $data;

        $array = array();
        foreach ($data as $key => $value) {
            $array[$value['key']] = $value;
        }
        return $array;
    }
    public static function get($app,$id,$index=true){
        $table = self::table($app);
        if(empty($table)) return false;

        $json = iDB::value("SELECT `data` FROM `#iCMS@__{$table}` where `id` = '$id'");
        $json && self::$data = self::toArray($json,$index);
    }
    public static function save($app,$id,$data=null){
        $data===null && $data = self::post();
        if($data){
            $table = self::create_table($app);
            $check = iDB::value("SELECT `id` FROM `#iCMS@__{$table}` where `id` = '$id'");
            if($check){
                iDB::update($table, array('data'=>$data), array('id'=>$id));
            }else{
                iDB::insert($table,array('id'=>$id,'data'=>$data));
            }
        }
    }

    public static function config($table){
        $config  = config::get(iCMS_APP_APPS,'apps:meta');
        $config[$table] = 1;
        $_POST['config'] = $config;
        config::save(iCMS_APP_APPS,'apps:meta',null,false);
    }
    public static function cache(){
        $rs = iDB::all("SHOW TABLE STATUS FROM `" . iPHP_DB_NAME . "` WHERE ENGINE IS NOT NULL;");
        $config = array();
        foreach ($rs as $key => $value) {
            if(stristr($value['Name'], '_meta') !== FALSE) {
                $name = str_replace(iPHP_DB_PREFIX, '', $value['Name']);
                $config[$name] = 1;
            }
        }
        $_POST['config'] = $config;
        config::save(iCMS_APP_APPS,'apps:meta',null,false);
    }
}
