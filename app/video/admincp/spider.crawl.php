<?php

defined('iPHP') OR exit('What are you doing?');
admincp::head();
?>
<script type="text/javascript">
$(function(){
    $("#<?php echo APP_FORMID;?>").batch();
    <?php if($nexturl){?>
    window.setTimeout(function(){
        window.location.href="<?php echo $nexturl;?>";
    },1000);
    <?php } ?>
});
</script>
<div class="iCMS-container">
  <div class="widget-box" id="<?php echo APP_BOXID;?>">
    <div class="widget-title">
      <span class="icon">
        <input type="checkbox" class="checkAll" data-target="#<?php echo APP_BOXID;?>" />
      </span>
      <h5><?php echo $resource['name'];?>资源采集(<?php echo $page;?>/<?php echo $maxpage;?>),请等待...</h5>
    </div>
    <div class="widget-content">
    <?php
    foreach ((array)$rs as $key => $value) {
        $value = array_filter($value);
        if(empty($value)){
          continue;
        }
        $result = self::insert_video($value,$category,$rid);
    ?>
    <p>采集
      <span class="label label-inverse"><?php echo $value['name'];?></span>
      <span class="label label-info"><?php echo $value['note'];?></span>.....
      <span class="label label-<?php echo $result['class'];?>"><?php echo $result['text'];?></span>
    </p>
    <hr class="mt5 mb5"/>
    <?php }  ?>
    </div>
  </div>
</div>
<div class='iCMS-batch'>
</div>
<?php admincp::foot();?>
