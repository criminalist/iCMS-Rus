<?php

defined('iPHP') OR exit('Oops, something went wrong');
admincp::head($navbar);
?>
<script type="text/javascript">
var click_type = {'file':'Файл','dir':'Каталог'};

function modal_callback(obj){
  var state = window.parent.modal_<?php echo $this->callback;?>('<?php echo $this->target; ?>',obj);
  if(state=='off'){
    window.top.iCMS_MODAL.destroy();
  }
}
$(function(){
    $('input[type=file]').uniform();
    <?php if($this->click){?>
    $('[data-click]').click(function() {
      var click = $(this).attr('data-click');
      if(click=="<?php echo $this->click;?>"){
        modal_callback(this);
      }else{
        if($(this).prop("checked")){
          $(this).prop("checked", false);
          $.uniform.update($(this));
          iCMS.alert("当前只能选择"+click_type['<?php echo $this->click;?>']);
        }
      }
    });
    <?php }?>
    <?php if($this->click=='file'){?>
    $(".checkAll").click(function() {
        var target = $(this).attr('data-target');
        $('[data-click="file"]:checkbox', $(target)).each(function() {
            if (this.checked) {
              modal_callback(this);
            }
        });
    });
    <?php }?>
    $('#mkdir').click(function() {
  		iCMS.dialog({
          follow:this,height:'auto',
          content:document.getElementById('mkdir-box'),
          modal:false,
  		    title: '创建新目录',
          okValue:'Создать',
          ok: function () {
              var a = $("#newdirname"),n = a.val(),d=this;
              if(n==""){
                iCMS.alert("Введите имя каталога!");
                a.focus();
                return false;
              }else{
                $.post('<?php echo __ADMINCP__;?>=files&do=mkdir&CSRF_TOKEN=<?php echo iPHP_WAF_CSRF_TOKEN;?>',{name: n,'pwd':'<?php echo $pwd;?>'},
                function(j){
                  if(j.code){
                      d.content(j.msg).button([{value: 'Закрыть',
                      callback: function () {
                        window.location.reload();
                      },autofocus: true
                    }]);
                    window.setTimeout(function(){
                      window.location.reload();
                    },3000);
                  }else{
                    iCMS.alert(j.msg);
                    a.focus();
                    return false;
                  }
                },"json");
              }
              return false;
          }
  		});
    });
});
</script>
<style>
.op { text-align: right !important; padding-right: 28px !important; }
#files-explorer tbody .checker { margin-left: 6px !important; }
#files-explorer .pwd { float:left; padding: 5px; margin: 6px 15px 0 10px; }
#files-explorer .pwd a { color: #fff; }
#files-explorer td { line-height: 2em; }
#mkdir-box, #upload-box { display:none; }
</style>
<?php if($this->from!='modal'){?>
<div class="iCMS-container">
  <?php } ?>
  <div class="widget-box<?php if($this->from=='modal'){?> widget-plain<?php } ?>" id="files-explorer">
    <div class="widget-title"> <span class="icon">
      <input type="checkbox" class="checkAll" data-target="#files-explorer" />
      </span>
      <h5 class="brs">Файловый менеджер</h5>
      <span class="label label-info pwd"><a href="<?php echo $URI.$parent; ?>" class="tip-bottom" title="Текущий путь">iCMS://<?php echo $pwd;?></a></span>
      <div class="buttons">
        <a href="javascript:;" class="btn btn-mini btn-success" id="mkdir"><i class="fa fa-folder"></i> Создать новый каталог </a> <a href="<?php echo __ADMINCP__; ?>=files&do=multi&from=modal&dir=<?php echo $pwd;?>" title="上传文件" data-toggle="modal" data-meta='{"width":"98%","height":"580px"}' class="btn btn-mini btn-primary" id="upload"> <i class="fa fa-upload"></i>Загрузить файл</a> </div>
    </div>
    <div class="widget-content nopadding">
      <form action="<?php echo APP_FURI; ?>&do=batch" method="post" class="form-inline" id="<?php echo APP_FORMID;?>" target="iPHP_FRAME">
        <?php if(empty($dirRs) && empty($fileRs)){
          	$parentShow	= true;
          ?>
        <table class="table table-bordered table-condensed table-hover">
          <thead>
            <tr>
              <th><i class="fa fa-arrows-v"></i></th>
              <th style="width:300px;"></th>
              <th>Операции</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td></td>
              <td colspan="2"><a href="<?php echo $URI.$parent; ?>"><i class="fa fa-angle-double-up"></i>Подняться на уровень вверх</a></td>
            </tr>
          </tbody>
        </table>
        <?php }  ?>
        <?php if($dirRs){ ?>
        <table class="table table-bordered table-condensed table-hover">
          <thead>
            <tr>
              <th><i class="fa fa-arrows-v"></i></th>
              <th style="width:320px;"> Каталог </th>
              <th>Операции</th>
            </tr>
          </thead>
          <tbody>
            <?php if($dir){
          	$parentShow	= true;
          ?>
            <tr>
              <td style="padding:3px;2px;1px;2px;"><span class="label label-info">Выбрать</span></td>
              <td colspan="2"><a href="<?php echo $URI.$parent; ?>"><i class="fa fa-angle-double-up"></i>Подняться на уровень вверх</a></td>
            </tr>
            <?php }  ?>
            <?php
	            $_count		= count($dirRs);
	            for($i=0;$i<$_count;$i++){
            ?>
            <tr id="<?php echo md5($dirRs[$i]['path']); ?>">
              <td><input type="checkbox" value="<?php echo $dirRs[$i]['path'] ; ?>" data-click="dir"/></td>
              <td><a href="<?php echo $dirRs[$i]['url']; ?>" class="dirname"><?php echo $dirRs[$i]['name'] ; ?></a></td>
              <td class="op">
                <?php if(0){ ?>
                <a class="btn btn-small mv_dir"><i class="fa fa-edit"></i> 重命名</a>
                <a href="<?php echo __ADMINCP__; ?>=files&do=multi&from=modal&dir=<?php echo $dirRs[$i]['path'] ; ?>" class="btn btn-small" data-toggle="modal" data-meta='{"width":"98%","height":"580px"}' title="上传到此目录"><i class="fa fa-upload"></i> Загрузить </a>
                <?php } ?>
                <a class="btn btn-danger btn-small" href="<?php echo __ADMINCP__; ?>=files&frame=iPHP&do=deldir&path=<?php echo $dirRs[$i]['path'] ; ?>" target="iPHP_FRAME" title="Удалить каталог" onclick="return confirm('Вы уверены, что хотите удалить?');"><i class="fa fa-trash-o"></i> Удалить</a>
            </tr>
            <?php }  ?>
          </tbody>
        </table>
        <?php }  ?>
        <?php if($fileRs){ ?>
        <table class="table table-bordered table-condensed table-hover">
          <thead>
            <tr>
              <th><span class="icon">
                <input type="checkbox" class="checkAll" data-target="#files-explorer" />
                </span></th>
              <th style="width:320px;">Имя файла <span class="label label-important">Подсказка: щелкните поле с несколькими вариантами выбора, чтобы выбрать</span></th>
              <th> Тип </th>
              <th>Размер</th>
              <th>Последнее изменение</th>
              <th>Операции</th>
            </tr>
          </thead>
          <tbody>
            <?php if($parent && !$parentShow){ ?>
            <tr>
              <td></td>
              <td colspan="7"><a href="<?php echo $URI.$parent; ?>"><i class="fa fa-angle-double-up"></i>Подняться на уровень вверх</a></td>
            </tr>
            <?php }  ?>
            <?php
            $_count		= count($fileRs);
            for($i=0;$i<$_count;$i++){
            	$icon	= files::icon($fileRs[$i]['name']);
            ?>
            <tr id="<?php echo md5($fileRs[$i]['path']) ; ?>">
              <td><input type="checkbox" value="<?php echo $fileRs[$i]['path'] ; ?>" url="<?php echo $fileRs[$i]['url'] ; ?>"  data-click="file"/></td>
              <td><?php if (in_array(strtolower($fileRs[$i]['ext']),files::$IMG_EXT)){?>
                <a href="###" class="tip-right" title="<img src='<?php echo $fileRs[$i]['url'] ; ?>' width='120px'/>"><?php echo $icon ; ?> <?php echo $fileRs[$i]['name'] ; ?></a>
                <?php }else{?>
                <?php echo $icon ; ?> <?php echo $fileRs[$i]['name'] ; ?>
                <?php } ?></td>
              <td><?php echo $fileRs[$i]['ext'] ; ?></td>
              <td><?php echo $fileRs[$i]['size'] ; ?></td>
              <td><?php echo $fileRs[$i]['modified'] ; ?></td>
              <td class="op">
                  <?php if(0){ ?>
              	  <a class="btn btn-small mv_file"><i class="fa fa-edit"></i> Редактировать </a>
              	  <a class="btn btn-small ed_file"><i class="fa fa-pencil-square"></i> 重命名</a>
                  <a class="btn btn-small" href="<?php echo $href; ?>" data-toggle="modal" title="Загрузить и перезаписать файлы"><i class="fa fa-upload"></i> Загрузить </a>
              	  <?php }?>
                  <?php if($href){?>
              	  <a class="btn btn-small" href="<?php echo $href; ?>" data-toggle="modal" title="查看文件"><i class="fa fa-eye"></i> Посмотреть </a>
              	  <?php }?>
              	  <a class="btn btn-small" href="<?php echo __ADMINCP__; ?>=files&frame=iPHP&do=delfile&path=<?php echo $fileRs[$i]['path'] ; ?>" target="iPHP_FRAME" title="删除文件" onclick="return confirm('Вы уверены, что хотите удалить?');"><i class="fa fa-trash-o"></i> Удалить</a>
              </td>
            </tr>
            <?php }  ?>
          </tbody>
        </table>
        <?php }  ?>
      </form>
    </div>
  </div>
  <?php if($this->from!='modal'){?>
</div>
<?php } ?>
<div id="mkdir-box" style="width:150px;">
  <input class="span2" id="newdirname" type="text" placeholder="Введите имя каталога">
</div>
<?php admincp::foot();?>
