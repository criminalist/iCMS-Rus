<?php

defined('iPHP') OR exit('Oops, something went wrong');
admincp::head();
?>
<style type="text/css">
.add-on { width: 70px; }
</style>
<div class="iCMS-container">
  <div class="widget-box">
    <div class="widget-title"> <span class="icon"> <i class="fa fa-plus-square"></i> </span>
    <h5><?php echo empty($this->id)?'Добавить':'Изменить' ; ?> ключевое слово</h5>
  </div>
  <div class="widget-content nopadding">
    <form action="<?php echo APP_FURI; ?>&do=save" method="post" class="form-inline" id="iCMS-keywords" target="iPHP_FRAME">
      <input name="id" type="hidden" value="<?php echo $this->id; ?>" />
      <div id="keywords-add" class="tab-content">
        <div class="input-prepend">
          <span class="add-on">Ключевое слово</span>
          <input type="text" name="keyword" class="span3" id="keyword" value="<?php echo $rs['keyword'] ; ?>"/>
        </div>
        <div class="clearfloat mb10"></div>
        <div class="input-prepend">
          <span class="add-on">Заменить на</span>
          <textarea name="replace" id="replace" class="span6" style="height: 150px;"><?php echo $rs['replace'] ; ?></textarea>
        </div>
        <span class="help-inline">Поддерживается html разметка</span>
      </div>
      <div class="form-actions">
        <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i>Отправить</button>
      </div>
    </form>
  </div>
</div>
</div>
<?php admincp::foot();?>
