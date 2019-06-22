<?php

defined('iPHP') OR exit('Oops, something went wrong');
admincp::head();
?>
<script type="text/javascript">
$(function(){
	<?php if($_GET['cid']){  ?>
	iCMS.select('cid',"<?php echo $_GET['cid'] ; ?>");
	<?php } ?>
	$("#<?php echo APP_FORMID;?>").batch();
});
</script>

<div class="iCMS-container">
  <div class="widget-box">
    <div class="widget-title"> <span class="icon"> <i class="fa fa-search"></i> </span>
      <h5SEO Заголовок/h5>
    </div>
    <div class="widget-content">
      <form action="<?php echo iPHP_SELF; ?>" method="get" class="form-inline">
        <input type="hidden" name="app" value="<?php echo admincp::$APP_NAME;?>" />
        <input type="hidden" name="do" value="<?php echo admincp::$APP_DO;?>" />
        <input type="hidden" name="rid" value="<?php echo $_GET['rid'];?>" />
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
        <div class="input-prepend input-append">
          <input type="text" name="days" id="days" value="<?php echo $_GET['days'] ? $_GET['days'] : 7; ?>" style="width:36px;"/>
          <span class="add-on">Дней</span>
        </div>
        <div class="input-prepend input-append">
          <span class="add-on">На страницу</span>
          <input type="text" name="perpage" id="perpage" value="<?php echo $_GET['perpage'] ? $_GET['perpage'] : 100; ?>" style="width:36px;"/>
          <span class="add-on">записей</span>
        </div>
        <div class="clearfloat mb10"></div>
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
      <h5>Сообщение об ошибке получения адресов</h5>
    </div>
    <div class="widget-content nopadding">
      <form action="<?php echo APP_FURI; ?>&do=batch" method="post" class="form-inline" id="<?php echo APP_FORMID;?>" target="iPHP_FRAME">
        <table class="table table-bordered table-condensed table-hover">
            <thead>
              <tr>
                <td>ID схемы</td>
                <td>ID правила</td>
                <td>С ошибками</td>
                <td>Дата</td>
              </tr>
            </thead>
          <?php foreach ((array)$rs as $key => $value) {?>
          <tr>
            <td><?php echo $value['pid'] ; ?>
              <a href="<?php echo __ADMINCP__; ?>=spider_rule&do=test&pid=<?php echo $value['pid']; ?>" class="btn btn-small" data-toggle="modal" title="测试方案"><i class="fa fa-keyboard-o"></i> 测试方案</a>
              <a href="<?php echo __ADMINCP__; ?>=spider_project&do=add&pid=<?php echo $value['pid']; ?>" class="btn btn-small" target="_blank"><i class="fa fa-edit"></i>Изменить схему</a>
            </td>
            <td>
              <a href="<?php echo __ADMINCP__; ?>=spider_error&do=manage&rid=<?php echo $value['rid']; ?>" class="btn btn-small"><i class="fa fa-eye"></i> <?php echo $ruleArray[$value['rid']]; ?>[<?php echo $value['rid'] ; ?>]</a>
              <a href="<?php echo __ADMINCP__; ?>=spider_rule&do=test&rid=<?php echo $value['rid']; ?>" class="btn btn-small" data-toggle="modal" title="测试<?php echo $ruleArray[$value['rid']]; ?>规则"><i class="fa fa-keyboard-o"></i> Тестировать</a>
              <a href="<?php echo __ADMINCP__; ?>=spider_rule&do=add&rid=<?php echo $value['rid']; ?>" class="btn btn-small" target="_blank"><i class="fa fa-edit"></i> Изменить </a>
              <a href="<?php echo __ADMINCP__; ?>=spider_project&do=manage&rid=<?php echo $value['rid']; ?>" class="btn btn-small" target="_blank"><i class="fa fa-list"></i> Проекты</a>
            </td>
            <td>
              <?php echo $value['ct'] ; ?>
              <a href="<?php echo __ADMINCP__; ?>=spider_error&do=view&pid=<?php echo $value['pid']; ?>" class="btn btn-small" data-toggle="modal" title="Подробности"><i class="fa fa-eye"></i> Подробности</a>
            </td>
            <td><?php echo $value['date'] ; ?></td>
            <td>
              <a href="<?php echo __ADMINCP__; ?>=spider_error&do=del&pid=<?php echo $value['pid']; ?>" class="btn btn-small" target="iPHP_FRAME"><i class="fa fa-close"></i> Удалить</a>
            </td>
          </tr>
          <?php } ?>
        </table>
      </form>
    </div>
  </div>
</div>
<?php admincp::foot(); ?>
