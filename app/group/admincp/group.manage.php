<?php

defined('iPHP') OR exit('Oops, something went wrong');
admincp::head();
?>
<script type="text/javascript">
$(function(){
	$("#<?php echo APP_FORMID;?>").batch();
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
        <div class="input-prepend input-append"> <span class="add-on">На страницу</span>
          <input type="text" name="perpage" id="perpage" value="<?php echo $maxperpage ; ?>" style="width:36px;"/>
          <span class="add-on">записей</span> </div>
        <div class="input-prepend input-append"> <span class="add-on">Ключевое слово</span>
          <input type="text" name="group" class="span2" id="group" value="<?php echo $_GET['group'] ; ?>" />
          <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i>поиск</button>
        </div>
      </form>
    </div>
  </div>
  <div class="widget-box" id="<?php echo APP_BOXID;?>">
    <div class="widget-title"> <span class="icon">
      <input type="checkbox" class="checkAll" data-target="#<?php echo APP_BOXID;?>" />
      </span>
      <h5>角色列表</h5>
    </div>
    <div class="widget-content nopadding">
      <form action="<?php echo APP_FURI; ?>&do=batch" method="post" class="form-inline" id="<?php echo APP_FORMID;?>" target="iPHP_FRAME">
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <th><i class="fa fa-arrows-v"></i></th>
              <th>ID</th>
              <th>Имя</th>
              <th> Тип </th>
              <th>Операции</th>
            </tr>
          </thead>
          <tbody>
            <?php for($i=0;$i<$_count;$i++){?>
            <tr id="id<?php echo $rs[$i]['gid'] ; ?>">
              <td><?php if($rs[$i]['gid']!='1'){  ?><input type="checkbox" name="id[]" value="<?php echo $rs[$i]['gid'] ; ?>" /><?php } ?></td>
              <td><?php echo $rs[$i]['gid'] ; ?></td>
              <td><?php echo $rs[$i]['name'] ; ?></td>
              <td><?php echo $rs[$i]['type']?"Администрация":"Группа участников" ; ?></td>
              <td>
                <?php if($rs[$i]['type']){  ?>
                <a href="<?php echo __ADMINCP__; ?>=members&gid=<?php echo $rs[$i]['gid'] ; ?>&job=1" class="btn btn-small"><i class="fa fa-bar-chart-o"></i> Статистика</a>
                <a href="<?php echo APP_URI; ?>&do=add&tab=mpriv&gid=<?php echo $rs[$i]['gid'] ; ?>" class="btn btn-small"><i class="fa fa-tachometer"></i> Фоновые права доступа</a>
                <a href="<?php echo APP_URI; ?>&do=add&tab=cpriv&gid=<?php echo $rs[$i]['gid'] ; ?>" class="btn btn-small"><i class="fa  fa-unlock-alt"></i> Права доступа к категориям</a>
                <?php } ?>
                <a href="<?php echo APP_URI; ?>&do=add&gid=<?php echo $rs[$i]['gid'] ; ?>" class="btn btn-small"><i class="fa fa-edit"></i> Изменить </a>
                <?php if($rs[$i]['gid']!='1'){  ?>
                <a href="<?php echo APP_FURI; ?>&do=del&gid=<?php echo $rs[$i]['gid'] ; ?>" target="iPHP_FRAME" class="del btn btn-small" title='Удалить навсегда'  onclick="return confirm('Вы уверены, что хотите удалить?');"><i class="fa fa-trash-o"></i> Удалить</a>
                <?php } ?>
              </td>
            </tr>
            <?php } ?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="6"><div class="pagination pagination-right" style="float:right;"><?php echo iPagination::$pagenav ; ?></div>
                <div class="input-prepend input-append mt20"> <span class="add-on"> Выбрать все
                  <input type="checkbox" class="checkAll checkbox" data-target="#<?php echo APP_BOXID;?>" />
                  </span>
                  <div class="btn-group dropup" id="iCMS-batch"> <a class="btn dropdown-toggle" data-toggle="dropdown" tabindex="-1"><i class="fa fa-wrench"></i>Пакетная операция</a><a class="btn dropdown-toggle" data-toggle="dropdown" tabindex="-1"> <span class="caret"></span></a>
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
<?php admincp::foot();?>
