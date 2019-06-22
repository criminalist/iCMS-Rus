<?php

class searchAdmincp{
    public function __construct() {
        $this->appid = iCMS_APP_SEARCH;
    	$this->id	= (int)$_GET['id'];
    }
    public function do_save_config(){
        $disable = explode("\n",$_POST['config']['disable']);
        $_POST['config']['disable'] = array_unique($disable);
        configAdmincp::save('999999','search',array($this,'cache'));
    }

    public static function cache($config=null){
        $config===null && $config  = configAdmincp::get('999999','filter');
        iCache::set('search/disable',$config['disable'],0);
    }

    public function do_iCMS(){
        $config = configAdmincp::app('999999','search',true);
        if($_GET['keywords']) {
			$sql =" WHERE `search` like '%{$_GET['keywords']}%'";
        }

        list($orderby,$orderby_option) = get_orderby(array(
            'id'    =>"ID",
            'times' =>"Время поиска",
        ));
        $maxperpage = $_GET['perpage']>0?(int)$_GET['perpage']:20;
        $total      = iPagination::totalCache("SELECT count(*) FROM `#iCMS@__search_log` {$sql}","G");
        iUI::pagenav($total,$maxperpage,"Записи");
        $rs     = iDB::all("SELECT * FROM `#iCMS@__search_log` {$sql} order by {$orderby} LIMIT ".iPagination::$offset." , {$maxperpage}");
        $_count = count($rs);
    	include admincp::view("search.manage");
    }
    public function do_del($id = null,$dialog=true){
    	$id===null && $id=$this->id;
		$id OR iUI::alert('Выберите запись для удаления!');
		iDB::query("DELETE FROM `#iCMS@__search_log` WHERE `id` = '$id'");
		$dialog && iUI::success('Запись была удалена','js:parent.$("#id'.$id.'").remove();');
    }
    public function do_batch(){
        list($idArray,$ids,$batch) = iUI::get_batch_args("Выберите запись");
    	switch($batch){
    		case 'dels':
				iUI::$break	= false;
	    		foreach($idArray AS $id){
	    			$this->do_del($id,false);
	    		}
	    		iUI::$break	= true;
				iUI::success('Удалить все записи!','js:1');
    		break;
		}
	}
}
