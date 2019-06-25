<?php

defined('iPHP') OR exit('What are you doing?');
admincp::head(false);
?>
<div class="widget-box widget-plain" id="<?php echo APP_BOXID;?>">
    <div class="widget-title"> <span class="icon"> <i class="fa fa-plus-square"></i> </span>
    <h5><?php echo empty($this->id)?'添加':'修改' ; ?> 《 <?php echo $video['title'];?> 》</b></h5>
    <ul class="nav nav-tabs" id="-add-tab">
        <li class="active">
            <a href="#-add-base" data-toggle="tab">
                <i class="fa fa-info-circle"></i> <?php echo empty($this->id)?'新资源':$rs['title']; ?>
            </a>
        </li>
    </ul>
</div>
<div class="widget-content nopadding">
    <form action="<?php echo APP_FURI; ?>&do=save" method="post" class="form-inline" id="<?php echo APP_FORMID;?>" target="iPHP_FRAME">
        <input name="id" type="hidden" value="<?php echo $this->id ; ?>" />
        <input name="video_id" type="hidden" value="<?php echo $rs['video_id'];?>" />
        <div class="tab-content">
            <div id="-add-base" class="tab-pane active">
                <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i> 添加</button>
                <div class="clearfloat mb10"></div>
                <div class="input-prepend">
                    <span class="add-on">类型</span>
                    <select name="type" id="type" class="chosen-select span4">
                        <?php foreach (videoApp::$typeMap as $key => $type) {?>
                        <option value="<?php echo $key;?>"> <?php echo $type[1];?> [type='<?php echo $key;?>']</option>
                        <?php }?>
                    </select>
                </div>
                <div class="clearfloat mb10"></div>
                <div class="input-prepend input-append">
                    <span class="add-on">来源</span>
                    <select name="from" id="from" class="chosen-select span4">
                        <?php foreach ($fromArray as $key => $fa) {?>
                        <option value="<?php echo $fa['val'];?>"> <?php echo $fa['name'];?> [from='<?php echo $fa['val'];?>']</option>
                        <?php }?>
                        <?php echo propAdmincp::get("from") ; ?>
                    </select>
                    <?php echo propAdmincp::btn_add('添加来源',null,'video');?>
                </div>
                <div class="clearfloat mb10"></div>
                <div class="input-prepend">
                    <span class="add-on">标题</span>
                    <input type="text" name="title" class="span6" id="title" value="<?php echo $rs['title'];?>"/>
                </div>
                <span class="help-inline">添加时可批量可不填写或者设置标题格式,如:第%s集</span>
                <div class="clearfloat mb10"></div>
                <div class="input-prepend" style="width:90%;">
                    <span class="add-on">资源</span>
                    <?php if(empty($this->id)){?>
                    <textarea name="src" style="width:90%;height:600px;"><?php echo $rs['src'] ; ?></textarea>
                    <?php }else{?>
                    <input type="text" name="src" class="span6" id="src" value="<?php echo $rs['src'];?>"/>
                    <?php }?>
                </div>
                <span class="help-inline">添加时可批量添加 格式: 标题$资源 下载名称$下载地址$大小</span>
                <div class="clearfloat mb10"></div>
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
                <div class="input-prepend input-append">
                    <span class="add-on">大小</span>
                    <input type="text" name="size" class="span3" id="size" value="<?php echo $rs['size'];?>"/>
                    <span class="add-on">KB</span>
                </div>
                <span class="help-inline">下载资源可填写</span>
                <div class="clearfloat mb10"></div>
                <div class="input-prepend">
                    <span class="add-on">添加时间</span>
                  <input id="addtime" class="ui-datepicker" value="<?php echo get_date($rs['addtime'],'Y-m-d H:i:s') ; ?>"  name="addtime" type="text" style="width:230px"/>
                </div>
                <div class="clearfloat mb10"></div>
                <div class="input-prepend input-append">
                    <span class="add-on">Статус</span>
                    <select name="status" id="status" class="chosen-select span4">
                        <option value="0"> 草稿 [status='0']</option>
                        <option value="1"> 正常 [status='1']</option>
                        <?php echo propAdmincp::get("status") ; ?>
                    </select>
                    <?php echo propAdmincp::btn_add('Добавить статус');?>
                </div>
            </div>
        </div>
        <div class="form-actions">
            <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i> 添加</button>
        </div>
    </form>
</div>
<script type="text/javascript">
$(function() {
    iCMS.select('status', "<?php echo $rs['status'] ; ?>");
    iCMS.select('type', "<?php echo $rs['type'] ; ?>");
    iCMS.select('from', "<?php echo $rs['from'] ; ?>");
});
</script>
<?php admincp::foot();?>
