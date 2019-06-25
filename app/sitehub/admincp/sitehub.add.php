<?php

defined('iPHP') OR exit('What are you doing?');
admincp::head();
?>
<div class="iCMS-container">
  <div class="widget-box" id="<?php echo APP_BOXID;?>">
    <div class="widget-title"> <span class="icon"> <i class="fa fa-plus-square"></i> </span>
    <h5><?php echo empty($this->id)?'Добавить':'Редактировать' ; ?>站点</b></h5>
    <ul class="nav nav-tabs" id="-add-tab">
      <li class="active"><a href="#-add-base" data-toggle="tab"><i class="fa fa-info-circle"></i> Основная информация</a></li>
      <li><a href="#-add-custom" data-toggle="tab"><i class="fa fa-wrench"></i> 自定义</a></li>
    </ul>
  </div>
  <div class="widget-content nopadding">
    <form action="<?php echo APP_FURI; ?>&do=save" method="post" class="form-inline" id="<?php echo APP_FORMID;?>" target="iPHP_FRAME">
      <input name="id" type="hidden" value="<?php echo $this->id ; ?>" />
      <div class="tab-content">
        <div id="-add-base" class="tab-pane active">
          <div class="input-prepend">
            <span class="add-on">站点名称</span>
            <input type="text" name="name" class="span6" id="name" value="<?php echo $rs['name'];?>"/>
          </div>
          <span class="help-inline"></span>
          <div class="clearfloat mb10"></div>
          <div class="input-prepend">
            <span class="add-on">后台网址</span>
            <input type="text" name="url" class="span6" id="url" value="<?php echo $rs['url'];?>"/>
          </div>
          <span class="help-inline"></span>
          <div class="clearfloat mb10"></div>
          <div class="input-prepend">
            <span class="add-on">服务器IP</span>
            <input type="text" name="ip" class="span6" id="ip" value="<?php echo $rs['ip'];?>"/>
          </div>
          <span class="help-inline"></span>
          <div class="clearfloat mb10"></div>
          <div class="input-prepend">
            <span class="add-on">管理员账号</span>
            <input type="text" name="uname" class="span6" id="uname" value="<?php echo $rs['username'];?>"/>
          </div>
          <span class="help-inline"></span>
          <div class="clearfloat mb10"></div>
          <div class="input-prepend">
            <span class="add-on">管理员密码</span>
            <input type="text" name="pass" class="span6" id="pass" value="<?php echo $rs['password'];?>"/>
          </div>
          <span class="help-inline"></span>
          <div class="clearfloat mb10"></div>
          <div class="input-prepend">
            <span class="add-on">iPHP_KEY</span>
            <input type="text" name="ikey" class="span6" id="ikey" value="<?php echo $rs['ikey'];?>"/>
          </div>
          <div class="clearfloat mb10"></div>
        </div>
      </div>
      <div class="form-actions">
        <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i> 添加</button>
      </div>
    </form>
  </div>
</div>
<script type="text/javascript">
// $(function(){
//   iCMS.select('cid',"<?php echo $rs['cid'] ; ?>");
//   iCMS.select('pid',"<?php echo $rs['pid']?$rs['pid']:0 ; ?>");
// });

function deleteEditor() {
    iCMS.editor.destroy();
    $("#cleanupEditor-btn").hide();
    $("#createEditor-btn").show();
    $("#deleteEditor-btn").hide();
}
function createEditor() {
    iCMS.editor.create('editor-body');
    $("#cleanupEditor-btn").show();
    $("#createEditor-btn").hide();
    $("#deleteEditor-btn").show();
}
</script>
<?php admincp::foot();?>
