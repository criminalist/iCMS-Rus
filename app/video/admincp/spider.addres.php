<?php

defined('iPHP') OR exit('What are you doing?');
admincp::head();
?>
<script type="text/javascript">
</script>
<div class="iCMS-container">
  <div class="widget-box">
    <div class="widget-title"> <span class="icon"> <i class="fa fa-cubes"></i> </span>
      <h5><?php echo empty($rs)?'Добавить':'Редактировать' ; ?>资源库</h5>
    </div>
    <div class="widget-content">
      <form action="<?php echo APP_FURI; ?>&do=saveres" method="post" class="form-inline" id="iCMS-video" target="iPHP_FRAME">
        <div class="input-prepend"> <span class="add-on">Имя библиотеки ресурсов</span>
          <input type="text" name="name" class="span3" id="name" value="<?php echo $rs['name']; ?>"/>
        </div>
        <div class="clearfloat mb10"></div>
        <div class="input-prepend"> <span class="add-on">资源库地址</span>
          <input type="text" name="url" class="span6" id="url" value="<?php echo $rs['url']; ?>"/>
        </div>
        <span class="help-line">资源库api地址，如：http://www.ooxx.com/api.php</span>
        <div class="clearfloat mb10"></div>
        <div class="input-prepend"> <span class="add-on">资源库描述</span>
          <input type="text" name="info" class="span6" id="info" value="<?php echo $rs['info']; ?>"/>
        </div>
        <div class="clearfloat mb10"></div>
        <div class="form-actions">
          <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i> Сохранить</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php admincp::foot();?>
