<?php

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
  </div>
  <div class="widget-content">
    <form action="<?php echo iPHP_SELF ; ?>" method="get" class="form-inline">
      <input type="hidden" name="app" value="<?php echo admincp::$APP_NAME;?>" />
      <div class="input-prepend input-append">
        <span class="add-on">每页</span>
        <input type="text" name="perpage" id="perpage" value="<?php echo $maxperpage ; ?>" style="width:36px;"/>
        <span class="add-on">条记录</span>
      </div>
      <div class="input-prepend input-append">
        <span class="add-on">关键字</span>
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
    <h5> <a href="<?php echo $que['url'];?>" target="_blank"><?php echo $que['title']; ?></a> 回复列表</h5>
    <span title="总共<?php echo $total;?>条回复" class="badge badge-info tip-left"><?php echo $total;?></span>
  </div>
  <div class="widget-content nopadding">
    <form action="<?php echo APP_FURI; ?>&do=batch" method="post" class="form-inline" id="<?php echo APP_FORMID;?>" target="iPHP_FRAME">
      <ul class="recent-comments">
        <?php if($rs){
        foreach ($rs as $key => $value) {
        $user = user::info($value['userid'],$value['username']);
        ?>
        <li id="id-<?php echo $value['id'] ; ?>">
          <div class="user-thumb">
            <a href="<?php echo $user['url'] ; ?>" target="_blank" class="avatar">
              <img width="50" height="50" alt="<?php echo $user['name'] ; ?>" src="<?php echo $user['avatar'] ; ?>">
            </a>
            <div class="claerfix mb10"></div>
            <a href="<?php echo __ADMINCP__; ?>=user&do=update&id=<?php echo $value['userid'] ; ?>&_args=status:2" class="btn btn-inverse btn-mini tip-bottom" title="加入黑名单,禁止用户登陆" target="iPHP_FRAME">黑名单</a>
          </div>
          <div class="comments">
            <input type="checkbox" name="id[]" value="<?php echo $value['id'] ; ?>" />
            <span class="user-info">
              <a href="<?php echo APP_URI; ?>&userid=<?php echo $value['userid'] ; ?>" class="tip" title="查看该用户所有回复"><span class="label label-info"><?php echo $user['name'] ; ?></span></a>
              发表<?php if(!$value['rootid']){?>问题<?php }else{ ?>回复<?php } ?>
              <a href="<?php echo APP_URI; ?>&ip=<?php echo $value['ip'] ; ?>" class="tip" title="查看该IP所有回复"><span class="label label-inverse">IP:<?php echo $value['ip'] ; ?></span></a>
              <?php if(!$value['status']){?>
              <span class="label label-warning">未审核</span>
              <?php } ?>
            </span>
            <p>
            <?php echo nl2br($value['content']); ?>
            </p>
            <div class="claerfix"></div>
            <span class="label">ID:<?php echo $value['id'];?></span>
            <span class="label label-info tip" title="更新时间"><?php echo get_date($value['pubdate'],'Y-m-d H:i:s');?></span>
            <span class="label label-info tip" title="Время публикации"><?php echo get_date($value['postime'],'Y-m-d H:i:s');?></span>
            <span class="label label-info"><i class="fa fa-thumbs-o-up"></i> <?php echo $value['good'] ; ?></span>
            <span class="label label-info"><i class="fa fa-thumbs-o-down"></i> <?php echo $value['bad'] ; ?></span>
            <?php if(!$value['status']){?>
            <a href="<?php echo APP_FURI; ?>&do=update&id=<?php echo $value['id'] ; ?>&_args=status:1" class="btn btn-success btn-mini" target="iPHP_FRAME">通过审核</a>
            <?php } ?>
            <?php if($value['rootid']){?>
            <a href="<?php echo APP_FURI; ?>&do=edit&id=<?php echo $value['id'] ; ?>" class="btn btn-primary btn-mini">Редактировать</a>
            <?php }else{ ?>
            <a href="<?php echo APP_FURI; ?>&do=add&id=<?php echo $value['iid'] ; ?>" class="btn btn-primary btn-mini">Редактировать</a>
            <?php } ?>
            <a href="<?php echo APP_FURI; ?>&do=delete&id=<?php echo $value['id'] ; ?>&rootid=<?php echo $value['rootid'] ; ?>&iid=<?php echo $que['id'] ; ?>" class="btn btn-danger btn-mini" target="iPHP_FRAME" onclick="return confirm('Вы уверены, что хотите удалить?');"> Удалить</a>
          </div>
          <div class="claerfix mb10"></div>
        </li>
        <?php }} ?>
      </ul>
    </form>
    <table class="table table-bordered table-condensed table-hover">
      <tr>
        <td><div class="pagination pagination-right" style="float:right;"><?php echo iPagination::$pagenav ; ?></div>
        <div class="input-prepend input-append mt20">
          <span class="add-on">全选
          <input type="checkbox" class="checkAll checkbox" data-target="#<?php echo APP_BOXID;?>" />
          </span>
          <div class="btn-group dropup" id="iCMS-batch"> <a class="btn dropdown-toggle" data-toggle="dropdown" tabindex="-1"><i class="fa fa-wrench"></i>Пакетные операции</a><a class="btn dropdown-toggle" data-toggle="dropdown" tabindex="-1"> <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a data-toggle="batch" data-action="dels"><i class="fa fa-trash-o"></i> Удалить</a></li>
          </ul>
        </div>
      </div></td>
    </tr>
  </table>
</div>
</div>
</div>
<?php admincp::foot();?>
