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
<style>
.modal{z-index: 999999 !important;}
</style>
<script type="text/javascript">
var upordurl="<?php echo APP_URI; ?>&do=updateorder";
$(function(){
  iCMS.select('status',"<?php echo isset($_GET['status'])?$_GET['status']:$this->_status ; ?>");
  // iCMS.select('postype',"<?php echo isset($_GET['postype'])?$_GET['postype']:$this->_postype ; ?>");

	<?php if(isset($_GET['pid']) && $_GET['pid']!='-1'){  ?>
	iCMS.select('pid',"<?php echo (int)$_GET['pid'] ; ?>");
	<?php } if($_GET['cid']){  ?>
	iCMS.select('cid',"<?php echo $_GET['cid'] ; ?>");
	<?php } if($_GET['orderby']){ ?>
	iCMS.select('orderby',"<?php echo $_GET['orderby'] ; ?>");
	<?php } if($_GET['sub']=="on"){ ?>
	iCMS.checked('#sub');
	<?php } ?>
	$("#<?php echo APP_FORMID;?>").batch({});
});
</script>

<div class="iCMS-container">
  <div class="widget-box">
    <div class="widget-title"> <span class="icon"> <i class="fa fa-search"></i> </span>
      <h5>搜索</h5>
      <div class="pull-right">
      </div>
    </div>
    <div class="widget-content">
      <form action="<?php echo iPHP_SELF ; ?>" method="get" class="form-inline">
        <input type="hidden" name="app" value="<?php echo admincp::$APP_NAME;?>" />
        <input type="hidden" name="uid" value="<?php echo $_GET['uid'] ; ?>" />
        <div class="input-prepend"> <span class="add-on">问题属性</span>
          <select name="pid" id="pid" class="span2 chosen-select">
            <option value="-1">所有问题</option>
            <?php echo $pid_select = propAdmincp::get("pid") ; ?>
          </select>
        </div>
        <div class="input-prepend input-append"> <span class="add-on">分类</span>
          <select name="cid" id="cid" class="span3 chosen-select">
            <option value="0">所有栏目</option>
            <?php echo $cid_select = category::priv('cs')->select() ; ?>
          </select>
          <span class="add-on">
          <input type="checkbox" name="sub" id="sub"/>
          子栏目 </span>
        </div>
        <div class="input-prepend input-append">
          <span class="add-on">无缩略图
          <input type="radio" name="pic" class="checkbox spic" value="0"/>
          </span>
          <span class="add-on">缩略图
          <input type="radio" name="pic" class="checkbox spic" value="1"/>
          </span>
        </div>
        <div class="clearfloat mb10"></div>
        <div class="input-prepend input-append"><span class="add-on"><i class="fa fa-calendar"></i> 发布时间</span>
          <input type="text" class="ui-datepicker" name="starttime" value="<?php echo $_GET['starttime'] ; ?>" placeholder="开始时间" />
          <span class="add-on">-</span>
          <input type="text" class="ui-datepicker" name="endtime" value="<?php echo $_GET['endtime'] ; ?>" placeholder="结束时间" />
          <span class="add-on"><i class="fa fa-calendar"></i></span>
        </div>
        <div class="input-prepend input-append"><span class="add-on"><i class="fa fa-calendar"></i> 添加时间</span>
          <input type="text" class="ui-datepicker" name="post_starttime" value="<?php echo $_GET['post_starttime'] ; ?>" placeholder="开始时间" />
          <span class="add-on">-</span>
          <input type="text" class="ui-datepicker" name="post_endtime" value="<?php echo $_GET['post_endtime'] ; ?>" placeholder="结束时间" />
          <span class="add-on"><i class="fa fa-calendar"></i></span>
        </div>
        <div class="clearfloat mb10"></div>
<!--         <div class="input-prepend">
          <span class="add-on">发布类型</span>
          <select name="postype" id="postype" class="chosen-select span3">
            <option value=""></option>
            <option value="all">所有类型</option>
            <option value="0"> 用户 [postype='0']</option>
            <option value="1"selected='selected'> 管理 [postype='1']</option>
            <?php echo propAdmincp::get("postype") ; ?>
          </select>
        </div> -->
        <div class="input-prepend">
          <span class="add-on">状 态</span>
          <select name="status" id="status" class="chosen-select span3">
            <option value=""></option>
            <option value="all">所有状态</option>
            <option value="0"> 草稿 [status='0']</option>
            <option value="1"selected='selected'> 正常 [status='1']</option>
            <option value="2"> 回收站 [status='2']</option>
            <option value="3"> 待审核 [status='3']</option>
            <option value="4"> 未通过 [status='4']</option>
            <?php echo propAdmincp::get("status") ; ?>
          </select>
        </div>
        <div class="input-prepend"> <span class="add-on">排序</span>
          <select name="orderby" id="orderby" class="span3 chosen-select">
            <option value=""></option>
            <optgroup label="降序"><?php echo $orderby_option['DESC'];?></optgroup>
            <optgroup label="升序"><?php echo $orderby_option['ASC'];?></optgroup>
          </select>
        </div>
        <div class="input-prepend input-append"> <span class="add-on">每页</span>
          <input type="text" name="perpage" id="perpage" value="<?php echo $maxperpage ; ?>" style="width:36px;"/>
          <span class="add-on">条记录</span>
        </div>

        <div class="input-prepend input-append"> <span class="add-on">关键字</span>
          <input type="text" name="keywords" class="span2" id="keywords" value="<?php echo iSecurity::escapeStr($_GET['keywords']) ; ?>" />
          <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i> 搜 索</button>
        </div>
      </form>
    </div>
  </div>
  <div class="widget-box" id="<?php echo APP_BOXID;?>">
    <div class="widget-title"> <span class="icon">
      <input type="checkbox" class="checkAll" data-target="#<?php echo APP_BOXID;?>" />
      </span>
      <h5>问题列表</h5>
    </div>
    <div class="widget-content nopadding">
    <form action="<?php echo APP_FURI; ?>&do=batch" method="post" class="form-inline" id="<?php echo APP_FORMID;?>" target="iPHP_FRAME">
      <table class="table table-bordered table-condensed table-hover">
        <thead>
          <tr>
            <th><i class="fa fa-arrows-v"></i></th>
            <th>ID</th>
            <th>排序</th>
            <th>问题</th>
            <th>栏目</th>
            <th style="width:48px;">回答</th>
            <th class="span2">发布/更新/回复时间</th>
            <th>操作</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $categoryArray  = category::multi_get($rs,'cid');
          for($i=0;$i<$_count;$i++){
              $C             = (array)$categoryArray[$rs[$i]['cid']];
              $iurl          = iURL::get('ask',array($rs[$i],$C));
              $rs[$i]['url'] = $iurl->url;
              $user   = user::info($rs[$i]['userid'],$rs[$i]['username']);
              // $poster = user::info($rs[$i]['lastpostuid'],$rs[$i]['lastposter']);
    	   ?>
          <tr id="id<?php echo $rs[$i]['id'] ; ?>">
            <td><input type="checkbox" name="id[]" value="<?php echo $rs[$i]['id'] ; ?>" /></td>
            <td><?php echo $rs[$i]['id'] ; ?></td>
            <td class="sortnum"><input type="text" name="sortnum[<?php echo $rs[$i]['id'] ; ?>]" value="<?php echo $rs[$i]['sortnum'] ; ?>" tid="<?php echo $rs[$i]['id'] ; ?>"/></td>
            <td>
              <div class="user-thumb mr10">
                <a href="<?php echo $user['url'] ; ?>" target="_blank" class="avatar">
                  <img width="50" height="50" alt="<?php echo $user['name'] ; ?>" src="<?php echo $user['avatar'] ; ?>">
                </a>
                <div class="claerfix mb10"></div>
                <a href="<?php echo __ADMINCP__; ?>=user&do=update&id=<?php echo $rs[$i]['userid'] ; ?>&_args=status:2" class="btn btn-inverse btn-mini tip-bottom" title="加入黑名单,禁止用户登陆" target="iPHP_FRAME">黑名单</a>
              </div>
              <div class="content">
                <?php if($rs[$i]['haspic'])echo '<img src="./app/admincp/ui/img/image.gif" align="absmiddle">';?>

                <a href="<?php echo $rs[$i]['url'] ; ?>" class="noneline aTitle" target="_blank"><?php echo $rs[$i]['title'] ; ?></a>
                <div class="claerfix"></div>
                <a href="<?php echo APP_URI; ?>&ip=<?php echo $rs[$i]['ip'] ; ?>" class="tip" title="查看该IP所有问题">
                  <span class="label label-inverse">发贴IP：<?php echo $rs[$i]['ip'] ; ?></span>
                </a>
                <div class="claerfix mt5"></div>
                最后回复:
                <a href="<?php echo admincp::uri("userid=".$rs[$i]['lastpostuid'],$uriArray); ?>">
                  <span class="label label-info">
                    <?php echo $rs[$i]['lastposter'] ; ?>
                  </span>
                </a>
                <span class="label label-important"><?php echo $rs[$i]['tags'] ; ?></span>
                <div class="claerfix mt5"></div>
                <a href="<?php echo APP_URI; ?>&do=answer&iid=<?php echo $rs[$i]['id'] ; ?>" class="btn btn-mini"><i class="fa fa-edit"></i> 管理回答</a>
              </div>
            </td>
            <td>
              <a href="<?php echo APP_DOURI; ?>&cid=<?php echo $rs[$i]['cid'] ; ?><?php echo $uri ; ?>"><?php echo $C['name'] ; ?></a>
              <a href="<?php echo __ADMINCP__; ?>=ask_category&do=add&cid=<?php echo $rs[$i]['cid']; ?>" target="_blank"><i class="fa fa-edit"></i></a>
              <?php $rs[$i]['pid'] && propAdmincp::flag($rs[$i]['pid'],$propArray,APP_DOURI.'&pid={PID}&'.$uri);?>
            </td>
            <td><?php echo $rs[$i]['replies']; ?></td>
            <td>
              <?php echo get_date($rs[$i]['pubdate'],'Y-m-d H:i');?><br />
              <?php echo get_date($rs[$i]['postime'],'Y-m-d H:i');?><br />
              <?php echo get_date($rs[$i]['lastpost'],'Y-m-d H:i');?>
            </td>
            <td>
              <?php if($rs[$i]['status']=="1"){ ?>
              <a href="<?php echo APP_FURI; ?>&do=update&id=<?php echo $rs[$i]['id'] ; ?>&_args=status:0" class="btn btn-small tip" target="iPHP_FRAME" title="当前状态:打开,点击可关闭此问题"><i class="fa fa-power-off"></i></a>
              <?php } ?>
              <?php if($rs[$i]['status']=="0"){ ?>
              <a href="<?php echo APP_FURI; ?>&do=update&id=<?php echo $rs[$i]['id'] ; ?>&_args=status:1" class="btn btn-small tip" target="iPHP_FRAME" title="当前状态:关闭,点击可打开此问题"><i class="fa fa-play-circle"></i></a>
              <?php } ?>
              <a href="<?php echo APP_URI; ?>&do=add&id=<?php echo $rs[$i]['id'] ; ?>" class="btn btn-small"><i class="fa fa-edit"></i> 编辑</a>
              <a href="<?php echo APP_FURI; ?>&do=del&id=<?php echo $rs[$i]['id'] ; ?>" target="iPHP_FRAME" class="del btn btn-small" title='永久删除'  onclick="return confirm('确定要删除?');"/><i class="fa fa-trash-o"></i> 永久删除</a></td>
            </tr>
        <?php }  ?>
          </tbody>
        <tfoot>
          <tr>
            <td colspan="11"><div class="pagination pagination-right" style="float:right;"><?php echo iPagination::$pagenav ; ?></div>
              <div class="input-prepend input-append mt20"> <span class="add-on">全选
                <input type="checkbox" class="checkAll checkbox" data-target="#<?php echo APP_BOXID;?>" />
                </span>
                <div class="btn-group dropup" id="iCMS-batch"> <a class="btn dropdown-toggle" data-toggle="dropdown" tabindex="-1"><i class="fa fa-wrench"></i> 批 量 操 作 </a><a class="btn dropdown-toggle" data-toggle="dropdown" tabindex="-1"> <span class="caret"></span></a>
                  <ul class="dropdown-menu">
                    <li><a data-toggle="batch" data-action="status:1"><i class="fa fa-play-circle"></i> 启用</a></li>
                    <li><a data-toggle="batch" data-action="status:0"><i class="fa fa-power-off"></i> 禁用</a></li>
                    <li class="divider"></li>
                    <li><a data-toggle="batch" data-action="move"><i class="fa fa-fighter-jet"></i> 移动栏目</a></li>
                    <li><a data-toggle="batch" data-action="prop"><i class="fa fa-puzzle-piece"></i> 设置属性</a></li>
                    <li><a data-toggle="batch" data-action="weight"><i class="fa fa-cog"></i> 设置权重</a></li>
                    <li><a data-toggle="batch" data-action="keyword"><i class="fa fa-star"></i> 设置关键字</a></li>
                    <li><a data-toggle="batch" data-action="tag"><i class="fa fa-tags"></i> 设置标签</a></li>
                    <li><a data-toggle="batch" data-action="tpl"><i class="fa fa-tags"></i> 设置模板</a></li>
                    <li class="divider"></li>
                    <li><a data-toggle="batch" data-action="status:2"><i class="fa fa-trash-o"></i> 移入回收站</a></li>
                    <li><a data-toggle="batch" data-action="dels"><i class="fa fa-trash-o"></i> 删除</a></li>
                  </ul>
                </div>
              </div></td>
          </tr>
        </tfoot>
      </table>
    </form>
  </div>
</div>
</div>
<div class="iCMS-batch">
  <div id="tplBatch">
    <div class="input-prepend input-append"> <span class="add-on">问题模板</span>
      <input type="text" name="mtpl" class="span2" id="mtpl" value=""/>
      <?php echo filesAdmincp::modal_btn('模板','mtpl');?>
    </div>
  </div>
</div>

<?php admincp::foot();?>
