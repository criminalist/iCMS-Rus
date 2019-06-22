<?php

class plugin_textad {
    /**
     * [Плагин:вставка рекламы]
     * @param [type] $content [Параметры]
     */
    public static function HOOK($content,&$resource=null){
        plugin::init(__CLASS__);
        $pieces    = 1000;
        $html      = str_replace('</p>', "</p>\n", $content);
        $htmlArray = explode("\n", $html);
        $result  = array();
        
        $imgs = files::preg_img($content,$pic_array);
        $len  = strlen($content)+(count($imgs)*300);

        if($len<($pieces*1.5)){
            return $content;
        }
        $i = 0;
        foreach ($htmlArray as $key => $phtm) {
            $pLen += strlen($phtm);
            if(strpos($phtm, '<img')!==false){
                $pLen +=100;
            }
            $llen = $len-$pLen;
            // if($_GET['debug']){
            //     var_dump(substr($phtm, 0,30));
            //     var_dump($pLen,$llen,floor($llen/$pieces),'=========');
            // }
            if($pLen>$pieces && floor($llen/$pieces)>=1){
                // if($_GET['debug']){
                //     var_dump('---------------------------');
                // }
                $ad = '<script>if(typeof showBodyUI==="function")showBodyUI("body.'.$i.'");</script>';
                $result[$i].= $phtm.$ad;
                $pLen = 0;
                $len = $llen;
                $i++;
            }else{
                $result[$i].= $phtm;
            }
        }
        unset($html,$htmlArray);
        return implode('', (array)$result);
    }
}
