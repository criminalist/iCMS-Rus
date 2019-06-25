<?php

defined('iPHP') OR exit('What are you doing?');
admincp::head(!isset($_GET['modal']));
?>
<div class="widget-box widget-plain" id="<?php echo APP_BOXID;?>">
    <div class="widget-title">
        <h5 class="brs">Список ресурсов</h5>
        <span class="icon">
            <a href="<?php echo APP_URI; ?>&do=add&video_id=<?php echo $video_id; ?>">
                <i class="fa fa-plus-square"></i> 添加新资源
            </a>
        </span>
        <h5 class="brs"> Источник </h5>
        <ul class="nav nav-tabs" id="config-tab">
        <li <?php if($_GET['from']=='') echo 'class="active"';?>><a href="<?php echo iURL::make(array('from'=>'null')) ; ?>">全部来源</a></li>
        <?php if($fromArray)foreach ($fromArray as $type => $froms) {?>
            <?php if($froms)foreach ($froms as $key => $from) {?>
        <li <?php if($_GET['from']==$from['val']) echo 'class="active"';?>><a href="<?php echo iURL::make(array('from'=>$from['val'])) ; ?>"><?php echo $from['name'].$from[1]; ?></a></li>
            <?php }?>
        <?php }?>
        </ul>
    </div>
    <div class="widget-content nopadding">
        <form action="<?php echo APP_FURI; ?>&do=batch" method="post" class="form-inline" id="<?php echo APP_FORMID;?>" target="iPHP_FRAME">
            <table class="table table-bordered table-condensed table-hover">
                <thead>
                    <tr>
                        <th><i class="fa fa-arrows-v"></i></th>
                        <th class="span1">ID</th>
                        <th style="width:80px;"> Источник </th>
                        <th> Название </th>
                        <th class="span2">添加日期</th>
                        <th class="span2">更新日期</th>
                        <th style="width:80px;">VIP</th>
                        <th style="width:120px;"> Операция </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ((array)$rs as $key => $value) {
                        $id = $value['id'];
                        $value = videoApp::res_value($value);
                    ?>
                    <tr id="id<?php echo $id; ?>">
                        <td><input type="checkbox" name="id[]" value="<?php echo $id ; ?>" /></td>
                        <td><?php echo $id ; ?></td>
                        <td><a href="<?php echo iURL::make(array('from'=>$value['from'])) ; ?>"><?php echo $value['from'] ; ?></a></td>
                        <td><?php echo $value['title'] ; ?></td>
                        <td><?php if($value['addtime']) echo get_date($value['addtime'],'Y-m-d H:i');?></td>
                        <td><?php if($value['update']) echo get_date($value['update'],'Y-m-d H:i');?></td>
                        <td><a href="<?php echo iURL::make(array('vip'=>$value['vip'])) ; ?>"><?php if($value['vip']) echo 'vip';?></a></td>
                        <td>
                            <?php if($value['status']=="1"){ ?>
                            <a href="<?php echo $value['url']; ?>" class="btn btn-success btn-mini" target="_blank"> Просмотр </a>
                            <?php } ?>
                            <a href="<?php echo APP_URI; ?>&do=add&id=<?php echo $id ; ?>&video_id=<?php echo $video_id; ?>" class="btn btn-primary btn-mini">Редактировать</a>
                            <a href="<?php echo APP_FURI; ?>&do=del&id=<?php echo $id ; ?>&video_id=<?php echo $video_id; ?>" target="iPHP_FRAME" class="del btn btn-danger btn-mini" onclick="return confirm('Вы уверены, что хотите удалить?');"/> Удалить</a>
                        </td>
                    </tr>
                    <?php }  ?>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="8">
                        <div class="pagination pagination-right" style="float:right;"><?php echo iPagination::$pagenav ; ?></div>
                    </td>
                </tr>
                </tfoot>
            </table>
        </form>
    </div>
</div>
<?php admincp::foot();?>
