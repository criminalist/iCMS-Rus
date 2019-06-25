<?php

defined('iPHP') OR exit('What are you doing?');
admincp::head();
?>
<script type="text/javascript">
$(function(){
    $("#<?php echo APP_FORMID;?>").batch();
    iCMS.select('rtid',"<?php echo $rtid; ?>");

    $('.bind').click(function() {
        var that = this;
        var tid = $(this).data('tid');

        iCMS.dialog({
            follow:that,
            content: document.getElementById('bind_category'),
            quickClose: false,title: null,width:"auto",height:"auto",
            cancelValue: '取消',
            cancel: function(){
                return true;
            },
            okValue: '确定',
            ok: function() {
                var a = $("#bind_category"),cid = $('select',a).val(),me = this;
                var name = $('select',a).find("option:selected").attr('name');
                $.post("<?php echo APP_FURI; ?>&do=bind_category&CSRF_TOKEN=<?php echo iPHP_WAF_CSRF_TOKEN;?>",
                    {'rid': '<?php echo $rid ; ?>','cid': cid,'rtid':tid},
                function(j) {
                    if (j.code) {
                        if(cid){
                            $('span',that).text(name+'[cid:'+cid+']')
                            .addClass('label-success')
                            .removeClass('label-important');
                        }else{
                            $('span',that).text('未绑定').addClass('label-important').removeClass('label-success');
                        }
                    } else {
                        return false;
                    }
                }, "json");
                return true;
            }
        });
    });
});
</script>
<style>
.bind_category li {
    float: left;
    margin: 3px;
    width: 15%;
}
</style>

<div class="iCMS-container">
  <div class="widget-box">
    <div class="widget-title">
      <span class="icon">
        <input type="checkbox" class="checkAll" data-target="#<?php echo APP_BOXID;?>" />
      </span>
      <h5><?php echo $res['name'];?> » 【采集前需绑定分类】</h5>
    </div>
    <div class="widget-content nopadding">
        <form action="<?php echo APP_FURI; ?>&do=bind_category" method="post" class="form-inline" target="iPHP_FRAME">
            <table class="table table-bordered table-condensed table-hover">
              <tbody>
                <tr>
                    <td>
                        <ul class="bind_category">
                <li><a href="<?php echo APP_URI; ?>&do=list&rid=<?php echo $rid ; ?>">全部</a></li>
                <?php
                $categoryArray = category::get($bindCategory);
                foreach ((array)$data['class']['ty'] as $tid => $name) {
                    $bcid = $bindCategory[$tid];
                ?>
                <li>
                    <a href="<?php echo APP_URI; ?>&do=list&rid=<?php echo $rid ; ?>&rtid=<?php echo $tid ; ?>">
                      <?php echo $name ; ?>[<?php echo $tid ; ?>]
                    </a>
                    <label class="bind" data-tid="<?php echo $tid ; ?>">
                        <?php
                            if($bcid){
                                $C = (array)$categoryArray[$bcid];
                        ?>
                        <span class="label label-success"><?php echo $C['name'] ; ?> [cid:<?php echo $bcid ; ?>]</span>
                        <?php }else{ ?>
                        <span class="label label-important">未绑定</span>
                        <?php } ?>
                    </label>
                </li>
                <?php } ?>
                        </ul>
                    </td>
                </tr>
              </tbody>
            </table>
        </form>
    </div>
  </div>
  <div class="widget-box">
    <div class="widget-title"> <span class="icon"> <i class="fa fa-search"></i> </span>
      <h5>搜索</h5>
    </div>
    <div class="widget-content">
      <form action="<?php echo iPHP_SELF ; ?>" method="get" class="form-inline">
        <input type="hidden" name="app" value="<?php echo admincp::$APP_NAME;?>" />
        <input type="hidden" name="do" value="<?php echo admincp::$APP_DO;?>" />
        <input type="hidden" name="rid" value="<?php echo $rid;?>" />
        <div class="input-prepend input-append"> <span class="add-on">分类</span>
          <select name="rtid" id="rtid" class="span3 chosen-select">
            <option>所有分类</option>
            <?php
            foreach ((array)$data['class']['ty'] as $tid => $name) {
            ?>
            <option value="<?php echo $tid ; ?>"><?php echo $name ; ?></option>
            <?php } ?>
          </select>
        </div>
        <div class="input-prepend input-append"> <span class="add-on">每页</span>
          <input type="text" name="perpage" id="perpage" value="<?php echo $data['perpage'] ; ?>" style="width:36px;"/>
          <span class="add-on">条记录</span>
        </div>
        <div class="input-prepend input-append"> <span class="add-on">关键字</span>
          <input type="text" name="keywords" class="span2" id="keywords" value="<?php echo iSecurity::escapeStr($_GET['keywords']) ; ?>" />
          <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i> 搜 索</button>
        </div>
        <a href="<?php echo APP_URI; ?>&do=crawl&dt=today&rid=<?php echo $rid; ?>&rtid=<?php echo $rtid; ?>" class="btn btn-success"><i class="fa fa-play"></i> 采集今天</a>
        <a href="<?php echo APP_URI; ?>&do=crawl&dt=week&rid=<?php echo $rid; ?>&rtid=<?php echo $rtid; ?>" class="btn btn-info"><i class="fa fa-play"></i> 采集本周</a>
        <a href="<?php echo APP_URI; ?>&do=crawl&dt=month&rid=<?php echo $rid; ?>&rtid=<?php echo $rtid; ?>" class="btn btn-warning"><i class="fa fa-play"></i> 采集本月</a>
        <a href="<?php echo APP_URI; ?>&do=crawl&dt=all&rid=<?php echo $rid; ?>&rtid=<?php echo $rtid; ?>" class="btn btn-primary"><i class="fa fa-play"></i> 采集全部</a>

      </form>
    </div>
  </div>
  <div class="widget-box" id="<?php echo APP_BOXID;?>">
    <div class="widget-title"> <span class="icon">
        <input type="checkbox" class="checkAll" data-target="#<?php echo APP_BOXID;?>" />
        </span>
        <h5>数据</h5>
    </div>
    <div class="widget-content nopadding">
      <form action="<?php echo APP_FURI; ?>&do=crawl&dt=ids&rid=<?php echo $rid; ?>&CSRF_TOKEN=<?php echo iPHP_WAF_CSRF_TOKEN;?>" method="post" class="form-inline" id="<?php echo APP_FORMID;?>" target="_blank">
        <table class="table table-bordered table-condensed table-hover">
          <thead>
            <tr>
              <th><i class="fa fa-arrows-v"></i></th>
              <th>Заголовок</th>
              <th>分类</th>
              <th>来源</th>
              <th>时间</th>
            </tr>
          </thead>
          <tbody>
        <?php
        foreach ((array)$rs as $key => $video) {?>
            <tr id="id<?php echo $video['id'] ; ?>">
              <td><input type="checkbox" name="id[]" value="<?php echo $video['id'] ; ?>" /></td>
              <td><?php echo $video['name'] ; ?><span class="label label-info"><?php echo $video['note']?:'' ; ?></span></td>
              <td><a href="<?php echo APP_URI; ?>&do=list&rid=<?php echo $rid ; ?>&rtid=<?php echo $video['tid'] ; ?>"><?php echo $video['type'] ; ?></a></td>
              <td><?php echo $video['dt'] ; ?></td>
              <td><?php echo $video['last']; ?></td>
            </tr>
            <?php }  ?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="9"><div class="pagination pagination-right" style="float:right;"><?php echo iPagination::$pagenav ; ?></div>
                <div class="input-prepend input-append mt20"> <span class="add-on">全选
                  <input type="checkbox" class="checkAll checkbox" data-target="#<?php echo APP_BOXID;?>" />
                  </span>
                  <a data-toggle="batch" data-action="select" class="btn"><i class="fa fa-magnet"></i> 采集</a>
                </div></td>
            </tr>
          </tfoot>
        </table>
      </form>
    </div>
  </div>
</div>
<div class='iCMS-batch'>
</div>
<div id="bind_category" class="hide">
    <select>
    <option value=''>取消绑定</option>
    <?php echo $loc_category ; ?>
    </select>
</div>
<?php admincp::foot();?>
