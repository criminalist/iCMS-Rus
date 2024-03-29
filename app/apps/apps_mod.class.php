<?php


class apps_mod {
    const DATA_TABLE_NAME  = '_cdata';
    const DATA_PRIMARY_KEY = 'cdata_id';
    const DATA_UNION_KEY   = '_id';
    public static $base_fields_key = null;

    public static function get_data_table(&$array) {
        $data_table  = next($array);
        if($data_table){
            $primary = $data_table['primary'];
            if($primary==self::DATA_PRIMARY_KEY){
                return $data_table;
            }else{
                return self::get_data_table($array);
            }
        }else{
            return false;
        }
    }
    public static function data_table_name($name){
      return $name.self::DATA_TABLE_NAME;
    }
    public static function data_union_key($name){
      return $name.self::DATA_UNION_KEY;
    }
    public static function base_fields_array(){
      $sql = implode(",\n", self::base_fields_sql());
      preg_match_all("@`(.+)`\s(.+)\sDEFAULT\s'(.*?)'\sCOMMENT\s'(.+)'@", $sql, $matches);
      return $matches;
    }
    public static function base_fields_sql(){
        return array(
            'cid'        =>"`cid` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'id категории'",
            'ucid'       =>"`ucid` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'id пользовательских разделов'",
            'pid'        =>"`pid` VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'Свойства'",
            'title'      =>"`title` VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'Название'",
            'editor'     =>"`editor` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '编辑名或用户名'",
            'userid'     =>"`userid` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '用户ID'",
            'pubdate'    =>"`pubdate` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Время публикации'",
            'postime'    =>"`postime` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Время публикации'",
            'clink'      =>"`clink` VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'Пользовательская ссылка'",
            'tpl'        =>"`tpl` VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'Шаблон'",
            'hits'       =>"`hits` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Общее количество просмотров'",
            'hits_today' =>"`hits_today` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Просмотры за сутки'",
            'hits_yday'  =>"`hits_yday` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Просмотры за день'",
            'hits_week'  =>"`hits_week` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Просмотры за неделю'",
            'hits_month' =>"`hits_month` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Просмотры за месяц'",
            'favorite'   =>"`favorite` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '收藏数'",
            'comments'   =>"`comments` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '评论数'",
            'good'       =>"`good` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '顶'",
            'bad'        =>"`bad` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '踩'",
            'sortnum'    =>"`sortnum` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '排序'",
            'weight'     =>"`weight` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '权重'",
            'creative'   =>"`creative` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '内容类型 0:转载;1:原创'",
            'mobile'     =>"`mobile` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '发布设备 0:pc;1:手机'",
            'postype'    =>"`postype` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '发布类型 0:用户;1管理员'",
            'status'     =>"`status` TINYINT(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '状态 0:草稿;1:正常;2:回收;3:审核;4:不合格'",
        );
    }
    public static function base_fields_index(){
        return array(
            'index_id'         =>'KEY `id` (`status`,`id`)',
            'index_hits'       =>'KEY `hits` (`status`,`hits`)',
            'index_pubdate'    =>'KEY `pubdate` (`status`,`pubdate`)',
            'index_hits_week'  =>'KEY `hits_week` (`status`,`hits_week`)',
            'index_hits_month' =>'KEY `hits_month` (`status`,`hits_month`)',
            'index_cid_hits'   =>'KEY `cid_hits` (`status`,`cid`,`hits`)'
        );
    }
    public static function base_fields_key($key=null){
        if(self::$base_fields_key===null){
          $array = array(
              'id','cid','ucid','pid',
              'title','editor','userid','pubdate','postime','clink','tpl',
              'hits','hits_today','hits_yday','hits_week','hits_month',
              'favorite','comments','good','bad',
              'sortnum','weight',
              'creative','mobile','postype','status'
          );
        }else{
          $array = self::$base_fields_key;
        }
        if($key){
            return in_array($key, $array);
        }
        return $array;
    }
    public static function data_base_fields($name=null) {
      $primary_key = self::DATA_PRIMARY_KEY;
      $a[$primary_key] = "id=".$primary_key."&label=附加表id&comment=主键%20自增ID&field=PRIMARY&name=".$primary_key."&default=&type=PRIMARY&len=10&";
      if($name){
        $union_id = self::data_union_key($name);
        $a[$union_id] = "id=".$union_id."&label=关联内容ID&comment=内容ID%20关联".$name."表&field=INT&name=".$union_id."&default=&type=union&len=10";
      }
      return $a;
    }
    public static function json_field($json=null){
        if(empty($json)) return array();

        $fieldata    = json_decode(stripcslashes($json),true);
        //QS转数组
        $field_array = apps_mod::get_field_array($fieldata);

        $json_array  = array();
        foreach ($field_array as $key => $value) {
            $a = array();
            foreach ($value as $k => $v) {
                if(in_array($k, array('field','label','name','default','len','comment','unsigned'))){
                    $a[$k] = $v;
                }
            }
            ksort($a);
            $json_array[$key] = json_encode($a);
        }

        // $json_array  = array_map('json_encode', $field_array);

        // $json_array = array();
        // foreach ($array as $key => $value) {
        //     $json_array[$key] = json_encode($value);
        // }

        return $json_array;
    }
    public static function drop_table($fieldata,&$table_array,$name) {
      if(empty($fieldata) && $table_array[$name] && iDB::check_table($name)){
        apps_db::drop_tables(array(iPHP_DB_PREFIX.$name));
        unset($table_array[$name]);
      }
    }
    public static function find_MEDIUMTEXT(&$json_field) {
        $addons_json_field = array();
        foreach ($json_field as $key => $value) {
            $a = json_decode($value,true);
            if(strtoupper($a['field'])=="MEDIUMTEXT"){
              $addons_json_field[$key] = $value;
              unset($json_field[$key]);
            }
        }
        return $addons_json_field;
    }
    
    public static function data_create_table($fieldata,$name,$union_id,$query=true) {
        $table = apps_db::create_table(
          $name,
          apps_mod::get_field_array($fieldata),
          array(
            'index_'.$union_id =>'KEY `'.$union_id.'` (`'.$union_id.'`)'
          ),
          $query
        );
        array_push ($table,$union_id,'附加');
        return array($name=>$table);
    }
    
    public static function get_field_array($data,$ui=false) {
        $array = array();
        if($data)foreach ($data as $key => $value) {
          $output = array();
          if($value=='UI:BR'){
              $ui && $output = array('type'=>'br');
          }else{
              parse_str($value,$output);
              // if($keys){
              //   extract ($output);
              //   $output = compact ($keys);
              //   ksort($output);
              // }
          }
          $output && $array[$key] = $output;
        }
        return $array;
    }
    public static function get_data($app,$id,$filter=null) {
        $data  = array();
        if(empty($id) ){
            return $data;
        }

        $table = $app['table'];
        foreach ((array)$table as $key => $value) {
            $primary_key = $value['primary'];
            $value['union'] && $primary_key = $value['union'];
            if($filter && !in_array($value['table'],$filter)){
              continue;
            }
            $udata = (array)iDB::row("SELECT * FROM `{$value['table']}` WHERE `{$primary_key}`='$id' LIMIT 1;",ARRAY_A);
            $udata && $data+=$udata;
        }
        return $data;
    }
    public static function template($rs,$ret='string'){
     
      if($rs['app']){
        $_app = $rs['app'];
        if($rs['config']['iFormer'] && $rs['apptype']=="2"){
          $_app = 'content';
        }
        $template = (array)apps::get_func($_app,true);
        list($path,$obj_name)= apps::get_path($_app,'app',true);

        if(is_file($path)){
            
            $class_methods = get_class_methods ($obj_name);
            if(array_search ($_app ,  $class_methods )!==FALSE){
              array_push ($template,'$'.$_app);
            }
        }
      }
      if($rs['config']['iFormer'] && $rs['apptype']=="2"){
        foreach ((array)$template as $key => $value) {
          $template[$key] = str_replace(array(':content:','$content'), array(':'.$rs['app'].':','$'.$rs['app']), $value);
        }
      }
      return $ret=='string'?implode("\n", (array)$template):(array)$template;
    }
    public static function iurl($rs){
      if($rs['table'] && $rs['apptype']=="2"){
        $table  = reset($rs['table']);
        $rule = array('rule'=>'4','primary'=>$table['primary'],'page'=>'p');
      }else{
        $array = array(
            'http'     => array('rule'=>'0','primary'=>''),
            'index'    => array('rule'=>'0','primary'=>''),
            'category' => array('rule'=>'1','primary'=>'cid'),
            'article'  => array('rule'=>'2','primary'=>'id','page'=>'p'),
            'tag'      => array('rule'=>'3','primary'=>'id'),
        );
        $rule = $array[$rs['app']];
        if(empty($rule) && $rs['config']['iurl']){
          $rule = $rs['config']['iurl'];
        }
      }
      return $rule;
    }
}
