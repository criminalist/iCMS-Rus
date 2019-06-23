<?php

defined('iPHP') OR exit('Oops, something went wrong');
admincp::head();
?>
<script type="text/javascript">
$(function(){
  iCMS.select('status',"<?php echo $_GET['status']; ?>");
  iCMS.select('rid',"<?php echo $_GET['rid'] ; ?>");
  iCMS.select('pid',"<?php echo $_GET['pid'] ; ?>");
	iCMS.select('cid',"<?php echo $_GET['cid'] ; ?>");

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
          Подкатегории</span>
        </div>
        <div class="input-prepend"> <span class="add-on">Схема</span>
          <select name="pid" id="pid" class="chosen-select span3">
            <option value=""></option>
            <option value="all">Все программы</option>
            <?php foreach ($projArray as $key => $value) {
              echo '<option value="'.$key.'">'.$value.'</option>';
            };?>
          </select>
        </div>
        <div class="input-prepend"> <span class="add-on"> Правила сбора </span>
          <select name="rid" id="rid" class="chosen-select span3">
            <option value=""></option>
            <option value="all">Все правила</option>
            <?php foreach ($ruleArray as $key => $value) {
              echo '<option value="'.$key.'">'.$value.'</option>';
            };?>
          </select>
        </div>
        <div class="input-prepend input-append"><span class="add-on"><i class="fa fa-calendar"></i></span>
          <input type="text" class="ui-datepicker" name="starttime" value="<?php echo $_GET['starttime']; ?>" placeholder="Время начала" />
          <span class="add-on">-</span>
          <input type="text" class="ui-datepicker" name="endtime" value="<?php echo $_GET['endtime']; ?>" placeholder="Время окончания" />
          <span class="add-on"><i class="fa fa-calendar"></i></span>
        </div>
        <div class="input-prepend input-append"> <span class="add-on">На страницу</span>
          <input type="text" name="perpage" id="perpage" value="<?php echo $_GET['perpage'] ? $_GET['perpage'] : 20; ?>" style="width:36px;"/>
          <span class="add-on">записей</span>
        </div>
        <div class="clearfloat mb10"></div>
        <div class="input-prepend">
          <span class="add-on"> Статус </span>
          <select name="status" id="status" class="chosen-select span3">
            <option value=""></option>
            <option value="all">Все статусы</option>
            <option value="0"> 未发布 [status='0']</option>
            <option value="1" selected='selected'> 发布 [status='1']</option>
            <?php echo propAdmincp::get("status") ; ?>
          </select>
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
      <h5>采集列表</h5>
    </div>
    <div class="widget-content nopadding">
      <form action="<?php echo APP_FURI; ?>&do=batch" method="post" class="form-inline" id="<?php echo APP_FORMID;?>" target="iPHP_FRAME">
        <table class="table table-bordered table-condensed table-hover">
          <thead>
            <tr>
              <th><i class="fa fa-arrows-v"></i></th>
              <th>ID</th>
              <th>Содержание</th>
              <th>Категории</th>
              <th class="span2">Дата и время парсинга/Публикации</th>
              <th>appid</th>
              <th>ID контента</th>
              <th>Статус / релиз</th>
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
              <td><?php echo $rs[$i]['title']; ?><br />
                <?php echo $rs[$i]['url']; ?></td>
              <td>
                <a href="<?php echo APP_URI; ?>&do=manage&cid=<?php echo $rs[$i]['cid']; ?>&<?php echo $uri; ?>"><?php echo $C['name']; ?></a> <br />
                <a href="<?php echo APP_URI; ?>&do=manage&rid=<?php echo $rs[$i]['rid']; ?>&<?php echo $uri; ?>"><?php echo $ruleArray[$rs[$i]['rid']]; ?></a></td>
              <td><?php echo get_date($rs[$i]['addtime'], 'Y-m-d H:i'); ?>
                <br />
                <?php echo $rs[$i]['pubdate'] ? get_date($rs[$i]['pubdate'], 'Y-m-d H:i') : 'Неопубликованные' ?>
              </td>
              <td><?php echo $rs[$i]['appid']; ?></td>
              <td><?php echo $rs[$i]['indexid']; ?></td>
              <td><?php echo $rs[$i]['status']; ?>/<?php echo $rs[$i]['publish']; ?></td>
              <td>
<?php if($_GET['perpage']<500){?>
                <a href="<?php echo __ADMINCP__; ?>=files&indexid=<?php echo $rs[$i]['indexid'] ; ?>&method=database" class="tip-bottom" title="查看内容使用的图片" target="_blank"><i class="fa fa-picture-o"></i></a>
                <a href="<?php echo __ADMINCP__; ?>=spider_project&do=add&pid=<?php echo $rs[$i]['pid'] ; ?>" class="btn btn-small" target="_blank"><i class="fa fa-edit"></i> Изменить схему</a>
                <a href="<?php echo __ADMINCP__; ?>=spider_rule&do=add&rid=<?php echo $rs[$i]['rid'] ; ?>" class="btn btn-small" target="_blank"><i class="fa fa-edit"></i> Редактировать правила</a>
                <?php if($rs[$i]['indexid']){?>
                <a href="<?php echo __ADMINCP__; ?>=article&do=add&id=<?php echo $rs[$i]['indexid'] ; ?>" class="btn btn-small"><i class="fa fa-edit"></i> 编辑内容</a>
                  <?php if(empty($rs[$i]['publish'])){?>
                  <a href="<?php echo APP_FURI; ?>&do=update&sid=<?php echo $rs[$i]['id']; ?>&_args=publish:1" class="btn btn-small" target="iPHP_FRAME"><i class="fa fa-flag"></i> 标识发布</a>
                  <?php }else{?>
                  <a href="<?php echo APP_FURI; ?>&do=publish&sid=<?php echo $rs[$i]['id']; ?>&pid=<?php echo $rs[$i]['pid']; ?>&indexid=<?php echo $rs[$i]['indexid'] ; ?>" class="btn btn-small" target="iPHP_FRAME"><i class="fa fa-edit"></i> 重新发布</a>
                  <?php }?>
                <?php }else{?>
                <a href="<?php echo APP_FURI; ?>&do=publish&sid=<?php echo $rs[$i]['id']; ?>&pid=<?php echo $rs[$i]['pid']; ?>" class="btn btn-small" target="iPHP_FRAME"><i class="fa fa-edit"></i> Опубликовать</a>
                <a href="<?php echo APP_FURI; ?>&do=update&sid=<?php echo $rs[$i]['id']; ?>&_args=publish:1" class="btn btn-small" target="iPHP_FRAME"><i class="fa fa-flag"></i> 标识发布</a>
                <?php }?>
                <a href="<?php echo __ADMINCP__; ?>=spider_project&do=test&rid=<?php echo $rs[$i]['rid']; ?>&url=<?php echo $rs[$i]['url']; ?>" class="btn btn-small" data-toggle="modal" title="测试内容规则"><i class="fa fa-keyboard-o"></i> Тестировать</a>
                <a href="<?php echo APP_FURI; ?>&do=delspider&sid=<?php echo $rs[$i]['id']; ?>" target="iPHP_FRAME" class="del btn btn-small btn-danger" title='Удалить навсегда'  onclick="return confirm('Вы уверены, что хотите удалить?');"/><i class="fa fa-trash-o"></i> Удалить</a>
                <?php if($rs[$i]['indexid']){?>
                <a href="<?php echo APP_FURI; ?>&do=delcontent&sid=<?php echo $rs[$i]['id']; ?>&pid=<?php echo $rs[$i]['pid']; ?>&indexid=<?php echo $rs[$i]['indexid'] ; ?>" target="iPHP_FRAME" class="del btn btn-small btn-danger" title='删除采集数据和发布的内容'  onclick="return confirm('Вы уверены, что хотите удалить?');"/><i class="fa fa-trash-o"></i> удалить & 内容</a>
                <?php }?>
<?php }?>
              </td>
            </tr>
            <?php } ?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="9"><div class="pagination pagination-right" style="float:right;"><?php echo iPagination::$pagenav; ?></div>
                <div class="input-prepend input-append mt20"> <span class="add-on"> Выбрать все
                  <input type="checkbox" class="checkAll checkbox" data-target="#<?php echo APP_BOXID;?>" />
                  </span>
                  <div class="btn-group dropup" id="iCMS-batch"> <a class="btn dropdown-toggle" data-toggle="dropdown" tabindex="-1"><i class="fa fa-wrench"></i> Пакетные операции</a><a class="btn dropdown-toggle" data-toggle="dropdown" tabindex="-1"> <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                      <li><a data-toggle="batch" data-action="delurl"><i class="fa fa-trash-o"></i> Удалить</a></li>
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
<?php admincp::foot(); ?>
