<?php

defined('iPHP') OR exit('Oops, something went wrong');
?>
<style>
.btn-group.open .category_rule .dropdown-toggle{-webkit-box-shadow:none;box-shadow:none;}
/*.dropdown-submenu .dropdown-menu{min-width: 240px;}*/
.dropdown-submenu .dropdown-menu>li>a{width: 240px;}
</style>
<?php if($this->category_rule)foreach ($this->category_rule as $key => $value) {
    $rule_id = 'rule_'.$key;
    $cname = $this->category_name;
?>
<div class="input-prepend input-append">
  <span class="add-on"><?php echo $value[0];?></span>
  <input type="text" name="rule[<?php echo $key;?>]" class="span5" id="<?php echo $rule_id;?>" value="<?php echo isset($rs['rule'][$key])?$rs['rule'][$key]:$value[1]; ?>"/>
  <div class="btn-group"> <a class="btn dropdown-toggle" data-toggle="dropdown" tabindex="-1"><i class="fa fa-question-circle"></i> Помощь </a>
    <ul class="dropdown-menu category_rule">
      <li class="dropdown-submenu">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $cname;?></a>
        <ul class="dropdown-menu">
        <li><a href="{CDIR}" data-toggle="insertContent" data-target="#<?php echo $rule_id;?>"><span class="label label-important">{CDIR}</span> <?php echo $cname;?> Каталог </a></li>
        <li><a href="{CID}" data-toggle="insertContent" data-target="#<?php echo $rule_id;?>"><span class="label label-important">{CID}</span> <?php echo $cname;?>CID</a></li>
        <li><a href="{0xCID}" data-toggle="insertContent" data-target="#<?php echo $rule_id;?>"><span class="label label-important">{0xCID}</span> <?php echo $cname;?>CID补零(8位)</a></li>
        <li class="divider"></li>
        <li><a href="{Hash@CID}" data-toggle="insertContent" data-target="#<?php echo $rule_id;?>"><span class="label label-important">{Hash@CID}</span>  <?php echo $cname;?>CID Hash</a></li>
        <li><a href="{Hash@0xCID}" data-toggle="insertContent" data-target="#<?php echo $rule_id;?>"><span class="label label-important">{Hash@0xCID}</span> <?php echo $cname;?>CID Hash</a></li>
        </ul>
      </li>
      <?php if($key!='index' && $key!='list'){?>
      <li class="divider"></li>
      <li class="dropdown-submenu">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $value[0];?></a>
        <ul class="dropdown-menu">
          <li><a href="{ID}" data-toggle="insertContent" data-target="#<?php echo $rule_id;?>"><span class="label label-important">{ID}</span>ID документа</a></li>
          <li><a href="{0xID}" data-toggle="insertContent" data-target="#<?php echo $rule_id;?>"><span class="label label-important">{0xID}</span> <?php echo $value[0];?>ID补零(8位)</a></li>
          <li><a href="{0xID,0,3}" data-toggle="insertContent" data-target="#<?php echo $rule_id;?>"><span class="label label-inverse">{0xID,0,3}</span> <?php echo $value[0];?>ID补零(前3位)</a></li>
          <li><a href="{0xID,3,2}" data-toggle="insertContent" data-target="#<?php echo $rule_id;?>"><span class="label label-inverse">{0xID,3,2}</span> <?php echo $value[0];?>ID补零(中间2位)</a></li>
          <li><a href="{LINK}" data-toggle="insertContent" data-target="#<?php echo $rule_id;?>"><span class="label label-important">{LINK}</span> <?php echo $value[0];?>Пользовательская ссылка</a></li>
          <li><a href="{TITLE}" data-toggle="insertContent" data-target="#<?php echo $rule_id;?>"><span class="label label-inverse">{TITLE}</span> <?php echo $value[0];?>Название</a></li>
          <li class="divider"></li>
          <li><a href="{Hash@ID}" data-toggle="insertContent" data-target="#<?php echo $rule_id;?>"><span class="label label-important">{Hash@ID}</span>  <?php echo $value[0];?>ID Hash</a></li>
          <li><a href="{Hash@0xID}" data-toggle="insertContent" data-target="#<?php echo $rule_id;?>"><span class="label label-important">{Hash@0xID}</span> <?php echo $value[0];?>0xID Hash</a></li>
          <?php if($this->category_rule_list[$key])foreach ($this->category_rule_list[$key] as $lk => $lv) {?>
            <?php if($lv[0]=='----'){?>
            <li class="divider"></li>
            <?php }else{?>
            <li><a href="<?php echo $lv[0];?>" data-toggle="insertContent" data-target="#<?php echo $rule_id;?>"><span class="label label-<?php echo ($lv[2]===false?'inverse':'important');?>"><?php echo $lv[0];?></span> <?php echo $lv[1];?></a></li>
            <?php }?>
          <?php }?>
        </ul>
      </li>
      <?php }?>
      <li class="divider"></li>
      <li class="dropdown-submenu">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Дата и время</a>
        <ul class="dropdown-menu">
          <li><a href="{YYYY}" data-toggle="insertContent" data-target="#<?php echo $rule_id;?>"><span class="label label-inverse">{YYYY}</span> Год (например /res/2019/)</a></li>
          <li><a href="{YY}" data-toggle="insertContent" data-target="#<?php echo $rule_id;?>"><span class="label label-inverse">{YY}</span> 2位数年份12</a></li>
          <li><a href="{MM}" data-toggle="insertContent" data-target="#<?php echo $rule_id;?>"><span class="label label-inverse">{MM}</span> 月份 01-12月份</a></li>
          <li><a href="{M}" data-toggle="insertContent" data-target="#<?php echo $rule_id;?>"><span class="label label-inverse">{M}</span> 月份 1-12 月份</a></li>
          <li><a href="{DD}" data-toggle="insertContent" data-target="#<?php echo $rule_id;?>"><span class="label label-inverse">{DD}</span> 日期 01-31</a></li>
          <li><a href="{D}" data-toggle="insertContent" data-target="#<?php echo $rule_id;?>"><span class="label label-inverse">{D}</span> 日期1-31</a></li>
          <li><a href="{TIME}" data-toggle="insertContent" data-target="#<?php echo $rule_id;?>"><span class="label label-inverse">{TIME}</span> 文章发布时间戳</a></li>
        </ul>
      </li>
      <li class="divider"></li>
      <li><a href="{MD5}" data-toggle="insertContent" data-target="#<?php echo $rule_id;?>"><span class="label label-inverse">{MD5}</span> ID MD5(16位)</a></li>
      <li><a href="{P}" data-toggle="insertContent" data-target="#<?php echo $rule_id;?>"><span class="label label-inverse">{P}</span> Номер страницы </a></li>
      <li><a href="{EXT}" data-toggle="insertContent" data-target="#<?php echo $rule_id;?>"><span class="label label-inverse">{EXT}</span> Суффикс</a></li>
      <li class="divider"></li>
      <li><a href="{PHP}" data-toggle="insertContent" data-target="#<?php echo $rule_id;?>"><span class="label label-inverse">{PHP}</span> Динамический URL</a></li>
    </ul>
  </div>
</div>
<span class="help-inline">Правило обязательно должно включать в себя одну из переменных <?php echo str_replace(',', ', ', $value[2]);?> в противном случае оно не будет разрешено.</span>
<div class="clearfloat mb10"></div>
<?php }?>
<div class="alert">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <strong>Внимание!</strong> Правила URL, пожалуйста, всесторонне оцените, будет ли окончательный URL конфликтовать и вызывать нерешенные
</div>
