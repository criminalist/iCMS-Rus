<?php

defined('iPHP') OR exit('Oops, something went wrong');

class article_categoryAdmincp extends categoryAdmincp {
    public function __construct() {
        parent::__construct(iCMS_APP_ARTICLE,'category');
        $this->category_name            = "Категория";
        $this->_app                     = 'article';
        $this->_app_name                = 'Статьи';
        $this->_app_table               = 'article';
        $this->_app_cid                 = 'cid';
        
        $this->category_template+=array(
            'article' => array('Статьи','{iTPL}/article.htm'),
            'tag'     => array('Теги','{iTPL}/tag.htm'),
        );

       
        $this->category_rule+= array(
            'article' => array('Статьи','/{CDIR}/{YYYY}/{MM}{DD}/{ID}{EXT}','{ID},{0xID},{LINK},{Hash@ID},{Hash@0xID}'),
            'tag'     => array('Теги','/{CDIR}/t-{TKEY}{EXT}','{ID},{0xID},{TKEY},{NAME},{RUS},{Hash@ID},{Hash@0xID}')
        );
        
        $this->category_rule_list+= array();
    }
}
