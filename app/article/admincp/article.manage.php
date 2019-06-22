<?php

defined('iPHP') OR exit('Oops, something went wrong');
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
  iCMS.select('postype',"<?php echo isset($_GET['postype'])?$_GET['postype']:$this->_postype ; ?>");

	<?php if($_GET['orderby']){ ?>
	iCMS.select('orderby',"<?php echo $_GET['orderby'] ; ?>");
	<?php } ?>
  <?php if($_GET['st']){ ?>
  iCMS.select('st',"<?php echo $_GET['st'] ; ?>");
  <?php } ?>
  <?php if($_GET['sub']=="on"){ ?>
  iCMS.checked('#sub');
  <?php } ?>
  <?php if($_GET['hidden']=="on"){ ?>
  iCMS.checked('#hidden');
  <?php } ?>
  <?php if($_GET['scid']=="on"){ ?>
    iCMS.checked('#search_scid');
  <?php } ?>
  <?php if(isset($_GET['pic'])){ ?>
  iCMS.checked('.spic','<?php echo $_GET['pic'] ; ?>');
  <?php } ?>

	var edialog;
	$(".edit").dblclick(function(){
		var a=$(this),aid=a.attr("aid"),box=$('#ed-box'),title=$.trim($('.aTitle',a).text());
		$('#edcid,#edpid').empty();
		var edcid	= $("#cid").clone().show().appendTo('#edcid'),
			edpid	= $("#pid").clone().show().appendTo('#edpid'),
			edtitle	= $('#edtitle',box).val(title),
			edtags	= $('#edtags',box),
			edsource= $('#edsource',box),
			eddesc	= $('#eddesc',box);

		$(".chosen-select",box).chosen(chosen_config);

		$.getJSON("<?php echo APP_URI; ?>",{'do':'getjson','id':aid},function(d){
			edcid.val(d.cid).trigger("chosen:updated");
      edpid.val(d.pid).trigger("chosen:updated");
			edtags.val(d.tags);
      edsource.val(d.source);
			eddesc.val(d.description);
		});

		iCMS.dialog({title: 'Быстрое редактирование ['+title+']',
        content:document.getElementById('ed-box'),
		    button: [{value: 'Сохранить',callback: function () {
						var title = edtitle.val(),cid=edcid.val();
						if(title==""){
							iCMS.alert("Введите название!");
							edtitle.focus();
							return false;
						}
						if(cid==0){
							iCMS.alert("Выберите категорию!");
							return false;
						}
						$(box).trigger("chosen:updated");
						$.post("<?php echo APP_URI; ?>&do=edit&CSRF_TOKEN=<?php echo iPHP_WAF_CSRF_TOKEN;?>",{
                id:aid,cid:cid,pid:edpid.val(),
                title:title,
                source:edsource.val(),
                tags:edtags.val(),
                description:eddesc.val()
              },
						  function(res){
  							if(res.code){
                  $('.aTitle',a).text(title);
                  iCMS.alert("Редактирование завершено!",true);
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
      <h5SEO Заголовок/h5>
    </div>
    <div class="widget-content">
      <form action="<?php echo iPHP_SELF ; ?>" method="get" class="form-inline">
        <input type="hidden" name="app" value="<?php echo admincp::$APP_NAME;?>" />
        <input type="hidden" name="do" value="<?php echo admincp::$APP_DO;?>" />
        <input type="hidden" name="userid" value="<?php echo $_GET['userid'] ; ?>" />
        <div class="input-prepend">
          <span class="add-on">Свойство</span>
          <select name="pid" id="pid" class="span2 chosen-select">
            <option value="-1">Любое</option>
            <option value="0">Общее[pid='0']</option>
            <?php echo propAdmincp::get("pid") ; ?>
          </select>
        </div>
        <div class="input-prepend input-append">
          <span class="add-on">Категории</span>
          <select name="cid" id="cid" class="chosen-select" style="width: 230px;">
            <option value="0"> Все категории</option>
            <?php echo $category_select = category::priv('cs')->select() ; ?>
          </select>
          <span class="add-on tip" title="Искать по всем записям, связанным с этим разделом">
            <input type="checkbox" name="scid" id="search_scid"/>Родительская категория
          </span>
          <span class="add-on tip" title="Искать по дочерним категориям включая родительскую.">
            <input type="checkbox" name="sub" id="sub"/>Дочерние + Родительская категории        
			</span>
          <span class="add-on tip" title="Не показывать скрытые категории">
            <input type="checkbox" name="hidden" id="hidden"/>Скрытые категории
          </span>
        </div>
        <div class="input-prepend input-append">
          <span class="add-on">Без эскизов
          <input type="radio" name="pic" class="checkbox spic" value="0"/>
          </span>
          <span class="add-on">С эскизами
          <input type="radio" name="pic" class="checkbox spic" value="1"/>
          </span> </div>
        <div class="clearfloat mb10"></div>
        <div class="input-prepend input-append"><span class="add-on"><i class="fa fa-calendar"></i> Время публикации</span>
          <input type="text" class="ui-datepicker" name="starttime" value="<?php echo $_GET['starttime'] ; ?>" placeholder="Время начала" />
          <span class="add-on">-</span>
          <input type="text" class="ui-datepicker" name="endtime" value="<?php echo $_GET['endtime'] ; ?>" placeholder="Время окончания" />
          <span class="add-on"><i class="fa fa-calendar"></i></span>
        </div>
        <div class="input-prepend input-append"><span class="add-on"><i class="fa fa-calendar"></i> Отложенная публикация</span>
          <input type="text" class="ui-datepicker" name="post_starttime" value="<?php echo $_GET['post_starttime'] ; ?>" placeholder="Время начала" />
          <span class="add-on">-</span>
          <input type="text" class="ui-datepicker" name="post_endtime" value="<?php echo $_GET['post_endtime'] ; ?>" placeholder="Время окончания" />
          <span class="add-on"><i class="fa fa-calendar"></i></span>
        </div>
        <div class="clearfloat mb10"></div>
        <div class="input-prepend">
          <span class="add-on">Тип публикации</span>
          <select name="postype" id="postype" class="chosen-select span3">
            <option value=""></option>
            <option value="all">Все типы</option>
            <option value="0">Опубликовано пользователями [postype='0']</option>
            <option value="1"selected='selected'>Опубликовано сайтом [postype='1']</option>
            <?php echo propAdmincp::get("postype") ; ?>
          </select>
        </div>
        <div class="input-prepend">
          <span class="add-on"> Статус </span>
          <select name="status" id="status" class="chosen-select span3">
            <option value=""></option>
            <option value="all">Все статусы</option>
            <option value="0"> Черновик [status='0']</option>
            <option value="1"selected='selected'> Нормальный [status='1']</option>
            <option value="2"> Корзина [status='2']</option>
            <option value="3"> На рассмотрении [status='3']</option>
            <option value="4"> Отказано [status='4']</option>
            <?php echo propAdmincp::get("status") ; ?>
          </select>
        </div>
        <div class="clearfloat mb10"></div>
        <div class="input-prepend input-append">
          <span class="add-on">На страницу</span>
          <input type="text" name="perpage" id="perpage" value="<?php echo $maxperpage ; ?>" style="width:36px;"/>
          <span class="add-on">записей</span>
        </div>
        <div class="input-prepend">
          <span class="add-on">Сортировка</span>
          <select name="orderby" id="orderby" class="span2 chosen-select">
            <option value=""></option>
            <optgroup label="По убыванию"><?php echo $orderby_option['DESC'];?></optgroup>
            <optgroup label="По возрастанию"><?php echo $orderby_option['ASC'];?></optgroup>
          </select>
        </div>
        <div class="input-prepend input-append">
          <span class="add-on">Искать по </span>
          <select name="st" id="st" class="chosen-select" style="width:120px;">
            <option value="title">Название</option>
            <option value="tag">Теги</option>
            <option value="source">Источник</option>
            <option value="clink"> Пользовательская ссылка </option>
            <option value="id">ID</option>
            <option value="weight">Вес</option>
            <option value="tkd">Заголовок / Ключевые слова / Содержание</option>
            <option value="pic">Иконка</option>
          </select>
          <span class="add-on">Ключевое слово</span>
          <input type="text" name="keywords" class="span3" id="keywords" value="<?php echo $_GET['keywords'] ; ?>" />
          <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i>поиск</button>
        </div>
      </form>
    </div>
  </div>
  <div class="widget-box" id="<?php echo APP_BOXID;?>">
    <div class="widget-title"> <span class="icon">
      <input type="checkbox" class="checkAll" data-target="#<?php echo APP_BOXID;?>" title="Выбрать все/Снять выбор"/>
      </span>
      <h5><?php if($cid){echo ' <span class="label label-info">'.category::get($cid)->name.'</span>';}?> Список статей</h5>
      <span title="Всего <?php echo $total;?> комментария" class="badge badge-info tip-left"><?php echo $total;?></span>
    </div>
    <div class="widget-content nopadding">
      <form action="<?php echo APP_FURI; ?>&do=batch" method="post" class="form-inline" id="<?php echo APP_FORMID;?>" target="iPHP_FRAME">
        <table class="table table-bordered table-condensed table-hover">
          <thead>
            <tr>
              <th><i class="fa fa-arrows-v"></i></th>
              <th class="span1">ID</th>
              <th>Название</th>
<?php if($maxperpage<1000){?>
              <th class="span2">Дата</th>
              <th>Категории</th>
              <th>Изменить</th>
              <th class="span3">Просмотры/Комментарии</th>
              <th class="span3">Операции</th>
<?php } ?>
            </tr>
          </thead>
          <tbody>
            <?php
                $categoryArray  = category::multi_get($rs,'cid');
                $scategoryArray = category::multi_get($rs,'scid');
                if($rs)foreach ($rs as $key => $value) {
                  $C    = (array)$categoryArray[$value['cid']];
                  $iurl = iURL::get('article',array($value,$C));
                  $value['url'] = $iurl->href;
            ?>
            <tr id="id<?php echo $value['id']; ?>">
              <td><input type="checkbox" name="id[]" value="<?php echo $value['id']; ?>" /></td>
              <td>
                <?php echo $value['id']; ?>
              </td>
              <td class="edit" aid="<?php echo $value['id']; ?>" title="Дважды щелкните, чтобы быстро изменить">
                <div>
                  <?php if($value['status']=="3"){ ?>
                  <span class="label label-important">待审核</span>
                  <?php } ?>
                  <?php if($value['postype']=="0"){ ?>
                  <span class="label label-info">Пользователей</span>
                  <?php } ?>
                  <?php if($value['haspic']) echo '<img src="./app/admincp/ui/img/image.gif" align="absmiddle">'?>
                  <a class="aTitle" href="<?php echo APP_URI; ?>&do=preview&id=<?php echo $value['id']; ?>" data-toggle="modal" title="Предварительный просмотр">
                    <?php echo $value['title'] ; ?>
                  </a>
                </div>
<?php if($maxperpage<500){?>
                <div class="row-actions">
                  <a href="<?php echo __ADMINCP__; ?>=files&indexid=<?php echo $value['id']; ?>&appid=<?php echo self::$appid;?>&method=database" class="tip-bottom" title="查看文章图片库" target="_blank"><i class="fa fa-picture-o"></i></a>
                  <a href="<?php echo APP_URI; ?>&do=findpic&id=<?php echo $value['id']; ?>" class="tip-bottom" title="查找文章所有图片" target="_blank"><i class="fa fa-picture-o"></i></a>
                  <?php if($value['postype']=="0"){ ?>
                  <a href="<?php echo APP_URI; ?>&do=update&id=<?php echo $value['id']; ?>&_args=status:1" class="tip-bottom" target="iPHP_FRAME" title="通过审核"><i class="fa fa-check-circle"></i></a>
                    <?php if($value['status']!="3"){ ?>
                    <a href="<?php echo APP_URI; ?>&do=update&id=<?php echo $value['id']; ?>&_args=status:3" class="tip-bottom" target="iPHP_FRAME" title="等待审核"><i class="fa fa-minus-circle"></i></a>
                    <?php } ?>
                  <a href="<?php echo APP_URI; ?>&do=update&id=<?php echo $value['id']; ?>&_args=status:4" class="tip-bottom" target="iPHP_FRAME" title="拒绝通过"><i class="fa fa-times-circle"></i></a>
                  <?php } ?>
                  <?php if($value['status']!="2"){ ?>
                  <a href="<?php echo __ADMINCP__; ?>=comment&appname=article&appid=<?php echo self::$appid;?>&iid=<?php echo $value['id']; ?>" class="tip-bottom" title="<?php echo $value['comments'] ; ?>条评论" target="_blank"><i class="fa fa-comment"></i></a>
                  <?php } ?>
                  <!-- <a href="<?php echo __ADMINCP__; ?>=chapter&aid=<?php echo $value['id']; ?>" class="tip-bottom" title="章节管理" target="_blank"><i class="fa fa-sitemap"></i></a> -->
                  <?php if($value['status']=="1"){ ?>
                  <?php if(apps::check('push')){ ?>
                  <a href="<?php echo __ADMINCP__; ?>=push&do=add&title=<?php echo $value['title'] ; ?>&pic=<?php echo $value['pic'] ; ?>&url=<?php echo $value['url'] ; ?>" class="tip-bottom" title="推送此文章"><i class="fa fa-thumb-tack"></i></a>
                  <?php } ?>
                  <a href="<?php echo APP_URI; ?>&do=update&id=<?php echo $value['id']; ?>&_args=status:0" class="tip-bottom" target="iPHP_FRAME" title="转为草稿"><i class="fa fa-inbox"></i></a>
                  <a href="<?php echo APP_URI; ?>&do=update&id=<?php echo $value['id']; ?>&_args=pubdate:now" class="tip-bottom" target="iPHP_FRAME" title="更新文章时间"><i class="fa fa-clock-o"></i></a>
                  <?php } ?>
                  <?php if($value['status']!="1"){ ?>
                  <a href="<?php echo APP_FURI; ?>&do=update&id=<?php echo $value['id']; ?>&_args=status:1" class="tip-bottom" target="iPHP_FRAME" title="发布文章"><i class="fa fa-paper-plane"></i></a>
                  <?php } ?>
                  <?php if($value['status']=="0"){ ?>
                  <a href="<?php echo APP_FURI; ?>&do=update&id=<?php echo $value['id']; ?>&_args=status:1" class="tip-bottom" target="iPHP_FRAME" title="发布文章"><i class="fa fa-share"></i></a>
                  <a href="<?php echo APP_URI; ?>&do=update&id=<?php echo $value['id']; ?>&_args=status:1,pubdate:now" class="tip-bottom" target="iPHP_FRAME" title="更新文章时间,并发布"><i class="fa fa-clock-o"></i></a>
                  <?php } ?>
                  <?php if($value['status']=="2"){ ?>
                  <a href="<?php echo APP_FURI; ?>&do=update&id=<?php echo $value['id']; ?>&_args=status:1" target="iPHP_FRAME" class="tip-bottom" title="从回收站恢复"/><i class="fa fa-reply-all"></i></a>
                  <?php } ?>
                  <a href="<?php echo APP_URI; ?>&do=purge&id=<?php echo $value['id']; ?>&url=<?php echo $value['url']; ?>" class="tip-bottom" data-toggle="modal" title="Очистить кэш Nginx"><i class="fa fa-recycle"></i></a>
                  <?php if (($C['mode'] && strstr($C['rule']['article'],'{PHP}')===false && $value['status']=="1" && empty($ourl) && members::$data->gid==1)||preg_match('/\[(.+)\]/', $value['clink'])){  ?>
                  <a href="<?php echo __ADMINCP__; ?>=html&do=createArticle&aid=<?php echo $value['id']; ?>&frame=iPHP" class="tip-bottom" target="iPHP_FRAME" title="Генерация статических файлов"><i class="fa fa-file"></i></a>
                  <?php } ?>
                  <?php if($value['chapter']){?>
                  <i class="fa fa-bookmark"></i>(<?php echo $value['chapter'];?>)
                  <?php } ?>
                </div>
                <?php if($value['pic'] && self::$config['showpic']){ ?>
                <a href="<?php echo APP_URI; ?>&do=preview&id=<?php echo $value['id']; ?>" data-toggle="modal" title="Предварительный просмотр"><img src="<?php echo iFS::fp($value['pic'],'+http'); ?>" style="height:120px;"/></a>
                <?php } ?>
<?php } ?>

              </td>
<?php if($maxperpage<1000){?>
              <td><?php if($value['pubdate']) echo get_date($value['pubdate'],'Y-m-d H:i');?><br />
                <?php if($value['postime']) echo get_date($value['postime'],'Y-m-d H:i');?></td>
              <td>
                <a href="<?php echo admincp::uri("cid=".$value['cid'],$uriArray); ?>"><?php echo $C['name']?:$value['cid'] ; ?></a><br />
                <?php
                 if($value['scid']){
                   $scid_array = explode(',', $value['scid']);
                   foreach ($scid_array as $scidk => $scid) {
                      $scva = $scategoryArray[$scid];
                      if($scid!=$value['cid']){
                        echo '<a href="'.admincp::uri("cid=".$value['scid'],$uriArray).'">'.$scva->name.'</a><br />';
                      }
                   }
                 }
                ?>
                <?php $value['pid'] && propAdmincp::flag($value['pid'],$propArray,admincp::uri("pid={PID}",$uriArray));?>
              </td>
              <td><a href="<?php echo admincp::uri("userid=".$value['userid'],$uriArray); ?>"><?php echo $value['editor'] ; ?></a><br /><?php echo $value['author'] ; ?></td>
              <td>
                <a class="tip" href="javascript:;" title="
                Общее количество просмотров:<?php echo $value['hits'] ; ?><br />
                За сегодня:<?php echo $value['hits_today'] ; ?><br />
                За вчера:<?php echo $value['hits_yday'] ; ?><br />
                За неделю:<?php echo $value['hits_week'] ; ?><br />
                В избранном:<?php echo $value['favorite'] ; ?><br />
                Комментариев:<?php echo $value['comments'] ; ?><br />
                Поставили лайк:<?php echo $value['good'] ; ?><br />
                ">
                  <?php echo $value['hits']; ?>/<?php echo $value['comments']; ?>
                </a>
              </td>
              <td>
                <?php if($value['status']=="1"){ ?>
                  <a href="<?php echo $value['url']; ?>" class="btn btn-success btn-mini" target="_blank"> Просмотр</a>
                <?php } ?>
                <!-- <a href="<?php echo APP_URI; ?>&do=add&id=<?php echo $value['id']; ?>" class="btn btn-primary btn-mini">+章节</a> -->
                <?php if(category::check_priv($value['cid'],'ce')){ ?>
                <a href="<?php echo APP_URI; ?>&do=add&id=<?php echo $value['id']; ?>" class="btn btn-primary btn-mini"> Изменить </a>
                <?php } ?>
                <?php if(in_array($value['status'],array("1","0")) && category::check_priv($value['cid'],'cd')){ ?>
                <a href="<?php echo APP_FURI; ?>&do=update&id=<?php echo $value['id']; ?>&_args=status:2" target="iPHP_FRAME" class="del btn btn-danger btn-mini" title="Переместить пост в корзину" /> В корзину</a>
                <?php } ?>
                <?php if($value['status']=="2"){ ?>
                <a href="<?php echo APP_FURI; ?>&do=del&id=<?php echo $value['id']; ?>" target="iPHP_FRAME" class="del btn btn-danger btn-mini" onclick="return confirm('Вы уверены, что хотите удалить?');"/>Удалить навсегда</a>
                <?php } ?>
              </td>
<?php } ?>
            </tr>
            <?php }  ?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="8"><div class="pagination pagination-right" style="float:right;"><?php echo iPagination::$pagenav ; ?></div>
                <div class="input-prepend input-append mt20">
                  <span class="add-on"> Выбрать все
                  <input type="checkbox" class="checkAll checkbox" data-target="#<?php echo APP_BOXID;?>" />
                  </span>
                  <div class="btn-group dropup" id="iCMS-batch"> <a class="btn dropdown-toggle" data-toggle="dropdown" tabindex="-1"><i class="fa fa-wrench"></i>Пакетная операция</a><a class="btn dropdown-toggle" data-toggle="dropdown" tabindex="-1"> <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                      <li><a data-toggle="batch" data-action="pubdate:now"><i class="fa fa-clock-o"></i> Изменить время публикации</a></li>
                      <?php if($stype=="inbox"||$stype=="trash"){ ?>
                      <li><a data-toggle="batch" data-action="status:1"><i class="fa fa-share"></i> Опубликовать</a></li>
                      <li><a data-toggle="batch" data-action="status:1,pubdate:now"><i class="fa fa-clock-o"></i> 发布并更新时间</a></li>
                      <?php } ?>
                      <li><a data-toggle="batch" data-action="status:0"><i class="fa fa-inbox"></i> 转为草稿</a></li>
                      <li class="divider"></li>
                      <li><a data-toggle="batch" data-action="prop"><i class="fa fa-puzzle-piece"></i> Свойства</a></li>
                      <li><a data-toggle="batch" data-action="move"><i class="fa fa-fighter-jet"></i> Переместить</a></li>
                      <li><a data-toggle="batch" data-action="scid"><i class="fa fa-code-fork"></i> Настройки подкатегорий</a></li>
                      <li><a data-toggle="batch" data-action="thumb"><i class="fa fa-picture-o"></i> Сделать первую картинку эскизом</a></li>
                      <li><a data-toggle="batch" data-action="weight"><i class="fa fa-cog"></i> 设置置顶权重</a></li>
                      <li><a data-toggle="batch" data-action="keyword"><i class="fa fa-star"></i> 设置关键字</a></li>
                      <li><a data-toggle="batch" data-action="tag"><i class="fa fa-tags"></i> 设置标签</a></li>
                      <li><a data-toggle="batch" data-action="meta"><i class="fa fa-sitemap"></i> 设置动态属性</a></li>
                      <li><a data-toggle="batch" data-action="status"><i class="fa fa-cog"></i> 设置状态</a></li>
                      <li><a data-toggle="batch" data-action="postype"><i class="fa fa-cog"></i> 设置发布类型</a></li>
                      <li class="divider"></li>
                      <?php if(iCMS::$config['api']['baidu']['sitemap']['site'] && iCMS::$config['api']['baidu']['sitemap']['access_token']){ ?>
                      <li><a data-toggle="batch" data-action="baiduping" title="百度站长平台-主动推送"><i class="fa fa-send"></i> Сообщить поисковику</a></li>
                      <li><a data-toggle="batch" data-action="baiduping_original" title="百度站长平台-原创提交"><i class="fa fa-send"></i> 原创提交</a></li>
                      <li class="divider"></li>
                      <?php } ?>
                      <li><a data-toggle="batch" data-action="status:2"><i class="fa fa-trash-o"></i> Переместить в корзину</a></li>
                      <li class="divider"></li>
                      <li><a data-toggle="batch" data-action="quick_dels" title="Удалить только данные статьи" class="tip-right"><i class="fa fa-trash-o"></i>Удалить навсегда (быстрое)</a></li>
                      <li><a data-toggle="batch" data-action="dels" title="Удалить все данные, связанные со статьей" class="tip-right"><i class="fa fa-trash-o"></i> Удалить навсегда</a></li>
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
  <div class="input-prepend"> <span class="add-on">Свойства</span> <span id="edpid"></span> </div>
  <div class="clearfloat mb10"></div>
  <div class="input-prepend">
    <span class="add-on"> Название </span>
    <input type="text" class="span6" id="edtitle"/>
  </div>
  <div class="clearfloat mb10"></div>
  <div class="input-prepend">
    <span class="add-on"> Источник </span>
    <input type="text" class="span6" id="edsource"/>
  </div>
  <div class="clearfloat mb10"></div>
  <div class="input-prepend">
   <span class="add-on">Теги</span>
    <input type="text" class="span6" id="edtags"/>
  </div>
  <div class="clearfloat mb10"></div>
  <div class="input-prepend"><span class="add-on">Описание</span>
    <textarea id="eddesc" class="span6" style="height: 120px;"></textarea>
  </div>
</div>
<div class='iCMS-batch'>
  <div id="scidBatch">
    <div class="input-prepend">
      <span class="add-on">Подкатегории</span>
      <select name="scid[]" id="scid" class="span3" multiple="multiple"  data-placeholder="Выберите подкатегорию (несколько вариантов)...">
        <?php echo $category_select;?>
      </select>
    </div>
  </div>
  <div id="statusBatch">
    <div class="input-prepend">
      <span class="add-on"> Статус </span>
      <select name="mstatus" id="mstatus" class="span3" data-placeholder="Выберите статус">
        <option value="0"> Черновик [status='0']</option>
        <option value="1"selected='selected'> Нормальный [status='1']</option>
        <option value="2"> Корзина [status='2']</option>
        <option value="3"> На рассмотрении [status='3']</option>
        <option value="4"> Отказано [status='4']</option>
        <?php echo propAdmincp::get("status") ; ?>
      </select>
    </div>
  </div>
  <div id="postypeBatch">
    <div class="input-prepend">
      <span class="add-on">Тип публикации</span>
      <select name="mpostype" id="mpostype" class="span3" data-placeholder="Выберите тип публикации">
        <option value=""></option>
        <option value="0">Опубликовано пользователями [postype='0']</option>
        <option value="1"selected='selected'>Опубликовано сайтом [postype='1']</option>
        <?php echo propAdmincp::get("postype") ; ?>
      </select>
    </div>
  </div>
  <div id="metaBatch">
    <?php include admincp::view("apps.meta","apps");?>
  </div>
</div>
<?php admincp::foot();?>
