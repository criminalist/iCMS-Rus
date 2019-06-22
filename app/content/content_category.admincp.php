<?php

defined('iPHP') OR exit('Oops, something went wrong');

class content_categoryAdmincp extends categoryAdmincp {
    public function __construct($app) {
        $table = apps::get_table($app);
        parent::__construct($app['id'],'category');

        $this->category_name   = "Категория";
        $this->_app            = $app['app'];
        $this->_app_name       = $app['title'];
        $this->_app_table      = $table['name'];
        $this->_app_cid        = 'cid';
        /**
         *  模板
         */
        $this->category_template = array(
            'index'     => array('Главная','{iTPL}/content.index.htm'),
            'list'      => array('Список','{iTPL}/content.list.htm'),
            $app['app'] => array($app['title'],'{iTPL}/content.htm'),
            'tag'      => array('Тег','{iTPL}/content.tag.htm'),
        );

        /**
         *  URL规则
         */
        $this->category_rule+= array(
            $app['app'] => array($app['title'],'/{CDIR}/{YYYY}/{MM}{DD}/{ID}{EXT}','{ID},{0xID},{LINK},{Hash@ID},{Hash@0xID}'),
            'tag'       => array('Тег','/{CDIR}/t-{TKEY}{EXT}','{ID},{0xID},{TKEY},{NAME},{EN_EN},{Hash@ID},{Hash@0xID}')
        );
        /**
         *  URL规则选项
         */
        // $this->category_rule_list+= array(

        // );
    }
    // public function do_add(){
    //     $this->_view_tpl_dir = $this->_app;
    //     parent::do_add();
    // }
}
