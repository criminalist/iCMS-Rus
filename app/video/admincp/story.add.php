<?php

defined('iPHP') OR exit('What are you doing?');
admincp::head(false);
?>
<div class="widget-box widget-plain" id="<?php echo APP_BOXID;?>">
    <div class="widget-title"> <span class="icon"> <i class="fa fa-plus-square"></i> </span>
    <h5><?php echo empty($this->id)?'Добавить':'Редактировать' ; ?> <?php echo $video['title'];?></b></h5>
    <ul class="nav nav-tabs" id="-add-tab">
        <li class="active">
            <a href="#-add-base" data-toggle="tab">
                <i class="fa fa-info-circle"></i> <?php echo empty($this->id)?'Новая серия':$rs['name']; ?>
            </a>
        </li>
    </ul>
</div>
<div class="widget-content nopadding">
    <form action="<?php echo APP_FURI; ?>&do=save" method="post" class="form-inline" id="<?php echo APP_FORMID;?>" target="iPHP_FRAME">
        <input name="id" type="hidden" value="<?php echo $this->id ; ?>" />
        <div class="tab-content">
            <div id="-add-base" class="tab-pane active">
                <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i> Сохранить</button>
                <div class="clearfloat mb10"></div>
                <div class="input-prepend">
                    <span class="add-on"> Название </span>
                    <input type="text" name="title" class="span6" id="title" value="<?php echo $rs['title'];?>"/>
                </div>
                <div class="clearfloat mb10"></div>
                <div class="input-prepend">
                    <span class="add-on">视频ID</span>
                    <input type="text" name="video_id" class="span3" id="video_id" value="<?php echo $rs['video_id'];?>"/>
                </div>
                <div class="input-prepend">
                    <span class="add-on">Сортировка</span>
                    <input type="text" name="sortnum" class="span3" id="sortnum" value="<?php echo $rs['sortnum'];?>"/>
                </div>
                <div class="clearfloat mb10"></div>
                <div class="input-prepend">
                    <span class="add-on">VIP类型</span>
                    <input type="text" name="vip" class="span3" id="vip" value="<?php echo $rs['vip'];?>"/>
                </div>
                <div class="clearfloat mb10"></div>
                <div class="input-prepend">
                    <span class="add-on">添加时间</span>
                  <input id="addtime" class="ui-datepicker" value="<?php echo get_date($rs['addtime'],'Y-m-d H:i:s') ; ?>"  name="addtime" type="text" style="width:230px"/>
                </div>
                <div class="clearfloat mb10"></div>
                <div class="input-prepend input-append">
                    <span class="add-on">Статус</span>
                    <select name="status" id="status" class="chosen-select span4">
                        <option value="0"> Черновик[status='0']</option>
                        <option value="1"> Опубликован [status='1']</option>
                        <?php echo propAdmincp::get("status") ; ?>
                    </select>
                    <?php echo propAdmincp::btn_add('Добавить статус');?>
                </div>
                <div class="clearfloat mb10"></div>
                <script type="text/javascript" charset="utf-8" src="./app/admincp/ui/iCMS.ueditor.js"></script>
                <script type="text/javascript" charset="utf-8" src="./app/admincp/ui/ueditor/ueditor.all.min.js"></script>
                <a id="cleanupEditor-btn" class="btn btn-inverse hide" href="javascript:iCMS.editor.cleanup();"><i class="fa fa-magic"></i> 自动排版</a>
                <a id="createEditor-btn" class="btn btn-success" href="javascript:createEditor();"><i class="fa fa-check"></i> Использовать редактор</a>
                <a id="deleteEditor-btn" class="btn btn-inverse hide" href="javascript:deleteEditor();"><i class="fa fa-times"></i> 关闭编辑器</a>
                <div class="clearfix mt10"></div>
                <textarea type="text/plain" id="editor-body" name="content" style="width:90%;height:600px;"><?php echo $rs['content'] ; ?></textarea>
            </div>
        </div>
        <div class="form-actions">
            <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i> Сохранить</button>
        </div>
    </form>
</div>
<script type="text/javascript">
$(function() {
    iCMS.select('status', "<?php echo $rs['status'] ; ?>");
});
var ieditor = iCMS.editor;

function deleteEditor() {
    ieditor.destroy();
    $("#cleanupEditor-btn").hide();
    $("#createEditor-btn").show();
    $("#deleteEditor-btn").hide();
    window.setTimeout(function() {
        var text = $("#editor-body").text()
                .replace(/<p[^>]*?>/g, "\n")
                .replace(/<br[^>]*?>/g, "\n")
                .replace(/<[^>]*?>/g, "");
        console.log(text);
        $("#editor-body").val(text);
    }, 800);
}
function createEditor() {
    ieditor.create('editor-body');
    $("#cleanupEditor-btn").show();
    $("#createEditor-btn").hide();
    $("#deleteEditor-btn").show();
    window.setTimeout(function() {
        ieditor.cleanup();
    }, 800);
}
</script>
<?php admincp::foot();?>
