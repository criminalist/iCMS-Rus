<?php

class filterApp{
    public static $disable = array();
    public static $filter  = array();

    public function __construct() {
        $this->appid = iCMS_APP_FILTER;
    }
    /**
     * [Поиск запрещенных символов, и вернуть FLASE ли TRUE]
     * @param [string] $content
     * @return [string]         
     */
    public static function HOOK_disable_FALSE($content){
        $disable = self::$disable?:iCache::get('filter/disable');  //disable
        //Запрещенные символы
        $subject = implode('', (array)$content);
        $pattern = '/(~|`|!|@|\#|\$|%|\^|&|\*|\(|\)|\-|=|_|\+|\{|\}|\[|\]|;|:|"|\'|<|>|\?|\/|,|\.|\s|\n|.|,|, |；|:|?|!|…|-|·|ˉ|ˇ|¨|‘|“|”|々|～|‖|∶|＂|＇|｀|｜|〃|〔|〕|〈|〉|《|》|「|」|『|』|．|〖|〗|[|]|(|)|［|］|｛|｝|°|′|″|＄|￡|￥|‰|％|℃|¤|￠|○|§|№|☆|★|○|●|◎|◇|◆|□|■|△|▲|※|→|←|↑|↓|〓|＃|＆|＠|＾|＿|＼|№|)*/i';
        $subject = preg_replace($pattern, '', $subject);
        foreach ((array)$disable AS $val) {
            $val = trim($val);
            if(strpos($val,'::')!==false){
                list($tag,$start,$end) = explode('::',$val);
                if($tag=='NUM'){
                    $subject = cnum($subject);
                    if (preg_match('/\d{'.$start.','.$end.'}/i', $subject)) {
                        return $val;
                    }
                }
            }else{
                if ($val && preg_match("/".preg_quote($val, '/')."/i", $subject)) {
                    return $val;
                }
            }
        }
    }
    /**
     * [Фильтрация замены ключевых слов]
     * @param [sting] $content
     * @return [string]
     */
    public static function HOOK_filter($content){
        $filter  = self::$filter?:iCache::get('filter/array');
        if($filter){
            
            foreach ((array)$filter AS $k =>$val) {
                $val = trim($val);
                if($val){
                    $exp = explode("=", $val);
                    empty($exp[1]) && $exp[1] = '***';
                    $search[$k]  = '/'.preg_quote($exp[0], '/').'/i';
                    $replace[$k] = $exp[1];
                }

            }
            $search && $content = preg_replace($search,$replace,$content);
        }
        return $content;
    }

    /**
     * [run 先判断后过滤]
     * @param  [array] &$content [引用内容]
     * @return [sting]           [返回内容]
     */
    public static function run(&$content){
        if($result = self::HOOK_disable_FALSE($content)){
            return $result;
        }

        $content = self::HOOK_filter($content);
    }
}
