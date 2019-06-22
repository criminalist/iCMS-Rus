<?php

class groupAdmincp{
    public $gid   = NULL;
    public $group = NULL;
    public $array = NULL;
    public $type  = NULL;

    public function __construct($type=null) {
    	$this->gid	= (int)$_GET['gid'];
    	if($type!==null){
    		$this->type = $type;
    		$sql=" and `type`='$type'";
    	}
		$rs		= iDB::all("SELECT * FROM `#iCMS@__group` where 1=1{$sql} ORDER BY `sortnum` , `gid` ASC");

        array_unshift($rs,
            array('gid'=>'0','type'=>$this->type,'name'=>'Гость'),
            array('gid'=>'65535','type'=>'0','name'=>'Супер администратор')
        );
        $_count = count($rs);
		for ($i=0;$i<$_count;$i++){
			$this->array[$rs[$i]['gid']] = $rs[$i];
			$this->group[$rs[$i]['type']][$rs[$i]['gid']] = $rs[$i];
		}
    }
    public function do_iCMS(){
        $this->do_manage();
    }
    public function do_add(){
        $this->gid && $rs = iDB::row("SELECT * FROM `#iCMS@__group` WHERE `gid`='$this->gid' LIMIT 1;");
        if($rs){
            $rs->config = json_decode($rs->config,true);
        }
        include admincp::view("group.add");
    }
    public function do_manage(){
        $rs     = iDB::all("SELECT * FROM `#iCMS@__group` ORDER BY `type` , `gid` ASC");
        $_count = count($rs);
        include admincp::view("group.manage");
    }
    public function do_del($gid = null,$dialog=true){
    	$gid===null && $gid=$this->gid;
		$gid OR iUI::alert('Выберите группу пользователей, которую вы хотите удалить');
		$gid=="1" && iUI::alert('Невозможно удалить системную группу супер администратора');
		iDB::query("DELETE FROM `#iCMS@__group` WHERE `gid` = '$gid'");
		$dialog && iUI::success('Удаление группы пользователей завершено','js:parent.$("#id'.$gid.'").remove();');
    }
    public function do_batch(){
    	list($idArray,$ids,$batch) = iUI::get_batch_args("Выберите группу пользователей, которую вы хотите удалить");
    	switch($batch){
    		case 'dels':
				iUI::$break	= false;
	    		foreach($idArray AS $id){
	    			$this->do_del($id,false);
	    		}
	    		iUI::$break	= true;
				iUI::success('Успешно удалено!','js:1');
    		break;
		}
	}
	public function do_save(){
		$gid    = intval($_POST['gid']);
		$type   = intval($_POST['type']);
		$name   = iSecurity::escapeStr($_POST['name']);

        $config = (array)$_POST['config'];
        $config = addslashes(json_encode($config));

		$name OR iUI::alert('Имя группы не может быть пустым');
		$fields = array('name', 'sortnum', 'config', 'type');
		$data   = compact ($fields);
		if($gid){
            iDB::update('group', $data, array('gid'=>$gid));
			$msg = "Изменения группы завершено!";
		}else{
			iDB::insert('group',$data);
			$msg = "Группа успешно добавлена!";
		}
		iUI::success($msg,'url:'.APP_URI);
	}
    public function select($type=null,$currentid=NULL){
        $type===null && $type = $this->type;
        if($this->group[$type])foreach($this->group[$type] AS $G){
            $selected=($currentid==$G['gid'])?" selected='selected'":'';
            $option.="<option value='{$G['gid']}'{$selected}>".$G['name']."[GID:{$G['gid']}] </option>";
        }
        return $option;
    }
}
