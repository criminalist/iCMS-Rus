<?php

defined('iPHP') OR exit('Oops, something went wrong');
configAdmincp::head("Настройка хотлинков");
?>
<div class="input-prepend">
    <span class="add-on">关键字替换</span>
    <input type="text" name="config[limit]" class="span3" id="keyword_limit" value="<?php echo $config['limit'] ; ?>"/>
</div>
<span class="help-inline">内链关键字替换次数 0为不替换,-1全部替换</span>
<?php configAdmincp::foot();?>
