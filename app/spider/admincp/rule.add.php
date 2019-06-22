<?php

defined('iPHP') OR exit('Oops, something went wrong');
admincp::head();
?>
<script type="text/javascript" src="./app/admincp/ui/jquery/jquery-ui.min.js"></script>
<style>
.rule_data_name {}
.delprop { width:45px; }
.chosen-container-multi .chosen-choices li.search-choice span{font-size: 14px;}
.data-processing-group.btn-group.open .dropdown-toggle{box-shadow:none;}
.data-processing-group .dropdown-menu>li>a{width: auto;}
.processing-item-fun{margin-right: 5px;}
.processing-item-dcl{display:block !important;clear: both;margin-top: 2px;}
/*.divider-text{
    height: 24px !important;
    background-color: #333 !important;
    text-align: center !important;
    line-height: 24px !important;
    color: #fff !important;
    margin: 0px !important;
}*/
</style>
<script type="text/javascript">
$(function() {
    iCMS.select('watermark_pos', "<?php echo (int)$rule['watermark']['pos'] ; ?>");
    select_sort_change('.helper-wrap');

    <?php if($_GET['tab']) {?>
        var $itab=$("#<?php echo $_GET['app']; ?>-tab");
        $("li", $itab).removeClass("active");
        $(".tab-pane").removeClass("active").addClass("hide");
        $("a[href='#<?php echo $_GET['app']; ?>-<?php echo $_GET['tab']; ?>']", $itab).parent().addClass("active");
        $("#<?php echo $_GET['app']; ?>-<?php echo $_GET['tab']; ?>").addClass("active").removeClass("hide");
    <?php } ?>

    $('#spider-data').on("click", ".delprop", function() {
        if(confirm('Вы уверены, что хотите удалить?')) {
            $(this).parent().parent().parent().remove();
        }
    });

    $('#spider').on("click", 'a[data-toggle="insertContent"]', function() {
        var href=$(this).attr("href"); // console.log(href.indexOf('<%'),href.indexOf('aaaaaaaaaa'));
        if(href.indexOf('<%')!="-1") {
            var target=$(this).attr('data-target');
            var text=$(target).val();
            if(text.indexOf(href)!="-1") {
                alert(href+"只能有一个!其它请用 变量标识!");
                return false;
            }
        }
    });

   $('.PresetMethod').on("mouseover", 'a.dropdown-DHMenu', function() {
        var pp = $(this).parent();
        var li = $("li.dropdown-submenu",".data_helper_clone").clone(true);
        // li.mouseout(function(event) {
        //   console.log(this);
        //   $(".dropdown-menu",pp).html('');
        // });
        $(".dropdown-menu",pp).empty().html(li);
    })
    $('.data-processing-group').on("click", 'a[data-act]', function() {
      var tr = $(this).parents('tr');
      var name = $('.rule_data_rule',tr).attr('name'),id = $('.rule_data_rule',tr).attr('id');
      var act  = $(this).data('act'),title=$(this).attr('title');
      var length =parseInt($(".processing div:last",tr).attr('data-key'))+1;

      if(!length) length=0;
      if(act=='dataclean'){
        var div = '<?php processing_item("'+name+'",array('helper'=>"'+act+'",'dataclean'=>true),"'+length+'",' 规则采集后数据整理');?>';
      }else{
        var div = '<?php processing_item("'+name+'",array('helper'=>"'+act+'"),"'+length+'","'+title+'");?>';
      }
      $(".processing",tr).append(div);
    });

    $('.processing').sortable({
      update: function( event, ui ) {
          var target = $(event.target);
          $('[data-key]', target).each(function(idx) {
            var dk = $(this).attr('data-key');
            $(this).attr('data-key',idx);
            $('input', this).each(function(i) {
              this.name = this.name.replace('[process]['+dk+']', '[process]['+idx+']');
            });
          });
      }
    }).disableSelection();

    $('.processing').on("click", ".process_del", function() {
        $(this).parent().parent().remove();
    });

    $(".add_items").click(function() {
        // var length=$("#spider-data tbody tr").length+1;
        var length =parseInt($("#spider-data tbody tr:last").attr('data-key'))+1;
        var href   =$(this).attr("href");
        var tb     =$(href);
        var tbody  =$("tbody", tb);
        var ntr    =$(".rule_data_clone tr").clone(true);

        if(!length) length=0;
        ntr.attr('data-key', length);

        $('input,textarea,td,select', ntr).each(function(i) {
            if(this.id)   this.id   = this.id.replace('__KEY__', length);
            if(this.name) this.name = this.name.replace('[__KEY__]', '['+length+']');
            // if(this.type) this.type = this.type.replace('dr_checkbox', 'checkbox');
            var type = $(this).attr('type');
            if(type=="dr_checkbox"){
                $(this).attr('type','checkbox');
            }
        });

        $('a[data-target]', ntr).each(function(i) {
            var target = $(this).attr('data-target');
            target = target.replace('__KEY__', length);
            $(this).attr('data-target', target);
        });

        chosen_config.width=$(".rule_data_helper").width()+'px';
        $(".dr_chosen", ntr).chosen(chosen_config);
        $('.dr_tip', ntr).tooltip();
        $(':checkbox',ntr).uniform();

        ntr.appendTo(tbody);

        return false;
    });



    $(".preg_checkbox,.dom_checkbox").on("click", function() {
        var pp=$(this).parents('td');
        var checkedStatus=$(this).prop("checked");
        var btn = $(".preg_rule",pp);
        if(this.className=='dom_checkbox') {
        btn.css('display',!checkedStatus?'':'none');
            var cb=$(".preg_checkbox", pp).prop("checked", !checkedStatus);
        } else {
            btn.css('display',checkedStatus?'':'none');
            var cb=$(".dom_checkbox", pp).prop("checked", !checkedStatus);
        }
        $.uniform.update(cb);
    });

    $(".rule_data_page").on("click", function() {
        var checkedStatus=$(this).prop("checked");
        if (checkedStatus) {
            alert("此数据项您选择有分页,\n\n请记得设置[分页设置]选项卡的内容!");
        }
    });
    $(".rule_data_datasource").on("click", function() {
        var pp=$(this).parents('td');
        var checkedStatus=$(this).prop("checked");
        if (checkedStatus) {
            $(".data_datasource", pp).removeClass('hide');
        }else {
            $(".data_datasource", pp).addClass('hide');
        }
    });
});

function select_sort_option(e, v) {
    var option=e.find('option[value="' + v + '"]').clone();
    option.attr('selected', 'selected');
    return option;
}

function select_sort_change($e) {
    $('select[multiple="multiple"]', $e).each(function(index, select) {
        var s_id=this.id;
        $("#sort_"+s_id, $e).html('');
        $(this).on('change', function(e, p) {
            select_sort_value(this, e, p);
        });
    });
}

function select_sort_value(a, e, p) {
    var s_id=a.id,
    select=$("#sort_"+s_id);
    if(p['selected']) {
        select.append(select_sort_option($(a), p['selected']));
    }
    if(p['deselected']) {
        select.find('option[value="' + p['deselected'] + '"]').remove();
    }
}
</script>

<div class="iCMS-container">
  <div class="widget-box">
    <div class="widget-title"> <span class="icon"> <i class="fa fa-plus-square"></i> </span>
      <h5 class="brs"><?php echo ($this->rid ?'Изменить'."[{$rs['name']}]":'Добавить') ; ?> правила </h5>
      <ul class="nav nav-tabs" id="spider-tab">
        <li class="active"><a href="#spider-base" data-toggle="tab"><i class="fa fa-info-circle"></i> Основные настройки</a></li>
        <li><a href="#spider-data" data-toggle="tab"><i class="fa fa-truck"></i> Сбор данных</a></li>
        <li><a href="#spider-page" data-toggle="tab"><i class="fa fa-columns"></i> Страницы</a></li>
        <li><a href="#spider-remote" data-toggle="tab"><i class="fa fa-cog"></i> Параметры парсера</a></li>
        <li><a href="#spider-proxy" data-toggle="tab"><i class="fa fa-cog"></i> Настройки прокси</a></li>
      </ul>
    </div>
    <div class="widget-content nopadding">
      <form action="<?php echo APP_FURI; ?>&do=save" method="post" class="form-inline" id="iCMS-spider" target="iPHP_FRAME">
        <input name="id" type="hidden" value="<?php echo $this->rid ; ?>" />
        <div id="spider" class="tab-content">
          <div id="spider-base" class="tab-pane active">
            <div class="input-prepend"><span class="add-on">Название правила</span>
              <input type="text" name="name" class="span6" id="name" value="<?php echo $rs['name']; ?>"/>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend input-append"><span class="add-on">User_Agent</span>
              <input type="text" name="rule[user_agent]" class="span6" id="user_agent" value="<?php echo $rule['user_agent'] ; ?>"/>
              <div class="btn-group">
                <a class="btn" href="Mozilla/5.0 (compatible; Baiduspider/2.0; +http://www.baidu.com/search/spider.html)" data-toggle="insertContent" data-target="#user_agent" data-mode="replace">Baidu Spider</a>
                <a class="btn" href="Mozilla/5.0 (Linux;u;Android 4.2.2;zh-cn;) AppleWebKit/534.46 (KHTML,like Gecko) Version/5.1 Mobile Safari/10600.6.3 (compatible; Baiduspider/2.0; +http://www.baidu.com/search/spider.html)" data-toggle="insertContent" data-target="#user_agent" data-mode="replace">Baidu Мобильный Паук</a>
                <a class="btn" href="Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Trident/4.0; .NET CLR 2.0.50727)" data-toggle="insertContent" data-target="#user_agent" data-mode="replace">Обычный браузер</a>
                <a class="btn" href="Mozilla/5.0 (iPhone; CPU iPhone OS 8_0 like Mac OS X) AppleWebKit/600.1.3 (KHTML, like Gecko) Version/8.0 Mobile/12A4345d Safari/600.1.4" data-toggle="insertContent" data-target="#user_agent" data-mode="replace">iPhone 6</a>
                <a class="btn" href="Mozilla/5.0 (Linux; Android 4.2.1; en-us; Nexus 5 Build/JOP40D) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.166 Mobile Safari/535.19" data-toggle="insertContent" data-target="#user_agent" data-mode="replace">Nexus 5</a>
              </div>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"><span class="add-on">Cookie</span>
              <input type="text" name="rule[curl][cookie]" class="span6" id="CURLOPT_COOKIE" value="<?php echo $rule['curl']['cookie'] ; ?>"/>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"><span class="add-on">Декодирование (gzip,deflate)</span>
              <input type="text" name="rule[curl][encoding]" class="span6" id="CURLOPT_ENCODING" value="<?php echo $rule['curl']['encoding'] ; ?>"/>
            </div>
            <span class="help-inline"><span class="label label-important">Настройка распаковки сжатого контента при помощи gzip,deflate, по умолчанию пусто, если вы собираете сжатый контент используйте эту настройку.</span></span>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"><span class="add-on">Реферальный URL</span>
              <input type="text" name="rule[curl][referer]" class="span6" id="CURLOPT_REFERER" value="<?php echo $rule['curl']['referer'] ; ?>"/>
            </div>
            <span class="help-inline"><span class="label label-important">По умолчанию пусто, если вам потребуется использовать реферальный адрес, вы можете ввести необходимый URL.</span></span>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend input-append"> <span class="add-on">Кодировка страниц</span><span class="add-on">
              <label class="radio">
                <input type="radio" name="rule[charset]" value="utf-8"<?php if($rule['charset']=="utf-8"){ echo ' checked="true"';};?>>
                UTF-8 </label>
              </span>
			<span class="add-on">
              <label class="radio">
                <input type="radio" name="rule[charset]" value="windows-1251"<?php if($rule['charset']=="windows-1251"){ echo ' checked="true"';};?>>
                Windows 1251 </label>
              </span>
              <span class="add-on">
              <label class="radio">
                <input type="radio" name="rule[charset]" value="auto"<?php if($rule['charset']=="auto"){ echo ' checked="true"';};?>>
                Автоматическая идентификация</label>
              </span> </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend input-append"> <span class="add-on">Последовательность</span><span class="add-on">
              <label class="radio">
                <input type="radio" name="rule[sort]" value="1"<?php if($rule['sort']=="1"){ echo ' checked="true"';};?>>
                Сверху вниз </label>
              </span><span class="add-on">
              <label class="radio">
                <input type="radio" name="rule[sort]" value="2"<?php if($rule['sort']=="2"){ echo ' checked="true"';};?>>
                Снизу вверх </label>
              </span><span class="add-on">
              <label class="radio">
                <input type="radio" name="rule[sort]" value="3"<?php if($rule['sort']=="3"){ echo ' checked="true"';};?>>
                Случайное </label>
              </span></div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend input-append"> <span class="add-on">Режим сбора</span><span class="add-on">
              <label class="radio">
                <input type="radio" name="rule[mode]" id="mode1" value="1"<?php if($rule['mode']=="1"){ echo ' checked="true"';};?>>
                Регулярное выражение</label>
              </span>
              <span class="add-on">
              <label class="radio">
                <input type="radio" name="rule[mode]" id="mode2" value="2"<?php if($rule['mode']=="2"){ echo ' checked="true"';};?>>
                phpQuery </label>
              </span>
              <span class="add-on">
              <label class="radio">
                <input type="radio" name="rule[mode]" id="mode3" value="3"<?php if($rule['mode']=="3"){ echo ' checked="true"';};?>>
                Синтаксис JSON </label>
              </span>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend input-sp"><span class="add-on">Список URL-адресов</span>
              <textarea name="rule[list_urls]" id="list_urls" class="span6"><?php echo $rule['list_urls'] ; ?></textarea>
            </div>
            <div class="clearfloat"></div>
            <div class="input-prepend input-sp"><span class="add-on">Список результатов</span>
              <textarea name="rule[list_urls_format]" id="list_urls_format" class="span6"><?php echo $rule['list_urls_format'] ; ?></textarea>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend input-sp"><span class="add-on">列表区域规则</span>
              <textarea name="rule[list_area_rule]" id="list_area_rule" class="span6"><?php echo $rule['list_area_rule'] ; ?></textarea>
              <div class="btn-group btn-group-vertical"> <a class="btn" href="<%content%>" data-toggle="insertContent" data-target="#list_area_rule">Контент</a> <a class="btn" href="<%var%>" data-toggle="insertContent" data-target="#list_area_rule"> Идентификация переменной </a> </div>
            </div>
            <div class="clearfloat"></div>
            <div class="input-prepend input-sp"><span class="add-on">列表区域整理</span>
              <textarea name="rule[list_area_format]" id="list_area_format" class="span6"><?php echo $rule['list_area_format'] ; ?></textarea>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend input-sp"><span class="add-on">列表链接规则</span>
              <textarea name="rule[list_url_rule]" id="list_url_rule" class="span6"><?php echo $rule['list_url_rule'] ; ?></textarea>
              <div class="btn-group btn-group-vertical"> <a class="btn" href="<%title%>" data-toggle="insertContent" data-target="#list_url_rule">Название</a> <a class="btn" href="<%url%>" data-toggle="insertContent" data-target="#list_url_rule">URL</a> <a class="btn" href="<%var%>" data-toggle="insertContent" data-target="#list_url_rule"> Идентификация переменной </a> </div>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend input-append"><span class="add-on">网址合成</span>
              <input type="text" name="rule[list_url]" class="span6" id="list_url" value="<?php echo $rule['list_url'] ; ?>"/>
              <a class="btn" href="<%url%>" data-toggle="insertContent" data-target="#list_url">URL</a>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend input-append"><span class="add-on">网址整理</span>
              <textarea name="rule[list_url_clean]" id="list_url_clean" class="span6 tip" title="合成后整理"><?php echo $rule['list_url_clean'] ; ?></textarea>
              <a class="btn" href="<%url%>" data-toggle="insertContent" data-target="#list_url_clean"> Идентификация переменной </a>
            </div>
            <div class="clearfloat mb10"></div>
          </div>
          <div id="spider-data" class="tab-pane">
            <div class="input-prepend input-append"><span class="add-on">UserAgent</span>
              <input type="text" name="rule[data_user_agent]" class="span6" id="data_user_agent" value="<?php echo $rule['data_user_agent'] ; ?>"/>
              <div class="btn-group">
                <a class="btn" href="Mozilla/5.0 (compatible; Baiduspider/2.0; +http://www.baidu.com/search/spider.html)" data-toggle="insertContent" data-target="#data_user_agent" data-mode="replace">Baidu Spider</a>
                <a class="btn" href="Mozilla/5.0 (Linux;u;Android 4.2.2;zh-cn;) AppleWebKit/534.46 (KHTML,like Gecko) Version/5.1 Mobile Safari/10600.6.3 (compatible; Baiduspider/2.0; +http://www.baidu.com/search/spider.html)" data-toggle="insertContent" data-target="#data_user_agent" data-mode="replace">Baidu Spider мобильный</a>
                <a class="btn" href="Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Trident/4.0; .NET CLR 2.0.50727)" data-toggle="insertContent" data-target="#data_user_agent" data-mode="replace">Обычный браузер</a>
                <a class="btn" href="Mozilla/5.0 (iPhone; CPU iPhone OS 8_0 like Mac OS X) AppleWebKit/600.1.3 (KHTML, like Gecko) Version/8.0 Mobile/12A4345d Safari/600.1.4" data-toggle="insertContent" data-target="#data_user_agent" data-mode="replace">iPhone 6</a>
                <a class="btn" href="Mozilla/5.0 (Linux; Android 4.2.1; en-us; Nexus 5 Build/JOP40D) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.166 Mobile Safari/535.19" data-toggle="insertContent" data-target="#data_user_agent" data-mode="replace">Nexus 5</a>
              </div>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend input-append"> <span class="add-on">Кодировка страницы</span><span class="add-on">
              <label class="radio">
                <input type="radio" name="rule[data_charset]" value="utf-8"<?php if($rule['data_charset']=="utf-8"){ echo ' checked="true"';};?>>
                UTF-8 </label>
              </span><span class="add-on">
              <label class="radio">
                <input type="radio" name="rule[data_charset]" value="windows-1251"<?php if($rule['data_charset']=="windows-1251"){ echo ' checked="true"';};?>>
                Windows 1251 </label>
              </span>
              <span class="add-on">
              <label class="radio">
                <input type="radio" name="rule[data_charset]" value="auto"<?php if($rule['data_charset']=="auto"){ echo ' checked="true"';};?>>
                Автоматическая идентификация</label>
              </span>
              <span class="add-on">
              <label class="radio">
                <input type="radio" name="rule[data_charset]" value=""<?php if($rule['data_charset']==""){ echo ' checked="true"';};?>>
                Кодировка с источника</label>
              </span>
            </div>
            <div class="clearfloat mb10"></div>
            <table class="table table-hover">
              <thead>
                <tr>
                  <th class="span3">Название элемента данных</th>
                  <th class="span6">Правила</th>
                  <th style="text-align: left;">Обработка данных</th>
                </tr>
              </thead>
              <tbody>
<?php
function rule_data_rule_td($dkey,$data = array()){
  $DR_target     = 'rule_data_'.$dkey.'_rule';
  $DR_id         = 'data_rule_'.$dkey;
  $DR_name       = 'rule[data]['.$dkey.']';
  $tip_class     = ($dkey==='__KEY__')?'dr_tip':'tip';
  $chosen_class  = ($dkey==='__KEY__')?'dr_chosen':'chosen-select';
  $checkbox_type = ($dkey==='__KEY__')?'dr_checkbox':'checkbox';
?>

  <td>
    <div class="input-prepend input-append">
      <span class="add-on"><a class="delprop"><i class="fa fa-trash-o"></i></a></span>
      <input name="<?php echo $DR_name;?>[name]" type="text" class="rule_data_name" value="<?php echo $data['name'];?>"/>
    </div>
  </td>
  <td class="rule_data_rule" id="<?php echo $DR_id;?>" name="<?php echo $DR_name;?>">

    <div class="<?php echo $data['data@source']?:'hide';?> data_datasource">
      <div class="input-prepend">
        <span class="add-on"> Источник данных </span>
        <input name="<?php echo $DR_name;?>[data@source]" type="text" class="span5 <?php echo $tip_class;?>" placeholder="По умолчанию пусто" title="可填写多个数据项名称,格式[DATA@数据项1][DATA@数据项2][DATA@title]" value="<?php echo $data['data@source'];?>"/>
      </div>
      <div class="clearfloat mb10"></div>
    </div>
    <textarea name="<?php echo $DR_name;?>[rule]" class="span6" id="<?php echo $DR_target;?>"><?php echo htmlspecialchars($data['rule']);?></textarea>
    <div class="clearfloat"></div>
    <div class="preg_rule input-prepend input-append" <?php if($data['dom']){ echo ' style="display:none;"';};?>>
      <a class="btn" href="<%content%>" data-toggle="insertContent" data-target="#<?php echo $DR_target;?>">Вставить идентификатор контента</a>
      <a class="btn" href="<%var%>" data-toggle="insertContent" data-target="#<?php echo $DR_target;?>">Вставить идентификатор переменной</a>
    </div>
    <div class="btn-group data-processing-group">
      <a class="btn dropdown-toggle" data-toggle="dropdown" tabindex="-1">Добавить обработку данных<span class="caret"></span></a>
      <ul class="dropdown-menu">
        <li><a class="tip-right" href="##" title="Обработка необработанных данных до совпадения с правилом сбора проекта">Перед правилом: обработка данных</a></li>
        <li class="divider"></li>
        <li><a class="tip-right" href="##" title="Обработка соответствующих данных для проекта" data-act='dataclean'>Обработка данных</a></li>
        <li class="divider"></li>
        <li class="dropdown-submenu PresetMethod" title="Предустановленные методы">
          <a href="javascript:;" title="Обработка данных с использованием предустановленных методов" class="dropdown-toggle tip-left dropdown-DHMenu" data-toggle="dropdown"><i class="fa fa-code"></i> <span>Предустановленные методы</span></a>
          <ul class="dropdown-menu">
          </ul>
        </li>
      </ul>
    </div>
    <div class="clearfloat mb10"></div>
    <label class="checkbox">
      <input type="<?php echo $checkbox_type;?>" class="preg_checkbox" name="<?php echo $DR_name;?>[preg]" value="1"<?php if(!$data['dom']||$data['preg']){ echo ' checked="true"';};?>>
      Регулярное выражение
    </label>
    <label class="checkbox">
      <input type="<?php echo $checkbox_type;?>" class="dom_checkbox" name="<?php echo $DR_name;?>[dom]" value="1"<?php if($data['dom']){ echo ' checked="true"';};?>>
      phpQuery совпадения
    </label>
    <label class="checkbox">
      <input type="<?php echo $checkbox_type;?>" class="rule_data_datasource" <?php if($data['data@source']){ echo ' checked="true"';};?>>
     Источник данных
    </label>
    <div class="clearfloat mb10"></div>
    <label class="checkbox">
      <input type="<?php echo $checkbox_type;?>" name="<?php echo $DR_name;?>[empty]" value="1"<?php if($data['empty']){ echo ' checked="true"';};?>>
      Не допускается пустое значение
    </label>
    <label class="checkbox">
      <input type="<?php echo $checkbox_type;?>" class="rule_data_page" name="<?php echo $DR_name;?>[page]" value="1"<?php if($data['page']){ echo ' checked="true"';};?>>
     Разбивка на страницы
    </label>
    <label class="checkbox">
      <input type="<?php echo $checkbox_type;?>" name="<?php echo $DR_name;?>[multi]" value="1"<?php if($data['multi']){ echo ' checked="true"';};?>>
      Совпадение нескольких
    </label>
  </td>
   <td class="processing">
    <?php if($data['process']) foreach ($data['process'] as $key => $value) {
        if(is_array($value)){
          $title = spider_process::$helperMaps[$value['helper']][1];
          processing_item($DR_name,$value,$key,$title);
        }
    }
    ?>
  </td>
      <?php }?>
    <?php
function processing_item($name,$item=array(),$length=0,$title=''){
  if($item['helper']=='dataclean'||$item['dataclean']){
    $div ='<div class="input-prepend processing-item-dcl" data-key="'.$length.'">'.
      '<span class="add-on"><a class="process_del"><i class="fa fa-trash-o"></i></a>'.
      '<input name="'.$name.'[process]['.$length.'][helper]" type="hidden" value="'.$item['helper'].'" />'.
      '</span>'.
      '<span class="add-on">整理</span>'.
      '<input type="text" name="'.$name.'[process]['.$length.'][rule]" class="span5 tip-top" title="'.$title.'" value="'.$item['rule'].'"/>'.
    '</div>';
  }else{
    $div ='<div class="input-prepend input-append processing-item-fun" data-key="'.$length.'">'.
      '<span class="add-on"><a class="process_del"><i class="fa fa-trash-o"></i></a></span>'.
      '<span class="add-on">'.$title.'<input name="'.$name.'[process]['.$length.'][helper]" type="hidden" value="'.$item['helper'].'" /></span>'.
    '</div>';
  }
  echo $div;
} ?>
              <?php
                if($rule['data'])foreach((array)$rule['data'] AS $dkey=>$data){
                echo '<tr data-key="'.$dkey.'">';
                echo rule_data_rule_td($dkey,$data);
                echo '</tr>';
                }
              ?>
              </tbody>
              <tfoot>
                <tr>
                  <td colspan="4">
                    <p class="mb10"> Системные поля: <span class="label label-info">Описание:description</span> <span class="label label-info">Теги:tags</span> <span class="label label-info">Источник:source</span> <span class="label label-info">Автор:author</span> <span class="label label-info">Ключевые слова:keywords</span></p>
                    <a href="#spider-data" class="btn btn-primary add_items"/>Добавить элемент</a></td>
                </tr>
              </tfoot>
            </table>
          </div>
          <div id="spider-page" class="tab-pane">
            <ul class="nav nav-tabs" id="spider-tab">
              <li class="active"><a href="#spider-page-area-rule" data-toggle="tab"><i class="fa fa-wrench"></i> Метод сбора</a></li>
              <li><a href="#spider-page-url-parse" data-toggle="tab"><i class="fa fa-random"></i> 逻辑方式</a></li>
            </ul>
            <div class="tab-content">
              <div id="spider-page-area-rule" class="tab-pane active">
                <div class="alert mt5" style="width:360px;">Метод сбора данных подходит для всех режимов пейджинга, перечисленных в нумерации страниц.</div>
                <div class="input-prepend input-sp"><span class="add-on">分页区域规则</span>
                  <textarea name="rule[page_area_rule]" id="page_area_rule" class="span6"><?php echo $rule['page_area_rule'] ; ?></textarea>
                  <div class="btn-group btn-group-vertical"> <a class="btn" href="<%content%>" data-toggle="insertContent" data-target="#page_area_rule">Контент</a> <a class="btn" href="<%var%>" data-toggle="insertContent" data-target="#page_area_rule"> Идентификация переменной </a> </div>
                </div>
                <span class="help-inline">支持phpQuery,格式DOM::选择器</span>
                <div class="clearfloat mb10"></div>
                <div class="input-prepend input-sp"><span class="add-on">分页链接规则</span>
                  <textarea name="rule[page_url_rule]" id="page_url_rule" class="span6"><?php echo $rule['page_url_rule'] ; ?></textarea>
                  <div class="btn-group btn-group-vertical"> <a class="btn" href="<%url%>" data-toggle="insertContent" data-target="#page_url_rule">URL</a> <a class="btn" href="<%var%>" data-toggle="insertContent" data-target="#page_url_rule"> Идентификация переменной </a> </div>
                </div>
                <span class="help-inline">URL-фильтр</span>
              </div>
              <div id="spider-page-url-parse" class="tab-pane">
                <div class="input-prepend input-append"><span class="add-on">当前网址分解</span>
                  <input type="text" name="rule[page_url_parse]" class="span6" id="page_url_parse" value="<?php echo $rule['page_url_parse'] ; ?>"/>
                  <a class="btn" href="<%url%>" data-toggle="insertContent" data-target="#page_url_parse">分页网址</a> </div>
                <div class="clearfloat mb10"></div>
                <div class="input-prepend input-append"><span class="add-on">分页增量</span> <span class="add-on">起始编号</span>
                  <input type="text" name="rule[page_no_start]" class="span1" id="page_no_start" value="<?php echo $rule['page_no_start'] ; ?>"/>
                  <span class="add-on"><i class="fa fa-arrows-h"></i></span> <span class="add-on">结束编号</span>
                  <input type="text" name="rule[page_no_end]" class="span1" id="page_no_end" value="<?php echo $rule['page_no_end'] ; ?>"/>
                  <span class="add-on"> Размер шага </span>
                  <input type="text" name="rule[page_no_step]" class="span1" id="page_no_step" value="<?php echo $rule['page_no_step'] ; ?>"/>
                  <span class="add-on">补位</span>
                  <input type="text" name="rule[page_no_fill]" class="span1" id="page_no_fill" value="<?php echo (int)$rule['page_no_fill'] ; ?>"/>
                  <span class="add-on"></span>
                </div>
              </div>
            </div>
            <div class="clearfloat mb10"></div>
            <hr />
            <div class="input-prepend input-sp"><span class="add-on">有效分页特征码</span>
              <textarea name="rule[page_url_right]" id="page_url_right" class="span6" ><?php echo $rule['page_url_right'] ; ?></textarea>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend input-sp"><span class="add-on">无效分页特征码</span>
              <textarea name="rule[page_url_error]" id="page_url_error" class="span6"><?php echo $rule['page_url_error'] ; ?></textarea>
            </div>
            <div class="clearfloat mb10"></div>
            <hr />
            <div class="input-prepend input-append"><span class="add-on">网址合成</span>
              <input type="text" name="rule[page_url]" class="span6" id="page_url" value="<?php echo $rule['page_url'] ; ?>"/>
              <a class="btn" href="<%url%>" data-toggle="insertContent" data-target="#page_url">分页网址</a> <a class="btn" href="<%step%>" data-toggle="insertContent" data-target="#page_url">分页增量</a> </div>
            <div class="clearfloat mb10"></div>
          </div>
          <div id="spider-remote" class="tab-pane">
            <div class="input-prepend"><span class="add-on">CURLOPT_ENCODING</span>
              <input type="text" name="rule[http][ENCODING]" class="span6" id="http_ENCODING" value="<?php echo $rule['http']['ENCODING'] ; ?>"/>
            </div>
            <span class="help-inline"><span class="label label-important">默认为空,如果采集乱码可以填上gzip,deflate</span></span>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"><span class="add-on">CURLOPT_REFERER</span>
              <input type="text" name="rule[http][REFERER]" class="span6" id="http_REFERER" value="<?php echo $rule['http']['REFERER'] ; ?>"/>
            </div>
            <span class="help-inline"><span class="label label-important">默认为空,如果网站限制来路可填上相关来路</span></span>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"><span class="add-on">CURLOPT_TIMEOUT</span>
              <input type="text" name="rule[http][TIMEOUT]" class="span6" id="http_TIMEOUT" value="<?php echo $rule['http']['TIMEOUT'] ; ?>"/>
            </div>
            <span class="help-inline"><span class="label label-important">默认为10秒,数据传输的最大允许时间</span></span>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"><span class="add-on">CURLOPT_CONNECTTIMEOUT</span>
              <input type="text" name="rule[http][CONNECTTIMEOUT]" class="span6" id="http_CONNECTTIMEOUT" value="<?php echo $rule['http']['CONNECTTIMEOUT'] ; ?>"/>
            </div>
            <span class="help-inline"><span class="label label-important">默认为3秒,连接超时时间</span></span>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend input-append"> <span class="add-on">水印设置</span><span class="add-on">
              <label class="radio">
                <input type="radio" name="rule[watermark_mode]" id="watermark_mode0" value="0"<?php if($rule['watermark_mode']=="0"){ echo ' checked="true"';};?>>
                系统全局 </label>
              </span>
              <span class="add-on">
                <label class="radio">
                  <input type="radio" name="rule[watermark_mode]" id="watermark_mode1" value="1"<?php if($rule['watermark_mode']=="1"){ echo ' checked="true"';};?>>
                  本规则
                </label>
              </span>
              <span class="add-on">
                <label class="radio">
                  <input type="radio" name="rule[watermark_mode]" id="watermark_mode2" value="2"<?php if($rule['watermark_mode']=="2"){ echo ' checked="true"';};?>>
                  关闭水印
                </label>
              </span>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"> <span class="add-on">Местоположение водяного знака</span>
              <select name="rule[watermark][pos]" id="watermark_pos" class="span3 chosen-select">
                <option value="0">Случайное</option>
                <option value="1">Вверху слева</option>
                <option value="2">Вверху по центру</option>
                <option value="3">Вверху справа</option>
                <option value="4">Центр слева</option>
                <option value="5">В центре</option>
                <option value="6">Центр справа</option>
                <option value="7">Внизу слева</option>
                <option value="8">Внизу по центру</option>
                <option value="9">Внизу справа</option>
                <option value="-1">Пользовательское регулярное выражения</option>
              </select>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend input-append"> <span class="add-on">Смещение положения водяного знака</span><span class="add-on">X</span>
              <input type="text" name="rule[watermark][x]" class="span1" id="watermark_x" value="<?php echo $rule['watermark']['x'] ; ?>"/>
              <span class="add-on">Y</span>
              <input type="text" name="rule[watermark][y]" class="span1" id="watermark_y" value="<?php echo $rule['watermark']['y'] ; ?>"/>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"> <span class="add-on">Изображения водяного знака</span>
              <input type="text" name="rule[watermark][img]" class="span3" id="watermark_img" value="<?php echo $rule['watermark']['img'] ; ?>"/>
            </div>
            <span class="help-inline">水印图片存放路径:conf/iCMS/watermark.png, 如果水印图片不存在,则使用文字水印</span>
            <div class="clearfloat mb10"></div>
          </div>
          <div id="spider-proxy" class="tab-pane">
            <div class="input-prepend"><span class="add-on">代理IP</span>
              <textarea name="rule[proxy]" id="rule_proxy" class="span6" style="height:150px;"><?php echo $rule['proxy'] ; ?></textarea>
            </div>
            <span class="help-inline">
              По одному на строку<br />
              socks5格式:socks5://127.0.0.1:1080@username:password<br />
              http格式:http://127.0.0.1:1080@username:password<br />
              Пример:127.0.0.1:1080 (默认为http模式 无验证信息)
            </span>
          </div>
        </div>
        <div class="form-actions">
          <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i>Отправить</button>
          <a id="test" href="<?php echo APP_URI; ?>&do=test&rid=<?php echo $this->rid ; ?>" class="btn btn-inverse" data-toggle="modal" title="测试规则"><i class="fa fa-keyboard-o"></i> Тестировать</a>
          <a href="<?php echo APP_URI; ?>&do=manage&rid=<?php echo $this->rid ; ?>" class="btn btn-success" target="_blank"><i class="fa fa-list-alt"></i>Правила парсера</a>
          <a href="<?php echo __ADMINCP__; ?>=spider_project&do=manage&rid=<?php echo $this->rid ; ?>" class="btn btn-info" target="_blank"><i class="fa fa-magnet"></i> Схема </a>
        </div>
      </form>
    </div>
  </div>
</div>
<table class="hide rule_data_clone">
  <tr>
    <?php echo rule_data_rule_td('__KEY__'); ?>
  </tr>
</table>
<div class="hide data_helper_clone">
  <?php foreach (spider_process::getArray() as $title => $collection){ ?>
    <li class="dropdown-submenu">
        <a href="javascript:;" title="<?php echo $title; ?>" class="dropdown-toggle tip-left" data-toggle="dropdown"><i class="fa fa-code"></i> <span><?php echo $title; ?></span></a>
        <ul class="dropdown-menu">
          <?php foreach ($collection as $act => $value){ ?>
            <li><a href="javascript:;" title="<?php echo $value[2]; ?>" data-act='<?php echo $act; ?>'> <i class="fa fa-magic"></i><span><?php echo $value[1]; ?></span></a></li>
          <?php } ?>
        </ul>
    </li>
  <?php } ?>
</div>
<?php admincp::foot();?>
