<?php

class templateAdmincp{
    public function __construct() {
    }
    public function do_iCMS(){
        $res       = iFS::folder('template',array('htm','css','js','png','jpg','gif'));
        $dirRs     = $res['DirArray'];
        $fileRs    = $res['FileArray'];
        $pwd       = $res['pwd'];
        $parent    = $res['parent'];
        $URI       = $res['URI'];
        $navbar    = true;
        $file_edit = true;

    	include admincp::view("files.explorer","files");
    }

}
