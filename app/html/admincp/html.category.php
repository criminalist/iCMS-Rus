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
        <li class="active"><a href="javascript:;"><i class="fa fa-floppy-o"></i> <b>Категории</b></a></li>
        <li><a href="<?php echo APP_URI; ?>&do=article"><i class="fa fa-floppy-o"></i> <b>Документы</b></a></li>
      </ul>
    </div>
    <div class="widget-content nopadding">
      <form action="<?php echo APP_FURI; ?>&do=createCategory" method="post" class="form-inline" id="iCMS-html" target="iPHP_FRAME">
        <div id="html-add" class="tab-content">
          <div class="input-prepend input-append"> <span class="add-on">Выберите категорию</span>
            <select name="cid[]" multiple="multiple" class="span3" size="15">
              <option value="all">Все категории</option>
              <optgroup label="======================================"></optgroup>
              <?php echo category::priv('cs')->select(0,0,1,false,array("mode"=>'1'));?>
            </select>
          </div>
          <div class="clearfloat mb10"></div>
          <?php /*
        <div class="input-prepend"> <span class="add-on">生成页数</span>
          <input type="text" name="cpn" class="span3" id="cpn" value="10000"/>
        </div>
    	<span class="help-inline"></span>
        <div class="clearfloat mb10"></div>
        <div class="input-prepend input-append"> <span class="add-on">间隔时间</span>
          <input type="text" name="time" class="span3" id="time" value="1"/><span class="add-on">сек.</span>
        </div>
    	<span class="help-inline"></span> */ ?>
        </div>
        <div class="form-actions">
          <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i>Запустить</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php admincp::foot();?>
