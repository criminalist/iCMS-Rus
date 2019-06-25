<?php

defined('iPHP') OR exit('What are you doing?');
admincp::head(!isset($_GET['modal']));
?>
<div class="widget-box widget-plain" id="<?php echo APP_BOXID;?>">
    <div class="widget-title">
        <h5 class="brs">剧情列表</h5>
        <span class="icon">
            <a href="<?php echo APP_URI; ?>&do=add&video_id=<?php echo $video_id; ?>">
                <i class="fa fa-plus-square"></i> Добавить новую серию
            </a>
        </span>
    </div>
    <div class="widget-content nopadding">
        <form action="<?php echo APP_FURI; ?>&do=batch" method="post" class="form-inline" id="<?php echo APP_FORMID;?>" target="iPHP_FRAME">
            <table class="table table-bordered table-condensed table-hover">
                <thead>
                    <tr>
                        <th><i class="fa fa-arrows-v"></i></th>
                        <th class="span1">ID</th>
                        <th> Название </th>
                        <th class="span2">日期</th>
                        <th style="width:80px;">VIP</th>
                        <th style="width:120px;"> Операция </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ((array)$rs as $key => $value) {
                        $id = $value['id'];
                        $value = videoApp::story_value($value);
                    ?>
                    <tr id="id<?php echo $id; ?>">
                        <td><input type="checkbox" name="id[]" value="<?php echo $id ; ?>" /></td>
                        <td><?php echo $id ; ?></td>
                        <td><?php echo $value['title'] ; ?></td>
                        <td><?php if($value['addtime']) echo get_date($value['addtime'],'Y-m-d H:i');?></td>
                        <td><?php if($value['vip']) echo 'vip';?></td>
                        <td>
                            <?php if($value['status']=="1"){ ?>
                            <a href="<?php echo $value['url']; ?>" class="btn btn-success btn-mini" target="_blank">查看</a>
                            <?php } ?>
                            <a href="<?php echo APP_URI; ?>&do=add&id=<?php echo $id ; ?>&video_id=<?php echo $video_id; ?>" class="btn btn-primary btn-mini">编辑</a>
                            <a href="<?php echo APP_FURI; ?>&do=del&id=<?php echo $id ; ?>&video_id=<?php echo $video_id; ?>" target="iPHP_FRAME" class="del btn btn-danger btn-mini" onclick="return confirm('确定要删除?');"/> Удалить</a>
                        </td>
                    </tr>
                    <?php } ?>
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
