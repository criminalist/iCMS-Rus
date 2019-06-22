<?php

defined('iPHP') OR exit('Oops, something went wrong');
?>
<script type="text/javascript">
$(function(){
    <?php if(iCMS::$config['system']['patch'] && members::is_superadmin()){?>
        window.setTimeout(function(){
            $.getJSON('<?php echo __ADMINCP__;?>=patch&do=check&ajax=1&jt=<?php echo time(); ?>',
                function(json){
                    if(json.code=="0"){
                        return;
                    }
                    iCMS.dialog({
                        content: json.msg,
                        okValue: 'Обновить сейчас',
                        ok: function () { window.location.href=json.url;},
                        cancelValue: 'Не сейчас',
                        cancel: function () {return true;}
                    });
                }
            );
        },1000);
    <?php } ?>
});
</script>
