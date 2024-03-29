<?php

defined('iPHP') OR exit('Oops, something went wrong');
$preview = isset($_GET['preview']);
admincp::head(!$preview);
?>
<div class="iCMS-container">
  <div class="widget-box" id="<?php echo APP_BOXID;?>">
    <div class="widget-title">
      <span class="icon"> <i class="fa fa-pencil"></i> </span>
      <?php if($preview){?>
            <h5 class="brs">预览表单</h5>
      <?php }else{ ?>
            <h5 class="brs"><?php echo ($this->id?'Редактировать':'Добавить'); ?><?php echo $this->form['title'];?></h5>
      <?php } ?>
      <div class="input-prepend" style="margin-top: 3px; margin-left: 5px;">
        <span class="add-on"> Форма </span>
        <select name="sssfid" id="sssfid" class="chosen-select span4"
        onchange="window.location.href='<?php echo APP_DOURI; ?>&fid='+this.value"
        data-placeholder="== Выберите форму ==">
          <?php echo $this->select();?>
        </select>
        <script>
        $(function(){
         iCMS.select('sssfid',"<?php echo (int)$_GET['fid'] ; ?>");
        })
        </script>
      </div>
    </div>
    <div class="widget-content">
      <?php if($this->fid){?>
        <form action="<?php echo APP_FURI; ?>&do=savedata" method="post" class="form-inline" id="<?php echo APP_FORMID;?>" target="iPHP_FRAME">
          <input id="fid" name="fid" type="hidden"  value="<?php echo $this->fid;?>" />
          <input name="REFERER" type="hidden" value="<?php echo iPHP_REFERER ; ?>" />
          <?php echo former::layout();?>
          <?php if($preview){?>
          <?php }else{ ?>
          <?php }?>
          <div class="form-actions">
            <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i> Сохранить</button>
          </div>
        </form>
      <?php } ?>
    </div>
  </div>
</div>
<?php admincp::foot();?>
