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
  <?php if(isset($_GET['status'])){  ?>
  iCMS.select('status',"<?php echo $_GET['status'] ; ?>");
  <?php } ?>
  <?php if($_GET['orderby']){ ?>
  iCMS.select('orderby',"<?php echo $_GET['orderby'] ; ?>");
  <?php } ?>
  <?php if(isset($_GET['pid']) && $_GET['pid']!='-1'){  ?>
  iCMS.select('pid',"<?php echo (int)$_GET['pid'] ; ?>");
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
        <div class="input-prepend"> <span class="add-on">Состояние аккаунта</span>
          <select name="status" id="status" class="span2 chosen-select">
            <option value="">Неизвестно</option>
            <option value="0">Отключен</option>
            <option value="1">Нормальный</option>
            <option value="2">Черный список</option>
            <option value="3">Заблокирован</option>
            <?php echo propAdmincp::get("status") ; ?>
          </select>
        </div>
        <div class="input-prepend"> <span class="add-on">IP регистрации</span>
          <input type="text" name="regip" id="regip" class="span2" value="<?php echo $_GET['regip'] ; ?>"/>
        </div>
        <div class="input-prepend"> <span class="add-on">IP последней авторизации на сайте</span>
          <input type="text" name="loginip" id="loginip" class="span2" value="<?php echo $_GET['loginip'] ; ?>"/>
        </div>
        <div class="clearfix mt10"></div>
        <div class="input-prepend"> <span class="add-on">Свойства пользователя</span>
          <select name="pid" id="pid" class="span2 chosen-select">
            <option value="-1">Все пользователи</option>
            <option value="0">Обычный пользователь [pid='0']</option>
            <?php echo propAdmincp::get("pid") ; ?>
          </select>
        </div>
        <div class="input-prepend">
          <span class="add-on">Сортировка</span>
          <select name="orderby" id="orderby" class="span2 chosen-select">
            <option value=""></option>
            <optgroup label="По убыванию"><?php echo $orderby_option['DESC'];?></optgroup>
            <optgroup label="По возрастанию"><?php echo $orderby_option['ASC'];?></optgroup>
          </select>
        </div>
        <div class="input-prepend input-append"> <span class="add-on">На страницу</span>
          <input type="text" name="perpage" id="perpage" value="<?php echo $maxperpage ; ?>" style="width:36px;"/>
          <span class="add-on">записей</span> </div>
        <div class="input-prepend input-append"> <span class="add-on">Ключевое слово</span>
          <input type="text" name="keywords" class="span2" id="keywords" value="<?php echo $_GET['keywords'] ; ?>" />
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
              <th style="width:130px;"><a class="fa fa-clock-o tip-top" title="Дата и время регистрации/Время последней авторизации"></a></th>
              <th>Операции</th>
            </tr>
          </thead>
          <tbody>
            <?php for($i=0;$i<$_count;$i++){
               $url = iURL::router(array('uid:home',$rs[$i]['uid']));
            ?>
            <tr id="id<?php echo $rs[$i]['uid'] ; ?>">
              <td><?php if($rs[$i]['uid']!="1"){ ; ?><input type="checkbox" name="id[]" value="<?php echo $rs[$i]['uid'] ; ?>" /><?php } ; ?></td>
              <td><a href="<?php echo $url; ?>" target="_blank"><?php echo $rs[$i]['uid'] ; ?></a></td>
              <td><a class="tip-top" title="
                Понравилось:<?php echo $rs[$i]['fans'] ; ?><br />
                Подписщики:<?php echo $rs[$i]['follow'] ; ?><br />
                Комментариев:<?php echo $rs[$i]['comments'] ; ?><br />
                Новостей:<?php echo $rs[$i]['article'] ; ?><br />
                Просмотров:<?php echo $rs[$i]['hits'] ; ?><br />
                За неделю:<?php echo $rs[$i]['hits_week'] ; ?><br />
                Просмотры за месяц:<?php echo $rs[$i]['hits_month'] ; ?><br />
                "><?php echo $rs[$i]['username'] ; ?></a></td>
              <td><?php echo $rs[$i]['nickname'] ; ?>
                <?php if($rs[$i]['status']=="2"){
                  echo '<span class="label label-inverse">В черном списке</span>';
                }else if($rs[$i]['status']=="0"){
                  echo '<span class="label">Аккаунт заблокирован</span>';
                } ?>
              </td>
              <td>
                <a href="<?php echo APP_URI; ?>&gid=<?php echo $rs[$i]['gid'] ; ?>"><?php echo $this->groupAdmincp->array[$rs[$i]['gid']]['name'] ; ?></a>
                <br />
                <?php $rs[$i]['pid'] && propAdmincp::flag($rs[$i]['pid'],$propArray,APP_DOURI.'&pid={PID}&'.$uri);?>
              </td>
              <td><?php echo $rs[$i]['lastloginip'] ; ?></td>
              <td>
                <?php if($rs[$i]['regdate']) echo get_date($rs[$i]['regdate'],"Y-m-d H:i:s") ; ?><br />
                <?php if($rs[$i]['lastlogintime']) echo get_date($rs[$i]['lastlogintime'],"Y-m-d") ; ?></td>
              <td>
                <?php if(members::is_superadmin()){?>
                <a href="<?php echo APP_URI; ?>&do=login&id=<?php echo $rs[$i]['uid'] ; ?>" class="btn btn-small" target="_blank">Авторизация от имени пользователя</a>
                 <?php } ?>
                <a href="<?php echo __ADMINCP__; ?>=article&do=user&userid=<?php echo $rs[$i]['uid'] ; ?>&pt=0" class="btn btn-small"><i class="fa fa-list-alt"></i> Посты</a>
                <a href="<?php echo APP_URI; ?>&do=add&id=<?php echo $rs[$i]['uid'] ; ?>" class="btn btn-small"><i class="fa fa-edit"></i> Изменить </a>
                <a href="<?php echo APP_FURI; ?>&do=del&id=<?php echo $rs[$i]['uid'] ; ?>" target="iPHP_FRAME" class="del btn btn-small" title='Удалить навсегда'  onclick="return confirm('Вы уверены, что хотите удалить?');"/><i class="fa fa-trash-o"></i> Удалить</a>
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
                  <div class="btn-group dropup" id="iCMS-batch"> <a class="btn dropdown-toggle" data-toggle="dropdown" tabindex="-1"><i class="fa fa-wrench"></i>Пакетная операция</a><a class="btn dropdown-toggle" data-toggle="dropdown" tabindex="-1"> <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                      <li><a data-toggle="batch" data-action="prop"><i class="fa fa-puzzle-piece"></i> Установить пользовательские свойства</a></li>
                      <li class="divider"></li>
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
