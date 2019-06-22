<?php

defined('iPHP') OR exit('Oops, something went wrong');
admincp::head();
?>
<script type="text/javascript">

$(function(){
  $(document).on("click",".del_device",function(){
      $(this).parent().parent().parent().remove();
  });
  $(".add_template_device").click(function(){
    var TD  = $("#template_device");
    var length = parseInt($("tr:last",TD).attr('data-key'))+1;
    var tdc = $(".template_device_clone tr").clone(true);
    if(!length) length = 0;
    tdc.attr('data-key', length);

    $('input',tdc).each(function(){
      this.id   = this.id.replace("{key}",length);
      if(this.name) this.name = this.name.replace("{key}",length);
    });

    $('.files_modal',tdc).each(function(index, el) {
      var href = $(this).attr("href").replace("{key}",length);
      $(this).attr("href",href);
    });

    tdc.appendTo(TD);
    return false;
  });
});
function modal_tplfile(el,a){
  if(!el) return;
  if(!a.checked) return;

  var e   = $('#'+el)||$('.'+el);
  var def = $("#template_desktop_tpl").val();
  var val = a.value.replace(def+'/', "{iTPL}/");
  e.val(val);
  return 'off';
}
function modal_tpl_index(el,a){
  if(!el) return;
  if(!a.checked) return;

  var e = $('#'+el)||$('.'+el),
  p = e.parent().parent(),
  pid = p.attr('id'),
  dir = $('#'+pid+'_tpl').val(),
  val = a.value.replace(dir+'/', "{iTPL}/");
  e.val(val);
  return 'off';
}
</script>

<div class="iCMS-container">
  <div class="widget-box">
    <div class="widget-title"> <span class="icon"> <i class="fa fa-cog"></i> </span>
      <ul class="nav nav-tabs" id="config-tab">
        <li class="active"><a href="#config-base" data-toggle="tab">Основная информация</a></li>
        <li><a href="#config-debug" data-toggle="tab">Отладка</a></li>
        <li><a href="#config-tpl" data-toggle="tab">Шаблон</a></li>
        <li><a href="#config-url" data-toggle="tab">URL</a></li>
        <li><a href="#config-cache" data-toggle="tab">Кэш</a></li>
        <li><a href="#config-file" data-toggle="tab">Вложения</a></li>
        <li><a href="#config-thumb" data-toggle="tab">Иконка</a></li>
        <li><a href="#config-watermark" data-toggle="tab">Водяной знак</a></li>
        <li><a href="#config-time" data-toggle="tab">Даты и время</a></li>        
        <li><a href="#config-patch" data-toggle="tab">Обновления</a></li>
        <li><a href="#config-grade" data-toggle="tab">Sphinx</a></li>
        <li><a href="#config-mail" data-toggle="tab"> Почта </a></li>
        <li><a href="#apps-metadata" data-toggle="tab">Свойства</a></li>
        <li><a href="#config-other" data-toggle="tab">Разное</a></li>
        <li><a href="#config-ext" data-toggle="tab">Расширение</a></li>
      </ul>
    </div>
    <div class="widget-content nopadding iCMS-config">
      <form action="<?php echo APP_FURI; ?>&do=save" method="post" class="form-inline" id="iCMS-config" target="iPHP_FRAME">
        <div id="config" class="tab-content">
          <div id="config-base" class="tab-pane active">
            <div class="input-prepend"> <span class="add-on">Название сайта</span>
              <input type="text" name="config[site][name]" class="span6" id="name" value="<?php echo $config['site']['name'] ; ?>"/>
            </div>
            <span class="help-inline">Например: Мой лучший блог</span>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"> <span class="add-on">SEO заголовок</span>
              <input type="text" name="config[site][seotitle]" class="span6" id="seotitle" value="<?php echo $config['site']['seotitle'] ; ?>"/>
            </div>
            <span class="help-inline">SEO title — или SEO заголовок страницы размещается между HTML Тегами <title></title> — это заголовок страницы, название страницы и один из самых важных элментов как с точки зрения SEO оптимизации так и с точки зрения оптимизации конверсии</span>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"><span class="add-on">SEO Ключевые слова</span>
              <textarea name="config[site][keywords]" id="keywords" class="span6" style="height: 90px;"><?php echo $config['site']['keywords'] ; ?></textarea>
            </div>
            <span class="help-inline">Ключевые слова сайта, разделенные запятой ","</span>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"><span class="add-on">SEO Описание</span>
              <textarea name="config[site][description]" id="description" class="span6" style="height: 90px;"><?php echo $config['site']['description'] ; ?></textarea>
            </div>
            <span class="help-inline">SEO Описание, будет использоваться для описание вашего сайта в сниппете поисковой выдаче, в основном на главной страницы.</span>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"><span class="add-on">Код статистики</span>
              <textarea name="config[site][code]" id="site_code" class="span6" style="height: 100px;"><?php echo $config['site']['code'] ; ?></textarea>
            </div>
            <span class="help-inline">В поле можно разместить код счетчиков, например яндекс метрику, liveinternet и другие, переменная выводиться в нижней части сайта, а также различные подключаемые скрипты, например онлайн чат.</span>
            <div class="clearfloat mb10"></div>
            <!--div class="input-prepend"> <span class="add-on">Лицензия сайта</span>
              <input type="text" name="config[site][icp]" class="span3" id="title" value="<?php echo $config['site']['icp'] ; ?>"/>
            </div>
            <span class="help-inline"></span-->
            <hr>
            <div class="metadata">

<button class="btn btn-inverse add_meta" type="button"><i class="fa fa-plus-circle"></i> Добавить пользовательские настройки веб-сайта</button>
<table class="table table-hover">
  <thead>
    <tr>
      <th>Имя</th>
      <th>Поле<span class="label label-important">Может состоять только из английских букв, цифр или символов _-</span></th>
      <th>Значение</th>
    </tr>
  </thead>
  <tbody>
    <?php
      $site_md_index  = 0;
      $site_meta = $config['site']['meta'];
    ?>
    <?php if($site_meta)foreach((array)$site_meta AS $mdkey=>$mdata){?>
    <tr>
      <td>
        <input name="config[site][meta][<?php echo $site_md_index;?>][name]" type="text" value="<?php echo $mdata['name'];?>" class="span3" />
        <span class="help-block">Тег шаблона:&lt;!--{$site.meta[<?php echo $site_md_index;?>].name}--&gt;</span>
      </td>
      <td>
        <input name="config[site][meta][<?php echo $site_md_index;?>][key]" type="text" value="<?php echo $mdata['key'];?>" class="span3" />
        <span class="help-block">&lt;!--{$site.meta[<?php echo $site_md_index;?>].key}--&gt;</span>
      </td>
      <td><input name="config[site][meta][<?php echo $site_md_index;?>][value]" type="text" value="<?php echo $mdata['value'];?>" class="span6" />
        <button class="btn btn-small btn-danger del_meta" type="button"><i class="fa fa-trash-o"></i> Удалить</button>
        <span class="help-block">&lt;!--{$site.meta[<?php echo $site_md_index;?>].value}--&gt;</span>
      </td>
    </tr>
    <?php ++$site_md_index;}?>
  </tbody>
  <tfoot>
    <tr class="hide meta_clone">
      <td><input name="config[site][meta][{key}][name]" type="text" disabled="disabled" class="span3" /></td>
      <td><input name="config[site][meta][{key}][key]" type="text" disabled="disabled" class="span3" /></td>
      <td><input name="config[site][meta][{key}][value]" type="text" disabled="disabled" class="span6"  />
        <button class="btn btn-small btn-danger del_meta" type="button"><i class="fa fa-trash-o"></i> Удалить</button>
      </td>
    </tr>
  </tfoot>
</table>
            </div>
          </div>
          <div id="config-debug" class="tab-pane">
            <div class="input-prepend"> <span class="add-on">Включить отладчик</span>
              <div class="switch">
                <input type="checkbox" data-type="switch" name="config[debug][php]" id="debug_php" <?php echo $config['debug']['php']?'checked':''; ?>/>
              </div>
            </div>
            <span class="help-inline">При возникновении ошибок в работе сайта, можно включить отладку, которая поможет отловить ошибку и вывести в системном сообщении подробности.<a onclick="javscript:$('.debug_php_trace,.debug_access_log').toggle();"> Подробнее </a></span>
            <div class="<?php echo $config['debug']['php_trace']?'':'hide'; ?> debug_php_trace">
              <div class="clearfloat mb10"></div>
              <div class="input-prepend"> <span class="add-on">Отладочная информация</span>
                <div class="switch">
                  <input type="checkbox" data-type="switch" name="config[debug][php_trace]" id="debug_php_trace" <?php echo $config['debug']['php_trace']?'checked':''; ?>/>
                </div>
              </div>
              <span class="help-inline">Показать информацию об отладке программы</span>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="<?php echo $config['debug']['access_log']?'':'hide'; ?> debug_access_log">
              <div class="clearfloat mb10"></div>
              <div class="input-prepend"> <span class="add-on">Журнал операций</span>
                <div class="switch" data-off-label="Вкл" data-on-label="Выкл">
                  <input type="checkbox" data-type="switch" name="config[debug][access_log]" id="debug_access_log" <?php echo $config['debug']['access_log']?'checked':''; ?>/>
                </div>
              </div>
              <span class="help-inline">По умолчанию включен</span>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"> <span class="add-on">Шаблон</span>
              <div class="switch">
                <input type="checkbox" data-type="switch" name="config[debug][tpl]" id="debug_tpl" <?php echo $config['debug']['tpl']?'checked':''; ?>/>
              </div>
            </div>
            <span class="help-inline">Сообщение об ошибке шаблона! Если веб-сайт пустой или неполный, вы можете открыть этот элемент, чтобы устранить ошибку! Его также можно включить при настройке шаблона. <a onclick="javscript:$('.debug_tpl_trace').toggle();"> Подробнее </a></span>
            <div class="<?php echo $config['debug']['tpl_trace']?'':'hide'; ?> debug_tpl_trace">
              <div class="clearfloat mb10"></div>
              <div class="input-prepend"> <span class="add-on">Шаблон отладочной информации</span>
                <div class="switch">
                  <input type="checkbox" data-type="switch" name="config[debug][tpl_trace]" id="debug_tpl_trace" <?php echo $config['debug']['tpl_trace']?'checked':''; ?>/>
                </div>
              </div>
              <span class="help-inline">模板所有数据调试信息</span>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"> <span class="add-on">Отладочной информация</span>
              <div class="switch">
                <input type="checkbox" data-type="switch" name="config[debug][db]" id="debug_db" <?php echo $config['debug']['db']?'checked':''; ?>/>
              </div>
            </div>
            <span class="help-inline">开启后将显示所有数据库错误信息. <a onclick="javscript:$('.debug_db_trace,.debug_db_explain').toggle();"> Подробнее </a></span>
            <div class="<?php echo $config['debug']['db_trace']?'':'hide'; ?> debug_db_trace">
              <div class="clearfloat mb10"></div>
              <div class="input-prepend"> <span class="add-on">SQL отладочная информация</span>
                <div class="switch">
                  <input type="checkbox" data-type="switch" name="config[debug][db_trace]" id="debug_db_trace" <?php echo $config['debug']['db_trace']?'checked':''; ?>/>
                </div>
              </div>
              <span class="help-inline">开启后将显示所有SQL执行情况</span>
            </div>
            <div class="<?php echo $config['debug']['db_explain']?'':'hide'; ?> debug_db_explain">
              <div class="clearfloat mb10"></div>
              <div class="input-prepend"> <span class="add-on">SQL EXPLAIN</span>
                <div class="switch">
                  <input type="checkbox" data-type="switch" name="config[debug][db_explain]" id="debug_db_explain" <?php echo $config['debug']['db_explain']?'checked':''; ?>/>
                </div>
              </div>
              <span class="help-inline">开启后将显示 SQL EXPLAIN 信息</span>
            </div>
            <hr />
            <h3 class="title">Экспериментальные функции</h3>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend">
              <span class="add-on">Оптимизация</span>
              <div class="switch">
                <input type="checkbox" data-type="switch" name="config[debug][db_optimize_in]" id="debug_db_optimize_in" <?php echo $config['debug']['db_optimize_in']?'checked':''; ?>/>
              </div>
            </div>
            <span class="help-inline">После включения функции производительность iCMS:article:list значительно улучшится. Если объем данных статьи большой, нагрузка на сервер высокая, попробуйте включить.</span>
          </div>
          <div id="config-tpl" class="tab-pane hide">
            <div class="input-prepend"> <span class="add-on">Статическая главная страница</span>
              <div class="switch">
                <input type="checkbox" data-type="switch" name="config[template][index][mode]" id="index_mode" <?php echo $config['template']['index']['mode']?'checked':''; ?>/>
              </div>
            </div>
            <span class="help-inline">После включения опции, главная страница будет генерироваться как статический HTML файл, запросы к базе данных значительно сократятся.</span>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"> <span class="add-on">REWRITE главной страницы</span>
              <div class="switch">
                <input type="checkbox" data-type="switch" name="config[template][index][rewrite]" id="index_rewrite" <?php echo $config['template']['index']['rewrite']?'checked':''; ?>/>
              </div>
            </div>
            <span class="help-inline">Если домашняя страница не находится в режиме динамического доступа и на главной странице веб-сайта есть страница, откройте этот элемент.</span>
            <div class="clearfloat mb10 solid"></div>
            <input type="hidden" name="config[template][index][tpl]" value="<?php echo $config['template']['index']['tpl']?:$config['template']['desktop']['index']; ?>"/>
            <input type="hidden" name="config[template][index][name]" value="<?php echo $config['template']['index']['name']?:'index' ; ?>"/>
            <div id="template_desktop">
              <input type="hidden" name="config[template][desktop][name]" value="desktop"/>
              <input type="hidden" name="config[template][desktop][domain]" value="<?php echo $config['router']['url'] ; ?>"/>
              <div class="input-prepend"> <span class="add-on">Доменное имя PC версии сайта</span>
                <input type="text" name="config[router][url]" class="span3" value="<?php echo $config['router']['url'] ; ?>"/>
              </div>
              <span class="help-inline">Пример:<span class="label label-info">https://www.icmsdev.com</span></span>
              <div class="clearfloat mb10"></div>
              <div class="input-prepend input-append"> <span class="add-on">桌面端模板</span>
                <input type="text" name="config[template][desktop][tpl]" class="span3" id="template_desktop_tpl" value="<?php echo $config['template']['desktop']['tpl'] ; ?>"/>
                <?php echo filesAdmincp::modal_btn('Шаблон','template_desktop_tpl','dir');?>
              </div>
              <span class="help-inline">网站桌面端模板默认模板</span>
              <div class="clearfloat mb10"></div>
              <div class="input-prepend input-append"> <span class="add-on">首页模板</span>
                <input type="text" name="config[template][desktop][index]" class="span3" id="template_desktop_index" value="<?php echo $config['template']['desktop']['index']?:'{iTPL}/index.htm' ; ?>"/>
                <?php echo filesAdmincp::modal_btn('Шаблон','template_desktop_index','file','tpl_index');?>
              </div>
              <span class="help-inline">桌面端默认模板</span>
            </div>
            <div class="clearfloat mb10 solid"></div>
            <div id="template_mobile">
              <input type="hidden" name="config[template][desktop][name]" value="mobile"/>
              <div class="input-prepend">
                <span class="add-on">移动端识别</span>
                <input type="text" name="config[template][mobile][agent]" class="span3" id="template_mobile_agent" value="<?php echo $config['template']['mobile']['agent'] ; ?>"/>
              </div>
              <span class="help-inline">请用<span class="label label-info">,</span>分隔 如不启用自动识别请留空</span>
              <div class="clearfloat mb10"></div>
              <div class="input-prepend"> <span class="add-on">Доменное имя мобильной версии сайта</span>
                <input type="text" name="config[template][mobile][domain]" class="span3" id="template_mobile_domain" value="<?php echo $config['template']['mobile']['domain'] ; ?>"/>
              </div>
              <span class="help-inline">Пример:<span class="label label-info">http://m.icmsdev.com</span></span>
              <div class="clearfloat mb10"></div>
              <div class="input-prepend input-append"> <span class="add-on">移动端模板</span>
                <input type="text" name="config[template][mobile][tpl]" class="span3" id="template_mobile_tpl" value="<?php echo $config['template']['mobile']['tpl'] ; ?>"/>
                <?php echo filesAdmincp::modal_btn('Шаблон','template_mobile_tpl','dir');?>
              </div>
              <span class="help-inline">网站移动端模板默认模板,如果不想让程序自行切换请留空</span>
              <div class="clearfloat mb10"></div>
              <div class="input-prepend input-append"> <span class="add-on">首页模板</span>
                <input type="text" name="config[template][mobile][index]" class="span3" id="template_mobile_index" value="<?php echo $config['template']['mobile']['index']?:'{iTPL}/index.htm'; ?>"/>
                <?php echo filesAdmincp::modal_btn('Шаблон','template_mobile_index','file','tpl_index');?>
              </div>
              <span class="help-inline">移动端首页默认模板</span>
            </div>
            <div class="clearfloat mb10 solid"></div>
            <div class="<?php echo $config['router']['redirect']?'':'hide'; ?> router_redirect">
              <div class="input-prepend"> <span class="add-on">Редирект</span>
                <div class="switch">
                  <input type="checkbox" data-type="switch" name="config[router][redirect]" id="router_redirect" <?php echo $config['router']['redirect']?'checked':''; ?>/>
                </div>
              </div>
              <span class="help-inline">如果出现循环重定向(跳转)或者已在服务器配置做重定向,请关闭此项.</span>
              <div class="clearfloat mb10 solid"></div>
            </div>
            <table class="table table-hover">
              <thead>
                <tr>
                  <th style="text-align:left;">
                    <span class="label label-important fs16">模板优先级为:设备模板 &gt; 移动端模板 &gt; PC端模板</span>
                    <span class="label label-inverse fs16"><i class="icon-warning-sign icon-white"></i> 设备模板和移动端模板 暂时不支持生成静态模式</span>
                    <a onclick="javscript:$('.router_redirect').toggle();">Редирект</a>
                  </th>
                </tr>
              </thead>
              <tbody id="template_device">
<?php
function template_device_td($key,$device=array()){
  $td_key = "device_{$key}";
?>
  <td>
    <div class="input-prepend input-append"> <span class="add-on">设备名称</span>
      <input type="text" name="config[template][device][<?php echo $key;?>][name]" class="span3" id="<?php echo $td_key;?>_name" value="<?php echo $device['name'];?>"/>
      <a class="btn del_device"><i class="fa fa-trash-o"></i> Удалить</a>
    </div>
    <span class="help-inline"></span>
    <div class="clearfloat mb10"></div>
    <div class="input-prepend"> <span class="add-on">设备识别符</span>
      <input type="text" name="config[template][device][<?php echo $key;?>][ua]" class="span3" id="<?php echo $td_key;?>_ua" value="<?php echo $device['ua'];?>"/>
    </div>
    <span class="help-inline">设备唯一识别符,识别设备的User agent,如果多个请用<span class="label label-info">,</span>分隔.</span>
    <div class="clearfloat mb10"></div>
    <div class="input-prepend"> <span class="add-on">访问域名</span>
      <input type="text" name="config[template][device][<?php echo $key;?>][domain]" class="span3" id="<?php echo $td_key;?>_domain" value="<?php echo $device['domain'];?>"/>
    </div>
    <span class="help-inline"></span>
    <div class="clearfloat mb10"></div>
    <div class="input-prepend input-append"> <span class="add-on">设备模板</span>
      <input type="text" name="config[template][device][<?php echo $key;?>][tpl]" class="span3" id="<?php echo $td_key;?>_tpl" value="<?php echo $device['tpl'];?>"/>
      <?php echo filesAdmincp::modal_btn('Шаблон',"<?php echo $td_key;?>_tpl",'dir');?>
    </div>
    <span class="help-inline">识别到的设备会使用这个模板设置</span>
    <div class="clearfloat mb10"></div>
    <div class="input-prepend input-append"> <span class="add-on">首页模板</span>
      <input type="text" name="config[template][device][<?php echo $key;?>][index]" class="span3" id="<?php echo $td_key;?>_index" value="<?php echo $device['index']?:'{iTPL}/index.htm';?>"/>
      <?php echo filesAdmincp::modal_btn('Шаблон',"<?php echo $td_key;?>_index",'file','tpl_index');?>
    </div>
    <span class="help-inline">设备的首页模板</span>
  </td>
<?php }?>
                <?php foreach ((array)$config['template']['device'] as $key => $device) {?>
                <?php echo '<tr data-key="'.$key.'">';?>
                <?php echo template_device_td($key,$device);?>
                <?php echo '</tr>';?>
                <?php }?>
              </tbody>
              <tfoot>
              <tr>
                <td colspan="2"><a href="#template_device" class="btn add_template_device btn-success"/><i class="fa fa-tablet"></i>Добавить шаблон устройства</a></td>
              </tr>
              </tfoot>
            </table>
          </div>
          <div id="config-url" class="tab-pane hide">
            <div class="input-prepend"> <span class="add-on">URL страницы 404</span>
              <input type="text" name="config[router][404]" class="span4" id="router_404" value="<?php echo $config['router']['404'] ; ?>"/>
            </div>
            <span class="help-inline"></span>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"> <span class="add-on">URL общедоступного каталога</span>
              <input type="text" name="config[router][public]" class="span4" id="router_public" value="<?php echo $config['router']['public'] ; ?>"/>
            </div>
            <span class="help-inline">В общедоступном каталоге обычно хранятся ресурсы открытые для свободного доступа, например картинки в новостях, JS скрипты, CSS стили и так далее.</span>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"> <span class="add-on">URL пользователя</span>
              <input type="text" name="config[router][user]" class="span4" id="router_user" value="<?php echo $config['router']['user'] ; ?>"/>
            </div>
            <span class="help-inline">URL личного кабинета пользователя</span>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"> <span class="add-on">Корневой каталог</span>
              <input type="text" name="config[router][dir]" class="span4" id="router_dir" value="<?php echo $config['router']['dir'] ; ?>"/>
            </div>
            <span class="help-inline">По умолчанию "/"</span>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"> <span class="add-on">Суффикс</span>
              <input type="text" name="config[router][ext]" class="span4" id="router_ext" value="<?php echo $config['router']['ext'] ; ?>"/>
            </div>
            <span class="help-inline">{EXT} Рекомендуем использовать .html</span>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"> <span class="add-on">Скорость генерации</span>
              <input type="text" name="config[router][speed]" class="span4" id="router_speed" value="<?php echo $config['router']['speed'] ; ?>"/>
            </div>
            <span class="help-inline">Количество статических страниц генерируемых сервером за один раз, на более слабых системах можно уменьшить число, тем самым уменьшить наггрузку на сервер.</span>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"> <span class="add-on">REWRITE</span>
              <div class="switch">
                <input type="checkbox" data-type="switch" name="config[router][rewrite]" id="router_rewrite" <?php echo $config['router']['rewrite']?'checked':''; ?>/>
              </div>
            </div>
            <a class="btn btn-small btn-success" href="https://www.icmsdev.com/docs/rewrite.html" target="_blank"><i class="fa fa-question-circle"></i> Документация</a>
            <span class="help-inline">Эта опция работает только для конфигурации маршрутизации приложения</span>
          </div>
          <div id="config-cache" class="tab-pane hide">
            <?php include admincp::view("cache.config","cache");?>
          </div>
          <div id="config-file" class="tab-pane hide">
            <?php include admincp::view("files.config","files");?>
          </div>
          <div id="config-thumb" class="tab-pane hide">
            <?php include admincp::view("thumb.config","files");?>
          </div>
          <div id="config-watermark" class="tab-pane hide">
            <?php include admincp::view("watermark.config","files");?>
          </div>
          <div id="config-time" class="tab-pane hide">
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"> <span class="add-on">Часовой пояс</span>
              <select name="config[time][zone]" id="time_zone" class="span4 chosen-select">
				  
<option disabled selected style='display:none;'>Часовой пояс...</option>

	<optgroup label="US (Common)">	
		<option value="America/Puerto_Rico">Puerto Rico (Atlantic)</option>
		<option value="America/New_York">New York (Eastern)</option>
		<option value="America/Chicago">Chicago (Central)</option>
		<option value="America/Denver">Denver (Mountain)</option>
		<option value="America/Phoenix">Phoenix (MST)</option>
		<option value="America/Los_Angeles">Los Angeles (Pacific)</option>
		<option value="America/Anchorage">Anchorage (Alaska)</option>
		<option value="Pacific/Honolulu">Honolulu (Hawaii)</option>
	</optgroup>

	<optgroup label="America">
		<option value="America/Adak">Adak</option>
		<option value="America/Anchorage">Anchorage</option>
		<option value="America/Anguilla">Anguilla</option>
		<option value="America/Antigua">Antigua</option>
		<option value="America/Araguaina">Araguaina</option>
		<option value="America/Argentina/Buenos_Aires">Argentina - Buenos Aires</option>
		<option value="America/Argentina/Catamarca">Argentina - Catamarca</option>
		<option value="America/Argentina/ComodRivadavia">Argentina - ComodRivadavia</option>
		<option value="America/Argentina/Cordoba">Argentina - Cordoba</option>
		<option value="America/Argentina/Jujuy">Argentina - Jujuy</option>
		<option value="America/Argentina/La_Rioja">Argentina - La Rioja</option>
		<option value="America/Argentina/Mendoza">Argentina - Mendoza</option>
		<option value="America/Argentina/Rio_Gallegos">Argentina - Rio Gallegos</option>
		<option value="America/Argentina/Salta">Argentina - Salta</option>
		<option value="America/Argentina/San_Juan">Argentina - San Juan</option>
		<option value="America/Argentina/San_Luis">Argentina - San Luis</option>
		<option value="America/Argentina/Tucuman">Argentina - Tucuman</option>
		<option value="America/Argentina/Ushuaia">Argentina - Ushuaia</option>
		<option value="America/Aruba">Aruba</option>
		<option value="America/Asuncion">Asuncion</option>
		<option value="America/Atikokan">Atikokan</option>
		<option value="America/Atka">Atka</option>
		<option value="America/Bahia">Bahia</option>
		<option value="America/Barbados">Barbados</option>
		<option value="America/Belem">Belem</option>
		<option value="America/Belize">Belize</option>
		<option value="America/Blanc-Sablon">Blanc-Sablon</option>
		<option value="America/Boa_Vista">Boa Vista</option>
		<option value="America/Bogota">Bogota</option>
		<option value="America/Boise">Boise</option>
		<option value="America/Buenos_Aires">Buenos Aires</option>
		<option value="America/Cambridge_Bay">Cambridge Bay</option>
		<option value="America/Campo_Grande">Campo Grande</option>
		<option value="America/Cancun">Cancun</option>
		<option value="America/Caracas">Caracas</option>
		<option value="America/Catamarca">Catamarca</option>
		<option value="America/Cayenne">Cayenne</option>
		<option value="America/Cayman">Cayman</option>
		<option value="America/Chicago">Chicago</option>
		<option value="America/Chihuahua">Chihuahua</option>
		<option value="America/Coral_Harbour">Coral Harbour</option>
		<option value="America/Cordoba">Cordoba</option>
		<option value="America/Costa_Rica">Costa Rica</option>
		<option value="America/Cuiaba">Cuiaba</option>
		<option value="America/Curacao">Curacao</option>
		<option value="America/Danmarkshavn">Danmarkshavn</option>
		<option value="America/Dawson">Dawson</option>
		<option value="America/Dawson_Creek">Dawson Creek</option>
		<option value="America/Denver">Denver</option>
		<option value="America/Detroit">Detroit</option>
		<option value="America/Dominica">Dominica</option>
		<option value="America/Edmonton">Edmonton</option>
		<option value="America/Eirunepe">Eirunepe</option>
		<option value="America/El_Salvador">El Salvador</option>
		<option value="America/Ensenada">Ensenada</option>
		<option value="America/Fortaleza">Fortaleza</option>
		<option value="America/Fort_Wayne">Fort Wayne</option>
		<option value="America/Glace_Bay">Glace Bay</option>
		<option value="America/Godthab">Godthab</option>
		<option value="America/Goose_Bay">Goose Bay</option>
		<option value="America/Grand_Turk">Grand Turk</option>
		<option value="America/Grenada">Grenada</option>
		<option value="America/Guadeloupe">Guadeloupe</option>
		<option value="America/Guatemala">Guatemala</option>
		<option value="America/Guayaquil">Guayaquil</option>
		<option value="America/Guyana">Guyana</option>
		<option value="America/Halifax">Halifax</option>
		<option value="America/Havana">Havana</option>
		<option value="America/Hermosillo">Hermosillo</option>
		<option value="America/Indiana/Indianapolis">Indiana - Indianapolis</option>
		<option value="America/Indiana/Knox">Indiana - Knox</option>
		<option value="America/Indiana/Marengo">Indiana - Marengo</option>
		<option value="America/Indiana/Petersburg">Indiana - Petersburg</option>
		<option value="America/Indiana/Tell_City">Indiana - Tell City</option>
		<option value="America/Indiana/Vevay">Indiana - Vevay</option>
		<option value="America/Indiana/Vincennes">Indiana - Vincennes</option>
		<option value="America/Indiana/Winamac">Indiana - Winamac</option>
		<option value="America/Indianapolis">Indianapolis</option>
		<option value="America/Inuvik">Inuvik</option>
		<option value="America/Iqaluit">Iqaluit</option>
		<option value="America/Jamaica">Jamaica</option>
		<option value="America/Jujuy">Jujuy</option>
		<option value="America/Juneau">Juneau</option>
		<option value="America/Kentucky/Louisville">Kentucky - Louisville</option>
		<option value="America/Kentucky/Monticello">Kentucky - Monticello</option>
		<option value="America/Knox_IN">Knox IN</option>
		<option value="America/La_Paz">La Paz</option>
		<option value="America/Lima">Lima</option>
		<option value="America/Los_Angeles">Los Angeles</option>
		<option value="America/Louisville">Louisville</option>
		<option value="America/Maceio">Maceio</option>
		<option value="America/Managua">Managua</option>
		<option value="America/Manaus">Manaus</option>
		<option value="America/Marigot">Marigot</option>
		<option value="America/Martinique">Martinique</option>
		<option value="America/Matamoros">Matamoros</option>
		<option value="America/Mazatlan">Mazatlan</option>
		<option value="America/Mendoza">Mendoza</option>
		<option value="America/Menominee">Menominee</option>
		<option value="America/Merida">Merida</option>
		<option value="America/Mexico_City">Mexico City</option>
		<option value="America/Miquelon">Miquelon</option>
		<option value="America/Moncton">Moncton</option>
		<option value="America/Monterrey">Monterrey</option>
		<option value="America/Montevideo">Montevideo</option>
		<option value="America/Montreal">Montreal</option>
		<option value="America/Montserrat">Montserrat</option>
		<option value="America/Nassau">Nassau</option>
		<option value="America/New_York">New York</option>
		<option value="America/Nipigon">Nipigon</option>
		<option value="America/Nome">Nome</option>
		<option value="America/Noronha">Noronha</option>
		<option value="America/North_Dakota/Center">North Dakota - Center</option>
		<option value="America/North_Dakota/New_Salem">North Dakota - New Salem</option>
		<option value="America/Ojinaga">Ojinaga</option>
		<option value="America/Panama">Panama</option>
		<option value="America/Pangnirtung">Pangnirtung</option>
		<option value="America/Paramaribo">Paramaribo</option>
		<option value="America/Phoenix">Phoenix</option>
		<option value="America/Port-au-Prince">Port-au-Prince</option>
		<option value="America/Porto_Acre">Porto Acre</option>
		<option value="America/Port_of_Spain">Port of Spain</option>
		<option value="America/Porto_Velho">Porto Velho</option>
		<option value="America/Puerto_Rico">Puerto Rico</option>
		<option value="America/Rainy_River">Rainy River</option>
		<option value="America/Rankin_Inlet">Rankin Inlet</option>
		<option value="America/Recife">Recife</option>
		<option value="America/Regina">Regina</option>
		<option value="America/Resolute">Resolute</option>
		<option value="America/Rio_Branco">Rio Branco</option>
		<option value="America/Rosario">Rosario</option>
		<option value="America/Santa_Isabel">Santa Isabel</option>
		<option value="America/Santarem">Santarem</option>
		<option value="America/Santiago">Santiago</option>
		<option value="America/Santo_Domingo">Santo Domingo</option>
		<option value="America/Sao_Paulo">Sao Paulo</option>
		<option value="America/Scoresbysund">Scoresbysund</option>
		<option value="America/Shiprock">Shiprock</option>
		<option value="America/St_Barthelemy">St Barthelemy</option>
		<option value="America/St_Johns">St Johns</option>
		<option value="America/St_Kitts">St Kitts</option>
		<option value="America/St_Lucia">St Lucia</option>
		<option value="America/St_Thomas">St Thomas</option>
		<option value="America/St_Vincent">St Vincent</option>
		<option value="America/Swift_Current">Swift Current</option>
		<option value="America/Tegucigalpa">Tegucigalpa</option>
		<option value="America/Thule">Thule</option>
		<option value="America/Thunder_Bay">Thunder Bay</option>
		<option value="America/Tijuana">Tijuana</option>
		<option value="America/Toronto">Toronto</option>
		<option value="America/Tortola">Tortola</option>
		<option value="America/Vancouver">Vancouver</option>
		<option value="America/Virgin">Virgin</option>
		<option value="America/Whitehorse">Whitehorse</option>
		<option value="America/Winnipeg">Winnipeg</option>
		<option value="America/Yakutat">Yakutat</option>
		<option value="America/Yellowknife">Yellowknife</option>
	</optgroup>

	<optgroup label="Europe">
		<option value="Europe/Amsterdam">Amsterdam</option>
		<option value="Europe/Andorra">Andorra</option>
		<option value="Europe/Athens">Athens</option>
		<option value="Europe/Belfast">Belfast</option>
		<option value="Europe/Belgrade">Belgrade</option>
		<option value="Europe/Berlin">Berlin</option>
		<option value="Europe/Bratislava">Bratislava</option>
		<option value="Europe/Brussels">Brussels</option>
		<option value="Europe/Bucharest">Bucharest</option>
		<option value="Europe/Budapest">Budapest</option>
		<option value="Europe/Chisinau">Chisinau</option>
		<option value="Europe/Copenhagen">Copenhagen</option>
		<option value="Europe/Dublin">Dublin</option>
		<option value="Europe/Gibraltar">Gibraltar</option>
		<option value="Europe/Guernsey">Guernsey</option>
		<option value="Europe/Helsinki">Helsinki</option>
		<option value="Europe/Isle_of_Man">Isle of Man</option>
		<option value="Europe/Istanbul">Istanbul</option>
		<option value="Europe/Jersey">Jersey</option>
		<option value="Europe/Kaliningrad">Kaliningrad</option>
		<option value="Europe/Kiev">Kiev</option>
		<option value="Europe/Lisbon">Lisbon</option>
		<option value="Europe/Ljubljana">Ljubljana</option>
		<option value="Europe/London">London</option>
		<option value="Europe/Luxembourg">Luxembourg</option>
		<option value="Europe/Madrid">Madrid</option>
		<option value="Europe/Malta">Malta</option>
		<option value="Europe/Mariehamn">Mariehamn</option>
		<option value="Europe/Minsk">Minsk</option>
		<option value="Europe/Monaco">Monaco</option>
		<option value="Europe/Moscow">Moscow</option>
		<option value="Europe/Nicosia">Nicosia</option>
		<option value="Europe/Oslo">Oslo</option>
		<option value="Europe/Paris">Paris</option>
		<option value="Europe/Podgorica">Podgorica</option>
		<option value="Europe/Prague">Prague</option>
		<option value="Europe/Riga">Riga</option>
		<option value="Europe/Rome">Rome</option>
		<option value="Europe/Samara">Samara</option>
		<option value="Europe/San_Marino">San Marino</option>
		<option value="Europe/Sarajevo">Sarajevo</option>
		<option value="Europe/Simferopol">Simferopol</option>
		<option value="Europe/Skopje">Skopje</option>
		<option value="Europe/Sofia">Sofia</option>
		<option value="Europe/Stockholm">Stockholm</option>
		<option value="Europe/Tallinn">Tallinn</option>
		<option value="Europe/Tirane">Tirane</option>
		<option value="Europe/Tiraspol">Tiraspol</option>
		<option value="Europe/Uzhgorod">Uzhgorod</option>
		<option value="Europe/Vaduz">Vaduz</option>
		<option value="Europe/Vatican">Vatican</option>
		<option value="Europe/Vienna">Vienna</option>
		<option value="Europe/Vilnius">Vilnius</option>
		<option value="Europe/Volgograd">Volgograd</option>
		<option value="Europe/Warsaw">Warsaw</option>
		<option value="Europe/Zagreb">Zagreb</option>
		<option value="Europe/Zaporozhye">Zaporozhye</option>
		<option value="Europe/Zurich">Zurich</option>
	</optgroup>
	
	<optgroup label="Asia">
		<option value="Asia/Aden">Aden</option>
		<option value="Asia/Almaty">Almaty</option>
		<option value="Asia/Amman">Amman</option>
		<option value="Asia/Anadyr">Anadyr</option>
		<option value="Asia/Aqtau">Aqtau</option>
		<option value="Asia/Aqtobe">Aqtobe</option>
		<option value="Asia/Ashgabat">Ashgabat</option>
		<option value="Asia/Ashkhabad">Ashkhabad</option>
		<option value="Asia/Baghdad">Baghdad</option>
		<option value="Asia/Bahrain">Bahrain</option>
		<option value="Asia/Baku">Baku</option>
		<option value="Asia/Bangkok">Bangkok</option>
		<option value="Asia/Beirut">Beirut</option>
		<option value="Asia/Bishkek">Bishkek</option>
		<option value="Asia/Brunei">Brunei</option>
		<option value="Asia/Calcutta">Calcutta</option>
		<option value="Asia/Choibalsan">Choibalsan</option>
		<option value="Asia/Chongqing">Chongqing</option>
		<option value="Asia/Chungking">Chungking</option>
		<option value="Asia/Colombo">Colombo</option>
		<option value="Asia/Dacca">Dacca</option>
		<option value="Asia/Damascus">Damascus</option>
		<option value="Asia/Dhaka">Dhaka</option>
		<option value="Asia/Dili">Dili</option>
		<option value="Asia/Dubai">Dubai</option>
		<option value="Asia/Dushanbe">Dushanbe</option>
		<option value="Asia/Gaza">Gaza</option>
		<option value="Asia/Harbin">Harbin</option>
		<option value="Asia/Ho_Chi_Minh">Ho Chi Minh</option>
		<option value="Asia/Hong_Kong">Hong Kong</option>
		<option value="Asia/Hovd">Hovd</option>
		<option value="Asia/Irkutsk">Irkutsk</option>
		<option value="Asia/Istanbul">Istanbul</option>
		<option value="Asia/Jakarta">Jakarta</option>
		<option value="Asia/Jayapura">Jayapura</option>
		<option value="Asia/Jerusalem">Jerusalem</option>
		<option value="Asia/Kabul">Kabul</option>
		<option value="Asia/Kamchatka">Kamchatka</option>
		<option value="Asia/Karachi">Karachi</option>
		<option value="Asia/Kashgar">Kashgar</option>
		<option value="Asia/Kathmandu">Kathmandu</option>
		<option value="Asia/Katmandu">Katmandu</option>
		<option value="Asia/Kolkata">Kolkata</option>
		<option value="Asia/Krasnoyarsk">Krasnoyarsk</option>
		<option value="Asia/Kuala_Lumpur">Kuala Lumpur</option>
		<option value="Asia/Kuching">Kuching</option>
		<option value="Asia/Kuwait">Kuwait</option>
		<option value="Asia/Macao">Macao</option>
		<option value="Asia/Macau">Macau</option>
		<option value="Asia/Magadan">Magadan</option>
		<option value="Asia/Makassar">Makassar</option>
		<option value="Asia/Manila">Manila</option>
		<option value="Asia/Muscat">Muscat</option>
		<option value="Asia/Nicosia">Nicosia</option>
		<option value="Asia/Novokuznetsk">Novokuznetsk</option>
		<option value="Asia/Novosibirsk">Novosibirsk</option>
		<option value="Asia/Omsk">Omsk</option>
		<option value="Asia/Oral">Oral</option>
		<option value="Asia/Phnom_Penh">Phnom Penh</option>
		<option value="Asia/Pontianak">Pontianak</option>
		<option value="Asia/Pyongyang">Pyongyang</option>
		<option value="Asia/Qatar">Qatar</option>
		<option value="Asia/Qyzylorda">Qyzylorda</option>
		<option value="Asia/Rangoon">Rangoon</option>
		<option value="Asia/Riyadh">Riyadh</option>
		<option value="Asia/Saigon">Saigon</option>
		<option value="Asia/Sakhalin">Sakhalin</option>
		<option value="Asia/Samarkand">Samarkand</option>
		<option value="Asia/Seoul">Seoul</option>
		<option value="Asia/Shanghai">Shanghai</option>
		<option value="Asia/Singapore">Singapore</option>
		<option value="Asia/Taipei">Taipei</option>
		<option value="Asia/Tashkent">Tashkent</option>
		<option value="Asia/Tbilisi">Tbilisi</option>
		<option value="Asia/Tehran">Tehran</option>
		<option value="Asia/Tel_Aviv">Tel Aviv</option>
		<option value="Asia/Thimbu">Thimbu</option>
		<option value="Asia/Thimphu">Thimphu</option>
		<option value="Asia/Tokyo">Tokyo</option>
		<option value="Asia/Ujung_Pandang">Ujung Pandang</option>
		<option value="Asia/Ulaanbaatar">Ulaanbaatar</option>
		<option value="Asia/Ulan_Bator">Ulan Bator</option>
		<option value="Asia/Urumqi">Urumqi</option>
		<option value="Asia/Vientiane">Vientiane</option>
		<option value="Asia/Vladivostok">Vladivostok</option>
		<option value="Asia/Yakutsk">Yakutsk</option>
		<option value="Asia/Yekaterinburg">Yekaterinburg</option>
		<option value="Asia/Yerevan">Yerevan</option>
	</optgroup>

	<optgroup label="Africa">
		<option value="Africa/Abidjan">Abidjan</option>
		<option value="Africa/Accra">Accra</option>
		<option value="Africa/Addis_Ababa">Addis Ababa</option>
		<option value="Africa/Algiers">Algiers</option>
		<option value="Africa/Asmara">Asmara</option>
		<option value="Africa/Asmera">Asmera</option>
		<option value="Africa/Bamako">Bamako</option>
		<option value="Africa/Bangui">Bangui</option>
		<option value="Africa/Banjul">Banjul</option>
		<option value="Africa/Bissau">Bissau</option>
		<option value="Africa/Blantyre">Blantyre</option>
		<option value="Africa/Brazzaville">Brazzaville</option>
		<option value="Africa/Bujumbura">Bujumbura</option>
		<option value="Africa/Cairo">Cairo</option>
		<option value="Africa/Casablanca">Casablanca</option>
		<option value="Africa/Ceuta">Ceuta</option>
		<option value="Africa/Conakry">Conakry</option>
		<option value="Africa/Dakar">Dakar</option>
		<option value="Africa/Dar_es_Salaam">Dar es Salaam</option>
		<option value="Africa/Djibouti">Djibouti</option>
		<option value="Africa/Douala">Douala</option>
		<option value="Africa/El_Aaiun">El Aaiun</option>
		<option value="Africa/Freetown">Freetown</option>
		<option value="Africa/Gaborone">Gaborone</option>
		<option value="Africa/Harare">Harare</option>
		<option value="Africa/Johannesburg">Johannesburg</option>
		<option value="Africa/Kampala">Kampala</option>
		<option value="Africa/Khartoum">Khartoum</option>
		<option value="Africa/Kigali">Kigali</option>
		<option value="Africa/Kinshasa">Kinshasa</option>
		<option value="Africa/Lagos">Lagos</option>
		<option value="Africa/Libreville">Libreville</option>
		<option value="Africa/Lome">Lome</option>
		<option value="Africa/Luanda">Luanda</option>
		<option value="Africa/Lubumbashi">Lubumbashi</option>
		<option value="Africa/Lusaka">Lusaka</option>
		<option value="Africa/Malabo">Malabo</option>
		<option value="Africa/Maputo">Maputo</option>
		<option value="Africa/Maseru">Maseru</option>
		<option value="Africa/Mbabane">Mbabane</option>
		<option value="Africa/Mogadishu">Mogadishu</option>
		<option value="Africa/Monrovia">Monrovia</option>
		<option value="Africa/Nairobi">Nairobi</option>
		<option value="Africa/Ndjamena">Ndjamena</option>
		<option value="Africa/Niamey">Niamey</option>
		<option value="Africa/Nouakchott">Nouakchott</option>
		<option value="Africa/Ouagadougou">Ouagadougou</option>
		<option value="Africa/Porto-Novo">Porto-Novo</option>
		<option value="Africa/Sao_Tome">Sao Tome</option>
		<option value="Africa/Timbuktu">Timbuktu</option>
		<option value="Africa/Tripoli">Tripoli</option>
		<option value="Africa/Tunis">Tunis</option>
		<option value="Africa/Windhoek">Windhoek</option>
	</optgroup>
	
	<optgroup label="Australia">
		<option value="Australia/ACT">ACT</option>
		<option value="Australia/Adelaide">Adelaide</option>
		<option value="Australia/Brisbane">Brisbane</option>
		<option value="Australia/Broken_Hill">Broken Hill</option>
		<option value="Australia/Canberra">Canberra</option>
		<option value="Australia/Currie">Currie</option>
		<option value="Australia/Darwin">Darwin</option>
		<option value="Australia/Eucla">Eucla</option>
		<option value="Australia/Hobart">Hobart</option>
		<option value="Australia/LHI">LHI</option>
		<option value="Australia/Lindeman">Lindeman</option>
		<option value="Australia/Lord_Howe">Lord Howe</option>
		<option value="Australia/Melbourne">Melbourne</option>
		<option value="Australia/North">North</option>
		<option value="Australia/NSW">NSW</option>
		<option value="Australia/Perth">Perth</option>
		<option value="Australia/Queensland">Queensland</option>
		<option value="Australia/South">South</option>
		<option value="Australia/Sydney">Sydney</option>
		<option value="Australia/Tasmania">Tasmania</option>
		<option value="Australia/Victoria">Victoria</option>
		<option value="Australia/West">West</option>
		<option value="Australia/Yancowinna">Yancowinna</option>
	</optgroup>

	<optgroup label="Indian">
		<option value="Indian/Antananarivo">Antananarivo</option>
		<option value="Indian/Chagos">Chagos</option>
		<option value="Indian/Christmas">Christmas</option>
		<option value="Indian/Cocos">Cocos</option>
		<option value="Indian/Comoro">Comoro</option>
		<option value="Indian/Kerguelen">Kerguelen</option>
		<option value="Indian/Mahe">Mahe</option>
		<option value="Indian/Maldives">Maldives</option>
		<option value="Indian/Mauritius">Mauritius</option>
		<option value="Indian/Mayotte">Mayotte</option>
		<option value="Indian/Reunion">Reunion</option>
	</optgroup>
	
	<optgroup label="Atlantic">
		<option value="Atlantic/Azores">Azores</option>
		<option value="Atlantic/Bermuda">Bermuda</option>
		<option value="Atlantic/Canary">Canary</option>
		<option value="Atlantic/Cape_Verde">Cape Verde</option>
		<option value="Atlantic/Faeroe">Faeroe</option>
		<option value="Atlantic/Faroe">Faroe</option>
		<option value="Atlantic/Jan_Mayen">Jan Mayen</option>
		<option value="Atlantic/Madeira">Madeira</option>
		<option value="Atlantic/Reykjavik">Reykjavik</option>
		<option value="Atlantic/South_Georgia">South Georgia</option>
		<option value="Atlantic/Stanley">Stanley</option>
		<option value="Atlantic/St_Helena">St Helena</option>
	</optgroup>

	<optgroup label="Pacific">
		<option value="Pacific/Apia">Apia</option>
		<option value="Pacific/Auckland">Auckland</option>
		<option value="Pacific/Chatham">Chatham</option>
		<option value="Pacific/Easter">Easter</option>
		<option value="Pacific/Efate">Efate</option>
		<option value="Pacific/Enderbury">Enderbury</option>
		<option value="Pacific/Fakaofo">Fakaofo</option>
		<option value="Pacific/Fiji">Fiji</option>
		<option value="Pacific/Funafuti">Funafuti</option>
		<option value="Pacific/Galapagos">Galapagos</option>
		<option value="Pacific/Gambier">Gambier</option>
		<option value="Pacific/Guadalcanal">Guadalcanal</option>
		<option value="Pacific/Guam">Guam</option>
		<option value="Pacific/Honolulu">Honolulu</option>
		<option value="Pacific/Johnston">Johnston</option>
		<option value="Pacific/Kiritimati">Kiritimati</option>
		<option value="Pacific/Kosrae">Kosrae</option>
		<option value="Pacific/Kwajalein">Kwajalein</option>
		<option value="Pacific/Majuro">Majuro</option>
		<option value="Pacific/Marquesas">Marquesas</option>
		<option value="Pacific/Midway">Midway</option>
		<option value="Pacific/Nauru">Nauru</option>
		<option value="Pacific/Niue">Niue</option>
		<option value="Pacific/Norfolk">Norfolk</option>
		<option value="Pacific/Noumea">Noumea</option>
		<option value="Pacific/Pago_Pago">Pago Pago</option>
		<option value="Pacific/Palau">Palau</option>
		<option value="Pacific/Pitcairn">Pitcairn</option>
		<option value="Pacific/Ponape">Ponape</option>
		<option value="Pacific/Port_Moresby">Port Moresby</option>
		<option value="Pacific/Rarotonga">Rarotonga</option>
		<option value="Pacific/Saipan">Saipan</option>
		<option value="Pacific/Samoa">Samoa</option>
		<option value="Pacific/Tahiti">Tahiti</option>
		<option value="Pacific/Tarawa">Tarawa</option>
		<option value="Pacific/Tongatapu">Tongatapu</option>
		<option value="Pacific/Truk">Truk</option>

		<option value="Pacific/Wake">Wake</option>
		<option value="Pacific/Wallis">Wallis</option>
		<option value="Pacific/Yap">Yap</option>
	</optgroup>
	
	<optgroup label="Antarctica">
		<option value="Antarctica/Casey">Casey</option>
		<option value="Antarctica/Davis">Davis</option>
		<option value="Antarctica/DumontDUrville">DumontDUrville</option>
		<option value="Antarctica/Macquarie">Macquarie</option>
		<option value="Antarctica/Mawson">Mawson</option>
		<option value="Antarctica/McMurdo">McMurdo</option>
		<option value="Antarctica/Palmer">Palmer</option>
		<option value="Antarctica/Rothera">Rothera</option>
		<option value="Antarctica/South_Pole">South Pole</option>
		<option value="Antarctica/Syowa">Syowa</option>
		<option value="Antarctica/Vostok">Vostok</option>
	</optgroup>

	<optgroup label="Arctic">
		<option value="Arctic/Longyearbyen">Longyearbyen</option>
	</optgroup>

	<optgroup label="UTC">
		<option value="UTC">UTC</option>
	</optgroup>

	<optgroup label="Manual Offsets">
		<option value="UTC-12">UTC-12</option>
		<option value="UTC-11">UTC-11</option>
		<option value="UTC-10">UTC-10</option>
		<option value="UTC-9">UTC-9</option>
		<option value="UTC-8">UTC-8</option>
		<option value="UTC-7">UTC-7</option>
		<option value="UTC-6">UTC-6</option>
		<option value="UTC-5">UTC-5</option>
		<option value="UTC-4">UTC-4</option>
		<option value="UTC-3">UTC-3</option>
		<option value="UTC-2">UTC-2</option>
		<option value="UTC-1">UTC-1</option>
		<option value="UTC+0">UTC+0</option>
		<option value="UTC+1">UTC+1</option>
		<option value="UTC+2">UTC+2</option>
		<option value="UTC+3">UTC+3</option>
		<option value="UTC+4">UTC+4</option>
		<option value="UTC+5">UTC+5</option>
		<option value="UTC+6">UTC+6</option>
		<option value="UTC+7">UTC+7</option>
		<option value="UTC+8">UTC+8</option>
		<option value="UTC+9">UTC+9</option>
		<option value="UTC+10">UTC+10</option>
		<option value="UTC+11">UTC+11</option>
		<option value="UTC+12">UTC+12</option>
		<option value="UTC+13">UTC+13</option>
		<option value="UTC+14">UTC+14</option>
	</optgroup>
				  
				  
				  
				  
				  
              </select>
            </div>
            <script>$(function(){iCMS.select('time_zone',"<?php echo $config['time']['zone'] ; ?>");});</script>
            <span class="help-inline">Часовой пояс сервера</span>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"> <span class="add-on">Коррекция времени сервера</span>
              <input type="text" name="config[time][cvtime]" class="span3" id="time_cvtime" value="<?php echo $config['time']['cvtime'] ; ?>"/>
            </div>
            <span class="help-inline">Единица измерения: минута</span>
            <div class="clearfloat"></div>
            <span class="help-inline">Эта функция используется для устранения проблемы неправильной установки времени операционной системы сервера.
             После подтверждения правильности установки часового пояса программы по умолчанию время отображения программы все еще содержит ошибки. Пожалуйста, используйте эту функцию для исправления</span>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend input-append"> <span class="add-on">Формат времени по умолчанию</span>
              <input type="text" name="config[time][dateformat]" class="span3" id="time_dateformat" value="<?php echo $config['time']['dateformat'] ; ?>"/>
              <div class="btn-group" to="#FS_dir_format"> <a class="btn dropdown-toggle" data-toggle="dropdown" tabindex="-1"><i class="fa fa-question-circle"></i> Помощь </a>
                <ul class="dropdown-menu">
                  <li><a href="#Y"><span class="label label-inverse">Y</span> Порядковый номер года, 4 цифры (Примеры: 1999, 2003)</a></li>
                  <li><a href="#y"><span class="label label-inverse">y</span> Номер года, 2 цифры (Примеры: 99, 03)</a></li>
                  <li><a href="#m"><span class="label label-inverse">m</span> Порядковый номер месяца с ведущими нулями (От 01 до 12)</a></li>
                  <li><a href="#n"><span class="label label-inverse">n</span> Порядковый номер месяца без ведущих нулей (От 1 до 12)</a></li>
                  <li><a href="#d"><span class="label label-inverse">d</span> День месяца, 2 цифры с ведущими нулями (От 01 до 31)</a></li>
                  <li><a href="#j"><span class="label label-inverse">j</span> День месяца без ведущих нулей (От 1 до 31)</a></li>
                </ul>
              </div>
            </div>
          </div>
          <div id="config-other" class="tab-pane hide">
            <div class="input-prepend"> <span class="add-on">Боковое меню</span>
              <div class="switch" data-on-label="Вкл" data-off-label="Закрыть">
                <input type="checkbox" data-type="switch" name="config[other][sidebar_enable]" id="other_sidebar_enable" <?php echo $config['other']['sidebar_enable']?'checked':''; ?>/>
              </div>
              <div class="switch" data-on-label="Вкл" data-off-label="Выкл">
                <input type="checkbox" data-type="switch" name="config[other][sidebar]" id="other_sidebar" <?php echo $config['other']['sidebar']?'checked':''; ?>/>
              </div>
            </div>
            <span class="help-inline">Фоновая боковая панель включена по умолчанию, при ее включении вы можете открыть или свернуть ее.</span>
            
          </div>
          <div id="config-patch" class="tab-pane hide">
            <div class="input-prepend"> <span class="add-on">Обновление системы</span>
              <select name="config[system][patch]" id="system_patch" class="span3 chosen-select">
                <option value="1">Автоматическая загрузка, по времени (рекомендуется)</option>
                <option value="2">Не загружать обновления автоматически, оповещать о выходе</option>
                <option value="0">Отключить автоматическое обновление</option>
              </select>
            </div>
            <script>$(function(){iCMS.select('system_patch',"<?php echo (int)$config['system']['patch'] ; ?>");});</script>
          </div>
          <div id="config-grade" class="tab-pane hide">
            <?php include admincp::view("config.grade","config");?>
          </div>
          <div id="config-mail" class="tab-pane hide">
            <?php include admincp::view("config.email","config");?>
          </div>
          <div id="apps-metadata" class="tab-pane hide">
            <?php include admincp::view("apps.meta","apps");?>
          </div>
         <div id="config-ext" class="tab-pane hide">
           <?php foreach (config::scan_config('ext') as $path) { include $path;} ?>
          </div>
        </div>
        <div class="form-actions">
          <button class="btn btn-primary btn-small" type="submit"><i class="fa fa-check"></i>Сохранить</button>
        </div>
      </form>
    </div>
  </div>
</div>
<table class="hide template_device_clone">
  <tr>
    <?php echo template_device_td('{key}');?>
  </tr>
</table>
<?php admincp::foot();?>
