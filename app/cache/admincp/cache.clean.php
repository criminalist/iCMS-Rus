<?php

defined('iPHP') OR exit('Oops, something went wrong');
?>
<script type="text/javascript">
$(function(){
    $.get('<?php echo __ADMINCP__;?>=cache&do=cleanall');
});
</script>
