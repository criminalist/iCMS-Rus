<?php

defined('iPHP') OR exit('Oops, something went wrong');
admincp::head();
?>
<script type="text/javascript">
$(function(){
  <?php if($_GET['cid']){  ?>
  iCMS.select('cid',"<?php echo $_GET['cid'] ; ?>");
  <?php } ?>
  <?php if($_GET['sub']=="on"){ ?>
  iCMS.checked('#sub');
  <?php } ?>

	$("#<?php echo APP_FORMID;?>").batch();

  $(".view_reply").popover({
    html:true,
    content:function(){
      var a = $(this);
      $.get('<?php echo APP_URI; ?>&do=get_reply',{'id': a.attr('data-id')},
        function(html) {
          update_popover(html,a);
        }
      );
      return '<p><img src="./app/admincp/ui/img/ajax_loader.gif" /></p>';
    }
  });
});
function update_popover(html,a){
  $('.popover-content','.popover').html(html);
  var close = $('<button type="button" class="close">×</button>');
  close.click(function(event) {
    a.popover('toggle');
  });
  $('.popover-title','.popover').append(close);
}
</script>
<style>
.popover{max-width: 600px;}
.popover.right .arrow{top:65px;}
</style>
<div class="iCMS-container">
  <div class="widget-box">
    <div class="widget-title"> <span class="icon"> <i class="fa fa-search"></i> </span>
      <h5SEO Заголовок/h5>
    </div>
    <div class="widget-content">
      <form action="<?php echo iPHP_SELF ; ?>" method="get" class="form-inline">
        <input type="hidden" name="app" value="<?php echo admincp::$APP_NAME;?>" />
        <div class="input-prepend input-append"> <span class="add-on">Категории</span>
          <select name="cid" id="cid" class="span3 chosen-select">
            <option value="0"> Все категории</option>
            <?php echo category::priv('cs')->select() ; ?>
          </select>
          <span class="add-on">
          <input type="checkbox" name="sub" id="sub"/>
        Подкатегории</span> </div>
        <div class="input-prepend input-append"> <span class="add-on">На страницу</span>
          <input type="text" name="perpage" id="perpage" value="<?php echo $maxperpage ; ?>" style="width:36px;"/>
        <span class="add-on">записей</span> </div>
        <div class="input-prepend input-append"> <span class="add-on">Ключевое слово</span>
          <input type="text" name="keywords" class="span2" id="keywords" value="<?php echo $_GET['keywords'] ; ?>" />
          <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i>поиск</button>
        </div>
      </form>
    </div>
  </div>
  <div class="widget-box" id="<?php echo APP_BOXID;?>">
    <div class="widget-title"> <span class="icon">
      <input type="checkbox" class="checkAll" data-target="#<?php echo APP_BOXID;?>" />
      </span>
      <h5>Список комментариев</h5>
      <span title="Всего <?php echo $total;?> комментариев" class="badge badge-info tip-left"><?php echo $total;?></span>
    </div>
    <div class="widget-content nopadding">
      <form action="<?php echo APP_FURI; ?>&do=batch" method="post" class="form-inline" id="<?php echo APP_FORMID;?>" target="iPHP_FRAME">
        <ul class="recent-comments">
          <?php if($rs){
          foreach ($rs as $key => $value) {
            $url       = commentApp::redirect_url($value);
            $user      = user::info($value['userid'],$value['username']);
            $app_label = apps::get_label($value['appid']);
          ?>
          <li id="id-<?php echo $value['id']; ?>">
            <div class="user-thumb">
              <a href="<?php echo $user['url'] ; ?>" target="_blank" class="avatar">
              <img width="50" height="50" alt="<?php echo $user['name'] ; ?>" src="<?php echo $user['avatar'] ; ?>">
              </a>
              <div class="claerfix mb10"></div>
              <a href="<?php echo __ADMINCP__; ?>=user&do=update&id=<?php echo $value['userid'] ; ?>&_args=status:2" class="btn btn-inverse btn-mini tip-bottom" title="Добавить черный список, чтобы запретить пользователю вход в систему" target="iPHP_FRAME">Блокировать</a>
            </div>
            <div class="comments">
              <input type="checkbox" name="id[]" value="<?php echo $value['id']; ?>" />
              <span class="user-info">
                <a href="<?php echo APP_URI; ?>&userid=<?php echo $value['userid'] ; ?>" class="tip" title="Посмотреть все комментарии этого пользователя"><span class="label label-info"><?php echo $user['name'] ; ?></span></a>
                在<?php echo $app_label; ?>
                <a href="<?php echo APP_URI; ?>&iid=<?php echo $value['iid'] ; ?>" class="tip" title="查看该<?php echo $app_label;?>所有评论"><?php echo $value['title'] ; ?></a>
                <a href="<?php echo $url; ?>" target="_blank">[Оригинал]</a>
                <?php if($value['reply_id']){?>
                  对<a href="<?php echo APP_URI; ?>&userid=<?php echo $value['reply_uid'] ; ?>" class="tip" title="Посмотреть все комментарии этого пользователя"><span class="label label-success"><?php echo $value['reply_name'] ; ?></span></a>Ответить на комментарий
                <?php }else{?>
                  ответ на комментарий
                <?php } ?>
                <a href="<?php echo APP_URI; ?>&ip=<?php echo $value['ip'] ; ?>" class="tip" title="Посмотреть все комментарии по IP"><span class="label label-inverse">IP:<?php echo $value['ip'] ; ?></span></a>
                <?php if(!$value['status']){?>
                 <span class="label label-warning">Ожидает модерации</span>
                <?php } ?>
              </span>
              <p>
              <?php echo nl2br($value['content']); ?>
              <?php if($value['reply_id']){?>
                <div class="claerfix"></div>
                <a href="javascript:;" class="btn view_reply" data-id="<?php echo $value['reply_id'] ; ?>" title="Ответы на комментарий от <?php echo $value['reply_name'] ; ?>" >Посмотреть ответ на комментарий от <?php echo $value['reply_name'] ; ?> </a>
              <?php } ?>
              </p>
              <div class="claerfix"></div>
              <span class="label"><?php echo get_date($value['addtime'],'Y-m-d H:i:s');?></span>
              <span class="label label-info"><i class="fa fa-thumbs-o-up"></i> <?php echo $value['up'] ; ?></span>
              <?php if(!$value['status']){?>
              <a href="<?php echo APP_FURI; ?>&do=update&id=<?php echo $value['id']; ?>&_args=status:1" class="btn btn-success btn-mini" target="iPHP_FRAME">Одобрен</a>
              <?php } ?>
              <a href="<?php echo APP_FURI; ?>&do=del&id=<?php echo $value['id']; ?>" class="btn btn-danger btn-mini" target="iPHP_FRAME" onclick="return confirm('Вы уверены, что хотите удалить?');"> Удалить</a>
            </div>
            <div class="claerfix mb10"></div>
          </li>
          <?php }} ?>
        </ul>
    </form>
    <table class="table table-bordered table-condensed table-hover">
      <tr>
        <td><div class="pagination pagination-right" style="float:right;"><?php echo iPagination::$pagenav ; ?></div>
        <div class="input-prepend input-append mt20"> <span class="add-on"> Выбрать все
          <input type="checkbox" class="checkAll checkbox" data-target="#<?php echo APP_BOXID;?>" />
          </span>
          <div class="btn-group dropup" id="iCMS-batch"> <a class="btn dropdown-toggle" data-toggle="dropdown" tabindex="-1"><i class="fa fa-wrench"></i> Пакетные операции</a><a class="btn dropdown-toggle" data-toggle="dropdown" tabindex="-1"> <span class="caret"></span></a>
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
