<?php

class iHttp{
    public static $callback   = array();
    public static $handle     = null;
    public static $debug      = true;
    public static $PROXY_URL  = null;
    public static $SAFE_PORT  = array('80','443');//检测url端口
    public static $SAFE_URL   = false; //是否检测url安全性 影响性能
    public static $SAFE_CHECK = false; //是否检测url安全性

    public static $CURL_MULTI             = false;
    public static $CURL_COUNT             = 3;
    public static $CURL_HTTP_CODE         = null;
    public static $CURL_CONTENT_TYPE      = null;
    public static $CURL_PROXY             = null;
    public static $CURL_PROXY_IP          = null;
    public static $CURL_PROXY_ARRAY       = array();
    public static $CURL_INFO              = null;
    public static $CURL_ERRNO             = 0;
    public static $CURL_ERROR             = null;
    public static $CURLOPT_ENCODING       = '';
    public static $CURLOPT_REFERER        = null;
    public static $CURLOPT_TIMEOUT        = 10; //数据传输的最大允许时间
    public static $CURLOPT_CONNECTTIMEOUT = 5; //连接超时时间
    public static $CURLOPT_USERAGENT      = 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/38.0.2125.122 Safari/537.36';
    public static $CURLOPT_COOKIE         = null;
    public static $CURLOPT_COOKIEFILE     = null;
    public static $CURLOPT_COOKIEJAR      = null;
    public static $CURLOPT_HTTPHEADER     = null;
    public static $CURLOPT_OPTIONS        = array();

    protected static $remote_count  = 0;

    public static function is_url($url,$strict=false) {
        $url = trim($url);
        if($strict){
            return (stripos($url, 'http://') === 0 || stripos($url, 'https://') === 0);
        }

        if (stripos($url, 'http://') === false && stripos($url, 'https://') === false) {
            return false;
        } else {
            return true;
        }
    }
    public static function is_ajax() {
        return (
            $_SERVER["HTTP_X_REQUESTED_WITH"] == "XMLHttpRequest"||
            $_SERVER["X-Requested-With"] == "XMLHttpRequest"||
            isset($_GET['ajax'])||isset($_POST['ajax'])||
            isset($_GET['is_ajax'])||isset($_POST['is_ajax'])||
            $_GET['format']=='json'||$_POST['format']=='json'
        );
    }
    public static function is_safe($url) {
        $parsed = parse_url($url);
        $validate_ip = true;

        if($parsed['port'] && is_array(self::$SAFE_PORT) && !in_array($parsed['port'],self::$SAFE_PORT)){
            iPHP_SHELL && print 'iHttp::safe_url Request error! only allow access to port 80,443'.PHP_EOL;
            return false;
        }else{
            preg_match('/^\d+$/', $parsed['host']) && $parsed['host'] = long2ip($parsed['host']);
            $long = ip2long($parsed['host']);
            if($long===false){
                $ip = null;
                if(self::$SAFE_URL){//影响性能
                    @putenv('RES_OPTIONS=retrans:1 retry:1 timeout:1 attempts:1');
                    $ip   = gethostbyname($parsed['host']);
                    $long = ip2long($ip);
                    $long===false && $ip = null;
                    @putenv('RES_OPTIONS');
                }
            }else{
                $ip = $parsed['host'];
            }
            $ip && $validate_ip = filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE);
        }

        if(!in_array($parsed['scheme'],array('http','https')) || !$validate_ip){
            iPHP_SHELL && print 'iHttp::safe_url only allow access to scheme http,https OR Public IP address'.PHP_EOL;
            return false;
        }else{
            return $url;
        }
    }
    public static function proxy_test($options=null,$_count=0) {
        if (self::$callback['proxy_test'] && is_callable(self::$callback['proxy_test'])) {
            $test = call_user_func_array(self::$callback['proxy_test'],array($options));
            if($test===false) return false;
        }
        if(self::$CURL_PROXY_IP){
            $proxy = self::$CURL_PROXY_IP;
        }else{
            if (empty(self::$CURL_PROXY_ARRAY)) {
                if (self::$CURL_PROXY && is_string(self::$CURL_PROXY)) {
                    self::$CURL_PROXY_ARRAY = explode("\n", self::$CURL_PROXY); // socks5://127.0.0.1:1080@username:password
                    self::$CURL_PROXY = null;
                }
            }

            if (self::$callback['proxy_array'] && is_callable(self::$callback['proxy_array'])) {
                call_user_func_array(self::$callback['proxy_array'],array(&self::$CURL_PROXY_ARRAY));
            }

            self::$CURL_PROXY_ARRAY = array_unique(self::$CURL_PROXY_ARRAY);
            self::$CURL_PROXY_ARRAY = array_filter(self::$CURL_PROXY_ARRAY);

            if (empty(self::$CURL_PROXY_ARRAY)) {
                return false;
            }

            $index = array_rand(self::$CURL_PROXY_ARRAY, 1);
            $proxy = self::$CURL_PROXY_ARRAY[$index];
            $proxy = trim($proxy);
        }

        $options = array(
            CURLOPT_URL            => 'http://www.baidu.com',
            CURLOPT_REFERER        => 'http://www.baidu.com',
            CURLOPT_USERAGENT      => 'Mozilla/5.0 (compatible; Baiduspider/2.0; +http://www.baidu.com/search/spider.html)',
            CURLOPT_TIMEOUT        => 8,//self::$CURLOPT_TIMEOUT,
            CURLOPT_CONNECTTIMEOUT => 3,//self::$CURLOPT_CONNECTTIMEOUT,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_HEADER         => 1,
            CURLOPT_NOSIGNAL       => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        );

        self::proxy_set($options, $proxy);

        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $ret  = curl_exec($ch);
        $info = curl_getinfo($ch);
        $errno = curl_errno($ch);
        $curl_error = curl_error($ch);
        curl_close($ch);

        iPHP_SHELL && print date("Y-m-d H:i:s ").'iHttp::proxy_test [count:'.$_count.'][index:'.$index.'] => '.$proxy;
        if ($info['http_code'] == 200) {
            iPHP_SHELL && print ' SUCCESS...'.PHP_EOL;
            self::$CURL_PROXY_IP = $proxy;
            return $proxy;
        } else {
            iPHP_SHELL && print " FAIL... [errno:$errno] [error:$curl_error]".PHP_EOL;
            // iPHP_SHELL && print date("Y-m-d H:i:s ");
            // iPHP_SHELL && print PHP_EOL;
            self::$CURL_PROXY_IP = null;
            unset(self::$CURL_PROXY_ARRAY[$index]);
            ++$_count;
            return self::proxy_test($options,$_count);
        }
    }
    public static function proxy_set(&$options = array(), $proxy) {
        if ($proxy) {
            $proxy = trim($proxy);
            if (strpos($proxy, 'socks5://') === false) {
                // $options[CURLOPT_HTTPPROXYTUNNEL] = true;//HTTP代理开关
                $options[CURLOPT_PROXYTYPE] = CURLPROXY_HTTP; //使用http代理模式
            } else {
                $options[CURLOPT_PROXYTYPE] = CURLPROXY_SOCKS5;
            }
            list($url, $auth) = explode('@', $proxy);
            $url = str_replace(array('http://', 'socks5://'), '', $url);
            $options[CURLOPT_PROXY] = $url;
            $auth && $options[CURLOPT_PROXYUSERPWD] = $auth; //代理验证格式  username:password
            $options[CURLOPT_PROXYAUTH] = CURLAUTH_BASIC; //代理认证模式

        }
        if (self::$callback['proxy_set'] && is_callable(self::$callback['proxy_set'])) {
            $options = call_user_func_array(self::$callback['proxy_set'],array($options,$proxy));
        }
        return $options;
    }
    public static function get($url) {
        return self::remote($url);
    }
    public static function post($url,$postdata) {
        return self::remote($url,$postdata);
    }
    /**
     * [Загрузить файл]
     * @param  [type] $url      [description]
     * @param  array  $files    [description]
     * @param  [type] $postdata [description]
     * @return [type]           [description]
     */
    public static function upload($url,array $files,$postdata) {
        return self::remote($url,$postdata,$files);
    }
    //获取远程页面的内容
    public static function remote($url, $postdata = null,$files = array()) {
        function_exists('curl_init') OR print "curl extension is missing. Please check your PHP configuration".PHP_EOL;

        self::$remote_count++;
        $url = str_replace(array(' ','&amp;'), array('%20','&'), $url);
        (iPHP_SHELL && self::$debug) && print date("Y-m-d H:i:s ")."\033[36miHttp::remote\033[0m [".self::$remote_count."] => ".$url.PHP_EOL;

        if (empty($url)) {
            echo "url:empty\n";
            return false;
        }
        self::$SAFE_CHECK && $url = self::is_safe($url);

        if (self::$CURLOPT_REFERER === null) {
            $uri = parse_url($url);
            self::$CURLOPT_REFERER = $uri['scheme'] . '://' . $uri['host'];
        }
        if(stripos($url, 'mmbiz.qpic.cn')!==false){
            self::$CURLOPT_REFERER = 'http://weixin.qq.com';
        }
        $options = array(
            CURLOPT_URL                     => $url,
            CURLOPT_REFERER                 => self::$CURLOPT_REFERER,
            CURLOPT_USERAGENT               => self::$CURLOPT_USERAGENT,
            CURLOPT_ENCODING                => self::$CURLOPT_ENCODING,
            CURLOPT_TIMEOUT                 => self::$CURLOPT_TIMEOUT, //数据传输的最大允许时间
            CURLOPT_CONNECTTIMEOUT          => self::$CURLOPT_CONNECTTIMEOUT, //连接超时时间
            CURLOPT_RETURNTRANSFER          => 1,
            CURLOPT_FAILONERROR             => 0,
            CURLOPT_HEADER                  => 0,
            CURLOPT_NOSIGNAL                => true,
            // CURLOPT_DNS_USE_GLOBAL_CACHE => true,
            // CURLOPT_DNS_CACHE_TIMEOUT    => 86400,
            CURLOPT_SSL_VERIFYPEER          => false,
            CURLOPT_SSL_VERIFYHOST          => false,
            // CURLOPT_FOLLOWLOCATION       => 1,// 使用自动跳转
            // CURLOPT_MAXREDIRS            => 7,//查找次数,防止查找太深
        );
        if (self::$PROXY_URL) {
            $options[CURLOPT_URL] = self::$PROXY_URL.urlencode($url);
        }
        $options[CURLOPT_POST] = 0;

        if (is_array($files)) {
            if (class_exists('CURLFile',false)) {
                defined('CURLOPT_SAFE_UPLOAD') && $options[CURLOPT_SAFE_UPLOAD] = 1;
                foreach ($files as $key => $value) {
                    $postdata[$key] = new CURLFile($value);
                }
            } else {
                if (defined('CURLOPT_SAFE_UPLOAD')) {
                    if (version_compare('5.6',PHP_VERSION,'>=')) {
                        $options[CURLOPT_SAFE_UPLOAD] = 0;
                    }
                }
                foreach ($files as $key => $value) {
                    $postdata[$key] = "@$value";
                }
            }
        }
        if ($postdata!==null) {
            $options[CURLOPT_POST] = 1;
            $options[CURLOPT_POSTFIELDS] = $postdata;
        }
        if (self::$CURLOPT_COOKIE) {
            $options[CURLOPT_COOKIE] = self::$CURLOPT_COOKIE;
        }
        if (self::$CURLOPT_COOKIEFILE) {
            $options[CURLOPT_COOKIEFILE] = self::$CURLOPT_COOKIEFILE;
        }

        if (self::$CURLOPT_COOKIEJAR) {
            $options[CURLOPT_COOKIEJAR] = self::$CURLOPT_COOKIEJAR;
        }
        if (self::$CURLOPT_HTTPHEADER) {
            $options[CURLOPT_HTTPHEADER] = self::$CURLOPT_HTTPHEADER;
        }

        if (self::$CURL_PROXY || self::$CURL_PROXY_ARRAY) {
            $proxy = self::proxy_test($options);
            $proxy && self::proxy_set($options, $proxy);
        }
        if (defined('CURLOPT_IPRESOLVE') && defined('CURL_IPRESOLVE_V4')){
            $options[CURLOPT_IPRESOLVE] = CURL_IPRESOLVE_V4;
        }
        if (self::$callback['progress'] && is_callable(self::$callback['progress'])) {
            $options[CURLOPT_NOPROGRESS] = FALSE;
            $options[CURLOPT_PROGRESSFUNCTION] = array(__CLASS__,'progressCallback');
        }
        if (self::$callback['header'] && is_callable(self::$callback['header'])) {
            $options[CURLOPT_HEADERFUNCTION] = self::$callback['header'];
        }
        self::$CURLOPT_OPTIONS && $options = array_merge($options, self::$CURLOPT_OPTIONS);

        if (self::$callback['options'] && is_callable(self::$callback['options'])) {
            call_user_func_array(self::$callback['options'],array(&$options));
        }

        self::$handle = curl_init();
        curl_setopt_array(self::$handle, $options);

        if(self::$CURL_MULTI){
            self::$remote_count = 0;
            return self::$handle;
        }
        $responses = curl_exec(self::$handle);
        $info  = self::$CURL_INFO   = curl_getinfo(self::$handle);
        self::$CURL_ERRNO  = curl_errno(self::$handle);
        self::$CURL_ERRNO && self::$CURL_ERROR = curl_error(self::$handle);

        if (self::$callback['exec'] && is_callable(self::$callback['exec'])) {
            $flag = call_user_func_array(self::$callback['exec'],array($responses,$info));
            if($flag===FALSE){
                self::$remote_count = 0;
                return $responses;
            }
        }

        if (self::$CURL_HTTP_CODE !== null) {
            $code_array = self::$CURL_HTTP_CODE;
            is_array($code_array) OR $code_array = explode(',', $code_array);

            if (in_array($info['http_code'], $code_array)) {
                self::$remote_count = 0;
                return $responses;
            }
        }

        if ($info['http_code'] == 404 || $info['http_code'] == 500) {
            curl_close(self::$handle);
            echo $url . "\n";
            echo "http_code:" . $info['http_code'] . PHP_EOL;
            unset($responses, $info);
            self::$remote_count = 0;
            return false;
        }
        if (($info['http_code'] == 301 || $info['http_code'] == 302) && self::$remote_count < self::$CURL_COUNT) {
            $newurl = $info['redirect_url'];
            if (empty($newurl)) {
                curl_setopt(self::$handle, CURLOPT_HEADER, 1);
                $header = curl_exec(self::$handle);
                preg_match('|Location: (.*)|i', $header, $matches);
                $newurl = ltrim($matches[1], '/');
                if (empty($newurl)) {
                    self::$remote_count = 0;
                    return false;
                }

                if (!strstr($newurl, 'http://')) {
                    $host = $uri['scheme'] . '://' . $uri['host'];
                    $newurl = $host . '/' . $newurl;
                }
            }
            $newurl = trim($newurl);
            curl_close(self::$handle);
            unset($responses, $info);
            return self::remote($newurl, $postdata,$files);
        }

        if (self::$CURL_CONTENT_TYPE !== null && $info['content_type']) {
            if (stripos($info['content_type'], self::$CURL_CONTENT_TYPE) === false) {
                curl_close(self::$handle);
                echo $url.PHP_EOL;
                echo "content_type:" . $info['content_type'] .PHP_EOL.PHP_EOL;
                unset($responses, $info);
                self::$remote_count = 0;
                return false;
            }
        }

        if (self::$CURL_ERRNO > 0 || empty($responses) || empty($info['http_code'])) {
            if (self::$remote_count < self::$CURL_COUNT) {
                curl_close(self::$handle);
                unset($responses, $info);
                return self::remote($url,$postdata,$files);
            } else {
                curl_close(self::$handle);
                unset($responses, $info);
                echo $url . " iHttp::remote:".self::$remote_count.PHP_EOL;
                echo "cURL Error (".self::$CURL_ERRNO.") ".self::$CURL_ERROR.PHP_EOL.PHP_EOL;
                self::$remote_count = 0;
                return false;
            }
        }
        curl_close(self::$handle);
        self::$remote_count = 0;
        return $responses;
    }
    public static function send($url, $POSTFIELDS=null,$ret=false) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, self::$CURLOPT_TIMEOUT);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, self::$CURLOPT_CONNECTTIMEOUT);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        if($POSTFIELDS){
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $POSTFIELDS);
        }

        $response = curl_exec ($ch);
        // self::$debug && var_dump($response);
        curl_close ($ch);

        if($ret){
            return $response;
        }
        if(empty($response)){
            return '0000000';
        }
        return json_decode($response);
    }
    public static function progressCallback($resource, $download_total = 0, $downloaded_size = 0, $upload_total = 0, $uploaded_size = 0){
        if (version_compare(PHP_VERSION, '5.5.0') < 0) {
            $download_total  = $resource;
            $downloaded_size = $download_total;
            $upload_total    = $downloaded_size;
            $uploaded_size   = $upload_total;
            $resource        = null;
        }
        $args = array($resource, $download_total, $downloaded_size, $upload_total, $uploaded_size);
        call_user_func_array(self::$callback['progress'],array($args));
    }
}
