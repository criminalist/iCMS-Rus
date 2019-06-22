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
      <form action="<?php echo APP_FURI; ?>&do=query" method="post" class="form-inline" id="<?php echo APP_FORMID;?>" target="iPHP_FRAME">
        <div class="tab-content">
          <div class="alert alert-info mb10">Текущий инструмент может легко нарушить работу сайта, потому что связан с базой данных, перед любой операцией обязательно делайте резервные копиии базы данных сайта</div>
          <div class="input-prepend"> <span class="add-on">Поле</span>
            <select name="field" id="field" class="chosen-select">
              <option value="title">Название</option>
              <option value="clink"> Пользовательская ссылка </option>
              <option value="comments">ID комментария</option>
              <option value="pic">Иконка</option>
              <option value="cid">Категории</option>
              <option value="tkd">Заголовок / Ключевые слова / Содержание</option>
              <option value="body">Содержание</option>
            </select>
          </div>
          <div class="clearfloat mb10"></div>
          <div class="input-prepend" style="width:100%;"><span class="add-on">查找</span>
            <textarea name="pattern" id="pattern" class="span6" style="height: 150px;"></textarea>
          </div>
          <div class="clearfloat mb10"></div>
          <div class="input-prepend" style="width:100%;"><span class="add-on">Заменить</span>
            <textarea name="replacement" id="replacement" class="span6" style="height: 150px;"></textarea>
          </div>
          <div class="clearfloat mb10"></div>
          <div class="input-prepend" style="width:100%;"><span class="add-on">Условия (where)</span>
            <textarea name="where" id="where" class="span6" style="height: 150px;"></textarea>
          </div>
    	  <span class="help-inline">Поддерживается только SQL синтаксис.</span>
          <div class="form-actions">
            <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i>Выполнить</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<?php admincp::foot();?>
