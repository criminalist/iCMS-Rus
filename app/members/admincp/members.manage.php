<?php

defined('iPHP') OR exit('Oops, something went wrong');
admincp::head();
?>
<style type="text/css">
.job { font-size: 14px; }
.job table { background: #FFF; text-align: center; }
.day td.today { background: #0054E3; color: #FFF; font-weight: bold; }
.job hr { height: 1px; margin: 2px 0px; }
.job .week th { text-align: center; color: rgb(187, 31, 1); text-align: center; }
</style>
<script type="text/javascript">
$(function(){
  <?php if($_GET['orderby']){ ?>
  iCMS.select('orderby',"<?php echo $_GET['orderby'] ; ?>");
  <?php } ?>
	$("#<?php echo APP_FORMID;?>").batch();
});
</script>
<div class="iCMS-container">
  <div class="widget-box">
    <div class="widget-title"> <span class="icon"> <i class="fa fa-search"></i> </span>
      <h5SEO Заголовок/h5>
    </div>
    <div class="widget-content">
      <form action="<?php echo iPHP_SELF ; ?>" method="get" class="form-inline">
        <input type="hidden" name="app" value="<?php echo admincp::$APP_NAME;?>" />
        <div class="input-prepend input-append"> <span class="add-on">На страницу</span>
          <input type="text" name="perpage" id="perpage" value="<?php echo $maxperpage ; ?>" style="width:36px;"/>
          <span class="add-on">записей</span> </div>
        <div class="input-prepend">
          <span class="add-on">Сортировка</span>
          <select name="orderby" id="orderby" class="span2 chosen-select">
            <option value=""></option>
            <optgroup label="По убыванию"><?php echo $orderby_option['DESC'];?></optgroup>
            <optgroup label="По возрастанию"><?php echo $orderby_option['ASC'];?></optgroup>
          </select>
        </div>
        <div class="input-prepend input-append"> <span class="add-on">Ключевое слово</span>
          <input type="text" name="members" class="span2" id="members" value="<?php echo $_GET['members'] ; ?>" />
          <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i>поиск</button>
        </div>
      </form>
    </div>
  </div>
  <div class="widget-box" id="<?php echo APP_BOXID;?>">
    <div class="widget-title"> <span class="icon">
      <input type="checkbox" class="checkAll" data-target="#<?php echo APP_BOXID;?>" />
      </span>
      <h5>Список пользователей</h5>
    </div>
    <div class="widget-content nopadding">
      <form action="<?php echo APP_FURI; ?>&do=batch" method="post" class="form-inline" id="<?php echo APP_FORMID;?>" target="iPHP_FRAME">
        <table class="table table-bordered table-condensed table-hover">
          <thead>
            <tr>
              <th><i class="fa fa-arrows-v"></i></th>
              <th>ID</th>
              <th>Аккаунт</th>
              <th>Логин</th>
              <th>Группа пользователя</th>
              <th>IP последней авторизации на сайте</th>
              <th style="width:80px;"><a class="fa fa-clock-o tip-top" title="Время последней авторизации"></a></th>
              <th>Операции</th>
            </tr>
          </thead>
          <tbody>
            <?php for($i=0;$i<$_count;$i++){?>
            <tr id="id<?php echo $rs[$i]['uid'] ; ?>">
              <td><?php if($rs[$i]['uid']!="1"){?><input type="checkbox" name="id[]" value="<?php echo $rs[$i]['uid'] ; ?>" /><?php } ; ?></td>
              <td><?php echo $rs[$i]['uid'] ; ?></td>
              <td><a class="tip-top" title="Дата и время регистрации:<?php if($rs[$i]['regtime']) echo get_date($rs[$i]['regtime'],"Y-m-d") ; ?><hr />累计登陆次数:<?php echo $rs[$i]['logintimes'] ; ?>"><?php echo $rs[$i]['username'] ; ?></a></td>
              <td><?php echo $rs[$i]['nickname'] ; ?></td>
              <td><a href="<?php echo APP_DOURI; ?>&gid=<?php echo $rs[$i]['gid'] ; ?>"><?php echo $this->groupAdmincp->array[$rs[$i]['gid']]['name'] ; ?></a></td>
              <td><?php echo $rs[$i]['lastip'] ; ?></td>
              <td><?php if($rs[$i]['lastlogintime']) echo get_date($rs[$i]['lastlogintime'],"Y-m-d") ; ?></td>
              <td>
                <a href="<?php echo APP_URI; ?>&do=job&id=<?php echo $rs[$i]['uid'] ; ?>" class="btn btn-small"><i class="fa fa-bar-chart-o"></i> Статистика</a>
                <a href="<?php echo __ADMINCP__; ?>=article&userid=<?php echo $rs[$i]['uid'] ; ?>" class="btn btn-small"><i class="fa fa-list-alt"></i> Посты</a>
                <a href="<?php echo APP_URI; ?>&do=add&id=<?php echo $rs[$i]['uid'] ; ?>" class="btn btn-small"><i class="fa fa-edit"></i> Редактировать </a>
                <?php if($rs[$i]['uid']!="1"){ ; ?>
                <a href="<?php echo APP_FURI; ?>&do=del&id=<?php echo $rs[$i]['uid'] ; ?>" target="iPHP_FRAME" class="del btn btn-small" title='Удалить навсегда'  onclick="return confirm('Вы уверены, что хотите удалить?');"/><i class="fa fa-trash-o"></i> Удалить</a>
                <?php } ; ?>
                </td>
            </tr>
            <?php if($_GET['job']){
    		$job->count_post($rs[$i]['uid']);
    		?>
            <tr>
              <td colspan="10"><div class="job">
                  <table class="table table-bordered table-hover span6">
                    <thead>
                      <tr>
                        <th><i class="fa fa-calendar"></i> Статистика</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Сегодня (<?php echo date('Y-m-d',$job->today['start']) ;?>)已经发布<?php echo $job->today['count'] ;?>篇文章</td>
                      </tr>
                      <tr>
                        <td>Вчера (<?php echo date('Y-m-d',$job->yesterday['start']) ;?>)已经发布<?php echo $job->yesterday['count'] ;?>篇文章</td>
                      </tr>
                      <tr>
                        <td>За прошлый месяц <?php echo $job->pmonth['count'] ;?>篇文章 从 <?php echo date('Y-m-d',$job->pmonth['start']) ;?> 至 <?php echo date('Y-m-d',$job->pmonth['end']) ;?>,平均<?php echo round($job->pmonth['count']/$job->pmonth['t'])?>篇/天</td>
                      </tr>
                      <tr>
                        <td>За этот месяц <?php echo $job->month['count'] ;?>篇文章 从 <?php echo date('Y-m-d',$job->month['start']) ;?> 至 <?php echo date('Y-m-d',$job->month['end']) ;?>,平均<?php echo round($job->month['count']/$job->month['t'])?>篇/天</td>
                      </tr>
                      <tr>
                        <td>Всего опубликовано <?php echo $job->total ;?>篇文章</td>
                      </tr>
                    </tbody>
                  </table>
                  <div class="clearfloat"></div>
                </div></td>
            </tr>
            <?php }  ?>
            <?php }  ?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="10"><div class="pagination pagination-right" style="float:right;"><?php echo iPagination::$pagenav ; ?></div>
                <div class="input-prepend input-append mt20"> <span class="add-on"> Выбрать все
                  <input type="checkbox" class="checkAll checkbox" data-target="#<?php echo APP_BOXID;?>" />
                  </span>
                  <div class="btn-group dropup" id="iCMS-batch"> <a class="btn dropdown-toggle" data-toggle="dropdown" tabindex="-1"><i class="fa fa-wrench"></i> Пакетные операции</a><a class="btn dropdown-toggle" data-toggle="dropdown" tabindex="-1"> <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                      <li><a data-toggle="batch" data-action="dels"><i class="fa fa-trash-o"></i> Удалить</a></li>
                    </ul>
                  </div>
                </div></td>
            </tr>
          </tfoot>
        </table>
      </form>
    </div>
  </div>
</div>
<?php admincp::foot();?>
