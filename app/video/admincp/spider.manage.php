<?php

defined('iPHP') OR exit('What are you doing?');
admincp::head();
?>
<script type="text/javascript">
$(function(){
  $("#<?php echo APP_FORMID;?>").batch();
});
</script>
<div class="iCMS-container">
  <div class="widget-box" id="<?php echo APP_BOXID;?>">
    <div class="widget-title">
      <span class="icon">
        <input type="checkbox" class="checkAll" data-target="#<?php echo APP_BOXID;?>" />
      </span>
      <h5 class="brs">Список ресурсов</h5>
      <span class="icon">
        <a class="add_hooks" href="<?php echo APP_URI; ?>&do=addres" title="Добавить ресурсы"><i class="fa fa-plus-square"></i> Добавить ресурсы</a>
      </span>
    </div>
    <div class="widget-content nopadding">
      <form action="<?php echo APP_FURI; ?>&do=batch" method="post" class="form-inline" id="<?php echo APP_FORMID;?>" target="iPHP_FRAME">
        <table class="table table-bordered table-condensed table-hover">
          <thead>
            <tr>
              <th><i class="fa fa-arrows-v"></i></th>
              <th>Имя библиотеки ресурсов</th>
              <th>资源库标识</th>
              <th>资源库地址</th>
              <th>资源库介绍</th>
              <th class="span6"> Операция </th>
            </tr>
          </thead>
          <tbody>
          <?php
          foreach ((array)$rs as $key => $value) {
            $id = $key;
          ?>
            <tr id="id<?php echo $id; ?>">
              <td><input type="checkbox" name="id[]" value="<?php echo $id ; ?>" /></td>
              <td><?php echo $value['name'] ; ?></td>
              <td><?php echo $id ; ?></td>
              <td><?php echo $value['url'] ; ?></td>
              <td><?php echo $value['description'] ; ?></td>
              <td>
                <a href="<?php echo APP_URI; ?>&do=list&rid=<?php echo $value['id']; ?>" class="btn btn-inverse btn-mini"><i class="fa fa-sitemap"></i> 分类绑定 </a>
                <a href="<?php echo APP_URI; ?>&do=crawl&dt=today&rid=<?php echo $value['id']; ?>" class="btn btn-success btn-mini"><i class="fa fa-play"></i> 采集今天</a>
                <a href="<?php echo APP_URI; ?>&do=crawl&dt=week&rid=<?php echo $value['id']; ?>" class="btn btn-info btn-mini"><i class="fa fa-play"></i> 采集本周</a>
                <a href="<?php echo APP_URI; ?>&do=crawl&dt=month&rid=<?php echo $value['id']; ?>" class="btn btn-warning btn-mini"><i class="fa fa-play"></i> 采集本月</a>
                <a href="<?php echo APP_URI; ?>&do=crawl&dt=all&rid=<?php echo $value['id']; ?>" class="btn btn-primary btn-mini"><i class="fa fa-play"></i> 采集全部</a>
                <a href="<?php echo APP_URI; ?>&do=delres&rid=<?php echo $value['id']; ?>" class="btn btn-danger btn-mini" target="iPHP_FRAME"><i class="fa fa-trash-o"></i> Удалить</a>
              </td>
            </tr>
            <?php }  ?>
          </tbody>
        </table>
      </form>
    </div>
  </div>
  <div class="clearfix mt20"></div>
  <div class="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>Warning!</strong> 采集资源库来自互联网,iCMS不提供任何资源采集服务!
  </div>
</div>
<div class='iCMS-batch'>
</div>
<?php admincp::foot();?>
