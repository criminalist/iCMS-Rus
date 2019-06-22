<?php

defined('iPHP') OR exit('Oops, something went wrong');
admincp::head();
?>
<style>
  hr{margin: 10px;}
</style>
<script>
$(function(){
  iCMS.select('checker',"<?php echo $rs['config']['checker']; ?>");
  iCMS.select('self',"<?php echo (int)$rs['config']['self']; ?>");
  var box = document.getElementById("mkurls");
  $('#makeurls').click(function(){
      iCMS.dialog({title:'URL адрес страницы источника',
        content:box,
        okValue: 'Ok',ok: function () {
          var urls_text = $("#urls").val();
          if(urls_text){
            urls_text+="\n";
          }
          var urls_rs = $("#urls_rs").val();
          $("#urls").val(urls_text+urls_rs);
        },
        cancelValue: "Отменить",cancel: function(){}
      });
  });
  $(":text",box).keyup(function(event) {
    if(this.id=='url_pattern'){
      return;
    }
    var pp = $(this).closest("div.input-prepend");
    var a  = $("[name='format']",pp);
    preview(a[0]);
  });
  $(":radio,:checkbox",box).click(function(event) {
    preview(this);
  });
  function preview(a){
    var pp  = $(a).closest("div.input-prepend"),
    format  = parseInt($("[name='format']",pp).val());
    begin   = $("[name='begin']",pp).val()||0,
    num     = $("[name='num']",pp).val()||0,
    step    = $("[name='step']",pp).val()||1,
    zeroize = $("[name='zeroize']",pp).prop("checked"),
    reverse = $("[name='reverse']",pp).prop("checked"),
    url     = $('#url_pattern').val(),
    start   = 0,
    end     = 0,
    pattern = '<'+format+','+begin+','+num+','+step+','+zeroize+','+reverse+'>';
    if(format=="2"){
      pattern = '<'+format+','+begin+','+num+','+reverse+'>';
    }
    $("[name='format']").prop("checked", false).parent().removeClass('checked');
    $("[name='format']",pp).prop("checked", true).parent().addClass('checked');

    if(url==""){
      iCMS.alert("Сначала введите URL");
      return;
    }
    if(url.indexOf("(*)")==-1){
      iCMS.alert("请使用(*)格式通配符匹配网址");
      return;
    }

    if(format==0){
      var num=parseInt(num)-1;
      if(num<0) num=0;
      start = parseInt(begin);
      // end   = start+parseInt(num);
      end = start+step*num;
    }else if(format==1){
      // if(parseInt(step)==1){
      //   iCMS.alert("等比不能为1");
      //   return;
      // }
      if(num>32){
        iCMS.alert("等比数列数值太大,请重新设置初始值,项数,比值");
        return;
      }
      start = parseInt(begin);
      // end   = start*Math.pow(parseInt(step), num-1);//parseInt(num)*parseInt(step);
      end = start+step*num;
    }else if(format==2){
      start = begin.charCodeAt(0);
      end   = num.charCodeAt(0);
    }
    urls = urlist(format,start,end,url,reverse,parseInt(step),zeroize,num);
    $("#urls_rs").val(url.replace('(*)',pattern));
    $("#preview").html(urls);
  }
  function urlist(format,start,end,url,reverse,step,zeroize,num){
    var urls = '',n = 0;
    if(format==2){
      num = end-start;
    }
    if(reverse){
      for(var i=end;i>=start;){
        if(n<5){
          urls+= _url(format,i,zeroize,end);
        }else{
          break;
        }
        n++;
        // if(format==1){
        //   i=i/step;
        // }else{
        // }
          i=i-step;
      }

      if(num>5){
        urls+= '..................<br />';
        urls+= _url(format,start,zeroize,end);
      }
    }else{
      for(var i=start;i<=end;){
        if(n<5){
          urls+= _url(format,i,zeroize,end)
        }else{
          break;
        }
        n++;
        // if(format==1){
        //   i=i*step;
        // }else{
        // }
          i=i+step;
      }
      if(num>5){
        urls+= '..................<br />';
        urls+= _url(format,end,zeroize,end);
      }
    }
    return urls;
  };
  function _url(format,i,zeroize,end){
      var ii = i
      if(format==2){
        ii = String.fromCharCode(i);
      }else{
        if(zeroize){
          var len = end.toString().length;
          if(len==1){
            len=2;
          }
          ii = pad(i,len);
        }
      }
      return url.replace('(*)',ii)+'<br />';
  }
})
</script>
<style>
#urls{height: 90px;font-size: 16px; font-family:"Consolas","Courier New",Courier,monospace}
</style>
<div class="iCMS-container">
  <div class="widget-box">
    <div class="widget-title"> <span class="icon"> <i class="fa fa-plus-square"></i> </span>
      <h5><?php echo empty($this->pid)?'Добавить':'Изменить' ; ?> Схема </h5>
    </div>
    <div class="widget-content nopadding">
      <form action="<?php echo APP_FURI; ?>&do=save" method="post" class="form-inline" id="iCMS-spider" target="iPHP_FRAME">
        <div id="addproject" class="tab-content">
          <input name="id" type="hidden" value="<?php echo $this->pid ; ?>" />
          <div class="input-prepend"><span class="add-on">Название программы</span>
            <input type="text" name="name" class="span6" id="name" value="<?php echo $rs['name']; ?>"/>
          </div>
          <div class="clearfloat mb10"></div>
          <div class="input-prepend input-append"><span class="add-on">Список URL-адресов</span>
            <textarea name="urls" id="urls" class="span6" style=""><?php echo $rs['urls'] ; ?></textarea>
            <a class="btn" id="makeurls">URL источника</a>
          </div>
          <div class="clearfloat mb10"></div>
          <div class="input-prepend input-append"><span class="add-on">内容网址合成</span>
            <input type="text" name="config[list_url]" class="span6" id="list_url" value="<?php echo $rs['config']['list_url'] ; ?>"/>
            <a class="btn" href="<%url%>" data-toggle="insertContent" data-target="#list_url">URL</a>
          </div>
          <div class="clearfloat mb10"></div>
          <div class="input-prepend"> <span class="add-on">Категория</span>
            <select name="cid" id="cid" class="chosen-select span3">
              <option value="0"> == Выберите категорию для привязки == </option>
              <?php echo $cata_option;?>
            </select>
          </div>
          <div class="clearfloat mb10"></div>
          <div class="input-prepend"> <span class="add-on"> Правила сбора </span>
            <select name="rid" id="rid" class="chosen-select span3">
              <option value="0"> == Выберите правило == </option>
              <?php echo $rule_option;?>
            </select>
          </div>
          <div class="clearfloat mb10"></div>
          <div class="input-prepend"> <span class="add-on">Правила публикации</span>
            <select name="poid" id="poid" class="chosen-select span3">
              <option value="0"> ==Выберите Правила публикации== </option>
              <?php echo $post_option;?>
            </select>
          </div>
          <div class="clearfloat mb10"></div>
          <div class="input-prepend"> <span class="add-on">Режим проверки</span>
            <select name="config[checker]" id="checker" class="chosen-select span3">
              <option value="0">Проверка на уникальность (дубликат)</option>
              <option value="1">Проверить по [URL]</option>
              <option value="2">Проверить по [Заголовок]</option>
              <option value="3">Проверьте по [URL]+[Заголовок]</option>
              <option value="4">Проверить наличие обновлений по [URL]</option>
              <option value="5">按[标题]检查更新</option>
              <option value="6">Проверьте обновления по [URL]+[Заголовок]</option>
              <option value="7">Проверить по [URL], 只更新[子采集]</option>
            </select>
          </div>
          <div class="clearfloat mb10"></div>
          <div class="input-prepend"><span class="add-on">检查范围</span>
            <select name="config[self]" id="self" class="chosen-select span3">
              <option value="0"> Все </option>
              <option value="1">仅限本方案</option>
              <option value="2">仅限本规则</option>
            </select>
          </div>
          <hr />
          <div class="input-prepend"><span class="add-on">Автоматический режим</span>
            <div class="switch">
              <input type="checkbox" data-type="switch" name="auto" id="auto" <?php echo $rs['auto']?'checked':''; ?>/>
            </div>
          </div>
          <span class="help-inline">Эта опция предназначена только для идентификации. Если вы хотите реализовать автоматический сбор, вам необходимо развернуть запланированные задачи на сервере.</span>
          <div class="clearfloat mb10"></div>
          <div class="input-prepend input-append"><span class="add-on">Интервал</span>
            <input type="text" name="config[sleep]" class="span1" id="sleep" value="<?php echo $rs['config']['sleep']; ?>"/>
            <span class="add-on">Миллисекунды</span>
          </div>
          <span class="help-inline">单条数据采集的间隔 (автоматический режим)</span>
          <div class="clearfloat mb10"></div>
          <div class="input-prepend input-append"><span class="add-on">Интервал схемы</span>
            <input type="text" name="config[psleep]" class="span1" id="psleep" value="<?php echo $rs['config']['psleep']; ?>"/>
            <span class="add-on">сек.</span>
          </div>
          <span class="help-inline">本方案下次采集的间隔 (автоматический режим)</span>
          <div class="clearfloat mb10"></div>
          <div class="input-prepend"> <span class="add-on">完成时间</span>
            <input id="lastupdate" class="span3" value="<?php echo $rs['lastupdate']?get_date($rs['lastupdate'],'Y-m-d H:i:s'):'' ; ?>"  name="lastupdate" type="text" style="width:230px"/>
          </div>
          <span class="help-inline">Время завершения выполнения  (автоматический режим)</span>
          <div class="clearfloat mb10"></div>
        </div>
        <div class="form-actions">
          <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i>Отправить</button>
          <?php if($this->pid){?>
          <a href="<?php echo __ADMINCP__; ?>=spider_rule&do=test&pid=<?php echo $this->pid; ?>" class="btn" data-toggle="modal" title="测试方案"><i class="fa fa-keyboard-o"></i> Тестировать</a>
          <?php }?>
        </div>
      </form>
    </div>
  </div>
</div>
<div id="mkurls" class="hide">
  <div class="input-prepend input-append"><span class="add-on">Формат адреса</span>
    <input type="text" class="span6" id="url_pattern" value=""/>
    <input type="hidden" id="urls_rs" value=""/>
    <a class="btn" href="(*)" data-toggle="insertContent" data-target="#url_pattern">(*)</a>
  </div>
  <hr>
  <div class="input-prepend input-append">
    <span class="add-on"><input type="radio" name="format" value="0"/> Арифметическая прогрессия</span>
    <span class="add-on"> Начальное значение </span>
    <input type="text" class="span1" name="begin" value="1"/>
    <span class="add-on">项数</span>
    <input type="text" class="span1" name="num" value="5"/>
    <span class="add-on"> Размер шага </span>
    <input type="text" class="span1" name="step" value="1"/>
    <span class="add-on">
    <input type="checkbox" name="zeroize"/>
    补零</span>
    <span class="add-on">
    <input type="checkbox" name="reverse"/>
    倒序</span>
  </div>
  <hr>
  <div class="input-prepend input-append">
    <span class="add-on"><input type="radio" name="format" value="1"/> 等比数列</span>
    <span class="add-on"> Начальное значение </span>
    <input type="text" class="span1" name="begin" value="1"/>
    <span class="add-on">项数</span>
    <input type="text" class="span1" name="num" value="5"/>
    <span class="add-on">比值</span>
    <input type="text" class="span1" name="step" value="2"/>
    <span class="add-on">
    <input type="checkbox" name="zeroize"/>
    补零</span>
    <span class="add-on">
    <input type="checkbox" name="reverse"/>
    倒序</span>
  </div>
  <hr>
  <div class="input-prepend input-append">
    <span class="add-on"><input type="radio" name="format" value="2"/> 字母变化</span>
    <span class="add-on">从</span>
    <input type="text" class="span1" name="begin" value="a" maxlength="1"/>
    <span class="add-on">到</span>
    <input type="text" class="span1" name="num" value="z" maxlength="1"/>
    (区分大小写)
    <span class="add-on">
    <input type="checkbox" name="reverse"/>
    倒序</span>
  </div>
  <div class="well" id="preview">
  </div>
</div>
<?php admincp::foot();?>
