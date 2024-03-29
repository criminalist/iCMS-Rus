<?php

defined('iPHP') OR exit('Oops, something went wrong');
admincp::head();
?>
<script type="text/javascript">
$(function(){
  $("#prop-app").on('change', function(evt, params) {
    var appid = $(this).find('option[value="' + params['selected'] + '"]').attr('appid');
    $("#appid","#iCMS-prop").val(appid);
  });
  iCMS.select('cid',"<?php echo $rs['cid'] ; ?>");
  iCMS.select('prop-app',"<?php echo $rs['app'] ; ?>");
});
</script>
<div class="iCMS-container">
  <div class="widget-box" id="<?php echo APP_BOXID;?>">
    <div class="widget-title"> <span class="icon"> <i class="fa fa-plus-square"></i> </span>
      <h5><?php echo empty($this->pid)?'Добавить':'Редактировать' ; ?> Свойства </b></h5>
    </div>
    <div class="widget-content nopadding">
      <form action="<?php echo APP_FURI; ?>&do=save" method="post" class="form-inline" id="iCMS-prop" target="iPHP_FRAME">
        <input name="pid" type="hidden" value="<?php echo $this->pid ; ?>" />
        <div id="<?php echo APP_BOXID;?>" class="tab-content">
          <div class="input-prepend"> <span class="add-on">Привязка к категории</span>
            <select name="cid" id="cid" class="span4 chosen-select">
              <option value="0"> ==== Нет привязки к категории ==== </option>
              <?php echo category::priv('ca')->select($rs['cid'],0,1,true);?>
            </select>
          </div>
          <span class="help-inline">Категория к которой привязано свойство</span>
          <div class="clearfloat mb10"></div>
          <div class="input-prepend"> <span class="add-on">Привязка к приложению</span>
            <select name="app" id="prop-app" class="span4 chosen-select">
              <option value="">Все приложения</option>
              <?php
              foreach (apps::get_array(array("!table"=>0)) as $key => $value) {
                $app_array[$value['app']] = $key;
              ?>
              <option value="<?php echo $value['app'];?>" appid="<?php echo $key;?>"><?php echo $value['app'];?>:<?php echo $value['name'];?></option>
              <?php }?>
            </select>
          </div>
          <?php empty($rs['appid']) && $rs['appid'] = $app_array[$rs['app']];?>
          <input name="appid" type="hidden" id="appid" value="<?php echo $rs['appid'];?>"/>
          <div class="clearfloat mb10"></div>
          <div class="input-prepend"> <span class="add-on">Поле свойства</span>
            <input type="text" name="field" class="span4" id="field" value="<?php echo $rs['field'];?>"/>
          </div>
          <div class="clearfloat mb10"></div>
          <div class="input-prepend">
            <?php if($this->pid){?>
              <span class="add-on">Имя свойства</span>
              <input type="text" name="name" class="span4" id="name" value="<?php echo $rs['name'];?>"/>
            <?php }else{?>
              <span class="add-on">Данные свойства</span>
              <textarea name="name" id="name" class="span4" style="height: 150px;"><?php echo $data ; ?></textarea>
            <?php }?>
          </div>

          <p class="help-inline">Поддерживает кирилицу <br />
            <?php if(!$this->pid){?>
            Пакетное форматирование:<br />
            <span class="label label-important">Имя:значение</span><br />
            <span class="label label-important">Имя:</span>(Значение свойства будет заполнено порядковым номером)<br />
            <span class="label label-important">Имя</span>(Значение свойства будет заполнено именем)<br />
            По одному на строку
            <?php }?>
          </p>
          <div class="clearfloat mb10"></div>
          <?php if($this->pid){?>
          <div class="input-prepend"> <span class="add-on">Значение свойств</span>
            <input type="text" name="val" class="span4" id="val" value="<?php echo $rs['val'];?>"/>
          </div>
          <p class="help-inline">
            <span class="label label-important">Значение свойства pid, поддерживаются только цифры</span>
          </p>
          <div class="clearfloat mb10"></div>
          <div class="input-prepend"> <span class="add-on">Сортировка</span>
            <input type="text" name="sortnum" class="span4" id="sortnum" value="<?php echo $rs['sortnum'];?>"/>
          </div>
          <?php }?>
          <div class="clearfloat mb10"></div>
          <div class="input-prepend"> <span class="add-on">Описание</span>
            <input type="text" name="info" class="span6" id="info" value="<?php echo $rs['info'];?>"/>
          </div>
          <div class="clearfloat mb10"></div>
          <div class="alert alert-block">
            <h4>注意事项</h4>
            添加属性时,请综合考虑下前台的调用还有数据的保存问题!<br />
            具体考虑使用数值或者直接使用名称类的值
          </div>
        </div>
        <div class="form-actions">
          <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i>Добавить</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php admincp::foot();?>
