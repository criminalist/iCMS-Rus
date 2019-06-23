<?php

defined('iPHP') OR exit('Oops, something went wrong');
admincp::head();
?>
<style>
.app_list_desc{font-size: 12px;color: rgb(103, 128, 159);}
.nopadding .tab-content{padding: 0px;}
</style>
<script type="text/javascript">
$(function(){
  $("#<?php echo APP_FORMID;?>").batch();
  $("#local_app").click(function(event) {
      var local_app_wrap = document.getElementById("local_app_wrap");
      iCMS.dialog({
        title: 'iCMS -Установка приложения локально',
        content:local_app_wrap
      });
  });
});
function uninstall($msg,$a) {
  if(confirm($msg)){
    var href = $($a).attr('href')+'&confirm=true';
    $($a).attr('href',href);
    return true;
  }
  return false;
}
</script>
<div class="iCMS-container">
  <div class="widget-box">
    <div class="widget-title"> <span class="icon"> <i class="fa fa-search"></i> </span>
    <h5SEO Заголовок/h5>
    <div class="pull-right">
      <a style="margin: 10px;" class="btn btn-success btn-mini" href="<?php echo APP_FURI; ?>&do=cache" target="iPHP_FRAME"><i class="fa fa-refresh"></i>Обновить кэш</a>
    </div>
  </div>
  <div class="widget-content">
      <div class="pull-right">
        <button class="btn btn-primary" type="button" id="local_app"><i class="fa fa-send"></i>Установка приложения локально</button>
      </div>
      <form action="<?php echo iPHP_SELF ; ?>" method="get" class="form-inline">
      <input type="hidden" name="app" value="<?php echo admincp::$APP_NAME;?>" />
      <div class="input-prepend input-append">
        <span class="add-on">На страницу</span>
        <input type="text" name="perpage" id="perpage" value="<?php echo $maxperpage ; ?>" style="width:36px;"/>
        <span class="add-on">записей</span> </div>
        <div class="input-prepend input-append">
          <span class="add-on">Ключевое слово</span>
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
    <ul class="nav nav-tabs" id="apps-tab">
      <?php foreach (apps::$type_array as $key => $value) {?>
      <li class="apps-type-<?php echo $key;?>"><a href="#apps-type-<?php echo $key;?>" data-toggle="tab"><i class="fa fa-cubes"></i> <?php echo $value;?></a></li>
      <?php }?>
    </ul>
  </div>
  <div class="widget-content nopadding">
    <form action="<?php echo APP_FURI; ?>&do=batch" method="post" class="form-inline" id="<?php echo APP_FORMID;?>" target="iPHP_FRAME">
      <div class="tab-content">
        <?php foreach (apps::$type_array as $type_key => $type_value) {?>
        <div id="apps-type-<?php echo $type_key;?>" class="tab-pane apps-type-<?php echo $type_key;?>">
          <table class="table table-bordered table-condensed table-hover">
            <thead>
              <tr>
                <th style="width:40px;">APPID</th>
                <th>ID</th>
                <th class="span3"> Имя </th>
                <th>Таблицы в базе</th>
                <th class="span3">Теги шаблона</th>
                <th>Операции</th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach ((array)$apps_type_group[$type_key] as $key => $data) {
                $table  = apps::table_item($data['table']);
                $config = json_decode($data['config'],true);
              ?>
              <tr id="id<?php echo $data['id'] ; ?>">
                <td><b><?php echo $data['id'] ; ?></b></td>
                <td>
                  <b><?php echo $data['app'] ; ?></b><br />
                  <span class="label label-inverse"><?php echo $config['version'] ; ?></span>
                </td>
                <td>
                  <?php echo $data['name'] ; ?>
                  <p class="app_list_desc"><?php echo $config['info'] ; ?></p>
                  <?php if($config['iFormer']){ ?>
                    <span class="label label-info">可自定义</span>
                  <?php }?>
                </td>
                <td>
                  <?php if(is_array($table)){ ?>
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <td> Название таблицы </td>
                        <td> Первичный ключ </td>
                        <td>关联</td>
                        <td> Имя </td>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      foreach ((array)$table as $tkey => $tval) {
                      ?>
                      <tr>
                        <td><?php echo $tval['name'] ; ?></td>
                        <td><?php echo $tval['primary'] ; ?></td>
                        <td><?php echo $tval['union'] ; ?></td>
                        <td><?php echo $tval['label'] ; ?></td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                  <?php }else{
                    echo '<span class="label">Нет привязки к таблицам</span>';
                  }
                  ?>
                </td>
                <td>
                  <?php
                  if($config['template']){
                    foreach ((array)$config['template'] as $key => $tpltags) {
                      echo '<a href="https://www.icmsdev.com/docs/'.str_replace(array(':','$'), array('_',''), $tpltags).'" target="_blank" title="点击查看模板标签说明">&lt;!--{'.$tpltags.'}--&gt;</a><br />';
                    }
                  }else{
                    echo '<span class="label">Не поддерживает теги</span>';
                  }
                  ?>
                  </td>
                  <td>
                    <?php if($data['type']){?>
                      <?php if($data['apptype']=="2"){?>
                        <a href="<?php echo __ADMINCP__; ?>=<?php echo $data['app'] ; ?>&do=manage&appid=<?php echo $data['id'] ; ?>" class="btn btn-small" target="_blank"><i class="fa fa-dashboard"></i> Контент</a>
                        <a href="<?php echo __ADMINCP__; ?>=<?php echo $data['app'] ; ?>&do=add&appid=<?php echo $data['id'] ; ?>" class="btn btn-small" target="_blank"><i class="fa fa-edit"></i> 添加内容</a>
                        <div class="clearfix mt5"></div>
                      <?php }?>
                      <a href="<?php echo APP_URI; ?>&do=add&id=<?php echo $data['id'] ; ?>" class="btn btn-small"><i class="fa fa-edit"></i> Редактировать</a>
                      <a href="<?php echo APP_URI; ?>&do=pack&id=<?php echo $data['id'] ; ?>" class="btn btn-small"><i class="fa fa-download"></i> Скачать пакет</a>
                      <?php if($data['apptype']){?>
                        <?php if($data['status']){?>
                          <a href="<?php echo APP_URI; ?>&do=update&_args=status:0&id=<?php echo $data['id'] ; ?>" target="iPHP_FRAME" class="btn btn-small btn-warning" onclick="return confirm('关闭应用不会删除数据,但应用将不可用\n确定要关闭应用?');"><i class="fa fa-close"></i> Закрыть </a>
                        <?php }else{?>
                          <a href="<?php echo APP_URI; ?>&do=update&_args=status:1&id=<?php echo $data['id'] ; ?>" target="iPHP_FRAME" class="btn btn-small btn-success"><i class="fa fa-check"></i> Активировать</a>
                        <?php }?>
                        <a href="<?php echo APP_FURI; ?>&do=uninstall&id=<?php echo $data['id'] ; ?>" target="iPHP_FRAME" class="del btn btn-small btn-danger" title='Удалить навсегда'  onclick="return uninstall('卸载应用会清除应用所有数据!\n卸载应用会清除应用所有数据!\n卸载应用会清除应用所有数据!\n确定要卸载?\n确定要卸载?\n确定要卸载?',this);"/><i class="fa fa-trash-o"></i> Удалить</a>
                      <?php }else{?>
                      <?php }?>
                    <?php }?>
                    </td>
                </tr>
                <?php }  ?>
              </tbody>
              <tr>
                <td colspan="7">
                  <div class="pagination pagination-right" style="float:right;"><?php echo iPagination::$pagenav ; ?></div>
<!--                   <div class="input-prepend input-append mt20">
                    <span class="add-on"> Выбрать все
                      <input type="checkbox" class="checkAll checkbox" data-target="#<?php echo APP_BOXID;?>" />
                    </span>
                    <div class="btn-group dropup" id="iCMS-batch">
                      <a class="btn dropdown-toggle" data-toggle="dropdown" tabindex="-1"><i class="fa fa-wrench"></i> Пакетные операции</a>
                      <a class="btn dropdown-toggle" data-toggle="dropdown" tabindex="-1"> <span class="caret"></span></a>
                      <ul class="dropdown-menu">
                        <li><a data-toggle="batch" data-action="dels"><i class="fa fa-trash-o"></i> Удалить</a></li>
                      </ul>
                    </div>
                  </div> -->
                </td>
              </tr>
            </table>
          </div>
          <?php }?>
        </div>
      </form>
    </div>
  </div>
</div>
<div id="local_app_wrap" style="display:none;">
  <form action="<?php echo APP_FURI; ?>&do=local_app" method="post" enctype="multipart/form-data" target="iPHP_FRAME">
    <div class="alert alert-info">
      由于安全限制<br />
      请先把iCMS应用安装包文件(.<?php echo apps::PKG_EXT; ?>)<br />
      上传到网站根目录下
    </div>
    <div class="clearfloat mb10"></div>
    <div class="input-prepend">
      <span class="add-on">Доступные пакеты приложений</span>
      <select name="zipfile" class="chosen-select span4" data-placeholder="Выбрать файл установочного пакета приложения (.zip)...">
        <?php
          foreach(glob(iPATH."iCMS.APP.*-v*.*.*.".apps::PKG_EXT) as $value){
            $name = str_replace(iPATH, '', $value);
        ?>
        <option value="<?php echo $name;?>"><?php echo $name;?></option>
        <?php } ?>
      </select>
    </div>
    <div class="clearfloat mb10"></div>
    <button class="btn btn-primary btn-large btn-block" type="submit"><i class="fa fa-check"></i> Установка </button>
    <div class="clearfloat mb10"></div>
  </form>
</div>
<script>
$("li","#apps-tab").click(function(event) {
  // console.log($(this).attr('class'));
  iCMS.setcookie('apps_tab',$(this).attr('class'));
});
var appstab = iCMS.getcookie('apps_tab');
  // console.log(appstab);
if(appstab){
  $('.'+appstab).addClass('active');
  $('#'+appstab).addClass('active');
}else{
  $("#apps-tab li:eq(0)").addClass('active');
  $(".tab-content div:eq(0)").addClass('active');
}

</script>
<?php admincp::foot();?>
