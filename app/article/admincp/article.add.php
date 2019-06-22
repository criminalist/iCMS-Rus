<?php

defined('iPHP') OR exit('Oops, something went wrong');
admincp::head();
?>
<?php if(self::$config['markdown']){?>
<link rel="stylesheet" href="./app/admincp/ui/editor.md/css/editormd.min.css" />
<style>
.editormd-menu>li{padding: 0px;margin-top: 4px;}
.editormd-form input[type=text], .editormd-form input[type=number]{height: 36px;}
.editormd-form input[type=number]{width: 45px !important;}
.editormd-dialog-footer{padding: 0px;}
.editormd-form input[type=text], .editormd-form input[type=number]{color: #999 !important;border:1px solid #ddd;}
.editormd-dialog-container .editormd-btn, .editormd-dialog-container button, .editormd-dialog-container input[type=submit], .editormd-dialog-footer .editormd-btn, .editormd-dialog-footer button, .editormd-dialog-footer input[type=submit], .editormd-form .editormd-btn, .editormd-form button, .editormd-form input[type=submit]{padding: 1px 2px;}
</style>
<script type="text/javascript" charset="utf-8" src="./app/admincp/ui/editor.md/editormd.min.js"></script>
<script type="text/javascript" charset="utf-8" src="./app/admincp/ui/iCMS.editormd.js"></script>
<?php }else{?>
<script type="text/javascript">
window.catchRemoteImageEnable = <?php echo iCMS::$config['article']['catch_remote']?'true':'false';?>;
</script>
<script type="text/javascript" charset="utf-8" src="./app/admincp/ui/iCMS.ueditor.js"></script>
<script type="text/javascript" charset="utf-8" src="./app/admincp/ui/ueditor/ueditor.all.min.js"></script>
<?php }?>

<script type="text/javascript">
<?php if(self::$config['markdown']){?>
  var iEditor = iCMS.editormd;
<?php }else{?>
  var iEditor = iCMS.editor;
<?php }?>
$(function(){
  // $("#title").focus();

  iEditor.create('editor-body-1');
	$(".editor-page").change(function(){
		$(".editor-container").hide();
		$("#editor-wrap-"+this.value).show();
    iEditor.create('editor-body-'+this.value).focus();
		$(".editor-page").val(this.value).trigger("chosen:updated");
	});

  iCMS.select('pid',"<?php echo $rs['pid']?trim($rs['pid']):0 ; ?>");
  iCMS.select('cid',"<?php echo $cid; ?>");
  iCMS.select('scid',"<?php echo trim($rs['scid']);?>");
  iCMS.select('status',"<?php echo $rs['status']; ?>");
  $('#inbox').click(function(){
    if($(this).prop("checked")){
      iCMS.select('status',"0");
    }else{
      iCMS.select('status',"1");
    }
  })

  $('#ischapter').click(function(){
    var checkedStatus = $(this).prop("checked"),chapter = $("input[name=chapter]").val();
    subtitleToggle (checkedStatus);
    if(!checkedStatus && chapter>1){
      return confirm('Вы добавили другие главы раньше! Вы уверены, что хотите отменить режим главы?');
    }
  })
  var hotkey = false;

	$("#<?php echo APP_FORMID;?>").submit(function(){
    if(hotkey){
        if(this.action.indexOf('&keyCode=ctrl-s')===-1){
          this.action+='&keyCode=ctrl-s';
        }
    }

    var cid = $("#cid option:selected").val();
		if(cid=="0"){
      $("#cid").focus();
			iCMS.alert("Выберите категорию");
			return false;
		}
		if($("#title").val()==''){
      $("#title").focus();
			iCMS.alert("Заголовок не может быть пустым!");
			return false;
		}
		if($("#url").val()==''){
			var n=$(".editor-page:eq(0) option:first").val(),ed = iEditor.get('editor-body-'+n);
      <?php if(self::$config['markdown']){?>
        if(!ed.getMarkdown()){
      <?php }else{?>
        if(!ed.hasContents()){
      <?php }?>
        ed.focus();
				iCMS.alert("Содержание "+n+" не может быть пустым!");
				$('#editor-wrap-'+n).show();
				$(".editor-page").val(n).trigger("chosen:updated");
				return false;
			}
		}
    // if($('#ischapter').prop("checked") && $("#subtitle").val()==''){
    //   $("#subtitle").focus();
    //   iCMS.alert("Заголовок главы не может быть пустым!");
    //   return false;
    // }
	});
  $(document).keydown(function (e) {
    var keyCode = e.keyCode || e.which || e.charCode;
    var ctrlKey = e.ctrlKey || e.metaKey;
    if(ctrlKey && keyCode == 83) {
        e.preventDefault();
        hotkey = true;
        $("#<?php echo APP_FORMID;?>").submit();
    }
    hotkey = false;
  });
  <?php if(self::$config['repeatitle']){?>
  $("#title").focus(function() {
    var me = $(this);
    var isblur = me.data('blur');
    $("#title-help").text('');
    console.log('isblur',Boolean(isblur));
    if(Boolean(isblur)){
      me.unbind('blur');
      me.data('blur',false);
    }

    me.bind("blur",function(e){
      me.data('blur',true);
      var title = me.val();
      $.getJSON("<?php echo APP_URI; ?>",{'do':'check','id':"<?php echo $this->id ; ?>",'title':title},
        function(json) {
          if(!json.code){
            $("#title-help").html('<span class="label label-important">'+json.msg+'</span>');
          }
          me.unbind('blur');
        }
      );
    });
  });
  <?php }?>

});

function mergeEditorPage(){
  <?php if(self::$config['markdown']){?>
    return;
  <?php }?>
  var html = [];
  $(".editor-container").each(function(n,a){
    var eid = a.id.replace('editor-wrap-','editor-body-');
    if(iEditor.container[eid]){
        iEditor.container[eid].destroy();
    }
    var content = $("textarea",this).val();
    content && html.push(content);
    if(n){
        $(this).remove();
    }
  });

  $(".editor-container").show();
  var allHtml = html.join('#--iCMS.PageBreak--#'),
  ned = $("textarea",".editor-container"),
  neid = $(".editor-container").attr('id').replace('editor-wrap-','editor-body-');
  ned.val(allHtml).css({
    width: "100%",
    height: '500px'
  });
  iEditor.create(neid).focus();
  $(".editor-page").html('<option value="1">Страница 1</option>').val(1).trigger("chosen:updated");
}
function addEditorPage(){
	//iCMSed.cleanup(iCMSed.id);
	var index	= parseInt($(".editor-page option:last").val()),n	= index+1;
	$(".editor-container").hide();
	$("#editor-wrap-"+index).after(
    '<div id="editor-wrap-'+n+'" class="editor-container">'+
      '<div class="chapter-title hide">'+
        '<input name="data_id[]" id="data_id-'+n+'" type="hidden" disabled="true" value="" />'+
        '<div class="input-prepend"> <span class="add-on" style="width:60px;">Добавить новую страницу</span>'+
            '<input type="text"  id="chapter-title-'+n+'" disabled="true" name="chaptertitle[]" class="span6" value="" />'+
        '</div>'+
        '<div class="clearfloat mb10"></div>'+
      '</div>'+
<?php if(self::$config['markdown']){?>
      '<div id="editor-body-'+n+'">'+
        '<textarea type="text/plain" name="body[]"></textarea>'+
      '</div>'+
<?php }else{?>
      '<textarea type="text/plain" id="editor-body-'+n+'" name="body[]"></textarea>'+
<?php }?>
    '</div>'
  );
	$(".editor-page").append('<option value="'+n+'">Страницы '+n+'</option>').val(n).trigger("chosen:updated");
	iEditor.create('editor-body-'+n).focus();
  var checkedStatus = $('#ischapter').prop("checked");
  subtitleToggle (checkedStatus);
}
function subtitleToggle (checkedStatus) {
  if(checkedStatus){
    $(".subtitle-box").hide();
    $("input",".subtitle-box").attr("disabled","disabled");
    $(".chapter-title").show();
    $("input",".chapter-title").removeAttr("disabled");

    var data_id = $("[name='data_id']",".subtitle-box").val();
    $("[name='data_id[]']",".chapter-title").eq(0).val(data_id);
    var subtitle = $("[name='subtitle']",".subtitle-box").val();
    $("[name='chaptertitle[]']",".chapter-title").eq(0).val(subtitle);

  }else{
    $(".subtitle-box").show();
    $("input",".subtitle-box").removeAttr("disabled");
    var data_id = $("[name='data_id[]']",".chapter-title").eq(0).val();
    $("[name='data_id']",".subtitle-box").val(data_id);
    var subtitle = $("[name='chaptertitle[]']",".chapter-title").eq(0).val();
    $("[name='subtitle']",".subtitle-box").val(subtitle);

    $(".chapter-title").hide();
    $("input",".chapter-title").attr("disabled","disabled");
  }
}
function delEditorPage(){
	if($(".editor-page:eq(0) option").length==1) return;

	var s = $(".editor-page option:selected"),
    i = s.val(),p = s.prev(),n = s.next();
	if(n.length){
    var index = n.val();
	}else if(p.length){
    var index = p.val();
	}
  s.remove();
  iEditor.destroy('editor-body-'+i);
  $("#editor-body-"+i).remove();
  $("#editor-wrap-"+i).remove();

	$(".editor-page").val(index).trigger("chosen:updated");
	$("#editor-wrap-"+index).show();
	iEditor.eid	= 'editor-body-'+index;
	iEditor.get('editor-body-'+index).focus();
}
function modal_picture(el,a){
  if(!a.checked) return;
  var ed = iEditor.get(),
  url = $(a).attr("url");
  // if(a.checked){
  var imgObj  = {};
  imgObj.src  = url;
  imgObj._src = url;
	ed.fireEvent('beforeInsertImage', imgObj);
	ed.execCommand("insertImage", imgObj);
  _modal_dialog("继续选择");
  // }else{
  //   var html = ed.getContent(),
  //   img = '<img src="'+url+'"/>';

  //   html = html.replace(img,'');
  //   log(html);
  // }
	return true;
}
function modal_sweditor(el){
  if(!el.checked) return;

  var e    = $(el),
  image    = e.attr('_image'),
  fileType = e.attr('_fileType'),
  original = e.attr('_original'),
  url      = e.attr('url'),
  ed       = iEditor.get();

  if(url=='undefined') return;
  var html = '<p class="attachment icon_'+fileType+'"><a href="'+url+'" target="_blank">' + original + '</a></p>';

  if(image=="1") html='<p><img src="'+url+'" /></p>';

	ed.execCommand("insertHTML",html);
  _modal_dialog("Продолжить загрузку");
}
function _modal_dialog(cancel_text){
  iCMS.dialog({
      content:'Вставлено успешно!',
      okValue: 'Закрыть',
      ok: function () {
        window.iCMS_MODAL.destroy();
        return true;
      },
      cancelValue: cancel_text,
      cancel: function(){
        return true;
      }
  });
}
</script>

<div class="iCMS-container">
  <div class="widget-box">
    <div class="widget-title"> <span class="icon"> <i class="fa fa-pencil"></i> </span>
      <h5 class="brs"><?php echo empty($this->id)?'Добавить':'Изменить' ; ?> документ</h5>
      <ul class="nav nav-tabs" id="article-add-tab">
        <li class="active"><a href="#article-add-base" data-toggle="tab"><i class="fa fa-info-circle"></i>Основная информация</a></li>
        <li><a href="#article-add-publish" data-toggle="tab"><i class="fa fa-rocket"></i> Настройки публикации</a></li>
        <li><a href="#article-add-custom" data-toggle="tab"><i class="fa fa-wrench"></i>Пользовательские поля</a></li>
        <li><a href="#apps-metadata" data-toggle="tab"><i class="fa fa-sitemap"></i>Динамические свойства</a></li>
      </ul>
    </div>
    <div class="widget-content nopadding">
      <form action="<?php echo APP_FURI; ?>&do=save" method="post" class="form-inline" id="<?php echo APP_FORMID;?>" target="iPHP_FRAME">
        <input name="_cid" type="hidden" value="<?php echo $rs['cid'] ; ?>" />
        <input name="_scid" type="hidden" value="<?php echo $rs['scid']; ?>" />
        <input name="_tags" type="hidden" value="<?php echo $rs['tags']; ?>" />
        <input name="_pid" type="hidden" value="<?php echo $rs['pid']; ?>" />
        <input name="_data_id" type="hidden" value='<?php echo $adIdArray?json_encode($adIdArray):''; ?>' />

        <input name="article_id" type="hidden" value="<?php echo $this->id ; ?>" />
        <input name="userid" type="hidden" value="<?php echo $rs['userid'] ; ?>" />
        <input name="ucid" type="hidden" value="<?php echo $rs['ucid'] ; ?>" />
        <input name="postype" type="hidden" value="<?php echo $rs['postype'] ; ?>" />
        <input name="REFERER" type="hidden" value="<?php echo iPHP_REFERER ; ?>" />
        <input name="chapter" type="hidden" value="<?php echo $rs['chapter']; ?>" />
        <input name="markdown" type="hidden" value="<?php echo self::$config['markdown']; ?>" />
        <div id="article-add" class="tab-content">
          <div id="article-add-base" class="tab-pane active">
            <div class="input-prepend"> <span class="add-on">Категория</span>
              <select name="cid" id="cid" class="chosen-select span3" data-placeholder="== Выберите категорию ==">
                <?php echo $cata_option;?>
              </select>
            </div>
            <div class="input-prepend input-append"> <span class="add-on"> Статус </span>
              <select name="status" id="status" class="chosen-select span3">
                <option value="0"> Черновик [status='0']</option>
                <option value="1"> Опубликован [status='1']</option>
                <option value="2"> Корзина [status='2']</option>
                <option value="3"> На рассмотрении [status='3']</option>
                <option value="4"> Отказано [status='4']</option>
                <?php echo propAdmincp::get("status") ; ?>
              </select>
              <?php echo propAdmincp::btn_add('Добавить новый статус');?>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"> <span class="add-on">Подкатегории</span>
              <select name="scid[]" id="scid" class="chosen-select span6" multiple="multiple"  data-placeholder="Выберите подкатегорию (несколько вариантов)...">
                <?php echo $cata_option;?>
              </select>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend input-append"> <span class="add-on">Свойства</span>
              <select name="pid[]" id="pid" class="chosen-select span6" multiple="multiple">
                <option value="0">Обычный документ[pid='0']</option>
                <?php echo propAdmincp::get("pid") ; ?>
              </select>
              <?php echo propAdmincp::btn_add('Добавить общие свойства');?>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"> <span class="add-on">Заголовок</span>
              <input type="text" name="title" class="span6" id="title" value="<?php echo $rs['title'] ; ?>"/>
            </div>
            <span class="help-inline" id="title-help"></span>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"> <span class="add-on">Краткое название</span>
              <input type="text" name="stitle" class="span6" id="stitle" value="<?php echo $rs['stitle'] ; ?>"/>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend input-append"> <span class="add-on">Источник</span>
              <input type="text" name="source" class="span2" id="source" value="<?php echo $rs['source'] ; ?>"/>
              <?php echo propAdmincp::btn_group("source");?>
              <?php echo propAdmincp::btn_add('Добавить источник');?>
            </div>
            <div class="input-prepend input-append"> <span class="add-on">Автор</span>
              <input type="text" name="author" class="span2" id="author" value="<?php echo $rs['author'] ; ?>"/>
              <?php echo propAdmincp::btn_group("author");?>
              <?php echo propAdmincp::btn_add('Добавить автора');?>
            </div>
            <div class="input-prepend input-append"> <span class="add-on">Редактор</span>
              <input type="text" name="editor" class="span2" id="editor" value="<?php echo $rs['editor'] ; ?>"/>
              <a class="btn tip-right" href="<?php echo __ADMINCP__; ?>=members&do=profile" target="_blank" title="Изменить"><i class="fa fa-pencil-square-o"></i></a>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend input-append"> <span class="add-on">Иконка</span>
              <input type="text" name="pic" class="span6" id="pic" value="<?php echo $rs['pic'] ; ?>"/>
              <?php filesAdmincp::set_opt($rs['pic'])->pic_btn("pic",$this->id);?>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend input-append"> <span class="add-on">Иконка 2</span>
              <input type="text" name="mpic" class="span6" id="mpic" value="<?php echo $rs['mpic'] ; ?>"/>
              <?php filesAdmincp::set_opt($rs['mpic'])->pic_btn("mpic",$this->id);?>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend input-append"> <span class="add-on">Иконка 3</span>
              <input type="text" name="spic" class="span6" id="spic" value="<?php echo $rs['spic'] ; ?>"/>
              <?php filesAdmincp::set_opt($rs['spic'])->pic_btn("spic",$this->id);?>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"> <span class="add-on">Ключевое слово</span>
              <input type="text" name="keywords" class="span6" id="keywords" value="<?php echo $rs['keywords'] ; ?>" onkeyup="javascript:this.value=this.value.replace(/,/ig,',');"/>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend input-append"> <span class="add-on">Теги</span>
              <input type="text" name="tags" class="span6" id="tags" value="<?php echo $rs['tags'] ; ?>" onkeyup="javascript:this.value=this.value.replace(/,/ig,',');"/>
              <button id="tag_extrac" data-target="#tags" class="btn" type="button" title="Получить теги автоматически"><i class="fa fa-magic"></i></button>
            </div>
            <script>
            $(function(){
                var api_url = "<?php echo __ADMINCP__; ?>=tag&do=api_extract&CSRF_TOKEN=<?php echo iPHP_WAF_CSRF_TOKEN;?>";
                $("#tag_extrac").click(function(event) {
                  var that = this;
                  var title = $("#title").val();
                  var n=$(".editor-page:eq(0) option:first").val(),ed = iEditor.get('editor-body-'+n);
                  <?php if(self::$config['markdown']){?>
                  var content = ed.getMarkdown();
                  <?php }else{?>
                    var content = ed.getContent();
                  <?php }?>
                $.post(api_url,{title: title,content:content},
                  function(data) {
                    var target = $(that).data('target');
                    if(data.length){
                      $(target).val(data.join(','));
                    }
                  }
                ,'json');
              });
            });
            </script>
            <span class="help-inline">Разделяйте запятой</span>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend" style="width:100%;"><span class="add-on">SEO Описание</span>
              <textarea name="description" id="description" class="span6" style="height: 150px;"><?php echo $rs['description'] ; ?></textarea>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="subtitle-box <?php if($rs['chapter']){ echo ' hide';}?>" >
              <input name="data_id" type="hidden" value="<?php echo $adRs['id']; ?>" <?php if($rs['chapter']){ echo ' disabled="true"';}?>/>
              <div class="input-prepend "> <span class="add-on">Подзаголовок</span>
                  <input type="text" name="subtitle" class="span6" id="subtitle" value="<?php echo $adRs['subtitle'] ; ?>" <?php if($rs['chapter']){ echo ' disabled="true"';}?>/>
              </div>
              <div class="clearfloat mb10"></div>
            </div>
              <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i>Отправить</button>
              <div class="input-prepend">
                <span class="add-on"><i class="fa fa-building-o"></i> Содержание</span>
                <select class="editor-page chosen-select">
                <?php
                  $option ='';
                  for($i=0;$i<$bodyCount;$i++){
                    $idNum  = $i+1;
                    $option .= '<option value="'.$idNum.'">Страница '.$idNum.' / '.$bodyCount.'</option>';
                  }
                  echo $option;
                ?>
                </select>
              </div>
              <div class="input-prepend">
                <div class="btn-group">
                  <button type="button" class="btn" onclick="javascript:addEditorPage();"><i class="fa fa-file-o"></i> Добавить новую страницу</button>
                  <button type="button" class="btn" onclick="javascript:delEditorPage();"><i class="fa fa-times-circle"></i> Удалить текущую страницу</button>
                <?php if(!self::$config['markdown']){?>
                  <button type="button" class="btn" onclick="javascript:mergeEditorPage();"><i class="fa fa-align-justify"></i> 合并编辑</button>
                <?php }?>
                  <button type="button" class="btn" onclick="javascript:iEditor.insPageBreak();"><i class="fa fa-ellipsis-h"></i>Вставить разрыв страницы</button>
                  <button type="button" class="btn" onclick="javascript:iEditor.delPageBreakflag();"><i class="fa fa-ban"></i> Удалить разрыв страницы</button>
                <?php if(!self::$config['markdown']){?>
                  <button type="button" class="btn" onclick="javascript:iEditor.cleanup();"><i class="fa fa-magic"></i> 自动排版</button>
                <?php }?>
                </div>
              </div>
              <!--div class="btn-group">
                <button type="button" class="btn" href="<?php echo __ADMINCP__; ?>=files&do=multi&from=modal&callback=sweditor" data-toggle="modal" title="Мультизагрузка файлов"><i class="fa fa-upload"></i> Мультизагрузка файлов</button>
                <button type="button" class="btn" href="<?php echo __ADMINCP__; ?>=files&do=picture&from=modal&click=file&callback=picture" data-toggle="modal" title="Выберите картинку с сайта"><i class="fa fa-picture-o"></i> Выбрать на сервере</button>
              </div-->
            <div class="clearfloat mb10"></div>
            <div class="input-prepend input-append">
              <span class="add-on wauto">
              <input name="ischapter" type="checkbox" id="ischapter" value="1" <?php if($rs['chapter']) echo 'checked="checked"'  ?>/>
              Режим главы</span>
              <span class="add-on wauto">
              <input name="remote" type="checkbox" id="remote" value="1" <?php if(self::$config['remote']=="1")echo 'checked="checked"'  ?>/>
              Загрузить удаленные изображения</span>
              <span class="add-on wauto">
              <input name="autopic" type="checkbox" id="autopic" value="1" <?php if(self::$config['autopic']=="1")echo 'checked="checked"'  ?>/>
              Сделать первую картинку эскизом </span>
              <span class="add-on wauto">
              <input name="dellink" type="checkbox" id="dellink" value="1"/>
              Очистить ссылки
              </span>
              <?php if(iCMS::$config['watermark']['enable']=="1"){ ?>
              <span class="add-on wauto">
                <input name="iswatermark" type="checkbox" id="iswatermark" value="1" />Не добавлять водяные знаки
              </span>
              <?php }?>
        <?php if(self::$config['markdown']){?>
              <a class="btn tip-top" href="<?php echo iURL::make('ui_editor=ueditor'); ?>" title="Сначала сохраните данные"><i class="fa fa-edit"></i> Ueditor</a>
        <?php }else{?>
              <a class="btn tip-top" href="<?php echo iURL::make('ui_editor=markdown'); ?>" title="Сначала сохраните данные"><i class="fa fa-edit"></i> Markdown</a>
        <?php }?>
            </div>
            <div class="clearfloat mb10"></div>
            <?php for($i=0;$i<$bodyCount;$i++){
                $idNum  = $i+1;
            ?>
            <div id="editor-wrap-<?php echo $idNum;?>" class="editor-container<?php if($i){ echo ' hide';}?>">
              <div class="chapter-title <?php if(!$rs['chapter']){ echo ' hide';}?>">
                <input name="data_id[]" id="data_id-<?php echo $idNum;?>" <?php if(!$rs['chapter']){ echo ' disabled="true"';}?> type="hidden" value="<?php echo $adIdArray[$i] ; ?>" />
                <div class="input-prepend">
                  <span class="add-on" style="width:60px;">Добавить новую страницу</span>
                  <input type="text" id="chapter-title-<?php echo $idNum;?>" <?php if(!$rs['chapter']){ echo ' disabled="true"';}?> name="chaptertitle[]" class="span6" value="<?php echo $cTitArray[$i] ; ?>" />
                </div>
                <div class="clearfloat mb10"></div>
              </div>
        <?php if(self::$config['markdown']){?>
              <div id="editor-body-<?php echo $idNum;?>">
                <textarea type="text/plain" name="body[]"><?php echo $bodyArray[$i];?></textarea>
              </div>
        <?php }else{?>
              <textarea type="text/plain" id="editor-body-<?php echo $idNum;?>" name="body[]"><?php echo $bodyArray[$i];?></textarea>
        <?php }?>
            </div>
            <?php }?>

            <div class="clearfloat mb10"></div>
            <div class="input-prepend"> <span class="add-on"><i class="fa fa-building-o"></i> Содержание</span>
              <select class="editor-page chosen-select">
              <?php echo $option;?>
              </select>
            </div>
            <div class="input-prepend">
              <div class="btn-group">
                <button type="button" class="btn" onclick="javascript:addEditorPage();"><i class="fa fa-file-o"></i> Добавить новую страницу</button>
                <button type="button" class="btn" onclick="javascript:delEditorPage();"><i class="fa fa-times-circle"></i> Удалить текущую страницу</button>
              <?php if(!self::$config['markdown']){?>
                <button type="button" class="btn" onclick="javascript:mergeEditorPage();"><i class="fa fa-align-justify"></i> 合并分页</button>
              <?php }?>
                <button type="button" class="btn" onclick="javascript:iEditor.insPageBreak();"><i class="fa fa-ellipsis-h"></i>Вставить разрыв страницы</button>
                <button type="button" class="btn" onclick="javascript:iEditor.delPageBreakflag();"><i class="fa fa-ban"></i> Удалить разрыв страницы</button>
              <?php if(!self::$config['markdown']){?>
                <button type="button" class="btn" onclick="javascript:iEditor.cleanup();"><i class="fa fa-magic"></i> 自动排版</button>
              <?php }?>
              </div>
            </div>
          </div>
          <div id="article-add-publish" class="tab-pane hide">
            <div class="input-prepend"> <span class="add-on">Время публикации</span>
              <input id="pubdate" class="<?php echo $readonly?'':'ui-datepicker'; ?>" value="<?php echo $rs['pubdate']?$rs['pubdate']:get_date(0,'Y-m-d H:i:s') ; ?>"  name="pubdate" type="text" style="width:230px" <?php echo $readonly ; ?>/>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"> <span class="add-on">Сортировка</span>
              <input id="sortnum" class="span2" value="<?php echo $rs['sortnum']?$rs['sortnum']:time() ; ?>" name="sortnum" type="text"/>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"> <span class="add-on">Вес</span>
              <input id="weight" class="span2" value="<?php echo $rs['weight']?$rs['weight']:time(); ?>" name="weight" type="text"/>
            </div>
            <div class="clearfix mb10"></div>
            <div class="input-prepend input-append">
              <span class="add-on">Общее количество просмотров</span>
              <input type="text" name="hits" class="span1" id="hits" value="<?php echo $rs['hits']?$rs['hits']:'0'; ?>"/>
              <span class="add-on">Просмотры за сутки</span>
              <input type="text" name="hits_today" class="span1" id="hits_today" value="<?php echo $rs['hits_today']?$rs['hits_today']:'0'; ?>"/>
              <span class="add-on">Просмотры за день</span>
              <input type="text" name="hits_yday" class="span1" id="hits_yday" value="<?php echo $rs['hits_yday']?$rs['hits_yday']:'0'; ?>"/>
              <span class="add-on">Просмотры за неделю</span>
              <input type="text" name="hits_week" class="span1" id="hits_week" value="<?php echo $rs['hits_week']?$rs['hits_week']:'0'; ?>"/>
              <span class="add-on">Просмотры за месяц</span>
              <input type="text" name="hits_month" class="span1" id="hits_month" value="<?php echo $rs['hits_month']?$rs['hits_month']:'0'; ?>"/>
            </div>
            <div class="clearfix mb10"></div>
            <div class="input-prepend input-append">
              <span class="add-on">В избранном</span>
              <input type="text" name="favorite" class="span1" id="favorite" value="<?php echo $rs['favorite']?$rs['favorite']:'0'; ?>"/>
              <span class="add-on">Комментариев</span>
              <input type="text" name="comments" class="span1" id="comments" value="<?php echo $rs['comments']?$rs['comments']:'0'; ?>"/>
              <span class="add-on">Понравилось</span>
              <input type="text" name="good" class="span1" id="good" value="<?php echo $rs['good']?$rs['good']:'0'; ?>"/>
              <span class="add-on">Не понравилось</span>
              <input type="text" name="bad" class="span1" id="bad" value="<?php echo $rs['bad']?$rs['bad']:'0'; ?>"/>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend input-append"> <span class="add-on"> Шаблон </span>
              <input type="text" name="tpl" class="span6" id="tpl" value="<?php echo $rs['tpl'] ; ?>"/>
              <?php echo filesAdmincp::modal_btn('Шаблон','tpl');?>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"> <span class="add-on">Пользовательский URL</span>
              <input type="text" name="clink" class="span6" id="clink" value="<?php echo $rs['clink'] ; ?>"/>
            </div>
            <span class="help-inline">Для того чтобы использовать пользовательскую ссылку необходимо в правилах формирования URL страницы включить переменную {LINK}, либо принудительно указать что URL является пользовательским с помощью квадратных скобок [], пример: [пользовательский-URL.html]</span>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"> <span class="add-on"> Внешняя ссылка </span>
              <input type="text" name="url" class="span6 tip" title="Примечание: содержимое в редакторе не будет сохранено после того, как установлена внешняя ссылка!" id="url" value="<?php echo $rs['url'] ; ?>"/>
               </div><span class="help-inline">Оставьте поле пустым, если не требуется редирект по внешнней ссылки</span>
            <div class="clearfloat mb10"></div>
          </div>
          <div id="article-add-custom" class="tab-pane hide">
            <?php echo former::layout();?>
          </div>
          <div id="apps-metadata" class="tab-pane hide">
            <?php include admincp::view("apps.meta","apps");?>
          </div>
        </div>
        <div class="form-actions">
          <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i>Отправить</button>
        </div>
      </form>
    </div>
  </div>
</div>
<div id="category_select" style="display: none;">
    <input class="form-control input-lg" id="user_category_new" type="text" placeholder="Введите название категории">
</div>
<?php admincp::foot();?>
