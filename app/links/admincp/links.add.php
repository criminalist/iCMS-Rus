<?php

defined('iPHP') OR exit('Oops, something went wrong');
admincp::head();
?>
<style type="text/css">
.add-on { width: 70px; }
</style>
<div class="iCMS-container">
  <div class="widget-box" id="<?php echo APP_BOXID;?>">
    <div class="widget-title"> <span class="icon"> <i class="fa fa-plus-square"></i> </span>
      <h5><?php echo empty($this->id)?'Добавить':'Редактировать' ; ?> ссылки</h5>
    </div>
    <div class="widget-content nopadding">
      <form action="<?php echo APP_FURI; ?>&do=save" method="post" class="form-inline" id="<?php echo APP_FORMID;?>" target="iPHP_FRAME">
        <input name="id" type="hidden" value="<?php echo $this->id; ?>" />
        <div class="tab-content">
          <div id="-add-base" class="tab-pane active">
            <div class="input-prepend"> <span class="add-on">Категория</span>
              <input type="text" name="cid" class="span1" id="cid" value="<?php echo $rs['cid'] ; ?>"/>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"> <span class="add-on">Имя</span>
              <input type="text" name="name" class="span3" id="name" value="<?php echo $rs['name'] ; ?>"/>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"> <span class="add-on">logo</span>
              <input type="text" name="logo" class="span6" id="logo" value="<?php echo $rs['logo'] ; ?>"/>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"> <span class="add-on">URL</span>
              <input type="text" name="url" class="span6" id="url" value="<?php echo $rs['url'] ; ?>"/>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend" style="width:100%;"><span class="add-on">Описание</span>
              <textarea name="desc" id="desc" class="span6" style="height: 150px;"><?php echo $rs['desc'] ; ?></textarea>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"> <span class="add-on">Сортировка</span>
              <input type="text" name="sortnum" class="span1" id="sortnum" value="<?php echo $rs['sortnum'] ; ?>"/>
            </div>
            <div class="clearfloat mb10"></div>
            <?php echo former::layout();?>
          </div>
        </div>
        <div class="form-actions">
          <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i> Сохранить</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php admincp::foot();?>
