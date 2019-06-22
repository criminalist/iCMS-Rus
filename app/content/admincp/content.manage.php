<?php

defined('iPHP') OR exit('Oops, something went wrong');
admincp::head();
?>
<script type="text/javascript">
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

  $("#<?php echo APP_FORMID;?>").batch({
    scid:function(){
      return $("#scidBatch").clone(true);
    }
  });
});
</script>
<div class="iCMS-container">
  <div class="widget-box">
    <div class="widget-title">
      <span class="icon"> <i class="fa fa-search"></i> </span>
      <h5SEO Заголовок/h5>
    </div>
    <div class="widget-content">
      <form action="<?php echo iPHP_SELF ; ?>" method="get" class="form-inline">
        <input type="hidden" name="app" value="<?php echo admincp::$APP_NAME;?>" />
        <input type="hidden" name="do" value="<?php echo admincp::$APP_DO;?>" />
        <input type="hidden" name="userid" value="<?php echo $_GET['userid'] ; ?>" />
        <div class="input-prepend"> <span class="add-on"><?php echo $this->app['name'];?> Свойства </span>
          <select name="pid" id="pid" class="span2 chosen-select">
            <option value="-1">Все <?php echo $this->app['name'];?></option>
            <option value="0">普通<?php echo $this->app['name'];?>[pid='0']</option>
            <?php echo propAdmincp::get("pid") ; ?>
          </select>
        </div>
        <div class="input-prepend input-append"> <span class="add-on">Категории</span>
          <select name="cid" id="cid" class="chosen-select" style="width: 230px;">
            <option value="0"> Все категории</option>
            <?php echo $category_select = category::priv('cs')->select() ; ?>
          </select>
          <span class="add-on tip" title="选中查询所有关联到此栏目的<?php echo $this->app['name'];?>">
          <input type="checkbox" name="scid" id="search_scid"/>
          副栏目 </span>
          <span class="add-on tip" title="选中查询此栏目下面所有的子栏目,包含本栏目">
            <input type="checkbox" name="sub" id="sub"/>Подкатегории
          </span>
          <span class="add-on tip" title="Не показывать скрытые категории">
            <input type="checkbox" name="hidden" id="hidden"/>Скрытые категории
          </span>
        </div>
        <div class="input-prepend">
          <span class="add-on">Сортировка</span>
          <select name="orderby" id="orderby" class="span2 chosen-select">
            <option value=""></option>
            <optgroup label="По убыванию"><?php echo $orderby_option['DESC'];?></optgroup>
            <optgroup label="По возрастанию"><?php echo $orderby_option['ASC'];?></optgroup>
          </select>
        </div>
        <div class="input-prepend input-append"> <span class="add-on">Без эскизов
          <input type="radio" name="pic" class="checkbox spic" value="0"/>
          </span> <span class="add-on">С эскизами
          <input type="radio" name="pic" class="checkbox spic" value="1"/>
          </span>
        </div>
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
            <option value="1"selected='selected'> Опубликован [status='1']</option>
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
            <option value="id">ID</option>
          </select>
        </div>
        <div class="input-prepend input-append">
          <span class="add-on">Ключевое слово</span>
          <input type="text" name="keywords" class="span2" id="keywords" value="<?php echo $_GET['keywords'] ; ?>" />
          <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i>поиск</button>
        </div>
      </form>
    </div>
  </div>
  <div class="widget-box" id="<?php echo APP_BOXID;?>">
    <div class="widget-title">
      <span class="icon">
        <input type="checkbox" class="checkAll" data-target="#<?php echo APP_BOXID;?>" />
      </span>
      <h5> Список </h5>
    </div>
    <div class="widget-content nopadding">
      <form action="<?php echo APP_FURI; ?>&do=batch" method="post" class="form-inline" id="<?php echo APP_FORMID;?>" target="iPHP_FRAME">
        <table class="table table-bordered table-condensed table-hover">
          <thead>
            <tr>
              <th><i class="fa fa-arrows-v"></i></th>
              <th class="span1">ID</th>
              <th>Название</th>
              <th class="span2">Дата</th>
              <th style="width:80px;">Категории</th>
              <th style="width:60px;"> Изменить </th>
              <th class="span1">Просмотры/Комментарии</th>
              <th style="width:120px;">Операции</th>
            </tr>
          </thead>
          <tbody>
          <?php

            // former::$callback = array(
            //   'category'       => array('category','get'),
            //   'multi_category' => array('category','get'),
            //   // 'prop'           => array('prop','get'),
            //   // 'multi_prop'     => array('prop','get'),
            // );
            // former::multi_value($rs,$fields);

            // $categoryArray = former::$variable['cid'];

            $categoryArray  = category::multi_get($rs,'cid');

            foreach ((array)$rs as $key => $value) {
              $id           = $value[content::$primary];
              $C            = (array)$categoryArray[$value['cid']];
              $iurl         = iURL::get($this->app['app'],array($value,$C));
              $value['url'] = $iurl->href;
          ?>
            <tr id="id<?php echo $id; ?>">
              <td><input type="checkbox" name="id[]" value="<?php echo $id ; ?>" /></td>
              <td><?php echo $id ; ?></td>
              <td><div class="edit" aid="<?php echo $id ; ?>">
                  <?php if($value['status']=="3"){ ?>
                  <span class="label label-important">待审核</span>
                  <?php } ?>
                  <?php if($value['postype']=="0"){ ?>
                  <span class="label label-info">Пользователей</span>
                  <?php } ?>
                  <a class="aTitle" href="<?php echo $value['url']; ?>" data-toggle="modal" title="Предварительный просмотр">
                    <?php echo $value['title'] ; ?>
                  </a>
                 </div>
                <div class="row-actions">
                  <a href="<?php echo __ADMINCP__; ?>=files&indexid=<?php echo $id ; ?>&appid=<?php echo $this->appid;?>&method=database" class="tip-bottom" title="查看<?php echo $this->app['name'];?>图片库" target="_blank"><i class="fa fa-picture-o"></i></a>
                  <a href="<?php echo APP_URI; ?>&do=findpic&id=<?php echo $id ; ?>" class="tip-bottom" title="查找<?php echo $this->app['name'];?>所有图片" target="_blank"><i class="fa fa-picture-o"></i></a>
                  <?php if($value['postype']=="0"){ ?>
                  <a href="<?php echo APP_URI; ?>&do=update&id=<?php echo $id ; ?>&_args=status:1" class="tip-bottom" target="iPHP_FRAME" title="通过审核"><i class="fa fa-check-circle"></i></a>
                    <?php if($value['status']!="3"){ ?>
                    <a href="<?php echo APP_URI; ?>&do=update&id=<?php echo $id ; ?>&_args=status:3" class="tip-bottom" target="iPHP_FRAME" title="等待审核"><i class="fa fa-minus-circle"></i></a>
                    <?php } ?>
                  <a href="<?php echo APP_URI; ?>&do=update&id=<?php echo $id ; ?>&_args=status:4" class="tip-bottom" target="iPHP_FRAME" title="拒绝通过"><i class="fa fa-times-circle"></i></a>
                  <?php } ?>
                  <?php if($value['status']!="2"){ ?>
                  <a href="<?php echo __ADMINCP__; ?>=comment&appname=<?php echo $this->app['app'];?>&appid=<?php echo $this->appid;?>&iid=<?php echo $id ; ?>" class="tip-bottom" title="<?php echo $value['comments'] ; ?>条评论" target="_blank"><i class="fa fa-comment"></i></a>
                  <?php } ?>
                  <!-- <a href="<?php echo __ADMINCP__; ?>=chapter&aid=<?php echo $id ; ?>" class="tip-bottom" title="章节管理" target="_blank"><i class="fa fa-sitemap"></i></a> -->
                  <?php if($value['status']=="1"){ ?>
                  <?php if(apps::check('push')){ ?>
                  <a href="<?php echo __ADMINCP__; ?>=push&do=add&title=<?php echo $value['title'] ; ?>&pic=<?php echo $value['pic'] ; ?>&url=<?php echo $value['url'] ; ?>" class="tip-bottom" title="推送此<?php echo $this->app['name'];?>"><i class="fa fa-thumb-tack"></i></a>
                  <?php } ?>
                  <a href="<?php echo APP_URI; ?>&do=update&id=<?php echo $id ; ?>&_args=status:0" class="tip-bottom" target="iPHP_FRAME" title="Отправить в черновик"><i class="fa fa-inbox"></i></a>
                  <a href="<?php echo APP_URI; ?>&do=update&id=<?php echo $id ; ?>&_args=pubdate:now" class="tip-bottom" target="iPHP_FRAME" title="更新<?php echo $this->app['name'];?>时间"><i class="fa fa-clock-o"></i></a>
                  <?php } ?>
                  <?php if($value['status']=="0"){ ?>
                  <a href="<?php echo APP_FURI; ?>&do=update&id=<?php echo $id ; ?>&_args=status:1" class="tip-bottom" target="iPHP_FRAME" title="发布<?php echo $this->app['name'];?>"><i class="fa fa-share"></i></a>
                  <a href="<?php echo APP_URI; ?>&do=update&id=<?php echo $id ; ?>&_args=status:1,pubdate:now" class="tip-bottom" target="iPHP_FRAME" title="更新<?php echo $this->app['name'];?>时间,并发布"><i class="fa fa-clock-o"></i></a>
                  <?php } ?>
                  <?php if($value['status']=="2"){ ?>
                  <a href="<?php echo APP_FURI; ?>&do=update&id=<?php echo $id ; ?>&_args=status:1" target="iPHP_FRAME" class="tip-bottom" title="从回收站恢复"/><i class="fa fa-reply-all"></i></a>
                  <?php } ?>
                </div>
                <?php if($value['pic'] && $this->config['showpic']){ ?>
                <a href="<?php echo APP_URI; ?>&do=preview&id=<?php echo $id ; ?>" data-toggle="modal" title="Предварительный просмотр"><img src="<?php echo iFS::fp($value['pic'],'+http'); ?>" style="height:120px;"/></a>
                <?php } ?>
              </td>
              <td><?php if($value['pubdate']) echo get_date($value['pubdate'],'Y-m-d H:i');?><br />
                <?php if($value['postime']) echo get_date($value['postime'],'Y-m-d H:i');?>
              </td>
              <td>
                <a href="<?php echo APP_DOURI; ?>&cid=<?php echo $value['cid'] ; ?>&<?php echo $uri ; ?>"><?php echo $C['name'] ; ?></a><br />
                <?php $value['pid'] && propAdmincp::flag($value['pid'],$propArray,APP_DOURI.'&pid={PID}&'.$uri);?>
              </td>
              <td><a href="<?php echo APP_DOURI; ?>&userid=<?php echo $value['userid'] ; ?>&<?php echo $uri ; ?>"><?php echo $value['editor'] ; ?></a></td>
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
                <a href="<?php echo $value['url']; ?>" class="btn btn-success btn-mini" target="_blank"> Просмотр </a>
                <?php } ?>
                <a href="<?php echo APP_URI; ?>&do=add&id=<?php echo $id ; ?>" class="btn btn-primary btn-mini"> Изменить </a>
                <?php if(in_array($value['status'],array("1","0")) && category::check_priv($value['cid'],'cd')){ ?>
                <a href="<?php echo APP_FURI; ?>&do=update&id=<?php echo $id ; ?>&_args=status:2" target="iPHP_FRAME" class="del btn btn-danger btn-mini" title="移动此<?php echo $this->app['name'];?>到回收站" /> Удалить</a>
                <?php } ?>
                <?php if($value['status']=="2"){ ?>
                <a href="<?php echo APP_FURI; ?>&do=del&id=<?php echo $id ; ?>" target="iPHP_FRAME" class="del btn btn-danger btn-mini" onclick="return confirm('Вы уверены, что хотите удалить?');"/>Удалить навсегда</a>
                <?php } ?>
              </td>
            </tr>
            <?php }  ?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="8"><div class="pagination pagination-right" style="float:right;"><?php echo iPagination::$pagenav ; ?></div>
                <div class="input-prepend input-append mt20"> <span class="add-on"> Выбрать все
                  <input type="checkbox" class="checkAll checkbox" data-target="#<?php echo APP_BOXID;?>" />
                  </span>
                  <div class="btn-group dropup" id="iCMS-batch"> <a class="btn dropdown-toggle" data-toggle="dropdown" tabindex="-1"><i class="fa fa-wrench"></i>Пакетная операция</a><a class="btn dropdown-toggle" data-toggle="dropdown" tabindex="-1"> <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                      <li><a data-toggle="batch" data-action="pubdate:now"><i class="fa fa-clock-o"></i> Изменить время публикации</a></li>
                      <?php if($stype=="inbox"||$stype=="trash"){ ?>
                      <li><a data-toggle="batch" data-action="status:1"><i class="fa fa-share"></i> Опубликовать</a></li>
                      <li><a data-toggle="batch" data-action="status:1,pubdate:now"><i class="fa fa-clock-o"></i> Время публикации и обновления</a></li>
                      <?php } ?>
                      <li><a data-toggle="batch" data-action="status:0"><i class="fa fa-inbox"></i> Отправить в черновик</a></li>
                      <li class="divider"></li>
                      <li><a data-toggle="batch" data-action="prop"><i class="fa fa-puzzle-piece"></i> Установка свойств к <?php echo $this->app['name'];?></a></li>
                      <li><a data-toggle="batch" data-action="move"><i class="fa fa-fighter-jet"></i> Переместить</a></li>
                      <li><a data-toggle="batch" data-action="scid"><i class="fa fa-code-fork"></i> Настройки подкатегорий</a></li>
                      <li><a data-toggle="batch" data-action="weight"><i class="fa fa-cog"></i> Установка веса</a></li>
                      <li><a data-toggle="batch" data-action="meta"><i class="fa fa-sitemap"></i> Установка динамических свойств</a></li>
                      <li class="divider"></li>
                      <?php if(iCMS::$config['api']['baidu']['sitemap']['site'] && iCMS::$config['api']['baidu']['sitemap']['access_token']){ ?>
                      <li><a data-toggle="batch" data-action="baiduping" title="百度站长平台主动推送"><i class="fa fa-send"></i> Сообщить поисковику</a></li>
                      <li class="divider"></li>
                      <?php } ?>
                      <li><a data-toggle="batch" data-action="status:2"><i class="fa fa-trash-o"></i> Переместить в корзину</a></li>
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
<div class='iCMS-batch'>
  <div id="scidBatch">
    <div class="input-prepend">
      <select name="scid[]" id="scid" class="span3" multiple="multiple"  data-placeholder="Выберите подкатегорию (несколько вариантов)...">
        <?php echo $category_select;?>
      </select>
    </div>
  </div>
  <div id="metaBatch">
    <?php include admincp::view("apps.meta","apps");?>
  </div>
</div>
<?php admincp::foot();?>
