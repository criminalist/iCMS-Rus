<?php

defined('iPHP') OR exit('Oops, something went wrong');
admincp::head();
?>

<div class="iCMS-container">
  <div class="widget-box">
    <div class="widget-title"> <span class="icon"> <i class="fa fa-file"></i> </span>
      <h5 class="brs">Генерация статических HTML файлов</h5>
      <ul class="nav nav-tabs" id="html-tab">
        <li><a href="<?php echo APP_URI; ?>&do=index"><i class="fa fa-floppy-o"></i> <b>Главная</b></a></li>
        <li><a href="<?php echo APP_URI; ?>&do=category"><i class="fa fa-floppy-o"></i> <b>Категории</b></a></li>
        <li class="active"><a href="javascript:;"><i class="fa fa-floppy-o"></i> <b>Документы</b></a></li>
      </ul>
    </div>
    <div class="widget-content nopadding">
      <form action="<?php echo iPHP_SELF ; ?>" method="get" class="form-inline" id="iCMS-html" target="iPHP_FRAME">
        <input type="hidden" name="app" value="<?php echo admincp::$APP_NAME;?>" />
        <input type="hidden" name="do" value="createArticle" />
        <input type="hidden" name="frame" value="iPHP" />
        <div id="html-add" class="tab-content">
          <div class="input-prepend input-append"> <span class="add-on">Категории</span>
            <select name="cid[]" multiple="multiple" class="span3" size="15">
              <option value="all">Все категории</option>
              <optgroup label="======================================"></optgroup>
              <?php echo category::appid(iCMS_APP_ARTICLE,'cs')->select();?>
            </select>
          </div>
          <hr>
          <div class="input-prepend input-append"><span class="add-on">По времени</span> <span class="add-on"><i class="fa fa-calendar"></i></span>
            <input type="text" class="ui-datepicker" name="startime" value="<?php echo $_GET['startime'] ; ?>" placeholder="Время начала" />
            <span class="add-on"><i class="fa fa-minus"></i></span>
            <input type="text" class="ui-datepicker" name="endtime" value="<?php echo $_GET['endtime'] ; ?>" placeholder="Время окончания" />
            <span class="add-on"><i class="fa fa-calendar"></i></span> </div>
          <hr>
          <div class="input-prepend input-append"><span class="add-on">По ID</span> <span class="add-on">Начальный ID</span>
            <input type="text" name="startid" class="span1" id="startId"/>
            <span class="add-on"><i class="fa fa-arrows-h"></i></span> <span class="add-on">Последний ID</span>
            <input type="text" name="endid" class="span1" id="endid"/>
            <span class="add-on"><i class="fa fa-filter"></i></span> </div>
          <hr>
          <div class="input-prepend"> <span class="add-on">Порядок</span>
            <select name="orderby" id="orderby" class="span4 chosen-select">
              <option value=""></option>
              <optgroup label="По убыванию"><?php echo $orderby_option['DESC'];?></optgroup>
              <optgroup label="По возрастанию"><?php echo $orderby_option['ASC'];?></optgroup>
            </select>
          </div>
        </div>
        <div class="form-actions">
          <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i>Запустить</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php admincp::foot();?>
