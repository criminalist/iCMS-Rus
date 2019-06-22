<?php

defined('iPHP') OR exit('Oops, something went wrong');
configAdmincp::head("Конфигурация облачного хранилища","cloud_config");
$cloud_config_file = filesAdmincp::cloud_config_file();
?>
<?php if($cloud_config_file){ ?>
<div class="input-prepend"> <span class="add-on">Облачное хранилище</span>
  <div class="switch">
    <input type="checkbox" data-type="switch" name="config[enable]" id="cloud_enable" <?php echo $config['enable']?'checked':''; ?>/>
  </div>
</div>
<span class="help-inline"></span>
<div class="clearfloat mb10"></div>
<div class="input-prepend"> <span class="add-on">Не сохранять локально</span>
  <div class="switch">
    <input type="checkbox" data-type="switch" name="config[local]" id="cloud_local" <?php echo $config['local']?'checked':''; ?>/>
  </div>
</div>
<span class="help-inline">Сохраняйте локальные ресурсы по умолчанию при резервном копировании</span>
<div class="clearfloat mb10"></div>
<div class="alert" style="width:360px;">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  Настройка облачного хранилища повлияет на эффективность загрузки файлов
</div>
<hr />
<?php //include admincp::view("remote.config","files"); ?>
<?php
  foreach ($cloud_config_file as $name =>$path) {
    include admincp::view("cloud_".$name,"files");
    echo '<hr />';
  }
?>
<?php }?>
<?php configAdmincp::foot();?>
