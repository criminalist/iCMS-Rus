<?php 
defined('iPHP') OR exit('Oops, something went wrong'); 
configAdmincp::head("Конфигурация системы комменатриев");
?>
<div class="input-prepend">
    <span class="add-on">Комментарии (вкл/выкл)</span>
    <div class="switch">
        <input type="checkbox" data-type="switch" name="config[enable]" id="comment_enable" <?php echo $config['enable']?'checked':''; ?>/>
    </div>
</div>
<div class="clearfloat mb10"></div>
<div class="input-prepend">
    <span class="add-on">Модерация новых комментариев</span>
    <div class="switch">
        <input type="checkbox" data-type="switch" name="config[examine]" id="comment_examine" <?php echo $config['examine']?'checked':''; ?>/>
    </div>
</div>
<div class="clearfloat mb10"></div>
<div class="input-prepend">
    <span class="add-on">Защитный код</span>
    <div class="switch">
        <input type="checkbox" data-type="switch" name="config[seccode]" id="comment_seccode" <?php echo $config['seccode']?'checked':''; ?>/>
    </div>
</div>
<span class="help-inline">После включение этой опции, пользователям будет необходимо вводить защитный код при комментировании материалов</span>

<?php configAdmincp::foot();?>
