<?php

defined('iPHP') OR exit('Oops, something went wrong');
admincp::head();
?>
<script type="text/javascript">
$(function(){
	iCMS.select('gid',"<?php echo $rs->gid ; ?>");
	iCMS.select('info_gender',"<?php echo $rs->gender ; ?>");
	iCMS.select('info_year',"<?php echo $rs->info['year'] ; ?>");
	iCMS.select('info_month',"<?php echo $rs->info['month'] ; ?>");
	iCMS.select('info_day',"<?php echo $rs->info['day'] ; ?>");
});
</script>

<div class="iCMS-container">
  <div class="widget-box">
    <div class="widget-title"> <span class="icon"> <i class="fa fa-user"></i> </span>
      <h5 class="brs">
        <?php if(admincp::$APP_DO=='profile'){ ?>
       Персональная информация
        <?php }else{?>
        <?php echo empty($this->uid)?'Добавить':'Редактировать' ; ?> администратора
        <?php }?>
      </h5>
      <ul class="nav nav-tabs" id="members-tab">
        <li class="active"><a href="#members-info" data-toggle="tab"><b>Основная информация</b></a></li>
        <?php if(members::is_superadmin()){ ?>
        <li><a href="#members-mpriv" data-toggle="tab"><b>Права доступа в панели управления</b></a></li>
        <li><a href="#members-apriv" data-toggle="tab"><b>Права доступа к приложениям</b></a></li>
        <li><a href="#members-cpriv" data-toggle="tab"><b>Права доступа к категориям</b></a></li>
        <?php }?>
      </ul>
    </div>
    <div class="widget-content nopadding">
      <form action="<?php echo APP_FURI; ?>&do=save" method="post" class="form-inline" id="iCMS-members" target="iPHP_FRAME">
        <input name="uid" type="hidden" value="<?php echo $this->uid; ?>" />
        <input name="type" type="hidden" value="<?php echo $this->type; ?>" />
        <div id="members-add" class="tab-content">
          <div id="members-info" class="tab-pane active">
            <?php if(members::is_superadmin()){ ?>
            <div class="input-prepend"> <span class="add-on"> Роль </span>
              <select name="gid" id="gid" class="chosen-select" data-placeholder="Выберите группу">
                <?php echo $this->groupAdmincp->select(); ?>
              </select>
            </div>
            <?php }?>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"> <span class="add-on">Имя пользователя</span>
              <input type="text" name="uname" class="span3" id="uname" value="<?php echo $rs->username ; ?>"/>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend input-append"> <span class="add-on">Пароль</span>
              <input type="text" name="pwd" class="span3" id="pwd" value=""/>
              <a class="btn" data-toggle="createpass" data-target="#pwd">Сгенерировать пароль</a>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"> <span class="add-on">Логин</span>
              <input type="text" name="nickname" class="span3" id="nickname" value="<?php echo $rs->nickname ; ?>"/>
            </div>
            <hr />
            <div class="input-prepend"> <span class="add-on">Настоящее имя</span>
              <input type="text" name="realname" class="span3" id="realname" value="<?php echo $rs->realname ; ?>"/>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"> <span class="add-on">Пол</span>
              <select name="info[gender]" id="info_gender" class="chosen-select">
                <option value="2">保密</option>
                <option value="1">Мужской</option>
                <option value="0">Женский</option>
              </select>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"> <span class="add-on">Q Q</span>
              <input type="text" name="info[QQ]" id="info_QQ" class="span3" value="<?php echo $rs->info['QQ'] ; ?>"  maxlength="12"/>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"> <span class="add-on">博客</span>
              <input type="text" name="info[blog]" id="info_blog" class="span3" value="<?php echo $rs->info['blog'] ; ?>" />
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend input-append"> <span class="add-on">生日</span>
              <select name="info[year]" id="info_year" class="chosen-select"  style="width:90px;" data-placeholder="年">
                <option value=""></option>
                <?php
                $year = (int)date('Y');$syear =$year-35;$eyear =$year-14;
                for ($i=$syear; $i < $eyear; $i++) {?>
                <option value="<?php echo $i ?>"><?php echo $i ?></option>
                <?php } ?>
              </select>
              <select name="info[month]" id="info_month" class="span1 chosen-select" data-placeholder="月">
                <option value=""></option>
                <option value="1">01</option>
                <option value="2">02</option>
                <option value="3">03</option>
                <option value="4">04</option>
                <option value="5">05</option>
                <option value="6">06</option>
                <option value="7">07</option>
                <option value="8">08</option>
                <option value="9">09</option>
                <option value="10">10</option>
                <option value="11">11</option>
                <option value="12">12</option>
              </select>
              <select name="info[day]" id="info_day" class="span1 chosen-select" data-placeholder="日">
                <option value=""></option>
                <option value="1">01</option>
                <option value="2">02</option>
                <option value="3">03</option>
                <option value="4">04</option>
                <option value="5">05</option>
                <option value="6">06</option>
                <option value="7">07</option>
                <option value="8">08</option>
                <option value="9">09</option>
                <option value="10">10</option>
                <option value="11">11</option>
                <option value="12">12</option>
                <option value="13">13</option>
                <option value="14">14</option>
                <option value="15">15</option>
                <option value="16">16</option>
                <option value="17">17</option>
                <option value="18">18</option>
                <option value="19">19</option>
                <option value="20">20</option>
                <option value="21">21</option>
                <option value="22">22</option>
                <option value="23">23</option>
                <option value="24">24</option>
                <option value="25">25</option>
                <option value="26">26</option>
                <option value="27">27</option>
                <option value="28">28</option>
                <option value="29">29</option>
                <option value="30">30</option>
                <option value="31">31</option>
              </select>
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"> <span class="add-on">来自</span>
              <input type="text" name="info[from]" class="span3" value="<?php echo $rs->info['from'] ; ?>" />
            </div>
            <div class="clearfloat mb10"></div>
            <div class="input-prepend"> <span class="add-on">签名</span>
              <textarea name="info[sign]" cols="45" rows="5" class="span3"><?php echo $rs->info['sign'] ; ?></textarea>
            </div>
            <div class="clearfloat mb10"></div>
          </div>
          <?php if(members::is_superadmin()){ ?>
          <?php include admincp::view("members.priv"); ?>
          <?php }?>
        </div>
        <div class="form-actions">
          <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i> Сохранить</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php admincp::foot();?>
