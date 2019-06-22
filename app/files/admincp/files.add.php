<?php

defined('iPHP') OR exit('Oops, something went wrong');
admincp::head(false);
?>
<script type="text/javascript">
$(function() {
    $("#upload").click(function() {
        $("input[name=upfile]").click();
    })
    $("input[name=upfile]").change(function() {
        $("form").submit();
    })
})
function callback(obj) {
  if(obj.code==0){
    return iCMS.alert(obj.state);
  }
	var state	= window.top.modal_<?php echo $this->callback;?>('<?php echo $this->target;?>',obj);
	if(!state){
		window.top.iCMS_MODAL.destroy();
	}
}
</script>
<style>
.footer-debug{display: none;}
</style>
<?php if($this->from!='modal'){?>
<div class="iCMS-container">
<?php } ?>

  <?php if ($rs) { ?>
  <div class="widget-box<?php if($this->from=='modal'){?> widget-plain<?php } ?>" id="files-add">
    <div class="widget-title">
      <h5 class="brs">Информация о файле</h5>
    </div>
    <div class="widget-content nopadding">
      <table class="table table-bordered table-condensed table-hover">
        <tbody>
          <tr>
            <td>Имя файла</td>
            <td>
              <?php echo $rs->filename; ?>.<?php echo $rs->ext; ?>
              <a class="btn btn-mini" href="<?php echo $href; ?>" target="_blank"><i class="fa fa-eye"></i> Посмотреть</a>
            </td>
          </tr>
          <tr>
            <td>Путь к файлу</td>
            <td><?php echo $rs->path; ?></td>
          </tr>
          <tr>
            <td>URL Оригинала</td>
            <td style="height: 20px;overflow: hidden;display: block;"><?php echo $rs->ofilename; ?></td>
          </tr>
          <tr>
            <td>Тип файла</td>
            <td><?php echo files::icon($rs->filename . '.' . $rs->ext); ?> .<?php echo $rs->ext; ?></td>
          </tr>
          <tr>
            <td>Загружен</td>
            <td><?php echo $rs->type ? "Удаленно" : "С компьютера"; ?></td>
          </tr>
          <tr>
            <td>Дата и время</td>
            <td><?php echo get_date($rs->time, 'Y-m-d H:i:s'); ?></td>
          </tr>
        </tbody>
      </table>
      <?php } ?>
      <div class="form-actions mt0 mb0">
        <form action="<?php echo APP_FURI; ?>&do=upload&id=<?php echo $this->id; ?>" method="post" enctype="multipart/form-data" target="iPHP_FRAME">
          <input type="file" name="upfile" class="hide">
          <input type="hidden" name="udir" value="<?php echo $_GET['dir']; ?>">
          <div class="input-prepend input-append"> <span class="add-on">Не наносить водяной знак</span><span class="add-on">
            <input type="checkbox" name="unwatermark" value="1">
            </span><a id="upload" class="btn btn-primary"><i class="fa fa-upload"></i>Выбрать файл</a></div>
        </form>
      </div>
    </div>
  </div>
<?php if($this->from!='modal'){?>
</div>
<?php } ?>
<?php admincp::foot(); ?>
