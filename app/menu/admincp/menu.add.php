<?php

defined('iPHP') OR exit('Oops, something went wrong');
admincp::head();
?>
<script type="text/javascript">
$(function(){
	iCMS.select('target',"<?php echo $rs['target'] ; ?>");
	iCMS.select('data-toggle',"<?php echo $rs['data-toggle'] ; ?>");
});
</script>

<div class="iCMS-container">
  <div class="widget-box">
    <div class="widget-title"> <span class="icon"> <i class="fa fa-plus-square"></i> </span>
      <h5><?php echo empty($id)?'Добавить':'Редактировать' ; ?> меню</h5>
    </div>
    <div class="widget-content nopadding">
      <form action="<?php echo APP_FURI; ?>&do=save" method="post" class="form-inline" id="iCMS-menu" target="iPHP_FRAME">
        <input name="id" type="hidden" value="<?php echo $rs['id']  ; ?>" />
        <div id="menu-add" class="tab-content">
          <div class="input-prepend"> <span class="add-on">上级菜单</span>
            <select name="rootid" class="chosen-select span3">
              <option value="0">========Главное меню=======</option>
              <?php echo $this->select($rootid,0,1);?>
            </select>
          </div>
          <span class="help-inline">本菜单的上级菜单或分类</span>
          <div class="clearfloat mb10"></div>
          <div class="input-prepend"> <span class="add-on">Название приложения</span>
            <input type="text" name="app" class="span3" id="app" value="<?php echo $rs['app'] ; ?>"/>
          </div>
          <span class="help-inline">Пример:category,article</span>
          <div class="clearfloat mb10"></div>
          <div class="input-prepend"> <span class="add-on">Название меню</span>
            <input type="text" name="name" class="span3" id="name" value="<?php echo $rs['name'] ; ?>"/>
          </div>
          <span class="help-inline">Пример:文章管理</span>
          <div class="clearfloat mb10"></div>
          <div class="input-prepend"> <span class="add-on">菜单提示</span>
            <input type="text" name="title" class="span3" id="title" value="<?php echo $rs['title'] ; ?>"/>
          </div>
          <div class="clearfloat mb10"></div>
          <div class="input-prepend"> <span class="add-on">菜单链接</span>
            <input type="text" name="href" class="span3" id="href" value="<?php echo $rs['href'] ; ?>"/>
          </div>
          <span class="help-inline">admincp.php?app=<span class="label label-important">article&do=add</span> 红色这部分</span>
          <div class="clearfloat mb10"></div>
          <div class="input-prepend"> <span class="add-on">链接 CSS</span>
            <input type="text" name="a_class" class="span3" id="a_class" value="<?php echo $rs['a_class'] ; ?>"/>
          </div>
          <div class="clearfloat mb10"></div>
          <div class="input-prepend"> <span class="add-on">菜单图标</span>
            <input type="text" name="icon" class="span3" id="icon" value="<?php echo $rs['icon'] ; ?>"/>
          </div>
          <span class="help-inline">参见 http://fontawesome.io/icons/</span>
          <div class="clearfloat mb10"></div>
          <div class="input-prepend"> <span class="add-on">打开方式</span>
            <select name="target" id="target" class="chosen-select" data-placeholder="正常">
              <option value="">Нормальный</option>
              <option value="iPHP_FRAME">AJAX</option>
              <option value="_blank">Новое окно</option>
            </select>
          </div>
          <div class="clearfloat mb10"></div>
          <div class="input-prepend"> <span class="add-on">菜单模式</span>
            <select name="data-toggle" id="data-toggle" class="chosen-select" data-placeholder="正常">
              <option value="">Нормальный</option>
              <option value="dropdown"> Выпадающее меню </option>
              <option value="modal">Модальное окно</option>
            </select>
          </div>
          <div class="clearfloat mb10"></div>
          <div class="input-prepend"> <span class="add-on">对话框配置</span>
            <input type="text" name="data-meta" class="span3" id="data-meta" value='<?php echo $rs['data-meta'] ; ?>' />
          </div>
          <span class="help-inline">菜单模式:对话框 才填写相关配置</span>
          <div class="clearfloat mb10"></div>
          <div class="input-prepend"> <span class="add-on">Сортировка</span>
            <input id="sortnum" class="span1" value="<?php echo $rs['sortnum'] ; ?>" name="sortnum" type="text"/>
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
