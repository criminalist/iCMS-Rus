<?php

defined('iPHP') OR exit('Oops, something went wrong');
admincp::head(false);
?>
<pre style="margin:4px;padding: 4px; background: #fff;">
<?php echo str_replace('<br />', "\n", $log[0]) ; ?>
</pre>

<div class="widget-box widget-plain hide">
  <div class="widget-content nopadding">
    <table class="table table-bordered table-condensed table-hover">
      <thead>
        <tr>
          <th><i class="fa fa-arrows-v"></i></th>
          <th>执行</th>
          <th>Файлы</th>
        </tr>
      </thead>
      <tbody>
        <?php
            if($log[1]) foreach ($log[1] AS $k => $value) {
        ?>
        <tr>
          <td><?php echo $k+1; ?></td>
          <td><?php echo $type_map[$value[0]] ; ?></td>
          <td><?php echo $value[2]; ?></td>
        </tr>
        <?php }?>
      </tbody>
    </table>
  </div>
</div>
<?php admincp::foot(); ?>
