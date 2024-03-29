<script>
$("#cid").on('change', function() {
  get_category_meta(this.value,"#apps-metadata");
});
</script>
<button class="btn btn-inverse add_meta" type="button"><i class="fa fa-plus-circle"></i> Добавить динамические свойства</button>
<table class="table table-hover">
  <thead>
    <tr>
      <th>Имя</th>
      <th>Поле<span class="label label-important">Может состоять только из английских букв, цифр или символов _-</span></th>
      <th> Значение </th>
    </tr>
  </thead>
  <tbody>
    <?php
      $md_index=0;
      if($meta_setting)foreach((array)$meta_setting AS $mskey=>$mts){
        $md      = apps_meta::$data[$mts['key']];
        $msvalue = $md['value'];
        $msname  = $mts['name'];
        if($mts['name']!=$md['name'] &&$md['name']){
          $msname  = $md['name'];
        }
        unset(apps_meta::$data[$mts['key']]);
    ?>
    <tr id="cid_meta_<?php echo $cid;?>_<?php echo $mts['key'];?>">
      <td><input name="metadata[<?php echo $md_index;?>][name]" type="text" value="<?php echo $msname;?>" class="span3" /></td>
      <td><input name="metadata[<?php echo $md_index;?>][key]" type="text" value="<?php echo $mts['key'];?>" class="span3" readonly="true" /></td>
      <td><input name="metadata[<?php echo $md_index;?>][value]" type="text" value="<?php echo $msvalue;?>" class="span6" />
        <button class="btn btn-small btn-danger del_meta" type="button"><i class="fa fa-trash-o"></i> Удалить</button>
      </td>
    </tr>
    <?php ++$md_index;}?>
    <?php if(apps_meta::$data)foreach((array)apps_meta::$data AS $mdkey=>$mdata){?>
    <tr>
      <td><input name="metadata[<?php echo $md_index;?>][name]" type="text" value="<?php echo $mdata['name'];?>" class="span3" /></td>
      <td><input name="metadata[<?php echo $md_index;?>][key]" type="text" value="<?php echo $mdata['key'];?>" class="span3" /></td>
      <td><input name="metadata[<?php echo $md_index;?>][value]" type="text" value="<?php echo $mdata['value'];?>" class="span6" />
        <button class="btn btn-small btn-danger del_meta" type="button"><i class="fa fa-trash-o"></i> Удалить</button>
      </td>
    </tr>
    <?php ++$md_index;}?>
  </tbody>
  <tfoot>
    <tr class="hide meta_clone">
      <td><input name="metadata[{key}][name]" type="text" disabled="disabled" class="span3" /></td>
      <td><input name="metadata[{key}][key]" type="text" disabled="disabled" class="span3" /></td>
      <td><input name="metadata[{key}][value]" type="text" disabled="disabled" class="span6"  />
        <button class="btn btn-small btn-danger del_meta" type="button"><i class="fa fa-trash-o"></i> Удалить</button>
      </td>
    </tr>
  </tfoot>
</table>
