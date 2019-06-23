<?php

defined('iPHP') OR exit('Oops, something went wrong');
configAdmincp::head("Настройка хотлинков");
?>
<div class="input-prepend">
    <span class="add-on">Замена ключевого слова</span>
    <input type="text" name="config[limit]" class="span3" id="keyword_limit" value="<?php echo $config['limit'] ; ?>"/>
</div>
<span class="help-inline">0 не заменяется, -1 заменяется</span>
<?php configAdmincp::foot();?>
