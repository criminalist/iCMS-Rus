<?php

defined('iPHP') OR exit('What are you doing?');
admincp::head();
?>
<script type="text/javascript">
var upordurl="<?php echo APP_URI; ?>&do=updateorder";
$(function(){
	<?php if(isset($_GET['pid']) && $_GET['pid']!='-1'){  ?>
	iCMS.select('pid',"<?php echo (int)$_GET['pid'] ; ?>");
	<?php } ?>
	<?php if($_GET['cid']){  ?>
	iCMS.select('cid',"<?php echo $_GET['cid'] ; ?>");
	<?php } ?>

  iCMS.select('status',"<?php echo isset($_GET['status'])?$_GET['status']:$this->_status ; ?>");
	<?php if($_GET['orderby']){ ?>
	iCMS.select('orderby',"<?php echo $_GET['orderby'] ; ?>");
	<?php } ?>
  <?php if($_GET['sub']=="on"){ ?>
  iCMS.checked('#sub');
  <?php } ?>
  <?php if($_GET['scid']=="on"){ ?>
    iCMS.checked('#search_scid');
  <?php } ?>
  <?php if(isset($_GET['pic'])){ ?>
  iCMS.checked('.spic','<?php echo $_GET['pic'] ; ?>');
  <?php } ?>

	var edialog;
	$(".edit").dblclick(function(){
		var a=$(this),vid=a.attr("vid"),box=$('#ed-box'),title=$.trim($('.aTitle',a).text());
		$('#edcid,#edpid').empty();
		var edcid	= $("#cid").clone().show().appendTo('#edcid'),
			edpid	= $("#pid").clone().show().appendTo('#edpid'),
			edtitle	= $('#edtitle',box).val(title),
			edtags	= $('#edtags',box),
			edsource= $('#edsource',box),
			eddesc	= $('#eddesc',box);

		$(".chosen-select",box).chosen(chosen_config);

		$.getJSON("<?php echo APP_URI; ?>",{'do':'getjson','id':vid},function(d){
			edcid.val(d.cid).trigger("chosen:updated");
      edpid.val(d.pid).trigger("chosen:updated");
			edtags.val(d.tags);
      edsource.val(d.source);
			eddesc.val(d.description);
		});

		iCMS.dialog({title: '简易编辑 ['+title+']',
        content:document.getElementById('ed-box'),
		    button: [{value: '保存',callback: function () {
						var title = edtitle.val(),cid=edcid.val();
						if(title==""){
							iCMS.alert("请填写标题!");
							edtitle.focus();
							return false;
						}
						if(cid==0){
							iCMS.alert("请选择栏目!");
							return false;
						}
						$(box).trigger("chosen:updated");
						$.post("<?php echo APP_URI; ?>&do=edit&CSRF_TOKEN=<?php echo iPHP_WAF_CSRF_TOKEN;?>",{
                id:vid,cid:cid,pid:edpid.val(),
                title:title,
                source:edsource.val(),
                tags:edtags.val(),
                description:eddesc.val()
              },
						  function(res){
  							if(res.code){
                  $('.aTitle',a).text(title);
                  iCMS.alert("修改完成!",true);
  							}
						},'json');
					}}]
		});
	});
	$("#<?php echo APP_FORMID;?>").batch({
    scid:function(){
      return $("#scidBatch").clone(true);
    }
  });
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
        <input type="hidden" name="do" value="<?php echo admincp::$APP_DO;?>" />
        <?php if(isset($_GET['pt'])){ ?>
        <input type="hidden" name="pt" value="<?php echo $_GET['pt'] ; ?>" />
        <?php } ?>

        <input type="hidden" name="userid" value="<?php echo $_GET['userid'] ; ?>" />
        <div class="input-prepend"> <span class="add-on">视频属性</span>
          <select name="pid" id="pid" class="span2 chosen-select">
            <option value="-1">所有视频</option>
            <option value="0">普通视频[pid='0']</option>
            <?php echo propAdmincp::get("pid") ; ?>
          </select>
        </div>
        <div class="input-prepend input-append"> <span class="add-on">栏目</span>
          <select name="cid" id="cid" class="chosen-select" style="width: 230px;">
            <option value="0">所有栏目</option>
            <?php echo $category_select = category::priv('cs')->select() ; ?>
          </select>
          <span class="add-on tip" title="选中查询所有关联到此栏目的视频">
          <input type="checkbox" name="scid" id="search_scid"/>
          副栏目 </span>
          <span class="add-on tip" title="选中查询此栏目下面所有的子栏目,包含本栏目">
          <input type="checkbox" name="sub" id="sub"/>
          子栏目 </span> </div>
        <div class="input-prepend">
          <span class="add-on">Сортировка</span>
          <select name="orderby" id="orderby" class="span2 chosen-select">
            <option value=""></option>
            <optgroup label="降序"><?php echo $orderby_option['DESC'];?></optgroup>
            <optgroup label="升序"><?php echo $orderby_option['ASC'];?></optgroup>
          </select>
        </div>
        <div class="input-prepend input-append"> <span class="add-on">无缩略图
          <input type="radio" name="pic" class="checkbox spic" value="0"/>
          </span> <span class="add-on">缩略图
          <input type="radio" name="pic" class="checkbox spic" value="1"/>
          </span> </div>
        <div class="clearfloat mb10"></div>
        <div class="input-prepend input-append"><span class="add-on"><i class="fa fa-calendar"></i> Время публикации</span>
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
        <div class="input-prepend"> <span class="add-on">Статус</span>
          <select name="status" id="status" class="chosen-select span3">
            <option value="0"> Черновик[status='0']</option>
            <option value="1"selected='selected'> Опубликован [status='1']</option>
            <option value="2"> Корзина [status='2']</option>
            <option value="3"> 待审核 [status='3']</option>
            <option value="4"> 未通过 [status='4']</option>
            <?php echo propAdmincp::get("status") ; ?>
          </select>
        </div>
        <div class="input-prepend input-append"> <span class="add-on">每页</span>
          <input type="text" name="perpage" id="perpage" value="<?php echo $maxperpage ; ?>" style="width:36px;"/>
          <span class="add-on">条记录</span> </div>
        <div class="input-prepend"> <span class="add-on">查找方式</span>
          <select name="st" id="st" class="chosen-select" style="width:120px;">
            <option value="title"> Название </option>
            <option value="tag">标签</option>
            <option value="source">出处</option>
            <option value="id">ID</option>
            <option value="weight">置顶权重</option>
            <option value="tkd">标题/关键字/简介</option>
            <option value="pic">缩略图</option>
          </select>
        </div>
        <div class="input-prepend input-append"> <span class="add-on">关键字</span>
          <input type="text" name="keywords" class="span3" id="keywords" value="<?php echo iSecurity::escapeStr($_GET['keywords']) ; ?>" />
          <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i> 搜 索</button>
        </div>
      </form>
    </div>
  </div>
  <div class="widget-box" id="<?php echo APP_BOXID;?>">
    <div class="widget-title"> <span class="icon">
      <input type="checkbox" class="checkAll" data-target="#<?php echo APP_BOXID;?>" title="点击全选/反选"/>
      </span>
      <h5><?php if($cid){echo ' <span class="label label-info">'.category::get($cid)->name.'</span>';}?>视频列表</h5>
      <span title="总共<?php echo $total;?>个视频" class="badge badge-info tip-left"><?php echo $total;?></span>
    </div>
    <div class="widget-content nopadding">
      <form action="<?php echo APP_FURI; ?>&do=batch" method="post" class="form-inline" id="<?php echo APP_FORMID;?>" target="iPHP_FRAME">
        <table class="table table-bordered table-condensed table-hover">
          <thead>
            <tr>
              <th><i class="fa fa-arrows-v"></i></th>
              <th class="span1">ID</th>
              <th> Название </th>
              <th class="span2">日期</th>
              <th style="width:80px;">栏目</th>
              <th style="width:60px;">Редактировать</th>
              <th class="span1">点/评</th>
              <th style="width:120px;">分集剧情</th>
              <th style="width:120px;">资源</th>
              <th style="width:120px;"> Операция </th>
            </tr>
          </thead>
          <tbody>
            <?php
                $categoryArray  = category::multi_get($rs,'cid');
                $scategoryArray = category::multi_get($rs,'scid');
                if($rs)foreach ($rs as $key => $value) {
                  $C    = (array)$categoryArray[$value['cid']];
                  $iurl = iURL::get('video',array($value,$C));
                  $value['url'] = $iurl->href;
            ?>
            <tr id="id<?php echo $value['id'] ; ?>">
              <td><input type="checkbox" name="id[]" value="<?php echo $value['id'] ; ?>" /></td>
              <td>
                <?php echo $value['id'] ; ?>
              </td>
              <td><div class="edit" vid="<?php echo $value['id'] ; ?>">
                  <?php if($value['status']=="3"){ ?>
                  <span class="label label-important">待审核</span>
                  <?php } ?>
                  <?php if($value['postype']=="0"){ ?>
                  <span class="label label-info">用户</span>
                  <?php } ?>
                  <?php if($value['haspic']) echo '<img src="./app/admincp/ui/img/image.gif" align="absmiddle">'?>
                  <a class="aTitle" href="<?php echo APP_URI; ?>&do=preview&id=<?php echo $value['id'] ; ?>" data-toggle="modal" title="预览">
                    <?php echo $value['title'] ; ?>
                  </a>
                 </div>
                <div class="row-actions">
                  <a href="<?php echo __ADMINCP__; ?>=files&indexid=<?php echo $value['id'] ; ?>&method=database" class="tip-bottom" title="查看视频图片库" target="_blank"><i class="fa fa-picture-o"></i></a>
                  <a href="<?php echo APP_URI; ?>&do=findpic&id=<?php echo $value['id'] ; ?>" class="tip-bottom" title="查找视频所有图片" target="_blank"><i class="fa fa-picture-o"></i></a>
                  <?php if($value['postype']=="0"){ ?>
                  <a href="<?php echo APP_URI; ?>&do=update&id=<?php echo $value['id'] ; ?>&_args=status:1" class="tip-bottom" target="iPHP_FRAME" title="通过审核"><i class="fa fa-check-circle"></i></a>
                    <?php if($value['status']!="3"){ ?>
                    <a href="<?php echo APP_URI; ?>&do=update&id=<?php echo $value['id'] ; ?>&_args=status:3" class="tip-bottom" target="iPHP_FRAME" title="等待审核"><i class="fa fa-minus-circle"></i></a>
                    <?php } ?>
                  <a href="<?php echo APP_URI; ?>&do=update&id=<?php echo $value['id'] ; ?>&_args=status:4" class="tip-bottom" target="iPHP_FRAME" title="拒绝通过"><i class="fa fa-times-circle"></i></a>
                  <?php } ?>
                  <?php if($value['status']!="2"){ ?>
                  <a href="<?php echo __ADMINCP__; ?>=comment&appname=video&appid=<?php echo self::$appid;?>&iid=<?php echo $value['id'] ; ?>" class="tip-bottom" title="<?php echo $value['comments'] ; ?>条评论" target="_blank"><i class="fa fa-comment"></i></a>
                  <?php } ?>
                  <!-- <a href="<?php echo __ADMINCP__; ?>=chapter&vid=<?php echo $value['id'] ; ?>" class="tip-bottom" title="章节管理" target="_blank"><i class="fa fa-sitemap"></i></a> -->
                  <?php if($value['status']=="1"){ ?>
                  <?php if(apps::check('push')){ ?>
                  <a href="<?php echo __ADMINCP__; ?>=push&do=add&title=<?php echo $value['title'] ; ?>&pic=<?php echo $value['pic'] ; ?>&url=<?php echo $value['url'] ; ?>" class="tip-bottom" title="推送此视频"><i class="fa fa-thumb-tack"></i></a>
                  <?php } ?>
                  <a href="<?php echo APP_URI; ?>&do=update&id=<?php echo $value['id'] ; ?>&_args=status:0" class="tip-bottom" target="iPHP_FRAME" title="转为草稿"><i class="fa fa-inbox"></i></a>
                  <a href="<?php echo APP_URI; ?>&do=update&id=<?php echo $value['id'] ; ?>&_args=pubdate:now" class="tip-bottom" target="iPHP_FRAME" title="更新视频时间"><i class="fa fa-clock-o"></i></a>
                  <?php } ?>
                  <?php if($value['status']=="0"){ ?>
                  <a href="<?php echo APP_FURI; ?>&do=update&id=<?php echo $value['id'] ; ?>&_args=status:1" class="tip-bottom" target="iPHP_FRAME" title="发布视频"><i class="fa fa-share"></i></a>
                  <a href="<?php echo APP_URI; ?>&do=update&id=<?php echo $value['id'] ; ?>&_args=status:1,pubdate:now" class="tip-bottom" target="iPHP_FRAME" title="更新视频时间,并发布"><i class="fa fa-clock-o"></i></a>
                  <?php } ?>
                  <?php if($value['status']=="2"){ ?>
                  <a href="<?php echo APP_FURI; ?>&do=update&id=<?php echo $value['id'] ; ?>&_args=status:1" target="iPHP_FRAME" class="tip-bottom" title="从回收站恢复"/><i class="fa fa-reply-all"></i></a>
                  <?php } ?>
                  <!-- <a href="<?php echo APP_URI; ?>&do=purge&id=<?php echo $value['id'] ; ?>&url=<?php echo $value['url'] ; ?>" class="tip-bottom" data-toggle="modal" title="清除nginx缓存"><i class="fa fa-recycle"></i></a> -->
                  <?php if ($C['mode'] && strstr($C['rule']['video'],'{PHP}')===false && $value['status']=="1" && empty($ourl) && members::$data->gid==1){  ?>
                  <a href="<?php echo __ADMINCP__; ?>=html&do=createvideo&video_id=<?php echo $value['id'] ; ?>&frame=iPHP" class="tip-bottom" target="iPHP_FRAME" title="生成静态文件"><i class="fa fa-file"></i></a>
                  <?php } ?>
                  <?php if($value['chapter']){?>
                  <i class="fa fa-bookmark"></i>(<?php echo $value['chapter'];?>)
                  <?php } ?>
                </div>
                <?php if($value['pic'] && self::$config['showpic']){ ?>
                <a href="<?php echo APP_URI; ?>&do=preview&id=<?php echo $value['id'] ; ?>" data-toggle="modal" title="预览"><img src="<?php echo iFS::fp($value['pic'],'+http'); ?>" style="height:120px;"/></a>
                <?php } ?>
              </td>
              <td><?php if($value['pubdate']) echo get_date($value['pubdate'],'Y-m-d H:i');?><br />
                <?php if($value['postime']) echo get_date($value['postime'],'Y-m-d H:i');?></td>
              <td>
                <a href="<?php echo APP_DOURI; ?>&cid=<?php echo $value['cid'] ; ?>&<?php echo $uri ; ?>"><?php echo $C['name'] ; ?></a><br />
                <?php
                 if($value['scid']){
                   $scid_array = explode(',', $value['scid']);
                   foreach ($scid_array as $scidk => $scid) {
                      $scva = $scategoryArray[$scid];
                      if($scid!=$value['cid']){
                        echo '<a href="'.APP_DOURI.'&cid='.$scid.'&'.$uri.'">'.$scva->name.'</a><br />';
                      }
                   }
                 }
                ?>
                <?php $value['pid'] && propAdmincp::flag($value['pid'],$propArray,APP_DOURI.'&pid={PID}&'.$uri);?>
              </td>
              <td><a href="<?php echo APP_DOURI; ?>&userid=<?php echo $value['userid'] ; ?>&<?php echo $uri ; ?>"><?php echo $value['editor'] ; ?></a><br /><?php echo $value['author'] ; ?></td>
              <td>
                <a class="tip" href="javascript:;" title="
                总点击:<?php echo $value['hits'] ; ?><br />
                今日点击:<?php echo $value['hits_today'] ; ?><br />
                昨日点击:<?php echo $value['hits_yday'] ; ?><br />
                周点击:<?php echo $value['hits_week'] ; ?><br />
                收藏:<?php echo $value['favorite'] ; ?><br />
                评论:<?php echo $value['comments'] ; ?><br />
                赞:<?php echo $value['good'] ; ?><br />
                ">
                  <?php echo $value['hits']; ?>/<?php echo $value['comments']; ?>
                </a>
              </td>
              <td>
                <a href="<?php echo __ADMINCP__; ?>=video_story&do=manage&video_id=<?php echo $value['id']; ?>&modal" data-toggle="modal" title="《<?php echo $value['title'] ; ?>》分集剧情管理" class="btn btn-inverse btn-mini"><i class="fa fa-sitemap"></i> 管理</a>
                <a href="<?php echo __ADMINCP__; ?>=video_story&do=add&video_id=<?php echo $value['id']; ?>" data-toggle="modal" title="新增《<?php echo $value['title'] ; ?>》分集剧情"  class="btn btn-info btn-mini"><i class="fa fa-edit"></i> Добавить видео</a>
              </td>
              <td>
                <a href="<?php echo __ADMINCP__; ?>=video_resource&do=manage&video_id=<?php echo $value['id']; ?>&modal" data-toggle="modal" title="<?php echo $value['title'] ; ?>" class="btn btn-inverse btn-mini"><i class="fa fa-sitemap"></i> 管理</a>
                <a href="<?php echo __ADMINCP__; ?>=video_resource&do=add&video_id=<?php echo $value['id']; ?>" data-toggle="modal" title="新增 <?php echo $value['title'] ; ?> 资源"  class="btn btn-info btn-mini"><i class="fa fa-edit"></i> Добавить видео</a>
              </td>
              <td><?php if($value['status']=="1"){ ?>
                <a href="<?php echo $value['url']; ?>" class="btn btn-success btn-mini" target="_blank"> Просмотр </a>
                <?php } ?>
                <!-- <a href="<?php echo APP_URI; ?>&do=add&id=<?php echo $value['id'] ; ?>" class="btn btn-primary btn-mini">+章节</a> -->
                <?php if(category::check_priv($value['cid'],'ce')){ ?>
                <a href="<?php echo APP_URI; ?>&do=add&id=<?php echo $value['id'] ; ?>" class="btn btn-primary btn-mini">Редактировать</a>
                <?php } ?>
                <?php if(in_array($value['status'],array("1","0")) && category::check_priv($value['cid'],'cd')){ ?>
                <a href="<?php echo APP_FURI; ?>&do=update&id=<?php echo $value['id'] ; ?>&_args=status:2" target="iPHP_FRAME" class="del btn btn-danger btn-mini" title="移动此视频到回收站" /> Удалить</a>
                <?php } ?>
                <?php if($value['status']=="2"){ ?>
                <a href="<?php echo APP_FURI; ?>&do=del&id=<?php echo $value['id'] ; ?>" target="iPHP_FRAME" class="del btn btn-danger btn-mini" onclick="return confirm('Вы уверены, что хотите удалить?');"/>Удалить навсегда</a>
                <?php } ?>
              </td>
            </tr>
            <?php }  ?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="10"><div class="pagination pagination-right" style="float:right;"><?php echo iPagination::$pagenav ; ?></div>
                <div class="input-prepend input-append mt20"> <span class="add-on">全选
                  <input type="checkbox" class="checkAll checkbox" data-target="#<?php echo APP_BOXID;?>" />
                  </span>
                  <div class="btn-group dropup" id="iCMS-batch"> <a class="btn dropdown-toggle" data-toggle="dropdown" tabindex="-1"><i class="fa fa-wrench"></i>Пакетные операции</a><a class="btn dropdown-toggle" data-toggle="dropdown" tabindex="-1"> <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                      <li><a data-toggle="batch" data-action="pubdate:now"><i class="fa fa-clock-o"></i> 更新Время публикации</a></li>
                      <?php if($stype=="inbox"||$stype=="trash"){ ?>
                      <li><a data-toggle="batch" data-action="status:1"><i class="fa fa-share"></i> 发布</a></li>
                      <li><a data-toggle="batch" data-action="status:1,pubdate:now"><i class="fa fa-clock-o"></i> 发布并更新时间</a></li>
                      <?php } ?>
                      <li><a data-toggle="batch" data-action="status:0"><i class="fa fa-inbox"></i> 转为草稿</a></li>
                      <li class="divider"></li>
                      <li><a data-toggle="batch" data-action="prop"><i class="fa fa-puzzle-piece"></i> 设置视频属性</a></li>
                      <li><a data-toggle="batch" data-action="move"><i class="fa fa-fighter-jet"></i> 移动栏目</a></li>
                      <li><a data-toggle="batch" data-action="scid"><i class="fa fa-code-fork"></i> 设置副栏目</a></li>
                      <li><a data-toggle="batch" data-action="thumb"><i class="fa fa-picture-o"></i> 提取缩略图</a></li>
                      <li><a data-toggle="batch" data-action="weight"><i class="fa fa-cog"></i> 设置置顶权重</a></li>
                      <li><a data-toggle="batch" data-action="keyword"><i class="fa fa-star"></i> 设置关键字</a></li>
                      <li><a data-toggle="batch" data-action="tag"><i class="fa fa-tags"></i> 设置标签</a></li>
                      <li><a data-toggle="batch" data-action="meta"><i class="fa fa-sitemap"></i> 设置动态属性</a></li>
                      <li class="divider"></li>
                      <?php if(iCMS::$config['api']['baidu']['sitemap']['site'] && iCMS::$config['api']['baidu']['sitemap']['access_token']){ ?>
                      <li><a data-toggle="batch" data-action="baiduping" title="百度站长平台主动推送"><i class="fa fa-send"></i> 百度主动推送</a></li>
                      <li class="divider"></li>
                      <?php } ?>
                      <li><a data-toggle="batch" data-action="status:2"><i class="fa fa-trash-o"></i> 移入回收站</a></li>
                      <li><a data-toggle="batch" data-action="dels"><i class="fa fa-trash-o"></i> Удалить навсегда</a></li>
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
<div id="ed-box" class="hide">
  <div class="input-prepend"> <span class="add-on">Категория</span> <span id="edcid"></span> </div>
  <div class="clearfloat mb10"></div>
  <div class="input-prepend"> <span class="add-on">属 性</span> <span id="edpid"></span> </div>
  <div class="clearfloat mb10"></div>
  <div class="input-prepend"> <span class="add-on">标 题</span>
    <input type="text" class="span6" id="edtitle"/>
  </div>
  <div class="clearfloat mb10"></div>
  <div class="input-prepend"> <span class="add-on">出 处</span>
    <input type="text" class="span6" id="edsource"/>
  </div>
  <div class="clearfloat mb10"></div>
  <div class="input-prepend"> <span class="add-on">标 签</span>
    <input type="text" class="span6" id="edtags"/>
  </div>
  <div class="clearfloat mb10"></div>
  <div class="input-prepend"><span class="add-on">摘 要</span>
    <textarea id="eddesc" class="span6" style="height: 120px;"></textarea>
  </div>
</div>
<div class='iCMS-batch'>
  <div id="scidBatch">
    <div class="input-prepend">
      <select name="scid[]" id="scid" class="span3" multiple="multiple"  data-placeholder="请选择副栏目(可多选)...">
        <?php echo $category_select;?>
      </select>
    </div>
    <div id="metaBatch">
      <?php include admincp::view("apps.meta","apps");?>
    </div>
  </div>
</div>
<?php admincp::foot();?>
