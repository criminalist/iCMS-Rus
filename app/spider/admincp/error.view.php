<?php

defined('iPHP') OR exit('Oops, something went wrong');
admincp::head(false);
?>
<div class="widget-box widget-plain">
  <div class="widget-content nopadding">
    <table class="table table-bordered table-condensed table-hover">
      <thead>
        <tr>
          <th><i class="fa fa-arrows-v"></i></th>
          <th>URL</th>
          <th class="span5">错误</th>
          <th>位置</th>
          <th>Даты и время</th>
        </tr>
      </thead>
      <?php foreach ((array)$rs AS $key => $value) {?>
      <tr>
        <td><?php echo $value['id'];?></td>
        <td>
          <?php echo $value['url'];?>(<?php echo $value['ct'];?>)
          <br />
          <a href="<?php echo __ADMINCP__; ?>=spider_project&do=test&url=<?php echo urlencode($value['url']);?>&rid=<?php echo $value['rid'];?>&pid=<?php echo $value['pid'];?>" class="btn btn-small" target="_blank">测试网址</a>
          <a href="<?php echo __ADMINCP__; ?>=spider_rule&do=test&rid=<?php echo $value['rid']; ?>" class="btn btn-small" data-toggle="modal" title="Тестировать правило"><i class="fa fa-keyboard-o"></i> Тестировать правило</a>
          <a href="<?php echo __ADMINCP__; ?>=spider_rule&do=add&rid=<?php echo $value['rid']; ?>" class="btn btn-small" target="_blank"><i class="fa fa-edit"></i> Редактировать правила</a>
        </td>
        <td><?php echo str_replace(',', '<br />', $value['msg']);?></td>
        <td><?php echo str_replace(',', '<br />', $value['type']);?></td>
        <td><?php echo date("Y-m-d H:i:s",$value['addtime']);?></td>
      </tr>
      <?php }?>
    </table>
  </div>
</div>
