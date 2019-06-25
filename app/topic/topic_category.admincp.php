<?php

defined('iPHP') OR exit('What are you doing?');

class topic_categoryAdmincp extends categoryAdmincp {
    public function __construct() {
        parent::__construct(iCMS_APP_TOPIC,'category');
        $this->category_name            = "分类";
        $this->_app                     = 'topic';
        $this->_app_name                = '专题';
        $this->_app_table               = 'topic';
        $this->_app_cid                 = 'tcid';
        /**
         *  模板
         */
        $this->category_template = array(
            'index' => array('首页','{iTPL}/topic.index.htm'),
            'list'  => array('列表','{iTPL}/topic.list.htm'),
            'topic' => array('专题','{iTPL}/topic.htm'),
        );

        /**
         *  URL规则
         */
        $this->category_rule+= array(
            'topic' => array('专题','/topic/{ID}/','{ID},{0xID},{LINK}')
        );
        /**
         *  URL规则选项
         */
        $this->category_rule_list+= array(
            'topic' => array(
                array('----'),
                array('{ID}','专题ID'),
                array('{0xID}','8位ID'),
                array('{LINK}','自定义链接'),
                array('{0x3ID}','8位ID(前3位)',false),
                array('{0x3,2ID}','8位ID',false),
                array('{TITLE}','专题标题',false),
            )
        );
    }
    public function do_add($default=null){
        parent::do_add(array('status'=> '2'));
    }
}
