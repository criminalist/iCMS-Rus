<?php

defined('iPHP') OR exit('Oops, something went wrong');
admincp::head();
?>
<script type="text/javascript">
$(function(){
  <?php if($_GET['cid']){  ?>
  iCMS.select('cid',"<?php echo $_GET['cid'] ; ?>");
  <?php } ?>
  <?php if($_GET['rid']){  ?>
  iCMS.select('rid',"<?php echo $_GET['rid'] ; ?>");
  <?php } ?>
	<?php if($_GET['sub']=="on"){ ?>
	iCMS.checked('#sub');
	<?php } ?>
  <?php if (isset($_GET['auto'])) { ?>
  iCMS.checked('[name="auto"][value="<?php echo $_GET['auto'];?>"]');
  <?php } ?>
  $("#<?php echo APP_FORMID;?>").batch({
    poid:function(){
      return $("#poidBatch").clone(true);
    }
  });
  $("#import_project").click(function(event) {
      var import_project_wrap = document.getElementById("import_project_wrap");
      iCMS.dialog({
        title: 'iCMS - Импорт схемы',
        content:import_project_wrap
      });
  });
  $("#local").click(function() {
      $("#localfile").click();
  });
  $("#localfile").change(function() {
      $("#import_project_wrap form").submit();
      $(this).val('');
  });
});
</script>

<div class="iCMS-container">
  <div class="widget-box">
    <div class="widget-title"> <span class="icon"> <i class="fa fa-search"></i> </span>
      <h5SEO Заголовок/h5>
      <div class="pull-right">
        <button class="btn btn-success" type="button" id="import_project"><i class="fa fa-send"></i> Импорт схемы</button>
      </div>
    </div>
    <div class="widget-content">
      <form action="<?php echo iPHP_SELF; ?>" method="get" class="form-inline">
        <input type="hidden" name="app" value="<?php echo admincp::$APP_NAME;?>" />
        <input type="hidden" name="do" value="<?php echo admincp::$APP_DO;?>" />
        <div class="input-prepend input-append"> <span class="add-on">Категории</span>
          <select name="cid" id="cid" class="span3 chosen-select">
            <option value="0"> Все категории</option>
            <?php echo category::select(); ?>
          </select>
          <span class="add-on">
          <input type="checkbox" name="sub" id="sub"/>
          Подкатегории</span> </div>
        <div class="input-prepend"> <span class="add-on"> Правила </span>
          <select name="rid" id="rid" class="span3 chosen-select">
            <option value="0">Все правила</option>
            <?php foreach ((array)$ruleArray as $rid => $rname) {
              echo '<option value="'.$rid.'">'.$rname.'</option>';
            }?>
          </select>
        </div>
        <div class="input-prepend"> <span class="add-on">Правила публикации</span>
          <select name="poid" id="poid" class="span3 chosen-select">
            <option value="0">Все правила</option>
            <?php foreach ((array)$postArray as $poid => $poname) {
              echo '<option value="'.$poid.'">'.$poname.'</option>';
            }?>
          </select>
        </div>
        <div class="clearfix mb10"></div>
        <div class="input-prepend input-append"><span class="add-on"><i class="fa fa-calendar"></i> Время публикации</span>
          <input type="text" class="ui-datepicker" name="starttime" value="<?php echo $_GET['starttime'] ; ?>" placeholder="Время начала" />
          <span class="add-on">-</span>
          <input type="text" class="ui-datepicker" name="endtime" value="<?php echo $_GET['endtime'] ; ?>" placeholder="Время окончания" />
          <span class="add-on"><i class="fa fa-calendar"></i></span>
        </div>
        <div class="input-prepend input-append">
          <span class="add-on">Автоматический режим</span>
          <span class="add-on">Да
            <input type="radio" name="auto" class="radio" value="1"/>
          </span>
          <span class="add-on">Нет
            <input type="radio" name="auto" class="radio" value="0"/>
          </span>
        </div>
        <div class="input-prepend input-append"> <span class="add-on">На страницу</span>
          <input type="text" name="perpage" id="perpage" value="<?php echo $_GET['perpage'] ? $_GET['perpage'] : 20; ?>" style="width:36px;"/>
          <span class="add-on">записей</span>
        </div>
        <div class="input-prepend input-append"> <span class="add-on">Ключевое слово</span>
          <input type="text" name="keywords" class="span2" id="keywords" value="<?php echo $_GET['keywords']; ?>" />
          <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i>поиск</button>
        </div>
      </form>
    </div>
  </div>
  <div class="widget-box" id="<?php echo APP_BOXID;?>">
    <div class="widget-title"> <span class="icon">
      <input type="checkbox" class="checkAll" data-target="#<?php echo APP_BOXID;?>" />
      </span>
      <h5>Список схем</h5>
    </div>
    <div class="widget-content nopadding">
      <form action="<?php echo APP_FURI; ?>&do=batch" method="post" class="form-inline" id="<?php echo APP_FORMID;?>" target="iPHP_FRAME">
        <table class="table table-bordered table-condensed table-hover">
          <thead>
            <tr>
              <th><i class="fa fa-arrows-v"></i></th>
              <th>ID</th>
              <th>Имя</th>
              <th>Правила</th>
              <th>Категории</th>
              <th>Публикация</th>
              <th>Операции</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $categoryArray = category::multi_get($rs,'cid');
            for($i=0;$i<$_count;$i++){
              $C = (array)$categoryArray[$rs[$i]['cid']];
            ?>
            <tr id="id<?php echo $rs[$i]['id']; ?>">
              <td><input type="checkbox" name="id[]" value="<?php echo $rs[$i]['id']; ?>" /></td>
              <td><?php echo $rs[$i]['id']; ?></td>
              <td>
                <a href="<?php echo __ADMINCP__; ?>=spider&do=manage&pid=<?php echo $rs[$i]['id']; ?>"><?php echo $rs[$i]['name']; ?></a> <br /><?php echo $rs[$i]['lastupdate']?get_date($rs[$i]['lastupdate'],'Y-m-d H:i:s'):'' ; ?>
                <div class="action mb10">
                  <a href="<?php echo APP_URI; ?>&do=copy&pid=<?php echo $rs[$i]['id']; ?>" class="btn mt10" target="iPHP_FRAME"><i class="fa fa-copy"></i> Копировать</a>
                  <a href="<?php echo APP_URI; ?>&do=add&pid=<?php echo $rs[$i]['id']; ?>" class="btn mt10"><i class="fa fa-edit"></i> Изменить </a>
                  <a href="<?php echo __ADMINCP__; ?>=spider_rule&do=test&rid=<?php echo $rs[$i]['rid']; ?>" class="btn mt10" data-toggle="modal" title="测试规则"><i class="fa fa-keyboard-o"></i> 测试规则</a>
                  <a href="<?php echo __ADMINCP__; ?>=spider_rule&do=test&pid=<?php echo $rs[$i]['id']; ?>" class="btn mt10" data-toggle="modal" title="测试方案"><i class="fa fa-keyboard-o"></i> 测试方案</a>
                  <a href="<?php echo __ADMINCP__; ?>=spider&do=listpub&pid=<?php echo $rs[$i]['id']; ?>" class="btn mt10 btn-primary" data-toggle="modal" title="采集列表,手动发布"><i class="fa fa-hand-o-up"></i> В ручном режиме</a>
                  <a href="<?php echo __ADMINCP__; ?>=spider&do=start&pid=<?php echo $rs[$i]['id']; ?>" class="btn mt10 btn-success tip" target="iPHP_FRAME" title="Автоматически собирать и публиковать "><i class="fa fa-play"></i> Запустить парсинг</a>
                  <a href="<?php echo __ADMINCP__; ?>=spider&do=manage&pid=<?php echo $rs[$i]['id']; ?>" class="btn mt10" target="_blank"><i class="fa fa-list-alt"></i> Данные</a>
                </div>
              </td>
              <td>
                <a href="<?php echo APP_URI; ?>&do=manage&rid=<?php echo $rs[$i]['rid']; ?>&<?php echo $uri; ?>"><?php echo $ruleArray[$rs[$i]['rid']]; ?></a>
                <a href="<?php echo __ADMINCP__; ?>=spider_rule&do=add&rid=<?php echo $rs[$i]['rid']; ?>" target="_blank"><i class="fa fa-edit"></i></a>
              </td>
              <td><a href="<?php echo APP_URI; ?>&do=manage&cid=<?php echo $rs[$i]['cid']; ?>&<?php echo $uri; ?>"><?php echo $C['name']; ?></a><?php echo $rs[$i]['auto']?'<i class="fa fa-rocket"></i>':''; ?></td>
              <td>
                <a href="<?php echo APP_URI; ?>&do=manage&poid=<?php echo $rs[$i]['poid']; ?>&<?php echo $uri; ?>"><?php echo $postArray[$rs[$i]['poid']]; ?></a>
                <a href="<?php echo __ADMINCP__; ?>=spider_post&do=add&poid=<?php echo $rs[$i]['poid']; ?>" target="_blank"><i class="fa fa-edit"></i></a>
              </td>
              <td style="text-align: right;">
                <a href="<?php echo __ADMINCP__; ?>=spider&do=dropurl&pid=<?php echo $rs[$i]['id']; ?>&type=all" class="btn mt10 btn-warning" target="iPHP_FRAME"  onclick="return confirm('确定要清空数据?');"><i class="fa fa-trash-o"></i> 清空数据</a>
                <a href="<?php echo __ADMINCP__; ?>=spider&do=dropurl&pid=<?php echo $rs[$i]['id']; ?>&type=0" class="btn mt10 btn-warning" target="iPHP_FRAME"  onclick="return confirm('Вы уверены, что хотите удалить неопубликованные данные?');"><i class="fa fa-inbox"></i> Очистить неопубликованные</a>
                <a href="<?php echo __ADMINCP__; ?>=spider&do=dropdata&pid=<?php echo $rs[$i]['id']; ?>" class="btn mt10 btn-danger" target="iPHP_FRAME"  onclick="return confirm('确定要删除采集数据,此操作会删除本方案的采集数据,并删除内容?');"><i class="fa fa-trash"></i> 删除所有采集数据&内容</a>
                <a href="<?php echo APP_FURI; ?>&do=del&pid=<?php echo $rs[$i]['id']; ?>" target="iPHP_FRAME" class="del btn mt10 btn-danger" title='删除本方案'  onclick="return confirm('确定要删除方案?');"/><i class="fa fa-trash"></i> 删除方案</a>
		          </td>
	          </tr>
            <?php } ?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="7"><div class="pagination pagination-right" style="float:right;"><?php echo iPagination::$pagenav; ?></div>
                <div class="input-prepend input-append mt20"> <span class="add-on"> Выбрать все
                  <input type="checkbox" class="checkAll checkbox" data-target="#<?php echo APP_BOXID;?>" />
                  </span>
                  <div class="btn-group dropup" id="iCMS-batch"> <a class="btn dropdown-toggle" data-toggle="dropdown" tabindex="-1"><i class="fa fa-wrench"></i>Пакетная операция</a><a class="btn dropdown-toggle" data-toggle="dropdown" tabindex="-1"> <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                      <li><a data-toggle="batch" data-action="auto:1"><i class="fa fa-check-square"></i> 标识自动采集</a></li>
                      <li><a data-toggle="batch" data-action="auto:0"><i class="fa fa-circle-o"></i> 取消自动采集</a></li>
                      <li class="divider"></li>
                      <li><a data-toggle="batch" data-action="lastupdate:0"><i class="fa fa-calendar"></i> 重置最后采集时间</a></li>
                      <li class="divider"></li>
                      <li><a data-toggle="batch" data-action="move"><i class="fa fa-fighter-jet"></i> 设置发布栏目</a></li>
                      <li class="divider"></li>
                      <li><a data-toggle="batch" data-action="poid"><i class="fa fa-magnet"></i> 设置发布规则</a></li>
                      <li class="divider"></li>
                      <li><a data-toggle="batch" data-action="rid"><i class="fa fa-magnet"></i> 设置采集规则</a></li>
                      <li class="divider"></li>
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
<div class='iCMS-batch'>
  <div id="poidBatch" style="width: 330px;">
    <div class="input-prepend">
        <span class="add-on">Правила публикации</span>
        <select name="poid" id="poid" class="span3 chosen-select">
          <option value="0">Неизвестно</option>
          <?php foreach ((array)$postArray as $poid => $poname) {
            echo '<option value="'.$poid.'">'.$poname.'</option>';
          }?>
        </select>
    </div>
  </div>
  <div id="ridBatch" style="width: 330px;">
    <div class="input-prepend">
        <span class="add-on"> Правила сбора </span>
        <select name="rid" id="rid" class="span3 chosen-select">
          <option value="0">Неизвестно</option>
          <?php foreach ((array)$ruleArray as $rid => $rname) {
            echo '<option value="'.$rid.'">'.$rname.'</option>';
          }?>
        </select>
    </div>
  </div>
</div>
<div id="import_project_wrap" style="display:none;">
  <form action="<?php echo APP_FURI; ?>&do=import" method="post" enctype="multipart/form-data" target="iPHP_FRAME">
    <div class="alert alert-info">
      Импортировать (только TXT)
    </div>
    <div class="clearfloat mb10"></div>
    <a id="local" class="btn btn-primary btn-large btn-block"><i class="fa fa-upload"></i> 请选择要导入的方案</a>
    <input id="localfile" name="upfile" type="file" class="hide"/>
    <div class="clearfloat mb10"></div>
  </form>
</div>
<?php admincp::foot(); ?>
