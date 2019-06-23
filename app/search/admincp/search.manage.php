<?php

defined('iPHP') OR exit('Oops, something went wrong');
admincp::head();
?>
<script type="text/javascript">
$(function(){
 $("#<?php echo APP_FORMID;?>").batch();
  $("#setting").click(function(event) {
      var setting_wrap = document.getElementById("setting_wrap");
      iCMS.dialog({
        title: 'iCMS - Поиск для отключенных слов',
        content:setting_wrap
      });
  });
});
</script>
<div class="iCMS-container">
  <div class="widget-box">
    <div class="widget-title"> <span class="icon"> <i class="fa fa-search"></i> </span>
      <h5SEO Заголовок/h5>
      <div class="pull-right">
        <button class="btn btn-success" type="button" id="setting"><i class="fa fa-send"></i> Установить поиск для отключенных слов</button>
      </div>
    </div>
    <div class="widget-content">
      <form action="<?php echo iPHP_SELF ; ?>" method="get" class="form-inline">
        <input type="hidden" name="app" value="<?php echo admincp::$APP_NAME;?>" />
        <div class="input-prepend input-append">
          <span class="add-on">На страницу</span>
          <input type="text" name="perpage" id="perpage" value="<?php echo $maxperpage ; ?>" style="width:36px;"/>
          <span class="add-on">записей</span>
        </div>
        <div class="input-prepend">
          <span class="add-on">Сортировка</span>
          <select name="orderby" id="orderby" class="span2 chosen-select">
            <option value=""></option>
            <optgroup label="По убыванию"><?php echo $orderby_option['DESC'];?></optgroup>
            <optgroup label="По возрастанию"><?php echo $orderby_option['ASC'];?></optgroup>
          </select>
        </div>
        <div class="input-prepend input-append">
          <span class="add-on">Ключевое слово</span>
          <input type="text" name="keywords" class="span2" id="keywords" value="<?php echo $_GET['keywords'] ; ?>" />
          <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i>поиск</button>
        </div>
      </form>
    </div>
  </div>
  <div class="widget-box" id="<?php echo APP_BOXID;?>">
    <div class="widget-title"> <span class="icon">
      <input type="checkbox" class="checkAll" data-target="#<?php echo APP_BOXID;?>" />
      </span>
      <h5>Список поисковых запросов</h5>
    </div>
    <div class="widget-content nopadding">
      <form action="<?php echo APP_FURI; ?>&do=batch" method="post" class="form-inline" id="<?php echo APP_FORMID;?>" target="iPHP_FRAME">
        <table class="table table-bordered table-condensed table-hover">
          <thead>
            <tr>
              <th><i class="fa fa-arrows-v"></i></th>
              <th class="span3">Ключевое слово</th>
              <th class="span3">Искали</th>
              <th class="span3">cоздан</th>
              <th>Операции</th>
            </tr>
          </thead>
          <tbody>
            <?php for($i=0;$i<$_count;$i++){?>
            <tr id="id<?php echo $rs[$i]['id'] ; ?>">
              <td><input type="checkbox" name="id[]" value="<?php echo $rs[$i]['id'] ; ?>" /></td>
              <td><?php echo $rs[$i]['search'] ; ?></td>
              <td><?php echo $rs[$i]['times'] ; ?></td>
              <td><?php echo get_date($rs[$i]['addtime'],'Y-m-d H:i:s'); ?></td>
              <td><a href="<?php echo APP_FURI; ?>&do=del&id=<?php echo $rs[$i]['id'] ; ?>" target="iPHP_FRAME" class="del btn btn-small" title='Удалить навсегда'  onclick="return confirm('Вы уверены, что хотите удалить?');"/><i class="fa fa-trash-o"></i> Удалить</a></td>
            </tr>
            <?php }  ?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="6"><div class="pagination pagination-right" style="float:right;"><?php echo iPagination::$pagenav ; ?></div>
                <div class="input-prepend input-append mt20"> <span class="add-on"> Выбрать все
                  <input type="checkbox" class="checkAll checkbox" data-target="#<?php echo APP_BOXID;?>" />
                  </span>
                  <div class="btn-group dropup" id="iCMS-batch"> <a class="btn dropdown-toggle" data-toggle="dropdown" tabindex="-1"><i class="fa fa-wrench"></i> Пакетная операция</a><a class="btn dropdown-toggle" data-toggle="dropdown" tabindex="-1"> <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                      <li><a data-toggle="batch" data-action="dels"><i class="fa fa-trash-o"></i> Удалить</a></li>
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
<div id="setting_wrap" style="display:none;">
  <form action="<?php echo APP_FURI; ?>&do=save_config" method="post" target="iPHP_FRAME">
        <div>
        <textarea name="config[disable]" class="span6" style="height: 300px;"><?php echo implode("\n",(array)$config['disable']) ; ?></textarea>
        </div>
        <span class="help-inline">По одному в строке</span>
        <div class="form-actions">
          <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i> Сохранить</button>
        </div>
  </form>
</div>
<?php admincp::foot();?>
