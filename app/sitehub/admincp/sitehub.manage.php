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
	$("#<?php echo APP_FORMID;?>").batch();
});
</script>

<div class="iCMS-container">
  <div class="widget-box">
    <div class="widget-title"> <span class="icon"> <i class="fa fa-search"></i> </span>
      <h5>搜索</h5>
      <div class="pull-right">
        <a style="margin: 10px;" class="btn btn-success btn-mini" href="<?php echo APP_FURI; ?>&do=cache" target="iPHP_FRAME"><i class="fa fa-refresh"></i> 更新统计</a>
      </div>
    </div>
    <div class="widget-content">
      <form action="<?php echo iPHP_SELF ; ?>" method="get" class="form-inline">
        <input type="hidden" name="app" value="<?php echo admincp::$APP_NAME;?>" />
        <div class="input-prepend input-append"> <span class="add-on">每页</span>
          <input type="text" name="perpage" id="perpage" value="<?php echo $maxperpage ; ?>" style="width:36px;"/>
          <span class="add-on">条记录</span> </div>
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
      <h5>站点管理</h5>
    </div>
    <div class="widget-content nopadding">
      <form action="<?php echo APP_FURI; ?>&do=batch" method="post" class="form-inline" id="<?php echo APP_FORMID;?>" target="iPHP_FRAME">
        <table class="table table-bordered table-condensed table-hover">
          <thead>
            <tr>
              <th><i class="fa fa-arrows-v"></i></th>
              <th style="width:20px;">ID</th>
              <th>站点名称</th>
              <th>IP</th>
              <th>管理网址</th>
              <th>数据统计</th>
              <th>操作</th>
            </tr>
          </thead>
          <tbody>
            <?php
            for($i=0;$i<$_count;$i++){
              $iframeId = "sitehub_".$rs[$i]['id'];
              $data = (array)json_decode($rs[$i]['data'],true);
              $counts = $data['count'];
            ?>
            <tr id="id<?php echo $rs[$i]['id'] ; ?>">
              <td><input type="checkbox" name="id[]" value="<?php echo $rs[$i]['id'] ; ?>" /></td>
              <td><?php echo $rs[$i]['id'] ; ?></td>
              <td><?php echo $rs[$i]['name'] ; ?></td>
              <td><?php echo $rs[$i]['ip'] ; ?></td>
              <td><?php $urls = parse_url($rs[$i]['url']);
              echo $urls['host']; ?></td>
              <td><?php
                if($counts){
                  echo "分类:".($counts['acc']+$counts['tcc']).' '.
                  "文章:".($counts['ac']);
                }
                echo ' 版本:'.substr($data['GIT_COMMIT'], 0,7).' 时间:'.date("Y-m-d",$data['GIT_TIME']);
              ?></td>
              <td>
                <iframe class="hide" id="<?php echo $iframeId ; ?>" name="<?php echo $iframeId ; ?>"></iframe>
                <a href="<?php echo APP_URI; ?>&do=admincp&id=<?php echo $rs[$i]['id'] ; ?>" class="btn btn-small btn-primary" data-toggle="modal" data-target="#iCMS-MODAL" data-meta="{&quot;width&quot;:&quot;85%&quot;,&quot;height&quot;:&quot;760px&quot;}" title="管理<?php echo $rs[$i]['name'] ; ?>"><i class="fa fa-home"></i> 管理</a>
                <a href="<?php echo APP_URI; ?>&do=upgrade&id=<?php echo $rs[$i]['id'] ; ?>" class="btn btn-small btn-success" target="iPHP_FRAME"><i class="fa fa-level-up"></i> 升级程序</a>
                <a href="<?php echo APP_URI; ?>&do=clean&id=<?php echo $rs[$i]['id'] ; ?>" class="btn btn-small btn-info" target="iPHP_FRAME"><i class="fa fa-refresh"></i> 更新缓存</a>
                <a href="<?php echo APP_URI; ?>&do=add&id=<?php echo $rs[$i]['id'] ; ?>" class="btn btn-small"><i class="fa fa-edit"></i> 编辑</a>
                <a href="<?php echo APP_FURI; ?>&do=del&id=<?php echo $rs[$i]['id'] ; ?>" target="iPHP_FRAME" class="del btn btn-small" title='永久删除'  onclick="return confirm('确定要删除?');"/><i class="fa fa-trash-o"></i> 删除</a></td>
            </tr>
            <?php }  ?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="9"><div class="pagination pagination-right" style="float:right;"><?php echo iPagination::$pagenav ; ?></div>
                <div class="input-prepend input-append mt20"> <span class="add-on">全选
                  <input type="checkbox" class="checkAll checkbox" data-target="#<?php echo APP_BOXID;?>" />
                  </span>
                  <div class="btn-group dropup" id="iCMS-batch"> <a class="btn dropdown-toggle" data-toggle="dropdown" tabindex="-1"><i class="fa fa-wrench"></i> 批 量 操 作 </a><a class="btn dropdown-toggle" data-toggle="dropdown" tabindex="-1"> <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                      <li><a data-toggle="batch" data-action="refresh"><i class="fa fa-refresh"></i> 更新缓存</a></li>
              		  <li class="divider"></li>
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
<?php admincp::foot();?>
