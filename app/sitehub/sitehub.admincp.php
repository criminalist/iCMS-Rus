<?php

defined('iPHP') OR exit('What are you doing?');
define('__COOKIE_DIR__',iPHP_APP_CACHE.'/sitehub');//临时文件夹

class sitehubAdmincp{
    public static $appid = null;
    public $TARGET="_parent";
    public function __construct() {
        self::$appid = apps::id(__CLASS__);
        $this->id = (int)$_GET['id'];
        file_exists(__COOKIE_DIR__) OR iFS::mkdir(__COOKIE_DIR__);
    }
    public function do_add(){
        $this->id && $rs = iDB::row("SELECT * FROM `#iCMS@__sitehub` WHERE `id`='$this->id' LIMIT 1;",ARRAY_A);
        if($_GET['act']=="copy"){
            $this->id   = 0;
            $rs['name'] = '';
            $rs['ikey'] = '';
            $rs['url'] = '';
        }
        include admincp::view("sitehub.add");
    }
    public function do_save(){
        $id     = (int)$_POST['id'];

        $name     = iSecurity::escapeStr($_POST['name']);
        $url      = iSecurity::escapeStr($_POST['url']);
        $ip       = iSecurity::escapeStr($_POST['ip']);
        $username = iSecurity::escapeStr($_POST['uname']);
        $password = iSecurity::escapeStr($_POST['pass']);
        $ikey     = iSecurity::escapeStr($_POST['ikey']);

        $name OR iUI::alert('站点名称不能为空!');
        $url OR iUI::alert('后台网址不能为空!');
        $username OR iUI::alert('管理员账号不能为空!');
        $password OR iUI::alert('管理员密码不能为空!');
        $ikey OR iUI::alert('iPHP_KEY不能为空!');

        $fields = array('name','username','password','ikey','url','ip');
        $data   = compact ($fields);


		if($id){
            iDB::update('sitehub', $data, array('id'=>$id));
			$msg="站点更新完成!";
		}else{
	        iDB::value("SELECT `id` FROM `#iCMS@__sitehub` where `url` ='$url'") && iUI::alert('该站点已经存在');
            $id = iDB::insert('sitehub',$data);
	        $msg="新站点添加完成!";
		}
        iUI::success($msg,'url:'.APP_URI);
    }
    public function site($id=null){
        $id===null && $id=$this->id;
        if(empty($id)){
            return false;
        }
        return iDB::row("SELECT * FROM `#iCMS@__sitehub` WHERE `id`='$id' LIMIT 1;",ARRAY_A);
    }

    public function login($site){
        if(empty($site)){
            return false;
        }

        iHttp::$CURLOPT_TIMEOUT        = 60; //数据传输的最大允许时间
        iHttp::$CURLOPT_CONNECTTIMEOUT = 10;  //连接超时时间

        $cookie_file = __COOKIE_DIR__.'/'.md5($site['url']).'.txt';
        $post['username'] = $site['username'];
        $post['password'] = $site['password'];
        $post['captcha'] = $site['ikey'];

        if(!file_exists($cookie_file)){
            iHttp::$CURLOPT_COOKIEJAR = $cookie_file;
            iHttp::post($site['url'],$post);
        }else if(filemtime($cookie_file)-time()<86400){
            iHttp::$CURLOPT_COOKIEJAR = $cookie_file;
            iHttp::post($site['url'],$post);
        }
        iHttp::$CURLOPT_COOKIEFILE = $cookie_file;
    }

    public function do_admincp($URLS=null,$id=null,$return=false){
        $site = $this->site($id);

        $this->login($site);
        $curl  = $site['url'];
        if($URLS===null){
            $URLS  = $_GET['URLS'];
            $URLS && $curl.='?'.base64_decode($URLS) ;
        }else{
            $URLS && $curl.='?'.$URLS;
        }

        $parse = parse_url($site['url']);
        $path  = $parse['path'];
        $response = iHttp::get($curl);
        preg_match_all('@(["|\'])'.preg_quote($path).'\?([\w\?&=\._-]+)\\1@is', $response, $matches);

        $arsort = array();
        foreach ($matches[2] as $key => $value) {
            $arsort[$key] = strlen($value);
        }
        arsort($arsort);

        $search = $replace = array();
        foreach ($arsort as $key => $value) {
            $search[$key] = $matches[2][$key];
            $replace[$key] = '&URLS='.urlencode(base64_encode($matches[2][$key]));
        }

        $proxy_url = APP_URI.'&do=admincp&id='.$site['id'];
        $response = str_replace($path, $proxy_url, $response);
        $response = str_replace($search, $replace, $response);
        $response = str_replace('?&URLS=', '&URLS=', $response);
        $response = str_replace('target="_blank"', 'target="'.$this->target.'"', $response);
        if($return){
            return $response;
        }
        echo $response;
    }
    public function do_cache(){
        iUI::flush_start();
        iUI::$break = false;
        $rs = iDB::all("SELECT `id`,`name`,`data` FROM `#iCMS@__sitehub`");
        $_count = count($rs);
        iUI::dialog('开始更新', '', false, 0, false);
        echo '
<script type="text/javascript">
var _count = '.$_count.',complete = 0;
</script>';
        foreach ($rs as $key => $value) {
            $msg = $value['name'].'获取失败';
            // if($this->update_site_data($value)){
            //     $msg = $value['name'].'更新完成';
            // }
            $msg = $value['name'].'更新完成';
            // $msg.= '<iframe src="'.APP_URI.'&do=update_site&json='.urlencode(base64_encode($value)).'" class="hide" id="site_'.$value['id'].'" name="site_'.$value['id'].'"></iframe>';
            // $a = array('site'=>$value);
            echo '
<script type="text/javascript">
    top.$.getJSON("'.APP_URI.'&do=update_site",'.json_encode($value).',function(j){
        ++complete;
        // top.iCMS.success("'.$value['name'].'更新完成");
        d.content(\'<table class=\"ui-dialog-table\" align=\"center\"><tr><td valign=\"middle\">'.$value['id'].':'.$value['name'].'更新完成 (\'+complete+\':\'+_count+\')</td></tr></table>\');
        if(complete==_count){
            d.content(\'<table class=\"ui-dialog-table\" align=\"center\"><tr><td valign=\"middle\">全部更新完成!</td></tr></table>\');
            window.setTimeout(function(){
                d.destroy();
            },1000);
        }
    });
</script>
            ';
            // echo $msg;
            // $updateMsg = $key ? true : false;
            // $timeout = ($key+1) == $_count ? '3' : false;
            // iUI::dialog($msg, '', $timeout, 0, $updateMsg);
            iUI::flush();
        }
        // iUI::success('更新完成','js:1');
    }
    public function do_clean(){
        $this->do_admincp('app=cache&do=all&format=json');
        iUI::success('操作成功!','js:1');
    }
    public function do_update_site(){
        $this->update_site_data($_GET);
    }
    public function update_site_data($site){
        $json = $this->do_admincp('do=count&a=all',$site['id'],true);
        $data = (array)json_decode($site['data'],true);

        $json2 = $this->do_admincp('do=version',$site['id'],true);
        $data2 = (array)json_decode($json2,true);
        $data = array_merge($data,$data2);
        if($json[0]=="{"){
            $data['count'] = json_decode($json,true);
            iDB::update('sitehub',
                array(
                    'data'=>addslashes(json_encode($data))
                ),
                array('id'=>$site['id'])
            );
            return true;
        }
        return false;
    }
    public function do_upgrade(){
        $site = $this->site();
        $this->target = '_self';
        //
        // $this->do_admincp('app=patch&do=git_check&git=true');
        $data = json_decode($site['data'],true);
        $log  =  patch::git('log',$data['GIT_COMMIT']);
        if(empty($log)){
            iUI::success($data['name'].'已经是最新版了','js:void(0)');
        }
        $html = $this->do_admincp('app=patch&do=git_download&last_commit_id='.$log[0]['commit_id'].'&release='.date("Ymd",$log[0]['info'][2]).'&git=true',$site['id'],true);
        preg_match('@<a class="btn btn-success btn-large" href="(.*?)"><i class="fa fa-wrench"></i> 开始升级</a>@', $html, $matches);

        if($matches[1]){
           //  $this->login($site);
           //  $response = iHttp::get(ACP_HOST.$matches[1]);
           // var_dump($matches[1]);
           //  preg_match('@var\slog\s=\s"(.*?)";@', $response, $r);
           //  print_r($r);
            iPHP::redirect($matches[1]);
        }else{
            echo $html;
        }
    }
    public function do_update(){
        if($this->id){
            $data = iSQL::update_args($_GET['_args']);
            $data && iDB::update("marker",$data,array('id'=>$this->id));
            $this->cache($this->id);
            iUI::success('操作成功!','js:1');
        }
    }
    public function do_del($id = null,$dialog=true){
    	$id===null && $id=$this->id;
    	$id OR iUI::alert('请选择要删除的站点!');
		iDB::query("DELETE FROM `#iCMS@__sitehub` WHERE `id` = '$id';");
    	$this->cache($id);
    	$dialog && iUI::success("已经删除!",'url:'.APP_URI);
    }

    public function do_batch(){
        list($idArray,$ids,$batch) = iUI::get_batch_args("请选择要操作的站点");
    	switch($batch){
    		case 'dels':
				iUI::$break	= false;
	    		foreach($idArray AS $id){
	    			$this->do_del($id,false);
	    		}
	    		iUI::$break	= true;
				iUI::success('站点全部删除完成!','js:1');
    		break;
    		case 'refresh':
                foreach($idArray AS $id){
    			 $this->cache($id);
                }
    			iUI::success('站点缓存全部更新完成!','js:1');
    		break;
		}
	}

    public function do_iCMS(){
        $sql			= " where 1=1";
        $_GET['pid'] && $sql.=" AND `pid`='".$_GET['pid']."'";
        $_GET['pid'] && $uri.='&pid='.$_GET['pid'];

        $_GET['cid']  && $sql.=" AND `cid`='".$_GET['cid']."'";
        $_GET['cid']  && $uri.='&cid='.$_GET['cid'];

        $maxperpage = $_GET['perpage']>0?(int)$_GET['perpage']:20;
        $total		= iPagination::totalCache("SELECT count(*) FROM `#iCMS@__sitehub` {$sql}","G");
        iUI::pagenav($total,$maxperpage,"个站点");
        $rs     = iDB::all("SELECT * FROM `#iCMS@__sitehub` {$sql} order by `ip` DESC LIMIT ".iPagination::$offset." , {$maxperpage}");
        $_count = count($rs);
    	include admincp::view("sitehub.manage");
    }

}
