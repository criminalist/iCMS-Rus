<?php

defined('iPHP') OR exit('Oops, something went wrong');
admincp::head();
?>

<div class="iCMS-container">
  <div class="widget-box">
    <div class="widget-title"> <span class="icon"> <i class="fa fa-file"></i> </span>
      <h5 class="brs">Генерация статических HTML файлов</h5>
      <ul class="nav nav-tabs" id="html-tab">
        <li class="active"><a href="javascript:;"><i class="fa fa-floppy-o"></i> <b> Главная </b></a></li>
        <li><a href="<?php echo APP_URI; ?>&do=category"><i class="fa fa-floppy-o"></i> <b>Категории</b></a></li>
        <li><a href="<?php echo APP_URI; ?>&do=article"><i class="fa fa-floppy-o"></i> <b>Документы</b></a></li>
      </ul>
    </div>
    <div class="widget-content nopadding">
      <form action="<?php echo APP_FURI; ?>&do=createIndex" method="post" class="form-inline" id="iCMS-html" target="iPHP_FRAME">
        <div id="html-add" class="tab-content">
          <div class="input-prepend input-append"> <span class="add-on">Шаблон главной страницы</span>
            <input type="text" name="indexTPL" class="span3" id="indexTPL" value="<?php echo iCMS::$config['template']['index']['tpl'] ; ?>"/>
            <?php echo filesAdmincp::modal_btn('Шаблон','indexTPL');?>
          </div>
          <div class="clearfloat mb10"></div>
          <div class="input-prepend"> <span class="add-on">Имя файла шаблона</span>
            <input type="text" name="indexName" class="span3" id="indexName" value="<?php echo iCMS::$config['template']['index']['name'] ; ?>"/>
          </div>
          <span class="help-inline"><?php echo iCMS::$config['router']['ext'] ; ?> Имя файла главной индексной страницы, как правило,<span class="label label-important">index</span> (префикс .html или любой другой указывать не требуется)</span> </div>
        <div class="form-actions">
          <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i> Сгенерировать</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php admincp::foot();?>
