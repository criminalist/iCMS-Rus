<?php

defined('iPHP') OR exit('Oops, something went wrong');
?>
<!--<div class="input-prepend"> <span class="add-on">Иконка</span>
<div class="switch">
<input type="checkbox" data-type="switch" name="config[thumb][enable]" id="thumb_enable" <?php echo $config['thumb']['enable']?'checked':''; ?>/>
</div>
</div>
<div class="clearfloat mb10"></div> -->
<div class="input-prepend"> <span class="add-on">Размеры миниатюры</span>
<textarea name="config[thumb][size]" id="thumb_size" class="span6" style="height: 90px;"><?php echo $config['thumb']['size'] ; ?></textarea>
</div>
<div class="clearfloat mb10"></div>
<span class="help-inline"><a class="btn btn-small btn-success" href="https://www.icmsdev.com/docs/thumb.html" target="_blank"><i class="fa fa-question-circle"></i> Информация!</a>
Введите необходимые размеры для автоматического создания миниатюр, в формате: 300x300. по одному размеру на строку.</span>