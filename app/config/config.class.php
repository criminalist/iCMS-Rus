<?php

defined('iCMS_APP_CONFIG') OR define('iCMS_APP_CONFIG', '11');

class config{
    public static $appid = 0;
    public static $data  = array();

public static function table(){
        $table = 'config';
        if(iPHP_APP_SITE!='iCMS'){
            $table = 'config_'.str_replace('.', '_', iPHP_APP_SITE);
        }
        return $table;
    }
    
    public static function cache(){
        $config         = self::get();
        $config['apps'] = apps::get_appsid();
        $config['iurl'] = apps::get_iurl();
        $config['router']['config'] = apps::get_router();
        $config['meta'] = array();
        $data = apps_meta::data(iCMS_APP_CONFIG,1);
        $data && $config['meta'] = $data['meta'];
        self::write($config);
    }
    public static function head($title=null,$action="config"){
        include admincp::view("config.head","config");
    }
    public static function foot(){
        include admincp::view("config.foot","config");
    }
    
    public static  function app($appid=0,$name=null,$ret=false,$suffix="config"){
        $name===null && $name = admincp::$APP_NAME;
        if(empty($appid) && self::$appid){
            $appid = self::$appid;
        }
        empty($appid) && iUI::alert("Ошибка конфигурации отсутствует APPID!");

        $config = self::get($appid,$name);
        if($ret){
            return $config;
        }
        include admincp::view($name.'.'.$suffix);
    }
    
    public static function save($appid=0,$name=null,$handler=null,$dialog=true){
        $name===null   && $name = admincp::$APP_NAME;
        if(empty($appid) && self::$appid){
            $appid = self::$appid;
        }
        empty($appid) && iUI::alert("Ошибка конфигурации отсутствует APPID!");
        $config = iSecurity::escapeStr($_POST['config']);
        self::set($config,$name,$appid,false);
        $handler && iPHP::callback($handler,array($config));
        self::cache();
        $dialog && iUI::success('Успешно обновлено','js:1');
    }

    public static function post() {
        $_POST['config'] = array_merge((array)self::$data,(array)$_POST['config']);
    }
    
    public static function get($appid = NULL, $name = NULL) {
        $appid && self::$appid = $appid;
        if ($name === NULL) {
            $sql = "appid< '999999'";
            $appid === NULL OR $sql = " AND `appid`='$appid'";
            $rs  = iDB::all("SELECT * FROM `#iCMS@__".self::table()."` WHERE $sql");
            foreach ((array)$rs AS $c) {
                $value = $c['value'];
                // strpos($c['value'], 'a:')===false OR $value = serialize($c['value']);
                $value = (array)json_decode($value,true);
                $config[$c['name']] = $value;
            }
            self::$data = $config;
            return $config;
        } else {
            $value = iDB::value("SELECT `value` FROM `#iCMS@__".self::table()."` WHERE `appid`='$appid' AND `name` ='$name'");
            // strpos($value, 'a:')===false OR $value = unserialize($value);
            $value = (array)json_decode($value,true);
            self::$data = $value;
            return $value;
        }
    }
    
    public static function set($value, $name, $appid, $cache = false) {
        $cache && iCache::set('config/' . $name, $value, 0);
        // is_array($value) && $value = addslashes(serialize($value));
        is_array($value) && $value = addslashes(cnjson_encode($value));
        $check  = iDB::value("SELECT `name` FROM `#iCMS@__".self::table()."` WHERE `appid` ='$appid' AND `name` ='$name'");
        $fields = array('appid','name','value');
        $data   = compact ($fields);
        if($check===null){
            iDB::insert(self::table(),$data);
        }else{
            iDB::update(self::table(),$data, array('appid'=>$appid,'name'=>$name));
        }
    }
    public static function del($name, $appid) {
        if($name &&$appid){
            iDB::query("DELETE FROM `#iCMS@__".self::table()."` WHERE `appid` ='$appid' AND `name` ='$name'");
        }
    }
    
    public static function write($config=null){
        $config===null && $config = self::get();
        $output = "<?php\ndefined('iPHP') OR exit('Access Denied');\nreturn ";
        $output.= var_export($config,true);
        $output.= ';';
        iFS::write(iPHP_APP_CONFIG,$output);
    }
    
    public static function update($k,$appid=0){
        self::set(iCMS::$config[$k],$k,$appid);
        self::cache();
    }
    public static function view(){
        include admincp::view('config',null,true);
    }
public static function scan_config($tab='*'){
        return (array)glob(iPHP_APP_DIR."/*/admincp/*.config.{$tab}.php");

        // foreach (glob(iPHP_APP_DIR."/*/admincp/*.config.{$tab}.php") as $path) {
        //     // var_dump($path);
        //     // preg_match("@.*?/(\w+)/admincp/((\w+)\.config\.(\w+))\.php@", $path, $match);
        //     include $path;
        // }
    }
}
