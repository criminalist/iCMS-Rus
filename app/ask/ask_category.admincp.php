<?php
/**
* iCMS - i Content Management System
* Copyright (c) 2007-2017 iCMSdev.com. All rights reserved.
*
* @author icmsdev <master@icmsdev.com>
* @site https://www.icmsdev.com
* @licence https://www.icmsdev.com/LICENSE.html
*/
defined('iPHP') OR exit('What are you doing?');

class ask_categoryAdmincp extends categoryAdmincp {
    public function __construct() {
        parent::__construct(iCMS_APP_ASK,'category');
        $this->category_name     = "分类";
        $this->_app              = 'ask';
        $this->_app_name         = '主题';
        $this->_app_table        = 'ask';
        $this->_app_cid          = 'cid';
       /**
         *  模板
         */
        $this->category_template = array(
            'index' => array('首页','{iTPL}/ask.index.htm'),
            'list'  => array('列表','{iTPL}/ask.list.htm'),
            'ask'   => array('主题','{iTPL}/ask.htm'),
            'tag'   => array('标签','{iTPL}/ask.tag.htm'),
        );

        /**
         *  URL规则
         */
        $this->category_rule+= array(
            'ask' => array('主题','/ask/{ID}{EXT}','{ID},{0xID},{LINK}'),
            'tag' => array('标签','/ask/{TKEY}{EXT}','{ID},{0xID},{TKEY},{NAME},{ZH_CN}')
        );
        /**
         *  URL规则选项
         */
        $this->category_rule_list+= array(
            'ask' => array(
                array('----'),
                array('{ID}','主题ID'),
                array('{0xID}','8位ID'),
                array('{LINK}','自定义链接'),
                array('{0x3ID}','8位ID(前3位)',false),
                array('{0x3,2ID}','8位ID',false),
                array('{TITLE}','主题标题',false),
            ),
            'tag' => array(
                array('----'),
                array('{ID}','标签ID'),
                array('{0xID}','8位ID'),
                array('{TKEY}','标签标识'),
                array('{ZH_CN}','标签名(中文)'),
                array('{NAME}','标签名'),
                array('----'),
                array('{TCID}','分类ID',false),
                array('{TCDIR}','分类目录',false),
            ),
        );
    }
}
