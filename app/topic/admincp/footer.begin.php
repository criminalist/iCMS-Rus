<?php

defined('iPHP') OR exit('What are you doing?');
$_app      = $_GET['_app'];
$select_id = $_GET['select_id'];
?>

<div id="select_result">

</div>
<script>
$(function(){
  $('input[type="hidden"][name="app"]').after(
    '<input type="hidden" name="_app" value="<?php echo $_app;?>" />'+
    '<input type="hidden" name="select_id" value="<?php echo $select_id;?>" />'
  )
  var box = $("#topic-box");
  box.on("click", '[name="id[]"]', function() {
    checkbox(this);
  });
  box.on("click", '.checkAll', function() {
      $('[name="id[]"]', box).each(function() {
        checkbox(this,!this.checked);
      });
  });

  function checkbox(a,checked){
    if(typeof(checked)==="undefined"){
      checked = $(a).prop("checked");
    }

    var id = $(a).val();
    if(checked){
      var tr = $("#id"+id);
      var title = $(".aTitle",tr).text();
      title = title.replace(/^\s+/g,'');
      title = title.replace(/\s+$/g,'');
    }else{
      title = '__remove__';
    }
    window.top.select_result(id,title,function(){
      $(a).prop("checked", false);
      $.uniform.update($(a));
    },"<?php echo $select_id;?>");
  }

  $("a[href^='<?php echo APP_URI;?>']").each(function() {
    var href = $(this).attr('href'),target = $(this).attr('target');
    var _app_url = '&do=select&_app=<?php echo $_app;?>'
    if(href.indexOf(_app_url)===-1){
      if(href.indexOf('&do=select')===-1){
        href = href.replace("<?php echo APP_URI;?>","<?php echo __ADMINCP__; ?>=<?php echo $_app;?>");
      }else{
        href = href.replace("<?php echo APP_URI;?>","<?php echo APP_URI;?>&_app=<?php echo $_app;?>&select_id=<?php echo $select_id;?>");
      }
      $(this).attr('href',href);
    }
    if(target!='iPHP_FRAME' && target!='_blank' && href.indexOf('&do=select&_app=article')===-1){
      $(this).attr('target','_blank');
    }
  });
});
</script>
