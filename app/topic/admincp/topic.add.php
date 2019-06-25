<?php

defined('iPHP') OR exit('What are you doing?');
admincp::head();
?>
<script type="text/javascript">
window.catchRemoteImageEnable = <?php echo iCMS::$config['topic']['catch_remote']?'true':'false';?>;
</script>
<script type="text/javascript" charset="utf-8" src="./app/admincp/ui/iCMS.ueditor.js"></script>
<script type="text/javascript" charset="utf-8" src="./app/admincp/ui/ueditor/ueditor.all.min.js"></script>

<script type="text/javascript">
$(function(){
  $("#title").focus();
  iCMS.select('pid',"<?php echo $rs['pid']?trim($rs['pid']):0 ; ?>");
  iCMS.select('cid',"<?php echo $cid; ?>");
  iCMS.select('tcid',"<?php echo $rs['tcid'] ; ?>");
  iCMS.select('scid',"<?php echo trim($rs['scid']);?>");
  iCMS.select('status',"<?php echo $rs['status']; ?>");
  $('#inbox').click(function(){
    if($(this).prop("checked")){
      iCMS.select('status',"0");
    }else{
      iCMS.select('status',"1");
    }
  })

  var hotkey = false;

	$("#<?php echo APP_FORMID;?>").submit(function(){
      if(hotkey){
          if(this.action.indexOf('&keyCode=ctrl-s')===-1){
            this.action+='&keyCode=ctrl-s';
          }
      }
      var cid = $("#cid option:selected").val();
  		if($("#title").val()==''){
        $("#title").focus();
  			iCMS.alert("标题不能为空!");
  			return false;
  		}
      $("select[name^='body'] option").each(function() {
        $(this).attr('selected', 'selected');
      });
	});
  $(document).keydown(function (e) {
    var keyCode = e.keyCode || e.which || e.charCode;
    var ctrlKey = e.ctrlKey || e.metaKey;
    if(ctrlKey && keyCode == 83) {
        e.preventDefault();
        hotkey = true;
        $("#<?php echo APP_FORMID;?>").submit();
    }
    hotkey = false;
  });
});

</script>

<div class="iCMS-container">
  <div class="widget-box">
    <div class="widget-title">
      <span class="icon"> <i class="fa fa-pencil"></i> </span>
      <h5 class="brs"><?php echo empty($this->id)?'Добавить':'Редактировать' ; ?>专题</h5>
      <ul class="nav nav-tabs" id="topic-add-tab">
        <li class="active"><a href="#topic-add-base" data-toggle="tab"><i class="fa fa-info-circle"></i> Основная информация</a></li>
        <li><a href="#topic-add-data" data-toggle="tab"><i class="fa fa-tasks"></i> 专题数据</a></li>
        <li><a href="#topic-add-publish" data-toggle="tab"><i class="fa fa-rocket"></i> Настройки публикации</a></li>
        <li><a href="#former-layout" data-toggle="tab"><i class="fa fa-wrench"></i> 自定义</a></li>
        <li><a href="#apps-metadata" data-toggle="tab"><i class="fa fa-sitemap"></i> 动态属性</a></li>
      </ul>
    </div>
    <div class="widget-content nopadding">
      <form action="<?php echo APP_FURI; ?>&do=save" method="post" class="form-inline" id="<?php echo APP_FORMID;?>" target="iPHP_FRAME">
        <input name="_cid" type="hidden" value="<?php echo $rs['cid'] ; ?>" />
        <input name="_tcid" type="hidden" value="<?php echo $rs['tcid'] ; ?>" />
        <input name="_scid" type="hidden" value="<?php echo $rs['scid']; ?>" />
        <input name="_tags" type="hidden" value="<?php echo $rs['tags']; ?>" />
        <input name="_pid" type="hidden" value="<?php echo $rs['pid']; ?>" />
        <input name="data_id" type="hidden" value='<?php echo $dataRs['id'] ; ?>' />
        <input name="topic_id" type="hidden" value="<?php echo $this->id ; ?>" />
        <input name="userid" type="hidden" value="<?php echo $rs['userid'] ; ?>" />
        <input name="postype" type="hidden" value="<?php echo $rs['postype'] ; ?>" />
        <div id="topic-add" class="tab-content">
          <div id="topic-add-base" class="tab-pane active">
            <div class="input-prepend">
              <span class="add-on">分类</span>
              <select name="cid" id="cid" class="chosen-select span3" data-placeholder="== 请选择所属分类 ==">
                <option></option>
                <?php echo $cata_option = category::appid(self::$appid,'ca')->select($rs['cid'],0,1,true);?>
              </select>
            </div>
            <div class="input-prepend input-append">
              <span class="add-on">状 态</span>
              <select name="status" id="status" class="chosen-select span3">
                <option value="0"> Черновик[status='0']</option>
                <option value="1"> Опубликован [status='1']</option>
                <option value="2"> Корзина [status='2']</option>
                <option value="3"> 待审核 [status='3']</option>
                <option value="4"> 未通过 [status='4']</option>
                <?php echo propAdmincp::get("status") ; ?>
              </select>
              <?php echo propAdmincp::btn_add('添加状态');?>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend">
              <span class="add-on">副分类</span>
              <select name="scid[]" id="scid" class="chosen-select span6" multiple="multiple"  data-placeholder="请选择副分类(可多选)...">
                <?php echo $cata_option;?>
              </select>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend">
              <span class="add-on">栏目</span>
              <select name="tcid" id="tcid" class="chosen-select span6" data-placeholder="请选择专题栏目...">
                <option value="0"> ==== 默认栏目 ==== </option>
                <?php echo category::appid(0,'ca')->select($rs['tcid'],0,1,true);?>
              </select>
            </div>
            <span class="help-inline">本专题所属的专题栏目</span>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend input-append">
              <span class="add-on">属 性</span>
              <select name="pid[]" id="pid" class="chosen-select span6" multiple="multiple">
                <option value="0">普通专题[pid='0']</option>
                <?php echo propAdmincp::get("pid") ; ?>
              </select>
              <?php echo propAdmincp::btn_add('添加常用属性');?>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend">
              <span class="add-on">标 题</span>
              <input type="text" name="title" class="span6" id="title" value="<?php echo $rs['title'] ; ?>"/>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend">
              <span class="add-on"> Краткое название </span>
              <input type="text" name="stitle" class="span6" id="stitle" value="<?php echo $rs['stitle'] ; ?>"/>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend input-append">
              <span class="add-on">出 处</span>
              <input type="text" name="source" class="span2" id="source" value="<?php echo $rs['source'] ; ?>"/>
              <?php echo propAdmincp::btn_group("source");?>
              <?php echo propAdmincp::btn_add('添加出处');?>
            </div>
            <div class="input-prepend input-append">
              <span class="add-on">作 者</span>
              <input type="text" name="author" class="span2" id="author" value="<?php echo $rs['author'] ; ?>"/>
              <?php echo propAdmincp::btn_group("author");?>
              <?php echo propAdmincp::btn_add('添加作者');?>
            </div>
            <div class="input-prepend">
              <span class="add-on">编 辑</span>
              <input type="text" name="editor" class="span2" id="editor" value="<?php echo $rs['editor'] ; ?>"/>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend input-append">
              <span class="add-on">缩略图</span>
              <input type="text" name="pic" class="span6" id="pic" value="<?php echo $rs['pic'] ; ?>"/>
              <?php filesAdmincp::set_opt($rs['pic'])->pic_btn("pic",$this->id);?>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend input-append">
              <span class="add-on">大图</span>
              <input type="text" name="bpic" class="span6" id="bpic" value="<?php echo $rs['bpic'] ; ?>"/>
              <?php filesAdmincp::set_opt($rs['bpic'])->pic_btn("bpic",$this->id);?>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend input-append">
              <span class="add-on">中图</span>
              <input type="text" name="mpic" class="span6" id="mpic" value="<?php echo $rs['mpic'] ; ?>"/>
              <?php filesAdmincp::set_opt($rs['mpic'])->pic_btn("mpic",$this->id);?>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend input-append">
              <span class="add-on">小图</span>
              <input type="text" name="spic" class="span6" id="spic" value="<?php echo $rs['spic'] ; ?>"/>
              <?php filesAdmincp::set_opt($rs['spic'])->pic_btn("spic",$this->id);?>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend">
              <span class="add-on">关键字</span>
              <input type="text" name="keywords" class="span6" id="keywords" value="<?php echo $rs['keywords'] ; ?>" onkeyup="javascript:this.value=this.value.replace(/,/ig,',');"/>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend">
              <span class="add-on">标 签</span>
              <input type="text" name="tags" class="span6" id="tags" value="<?php echo $rs['tags'] ; ?>" onkeyup="javascript:this.value=this.value.replace(/,/ig,',');"/>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend" style="width:100%;">
              <span class="add-on">摘 要</span>
              <textarea name="description" id="description" class="span6" style="height: 150px;"><?php echo $rs['description'] ; ?></textarea>
            </div>
          </div>
          <div id="topic-add-data" class="tab-pane hide">
            <div class="alert alert-info alert-block">
              <p>字段名:只能由英文字母、数字或_-组成,不支持中文</p>
              <p>应用数据双击可删除</p>
            </div>
            <button class="btn btn-inverse add_body_data"
            data-target="#topic-data-container"
            data-item="body_field"
             type="button"><i class="fa fa-plus-circle"></i> 增加字段</button>
            <div class="btn-group">
              <a class="btn btn-inverse dropdown-toggle" data-toggle="dropdown" tabindex="-1"> <span class="caret"></span> 添加应用数据</a>
              <ul class="dropdown-menu">
                <?php
                $manage = $this->app_manage();
                foreach ($manage as $key => $value) {
                ?>
                <li>
                  <a class="add_body_data"
                  href="javascript:;"
                  data-target="#topic-data-container"
                  data-item="body_app"
                  data-app_name="<?php echo $value['name'];?>"
                  data-app_id="<?php echo $value['id'];?>"
                  data-app="<?php echo $value['app'];?>">
                  <i class="fa fa-plus-circle"></i>
                  <?php echo $value['name'];?></a>
                </li>
                <?php } ?>
              </ul>
            </div>
            <div class="clearfloat mb10"></div>
            <div id="topic-data-container">
              <?php
              $data_index=0;
              if($dataRs['body'])foreach ((array)$dataRs['body'] as $key => $value) {?>
                <?php
                  if (isset($value['app'])){
                    $info = apps::get_app($value['app_id']);
                ?>
                  <div class="body_data">
                    <input class="app" name="body[<?php echo $data_index;?>][app]" type="hidden" value="<?php echo $value['app'];?>" />
                    <input class="app_id" name="body[<?php echo $data_index;?>][app_id]" type="hidden" value="<?php echo $value['app_id'];?>" />
                    <div class="input-prepend input-append">
                      <span class="add-on">Заголовок</span>
                      <input name="body[<?php echo $data_index;?>][name]" type="text" class="span2" value="<?php echo $value['name'];?>" />
                      <span class="add-on">字段名</span>
                      <input name="body[<?php echo $data_index;?>][key]" type="text" class="span2 tip" value="<?php echo $value['key'];?>" title="只能由英文字母、数字或_-组成,不支持中文"/>
                      <a class="btn"
                      href="<?php echo APP_URI; ?>&do=select&_app=<?php echo $value['app'];?>"
                      data-toggle="modal" data-target="#iCMS-MODAL"
                      data-meta='{"width":"85%","height":"640px"}'>
                      <i class="fa fa-plus"></i>
                      选择<span class="app_name"><?php echo $info['name'];?>数据</span>
                      </a>
                      <button class="btn btn-danger del_body_data" type="button"><i class="fa fa-trash-o"></i> Удалить</button>
                    </div>
                    <div class="clearfloat mb5"></div>
                    <div class="input-prepend input-append input-sp">
                      <span class="add-on app_name"><?php echo $info['name'];?>数据</span>
                      <select name="body[<?php echo $data_index;?>][data][]" class="span6" multiple="multiple">
                        <?php
                          foreach ((array)$value['data'] as $k => $v) {
                            list($_id,$_title) = explode('#@#', $v);
                        ?>
                            <option id='<?php echo $_id;?>' value='<?php echo $_id;?>#@#<?php echo $_title;?>'>[<?php echo $_id;?>]<?php echo $_title;?></option>
                        <?php } ?>
                      </select>
                      <div class="btn-group btn-group-vertical">
                        <button class="btn select_opt_up" type="button"><i class="fa fa-arrow-up"></i></button>
                        <button class="btn select_opt_down" type="button"><i class="fa fa-arrow-down"></i></button>
                        <button class="btn select_opt_remove" type="button"><i class="fa fa-close"></i></button>
                      </div>
                    </div>
                  </div>
                <?php }else{ ?>
                  <div class="body_data">
                    <div class="input-prepend input-append">
                      <span class="add-on">Заголовок</span>
                      <input name="body[<?php echo $data_index;?>][name]" type="text" class="span2" value="<?php echo $value['name'];?>"/>
                      <span class="add-on">字段名</span>
                      <input name="body[<?php echo $data_index;?>][key]" type="text" class="span2 tip" title="只能由英文字母、数字或_-组成,不支持中文" value="<?php echo $value['key'];?>"/>
                      <button class="btn btn-danger del_body_data" type="button"><i class="fa fa-trash-o"></i> Удалить</button>
                    </div>
                    <div class="clearfloat mb5"></div>
                    <div class="input-prepend">
                      <span class="add-on">数据</span>
                      <textarea name="body[<?php echo $data_index;?>][value]" class="span6" style="height: 60px;"><?php echo $value['value'];?></textarea>
                    </div>
                  </div>
                <?php } ?>
              <?php ++$data_index; } ?>
            </div>
            <div id="body-clone-wrap">
              <div class="hide body_data body_field_clone">
                <div class="input-prepend input-append">
                  <span class="add-on">Заголовок</span>
                  <input name="body[{key}][name]" type="text" disabled="disabled" class="span2" />
                  <span class="add-on">字段名</span>
                  <input name="body[{key}][key]" type="text" disabled="disabled" class="span2 tip" title="只能由英文字母、数字或_-组成,不支持中文"/>
                  <button class="btn btn-danger del_body_data" type="button"><i class="fa fa-trash-o"></i> Удалить</button>
                </div>
                <div class="clearfloat mb5"></div>
                <div class="input-prepend">
                  <span class="add-on">数据</span>
                  <textarea name="body[{key}][value]" class="span6" style="height: 60px;" disabled="disabled"></textarea>
                </div>
              </div>
              <div class="hide body_data body_app_clone">
                <input class="app" name="body[{key}][app]" disabled="disabled" type="hidden" value="" />
                <input class="app_id" name="body[{key}][app_id]" disabled="disabled" type="hidden" value="" />
                <div class="input-prepend input-append">
                  <span class="add-on">Заголовок</span>
                  <input name="body[{key}][name]" type="text" disabled="disabled" class="span2" />
                  <span class="add-on">字段名</span>
                  <input name="body[{key}][key]" type="text" disabled="disabled" class="span2 tip" title="只能由英文字母、数字或_-组成,不支持中文"/>
                  <a class="btn"
                  href="<?php echo APP_URI; ?>&do=select&_app={app}"
                  data-toggle="modal" data-target="#iCMS-MODAL"
                  data-meta='{"width":"85%","height":"640px"}'>
                  <i class="fa fa-plus"></i>
                  选择<span class="app_name">{app_name}数据</span>
                  </a>
                  <button class="btn btn-danger del_body_data" type="button"><i class="fa fa-trash-o"></i> Удалить</button>
                </div>
                <div class="clearfloat mb5"></div>
                <div class="input-prepend input-append input-sp">
                  <span class="add-on app_name">{app_name}数据</span>
                  <select name="body[{key}][data][]" class="span6" multiple="multiple" disabled="disabled">
                  </select>
                  <div class="btn-group btn-group-vertical">
                    <button class="btn select_opt_up" type="button"><i class="fa fa-arrow-up"></i></button>
                    <button class="btn select_opt_down" type="button"><i class="fa fa-arrow-down"></i></button>
                    <button class="btn select_opt_remove" type="button"><i class="fa fa-close"></i></button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div id="topic-add-publish" class="tab-pane hide">
            <div class="input-prepend">
              <span class="add-on">发布时间</span>
              <input id="pubdate" class="<?php echo $readonly?'':'ui-datepicker'; ?>" value="<?php echo $rs['pubdate']?$rs['pubdate']:get_date(0,'Y-m-d H:i:s') ; ?>"  name="pubdate" type="text" style="width:230px" <?php echo $readonly ; ?>/>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend">
              <span class="add-on">排序</span>
              <input id="sortnum" class="span2" value="<?php echo $rs['sortnum']?$rs['sortnum']:time() ; ?>" name="sortnum" type="text"/>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend">
              <span class="add-on">权重</span>
              <input id="weight" class="span2" value="<?php echo $rs['weight']?$rs['weight']:time(); ?>" name="weight" type="text"/>
            </div>
            <div class="clearfix mb10"></div>
            <div class="input-prepend input-append">
              <span class="add-on">总点击数</span>
              <input type="text" name="hits" class="span1" id="hits" value="<?php echo $rs['hits']?$rs['hits']:'0'; ?>"/>
              <span class="add-on">当天点击数</span>
              <input type="text" name="hits_today" class="span1" id="hits_today" value="<?php echo $rs['hits_today']?$rs['hits_today']:'0'; ?>"/>
              <span class="add-on">昨天点击数</span>
              <input type="text" name="hits_yday" class="span1" id="hits_yday" value="<?php echo $rs['hits_yday']?$rs['hits_yday']:'0'; ?>"/>
              <span class="add-on">周点击</span>
              <input type="text" name="hits_week" class="span1" id="hits_week" value="<?php echo $rs['hits_week']?$rs['hits_week']:'0'; ?>"/>
              <span class="add-on">月点击</span>
              <input type="text" name="hits_month" class="span1" id="hits_month" value="<?php echo $rs['hits_month']?$rs['hits_month']:'0'; ?>"/>
            </div>
            <div class="clearfix mb10"></div>
            <div class="input-prepend input-append">
              <span class="add-on">收藏数</span>
              <input type="text" name="favorite" class="span1" id="favorite" value="<?php echo $rs['favorite']?$rs['favorite']:'0'; ?>"/>
              <span class="add-on">评论数</span>
              <input type="text" name="comments" class="span1" id="comments" value="<?php echo $rs['comments']?$rs['comments']:'0'; ?>"/>
              <span class="add-on">点赞数</span>
              <input type="text" name="good" class="span1" id="good" value="<?php echo $rs['good']?$rs['good']:'0'; ?>"/>
              <span class="add-on">点踩数</span>
              <input type="text" name="bad" class="span1" id="bad" value="<?php echo $rs['bad']?$rs['bad']:'0'; ?>"/>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend input-append">
              <span class="add-on">模板</span>
              <input type="text" name="tpl" class="span6" id="tpl" value="<?php echo $rs['tpl'] ; ?>"/>
              <?php echo filesAdmincp::modal_btn('Шаблон','tpl');?>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend">
              <span class="add-on">自定链接</span>
              <input type="text" name="clink" class="span6" id="clink" value="<?php echo $rs['clink'] ; ?>"/>
              <span class="help-inline">只能由英文字母、数字或_-组成(不支持中文),留空则自动以标题拼音填充</span>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend">
              <span class="add-on">外部链接</span>
              <input type="text" name="url" class="span6 tip" title="注意:专题设置外部链接后编辑器里的内容是不会被保存的哦!" id="url" value="<?php echo $rs['url'] ; ?>"/>
            </div>
            <span class="help-inline">不填写请留空!</span>
            <div class="clearfloat mb10"></div>
          </div>
          <div id="former-layout" class="tab-pane hide">
            <?php echo former::layout();?>
          </div>
          <div id="apps-metadata" class="tab-pane hide">
            <?php include admincp::view("apps.meta","apps");?>
          </div>
        </div>
        <div class="form-actions">
          <button class="btn btn-primary btn-large" type="submit"><i class="fa fa-check"></i> 提交</button>
        </div>
      </form>
    </div>
  </div>
</div>
<div id="category_select" style="display: none;">
  <input class="form-control input-lg" id="user_category_new" type="text" placeholder="请输入分类名称">
</div>
<style>
#select_result{
  position: absolute;bottom: 0px;right: 0px;height: 450px;width: 360px;display: none;
  z-index: 999999;
  background-color: #fff;
  border: 1px solid #D4D4D4;
  padding: 10px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.4);
  border-radius: 4px;
  overflow-y: scroll;
  overflow-x: hidden;
}
.body_data{border-bottom: 1px solid #ddd;padding: 10px 0px;}
.body_data input{vertical-align: top;}
.body_data select{min-height: 90px;}
.input-sp .add-on{height: 80px !important;}
.input-sp .btn:last-child{
  margin-left: 0px !important;
  width: 100%;
}
.input-sp .btn-group .btn:first-child {
    border-radius: 0 4px 0 0 !important;
}
.input-sp .btn-group-vertical>.btn+.btn{
  border-radius: 0 !important;
}
.input-sp .btn-group .btn:last-child {
    border-radius: 0 0 4px 0 !important;
}
</style>
<div id="select_result"></div>
<script>
var doc = $(document);
doc.on("click", ".del_body_data", function() {
    $(this).parent().parent().remove();
});
doc.on("click", ".add_body_data", function() {
    var Pdiv = $(this).parent(),
        target=$($(this).data('target')),
        item=$(this).data('item'),
        count = $('.body_data', target).length;
    var ndiv = $("."+item+"_clone", '#body-clone-wrap').clone(true).removeClass("hide "+item+"_clone");
    // var html = ndiv[0].innerHTML.replace(/{key}/g, count);
    //  html = html.replace(/disabled="disabled"/g, '');
    $('[name]', ndiv).removeAttr("disabled").each(function() {
        this.name = this.name.replace("{key}", count);
    });
    if(item=='body_app'){
        var app=$(this).data('app'),
        app_id=$(this).data('app_id'),
        app_name=$(this).data('app_name'),
        select_id = 'body_'+count+'_data';
        $(".app",ndiv).val(app);
        $(".app_id",ndiv).val(app_id);
        $(".app_name",ndiv).text(app_name+'数据');
        // $("a.btn",ndiv).attr('href', '<?php echo __ADMINCP__; ?>='+app+'&do=manage');
        $("a.btn",ndiv).attr('href', '<?php echo APP_URI; ?>&do=select&_app='+app+'&select_id='+select_id);
        $("select",ndiv).attr('id', select_id);
    }
    ndiv.appendTo(target);
});
doc.on("dblclick", ".body_data select", function() {
   $("option:selected",this).remove();
});
doc.on("click", ".body_data .select_opt_up", function() {
  var P = $(this).parent().parent(),S = $("select",P);
  var os = $("option:selected",S);
  if(os.length==0){
    iCMS.alert("请选择至少一个数据项目");
    return;
  }
  if(os.get(0).index!=0){
   os.each(function(){
       $(this).prev().before($(this));
   });
  }
});
doc.on("click", ".body_data .select_opt_down", function() {
  var P = $(this).parent().parent(),S = $("select",P);
  var os = $("option:selected",S);
  var all = $("option",S);
  if(os.length==0){
    iCMS.alert("请选择至少一个数据项目");
    return;
  }
  if(os.get(os.length-1).index!=all.length-1){
   for(i=os.length-1;i>=0;i--){
     var item = $(os.get(i));
     item.insertAfter(item.next());
   }
  }
});
doc.on("click", ".body_data .select_opt_remove", function() {
  var P = $(this).parent().parent(),S = $("select",P);
  var os = $("option:selected",S);
  if(os.length==0){
    iCMS.alert("请选择至少一个数据项目");
    return;
  }
  os.next().attr('selected', 'selected');
  os.remove();
});

function select_result(id,title,cb,select_id){
    var result = $("#select_result");
    result.show();
    $('[data-dismiss="modal"][aria-hidden="true"]').on('click', function() {
        result.text("").hide();
    });
    var count =  $('div.clearfix', result).length;
    if(count==0){
      result.hide();
    }
    if(count>1000){
      return iCMS.alert("请使用关键字查询");
    }

    $("#sr_"+id).remove();
    $("#"+select_id+' option[id='+id+']').remove();

    if(title=='__remove__'){
      return;
    }

    $("#"+select_id).append("<option id='"+id+"' value='"+id+"#@#"+title+"'>["+id+"]"+title+"</option>");

    var div = $("<div>").addClass('input-prepend input-append clearfix').attr('id', "sr_"+id);
    var delbtn = $('<button class="btn btn-danger sr-del" type="button" data-id="'+id+'"><i class="fa fa-times"></i></button>');

    delbtn.click(function(event) {
      $(this).parent().remove();
      var id = $(this).data("id");
      $("#"+select_id+' option[id='+id+']').remove();

      if(typeof(cb)==="function"){
        cb(id);
      }
    });

    div.append(delbtn)
    div.append('<span class="add-on">'+id+'</span>');
    div.append('<span class="add-on">'+title+'</span>');
    result.append(div);
    result.show();
}

</script>
<?php admincp::foot();?>
