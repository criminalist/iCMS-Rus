<?php

defined('iPHP') OR exit('Oops, something went wrong');
configAdmincp::head("Настройка тегов");
?>
<!-- <div class="input-prepend">
  <span class="add-on">标签URL</span>
  <input type="text" name="config[url]" class="span4" id="url" value="<?php echo $config['url'] ; ?>"/>
</div>
<span class="help-inline">标签目录访问URL 可绑定域名</span>
<div class="clearfloat mb10"></div> -->
<div class="input-prepend input-append">
  <span class="add-on">URL правила</span>
  <input type="text" name="config[rule]" class="span4" id="rule" value="<?php echo $config['rule'] ; ?>"/>
  <div class="btn-group">
    <a class="btn dropdown-toggle" data-toggle="dropdown" tabindex="-1"><i class="fa fa-question-circle"></i> Помощь </a>
    <ul class="dropdown-menu">
      <li><a href="{ID}" data-toggle="insertContent" data-target="#rule"><span class="label label-important">{ID}</span> ID Тега</a></li>
      <li><a href="{TKEY}" data-toggle="insertContent" data-target="#rule"><span class="label label-important">{TKEY}</span> Идентификация тега</a></li>
      <li><a href="{EN_EN}" data-toggle="insertContent" data-target="#rule"><span class="label label-important">{EN_EN}</span> Имя тега (китайский)</a></li>
      <li><a href="{NAME}" data-toggle="insertContent" data-target="#rule"><span class="label label-important">{NAME}</span>Имя тега</a></li>
      <li class="divider"></li>
      <li><a href="{TCID}" data-toggle="insertContent" data-target="#rule"><span class="label label-inverse">{TCID}</span> ID категории</a></li>
      <li><a href="{TCDIR}" data-toggle="insertContent" data-target="#rule"><span class="label label-inverse">{TCDIR}</span> Категория</a></li>
      <li><a href="{CDIR}" data-toggle="insertContent" data-target="#rule"><span class="label label-inverse">{CDIR}</span> Каталог</a></li>
      <li class="divider"></li>
      <li><a href="{P}" data-toggle="insertContent" data-target="#rule"><span class="label label-inverse">{P}</span>Номер страницы</a></li>
      <li><a href="{EXT}" data-toggle="insertContent" data-target="#rule"><span class="label label-inverse">{EXT}</span>Суффикс</a></li>
      <li class="divider"></li>
      <li><a href="{PHP}" data-toggle="insertContent" data-target="#rule"><span class="label label-inverse">{PHP}</span>Динамический URL</a></li>
    </ul>
  </div>
</div>
<div class="help-inline">Правило обязательно должно включать в себя одну из переменных <span class="label label-important">{ID}</span> , <span class="label label-important">{NAME}</span> , <span class="label label-important">{EN_EN}</span> , <span class="label label-important">{TKEY}</span></div>
<!-- <div class="clearfloat mb10"></div>
<div class="input-prepend">
  <span class="add-on">Каталог</span>
  <input type="text" name="config[dir]" class="span4" id="dir" value="<?php echo $config['dir'] ; ?>"/>
</div>
<span class="help-inline">存放标签静态页面目录,相对于app目录.可用../表示上级目录</span>
 --><div class="clearfloat mb10"></div>
<div class="input-prepend input-append">
  <span class="add-on">Шаблон</span>
  <input type="text" name="config[tpl]" class="span4" id="tpl" value="<?php echo $config['tpl'] ; ?>"/>
  <?php echo filesAdmincp::modal_btn('Шаблон','tpl');?>
</div>
<div class="clearfloat mb10"></div>
<div class="input-prepend"> <span class="add-on">Разделитель TKEY</span>
  <input type="text" name="config[tkey]" class="span4" id="tkey" value="<?php echo $config['tkey'] ; ?>"/>
</div>
<span class="help-inline">留空,按紧凑型生成(Транслит)</span>
<div class="mt20"></div>
<div class="alert alert-block">
  此配置为标签的URL默认配置<br />
  标签规则优先级
  标签自定义链接 > 标签分类 > 标签所属栏目 > Настройка тегов
</div>
<?php configAdmincp::foot();?>
