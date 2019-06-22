<?php

defined('iPHP') OR exit('Oops, something went wrong');
?>
<div id="header" class="navbar navbar-static-top">
  <div class="navbar-inner">
    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
    <span class="fa fa-bars"></span>
    </a>
    <a class="brand iCMS-logo" href="https://www.icmsdev.com" target="_blank">
      <img src="./app/admincp/ui/iCMS.logo.mini.png" />
    </a>
      <div class="nav-collapse collapse">
        <ul class="nav pull-right">
          <li><a href="<?php echo iCMS_URL;?>" target="_blank" title="网站首页"><i class="fa fa-home fa-lg"></i></a></li>
          <li class="divider-vertical"></li>
          <li class="dropdown"> <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" title="<?php echo members::$data->nickname;?>"><i class="fa fa-user"></i> <b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li><a href="javascript:;"><?php echo members::$group->name;?></a></li>
              <li class="divider"></li>
              <li><a href="<?php echo __ADMINCP__; ?>=members&do=job"><i class="fa fa-bar-chart-o"></i> Статистика</a></li>
              <li><a href="<?php echo __ADMINCP__; ?>=members&do=profile"><i class="fa fa-user"></i> Персональная информация</a></li>
              <?php if(members::is_superadmin()){?>
              <?php if(!iCMS::$config['debug']['access_log']){?>
              <li class="divider"></li>
              <li><a href="<?php echo iPHP_SELF; ?>?do=access_log"><i class="fa fa-bar-chart"></i> Журнал операций</a></li>
              <?php }?>
              <?php }?>
              <li class="divider"></li>
              <li><a href="<?php echo iPHP_SELF; ?>?do=logout&frame=iPHP" target="iPHP_FRAME"><i class="fa fa-sign-out"></i> Выход</a></li>
            </ul>
          </li>
        </ul>
        <ul class="nav iMenu-nav" id="iCMS-menu">
          <?php echo menu::nav(); ?>
        </ul>
      </div>
  </div>
</div>
<?php if(iCMS::$config['other']['sidebar_enable']){?>
<div id="sidebar" class="navbar">
	<div id="sidebartop" class="navbar-inner">
    <a class="brand iCMS-logo" href="https://www.icmsdev.com" target="_blank">
    <img src="./app/admincp/ui/iCMS.logo.mini.png" />
    </a>
  </div>
  <ul class="iMenu-sidebar">
    <?php echo menu::sidebar(); ?>
    <li class="last"></li>
  </ul>
  <div class="clearfloat"></div>
  <span id="mini"> <i class="fa fa-arrow-circle-left"></i> </span>
</div>
<?php }?>
<div id="content">
  <div id="breadcrumb">
    <a href="<?php echo iPHP_SELF; ?>" title="Вернуться на главную страницу управления" class="tip-bottom"><i class="fa fa-home"></i> Центр управления</a>
  </div>
<script type="text/javascript">
$(".iMenu-nav,.iMenu-sidebar")
.find('a[href="<?php echo menu::$url; ?>"]')
.each(function(){
  find_parent(this);
});
var href = '/'+window.location.href.split("/")[3];

$(".submenu,.dropdown-submenu").find('a[href="'+href+'"]').each(function(){
  $(this).addClass("active");
  $(this).parent().addClass("active");
});

$("li.active>a","#iCMS-menu").each(function(){
  var a = $(this).clone();
  a.removeClass('dropdown-toggle').removeAttr('data-toggle');
  $("#breadcrumb").append(a);
});

function find_parent (a) {
  var p = $(a).parent();
  if(p[0].nodeName=="LI"||p[0].nodeName=="UL"){
    $(p).addClass("active");
    if($(p).hasClass("submenu")){
      $(p).addClass("open");
      $("ul",$(p)).show();
    }
    if(p[0].nodeName=="UL" && !$(p).hasClass("dropdown-menu")){
      $(p).addClass("open").show();
    }
    find_parent(p[0]);
  }
  return true;
}

</script>
