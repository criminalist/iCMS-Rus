<?php

defined('iPHP') OR exit('Oops, something went wrong');
configAdmincp::head("Настройка контента");
?>
<div class="input-prepend">
  <span class="add-on">Автоматические ALT к изображениям</span>
  <div class="switch" data-on-label="Вкл" data-off-label="Закрыть">
    <input type="checkbox" data-type="switch" name="config[img_title]" id="article_img_title" <?php echo $config['img_title']?'checked':''; ?>/>
  </div>
</div>
<span class="help-inline">启用后 可以自定义文章正文内图片的title和alt,关闭后 系统将直接替换成文章标题</span>
<div class="clearfloat mb10"></div>
<div class="input-prepend">
  <span class="add-on">Изображение по центру</span>
  <div class="switch" data-on-label="Вкл" data-off-label="Закрыть">
    <input type="checkbox" data-type="switch" name="config[pic_center]" id="article_pic_center" <?php echo $config['pic_center']?'checked':''; ?>/>
  </div>
</div>
<span class="help-inline">После включения изображения в статье будут автоматически центрированы.</span>
<div class="clearfloat mb10"></div>
<div class="input-prepend">
  <span class="add-on">Ссылка на изображение</span>
  <div class="switch" data-on-label="Вкл" data-off-label="Закрыть">
    <input type="checkbox" data-type="switch" name="config[pic_next]" id="article_pic_next" <?php echo $config['pic_next']?'checked':''; ?>/>
  </div>
</div>
<span class="help-inline">启用后 文章内的图片都会带上下一页的链接和点击图片进入下一页的链接</span>
<div class="clearfloat mb10"></div>
<div class="input-prepend">
  <span class="add-on">文章分页+N</span>
  <input type="text" name="config[pageno_incr]" class="span3" id="article_pageno_incr" value="<?php echo $config['pageno_incr'] ; ?>"/>
</div>
<span class="help-inline">设置此项后,内容分页数比实际页数+N页,不增加请设置为0</span>
<div class="clearfloat mb10"></div>
<div class="input-prepend">
  <span class="add-on">Визуальный редактор</span>
  <div class="switch" data-on-label="Editor.md" data-off-label="UEditor">
    <input type="checkbox" data-type="switch" name="config[markdown]" id="article_markdown" <?php echo $config['markdown']?'checked':''; ?>/>
  </div>
</div>
<span class="help-inline">Editor.md - упрощенный редактор, UEditor более функциональный и используется по умолчанию.</span>
<div class="clearfloat mb10"></div>
<div class="input-prepend">
  <span class="add-on">Автоматическое форматирование текста</span>
  <div class="switch">
    <input type="checkbox" data-type="switch" name="config[autoformat]" id="article_autoformat" <?php echo $config['autoformat']?'checked':''; ?>/>
  </div>
</div>
<span class="help-inline">Система автоматически удалить лишний HTML код после публикации контента, рекомендуется включать при парсинге с других сайтов, если вы заметили проблемы после сохранения документа, с текстом или версткой, отключите эту функцию.</span>
<div class="clearfloat mb10"></div>
<div class="input-prepend">
  <span class="add-on">编辑器图片</span>
  <div class="switch">
    <input type="checkbox" data-type="switch" name="config[catch_remote]" id="article_catch_remote" <?php echo $config['catch_remote']?'checked':''; ?>/>
  </div>
</div>
<span class="help-inline">开启后发表文章时只要有图片 就会自动下载</span>
<div class="clearfloat mb10"></div>
<div class="input-prepend">
  <span class="add-on">Загрузить удаленные изображения</span>
  <div class="switch">
    <input type="checkbox" data-type="switch" name="config[remote]" id="article_remote" <?php echo $config['remote']?'checked':''; ?>/>
  </div>
</div>
<span class="help-inline">开启后发表文章时该选项默认为选中状态</span>
<div class="clearfloat mb10"></div>
<div class="input-prepend">
  <span class="add-on">Сделать первую картинку эскизом</span>
  <div class="switch">
    <input type="checkbox" data-type="switch" name="config[autopic]" id="article_autopic" <?php echo $config['autopic']?'checked':''; ?>/>
  </div>
</div>
<span class="help-inline">开启后发表文章时该选项默认为选中状态</span>
<div class="clearfloat mb10"></div>
<div class="input-prepend">
  <span class="add-on">提取摘要</span>
  <div class="switch">
    <input type="checkbox" data-type="switch" name="config[autodesc]" id="article_autodesc" <?php echo $config['autodesc']?'checked':''; ?>/>
  </div>
</div>
<span class="help-inline">开启后发表文章时程序会自动提取文章部分内容为文章摘要</span>
<div class="clearfloat mb10"></div>
<div class="input-prepend">
  <span class="add-on">提取摘要字数</span>
  <input type="text" name="config[descLen]" class="span3" id="article_descLen" value="<?php echo $config['descLen'] ; ?>"/>
</div>
<span class="help-inline">设置自动提取内容摘要字数</span>
<div class="clearfloat mb10"></div>
<div class="input-prepend">
  <span class="add-on">Разбивка содержания на несколько страниц</span>
  <div class="switch">
    <input type="checkbox" data-type="switch" name="config[autoPage]" id="article_autoPage" <?php echo $config['autoPage']?'checked':''; ?>/>
  </div>
</div>
<span class="help-inline">开启后发表文章时程序会分页</span>
<div class="clearfloat mb10"></div>
<div class="input-prepend">
  <span class="add-on">内容分页字数</span>
  <input type="text" name="config[AutoPageLen]" class="span3" id="article_AutoPageLen" value="<?php echo $config['AutoPageLen'] ; ?>"/>
</div>
<span class="help-inline">设置自动内容分页字数</span>
<div class="clearfloat mb10"></div>
<div class="input-prepend">
  <span class="add-on">检查标题重复</span>
  <div class="switch">
    <input type="checkbox" data-type="switch" name="config[repeatitle]" id="article_repeatitle" <?php echo $config['repeatitle']?'checked':''; ?>/>
  </div>
</div>
<span class="help-inline">开启后不能发表相同标题的文章</span>
<div class="clearfloat mb10"></div>
<div class="input-prepend">
  <span class="add-on">列表显示图片</span>
  <div class="switch">
    <input type="checkbox" data-type="switch" name="config[showpic]" id="article_showpic" <?php echo $config['showpic']?'checked':''; ?>/>
  </div>
</div>
<span class="help-inline">开启后文章列表将会显示缩略图</span>
<div class="clearfloat mb10"></div>
<div class="input-prepend">
  <span class="add-on">后台文章过滤</span>
  <select name="config[filter][]" id="article_filter" class="chosen-select span6" multiple="multiple">
    <option value="title:заголовок">Заголовок</option>
    <option value="description:описание">Описание</option>
    <option value="body:Содержание">Содержание</option>
    <option value="tags:теги">Теги</option>
    <option value="stitle:Краткое название">Краткое название</option>
    <option value="keywords:Ключевое слово">Ключевое слово</option>
  </select>
</div>
<script>
$(function(){
  iCMS.select('article_filter',"<?php echo implode(',', (array)$config['filter']);?>");
})
</script>
<span class="help-inline">开启台 后台输入的文章相关字段都将经过关键字过滤</span>
<div class="clearfloat mb10"></div>
<div class="input-prepend">
  <span class="add-on">列表优化</span>
  <input type="text" name="config[total_num]" class="span3" id="article_total_num" value="<?php echo $config['total_num'] ; ?>"/>
</div>
<span class="help-inline">固定总条数,将大大提高后台性能,但无法正确统计条数</span>
<div class="clearfloat mb10"></div>
<div class="input-prepend">
  <span class="add-on">字符分隔符</span>
  <input type="text" name="config[clink]" class="span3" id="article_clink" value="<?php echo $config['clink'] ; ?>"/>
</div>
<span class="help-inline">文章自定义链接字符分隔符</span>
<div class="clearfloat mb10"></div>
<div class="input-prepend">
  <span class="add-on">Преобразование смайликов</span>
  <select name="config[emoji]" id="article_emoji" class="chosen-select span3">
    <option value="">Не преобразовывать</option>
    <option value="unicode">unicode</option>
    <option value="clean"> Очистить </option>
  </select>
</div>
<script>
$(function(){
  iCMS.select('article_emoji',"<?php echo $config['article_emoji'];?>");
})
</script>
<span class="help-inline">文章内容出现emoji表情,会出现内容被截断的数据,可选相关处理方法</span>
<?php configAdmincp::foot();?>
