<?php
defined('iPHP') OR exit('Oops, something went wrong');
?>
<div class="input-prepend">
    <span class="add-on">Водяной знак</span>
    <div class="switch">
        <input type="checkbox" data-type="switch" name="config[watermark][enable]" id="watermark_enable" <?php echo $config[ 'watermark'][ 'enable']? 'checked': ''; ?>/>
    </div>
</div>
<span class="help-inline">Изображение или текстовый водяной знак, который вы установили ниже, будут добавлены во вложенное изображение.</span>
<div class="clearfloat mb10"></div>
<hr />
<div class="input-prepend">
    <span class="add-on">Режим водяного знака</span>
    <div class="switch" data-on-label="Мозайка" data-off-label="Водяной знак">
        <input type="checkbox" data-type="switch" name="config[watermark][mode]" id="watermark_mode" <?php echo $config[ 'watermark'][ 'mode']? 'checked': ''; ?>/>
    </div>
</div>
<div class="clearfloat mb10"></div>
<div class="input-prepend">
    <span class="add-on">Местоположение водяного знака</span>
    <select name="config[watermark][pos]" id="watermark_pos" class="span3 chosen-select">
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
        <option value="-1">Пользовательские регулярные выражения</option>
    </select>
</div>
<script>
$(function() { iCMS.select('watermark_pos', "<?php echo (int)$config['watermark']['pos'] ; ?>"); });
</script>
<div class="clearfloat mb10"></div>
<div class="input-prepend input-append">
    <span class="add-on">Смещение</span><span class="add-on">X</span>
    <input type="text" name="config[watermark][x]" class="span1" id="watermark_x" value="<?php echo $config['watermark']['x'] ; ?>" />
    <span class="add-on">Y</span>
    <input type="text" name="config[watermark][y]" class="span1" id="watermark_y" value="<?php echo $config['watermark']['y'] ; ?>" />
</div>
<div class="clearfloat mb10"></div>
<div class="input-prepend input-append">
    <span class="add-on">Размер изображения</span><span class="add-on">Ширина</span>
    <input type="text" name="config[watermark][width]" class="span1" id="watermark_width" value="<?php echo $config['watermark']['width'] ; ?>" />
    <span class="add-on"> Высота </span>
    <input type="text" name="config[watermark][height]" class="span1" id="watermark_height" value="<?php echo $config['watermark']['height'] ; ?>" />
</div>
<span class="help-inline">Единица измерения:пиксель(px) Изображения водяных знаков или текст добавляются только к изображениям вложений, размер которых превышает размер, установленный программой (установлен на 0 без ограничений)</span>
<div class="clearfloat mb10"></div>
<div class="input-prepend">
    <span class="add-on">Тип изображения</span>
    <input type="text" name="config[watermark][allow_ext]" class="span3" id="watermark_allow_ext" value="<?php echo $config['watermark']['allow_ext'] ; ?>" />
</div>
<span class="help-inline">Формат изображения для водяного знака (jpg,jpeg,png)</span>
<div class="clearfloat mb10"></div>
<hr />
<div class="input-prepend">
    <span class="add-on">Изображения водяного знака</span>
    <input type="text" name="config[watermark][img]" class="span3" id="watermark_img" value="<?php echo $config['watermark']['img'] ; ?>" />
</div>
<span class="help-inline">По умолчанию путь хранения изображений водяного знака:/cache/conf/iCMS/watermark.png</span>
<div class="clearfloat mb10"></div>
<div class="input-prepend">
    <span class="add-on">Прозрачность водяного знака</span>
    <input type="text" name="config[watermark][transparent]" class="span3" id="watermark_transparent" value="<?php echo $config['watermark']['transparent'] ; ?>" />
</div>
<hr />
<div class="input-prepend">
    <span class="add-on">水印文字</span>
    <input type="text" name="config[watermark][text]" class="span3" id="watermark_text" value="<?php echo $config['watermark']['text'] ; ?>" />
</div>
<span class="help-inline">如果设置为中文,字体文件必需要支持中文字体 ,存放路径:/cache/conf/iCMS/</span>
<div class="clearfloat mb10"></div>
<div class="input-prepend">
    <span class="add-on">文字字体</span>
    <input type="text" name="config[watermark][font]" class="span3" id="watermark_font" value="<?php echo $config['watermark']['font'] ; ?>" />
</div>
<span class="help-inline">字体文件</span>
<div class="clearfloat mb10"></div>
<div class="input-prepend">
    <span class="add-on">水印文字字体大小</span>
    <input type="text" name="config[watermark][fontsize]" class="span3" id="watermark_fontsize" value="<?php echo $config['watermark']['fontsize'] ; ?>" />
</div>
<div class="clearfloat mb10"></div>
<div class="input-prepend">
    <span class="add-on">水印文字颜色</span>
    <input type="text" name="config[watermark][color]" class="span3" id="watermark_color" value="<?php echo $config['watermark']['color'] ; ?>" />
</div>
<span class="help-inline">例#000000 长度必须7位</span>
<hr />
<div class="clearfloat mb10"></div>
<div class="input-prepend input-append">
    <span class="add-on">Размер мозаики</span>
    <span class="add-on" style="width:30px;"> Ширина </span>
    <input type="text" name="config[watermark][mosaics][width]" class="span1" id="watermark_mosaics_width" value="<?php echo $config['watermark']['mosaics']['width']?:150 ; ?>" />
    <span class="add-on" style="width:30px;"> Высота </span>
    <input type="text" name="config[watermark][mosaics][height]" class="span1" id="watermark_mosaics_height" value="<?php echo $config['watermark']['mosaics']['height']?:90 ; ?>" />
</div>
<div class="clearfloat mb10"></div>
<div class="input-prepend">
    <span class="add-on">马赛克深度</span>
    <input type="text" name="config[watermark][mosaics][deep]" class="span3" id="watermark_mosaics_deep" value="<?php echo $config['watermark']['mosaics']['deep']?:9 ; ?>" />
</div>
<!--
<div class="clearfloat mb10"></div>
<div class="input-prepend">
    <span class="add-on">С эскизами水印</span>
    <div class="switch">
        <input type="checkbox" data-type="switch" name="config[watermark][thumb]" id="watermark_thumb" <?php echo $config['watermark']['thumb']?'checked':''; ?>/>
    </div>
</div>
<span class="help-inline">开启时缩略图也会打上水印</span>
-->
