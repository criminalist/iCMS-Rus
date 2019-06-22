<?php

defined('iPHP') OR exit('Oops, something went wrong');
admincp::head();
?>
<script type="text/javascript">
$(function(){
    iCMS.select('type',"<?php echo $rs->type ; ?>");
});
</script>
<div class="iCMS-container">
  <div class="widget-box">
    <div class="widget-title"> <span class="icon"> <i class="fa fa-user"></i> </span>
      <h5 class="brs"><?php echo empty($this->gid)?'Добавить':'Изменить' ; ?> группу </h5>
      <ul class="nav nav-tabs" id="group-tab">
        <li class="active"><a href="#group-info" data-toggle="tab"><b>Основная информация</b></a></li>
        <li><a href="#group-mpriv" data-toggle="tab"><b>Права доступа в панели управления</b></a></li>
        <li><a href="#group-apriv" data-toggle="tab"><b>Права доступа к приложениям</b></a></li>
        <li><a href="#group-cpriv" data-toggle="tab"><b>Права доступа к категориям</b></a></li>
      </ul>
    </div>
    <div class="widget-content nopadding">
      <form action="<?php echo APP_FURI; ?>&do=save" method="post" class="form-inline" id="iCMS-group" target="iPHP_FRAME">
        <input name="gid" type="hidden" value="<?php echo $this->gid; ?>" />
        <div id="group-add" class="tab-content">
          <div id="group-info" class="tab-pane active">
            <div class="input-prepend"> <span class="add-on">Тип роли</span>
              <select name="type" id="type" class="chosen-select" data-placeholder="Выберите тип роли">
                <option value='0'>Группа участников [type:0] </option>
                <option value='1'>Администрация [type:1] </option>
              </select>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"> <span class="add-on">Название</span>
              <input type="text" name="name" class="span3" id="name" value="<?php echo $rs->name ; ?>"/>
            </div>
            <div class="clearfloat mb10"></div>
          </div>
          <?php include admincp::view("members.priv","members"); ?>
        </div>
        <div class="form-actions">
          <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i>Отправить</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php admincp::foot();?>
