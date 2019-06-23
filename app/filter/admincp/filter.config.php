<?php

defined('iPHP') OR exit('Oops, something went wrong');
admincp::head();
?>
<div class="iCMS-container">
  <div class="widget-box" id="<?php echo APP_BOXID;?>">
    <div class="widget-title"> <span class="icon"> <i class="fa fa-filter"></i> </span>
    <ul class="nav nav-tabs" id="filter-tab">
      <li class="active"><a href="#tab-disable" data-toggle="tab"><i class="fa fa-strikethrough"></i> Запрещенные слова</a></li>
      <li><a href="#tab-filter" data-toggle="tab"><i class="fa fa-umbrella"></i>Фильтр слов</a></li>
    </ul>
  </div>
  <div class="widget-content nopadding">
    <form action="<?php echo APP_FURI; ?>&do=save_config" method="post" class="form-inline" id="iCMS-filter" target="iPHP_FRAME">
      <div id="filter" class="tab-content">
        <div id="tab-disable" class="tab-pane active">
          <textarea name="config[disable]" class="span6" style="height: 300px;"><?php echo implode("\n",(array)$config['disable']) ; ?></textarea>
        </div>
        <div id="tab-filter" class="tab-pane hide">
          <textarea name="config[filter]" class="span6" style="height: 300px;"><?php echo implode("\n",(array)$config['filter']) ; ?></textarea>
        </div>
        <span class="help-inline">По одному на строку<br />
        过滤词格式:过滤词=***</span> </div>
        <div class="form-actions">
          <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i> Сохранить</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php admincp::foot();?>
