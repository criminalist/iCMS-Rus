<?php
/**
* iCMS - i Content Management System
* Copyright (c) 2007-2017 iCMSdev.com. All rights reserved.
*
* @author icmsdev <master@icmsdev.com>
* @site https://www.icmsdev.com
* @licence https://www.icmsdev.com/LICENSE.html
*/
defined('iPHP') OR exit('What are you doing?');
admincp::head();
?>
<script type="text/javascript">
$(function(){
  iCMS.select('status',"<?php echo $rs['status'] ; ?>");
});
</script>
<div class="iCMS-container">
  <div class="widget-box">
    <div class="widget-title"> <span class="icon"> <i class="fa fa-pencil"></i> </span>
    <h5 class="brs"><?php echo ($id?'添加':'修改'); ?>回复</h5>
    <ul class="nav nav-tabs" id="tag-add-tab">
      <li class="active"><a href="#tag-add-base" data-toggle="tab"><i class="fa fa-info-circle"></i> 基本信息</a></li>
    </ul>
  </div>
  <div class="widget-content nopadding">
    <form action="<?php echo APP_FURI; ?>&do=answer_save" method="post" class="form-inline" id="<?php echo APP_FORMID;?>" target="iPHP_FRAME">
      <input name="id" type="hidden" value="<?php echo $this->id ; ?>" />
      <input name="iid" type="hidden" value="<?php echo $rs['iid'] ; ?>" />
      <input name="rootid" type="hidden" value="<?php echo $rs['rootid'] ; ?>" />
      <div id="tags-add" class="tab-content">
        <div id="tag-add-base" class="tab-pane active">
          <div class="input-prepend">
            <span class="add-on">标题</span>
            <input type="text" name="title" class="span6" id="title" value="<?php echo $rs['title'] ; ?>"/>
          </div>
          <div class="input-prepend input-append"> <span class="add-on">状 态</span>
            <select name="status" id="status" class="chosen-select span3">
              <option value="0"> 草稿 [status='0']</option>
              <option value="1"> 正常 [status='1']</option>
              <option value="2"> 回收站 [status='2']</option>
              <option value="3"> 待审核 [status='3']</option>
              <option value="4"> 未通过 [status='4']</option>
              <?php echo propAdmincp::get("status") ; ?>
            </select>
            <?php echo propAdmincp::btn_add('添加状态');?>
          </div>
          <div class="clearfloat mb10"></div>
          <div class="clearfloat mb10"></div>
          <div class="clearfloat mb10"></div>
          <div class="input-prepend">
            <span class="add-on">提交者UID</span>
            <input type="text" name="uid" class="span3" id="uid" value="<?php echo $rs['userid'] ; ?>"/>
          </div>
          <div class="input-prepend">
            <span class="add-on">提交者</span>
            <input type="text" name="uname" class="span3" id="uname" value="<?php echo $rs['username'] ; ?>"/>
          </div>
          <div class="input-prepend">
            <span class="add-on">提交者IP</span>
            <input type="text" name="ip" class="span3" id="ip" value="<?php echo $rs['ip'] ; ?>"/>
          </div>
          <div class="clearfloat mb10"></div>
          <div class="input-prepend">
            <span class="add-on">问题</span>
            <textarea name="content" class="span12" style="height: 500px;"><?php echo $rs['content'] ; ?></textarea>
          </div>
          <div class="clearfix mb10"></div>
          <div class="input-prepend input-append">
            <span class="add-on">点赞数</span>
            <input type="text" name="good" class="span1" id="good" value="<?php echo $rs['good']?$rs['good']:'0'; ?>"/>
            <span class="add-on">点踩数</span>
            <input type="text" name="bad" class="span1" id="bad" value="<?php echo $rs['bad']?$rs['bad']:'0'; ?>"/>
          </div>
          <div class="clearfloat mb10"></div>
          <div class="input-prepend">
            <span class="add-on">更新时间</span>
            <input id="pubdate" class="ui-datepicker" value="<?php echo get_date($rs['pubdate'],'Y-m-d H:i:s') ; ?>"  name="pubdate" type="text" style="width:230px"/>
          </div>
          <div class="input-prepend">
            <span class="add-on">发布时间</span>
            <input id="postime" class="ui-datepicker" value="<?php echo get_date($rs['postime'],'Y-m-d H:i:s') ; ?>"  name="postime" type="text" style="width:230px"/>
          </div>
        </div>
      </div>
      <div class="form-actions">
        <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i> 提交</button>
      </div>
    </form>
  </div>
</div>
<?php admincp::foot();?>
