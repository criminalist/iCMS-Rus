<?php

defined('iPHP') OR exit('Oops, something went wrong');

class patchAdmincp{

	public function __construct() {
		$this->msg   = "";
		if(isset($_GET['git'])){
			patch::$release = $_GET['release'];
			patch::$zipName = $_GET['zipname'];
			// patch::$test = true;
		}else{
			$this->patch = patch::init(isset($_GET['force'])?true:false);
		}
	}
    
    public static function check_update() {
        include admincp::view("check_update","patch");
    }
    public function do_check(){
		if(empty($this->patch)){
			if($_GET['ajax']){
				iUI::json(array('code'=>0));
			}else{
				iUI::success("Версия iCMS, которую вы используете, в настоящее время является последней версией<hr />Текущая версия:iCMS ".iCMS_VERSION." [".iCMS_RELEASE."]",0,"5");
			}
		}else{
	    	switch(iCMS::$config['system']['patch']){
	    		case "1":
					$this->msg = patch::download($this->patch[1]);
					$json      = array(
						'code' => "1",
						'url'  => __ADMINCP__.'=patch&do=install',
						'msg'  => "Найдена новая версия системы<br /><span class='label label-warning'>iCMS ".$this->patch[0]." [".$this->patch[1]."]</span><br />".$this->patch[3]."<hr />Версия, которую вы используете в настоящее время<br /><span class='label label-info'>iCMS ".iCMS_VERSION." [".iCMS_RELEASE."]</span><br /><br />Новая версия была загружена! Обновить сейчас?",
		    		);
	    		break;
	    		case "2"://Не загружать обновления автоматически
		    		$json	= array(
						'code' => "2",
						'url'  => __ADMINCP__.'=patch&do=download',
						'msg'  => "Найдена новая версия системы<br /><span class='label label-warning'>iCMS ".$this->patch[0]." [".$this->patch[1]."]</span><br />".$this->patch[3]."<hr />Версия, которую вы используете в настоящее время<br /><span class='label label-info'>iCMS ".iCMS_VERSION." [".iCMS_RELEASE."]</span><br /><br />Пожалуйста, обновите вашу систему сейчас!",
		    		);
	    		break;
	    	}
	    	if($_GET['ajax']){
	    		iUI::json($json,true);
	    	}
		    $moreBtn=array(
		            array("text"=>"Обновить сейчас","url"=>$json['url']),
		            array("text"=>"Не сейчас","js" =>'return true'),
		    );
    		iUI::dialog('success:#:check:#:'.$json['msg'],0,30,$moreBtn);
		}
    }
    /**
     * [Загрузите пакет обновления]
     */
    public function do_download(){
		$this->msg = patch::download();//Скачать файл пакета
		include admincp::view("patch");
    }
    /**
     * [Установка пакета]
     */
    public function do_install(){
        patch::setTime();
        $this->msg  = patch::update();
        $is_upgrade = patch::$upgrade;
		  include admincp::view("patch");
    }
    /**
     * [Обновление]
     */
    public function do_upgrade(){
        $this->msg  = patch::run();
        $is_upgrade = patch::$upgrade;
        include admincp::view("patch");
    }
    public function do_check_upgrade(){
        $json = array('code' => "0");
        $files = patch::get_upgrade_files(true);
        if($files){
            foreach ($files as $d => $value) {
              $text.='<span class="span3">№#'.$d.'#</span>';
            }
            $json = array(
                'code' => "1",
                'url'  => __ADMINCP__.'=patch&do=upgrade&force=1',
                'msg'  => "Найдены обновления!<br /><div>".$text."</div><hr class='clearfix'/>Обновить сейчас?",
            );
        }
        iUI::json($json,true);
    }
    public static function check_upgrade() {
        include admincp::view("check_upgrade","patch");
    }
    //===================git=========
    /**
     * [Проверка обновления на GITHUB]
     */
    public function do_git_check(){
    	$log =  patch::git('log');
    	include admincp::view("git.log");
    }
    /**
     * [Загрузите пакет обновления для разработчиков]
     */
    public function do_git_download(){
    	$zip_url = patch::git('zip',null,'url');
		$release = $_GET['release'];
		$zipName = str_replace(patch::PATCH_URL.'/', '', $zip_url);

		// patch::$release = $release;
		// patch::$zipName = $zipName;
		// $this->do_download();
		iPHP::redirect(APP_URI.'&do=download&release='.$release.'&zipname='.$zipName.'&git=true');
    }
    /**
     * [Получить информацию о версии разработки]
     */
    public function do_git_show(){
    	$log =  patch::git('show');
        $type_map = array(
          'D'=>'Удалить',
          'A'=>'Добавить',
          'M'=>'Редактировать'
        );
    	include admincp::view("git.show");
    }
    /**
     * [Получить информацию о версии]
     */
    public static function do_version() {
        echo patch::version();
    }
    public static function check_version() {
        include admincp::view("check_version","patch");
    }
}
