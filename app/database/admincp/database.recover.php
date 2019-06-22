<?php
defined('iPHP') OR exit('Oops, something went wrong');
admincp::head();
?>
<div class="iCMS-container">
  <div class="widget-box" id="<?php echo APP_BOXID;?>">
    <div class="widget-title"> <span class="icon"> <i class="fa fa-cloud"></i> </span>
    <h5 class="brs">База данных</h5>
    <ul class="nav nav-tabs iMenu-tabs">
      <?php echo menu::app_memu(admincp::$APP_NAME); ?>
    </ul>
    <script>$(".iMenu-tabs").find('a[href="<?php echo menu::$url; ?>"]').parent().addClass('active');</script>
  </div>
  <div class="widget-content nopadding">
    <form action="<?php echo APP_FURI; ?>&do=batch" method="post" class="form-inline" id="<?php echo APP_FORMID;?>" target="iPHP_FRAME">
      <table class="table table-bordered table-condensed table-hover">
        <thead>
          <tr>
            <th><i class="fa fa-arrows-v"></i></th>
            <th style="width:24px;"></th>
            <th>Резервный том</th>
            <th class="span4">Операции</th>
          </tr>
        </thead>
        <?php
        $_count   = count($dirRs);
        for($i=0;$i<$_count;$i++){
        ?>
        <tr id="<?php echo md5($dirRs[$i]['name']);?>">
          <td><input type="checkbox" name="dir[]" value="<?php echo $dirRs[$i]['name'] ; ?>" /></td>
          <td><?php echo $i+1 ; ?></td>
          <td><?php echo $dirRs[$i]['name'] ; ?></td>
          <td class="op">
            <a class="btn btn-small" href="<?php echo APP_FURI; ?>&do=download&dir=<?php echo $dirRs[$i]['name'] ; ?>" target="iPHP_FRAME"><i class="fa fa-cloud-download"></i> Скачать </a>
            <a class="btn btn-small" href="<?php echo APP_FURI; ?>&do=recovery&dir=<?php echo $dirRs[$i]['name'] ; ?>" target="iPHP_FRAME"><i class="fa fa-cloud-download"></i> Восстановить</a>
            <a href="<?php echo APP_FURI; ?>&do=del&dir=<?php echo $dirRs[$i]['name'] ; ?>" target="iPHP_FRAME" class="del btn btn-small" title='Удалить навсегда'  onclick="return confirm('Вы уверены, что хотите удалить?');"/><i class="fa fa-trash-o"></i> Удалить</a></td>
        </tr>
        <?php } if(!$_count){  ?>
        <tr><td colspan="4"><div class="alert alert-info">Резервные копии отсутствуют, настоятельно рекомендуем создавать их периодически, и при любых операциях с базой данных!</div></td></tr>
        <?php }?>
      </table>
      <div class="form-actions">
      </div>
    </form>
  </div>
</div>
</div>
<?php admincp::foot();?>
