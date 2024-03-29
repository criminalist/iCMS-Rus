<?php

defined('iPHP') OR exit('Oops, something went wrong');
admincp::head();
?>
<script type="text/javascript">
$(function(){
  iCMS.select('pid',"<?php echo $rs['pid']?$rs['pid']:0 ; ?>");
  iCMS.select('appid',"<?php echo $rs['appid']?$rs['appid']:0 ; ?>");
	iCMS.select('mode',"<?php echo $rs['mode'] ; ?>");
  iCMS.select('status',"<?php echo $rs['status'] ; ?>");

	iCMS.select('config_ucshow',"<?php echo $rs['config']['ucshow'] ; ?>");
	iCMS.select('config_send',"<?php echo $rs['config']['send'] ; ?>");
	iCMS.select('config_examine',"<?php echo $rs['config']['examine'] ; ?>");

  $("#mode").change(function(event) {
    if(this.value=="0"){
      $("#mode-box").hide();
    }else{
      $("#mode-box").show();
    }
  });
});
</script>
<style>
.category_rule>li>a{width: auto;}
.category_rule:before {
    position: absolute;
    top: -7px;
    left: 9px;
    display: inline-block;
    border-right: 7px solid transparent;
    border-bottom: 7px solid #ccc;
    border-left: 7px solid transparent;
    border-bottom-color: rgba(0,0,0,0.2);
    content: ''
}

.category_rule:after {
    position: absolute;
    top: -6px;
    left: 10px;
    display: inline-block;
    border-right: 6px solid transparent;
    border-bottom: 6px solid #fff;
    border-left: 6px solid transparent;
    content: ''
}
</style>
<div class="iCMS-container">
  <div class="widget-box">
    <div class="widget-title"> <span class="icon"> <i class="fa fa-plus-square"></i> </span>
      <h5 class="brs"><?php echo empty($this->cid)?'Добавить':'Редактировать' ; ?> <?php echo $this->category_name;?></h5>
      <ul class="nav nav-tabs" id="category-add-tab">
        <li class="active"><a href="#category-add-base" data-toggle="tab"><i class="fa fa-info-circle"></i> Основная информация</a></li>
        <li><a href="#category-add-url" data-toggle="tab"><i class="fa fa-link"></i> Правила формирования URL</a></li>
        <li><a href="#category-add-tpl" data-toggle="tab"><i class="fa fa-columns"></i> Шаблон</a></li>
        <li><a href="#category-add-config" data-toggle="tab"><i class="fa fa-cog"></i> Конфигурация </a></li>
        <li><a href="#category-add-custom" data-toggle="tab"><i class="fa fa-wrench"></i>Пользовательские поля</a></li>
        <li><a href="#apps-metadata" data-toggle="tab"><i class="fa fa-sitemap"></i> Свойства</a></li>
        <li><a href="#category-app-meta" data-toggle="tab"><i class="fa fa-sitemap"></i> Динамические свойства</a></li>
      </ul>
    </div>
    <div class="widget-content nopadding">
      <form action="<?php echo $this->category_furi; ?>&do=save" method="post" class="form-inline" id="<?php echo APP_FORMID;?>" target="iPHP_FRAME">
        <input name="cid" type="hidden" value="<?php echo $rs['cid']  ; ?>" />
        <input name="_pid" type="hidden" value="<?php echo $rs['pid']  ; ?>" />
        <div id="category-add" class="tab-content">
          <div id="category-add-base" class="tab-pane active">
            <div class="input-prepend"> <span class="add-on">Родитель</span>
              <?php if(category::check_priv($rootid,'a')||empty($rootid)) {   ?>
              <select name="rootid" class="span6 chosen-select">
                <option value="0">======<?php echo $this->category_name;?>=====</option>
                <?php echo category::priv('a')->select($rootid,0,1,true);?>
              </select>
              <!-- <span class="add-on" title="继承"><input type="checkbox" name="extends" /> 继承</span> -->
              <?php }else { ?>
              <input name="_rootid_hash" type="hidden" value="<?php echo auth_encode($rootid) ; ?>" />
              <input name="rootid" id="rootid" type="hidden" value="<?php echo $rootid ; ?>" />
              <input readonly="true" value="<?php echo category::get($rootid)->name ; ?>" type="text" class="txt" />
              <?php } ?>
            </div>
            <span class="help-inline">Родительская категория</span>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend input-append"> <span class="add-on">Свойства</span>
              <select name="pid[]" id="pid" class="chosen-select span6" data-placeholder="Выберите свойства..." multiple="multiple">
                <option value="0">Общие [pid='0']</option>
                <?php echo propAdmincp::app('category')->get("pid") ; ?>
              </select>
              <?php echo propAdmincp::btn_add('Добавить общие свойства');?>
            </div>
            <div class="clearfloat mb10"></div>
            <?php if($this->appid===null){?>
            <div class="input-prepend"> <span class="add-on">Привязка к приложению</span>
              <select name="appid" id="appid" class="chosen-select span6" data-placeholder="Выберите приложение ... <?php echo $this->category_name;?>">
                <option value="0">Без привязки [appid='0']</option>
              <?php foreach (apps::get_array(array("!table"=>0)) as $key => $value) {?>
              <option value="<?php echo $value['id'];?>"><?php echo $value['app'];?>:<?php echo $value['name'];?> [appid=<?php echo $value['id'];?>]</option>
              <?php }?>
              </select>
            </div>
            <div class="clearfloat mb10"></div>
            <?php }?>
            <div class="input-prepend"> <span class="add-on">Имя</span>
              <?php if(empty($this->cid)){?>
              <textarea name="name" id="name" class="span6" style="height: 150px;width:600;"><?php echo $rs['name'] ; ?></textarea>
              <?php }else{?>
              <input type="text" name="name" class="span6" id="name" value="<?php echo $rs['name'] ; ?>"/>
              <?php }?>
            </div>
            <?php if(empty($this->cid)){?>
            <span class="help-inline"><span class="label label-important">Вы можете добавить <?php echo $this->category_name;?>, по одному на строку</span></span>
            <?php }?>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"> <span class="add-on">Псевдоним</span>
              <input type="text" name="subname" class="span6" id="subname" value="<?php echo $rs['subname'] ; ?>"/>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend input-append"> <span class="add-on">Иконка</span>
              <input type="text" name="pic" class="span6" id="pic" value="<?php echo $rs['pic'] ; ?>"/>
              <?php filesAdmincp::set_opt($rs['pic'])->pic_btn("pic");?>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend input-append"> <span class="add-on">Иконка 2</span>
              <input type="text" name="mpic" class="span6" id="mpic" value="<?php echo $rs['mpic'] ; ?>"/>
              <?php filesAdmincp::set_opt($rs['mpic'])->pic_btn("mpic");?>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend input-append"> <span class="add-on">Иконка 3</span>
              <input type="text" name="spic" class="span6" id="spic" value="<?php echo $rs['spic'] ; ?>"/>
              <?php filesAdmincp::set_opt($rs['spic'])->pic_btn("spic");?>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"> <span class="add-on">SEO Заголовок</span>
              <input type="text" name="title" class="span6" id="title" value="<?php echo $rs['title'] ; ?>" />
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"> <span class="add-on">SEO Ключевые слова</span>
              <input type="text" name="keywords" class="span6" id="keywords" value="<?php echo $rs['keywords'] ; ?>" onkeyup="javascript:this.value=this.value.replace(/,/ig,',');"/>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend" style="width:100%;"><span class="add-on">Описание</span>
              <textarea name="description" id="description" class="span6" style="height: 150px;width:600;"><?php echo $rs['description'] ; ?></textarea>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"> <span class="add-on"> Внешняя ссылка </span>
              <input type="text" name="url" class="span6" id="url" value="<?php echo $rs['url'] ; ?>"/>
            </div>
            <span class="help-inline"><span class="label label-important">При заполнении URL в текущем поле, категория будет доступна только как редирект на текущий URL. (оставьте поле пустым чтобы не активировать переадресацию)</span></span>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"> <span class="add-on">Сортировка</span>
              <input id="sortnum" class="span3" value="<?php echo $rs['sortnum'] ; ?>" name="sortnum" type="text"/>
            </div>
            <div class="clearfloat mb10"></div>
            <?php if($rs['userid'] && $rs['userid']==members::$userid){?>
            <div class="input-prepend"> <span class="add-on">Добавил</span>
              <input name="creator" id="creator" class="span3" value="<?php echo $rs['creator']?$rs['creator']:members::$nickname ; ?>" type="text"/>
              <input name="userid" type="hidden" value="<?php echo $rs['userid']?$rs['userid']:members::$userid ; ?>" />
            </div>
            <div class="clearfloat mb10"></div>
            <?php }?>
            <div class="input-prepend input-append"> <span class="add-on">Статус </span>
              <select name="status" id="status" class="chosen-select span3">
                <option value="0">Скрытый [status="0"]</option>
                <option value="1">Показывать [status="1"]</option>
                <option value="2">不调用[status="2"]</option>
                <?php echo propAdmincp::get("status") ; ?>
              </select>
              <?php echo propAdmincp::btn_add('Добавить новый статус');?>
            </div>
          </div>
          <div id="category-add-url" class="tab-pane hide">
            <div class="input-prepend"> <span class="add-on">Тип URL</span>
              <select name="mode" id="mode" class="chosen-select span3">
                <option value="0">Динамический</option>
                <option value="1">Статический</option>
                <option value="2">Псевдо-статический (требуется настройка веб сервера)</option>
              </select>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"> <span class="add-on">Привязка к домену</span>
              <input type="text" name="domain" class="span3" id="domain" value="<?php echo $rs['domain'] ; ?>"/>
            </div>
            <span class="help-inline">Пример: https://category.site.com</span>
            <div class="clearfloat mb10"></div>
            <div id="mode-box" class="<?php if(!$rs['mode']){ echo ' hide';}?>">
              <div class="input-prepend"> <span class="add-on">Статический каталог</span>
                <input type="text" name="dir" class="span3" id="dir" value="<?php echo $rs['dir'] ; ?>"/>
              </div>
              <div class="clearfloat mb10"></div>
              <div class="input-prepend"> <span class="add-on">Суффикс</span>
                <input type="text" name="htmlext" class="span3" id="htmlext" value="<?php echo $rs['htmlext'] ; ?>"/>
              </div>
              <div class="clearfloat mb10"></div>
              <?php include admincp::view('category.rule',$this->_view_tpl_dir);?>
            </div>
          </div>
          <div id="category-add-tpl" class="tab-pane hide">
              <?php include admincp::view('category.template',$this->_view_tpl_dir);?>
          </div>
          <div id="category-add-config" class="tab-pane hide">
            <div class="input-prepend"> <span class="add-on">Центр пользователя</span>
              <div class="switch">
                <input type="checkbox" data-type="switch" name="config[ucshow]" id="config_ucshow" <?php echo $rs['config']['ucshow']?'checked':''; ?>/>
              </div>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"> <span class="add-on">Разрешить пользователям добавлять новости</span>
              <div class="switch">
                <input type="checkbox" data-type="switch" name="config[send]" id="config_send" <?php echo $rs['config']['send']?'checked':''; ?>/>
              </div>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"> <span class="add-on">Модерация пользовательских новостей</span>
              <div class="switch">
                <input type="checkbox" data-type="switch" name="config[examine]" id="config_examine" <?php echo $rs['config']['examine']?'checked':''; ?>/>
              </div>
            </div>
          </div>
          <div id="apps-metadata" class="tab-pane hide">
            <?php include admincp::view("apps.meta","apps");?>
          </div>
          <div id="category-app-meta" class="tab-pane hide">
            <button class="btn btn-inverse add_meta" type="button" href="#category-app-meta"><i class="fa fa-plus-circle"></i> Добавить динамические свойства контента</button>
            <table class="table table-hover">
              <thead>
                <tr>
                  <th class="span3">Имя</th>
                  <th>Поле<span class="label label-important">(Он может состоять только из английских букв, цифр или _- (кирилица не поддерживается), и если оставить его пустым, он будет автоматически заполнен транслитом)<span></th>
                </tr>
              </thead>
              <tbody>
                <?php if($rs['config']['meta'])foreach((array)$rs['config']['meta'] AS $ckey=>$meta){?>
                <tr>
                  <td><input name="config[meta][<?php echo $ckey;?>][name]" type="text" value="<?php echo $meta['name'];?>" class="span3"/></td>
                  <td><input name="config[meta][<?php echo $ckey;?>][key]" type="text" value="<?php echo $meta['key'];?>" class="span3"/>
                    <button class="btn btn-small btn-danger del_meta" type="button"><i class="fa fa-trash-o"></i> Удалить</button>
                  </td>
                </tr>
                <?php }?>
              </tbody>
              <tfoot>
                <tr class="hide meta_clone">
                  <td><input name="config[meta][{key}][name]" type="text" disabled="disabled" class="span3" /></td>
                  <td ><input name="config[meta][{key}][key]" type="text" disabled="disabled" class="span3" />
                    <button class="btn btn-small btn-danger del_meta" type="button"><i class="fa fa-trash-o"></i> Удалить</button>
                  </td>
                </tr>
              </tfoot>
            </table>
          </div>
          <div id="category-add-custom" class="tab-pane hide">
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
