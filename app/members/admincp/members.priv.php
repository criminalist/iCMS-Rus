<?php

defined('iPHP') OR exit('Oops, something went wrong');
?>
<style>.apriv .add-on{display: block;float: left;}</style>
<link rel="stylesheet" href="./app/admincp/ui/jquery/treeview-0.1.0.css" type="text/css" />
<script type="text/javascript" src="./app/admincp/ui/template-3.0.js"></script>
<script type="text/javascript" src="./app/admincp/ui/jquery/treeview-0.1.0.js"></script>
<script type="text/javascript" src="./app/admincp/ui/jquery/treeview-0.1.0.async.js"></script>
<script id="cpriv_item" type="text/html">
<div class="input-prepend input-append">
    <span class="add-on">APPID:{{appid}}</span>
    <span class="add-on">CID:{{cid}}</span>
    <span class="add-on"><b>{{name}}</b></span>
    <span class="add-on"><input type="checkbox" name="config[cpriv][]" value="{{cid}}:s"> 查询</span>
    <span class="add-on"><input type="checkbox" name="config[cpriv][]" value="{{cid}}:a" /> 添加子级</span>
    <span class="add-on"><input type="checkbox" name="config[cpriv][]" value="{{cid}}:e" /> Редактировать </span>
    <span class="add-on"><input type="checkbox" name="config[cpriv][]" value="{{cid}}:d" /> Удалить</span>
</div>
<div class="input-prepend input-append">
    <span class="add-on">Права на контент</span>
    <span class="add-on"><input type="checkbox" name="config[cpriv][]" value="{{cid}}:cs" /> 查询</span>
    <span class="add-on"><input type="checkbox" name="config[cpriv][]" value="{{cid}}:ca" />Добавить</span>
    <span class="add-on"><input type="checkbox" name="config[cpriv][]" value="{{cid}}:ce" /> Редактировать </span>
    <span class="add-on"><input type="checkbox" name="config[cpriv][]" value="{{cid}}:cd" /> Удалить</span>
</div>
</script>
<script id="mpriv_item" type="text/html">
<div class="input-prepend input-append">
  <span class="add-on"><input type="checkbox" name="config[mpriv][]" value="{{priv}}"></span>
  {{if caption=='-'}}
  <span class="add-on tip" title="Разделитель разрешений, только интерфейс красивый">────────────</span>
  {{else}}
  <span class="add-on">{{caption}}</span>
  {{/if}}
</div>
</script>
<script type="text/javascript">
$(function(){
  var mpriv  = <?php echo json_encode($rs->config['mpriv']);?>,cpriv = <?php echo json_encode($rs->config['cpriv']);?>;
  get_tree('<?php echo __ADMINCP__;?>=menu&do=ajaxtree&expanded=1','mpriv',
    function(html){
      set_select(mpriv,html)
    }
  );
  get_tree('<?php echo __ADMINCP__;?>=category&do=ajaxtree&expanded=0','cpriv',
    function(html){
      set_select(cpriv,html)
    }
  );
  set_select(mpriv,'#<?php echo admincp::$APP_NAME;?>-mpriv');
  set_select(cpriv,'#<?php echo admincp::$APP_NAME;?>-cpriv');
  set_select(<?php echo json_encode($rs->config['apriv']);?>,'#<?php echo admincp::$APP_NAME;?>-apriv');

});
function get_tree(url,e,callback){
  return $("#"+e+"_tree").treeview({
      tpl:e+'_item',
      url:url,
      callback:callback,
      collapsed: false,
      animated: "medium",
      control:"#"+e+"_treecontrol"
  });
}
function set_select(vars,el){
    if(!vars) return;

    $.each(vars, function(i,val){
      var input = $('input[value="'+val+'"]',$(el));
      input.prop("checked", true)
      $.uniform.update(input);
    });
}
</script>
<style>
.separator .checker{margin-top: -20px !important;}
</style>
<div id="<?php echo admincp::$APP_NAME;?>-mpriv" class="tab-pane hide">
  <div class="input-prepend input-append">
    <span class="add-on"> Выбрать все</span>
    <span class="add-on">
      <input type="checkbox" class="checkAll checkbox" data-target="#<?php echo admincp::$APP_NAME;?>-mpriv"/>
    </span>
    <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i> Сохранить</button>
  </div>
  <div class="clearfloat mb10"></div>
  <div class="input-prepend input-append">
    <span class="add-on"><i class="fa fa-cog"></i> Глобальные права</span>
    <span class="add-on">::</span>
    <span class="add-on"><input type="checkbox" name="config[mpriv][]" value="ADMINCP" /> Разрешить вход в панель управления</span>
  </div>
  <div class="clearfloat mb10"></div>
  <span class="alert alert-danger">В случае если группе выдаются права к конкретному приложению, нужно обратить внимания на доступ к панели управления</span>
  <div class="clearfloat mb10"></div>
  <div id="mpriv_treecontrol">
    <a style="display:none;"></a>
    <a style="display:none;"></a>
    <a class="btn btn-info" href="javascript:;">Развернуть / Свернуть</a>
  </div>
  <ul id="mpriv_tree">

  </ul>
</div>
<div id="<?php echo admincp::$APP_NAME;?>-cpriv" class="tab-pane hide">
  <div class="input-prepend input-append">
    <span class="add-on"> Выбрать все</span>
    <span class="add-on">
      <input type="checkbox" class="checkAll checkbox" data-target="#<?php echo admincp::$APP_NAME;?>-cpriv"/>
    </span>
    <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i> Сохранить</button>
  </div>
  <div class="clearfloat mb10"></div>
  <div class="input-prepend input-append">
    <span class="add-on"><i class="fa fa-cog"></i> Глобальные права</span>
    <span class="add-on">::</span>
    <span class="add-on">Разрешить добавлять категории в корень</span>
    <span class="add-on"><input type="checkbox" name="config[cpriv][]" value="0:a" /></span>
  </div>
  <div class="clearfloat mb10"></div>
  <div id="cpriv_treecontrol">
    <a style="display:none;"></a>
    <a style="display:none;"></a>
    <a class="btn btn-info" href="javascript:;">Развернуть / Свернуть</a>
  </div>
  <ul id="cpriv_tree">

  </ul>
</div>
<div id="<?php echo admincp::$APP_NAME;?>-apriv" class="tab-pane hide apriv">
  <div class="input-prepend input-append">
    <span class="add-on"> Выбрать все</span>
    <span class="add-on">
      <input type="checkbox" class="checkAll checkbox" data-target="#<?php echo admincp::$APP_NAME;?>-apriv"/>
    </span>
    <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i> Сохранить</button>
  </div>
  <div class="clearfloat"></div>
  <table class="table table-bordered table-condensed table-hover">
    <thead>
      <tr>
        <th><i class="fa fa-arrows-v"></i></th>
        <th>appid</th>
        <th>Приложение</th>
        <th>Приложение</th>
        <th>Права доступа</th>
        <th>Дополнительные разрешения</th>
      </tr>
    </thead>
    <tbody>
    <?php echo apps_hook::get_app_priv();?>
    </tbody>
  </table>
</div>
