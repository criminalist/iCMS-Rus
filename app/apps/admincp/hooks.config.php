<?php

defined('iPHP') OR exit('Oops, something went wrong');
admincp::head();
?>
<div class="hide hooks_item">
  <div class="input-prepend input-append">
    <span class="add-on">Приложение</span>
    <select name="hooks[app][]" class="H_app span3">
      <option value="">Выберите приложение</option>
      <?php echo apps_hook::app_select();?>
    </select>
    <span class="add-on">Поле</span>
    <select name="hooks[field][]" class="H_field span3">
    </select>
    <span class="add-on"> Метод </span>
    <select name="hooks[method][]" class="H_method span3">
    <?php
      $hook_method = apps_hook::app_method();
      echo $hook_method?$hook_method:'<option value="">Нет доступных способов</option>';
    ?>
    </select>
    <span class="add-on"><a class="del_hooks" href="javascript:;"><i class="fa fa-times"></i></a></span>
  </div>
  <div class="clearfloat mb10"></div>
</div>
<div class="app_fields_select hide">
  <?php echo apps_hook::app_fields_select();?>
</div>

<script type="text/javascript">
$(function(){
  $("#<?php echo APP_FORMID;?>").batch();
});
</script>

<script>
function hooks_item_clone(a,f,m) {
  var set_field = function  (hooks_item,f,html) {
      $(".H_field",hooks_item).html('');
      $(".H_field",hooks_item).html(html);
      if(f){
        $(".H_field",hooks_item).val(f)
      }
      $(".H_field",hooks_item).trigger("chosen:updated");
  }
  var hooks_item = $(".hooks_item").clone(true);
  hooks_item.removeClass('hide hooks_item');
  hooks_item.on('chosen:updated change', '.H_app', function(event) {
    event.preventDefault();
    var me = this,app = this.value;
    $(".H_field",hooks_item).html('<option value="">Идет загрузка ... пожалуйста, подождите!</option>');
    var option = $("#app_"+app+"_select").html();

    set_field(hooks_item,f,option);
    // $.get("<?php echo APP_URI; ?>&do=hooks_app_field_opt&_app="+app,
    //   function(html){
    //     set_field(hooks_item,f,html,app);
    //   }
    // );
  }).on('click', '.del_hooks', function(event) {
    event.preventDefault();
    var ppp = $(this).parent().parent().parent();
    ppp.remove();
  });
  if(a){
    $(".H_app",hooks_item).val(a).trigger("chosen:updated");
  }
  if(m){
    $(".H_method",hooks_item).val(m).trigger("chosen:updated");
  }
  $(".hooks_container").append(hooks_item)
  $("select",hooks_item).chosen(chosen_config);
}
</script>

<div class="iCMS-container">
  <div class="widget-box" id="<?php echo APP_BOXID;?>">
    <div class="widget-title">
      <span class="icon">
        <i class="fa fa-bank"></i> <span>Хуки приложений</span>
      </span>
      <span class="icon">
        <a class="add_hooks" href="javascript:;" title="Добавить хук"><i class="fa fa-plus-square"></i> Добавить хук</a>
      </span>
    </div>
    <div class="widget-content">
      <form action="<?php echo APP_FURI; ?>&do=hooks_save" method="post" class="form-inline" id="<?php echo APP_FORMID;?>" target="iPHP_FRAME">
            <div class="hooks_container"></div>
<script>
<?php
  foreach ((array)$config as $H_app => $hooks) {
    foreach ((array)$hooks as $H_field => $H_method) {
      foreach ($H_method as $key => $_method) {
        echo 'hooks_item_clone("'.$H_app.'","'.$H_field.'","'.implode('::', $_method).'");';
      }
    }
  }
?>
$(function(){
  $(".add_hooks").click(function(event) {
      event.preventDefault();
      hooks_item_clone();
  });
})
</script>
<div class="clearfloat mb10"></div>
        <div class="form-actions">
          <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i>Отправить</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php admincp::foot();?>
