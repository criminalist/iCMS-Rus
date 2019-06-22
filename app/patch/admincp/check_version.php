<?php

defined('iPHP') OR exit('Oops, something went wrong');
?>
<script type="text/javascript">
$(function(){
    window.setTimeout(function(){
        $.getJSON('<?php echo __ADMINCP__;?>=patch&do=version&t=<?php echo time(); ?>',
            function(o){
	            $('#iCMS_RELEASE').text(o.release);
	            $('#iCMS_GIT').text(o.git);
	            $('#iCMS_GIT_UPDATE').removeClass('hide').text(o.gitcount);
            }
        );
    },1000);
});
</script>
