<?php


defined('iPHP') OR exit('Oops, something went wrong');

define('ADMINCP', true);
define('__ADMINCP__', iPHP_SELF . '?app');
define('ACP_PATH', iPHP_APP_DIR . '/admincp');
define('ACP_HOST', (($_SERVER['SERVER_PORT'] == 443)?'https':'http')."://" . $_SERVER['HTTP_HOST']);
$git_ver_file = iPHP_APP_CORE.'/git.version.php';
file_exists($git_ver_file) && require_once $git_ver_file;

class admincp {
	public static $apps       = NULL;
	public static $callback   = NULL;
	public static $view       = NULL;
	public static $APP_OBJ    = NULL;
	public static $APP_NAME   = NULL;
	public static $APP_DO     = NULL;
	public static $APP_METHOD = NULL;
	public static $APP_PATH   = NULL;
	public static $APP_TPL    = NULL;
	public static $APP_FILE   = NULL;
	public static $APP_DIR    = NULL;
	public static $APP_ARGS   = NULL;
	public static $URL_TOKEN  = false;

	public static function init() {
		($_GET['do'] == 'seccode') && admincpApp::get_seccode();

		iUI::$dialog['title'] = iPHP_APP;
		iDB::$show_errors     = true;
		iDB::$show_trace      = false;
		iDB::$show_explain    = false;

		members::$LOGIN_PAGE  = ACP_PATH.'/template/admincp.login.php';
		members::$GATEWAY     = iSecurity::request('gateway');
		members::check_login(array("admincpApp","check_seccode")); //用户登陆验证
		members::check_priv('ADMINCP','page');//检查是否有后台权限

		iWAF::CSRF_token(members::$userid);

		files::init(array('userid'=> members::$userid));
		//菜单
		menu::init();
		menu::$callback = array(
			"priv" => array("members","check_priv"),
			"hkey" => members::$userid
        );

        admincp::$callback = array(
			"history" => array("menu","history"),
			"priv"    => array("members","check_priv")
        );
	}

	public static function run($app = NULL, $do = NULL, $args = NULL, $prefix = "do_") {
		self::init();

		$app OR $app = iSecurity::request('app');
		$do  OR $do  = iSecurity::escapeStr($_GET['do']);
		$app OR $app = 'admincp';
		$do  OR $do  = 'iCMS';

		if ($_POST['action']) {
			$do = iSecurity::escapeStr($_POST['action']);
			$prefix = 'ACTION_';
		}

		strpos($app, '..') === false OR exit('what the fuck');

		self::$APP_NAME   = $app;
		self::$APP_DO     = $do;
		self::$APP_METHOD = $prefix . $do;

		self::$APP_PATH   = ACP_PATH;
		self::$APP_TPL    = ACP_PATH . '/template';

		//admincp.app.php
		self::$APP_FILE   = ACP_PATH . '/' . $app . '.app.php';
		$obj_name = self::$APP_NAME . 'App';

		if(!is_file(self::$APP_FILE)){
			//ooxx.admincp.php
			$app_file = $app . '.admincp.php';
			$obj_name = $app.'Admincp';
			//app_category.admincp.php
	        if(stripos($app, '_')!== false){
	            list($app,$sapp) = explode('_', $app);
	        }
			self::$APP_PATH = iPHP_APP_DIR . '/' . $app;
			self::$APP_TPL  = self::$APP_PATH . '/admincp';
			self::$APP_FILE = self::$APP_PATH . '/'.$app_file;
		}
		
		if(!is_file(self::$APP_FILE)){
			$appData = apps::get_app($app);
			if($appData){
				$sapp && $sapp_name = '_'.$sapp;
				$app_file = 'content'.$sapp_name.'.admincp.php';
				$obj_name = 'content'.$sapp_name.'Admincp';
				self::$APP_PATH = iPHP_APP_DIR . '/content';
				self::$APP_TPL  = self::$APP_PATH . '/admincp';
				self::$APP_FILE = self::$APP_PATH . '/'.$app_file;
			}else{

			}
		}

		is_file(self::$APP_FILE) OR iPHP::error_throw('Unable to find admincp file <b>' .self::$APP_NAME. '.admincp.php</b>('.self::$APP_FILE.')', 1002);

		define('APP_URI', __ADMINCP__ . '=' . self::$APP_NAME);
		define('APP_FURI', APP_URI );
		define('APP_DOURI', APP_URI . ($do=='iCMS'?null:'&do='.$do));
		define('APP_BOXID', self::$APP_NAME . '-box');
		define('APP_FORMID', 'iCMS-' . APP_BOXID);

		$obj_flag = self::app_startup(self::$APP_NAME,$prefix);
		if($obj_flag===false){
			require_once self::$APP_FILE;
			self::$APP_OBJ = new $obj_name($appData?$appData:null);
		}else{
			$obj_name = $obj_flag;
		}

		$app_methods = get_class_methods(self::$APP_OBJ);
		in_array(self::$APP_METHOD, $app_methods) OR iPHP::error_throw('Call to undefined method <b>' . $obj_name . '::' . self::$APP_METHOD . '</b>', 1003);

		//CSRF check
		iWAF::CSRF_check(members::$userid);
		iPHP::callback(self::$callback['history'],APP_DOURI);
		iPHP::callback(self::$callback['priv'],array(APP_DOURI,'page'));
		iCMS::$config['debug']['access_log'] OR admincpApp::access_log();

		$method = self::$APP_METHOD;
		$args === null && $args = self::$APP_ARGS;

		if($method=='do_batch'){
	        $bmIds = $_POST['bmIds'];
	        if(isset($_POST['bmIds']) && $bmIds){
	            $_POST['id'] = explode(',', $bmIds);
	        }
		}
		if ($args) {
			if ($args === 'object') {
				return self::$APP_OBJ;
			}
			return self::$APP_OBJ->$method($args);
		} else {
			return self::$APP_OBJ->$method();
		}
	}
	public static function app_startup($app,$prefix=null) {
		$prefix = strtoupper($prefix);
		$path   = self::$APP_PATH . '/'.$prefix.$app.'.admincp.php';
		// var_dump($path);
		// a/DO_a.admincp.php
		// a/ACTION_a.admincp.php
		if(is_file($path) && $prefix){
			$class_name = $prefix.$app.'Admincp';
			require_once $path;
			if(class_exists($class_name,false)){
				self::$APP_FILE = $path;
				self::$APP_OBJ  = new $class_name();
				$class_methods  = get_class_methods(self::$APP_OBJ);
				if(in_array(self::$APP_METHOD, $class_methods)){
					return $class_name;
				}
			}
		}else{
			return false;
		}
		// return self::app_startup($app);
	}
	public static function view($name = NULL, $dir=null) {
		self::$view['name']&& $name = self::$view['name'];
		if($dir===null && self::$view['dir']){
			$dir = self::$view['dir'];
		}
		if ($name === NULL && self::$APP_NAME) {
			$name = self::$APP_NAME;
			self::$APP_DO && $name.= '.' . self::$APP_DO;
		}
		$tpl = self::$APP_TPL;
		if($dir){
			if($dir=='admincp'){
				$tpl = ACP_PATH . '/template';
			}else{
				$tpl = iPHP_APP_DIR.'/'.$dir.'/admincp';
			}
		}
		$def = $tpl . '/' . $name . '.def.php';
		if(file_exists($def)){
			return $def;
		}
		$path = $tpl . '/' . $name . '.php';
		return $path;
	}

	public static function head($navbar = true) {
		$body_class = '';
		if (iCMS::$config['other']['sidebar_enable']) {
			iCMS::$config['other']['sidebar'] OR $body_class = 'sidebar-mini';
			$body_class = iPHP::get_cookie('ACP_sidebar_mini') ? 'sidebar-mini' : '';
		} else {
			$body_class = 'sidebar-display';
		}
		isset(self::$view['navbar']) && $navbar = self::$view['navbar'];
		// var_dump(self::$view);
		$navbar === false && $body_class = 'iframe ';
		if(self::$view['head:begin']){
			include self::view("head.begin",self::$view['head:begin']);
		}
		include self::view("admincp.header",'admincp');
		if(self::$view['head:after']){
			include self::view("head.after",self::$view['head:after']);
		}
		if($navbar === true){
			include self::view("admincp.navbar",'admincp');
		}
	}

	public static function foot() {
		if(self::$view['foot:begin']){
			include self::view("footer.begin",self::$view['foot:begin']);
		}
		include self::view("admincp.footer",'admincp');

		if(self::$view['foot:after']){
			include self::view("footer.after",self::$view['foot:after']);
		}
	}
    public static function uri($q,$a){
    	is_string($q) && $q = parse_url_qs($q);
        $query = array_merge((array)$a,(array)$q);
        return iURL::make($query,APP_DOURI);
    }
	public static function debug_info(){
		$memory = memory_get_usage();
		return "Память:".iFS::sizeUnit($memory)." Время выполнения:".iPHP::timer_stop()."s";
	}
}

