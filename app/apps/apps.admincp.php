<?php

defined('iPHP') OR exit('Oops, something went wrong');

class appsAdmincp{
    public function __construct() {
      $this->appid = iCMS_APP_APPS;
    	$this->id = (int)$_GET['id'];

      iHttp::$CURLOPT_TIMEOUT        = 60;
      iHttp::$CURLOPT_CONNECTTIMEOUT = 10;
    }
    public function do_iCMS(){
      $this->do_manage();
    }
    public function do_add(){
        $this->id && $rs = apps::get($this->id);
        if(empty($rs)){
          $rs['type']   = "2";
          $rs['status'] = "1";
          $rs['create'] = "1";
          if($rs['type']=="2"){
            $rs['apptype'] = "2";
            $rs['config']['iFormer'] = '1';
            $rs['config']['menu']    = 'default';
            $base_fields  = apps_mod::base_fields_array();
            $rs['fields'] = apps::etc('apps','fields.source');
            $rs['menu']   = apps::etc('apps','menu.source');
            $rs['fields'] = json_decode($rs['fields'],true);
          }
        }else{
          if($rs['apptype']=="2"){
            $rs['config']['iFormer'] = '1';
          }
        }

        $rs['config']['template'] = apps_mod::template($rs);
        if(empty($rs['config']['iurl'])){
          $rs['config']['iurl'] = apps_mod::iurl($rs);
        }
        if($rs['menu']){
          $rs['menu'] = jsonFormat($rs['menu']);




        }
        if($rs['router']){
          $rs['router'] = jsonFormat($rs['router']);




        }
        include admincp::view("apps.add");
    }

    public function do_save(){
        @set_time_limit(0);

        $id      = (int)$_POST['_id'];
        $name    = iSecurity::escapeStr($_POST['_name']);
        $title   = iSecurity::escapeStr($_POST['_title']);
        $app     = iSecurity::escapeStr($_POST['_app']);
        $apptype = (int)$_POST['apptype'];
        $type    = (int)$_POST['type'];
        $status  = (int)$_POST['status'];

        if($_POST['menu']){
          $menu = json_decode(stripcslashes($_POST['menu']));
          $menu = addslashes(cnjson_encode($menu));
        }
        if($_POST['router']){
          $router = json_decode(stripcslashes($_POST['router']));
          $router = addslashes(json_encode($router));
        }
        $name OR iUI::alert('Имя приложения не может быть пустым!');
        strpos($app, '..') !== false && iUI::alert('非法应用标识!');
        empty($app) && $app = iPinyin::get($name);
        empty($title) && $title = $name;

        $table_array  = (array)$_POST['table'];
        if($table_array){
          $table_array  = array_filter($table_array);
          $table  = addslashes(cnjson_encode($table_array));
        }

        $config = (array)$_POST['config'];
        if($config['template']){
          $config['template'] = explode("\n", $config['template']);
          $config['template'] = array_map('trim', $config['template']);
        }
        if($config['iurl']){
          $config['iurl'] = json_decode(stripcslashes($config['iurl']),true);
        }

        $config = array_filter($config);
        $config = addslashes(cnjson_encode($config));

        $fields   = '';
        $fieldata = $_POST['fields'];
        if(is_array($fieldata)){
          $field_array = array();
          foreach ($fieldata as $key => $value) {
            $output = parse_url_qs($value);
            if(isset($output['UI:BR'])){
              $field_array[$key] = 'UI:BR';
            }else{
              preg_match("/[a-zA-Z0-9_\-]/",$output['name']) OR iUI::alert('['.$output['label'].'] 字段名 Может состоять только из английских букв, цифр или символов _-');
              $output['label'] OR iUI::alert('发现自定义字段中空字段名称!');
              $output['comment'] = $output['label'].($output['comment']?':'.$output['comment']:'');
              $fname = $output['name'];
              $fname OR iUI::alert('发现自定义字段中有空字段名!');
              $field_array[$fname] = $value;
              if($output['field']=="MEDIUMTEXT"){
                $dataTable_field_array[$key] = $value;
                unset($fieldata[$key]);//从基本表移除
              }
            }
          }
          //字段数据存入数据库
          $fields = addslashes(cnjson_encode($field_array));
        }

        $addtime = time();
        $array   = compact(array('app','name','title','menu','router','table','config','fields','addtime','apptype','type','status'));
        // $array['menu'] = str_replace(array("\r","\n"),'',$array['menu']);

        if(empty($id)) {
            iDB::value("SELECT `id` FROM `#iCMS@__apps` where `app` ='$app'") && iUI::alert('Приложение уже существует!');
            // iDB::$print_sql = true;
            if($type=='3'){
              $array['fields'] = '';
              $msg = "应用信息添加完成!";
            }else if($type=='2'){
                iDB::check_table($array['app']) && iUI::alert('Таблица  ['.$array['app'].']уже существует!');
                if($dataTable_field_array){
                    $dataTable_name = apps_mod::data_table_name($array['app']);
                    iDB::check_table($dataTable_name) && iUI::alert('['.$dataTable_name.'] дополнительная таблица уже существует!');
                }

              //创建基本表
              $tb = apps_db::create_table(
                $array['app'],
                apps_mod::get_field_array($fieldata),//获取字段数组
                apps_mod::base_fields_index(),//索引
                true
              );
              array_push ($tb,null,$array['name']);
              $table_array = array();
              $table_array[$array['app']]= $tb;//记录基本表名

              //有MEDIUMTEXT类型字段就创建xxx_cdata附加表
              if($dataTable_field_array){
                $union_id = apps_mod::data_union_key($array['app']);//关联基本表id
                $dataTbale_base_fields = apps_mod::data_base_fields($array['app']);//xxx_data附加表的基础字段
                $dataTable_field_array = $dataTbale_base_fields+$dataTable_field_array;
                $table_array += apps_mod::data_create_table($dataTable_field_array,$dataTable_name,$union_id,true);
              }

              $table_array+=apps_meta::table_array($app);

              $array['table']  = $table_array;
              $array['config'] = $config_array;

              $config_array['template'] = apps_mod::template($array,'array');
              $config_array['iurl']   = apps_mod::iurl($array);

              $array['table'] = addslashes(cnjson_encode($table_array));
              $array['config'] = addslashes(cnjson_encode($config_array));
              $msg = "Приложение успешно создано!";
            }

            $id = iDB::insert('apps',$array);
            // if(stripos($array['menu'], '{app}') !== false){
            //   $_name = $array['title']?$array['title']:$array['name'];
            //   $menu = str_replace(
            //       array('{appid}','{app}','{name}','{sort}'),
            //       array($id,$array['app'],$_name,$id*1000),
            //       $array['menu']
            //   );
            //   iDB::update('apps', array('menu'=>$menu), array('id'=>$id));
            // }
        }else {
            iDB::value("SELECT `id` FROM `#iCMS@__apps` where `app` ='$app' AND `id` !='$id'") && iUI::alert('Приложение уже существует!');
            $_fields     = iDB::value("SELECT `fields` FROM `#iCMS@__apps` where `id` ='$id'");//json
            $_json_field = apps_mod::json_field($_fields);//旧数据
            $json_field  = apps_mod::json_field($fields); //新数据
            /**
             * 找出字段数据中的 MEDIUMTEXT类型字段
             * PS:函数内会unset(json_field[key]) 所以要在 基本表make_alter_sql前执行
             */
            $_DT_json_field = apps_mod::find_MEDIUMTEXT($_json_field);
            $DT_json_field  = apps_mod::find_MEDIUMTEXT($json_field);

            //基本表 新旧数据计算交差集 origin 为旧字段名
            $alter_sql_array = apps_db::make_alter_sql($json_field,$_json_field,$_POST['origin']);
            if($alter_sql_array){
                $t_fields  = apps_db::fields('#iCMS@__'.$array['app']);
                foreach ($alter_sql_array as $skey => $sql) {
                    $p = explode('`', $sql);
                    if(strpos($sql, 'CHANGE')!==false || strpos($sql, 'DROP COLUMN')!==false){
                        if(!$t_fields[$p[1]]){//检查当前表 字段是否存在
                            unset($alter_sql_array[$skey]);
                        }
                    }
                }
                $alter_sql_array && apps_db::alter_table($array['app'],$alter_sql_array);
            }

            //Дополнительные таблицы
            $dataTable_name = apps_mod::data_table_name($array['app']);
            if($table_array[$dataTable_name] && iDB::check_table($dataTable_name)){
                //Поле типа MEDIUMTEXT. Источником набора пересечений для новых и старых данных является старое имя поля.
                $dataTable_alter_array = apps_db::make_alter_sql($DT_json_field,$_DT_json_field,$_POST['origin']);
                //Таблица существует для выполнения Alter
                $dataTable_alter_array && apps_db::alter_table($dataTable_name,$dataTable_alter_array);
                //Таблица существует, но данные структуры таблицы не удаляют таблицу
                if(empty($dataTable_field_array)){
                    apps_mod::drop_table($dataTable_field_array,$table_array,$dataTable_name);
                    $array['table'] = addslashes(cnjson_encode($table_array));
                }
            }else{
                //表不存在 但有表结构数据 则创建表
                if($dataTable_field_array){
                    //有MEDIUMTEXT类型字段创建xxx_cdata Дополнительные таблицы
                    $union_id = apps_mod::data_union_key($array['app']);
                    $dataTbale_base_fields = apps_mod::data_base_fields($array['app']);//xxx_cdata附加表的基础字段
                    $dataTable_field_array = $dataTbale_base_fields+$dataTable_field_array;
                    $table_array += apps_mod::data_create_table($dataTable_field_array,$dataTable_name,$union_id);
                    $array['table'] = addslashes(cnjson_encode($table_array));
                }
            }

            iDB::update('apps', $array, array('id'=>$id));
            $msg = "Успешно изменено!";
        }
        apps::cache();
        menu::cache();
        iUI::success($msg,'url:'.APP_URI);
    }

    public function do_update(){
        if($this->id){
            $args = iSQL::update_args($_GET['_args']);
            $args && iDB::update("apps",$args,array('id'=>$this->id));
            apps::cache();
            iUI::success('Успешно выполнено!','js:1');
        }
    }
    public function do_manage(){
      // if($_GET['keywords']) {
		    // $sql=" WHERE `keyword` REGEXP '{$_GET['keywords']}'";
      // }
      list($orderby,$orderby_option) = get_orderby();
      $maxperpage = $_GET['perpage']>0?(int)$_GET['perpage']:50;
      $total      = iPagination::totalCache("SELECT count(*) FROM `#iCMS@__apps` {$sql}","G");
      iUI::pagenav($total,$maxperpage,"приложений");
      $rs     = iDB::all("SELECT * FROM `#iCMS@__apps` {$sql} order by {$orderby} LIMIT ".iPagination::$offset." , {$maxperpage}");
      $_count = count($rs);

      //分组
      foreach ($rs as $key => $value) {
        $apps_type_group[$value['type']][$key] = $value;
      }
    	include admincp::view("apps.manage");
    }

    public function do_batch(){
        list($idArray,$ids,$batch) = iUI::get_batch_args("请选择要操作的应用");
      	switch($batch){
  		  }
	  }
    public function do_cache(){
      apps::cache();
      iUI::success('Успешно обновлено');
    }
    /**
     * [卸载应用]
     * @return [type] [description]
     */
    public function do_uninstall($id = null,$dialog=true){
      if(!isset($_GET['confirm'])||!$_GET['confirm']){
        iUI::alert('非正常删除','js:1');
      }
      $id===null && $id=$this->id;
      $app = apps::get($id);
      if($app && $app['type'] && $app['apptype']){
        apps::uninstall($app);
        apps::cache();
        menu::cache();
      }
      $dialog && iUI::alert('应用已经删除','js:1');
    }
    /**
     * [本地安装应用]
     * @return [type] [description]
     */
    public function do_local_app(){
      $zipfile = trim($_POST['zipfile']);
      if(preg_match("/^iCMS\.APP\.(\w+)\-v\d+\.\d+\.\d+\.".apps::PKG_EXT."$/", $zipfile,$match)){
        apps_store::$zip_file = iPATH.$zipfile;
        apps_store::$msg_mode = 'alert';
        apps_store::install_app($match[1]);
        iUI::success('应用安装完成','js:1');
      }else{
        iUI::alert('What the fuck!!');
      }
    }
    /**
     * [打包下载应用]
     * @return [type] [description]
     */
    public function do_pack(){
      $rs = iDB::row("SELECT * FROM `#iCMS@__apps` where `id`='".$this->id."'",ARRAY_A);
      iFS::check($rs['app'],true);
      $appdir = iPHP_APP_DIR.'/'.$rs['app'];
      unset($rs['id']);
      $data     = base64_encode(serialize($rs));
      $config   = json_decode($rs['config'],true);
      $filename = 'iCMS.APP.'.$rs['app'].'-'.$config['version'];
      if(iFS::ex($appdir)) { //本地应用
        $remove_path = iPHP_APP_DIR;
      }else{//自定义应用
        $appdir = iPHP_APP_CACHE.'/pack.app/'.$rs['app'];
        $remove_path = iPHP_APP_CACHE.'/pack.app/';
        iFS::mkdir($appdir);
      }
      //应用数据
      $app_data_file = $appdir.'/iCMS.APP.DATA.php';
      put_php_file($app_data_file, $data);

      //数据库结构
      if($rs['table']){
        $app_table_file = $appdir.'/iCMS.APP.TABLE.php';

        put_php_file(
          $app_table_file,
          apps_db::create_table_sql($rs['table'])
        );
      }

      $package = apps::get_package($filename,$appdir,$remove_path);
      filesApp::attachment($package);
      iFS::rm($package);
      iFS::rm($app_data_file);
      $app_table_file && iFS::rm($app_table_file);

      if($remove_path != iPHP_APP_DIR){
        iFS::rmdir($remove_path);
      }
    }
    /**
     * [钩子管理]
     * @return [type] [description]
     */
    public function do_hooks(){
        configAdmincp::app($this->appid,'hooks');
    }
    /**
     * [保存钩子]
     * @return [type] [description]
     */
    public function do_hooks_save(){
        $hooks = array();
        foreach ((array)$_POST['hooks']['method'] as $key => $method) {
          $h_app   = $_POST['hooks']['app'][$key];
          $h_field = $_POST['hooks']['field'][$key];
          if($method && $h_app && $h_field){
            $hooks[$h_app][$h_field][]= explode("::", $method);
          }
        }
        $_POST['config'] = $hooks;
        configAdmincp::save($this->appid,'hooks');
    }
    public function do_menu_source(){
        echo apps::etc('apps','menu.source');
    }
    public static function _count(){
      return iDB::value("SELECT count(*) FROM `#iCMS@__apps`");
    }
}
