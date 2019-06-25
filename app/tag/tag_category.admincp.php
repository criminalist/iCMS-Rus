<?php

defined('iPHP') OR exit('Oops, something went wrong');

class tag_categoryAdmincp extends categoryAdmincp {
    public function __construct() {
        parent::__construct(iCMS_APP_TAG,'category');
        $this->category_name     = "Категории";
        $this->_app              = 'tag';
        $this->_app_name         = 'Тег';
        $this->_app_table        = 'tag';
        $this->_app_cid          = 'tcid';
       
        $this->category_template+=array(
            'tag'     => array('Тег','{iTPL}/tag.htm'),
        );

        
        $this->category_rule+= array(
            'tag'     => array('Тег','/tag/{TKEY}{EXT}','{ID},{0xID},{TKEY},{NAME},{RUS},{Hash@ID},{Hash@0xID}')
        );
        /**
         *  Параметры формирования URL
         */
        $this->category_rule_list+= array();
    }
    public function do_add($default=null){
        parent::do_add(array('status'=> '2'));
    }
}
