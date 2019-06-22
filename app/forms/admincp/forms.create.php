<?php

defined('iPHP') OR exit('Oops, something went wrong');
admincp::head();
?>
<style type="text/css">
#field-default .add-on { width: 70px;text-align: right; }
.iCMS_dialog .ui-dialog-content .chosen-container{position: relative;}
.add_table_item{vertical-align: top;margin-top: 5px;}
</style>
<script type="text/javascript">
$(function(){
  $("#iCMS-apps").submit(function(){
    var name =$("#app_name").val();
    if(name==''){
      $("#app_name").focus();
      iCMS.alert("表单名称不能为空");
      return false;
    }
    var app =$("#app_app").val();
    if(app==''){
      $("#app_app").focus();
      iCMS.alert("表单标识不能为空");
      return false;
    }
  });
  $(".add_table_item").click(function(){
    // var clone = $("#table_item").clone();
    // console.log(clone);
      var key = $("#table_list").find('tr').size();
      var tr = $("<tr>");
      for (var i = 0; i < 4; i++) {
          var td = $("<td>");
          td.html('<input type="text" name="table['+key+']['+i+']" class="span2" id="table_'+key+'_'+i+'" value=""/>');
          tr.append(td);
      };
      $("#table_list").append(tr);
  });
})
</script>

<div class="iCMS-container">
  <div class="widget-box">
    <div class="widget-title">
      <span class="icon"> <i class="fa fa-pencil"></i> </span>
      <h5 class="brs"><?php echo empty($this->id)?'Создать':'Изменить' ; ?> форму</h5>
      <ul class="nav nav-tabs" id="apps-add-tab">
        <li class="active"><a href="#apps-add-base" data-toggle="tab"><i class="fa fa-info-circle"></i> Основная информация</a></li>
        <?php if($rs['table'])foreach ($rs['table'] as $key => $tval) {?>
        <li><a href="#apps-add-<?php echo $key; ?>-field" data-toggle="tab"><i class="fa fa-database"></i> Поля таблицы (<?php echo $tval['label']?$tval['label']:$tval['name']; ?>)</a></li>
        <?php }?>
        <?php if(!$rs['table']){?>
        <li><a href="#apps-add-field" data-toggle="tab"><i class="fa fa-cog"></i> Базовое поле</a></li>
        <?php }?>
        <li><a href="#apps-add-custom" data-toggle="tab"><i class="fa fa-cog"></i>Редактор полей</a></li>
      </ul>
    </div>
    <div class="widget-content nopadding">
      <form action="<?php echo APP_FURI; ?>&do=save" method="post" class="form-inline" id="iCMS-apps" target="iPHP_FRAME">
        <input name="_id" type="hidden" value="<?php echo $this->id; ?>" />
        <div id="apps-add" class="tab-content">
          <div id="apps-add-base" class="tab-pane active">
            <div class="input-prepend">
              <span class="add-on">Имя формы</span>
              <input type="text" name="_name" class="span3" id="_name" value="<?php echo $rs['name'] ; ?>"/>
            </div>
            <span class="help-inline">Можно использовать кирилицу</span>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend">
              <span class="add-on">ID формы</span>
              <input type="text" name="_app" class="span3" id="_app" value="<?php echo $rs['app'] ; ?>"/>
            </div>
            <span class="help-inline">Используйте уникальный идентификатор</span>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend">
              <span class="add-on">Заголовок формы</span>
              <input type="text" name="_title" class="span3" id="_title" value="<?php echo $rs['title'] ; ?>"/>
            </div>
            <span class="help-inline">Заголовок формы</span>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend input-append"> <span class="add-on">Изображение формы</span>
              <input type="text" name="_pic" class="span6" id="_pic" value="<?php echo $rs['pic'] ; ?>"/>
              <?php filesAdmincp::pic_btn("_pic");?>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend">
              <span class="add-on">Описание формы</span>
              <textarea name="_description" id="_description" class="span6" style="height: 150px;"><?php echo $rs['description'] ; ?></textarea>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend input-append"> <span class="add-on">Шаблон формы</span>
              <input type="text" name="_tpl" class="span3" id="_tpl" value="<?php echo $rs['tpl'] ; ?>"/>
              <?php echo filesAdmincp::modal_btn('Шаблон','_tpl');?>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend">
              <span class="add-on">Подсазка</span>
              <input type="text" name="config[success]" class="span3" id="config_success" value="<?php echo $rs['config']['success']?$rs['config']['success']:' Все отлично!' ; ?>"/>
            </div>
            <span class="help-inline">表单提交完成提示语</span>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend">
              <span class="add-on">Доступно пользователям</span>
              <div class="switch" data-on-label="Вкл" data-off-label="Выкл">
                <input type="checkbox" data-type="switch" name="config[enable]" id="config_enable" <?php echo $rs['config']['enable']?'checked':''; ?>/>
              </div>
              <span class="help-inline"></span>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend">
              <span class="add-on">Статус формы</span>
              <div class="switch" data-on-label="Вкл" data-off-label="Выкл">
                <input type="checkbox" data-type="switch" name="status" id="status" <?php echo $rs['status']?'checked':''; ?>/>
              </div>
              <span class="help-inline"></span>
            </div>
            <div class="clearfloat mb10"></div>
            <?php if(empty($this->id)){?>
            <div class="input-prepend">
              <span class="add-on">是否同时创建数据表</span>
              <div class="switch" data-on-label="Да" data-off-label="Нет">
                <input type="checkbox" data-type="switch" name="create" id="create" <?php echo $rs['create']?'checked':''; ?>/>
              </div>
            </div>
            <span class="help-block">
              如果选择不同时创建数据表,将只保存表单数据而不创建数据表.需要手工建表<br />
              一般用于数据表已经存在,只需要简单的查/增/改数据功能
            </span>
            <div class="clearfloat mb10"></div>
            <?php }?>
            <h3 class="title" style="width:620px;">
              <span>Таблицы в базе</span>
              <button type="button" class="btn btn-link add_table_item">
                <i class="fa fa-plus-square"></i> Добавить
              </button>
            </h3>
            <table class="table table-bordered bordered" style="width:600px;">
              <thead>
                <tr>
                  <th style="width:120px;"> Название таблицы </th>
                  <th>Первичный ключ</th>
                  <th>关联</th>
                  <th>Имя</th>
                </tr>
              </thead>
              <tbody id="table_list">
              <?php if($rs['table']){?>
                <?php foreach ((array)$rs['table'] as $tkey => $tval) {?>
                <tr>
                  <td><input type="text" name="table[<?php echo $tkey; ?>][0]" class="span2" id="table_<?php echo $tkey; ?>_0" value="<?php echo $tval['name'] ; ?>"/></td>
                  <td><input type="text" name="table[<?php echo $tkey; ?>][1]" class="span2" id="table_<?php echo $tkey; ?>_1" value="<?php echo $tval['primary'] ; ?>"/></td>
                  <td><input type="text" name="table[<?php echo $tkey; ?>][2]" class="span2" id="table_<?php echo $tkey; ?>_2" value="<?php echo $tval['union'] ; ?>"/></td>
                  <td><input type="text" name="table[<?php echo $tkey; ?>][3]" class="span2" id="table_<?php echo $tkey; ?>_3" value="<?php echo $tval['label'] ; ?>"/></td>
                </tr>
                <?php } ?>
              <?php }else{ ?>
                <input name="table" type="hidden" value="<?php echo $rs['table']; ?>" />
              <?php } ?>
              </tbody>
            </table>
            <span class="help-inline">Не меняйте название таблицы</span>
            <div class="clearfloat mb10"></div>
          </div>
          
          <?php include admincp::view("apps.table","apps");?>
          <div id="apps-add-field" class="tab-pane">
            
            <?php include admincp::view("apps.base","apps");?>
          </div>
          <div id="apps-add-custom" class="tab-pane">
            <?php include admincp::view("former.build","former");?>
          </div>
          <div class="clearfloat"></div>
          <div class="form-actions">
            <button class="btn btn-primary btn-small" type="submit"><i class="fa fa-check"></i>Отправить</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<div class="hide">
  <div id="table_item">
      <td><input type="text" name="table[~KEY~][0]" class="span2" id="table_~KEY~_0" value=""/></td>
      <td><input type="text" name="table[~KEY~][1]" class="span2" id="table_~KEY~_1" value=""/></td>
      <td><input type="text" name="table[~KEY~][2]" class="span2" id="table_~KEY~_2" value=""/></td>
      <td><input type="text" name="table[~KEY~][3]" class="span2" id="table_~KEY~_3" value=""/></td>
  </div>
</div>
<?php include admincp::view("former.editor","former");?>
<?php admincp::foot();?>
