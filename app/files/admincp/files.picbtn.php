<?php

defined('iPHP') OR exit('Oops, something went wrong');
$unid = uniqid();
?>
<div class="btn-group">
  <a class="btn dropdown-toggle" data-toggle="dropdown" tabindex="-1"> <span class="caret"></span> Выбрать изображение</a>
  <ul class="dropdown-menu">
    <?php if(members::check_priv('files.add')){?>
    <li><a href="<?php echo __ADMINCP__;?>=files&do=add&from=modal&callback=<?php echo $callback;?>" data-toggle="modal" data-meta='{"width":"300px","height":"80px"}' title="С компьютера"><i class="fa fa-upload"></i> С компьютера</a></li>
    <?php if($multi){?>
    <li><a href="<?php echo __ADMINCP__;?>=files&do=multi&from=modal&callback=<?php echo $callback;?>" data-toggle="modal" data-meta='{"width":"85%","height":"640px"}' title="Мультизагрузка изображений"><i class="fa fa-upload"></i>Мультизагрузка изображений</a></li>
    <?php }?>
    <?php }?>
    <?php if(members::check_priv('files.browse')){?>
    <li><a href="<?php echo __ADMINCP__;?>=files&do=browse&from=modal&click=file&callback=<?php echo $callback;?>" data-toggle="modal" title="从网站选择"><i class="fa fa-search"></i> Выбрать на сервере</a></li>
    <li class="divider"></li>
    <?php }?>
    <?php if(members::check_priv('files.editpic')){?>
    <li><a href="<?php echo __ADMINCP__;?>=files&do=editpic&from=modal&callback=<?php echo $callback;?>" data-toggle="modal" title="使用美图秀秀编辑图片" class="modal_photo_<?php echo $callback.'_'.$unid;?> tip"><i class="fa fa-edit"></i> Редактировать </a></li>
    <li class="divider"></li>
        <?php if($indexid){?>
        <li><a href="<?php echo __ADMINCP__;?>=files&do=editpic&from=modal&indexid=<?php echo $indexid;?>&callback=<?php echo $callback;?>" data-toggle="modal" title="使用加载本篇内容所有图片编辑" class="modal_mphoto_<?php echo $unid;?> tip"><i class="fa fa-edit"></i> Редактирование изображений</a></li>
        <li class="divider"></li>
        <?php }?>
    <?php }?>
    <li><a href="<?php echo __ADMINCP__;?>=files&do=preview&from=modal&callback=<?php echo $callback;?>" data-toggle="modal" data-check="1" title="Предварительный просмотр" class="modal_photo_<?php echo $callback.'_'.$unid;?>"><i class="fa fa-eye"></i> Предварительный просмотр </a></li>
  </ul>
  <?php if(self::$no_http){ ?>
      <span class="add-on tip brr" title="Не загружать удаленные файлы на сервер">
        <input type="checkbox" name="<?php echo $callback;?>_http"/>
        <i class="fa fa-cog"></i>
      </span>
  <?php } ?>
</div>

<?php

if($multi){
    $s = '<ul class="row multiupload-preview">';
    if(preg_match('/^a:\d+:\{/', self::$pic_value)){
        $picArr = unserialize(self::$pic_value);
    }else{
        $picArr = json_decode(self::$pic_value,true);
    }
    if(self::$pic_value && empty($picArr)){
        $picArr = explode("\n", self::$pic_value);
    }
    if (is_array($picArr))foreach ($picArr as $row) {
        $url = iFS::fp($row,'+http');
        $s .= '<li class="span2 multiupload-item">';
        $s .= '<a href="'.$url.'" target="_blank" class="thumbnail">';
        $s .= '<img src="'.$url.'"></a>';
        $s .= '<input type="hidden" name="'.$callback.'[]" value="'.$row.'">';
        $s .= '<em class="delete" title="Удалить это изображение" onclick="deleteMultiImage(this)">×</em></li>';
    }
    $s .= '</ul>';
    echo $s;
}
?>

<script type="text/javascript">
$(function(){
<?php if(self::$no_http && iFS::checkHttp(self::$pic_value)){ ?>
    $('[name="<?php echo $callback;?>_http"]').prop('checked','checked');
<?php } ?>
    window.modal_<?php echo $callback;?> = function(el,a,c){
        // console.log(el,a,c,'11111111111111');
        var e = $("#<?php echo $callback;?>");
<?php if($multi){?>
        e.parent().find('.multiupload-preview').append('<li class="span2 multiupload-item"><a href="'+a.url+'" target="_blank" class="thumbnail"><img src="'+a.url+'"></a><input type="hidden" name="<?php echo $callback;?>[]" value="'+a.value+'"><em class="delete" title="Удалить это изображение" onclick="deleteMultiImage(this)">×</em></li>');

        $(".multiupload-item").on("mouseover",function(){
            $(this).find(".delete").show();
        });
        $(".multiupload-item").on("mouseout",function(){
            $(this).find(".delete").hide();
        });
<?php }else{?>
        e.val(a.value);
<?php }?>
        if(c===false){
            return true;
        }
        window.iCMS_MODAL.destroy();
    }

    $(".modal_photo_<?php echo $callback.'_'.$unid;?>").on("click",function(){
        var pic = $("#<?php echo $callback;?>").val(),href = $(this).attr("href");
        if(pic){
            $("#modal-iframe").attr("src",href+"&pic="+pic);
        }else{
            var check = $(this).attr("data-check"),title=$(this).attr("title");
            if(check){
                window.iCMS_MODAL.destroy();
                iCMS.alert("Нет изображения, вы не можете сейчас "+title);
            }
        }
        return false;
    });

    $(".multiupload-item").on("mouseover",function(){
        $(this).find(".delete").show();
    });
    $(".multiupload-item").on("mouseout",function(){
        $(this).find(".delete").hide();
    });
});

<?php if($multi){?>
function deleteMultiImage(elm){
    iCMS.dialog({
        content:'Вы уверены, что хотите удалить это изображение?',
        label: 'warning',
        icon: 'warning',
        okValue: 'Ok',
        ok: function () {
            $(elm).parent().remove();
            return true;
        },
        cancelValue: 'Отменить',
        cancel: function(){
            return true;
        }
    });
}
<?php }?>
</script>
