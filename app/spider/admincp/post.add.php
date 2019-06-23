<?php

defined('iPHP') OR exit('Oops, something went wrong');
admincp::head();
?>
<script type="text/javascript">
$(function(){
  iCMS.select('app',"<?php echo $rs['app'] ; ?>");
  $("#app").on('change', function(evt, params) {
    var fun = $(this).find('option[value="' + params['selected'] + '"]').attr('fun');
    $("#fun").val(fun);
    var tipMap ={
      'forms':'Настраиваемая форма form_id',
      'content':'Для пользовательского приложения необходимо заполнить appid'
    }
    var tip = tipMap[params['selected']]||'';
    $(".post-tip").addClass('hide').removeClass('hide').html(tip);
  });
});

</script>

<div class="iCMS-container">
  <div class="widget-box">
    <div class="widget-title"> <span class="icon"> <i class="fa fa-plus-square"></i> </span>
      <h5><?php echo empty($this->poid)?'Добавить':'Редактировать' ; ?> правила публикации</h5>
    </div>
    <div class="widget-content nopadding">
      <form action="<?php echo APP_FURI; ?>&do=save" method="post" class="form-inline" id="iCMS-spider-post" target="iPHP_FRAME">
        <input name="id" type="hidden" value="<?php echo $this->poid ; ?>" />
        <div id="addpost" class="tab-content">
          <div class="input-prepend"><span class="add-on">Приложение</span>
            <select name="app" id="app" class="chosen-select span3">
              <option value="0"></option>
              <option value="article" fun="do_save">Статья</option>
              <option value="book" fun="do_save"> Книга </option>
              <option value="video" fun="do_save">Видео</option>
              <option value="tag" fun="do_save">Теги</option>
              <option value="article_category" fun="do_save">Категория</option>
              <option value="tag_category" fun="do_save"> Категории тегов </option>
              <option value="forms" fun="do_savedata">Настраиваемая форма</option>
              <option value="content" fun="do_save"> Пользовательские приложения  </option>
            </select>
          </div>
          <div class="clearfloat mb10"></div>
          <div class="input-prepend"><span class="add-on">Имя</span>
            <input type="text" name="name" class="span6" id="name" value="<?php echo $rs['name']; ?>"/>
          </div>
          <div class="clearfloat mb10"></div>
          <div class="input-prepend"><span class="add-on">интерфейс</span>
            <input type="text" name="fun" class="span6" id="fun" value="<?php echo $rs['fun']?$rs['fun']:'do_save'; ?>"/>
          </div>
          <span class="help-inline">Может быть удаленно опубликован с помощью URL</span>
          <div class="clearfloat mb10"></div>
          <div class="input-prepend"><span class="add-on">发布项</span>
            <textarea name="post" id="post" class="span6" style="height: 90px;"><?php echo $rs['post'] ; ?></textarea>
          </div>
          <span class="help-inline hide post-tip"></span>
          <div class="clearfloat mb10"></div>
        </div>
        <div class="form-actions">
          <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i> Сохранить</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php admincp::foot();?>
