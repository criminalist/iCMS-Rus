<?php

defined('iPHP') OR exit('Oops, something went wrong');
?>
<script type="text/javascript">
$(function(){
    <?php if(members::is_superadmin()){?>
        $.getJSON('<?php echo __ADMINCP__;?>=apps_store&do=check_update&t=<?php echo time(); ?>',
            function(json){
                if(json.code=="0"){
                    return;
                }
                $("#store_update").removeClass('hide').text(json.count)
            }
        );
    <?php } ?>
});
</script>
