<?php

defined('iPHP') OR exit('Oops, something went wrong');
admincp::head();
?>
<div class="iCMS-container">
  <div class="notes"></div>
  <div class="row-fluid">
    <div class="span12 center" style="text-align: center;">
      <ul class="quick-actions">
        <li><a href="<?php echo __ADMINCP__; ?>=article&do=manage"><i class="icon-survey"></i>Документы</a></li>
        <li><a href="<?php echo __ADMINCP__; ?>=tag"><i class="icon-tag"></i>Теги</a></li>
        <li><a href="<?php echo __ADMINCP__; ?>=spider_project&do=manage"><i class="icon-download"></i>Парсер</a></li>
        <li><a href="<?php echo __ADMINCP__; ?>=user"><i class="icon-people"></i>Пользователи</a></li>
        <li><a href="<?php echo __ADMINCP__; ?>=database&do=backup"><i class="icon-database"></i>База данных</a></li>
        <li><a href="<?php echo __ADMINCP__; ?>=cache&do=all" target="iPHP_FRAME"><i class="icon-web"></i>Очистить кеш</a></li>
      </ul>
    </div>
  </div>
  <div class="widget-box">
    <div class="widget-title"> <span class="icon"> <i class="fa fa-signal"></i> </span>
      <h5>Статистика сайта</h5>
      <span id="counts" style="display: inline-block;margin-top: 6px; color: #999;">
        <img src="./app/admincp/ui/img/ajax_loader.gif" width="16" height="16" align="absmiddle">
        Загрузка данных ожидайте...
      </span>
    </div>
    <div class="widget-content">
      <div class="row-fluid">
        <div class="span3">
          <ul class="site-stats">
            <li><a href="<?php echo __ADMINCP__;?>=article_category"><i class="fa fa-sitemap"></i> <strong id="counts_acc">0</strong> <small> Категорий для новостей</small></a></li>
            <li><a href="<?php echo __ADMINCP__;?>=tag_category"><i class="fa fa-sitemap"></i> <strong id="counts_tcc">0</strong> <small>Категорий для тегов</small></a></li>
            <li><a href="<?php echo __ADMINCP__;?>=apps"><i class="fa fa-sitemap"></i> <strong id="counts_apc">0</strong> <small>Приложений</small> <span id="store_update" class="hide badge badge-success">0</span></a></li>
            <li class="divider"></li>
            <li><a href="<?php echo __ADMINCP__;?>=user"><i class="fa fa-user"></i> <strong id="counts_uc">0</strong> <small>Пользователей</small></a></li>
          </ul>
        </div>
        <div class="span3">
          <ul class="site-stats">
            <li><a href="<?php echo __ADMINCP__;?>=article&do=manage"><i class="fa fa-file-text"></i> <strong id="counts_ac">0</strong> <small>Общее количество материалов</small></a></li>
            <li><a href="<?php echo __ADMINCP__;?>=article&do=inbox"><i class="fa fa-file"></i> <strong id="counts_ac0">0</strong> <small>Черновиков</small></a></li>
            <li><a href="<?php echo __ADMINCP__;?>=article&do=trash"><i class="fa fa-file-o"></i> <strong id="counts_ac2">0</strong> <small>В корзине</small></a></li>
            <li class="divider"></li>
            <li><a href="<?php echo __ADMINCP__;?>=links"><i class="fa fa-heart"></i> <strong id="counts_lc">0</strong> <small>Ссылки друзей</small></a></li>
          </ul>
        </div>
        <div class="span3">
          <ul class="site-stats">
            <li><a href="<?php echo __ADMINCP__;?>=tag"><i class="fa fa-tag"></i> <strong id="counts_tc">0</strong> <small>Теги</small></a></li>
            <li><a href="<?php echo __ADMINCP__;?>=comment"><i class="fa fa-comment"></i> <strong id="counts_cc">0</strong> <small>Комментарии (вкл/выкл)</small></a></li>
            <li><a href="<?php echo __ADMINCP__;?>=keywords"><i class="fa fa-paperclip"></i> <strong id="counts_kc">0</strong> <small>Хотлинки</small></a></li>
            <li class="divider"></li>
            <li><a href="<?php echo __ADMINCP__;?>=prop"><i class="fa fa-thumb-tack"></i> <strong id="counts_pc">0</strong> <small> Свойства </small></a></li>
          </ul>
        </div>
        <div class="span3">
          <ul class="site-stats">
            <li><a href="<?php echo __ADMINCP__;?>=database&do=backup"><i class="fa fa-database"></i> <strong><?php echo iFS::sizeUnit($datasize+$indexsize) ; ?></strong> <small>База данных</small></a></li>
            <li><a href="<?php echo __ADMINCP__;?>=database&do=backup"><i class="fa fa-puzzle-piece"></i> <strong><?php echo count($iTable) ; ?></strong><small>iCMS表</small></a></li>
            <li><a href="<?php echo __ADMINCP__;?>=database&do=backup"><i class="fa fa-puzzle-piece"></i> <strong><?php echo $oTable?count($oTable):0 ; ?></strong> <small>其它表</small></a></li>
            <li class="divider"></li>
            <li><a href="<?php echo __ADMINCP__;?>=files"><i class="fa fa-files-o"></i> <strong id="counts_fc">0</strong> <small>Файлы</small></a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <div class="widget-box">
    <div class="widget-title"> <span class="icon"> <i class="fa fa-tachometer"></i> </span>
      <h5> Информация о системе </h5>
    </div>
    <div class="widget-content nopadding">
      <table class="table table-bordered table-striped">
        <tr>
          <td>Текущая версия программы</td>
          <td>iCMS <?php echo iCMS_VERSION ; ?>[git:<?php echo date("Y-m-d H:i",GIT_TIME) ; ?>]</td>
          <td><a href="<?php echo __ADMINCP__;?>=patch&do=check&force=1&frame=iPHP" target="iPHP_FRAME" id="home_patch">Последняя версия</a></td>
          <td><span id="iCMS_RELEASE"><img src="./app/admincp/ui/img/ajax_loader.gif" width="16" height="16" align="absmiddle"></span></td>
          <td><a href="<?php echo __ADMINCP__;?>=patch&do=git_check&git=true" data-toggle="modal" data-target="#iCMS-MODAL" data-meta="{&quot;width&quot;:&quot;85%&quot;,&quot;height&quot;:&quot;640px&quot;}" title="">Информация о версии разработки<span id="iCMS_GIT_UPDATE" class="hide badge badge-success">0</span></a></td>
          <td><span id="iCMS_GIT"><img src="./app/admincp/ui/img/ajax_loader.gif" width="16" height="16" align="absmiddle"></span></td>
        </tr>
        <tr>
          <td>Серверная операционная система</td>
          <td><?php $os = explode(" ", php_uname()); echo $os[0];?> &nbsp;Версия ядра:<?php if('/'==DIRECTORY_SEPARATOR){echo $os[2];}else{echo $os[1];} ?></td>
          <td>Версия веб-сервера</td>
          <td><?php echo $_SERVER['SERVER_SOFTWARE'] ; ?></td>
          <td>IP сервера</td>
          <td><?php echo $_SERVER['SERVER_ADDR'].":".$_SERVER['SERVER_PORT']; ?></td>
        </tr>
        <tr>
          <td>Общее пространство сервера</td>
          <td><?php
            $dt = round(@disk_total_space(".")/(1024*1024*1024),3);
            echo $dt?$dt:'∞'
           ?>G</td>
          <td>Свободное место на сервере</td>
          <td><?php
            $df = round(@disk_free_space(".")/(1024*1024*1024),3);
            echo $df?$df:'∞'
           ?>G</td>
          <td>Время сервера</td>
          <td><?php echo date("Y-m-d H:i:s"); ?></td>
        </tr>
        <tr>
          <td>Версия PHP</td>
          <td><?php echo PHP_VERSION ; ?></td>
          <td>Версия MySQL</td>
          <td><?php echo iDB::version() ; ?></td>
          <td>Режим работы PHP</td>
          <td><?php echo strtoupper(php_sapi_name());?></td>
        </tr>
        <tr>
          <td>Скрипт занимает максимум памяти</td>
          <td><?php echo $this->show("memory_limit"); ?></td>
          <td>Ограничение размера файла загрузки скрипта</td>
          <td><?php echo $this->show("upload_max_filesize");?></td>
          <td>PHP max_execution_time</td>
          <td><?php echo $this->show("max_execution_time"); ?>(сек)</td>
        </tr>
        <tr>
          <td>Поддержка MySQL</td>
          <td><?php echo version_compare(PHP_VERSION,'5.5','>=')?'mysqli':'mysql';?></td>
          <td>CURL поддержка:</td>
          <td><?php echo $this->isfun("curl_init");?></td>
          <td>Поддержка mb_string:</td>
          <td><?php echo $this->isfun("mb_convert_encoding");?></td>
        </tr>
        <tr>
          <td>Поддержка библиотеки GD</td>
          <td><?php
            if(function_exists('gd_info')) {
                $gd_info = @gd_info();
              echo $gd_info["GD Version"];
          }else{
            echo iUI::check(0);
          }
          ?></td>
          <td>Поддержка FTP:</td>
          <td><?php echo $this->isfun("ftp_login");?></td>
          <td>Поддержка сессий</td>
          <td><?php echo $this->isfun("session_start") ; ?></td>
        </tr>
        <tr>
          <td>Размер файла POST</td>
          <td><?php echo $this->show("post_max_size"); ?></td>
          <td>Заблокированные функции</td>
          <td><?php echo get_cfg_var("disable_functions")?'<a class="tip" href="javascript:;" title="'.get_cfg_var("disable_functions").'"> Просмотр </a>':"Отсутствуют" ; ?></td>
          <td>Безопасный режим</td>
          <td><?php echo iUI::check(ini_get('safe_mode')); ?></td>
        </tr>
      </table>
    </div>
  </div>
</div>
<div class="iCMS-container">
  <div class="row-fluid">
    <div class="span5">
      <div class="widget-box">
        <div class="widget-title"> <span class="icon"> <i class="fa fa-info-circle"></i> </span>
          <h5>Информация о разработчиках iCMS</h5>
        </div>
        <div class="widget-content nopadding">
          <table class="table table-bordered">
            <tr>
              <td>Плагины и шаблоны</td>
              <td>
                <a class="btn btn-small" href="https://www.icmsdev.com" target="_blank">iCMS</a>
                <a class="btn btn-small" href="https://www.icmsdev.com/template/" target="_blank"> Шаблоны </a>
                <a class="btn btn-small" href="https://www.icmsdev.com/docs/" target="_blank">Документация</a>
                
              </td>
            </tr>
          </table>
        </div>
      </div>
    </div>
    
    <div class="span4">
      <div class="widget-box">
        <div class="widget-title"> <span class="icon"> <i class="fa fa-bug"></i> </span>
          <h5>Сообщение об ошибке</h5>
        </div>
        <div class="widget-content nopadding">
          <form action="https://www.icmsdev.com/cms/bugs.php" method="post" class="form-inline" id="iCMS-feedback" target="iPHP_FRAME">
            <textarea id="bug_content" name="content" class="tip" title="为了保证效率,请务必描述清楚你的问题,例如包含 iCMS 版本号, 服务器操作系统, WEB服务器版本, 浏览器版本等必要信息,不合格问题将可能会被无视掉" style="width:95%; height: 160px; margin:4px 0px 4px 10px;padding: 4px;">
  Проблемный URL:
  Описание проблемы:
  -----------------------------------------------------------
  Номер версии:iCMS <?php echo iCMS_VERSION ; ?>[<?php echo iCMS_RELEASE ; ?>]
  Информация о версии GIT:<?php echo GIT_COMMIT ; ?> [<?php echo GIT_TIME ; ?>]
  Серверная операционная система:<?php echo PHP_OS ; ?>;
  Версия WEB сервера:<?php echo $_SERVER['SERVER_SOFTWARE'] ; ?>;
  Версия MYSQL:<?php echo iDB::version() ; ?>;
  Версия браузера:<?php echo $_SERVER['HTTP_USER_AGENT'] ; ?>;
  </textarea>
            <div class="clearfix mt10"></div>
            <button id="bug_button" class="btn btn-primary fr mr20" type="submit"><i class="fa fa-check"></i>Отправить</button>
            <input id="bug_email" name="email" type="text" class="ml10" placeholder="Ваш Email">
            <div class="clearfix mt10"></div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
window.setTimeout(function(){
  $.getJSON('<?php echo iPHP_SELF; ?>?do=count',
    function(array){
      $("#counts").hide();
      $.each(array, function(index, val) {
          $("#counts_"+index).text(val)
      });
    }
  );
  $.getJSON('<?php echo iPHP_SELF; ?>?do=count&a=article',
    function(array){
      $.each(array, function(index, val) {
          $("#counts_"+index).text(val)
      });
    }
  );
},1000);
</script>
<?php iPHP::callback(array('apps_storeAdmincp','check_update'));?>
<?php iPHP::callback(array('patchAdmincp','check_version'));?>
<?php iPHP::callback(array('patchAdmincp','check_update'));?>
<?php iPHP::callback(array('patchAdmincp','check_upgrade'));?>
<?php iPHP::callback(array('cacheAdmincp','clean_cache'));?>
<?php admincp::foot();?>
