<?php defined('iPHP') OR exit('Oops, something went wrong'); ?>
<?php
if($this->category_template)foreach ($this->category_template as $key => $value) {
    $template_id = 'template_'.$key;
?>
<div class="input-prepend input-append"> <span class="add-on">Шаблон <?php echo $value[0];?> </span>
  <input type="text" name="template[<?php echo $key;?>]" class="span6" id="<?php echo $template_id;?>" value="<?php echo isset($rs['template'][$key])?$rs['template'][$key]:$value[1]; ?>"/>
  <?php echo filesAdmincp::modal_btn('Шаблон',$template_id);?>
</div>
<div class="clearfloat mb10"></div>
<?php }?>
<div class="help-inline">
<span class="label label-info">{iTPL}</span> Переменная шаблона подключенного в настройках системы<br />
<span class="label label-info">{DEVICE}</span> Устройство, настроенное для системы, автоматически сопоставляется, два устройства по умолчанию: desktop, mobile, пожалуйста, сделайте два набора шаблонов отдельно
<hr />
<span class="label label-info">Шаблон главной страницы категории</span> (может использоваться для обложки канала, отдельной страницы и т. Д.)<br />
<span class="label label-info">Шаблон списка документов</span> используется если в разделе более одной страницы для вывода документов.
</div>
