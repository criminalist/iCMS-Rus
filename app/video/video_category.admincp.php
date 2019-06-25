<?php

defined('iPHP') OR exit('What are you doing?');

class video_categoryAdmincp extends categoryAdmincp {
    public function __construct() {
        parent::__construct(iCMS_APP_VIDEO,'category');
        $this->category_name            = "栏目";
        $this->_app                     = 'video';
        $this->_app_name                = '视频';
        $this->_app_table               = 'video';
        $this->_app_cid                 = 'cid';
        /**
         *  模板
         */
        $this->category_template=array(
            'index'       => array('首页','{iTPL}/video.index.htm'),
            'list'        => array('列表','{iTPL}/video.list.htm'),
            'video'       => array('视频','{iTPL}/video.info.htm'),
            'video:play'  => array('播放','{iTPL}/video.play.htm'),
            'video:down'  => array('下载','{iTPL}/video.down.htm'),
            'video:story' => array('剧情','{iTPL}/video.story.htm'),
            'tag'         => array('标签','{iTPL}/video.tag.htm'),
        );

        /**
         *  URL规则
         */
        $this->category_rule+= array(
            'video'       => array('视频','/video/{ID}{EXT}','{ID},{0xID},{LINK},{Hash@ID},{Hash@0xID}'),
            'video:play'  => array('播放','/play/{VIDEO:ID}/{ID}{EXT}','{VIDEO:ID},{ID},{0xID},{Hash@ID},{Hash@0xID}'),
            'video:down'  => array('下载','/down/{VIDEO:ID}/{ID}{EXT}','{VIDEO:ID},{ID},{0xID},{Hash@ID},{Hash@0xID}'),
            'video:story' => array('剧情','/story/{VIDEO:ID}/{ID}{EXT}','{VIDEO:ID},{ID},{0xID},{Hash@ID},{Hash@0xID}'),
            'tag'         => array('标签','/video/{TKEY}{EXT}','{ID},{0xID},{TKEY},{NAME},{RUS},{Hash@ID},{Hash@0xID}')
        );
        /**
         *  URL规则选项
         */
        $this->category_rule_list+= array(
            'video:play'  => array(array('{VIDEO:ID}','视频ID')),
            'video:down'  => array(array('{VIDEO:ID}','视频ID')),
            'video:story' => array(array('{VIDEO:ID}','视频ID')),
        );
    }
}
