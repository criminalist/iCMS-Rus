<?php

defined('iPHP') OR exit('Oops, something went wrong');
admincp::head();
?>
<script type="text/javascript">
$(function(){
	$("#<?php echo APP_FORMID;?>").batch();
  $("#import_rule").click(function(event) {
      var import_rule_wrap = document.getElementById("import_rule_wrap");
      iCMS.dialog({
        title: 'iCMS - 导入规则',
        content:import_rule_wrap
      });
  });
  $("#local").click(function() {
      $("#localfile").click();
  });
  $("#localfile").change(function() {
      $("#import_rule_wrap form").submit();
      $(this).val('');
  });
});
</script>
<div class="iCMS-container">
  <div class="widget-box">
    <div class="widget-title"> <span class="icon"> <i class="fa fa-search"></i> </span>
      <h5SEO Заголовок/h5>
    </div>
    <div class="widget-content">
      <form action="<?php echo iPHP_SELF ; ?>" method="get" class="form-inline">
        <input type="hidden" name="app" value="<?php echo admincp::$APP_NAME;?>" />
        <input type="hidden" name="do" value="<?php echo admincp::$APP_DO;?>" />
        <div class="input-prepend input-append"> <span class="add-on">На страницу</span>
          <input type="text" name="perpage" id="perpage" value="<?php echo $maxperpage ; ?>" style="width:36px;"/>
          <span class="add-on">записей</span> </div>
        <div class="input-prepend input-append"> <span class="add-on">Ключевое слово</span>
          <input type="text" name="keywords" class="span2" id="keywords" value="<?php echo $_GET['keywords'] ; ?>" />
          <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i>поиск</button>
        </div>
        <div class="pull-right">
          <button class="btn btn-success" type="button" id="import_rule"><i class="fa fa-send"></i> Импорт правил</button>
        </div>
      </form>
    </div>
  </div>
  <div class="widget-box" id="<?php echo APP_BOXID;?>">
    <div class="widget-title"> <span class="icon">
      <input type="checkbox" class="checkAll" data-target="#<?php echo APP_BOXID;?>" />
      </span>
      <h5>规则列表</h5>
    </div>
    <div class="widget-content nopadding">
      <form action="<?php echo APP_FURI; ?>&do=batch" method="post" class="form-inline" id="<?php echo APP_FORMID;?>" target="iPHP_FRAME">
        <table class="table table-bordered table-condensed table-hover">
          <thead>
            <tr>
              <th><i class="fa fa-arrows-v"></i></th>
              <th>ID</th>
              <th class="span6"> Имя </th>
              <th>Операции</th>
            </tr>
          </thead>
          <tbody>
            <?php for($i=0;$i<$_count;$i++){?>
            <tr id="id<?php echo $rs[$i]['id'] ; ?>">
              <td><input type="checkbox" name="id[]" value="<?php echo $rs[$i]['id'] ; ?>" /></td>
              <td><?php echo $rs[$i]['id'] ; ?></td>
              <td><?php echo $rs[$i]['name'] ; ?></td>
              <td>
                <a href="<?php echo __ADMINCP__; ?>=spider&do=manage&rid=<?php echo $rs[$i]['id'] ; ?>" class="btn btn-small btn-success" target="_blank"><i class="fa fa-list-alt"></i>Правила парсера</a>
                <a href="<?php echo __ADMINCP__; ?>=spider_project&do=manage&rid=<?php echo $rs[$i]['id'] ; ?>" class="btn btn-small btn-info" target="_blank"><i class="fa fa-magnet"></i> Схема </a>
                <a href="<?php echo APP_URI; ?>&do=error&rid=<?php echo $rs[$i]['id'] ; ?>" class="btn btn-small btn-danger" target="_blank"><i class="fa fa-info-circle"></i>Сообщение об ошибке</a>
                <a href="<?php echo APP_FURI; ?>&do=export&rid=<?php echo $rs[$i]['id'] ; ?>" class="btn btn-small" target="iPHP_FRAME"><i class="fa fa-download"></i> Экспорт </a>
                <a href="<?php echo __ADMINCP__; ?>=spider_project&do=export&rid=<?php echo $rs[$i]['id'] ; ?>" class="btn btn-small" target="iPHP_FRAME"><i class="fa fa-download"></i> Экспорт схемы</a>
                <a href="<?php echo APP_FURI; ?>&do=copy&rid=<?php echo $rs[$i]['id'] ; ?>" class="btn btn-small" target="iPHP_FRAME"><i class="fa fa-clipboard"></i> Копировать</a>
                <a href="<?php echo APP_URI; ?>&do=test&rid=<?php echo $rs[$i]['id'] ; ?>" class="btn btn-small btn-inverse" data-toggle="modal" title="Тестировать правило"><i class="fa fa-keyboard-o"></i> Тестировать</a>
                <a href="<?php echo APP_URI; ?>&do=add&rid=<?php echo $rs[$i]['id'] ; ?>" class="btn btn-small" target="_blank"><i class="fa fa-edit"></i> Редактировать </a>
                <a href="<?php echo APP_FURI; ?>&do=del&rid=<?php echo $rs[$i]['id'] ; ?>" target="iPHP_FRAME" class="del btn btn-small" title='Удалить навсегда'  onclick="return confirm('Вы уверены, что хотите удалить?');"/><i class="fa fa-trash-o"></i> Удалить</a></td>
            </tr>
            <?php }  ?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="4"><div class="pagination pagination-right" style="float:right;"><?php echo iPagination::$pagenav ; ?></div>
                <div class="input-prepend input-append mt20"> <span class="add-on"> Выбрать все
                  <input type="checkbox" class="checkAll checkbox" data-target="#<?php echo APP_BOXID;?>" />
                  </span>
                  <div class="btn-group dropup" id="iCMS-batch"> <a class="btn dropdown-toggle" data-toggle="dropdown" tabindex="-1"><i class="fa fa-wrench"></i> Пакетные операции</a><a class="btn dropdown-toggle" data-toggle="dropdown" tabindex="-1"> <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                      <li><a data-toggle="batch" data-action="del"><i class="fa fa-trash-o"></i> Удалить</a></li>
                    </ul>
                  </div>
                </div></td>
            </tr>
          </tfoot>
        </table>
      </form>
    </div>
  </div>
</div>
<div id="import_rule_wrap" style="display:none;">
  <form action="<?php echo APP_FURI; ?>&do=import" method="post" enctype="multipart/form-data" target="iPHP_FRAME">
    <div class="alert alert-info">
      Импорт (поддерживаются только TXT)
    </div>
    <div class="clearfloat mb10"></div>
    <a id="local" class="btn btn-primary btn-large btn-block"><i class="fa fa-upload"></i> Выберите правила для импорта</a>
    <input id="localfile" name="upfile" type="file" class="hide"/>
    <div class="clearfloat mb10"></div>
  </form>
</div>
<?php admincp::foot();?>
