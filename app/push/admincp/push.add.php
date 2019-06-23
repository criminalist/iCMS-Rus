<?php

defined('iPHP') OR exit('What are you doing?');
admincp::head();
?>
<script type="text/javascript">
$(function(){
  iCMS.select('cid',"<?php echo $rs['cid'] ; ?>");
  iCMS.select('pcid',"<?php echo $rs['pcid'] ; ?>");
  iCMS.select('pid',"<?php echo $rs['pid']?$rs['pid']:0 ; ?>");
	$("#title").focus();

	$("#iCMS-push").submit(function(){
		// if($("#cid option:selected").attr("value")=="0"){
		// 	iCMS.alert("请选择所属版块");
		// 	$("#cid").focus();
		// 	window.scrollTo(0,0);
		// 	return false;
		// }
		if($("#title").val()==''){
			iCMS.alert("1.Введите название!");
			$("#title").focus();
			return false;
		}
	});
});
</script>

<div class="iCMS-container">
  <div class="widget-box">
    <div class="widget-title"> <span class="icon"> <i class="fa fa-plus-square"></i> </span>
      <h5><?php echo empty($id)?'Добавить':'Изменить' ; ?>推荐</h5>
      <ul class="nav nav-tabs" id="push-add-tab">
        <li class="active"><a href="#push-add-base" data-toggle="tab"><i class="fa fa-info-circle"></i> Основная информация</a></li>
        <li><a href="#push-add-custom" data-toggle="tab"><i class="fa fa-wrench"></i> 自定义</a></li>
      </ul>
    </div>
    <div class="widget-content nopadding">
      <form action="<?php echo APP_FURI; ?>&do=save" method="post" enctype="multipart/form-data" class="form-inline" id="iCMS-push" target="iPHP_FRAME">
        <input name="id" type="hidden" value="<?php echo $id ; ?>" />
        <input name="uid" type="hidden" value="<?php echo $rs['uid'] ; ?>" />

        <input name="_cid" type="hidden" value="<?php echo $rs['cid'] ; ?>" />
        <input name="_pcid" type="hidden" value="<?php echo $rs['pcid'] ; ?>" />
        <input name="_pid" type="hidden" value="<?php echo $rs['pid'] ; ?>" />

        <div id="push-add" class="tab-content">
          <div id="push-add-base" class="tab-pane active">
            <div class="input-prepend"> <span class="add-on">Привязка к категории</span>
              <select name="cid" id="cid" class="chosen-select span6" multiple="multiple" data-placeholder="Выберите категорию  (множественный выбор)...">
                <option value="0"> ==== Категория по умолчанию ==== </option>
                <?php echo category::priv('ca')->select($rs['cid'],0,1,true);?>
              </select>
            </div>
            <span class="help-inline">Категорию к которому привязана рекомендация</span>
  		      <div class="clearfloat mb10"></div>
            <div class="input-prepend"> <span class="add-on">推荐分类</span>
              <select name="pcid[]" id="pcid" class="chosen-select span6" multiple="multiple" data-placeholder="请选择推荐分类  (множественный выбор)...">
                <option value="0"> ==== Категория по умолчанию ==== </option>
                <?php echo category::appid($this->appid,'ca')->select($rs['pcid'],0,1,true);?>
              </select>
            </div>
            <span class="help-inline">本推荐所属的标签分类</span>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"> <span class="add-on"> Свойства </span>
              <select name="pid[]" id="pid" class="chosen-select span6" multiple="multiple" data-placeholder="请选择标签属性  (множественный выбор)...">
                <option value="0">普通标签[pid='0']</option>
                <?php echo propAdmincp::get("pid") ; ?>
              </select>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"> <span class="add-on"> Изменить </span>
              <input id="'editor" class="span3" value="<?php echo $rs['editor'] ; ?>" name="editor" type="text"/>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"> <span class="add-on">Время публикации</span>
              <input id="pubdate" class="<?php echo $readonly?'':'ui-datepicker'; ?>" value="<?php echo $rs['pubdate']?$rs['pubdate']:get_date(0,'Y-m-d H:i:s') ; ?>"  name="pubdate" type="text" style="width:230px" <?php echo $readonly ; ?>/>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"> <span class="add-on"> Сортировка </span>
              <input id="sortnum" class="span3" value="<?php echo $rs['sortnum']?$rs['sortnum']:time() ; ?>" name="sortnum" type="text"/>
            </div>
            <fieldset>
              <legend>1</legend>
              <div class="clearfloat mb10"></div>
              <div class="input-prepend"> <span class="add-on"> Название </span>
                <input type="text" name="title" class="span6" id="title" value="<?php echo $rs['title'] ; ?>"/>
              </div>
              <span class="label label-important">*Обязательно</span>
              <div class="clearfloat mb10"></div>
              <div class="input-prepend input-append"> <span class="add-on">С эскизами</span>
                <input type="text" name="pic" class="span6" id="pic" value="<?php echo $rs['pic'] ; ?>"/>
                <?php filesAdmincp::set_opt($rs['pic'])->pic_btn("pic");?>
              </div>
              <div class="clearfloat mb10"></div>
              <div class="input-prepend"> <span class="add-on">URL</span>
                <input type="text" name="url" class="span6" id="url" value="<?php echo $rs['url'] ; ?>"/>
              </div>
              <div class="clearfloat mb10"></div>
              <div class="input-prepend" style="width:100%;"><span class="add-on">Описание</span>
                <textarea name="description" id="description" class="span6" style="height: 150px;"><?php echo $rs['description'] ; ?></textarea>
              </div>
            </fieldset>
            <fieldset>
              <legend>2</legend>
              <div class="clearfloat mb10"></div>
              <div class="input-prepend"> <span class="add-on"> Название </span>
                <input type="text" name="title2" class="span6" id="title2" value="<?php echo $rs['title2'] ; ?>"/>
              </div>
              <div class="clearfloat mb10"></div>
              <div class="input-prepend input-append"> <span class="add-on">С эскизами</span>
                <input type="text" name="pic2" class="span6" id="pic2" value="<?php echo $rs['pic2'] ; ?>"/>
                <?php filesAdmincp::set_opt($rs['pic2'])->pic_btn("pic2");?>
              </div>
              <div class="clearfloat mb10"></div>
              <div class="input-prepend"> <span class="add-on">URL</span>
                <input type="text" name="url2" class="span6" id="url2" value="<?php echo $rs['url2'] ; ?>"/>
              </div>
              <div class="clearfloat mb10"></div>
              <div class="input-prepend" style="width:100%;"><span class="add-on">Описание</span>
                <textarea name="description2" id="description2" class="span6" style="height: 150px;"><?php echo $rs['description2'] ; ?></textarea>
              </div>
            </fieldset>
            <fieldset>
              <legend>3</legend>
              <div class="clearfloat mb10"></div>
              <div class="input-prepend"> <span class="add-on"> Название </span>
                <input type="text" name="title3" class="span6" id="title3" value="<?php echo $rs['title3'] ; ?>"/>
              </div>
              <div class="clearfloat mb10"></div>
              <div class="input-prepend input-append"> <span class="add-on">С эскизами</span>
                <input type="text" name="pic3" class="span6" id="pic3" value="<?php echo $rs['pic3'] ; ?>"/>
                <?php filesAdmincp::set_opt($rs['pic3'])->pic_btn("pic3");?>
              </div>
              <div class="clearfloat mb10"></div>
              <div class="input-prepend"> <span class="add-on">URL</span>
                <input type="text" name="url3" class="span6" id="url3" value="<?php echo $rs['url3'] ; ?>"/>
              </div>
              <div class="clearfloat mb10"></div>
              <div class="input-prepend" style="width:100%;"><span class="add-on">Описание</span>
                <textarea name="description3" id="description3" class="span6" style="height: 150px;"><?php echo $rs['description3'] ; ?></textarea>
              </div>
            </fieldset>
          </div>
          <div id="push-add-custom" class="tab-pane hide">
            <?php echo former::layout();?>
          </div>
          <div id="apps-metadata" class="tab-pane hide">
            <?php // include admincp::view("apps.meta","apps");?>
          </div>
        </div>
        <div class="form-actions">
          <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i> Отправить</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php admincp::foot();?>
