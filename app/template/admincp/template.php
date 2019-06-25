<?php

defined('iPHP') OR exit('What are you doing?');
admincp::head();
?>
<script type="text/javascript">
$(function(){
	$('input[type=file]').uniform();
	<?php if($this->click){?>
    $('[data-click="<?php echo $this->click;?>"]').click(function() {
    	if (this.checked) {
			var state	= window.top.modal_<?php echo $this->callback;?>('<?php echo $this->target; ?>',this);
	    	if(!state){
	    		window.top.iCMS_MODAL.destroy();
	    	}
    	}
    });
    <?php }?>
    $('#mkdir').click(function() {
  		iCMS.dialog({
          follow:this,
          content:document.getElementById('mkdir-box'),
          modal:false,
  		    title: 'Создать новый каталог',
          okValue:'创建',
          ok: function () {
              var a = $("#newdirname"),n = a.val(),d=this;
              if(n==""){
                iCMS.alert("Введите имя каталога!");
                a.focus();
                return false;
              }else{
                $.post('<?php echo __ADMINCP__;?>=files&do=mkdir',{name: n,'pwd':'<?php echo $pwd;?>'},
                function(j){
                  if(j.code){
                      d.content(j.msg).button([{value: 'Завершить',
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
      <h5 class="brs">文件管理</h5>
      <span class="label label-info pwd"><a href="<?php echo $URI.$parent; ?>" class="tip-bottom" title="Текущий путь">iCMS::/<?php echo $pwd;?></a></span>
      <div class="buttons"> <a href="#" class="btn btn-mini btn-success" id="mkdir"><i class="fa fa-folder"></i> Создать новый каталог</a> <a href="<?php echo APP_URI; ?>&do=multi&from=modal&dir=<?php echo $pwd;?>" title="上传文件" data-toggle="modal" data-meta='{"width":"98%","height":"580px"}' class="btn btn-mini btn-primary" id="upload"> <i class="fa fa-upload"></i> 上传文件</a> </div>
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
              <td colspan="2"><a href="<?php echo $URI.$parent; ?>"><i class="fa fa-angle-double-up"></i> Вернуться на уровень выше</a></td>
            </tr>
          </tbody>
        </table>
        <?php }  ?>
        <?php if($dirRs){ ?>
        <table class="table table-bordered table-condensed table-hover">
          <thead>
            <tr>
              <th><i class="fa fa-arrows-v"></i></th>
              <th style="width:320px;">Каталог</th>
              <th>Операции</th>
            </tr>
          </thead>
          <tbody>
            <?php if($dir){
          	$parentShow	= true;
          ?>
            <tr>
              <td style="padding:3px;2px;1px;2px;"><span class="label label-info"> Выбрать </span></td>
              <td colspan="2"><a href="<?php echo $URI.$parent; ?>"><i class="fa fa-angle-double-up"></i> Вернуться на уровень выше</a></td>
            </tr>
            <?php }  ?>
            <?php
	            $_count		= count($dirRs);
	            for($i=0;$i<$_count;$i++){
            ?>
            <tr>
              <td><input type="checkbox" value="<?php echo $dirRs[$i]['path'] ; ?>" data-click="dir"/></td>
              <td><a href="<?php echo $dirRs[$i]['url']; ?>" class="dirname"><?php echo $dirRs[$i]['name'] ; ?></a></td>
              <td class="op"><a class="btn btn-small mvr"><i class="fa fa-edit"></i> 重命名</a> <a href="<?php echo APP_URI; ?>&do=add&from=modal" class="btn btn-small" data-toggle="modal" data-meta='{"width":"600px","height":"360px"}' title="Загрузить"><i class="fa fa-upload"></i> Загрузить</a> <a href="<?php echo APP_FURI; ?>&do=del&id=<?php echo $rs[$i]['id'] ; ?>&indexid=<?php echo $rs[$i]['indexid'] ; ?>" target="iPHP_FRAME" class="del btn btn-small" title='永久删除'  onclick="return confirm('Вы уверены, что хотите удалить?');"/><i class="fa fa-trash-o"></i> Удалить</a></td>
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
              <th style="width:320px;">Имя файла <span class="label label-important">Подсказка: вы можете выбрать несколько элементов одновременно</span></th>
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
              <td colspan="7"><a href="<?php echo $URI.$parent; ?>"><i class="fa fa-angle-double-up"></i> Вернуться на уровень выше</a></td>
            </tr>
            <?php }  ?>
            <?php
            $_count		= count($fileRs);
            for($i=0;$i<$_count;$i++){
            	$icon	= files::icon($fileRs[$i]['name']);
            ?>
            <tr>
              <td><input type="checkbox" value="<?php echo $fileRs[$i]['path'] ; ?>" url="<?php echo $fileRs[$i]['url'] ; ?>"  data-click="file"/></td>
              <td><?php if (in_array(strtolower($fileRs[$i]['ext']),files::$IMG_EXT)){?>
                <a href="###" class="tip-right" title="<img src='<?php echo $fileRs[$i]['url'] ; ?>' width='120px'/>"><?php echo $icon ; ?> <?php echo $fileRs[$i]['name'] ; ?></a>
                <?php }else{?>
                <?php echo $icon ; ?> <?php echo $fileRs[$i]['name'] ; ?>
                <?php } ?></td>
              <td><?php echo $fileRs[$i]['ext'] ; ?></td>
              <td><?php echo $fileRs[$i]['size'] ; ?></td>
              <td><?php echo $fileRs[$i]['modified'] ; ?></td>
              <td class="op"><a class="btn btn-small" href="<?php echo $href; ?>" data-toggle="modal" title="Просмотр"><i class="fa fa-eye"></i> Посмотреть </a> <a class="btn btn-small" href="<?php echo $href; ?>" data-toggle="modal" title="Просмотр"><i class="fa fa-upload"></i> Загрузить</a> <a class="btn btn-small" href="<?php echo $href; ?>" data-toggle="modal" title="Просмотр"><i class="fa fa-trash-o"></i> Удалить</a></td>
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
<div id="mkdir-box">
  <input class="span2" id="newdirname" type="text" placeholder="Введите имя каталога">
</div>
<?php admincp::foot();?>
