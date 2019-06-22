<?php
    $editor_id = 'ED_'.substr(md5(uniqid(true)),8,16);;
?>
<script type="text/javascript">
window.catchRemoteImageEnable = <?php echo iCMS::$config[$app]['catch_remote']?'true':'false';?>;
</script>
<script type="text/javascript" charset="utf-8" src="./app/admincp/ui/iCMS.ueditor.js"></script>
<script type="text/javascript" charset="utf-8" src="./app/admincp/ui/ueditor/ueditor.all.min.js"></script>
<script>
var <?php echo $editor_id;?> = iCMS.editor;
(function($) {
    $(function(){
        <?php echo $editor_id;?>.create("<?php echo $id;?>");
    })
})(jQuery);
</script>
<div class="clearfloat"></div>
<div class="input-prepend">
    <div class="btn-group">
        <span class="add-on wauto">
        <input name="remote" type="checkbox" id="remote" value="1" <?php if(iCMS::$config[$app]['remote']=="1")echo 'checked="checked"'  ?>/>
        Загрузить удаленные изображения</span>
        <span class="add-on wauto">
        <input name="dellink" type="checkbox" id="dellink" value="1"/>
        Очистить ссылки
        </span>
        <?php if(iCMS::$config['watermark']['enable']=="1"){ ?>
        <span class="add-on wauto">
        <input name="iswatermark" type="checkbox" id="iswatermark" value="1" />Не добавлять водяные знаки
        </span>
        <?php }?>
        <button type="button" class="btn" onclick="javascript:<?php echo $editor_id;?>.insPageBreak();"><i class="fa fa-ellipsis-h"></i> Вставить разрыв страницы</button>
        <button type="button" class="btn" onclick="javascript:<?php echo $editor_id;?>.delPageBreakflag();"><i class="fa fa-ban"></i> Удалить разрыв страницы</button>
        <button type="button" class="btn" onclick="javascript:<?php echo $editor_id;?>.cleanup();"><i class="fa fa-magic"></i> 自动排版</button>
    </div>
</div>
<div class="clearfloat mt10"></div>
