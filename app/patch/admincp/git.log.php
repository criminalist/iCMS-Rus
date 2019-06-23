<?php

defined('iPHP') OR exit('Oops, something went wrong');
admincp::head(false);
?>
<div class="widget-box widget-plain">
  <div class="widget-content nopadding">
    <table class="table table-bordered table-condensed table-hover">
      <thead>
        <tr>
          <th><i class="fa fa-arrows-v"></i></th>
          <th>版本</th>
          <th>信息</th>
          <th>作者</th>
          <th>Время обновления </th>
          <th></th>
        </tr>
      </thead>
      <tbody class="log-list">
        <?php
          $uri=$_GET['path']?"&path=".urlencode($_GET['path']):null;

          if($log) foreach ($log AS $k => $value) {
              $commit_id = $value['commit_id'];
        ?>
        <tr id="<?php echo $commit_id; ?>">
          <td><?php echo $k+1; ?></td>
          <td><?php echo substr($commit_id, 0,16) ; ?></td>
          <td class="span7"><?php echo $value['info'][3]; ?></td>
          <td><?php echo $value['info'][1]; ?></td>
          <td><?php echo date('Y-m-d H:i',$value['info'][2]); ?></td>
          <td>
            <!-- <a href="<?php echo APP_FURI; ?>&do=git_log&commit_id=<?php echo $commit_id; ?>" class="gitlog btn btn-small" title="查看这个版本详细信息"><i class="fa fa-eye"></i> Посмотреть </a> -->
            <a href="<?php echo APP_FURI; ?>&do=git_show&commit_id=<?php echo $commit_id; ?>&git=true<?php echo $uri;?>" class="btn btn-info btn-small"
              data-toggle="modal" data-target="#iCMS-MODAL" data-meta="{&quot;width&quot;:&quot;85%&quot;,&quot;height&quot;:&quot;450px&quot;}"
              title="Посмотреть <?php echo substr($commit_id, 0,16) ; ?>详细信息"><i class="fa fa-eye"></i>Посмотреть</a>
            <a href="<?php echo APP_FURI; ?>&do=git_download&last_commit_id=<?php echo $commit_id; ?>&release=<?php echo date('Ymd',$value['info'][2]); ?>&git=true<?php echo $uri;?>" class="btn btn-success btn-small" target="_blank" title="Обновление до этой версии"><i class="fa fa-check"></i>Обновить</a>
          </td>
        </tr>
        <?php }?>
        <tr>
          <td></td>
          <td><?php echo substr(GIT_COMMIT, 0,16) ; ?></td>
          <td>
            <?php if($log){ ?>
            Текущая версия системы
            <?php }else{?>
            Вы используете последнюю версию системы
            <?php }?>
          </td>
          <td><?php echo GIT_AUTHOR; ?></td>
          <td><?php echo date('Y-m-d H:i',GIT_TIME); ?></td>
          <td></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
<script>
// $(function(){
//   var param    = {}
//   var type_map = {'D':'Удалить','A':'增加','M':'更改'}
//   $(".gitlog").click(function(event) {
//     event.preventDefault();
//     var $this = $(this);
//     var url = $this.attr('href');
//     $.get(url, function(c) {
//       // $("#git_commit").html(c[0]);
//       var fileList =''
//       $.each(c[1], function(index, val) {
//         fileList+='<tr>'
//                     +'<td scope="row">'+index+'</td>'
//                     +'<td>'
//                       +'<div class="checkbox">'
//                         +'<label>'
//                           +'<input type="checkbox" name="filelist"'
//                           +'value="'+val[0]+'@~@'+val[1]+'" checked />'
//                         +'</label>'
//                       +'</div>'
//                     +'</td>'
//                     +'<td>'+type_map[val[0]]+'</td>'
//                     +'<td><div class="filepath">'+val[2]+'</div></td>'
//                   +'</tr>';
//       });
//       var table = '<table class="table table-hover">'
//                 +'<thead>'
//                   +'<tr>'
//                     +'<th>#</th>'
//                     +'<th>Выбрать</th>'
//                     +'<th>执行</th>'
//                     +'<th></th>'
//                   +'</tr>'
//                 +'</thead>'
//                 +'<tbody>'
//                 +fileList
//                 +'</tbody>'
//               +'</table>';

//       iCMS.dialog({
//           content: $(table)
//       });
//     },'json');
//   });
// });
</script>
<?php admincp::foot(); ?>
