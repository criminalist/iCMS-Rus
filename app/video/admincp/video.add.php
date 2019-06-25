<?php

defined('iPHP') OR exit('What are you doing?');
admincp::head();
?>
<script type="text/javascript">
window.catchRemoteImageEnable = <?php echo iCMS::$config['video']['catch_remote']?'true':'false';?>;
</script>
<script type="text/javascript" charset="utf-8" src="./app/admincp/ui/iCMS.ueditor.js"></script>
<script type="text/javascript" charset="utf-8" src="./app/admincp/ui/ueditor/ueditor.all.min.js"></script>
<script type="text/javascript">
$(function(){
  $("#title").focus();

  iCMS.select('pid',"<?php echo $rs['pid']?trim($rs['pid']):0 ; ?>");
  iCMS.select('cid',"<?php echo $cid; ?>");
  iCMS.select('scid',"<?php echo trim($rs['scid']);?>");
  iCMS.select('status',"<?php echo $rs['status']; ?>");

  $("#<?php echo APP_FORMID;?>").submit(function(){
    var cid = $("#cid option:selected").val();
		if(cid=="0"){
      $("#cid").focus();
			iCMS.alert("Выберите категорию для привязки");
			return false;
		}
		if($("#title").val()==''){
      $("#title").focus();
			iCMS.alert("Заголовок не может быть пустым!");
			return false;
		}
		if($("#url").val()==''){
			// var n=$(".iCMS-editor-page:eq(0) option:first").val(),ed = iCMS.editor.get(n);
			// if(!ed.hasContents()){
   //      ed.focus();
			// 	iCMS.alert("第"+n+"页内容不能为空!");
			// 	$('#editor-'+n).show();
			// 	$(".iCMS-editor-page").val(n).trigger("chosen:updated");
			// 	return false;
			// }
		}
	});

});


function modal_picture(el,a){
  if(!a.checked) return;
  var ed = iCMS.editor.get(),
  url = $(a).attr("url");
  // if(a.checked){
  var imgObj  = {};
  imgObj.src  = url;
  imgObj._src = url;
	ed.fireEvent('beforeInsertImage', imgObj);
	ed.execCommand("insertImage", imgObj);
  _modal_dialog("Продолжить выбор");
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
  ed       = iCMS.editor.get();

  if(url=='undefined') return;
  var html = '<p class="attachment icon_'+fileType+'"><a href="'+url+'" target="_blank">' + original + '</a></p>';

  if(image=="1") html='<p><img src="'+url+'" /></p>';

	ed.execCommand("insertHTML",html);
  _modal_dialog("Продолжить загрузку");
}
function _modal_dialog(cancel_text){
  iCMS.dialog({
      content:'Успешно добавлено!',
      okValue: 'Завершить',
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
      <h5 class="brs"><?php echo empty($this->id)?'Добавить':'Редактировать' ; ?> видео</h5>
      <ul class="nav nav-tabs" id="video-add-tab">
        <li class="active"><a href="#video-add-base" data-toggle="tab"><i class="fa fa-info-circle"></i> Основная информация</a></li>
        <li><a href="#video-add-photo" data-toggle="tab"><i class="fa fa-rocket"></i> Скриншоты</a></li>
        <li><a href="#video-add-story" data-toggle="tab"><i class="fa fa-rocket"></i> Описание</a></li>
        <li><a href="#video-add-publish" data-toggle="tab"><i class="fa fa-rocket"></i> Настройки публикации</a></li>
	      <li><a href="#apps-custom" data-toggle="tab"><i class="fa fa-wrench"></i> 自定义</a></li>
        <li><a href="#apps-metadata" data-toggle="tab"><i class="fa fa-sitemap"></i> 动态属性</a></li>
      </ul>
    </div>
    <div class="widget-content nopadding iCMS-video-add">
      <form action="<?php echo APP_FURI; ?>&do=save" method="post" class="form-inline" id="iCMS-video" target="iPHP_FRAME">
        <input name="_cid" type="hidden" value="<?php echo $rs['cid'] ; ?>" />
        <input name="_scid" type="hidden" value="<?php echo $rs['scid']; ?>" />
        <input name="_tags" type="hidden" value="<?php echo $rs['tags']; ?>" />
        <input name="_pid" type="hidden" value="<?php echo $rs['pid']; ?>" />

        <input name="video_id" type="hidden" value="<?php echo $this->id ; ?>" />
        <input name="data_id" type="hidden" value="<?php echo $dRs['id']; ?>" />
        <input name="userid" type="hidden" value="<?php echo $rs['userid'] ; ?>" />
        <input name="ucid" type="hidden" value="<?php echo $rs['ucid'] ; ?>" />
        <input name="postype" type="hidden" value="<?php echo $rs['postype'] ; ?>" />
        <input name="REFERER" type="hidden" value="<?php echo iPHP_REFERER ; ?>" />

        <div id="video-add" class="tab-content">
          <div id="video-add-base" class="tab-pane active">
            <div class="input-prepend"> <span class="add-on">Категория</span>
              <select name="cid" id="cid" class="chosen-select span3" data-placeholder="== Выберите категорию для привязки ==">
                <?php echo $cata_option;?>
              </select>
            </div>
            <div class="input-prepend input-append"> <span class="add-on">Статус</span>
              <select name="status" id="status" class="chosen-select span3">
                <option value="0"> 草稿 [status='0']</option>
                <option value="1"> 正常 [status='1']</option>
                <option value="2"> 回收站 [status='2']</option>
                <option value="3"> 待审核 [status='3']</option>
                <option value="4"> 未通过 [status='4']</option>
                <?php echo propAdmincp::get("status") ; ?>
              </select>
              <?php echo propAdmincp::btn_add('Добавить статус');?>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"> <span class="add-on">副栏目</span>
              <select name="scid[]" id="scid" class="chosen-select span6" multiple="multiple"  data-placeholder="请选择副栏目(可多选)...">
                <?php echo $cata_option;?>
              </select>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend input-append"> <span class="add-on">属 性</span>
              <select name="pid[]" id="pid" class="chosen-select span6" multiple="multiple">
                <option value="0">普通视频[pid='0']</option>
                <?php echo propAdmincp::get("pid") ; ?>
              </select>
              <?php echo propAdmincp::btn_add('添加常用属性');?>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend">
              <span class="add-on">Заголовок</span>
              <input type="text" name="title" class="span6" id="title" value="<?php echo $rs['title'] ; ?>"/>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"> <span class="add-on"> Краткое название </span>
              <input type="text" name="stitle" class="span6" id="stitle" value="<?php echo $rs['stitle'] ; ?>"/>
            </div>
            <hr />
            <div class="input-prepend"> <span class="add-on">Псевдоним</span>
              <input type="text" name="alias" class="span6" id="alias" value="<?php echo $rs['alias'] ; ?>"/>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"> <span class="add-on">Транслит</span>
              <input type="text" name="enname" class="span6" id="enname" value="<?php echo $rs['enname'] ; ?>"/>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"> <span class="add-on">Рейтинг</span>
              <select name="star" id="star" class="chosen-select span3" data-placeholder="请选择星级">
                <option></option>
                <option value="1">★</option>
                <option value="2">★★</option>
                <option value="3">★★★</option>
                <option value="4">★★★★</option>
                <option value="5">★★★★★</option>
              </select>
              <script>$(function(){iCMS.select('star',"<?php echo $rs['star'];?>");})</script>
            </div>
            <div class="input-prepend">
              <span class="add-on"> Примечание </span>
              <input type="text" name="remark" class="span3" id="remark" value="<?php echo $rs['remark'] ; ?>"/>
            </div>
            <span class="help-inline">如：高清,无水印 (配合标题一起显示)</span>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend input-append"> <span class="add-on">类型</span>
              <select name="genre[]" id="genre" class="chosen-select span6" multiple="multiple"  data-placeholder="请选择影片类型(可多选)...">
                <option></option>
                <?php echo propAdmincp::get("genre") ; ?>
              </select>
              <?php echo propAdmincp::btn_add('Добавить жанр');?>
            </div>
            <script>$(function(){iCMS.select('genre',"<?php echo $rs['genre'];?>");})</script>
            <input name="_genre" type="hidden" value="<?php echo $rs['genre']; ?>" />
            <div class="clearfloat mb10"></div>
            <div class="input-prepend input-append"> <span class="add-on">主演</span>
              <input type="text" name="actor" class="span6" id="actor" value="<?php echo $rs['actor'] ; ?>" onkeyup="javascript:this.value=this.value.replace(/，/ig,',');"/>
            </div>
            <input name="_actor" type="hidden" value="<?php echo $rs['actor']; ?>" />
            <span class="help-inline">Множественное кол-во, разделяйте запятой ","</span>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"> <span class="add-on">导演</span>
              <input type="text" name="director" class="span6" id="director" value="<?php echo $rs['director'] ; ?>" onkeyup="javascript:this.value=this.value.replace(/，/ig,',');"/>
            </div>
            <input name="_director" type="hidden" value="<?php echo $rs['director']; ?>" />
            <span class="help-inline">Множественное кол-во, разделяйте запятой ","</span>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"> <span class="add-on">编剧</span>
              <input type="text" name="attrs" class="span6" id="attrs" value="<?php echo $rs['attrs'] ; ?>" onkeyup="javascript:this.value=this.value.replace(/，/ig,',');"/>
            </div>
            <input name="_attrs" type="hidden" value="<?php echo $rs['attrs']; ?>" />
            <span class="help-inline">Множественное кол-во, разделяйте запятой ","</span>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend input-append"> <span class="add-on">发行年份</span>
              <input type="text" name="year" class="span1" id="year" value="<?php echo $rs['year'] ; ?>"/>
              <select id="_year" data-toggle="select_insert" data-target="#year"  data-placeholder="请选择或填写" class="chosen-select span1">
                <option></option>
                <?php echo propAdmincp::get("year") ; ?>
              </select>
              <?php echo propAdmincp::btn_add('添加发行年份');?>
            </div>
            <script>$(function(){iCMS.select('_year',"<?php echo $rs['year'];?>");})</script>
            <div class="input-prepend input-append"> <span class="add-on">版本</span>
              <input type="text" name="version" class="span2" id="version" value="<?php echo $rs['version'] ; ?>"/>
              <select id="_version" data-toggle="select_insert" data-target="#version"  data-placeholder="请选择或填写" class="chosen-select span1">
                <option></option>
                <?php echo propAdmincp::get("version") ; ?>
              </select>
              <?php echo propAdmincp::btn_add('添加版本');?>
            </div>
            <script>$(function(){iCMS.select('_version',"<?php echo $rs['version'];?>");})</script>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend input-append"> <span class="add-on">语言</span>
              <input type="text" name="language" class="span2" id="language" value="<?php echo $rs['language'] ; ?>"/>
              <select id="_language" data-toggle="select_insert" data-target="#language"  data-placeholder="请选择或填写" class="chosen-select span1">
                <option></option>
                <?php echo propAdmincp::get("language") ; ?>
              </select>
              <?php echo propAdmincp::btn_add('添加语言');?>
            </div>
            <script>$(function(){iCMS.select('_language',"<?php echo $rs['language'];?>");})</script>
            <div class="input-prepend input-append"> <span class="add-on">制片国家/地区</span>
              <input type="text" name="area" class="span2" id="area" value="<?php echo $rs['area'] ; ?>"/>
              <select id="_area" data-toggle="select_insert" data-target="#area"  data-placeholder="请选择或填写" class="chosen-select span1">
                <option></option>
                <?php echo propAdmincp::get("area") ; ?>
              </select>
              <?php echo propAdmincp::btn_add('Добавить регион');?>
            </div>
            <script>$(function(){iCMS.select('_area',"<?php echo $rs['area'];?>");})</script>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend input-append"> <span class="add-on">更新周期</span>
              <span class="add-on"><input class="cycle" name="cycle[]" type="checkbox" value="周一">周一</span>
              <span class="add-on"><input class="cycle" name="cycle[]" type="checkbox" value="周二">周二</span>
              <span class="add-on"><input class="cycle" name="cycle[]" type="checkbox" value="周三">周三</span>
              <span class="add-on"><input class="cycle" name="cycle[]" type="checkbox" value="周四">周四</span>
              <span class="add-on"><input class="cycle" name="cycle[]" type="checkbox" value="周五">周五</span>
              <span class="add-on"><input class="cycle" name="cycle[]" type="checkbox" value="周六">周六</span>
              <span class="add-on"><input class="cycle" name="cycle[]" type="checkbox" value="周日">周日</span>
            </div>
            <script>$(function(){iCMS.checked('.cycle',<?php echo $rs['cycle']?$rs['cycle']:'""';?>);})</script>
            <div class="input-prepend input-append">
              <span class="add-on">Несколько серий?
                <input id="isSer" type="checkbox" value="1" <?php if($rs['ser']) echo 'checked="checked"'  ?>/>
              </span>
              <span class="add-on">到第</span>
              <input type="text" name="ser" class="span1" id="ser" value="<?php echo $rs['ser']; ?>"/>
              <span class="add-on">集</span>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"> <span class="add-on">Дата выхода</span>
              <input type="text" name="release" class="span3" id="release" value="<?php echo $rs['release'] ; ?>" />
            </div>
            <div class="input-prepend input-append">
              <span class="add-on"> Продолжительность </span>
              <input type="text" name="time" class="span1" id="time" value="<?php echo $rs['time'] ; ?>"/>
              <span class="add-on">Продолжительность видео в минутах</span>
            </div>
            <div class="input-prepend input-append">
              <span class="add-on">Кол-во эпизодов</span>
              <input type="text" name="total" class="span1" id="total" value="<?php echo $rs['total'] ; ?>"/>
              <span class="add-on">集</span>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"> <span class="add-on">发行公司</span>
              <input type="text" name="company" class="span3" id="company" value="<?php echo $rs['company'] ; ?>"/>
            </div>
            <div class="input-prepend"> <span class="add-on">电视台</span>
              <input type="text" name="tv" class="span3" id="tv" value="<?php echo $rs['tv'] ; ?>"/>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend input-append">
              <span class="add-on">影片评分</span>
              <span class="add-on">豆瓣</span>
              <input type="text" name="scores[douban]" class="span1" id="douban" value="<?php echo $rs['scores']['douban']; ?>"/>
              <span class="add-on">时光网</span>
              <input type="text" name="scores[mtime]" class="span1" id="mtime" value="<?php echo $rs['scores']['mtime']; ?>"/>
              <span class="add-on">IMDB</span>
              <input type="text" name="scores[imdb]" class="span1" id="imdb" value="<?php echo $rs['scores']['imdb']; ?>"/>
              <span class="add-on">站内</span>
              <input type="text" name="score" class="span1" id="site_score" value="<?php echo $rs['score']; ?>"/>
              <span class="add-on">评分次数</span>
              <input type="text" name="scorenum" class="span1" id="score_num" value="<?php echo $rs['scorenum']; ?>"/>
            </div>
            <hr />
            <div class="input-prepend input-append"> <span class="add-on">预览图</span>
              <input type="text" name="pic" class="span6" id="pic" value="<?php echo $rs['pic'] ; ?>"/>
              <?php filesAdmincp::set_opt($rs['pic'])->pic_btn("pic",$this->id);?>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend input-append"> <span class="add-on">大图</span>
              <input type="text" name="bpic" class="span6" id="bpic" value="<?php echo $rs['bpic'] ; ?>"/>
              <?php filesAdmincp::set_opt($rs['bpic'])->pic_btn("bpic",$this->id);?>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend input-append"> <span class="add-on">中图</span>
              <input type="text" name="mpic" class="span6" id="mpic" value="<?php echo $rs['mpic'] ; ?>"/>
              <?php filesAdmincp::set_opt($rs['mpic'])->pic_btn("mpic",$this->id);?>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend input-append"> <span class="add-on">小图</span>
              <input type="text" name="spic" class="span6" id="spic" value="<?php echo $rs['spic'] ; ?>"/>
              <?php filesAdmincp::set_opt($rs['spic'])->pic_btn("spic",$this->id);?>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"> <span class="add-on">关键字</span>
              <input type="text" name="keywords" class="span6" id="keywords" value="<?php echo $rs['keywords'] ; ?>" onkeyup="javascript:this.value=this.value.replace(/，/ig,',');"/>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"> <span class="add-on">标 签</span>
              <input type="text" name="tags" class="span6" id="tags" value="<?php echo $rs['tags'] ; ?>" onkeyup="javascript:this.value=this.value.replace(/，/ig,',');"/>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend" style="width:100%;"><span class="add-on">简介</span>
              <textarea name="description" id="description" class="span12" style="height: 300px;"><?php echo $rs['description'] ; ?></textarea>
            </div>
          </div>
          <div id="video-add-publish" class="tab-pane hide">
            <div class="input-prepend"> <span class="add-on">Время публикации</span>
              <input id="pubdate" class="<?php echo $readonly?'':'ui-datepicker'; ?>" value="<?php echo $rs['pubdate'] ; ?>"  name="pubdate" type="text" style="width:230px" <?php echo $readonly ; ?>/>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"> <span class="add-on">Сортировка</span>
              <input id="sortnum" class="span2" value="<?php echo $rs['sortnum']?$rs['sortnum']:time() ; ?>" name="sortnum" type="text"/>
            </div>
            <div class="input-prepend"> <span class="add-on">Приоритет</span>
              <input id="weight" class="span2" value="<?php echo $rs['weight']?$rs['weight']:time(); ?>" name="weight" type="text"/>
            </div>
            <div class="clearfix mb10"></div>
            <div class="input-prepend input-append">
              <span class="add-on">总点击数</span>
              <input type="text" name="hits" class="span1" id="hits" value="<?php echo $rs['hits']?$rs['hits']:'0'; ?>"/>
              <span class="add-on">当天点击数</span>
              <input type="text" name="hits_today" class="span1" id="hits_today" value="<?php echo $rs['hits_today']?$rs['hits_today']:'0'; ?>"/>
              <span class="add-on">昨天点击数</span>
              <input type="text" name="hits_yday" class="span1" id="hits_yday" value="<?php echo $rs['hits_yday']?$rs['hits_yday']:'0'; ?>"/>
              <span class="add-on">周点击</span>
              <input type="text" name="hits_week" class="span1" id="hits_week" value="<?php echo $rs['hits_week']?$rs['hits_week']:'0'; ?>"/>
              <span class="add-on">月点击</span>
              <input type="text" name="hits_month" class="span1" id="hits_month" value="<?php echo $rs['hits_month']?$rs['hits_month']:'0'; ?>"/>
            </div>
            <div class="clearfix mb10"></div>
            <div class="input-prepend input-append">
              <span class="add-on">收藏数</span>
              <input type="text" name="favorite" class="span1" id="favorite" value="<?php echo $rs['favorite']?$rs['favorite']:'0'; ?>"/>
              <span class="add-on">评论数</span>
              <input type="text" name="comments" class="span1" id="comments" value="<?php echo $rs['comments']?$rs['comments']:'0'; ?>"/>
              <span class="add-on">点赞数</span>
              <input type="text" name="good" class="span1" id="good" value="<?php echo $rs['good']?$rs['good']:'0'; ?>"/>
              <span class="add-on">点踩数</span>
              <input type="text" name="bad" class="span1" id="bad" value="<?php echo $rs['bad']?$rs['bad']:'0'; ?>"/>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend input-append"> <span class="add-on"> Шаблон </span>
              <input type="text" name="tpl" class="span6" id="tpl" value="<?php echo $rs['tpl'] ; ?>"/>
              <?php echo filesAdmincp::modal_btn('Шаблон','tpl');?>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"> <span class="add-on">自定链接</span>
              <input type="text" name="clink" class="span6" id="clink" value="<?php echo $rs['clink'] ; ?>"/>
              <span class="help-inline">只能由英文字母、数字或_-组成(不支持中文),留空则自动以标题拼音填充</span>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"> <span class="add-on">外部链接</span>
              <input type="text" name="url" class="span6 tip" title="注意:应用视频设置外部链接后编辑器里的内容是不会被保存的哦!" id="url" value="<?php echo $rs['url'] ; ?>"/>
            </div>
            <span class="help-inline">不填写请留空!</span>
          </div>
          <div id="video-add-photo" class="tab-pane hide">
            <script>
              $(function(){
                iCMS.editor.create('editor-photo',{
                    toolbars: [['simpleupload','insertimage']]
                });
              })
              function photo_cleanup() {
                iCMS.editor.eid='editor-photo';
                iCMS.editor.cleanup();
              }
            </script>
            <div class="clearfloat mb5"></div>
            <div class="input-prepend input-append">
              <button type="button" class="btn" onclick="javascript:photo_cleanup();"><i class="fa fa-magic"></i> 自动排版</button>
              <span class="add-on wauto">
              <input name="photo_remote" type="checkbox" id="photo_remote" value="1" <?php if(self::$config['remote']=="1")echo 'checked="checked"'  ?>/>
              Скачать удаленные изображения</span>
              <span class="add-on wauto">
              <input name="photo_autopic" type="checkbox" id="photo_autopic" value="1" <?php if(self::$config['autopic']=="1")echo 'checked="checked"'  ?>/>
              提取缩略图 </span>
              <span class="add-on wauto">
              <input name="photo_dellink" type="checkbox" id="photo_dellink" value="1"/>
              清除链接
              </span>
              <?php if(iCMS::$config['watermark']['enable']=="1"){ ?>
              <span class="add-on wauto">
                <input name="photo_iswatermark" type="checkbox" id="photo_iswatermark" value="1" />不添加水印
              </span>
              <?php }?>
            </div>
            <div class="clearfloat mb10"></div>
            <div style="width:100%;">
              <textarea type="text/plain" id="editor-photo" name="photo"><?php echo $dRs['photo'] ; ?></textarea>
            </div>
            <div class="clearfloat mb5"></div>
            <div class="alert alert-info">前端只显示图片数据</div>
          </div>
          <div id="video-add-story" class="tab-pane hide">
            <script>
              $(function(){
                iCMS.editor.create('editor-body');
              })
              function body_cleanup() {
                iCMS.editor.eid = 'editor-body';
                iCMS.editor.cleanup();
              }
            </script>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend input-append">
              <button type="button" class="btn" onclick="javascript:body_cleanup();"><i class="fa fa-magic"></i> 自动排版</button>
              <span class="add-on wauto">
              <input name="body_remote" type="checkbox" id="body_remote" value="1" <?php if(self::$config['remote']=="1")echo 'checked="checked"'  ?>/>
              Скачать удаленные изображения</span>
              <span class="add-on wauto">
              <input name="body_autopic" type="checkbox" id="body_autopic" value="1" <?php if(self::$config['autopic']=="1")echo 'checked="checked"'  ?>/>
              提取缩略图 </span>
              <span class="add-on wauto">
              <input name="body_dellink" type="checkbox" id="body_dellink" value="1"/>
              清除链接
              </span>
              <?php if(iCMS::$config['watermark']['enable']=="1"){ ?>
              <span class="add-on wauto">
                <input name="body_iswatermark" type="checkbox" id="body_iswatermark" value="1" />不添加水印
              </span>
              <?php }?>
            </div>
            <div class="clearfloat mb10"></div>
            <div style="width:100%;">
              <textarea type="text/plain" id="editor-body" name="body"><?php echo $dRs['body'] ; ?></textarea>
            </div>
          </div>
          <div id="apps-custom" class="tab-pane hide">
            <?php echo former::layout();?>
          </div>
          <div id="apps-metadata" class="tab-pane hide">
            <?php include admincp::view("apps.meta","apps");?>
          </div>
        </div>
        <div class="form-actions">
          <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i> Сохранить</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php admincp::foot();?>
