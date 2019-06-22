<?php

defined('iPHP') OR exit('What are you doing?');

class push_categoryAdmincp extends categoryAdmincp {
    public function __construct() {
        parent::__construct(iCMS_APP_PUSH,'category');
        $this->category_name   = "分类";
        $this->_app            = 'push';
        $this->_app_name       = '推荐';
        $this->_app_table      = 'push';
        $this->_app_cid        = 'cid';
       // /**
       //   *  模板
       //   */
       //  $this->category_template+=array(
       //      'push' => array('推荐','{iTPL}/push.index.htm'),
       //  );

       //  /**
       //   *  URL规则
       //   */
       //  $this->category_rule+= array(
       //      'push' => array('推荐','/{CDIR}/{TKEY}{EXT}','{ID},{0xID}')
       //  );
       //  /**
       //   *  URL规则选项
       //   */
       //  $this->category_rule_list+= array(
       //      'tag' => array(
       //          array('----'),
       //          array('{ID}','推荐ID'),
       //          array('{0xID}','8位ID'),
       //      ),
       //  );
    }
    public function do_add($default=null){
        $this->_view_tpl_dir = $this->_app;
        parent::do_add(array('status'=> '2'));
    }
}
