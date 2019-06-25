<?php

class video_spiderAdmincp {
    public static $appid = null;
    public static $video = array();
    public static $conf = array(
        'r'=>array('999999','video:spider:res'),
        'c'=>array('999999','video:spider:category')
    );
    public function __construct() {
		self::$appid     = iCMS_APP_VIDEO;
		$this->id        =  (int)$_GET['id'];
		$this->book_id   =  (int)$_GET['book_id'];
		category::$appid = iCMS_APP_VIDEO;
    }
    public function do_addres(){
        menu::set('manage','video_spider');
        include admincp::view("spider.addres");
    }
    public function do_saveres(){
        menu::set('manage','video_spider');
        $id    = md5($_POST['url']);
        $value = $_POST;
        $value['id'] = $id;
        self::set_config($id,$value,'r');
    }
    public function do_delres(){
        menu::set('manage','video_spider');
        $rid = $_GET['rid'];
        self::set_config($rid,'delete','r');
    }
    public function do_manage(){
        $rs = self::get_config(null,'r');
    	include admincp::view("spider.manage");
    }
    public function do_list(){
    	menu::set('manage','video_spider');
        $rid  = $_GET['rid'];
        $rtid = $_GET['rtid'];
        $loc_category  = category::priv('cs')->select();
        //分类绑定数据
        $bindCategory  = self::get_config($rid,'c');
        $res     = self::get_config($rid,'r');
        $data    = self::api_get($res['url'],array('ac'=>'list','t'=>$rtid,'h'=>$_GET['h'],'pg'=>$_GET['page'],'wd'=>$_GET['keywords']));
        $total   = $data['list']['@attributes']['recordcount'];
        $perpage = $data['list']['@attributes']['pagesize'];
        if($total>1){
            $rs = $data['list']['video'];
        }else{
            $rs = array($data['list']['video']);
        }
        iPagination::pagenav($total,$perpage,"条记录");

    	include admincp::view("spider.list");
    }
    public function do_crawl(){
        @set_time_limit(0);
    	menu::set('manage','video_spider');
        $total    = 0;
        $num      = 10;
        $dt       = $_GET['dt'];
        $rid      = $_GET['rid'];
        $rtid     = $_GET['rtid'];
        $page     = (int)$_GET['page']?:1;
        $res      = self::get_config($rid,'r');
        $category = self::get_config($rid,'c');

        if($dt=='ids'){
            list($idArray,$ids,$batch) = iUI::get_batch_args("请选择要采集的小说");
            $param = array('ac'=>'videolist','ids'=>$ids);
        }else{
        // https://api.ooxx.com/inc/api.php?ac=videolist&t=0&h=24&pg=4
        // https://api.ooxx.com/inc/api.php?ac=videolist&t=0&h=168&pg=2
        // https://api.ooxx.com/inc/api.php?ac=videolist&pg=
        // https://api.ooxx.com/inc/api.php?ac=videolist&ids=22757,23847,23782,26141,9192,25779,24013,6963,26059,23801,22871,15935,23462,21263,26099,24132,23977,24810,26061,26057,24726,21697,25734,25930
            $param = array('ac'=>'videolist','pg'=>$_GET['page'],'wd'=>$_GET['keywords']);
            $dt =='today'  && $param['h']='24';
            $dt =='week'   && $param['h']='168';
            $dt =='month'  && $param['h']='720';
            $rtid && $param['t']=$rtid;
        }
        $data  = self::api_get($res['url'],$param);

        if($data){
            $total = $data['list']['@attributes']['recordcount'];
            $num   = $data['list']['@attributes']['pagesize'];

            if($total>1){
                $rs = $data['list']['video'];
            }else{
                $rs = array($data['list']['video']);
            }

            $maxpage = ceil($total/$num);
            $nquery  = array(
                'page' =>$page+1,
            );
            if ($maxpage>0 && $nquery['page']<=$maxpage){
                $nexturl = iURL::URI($nquery);
            }
        }
    	include admincp::view("spider.crawl");
    }
    public function do_bind_category(){
        $rid  = $_POST['rid'];
        $cid  = $_POST['cid'];
        $rtid = $_POST['rtid'];
		$config = configAdmincp::get(self::$conf['c'][0],self::$conf['c'][1]);

        empty($config) && $config[$rid] = array();
        empty($config[$rid]) && $config[$rid] = array();

        if($cid===''){
            unset($config[$rid][$rtid]);
        }else{
            $config[$rid]+=array($rtid=>$cid);
        }
        $_POST['config'] = $config;
    	configAdmincp::save(self::$conf['c'][0],self::$conf['c'][1],null,false);
        iUI::code(1);
    }
    public static function insert_video($value,$category,$rid) {
        $video_id = self::video_spider_check($value['id'],$rid);
        
        $title = str_replace(array('\\','()','\''),'/',$value['name']);
        $title = str_replace(array('HD','BD','DVD','VCD','TS','[完结]','[] ','[]','()','\''),'',$title);
        $vid = video::check($title,$video_id);
        $vid && $video_id = $vid;

        if($video_id){
            $msg = 'Обновить';
            $vid && $msg = '仅更新资源';
            // $video['video_id'] = $video_id;
            list($_video,$vdata) = video::data($video_id);
            if($_video['status']=="2"){
                return array('class'=>'warning','text'=>'已删除跳过');
            }
            $video['pubdate']  = date("Y-m-d H:i:s");
            $video['remark']   = iSecurity::escapeStr($value['note']);
        }else{
            $msg = '新增';
            $video['cid'] = $category[$value['tid']];
            if(empty($video['cid'])){
                return array('class'=>'warning','text'=>'无绑定栏目跳过');
            }
        }


        //没有编辑过或者新增
        if(empty($_video['weight']) && empty($video_id)){
            $video['postime'] = date("Y-m-d H:i:s");
            $video['status']  = self::$video['status']?:0;
            $video['postype'] = 1;
            $video['title']    = iSecurity::escapeStr($title);
            $video['alias']    = iSecurity::escapeStr($value['nickname']);
            $video['tags']     = iSecurity::escapeStr($value['keywords']);
            $video['cycle']    = iSecurity::escapeStr($value['reweek']);
            $video['tv']       = iSecurity::escapeStr($value['tvs']);
            $video['company']  = iSecurity::escapeStr($value['company']);
            $video['total']    = iSecurity::escapeStr($value['episode']);
            $video['total']    = iSecurity::escapeStr($value['total']);
            $video['time']     = iSecurity::escapeStr($value['len']);

            $video['release']  =
            $video['year']     = iSecurity::escapeStr($value['year']);
            $video['pic']      = iSecurity::escapeStr($value['pic']);
            $video['pic_http'] = 1;
            $video['genre']    = iSecurity::escapeStr($value['genre']);
            $video['actor']    = iSecurity::escapeStr($value['actor']);
            $video['director'] = iSecurity::escapeStr($value['director']);
            $video['language'] = iSecurity::escapeStr($value['lang']);
            $video['area']     = iSecurity::escapeStr($value['area']);

            $des = (string)$value['des'];
            $video['description'] = html2text($des);
            $video['body']        = autoformat($des);
        }
        if(empty($vid)){
            $video['pubdate']  = iSecurity::escapeStr($value['last']);
            empty($video['pubdate']) && $video['pubdate'] = $video['postime'];
            $video['remark']   = iSecurity::escapeStr($value['note']);
            $video = array_filter($video);
        }

        $ddArray  = $value['dl']['dd'];
        if(!is_array($ddArray)){
            if(strpos($ddArray, '$$$')!==false){
                $ddArray  = preg_split('/\$\$\$\w+\$\$/', $ddArray);
            }else{
                $ddArray  = (array)$ddArray;
            }
        }

        $ddCount  = count($ddArray);
        $resource = array();
        for($i=0;$i<$ddCount;$i++){
            if($ddArray[$i]){
                $vars   = explode("#", $ddArray[$i]);
                $pieces = array();
                foreach ($vars as $k => $v) {
                    $v = trim($v);
                    $p = explode('$', $v);
                    $c = count($p);
                    if($c=="3"){
                        $from = iFS::get_ext($p[1]);
                        $from!='m3u8' && $from = $p[2];unset($p[2]);
                        empty($from) && $from = self::get_from($p[1]);
                    }elseif($c=="2"){
                        foreach ($p as $pk => $pv) {
                            if(iHttp::is_url($pv)){
                                $np = array("第".($k+1)."集",$pv);
                                $from = iFS::get_ext($pv);
                                empty($from) && $from = self::get_from($pv);
                                break;
                            }
                        }
                        $np && $p = $np;
                    }else{
                        $from = iFS::get_ext($v);
                        empty($from) && $from = self::get_from($v);
                        $p = array("第".($k+1)."集",$v);
                    }
                    $pieces[$k] = implode('$', $p);
                }
                if($pieces){
                    $resource[$i]['src']    = implode("\n", $pieces);
                    $resource[$i]['status'] = 1;
                    $resource[$i]['type']   = 0;
                    $resource[$i]['from']   = $from;
                    $resource[$i]['update'] = time();
                }
            }
        }

        if(empty($resource)){
            return array('class'=>'warning','text'=>'无资源跳过');
        }

        if($video){
            iSecurity::slashes($video);
            if(empty($video_id)){
                $_POST = $video;
                $videoAdmincp = new videoAdmincp();
                $videoAdmincp->callback['save:return'] = '1000';
                $result = $videoAdmincp->do_save();
                if($result=='1000'){
                    $video_id = $videoAdmincp->callback['indexid'];
                    self::video_spider($value['id'],$video_id,$rid);
                }
            }else{
                $video['pubdate'] = str2time($video['pubdate']);
                video::update($video,array('id'=>$video_id));
            }
        }

        foreach ($resource as $key => $value) {
            video_resourceAdmincp::save($value,$video_id);
        }

    	return array('class'=>'success','text'=>$msg.'Завершить');
    }
    public static function video_spider_check($data,$source=null) {
        $sql = "SELECT `video_id` FROM `#iCMS@__video_spider` where `data` = '$data'";
        $source && $sql.=" AND `source` ='$source'";
        return iDB::value($sql);
    }
    public static function video_spider_del($video_id,$source=null) {
        $sql = "DELETE FROM `#iCMS@__video_spider` where `video_id` = '$video_id'";
        $source && $sql.=" AND `source` ='$source'";
        return iDB::query($sql);
    }
    public static function video_spider($data,$video_id,$source=null) {
        iDB::insert('video_spider',array('data'=>$data,'video_id'=>$video_id,'source'=>$source),true);
    }
    public static function get_config($rid,$t) {
        $config = configAdmincp::get(self::$conf[$t][0],self::$conf[$t][1]);
        if($rid===null){
            return $config;
        }
        return $config[$rid];
    }
    public static function set_config($rid,$value,$t) {
        $_POST['config'] = configAdmincp::get(self::$conf[$t][0],self::$conf[$t][1]);
        if(empty($_POST['config'])){
            $_POST['config'][$rid] = array();
        }
        if($value==='delete'){
            unset($_POST['config'][$rid]);
        }else{
            $_POST['config'][$rid] = $value;
        }
        configAdmincp::save(self::$conf[$t][0],self::$conf[$t][1]);
    }
    public static function api_get($url,$q=null) {
        $xml   = self::remote($url,$q);
        if(strpos($xml, '<ty id')){
            preg_match_all('@<ty id="(\d+)">(.*?)</ty>@', $xml, $matches);
            $class = array();
            if($matches[1])foreach ($matches[1] as $k => $id) {
                $class[$id] = $matches[2][$k];
            }
        }
        $array = iUtils::xmlToArray($xml);
        $class && $array['class']['ty'] = $class;
        return $array;
    }

    public static function remote($url,$query=null){
        iHttp::$CURLOPT_TIMEOUT        = 60;
        iHttp::$CURLOPT_CONNECTTIMEOUT = 10;
        iHttp::$CURLOPT_REFERER        = 'http://www.baidu.com';
        iHttp::$CURLOPT_USERAGENT      = 'Mozilla/5.0 (compatible; Baiduspider/2.0; +http://www.baidu.com/search/spider.html)';
		$url = iURL::make($query,$url);
        // var_dump($url);
		$data = iHttp::remote($url);
        // var_dump($data);
        return $data;
    }
    public static function get_from($url){
        if(strpos($url, 'v.qq.com')!==false){
            $from='qq';
        }elseif(strpos($url, 'v.youku.com')!==false||strpos($url, 'm.youku.com')!==false){
            $from='youku';
        }elseif(strpos($url, 'www.mgtv.com')!==false||strpos($url, 'm.mgtv.com')!==false){
            $from='mgtv';
        }elseif(strpos($url, 'bilibili.com')!==false){
            $from='bilibili';
        }elseif(strpos($url, 'www.iqiyi.com')!==false){
            $from='iqiyi';
        }elseif(strpos($url, 'film.sohu.com')!==false){
            $from='sohu';
        }else{
            $from='http';
        }
        return $from;
    }
}
