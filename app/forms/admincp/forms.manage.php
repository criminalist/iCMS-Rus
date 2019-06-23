<?php

defined('iPHP') OR exit('Oops, something went wrong');
admincp::head();
?>
<style>
.app_list_desc{font-size: 12px;color: rgb(103, 128, 159);}
.nopadding .tab-content{padding: 0px;}
.solid{border-bottom: 1px solid #ddd;}
</style>
<script type="text/javascript">
$(function(){
  $("#<?php echo APP_FORMID;?>").batch();
  $("#local_app").click(function(event) {
      var local_app_wrap = document.getElementById("local_app_wrap");
      iCMS.dialog({
        title: 'iCMS - Установка из локального пакета',
        content:local_app_wrap
      });
  });
});
</script>
<div class="iCMS-container">
  <div class="widget-box">
    <div class="widget-title"> <span class="icon"> <i class="fa fa-search"></i> </span>
    <h5SEO Заголовок/h5>
    <div class="pull-right">
      <a style="margin: 10px;" class="btn btn-success btn-mini" href="<?php echo APP_FURI; ?>&do=cache" target="iPHP_FRAME"><i class="fa fa-refresh"></i>Обновить кэш</a>
    </div>
  </div>
  <div class="widget-content">
      <div class="pull-right">
        <button class="btn btn-primary" type="button" id="local_app"><i class="fa fa-send"></i> Установка из локального пакета</button>
      </div>
      <form action="<?php echo iPHP_SELF ; ?>" method="get" class="form-inline">
      <input type="hidden" name="app" value="<?php echo admincp::$APP_NAME;?>" />
      <div class="input-prepend input-append">
        <span class="add-on">На страницу</span>
        <input type="text" name="perpage" id="perpage" value="<?php echo $maxperpage ; ?>" style="width:36px;"/>
        <span class="add-on">записей</span> </div>
        <div class="input-prepend input-append">
          <span class="add-on">Ключевое слово</span>
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
  </div>
  <div class="widget-content nopadding">
    <form action="<?php echo APP_FURI; ?>&do=batch" method="post" class="form-inline" id="<?php echo APP_FORMID;?>" target="iPHP_FRAME">
      <div class="tab-content">
        <div id="apps-type" class="tab-pane active">
          <table class="table table-bordered table-condensed table-hover">
            <thead>
              <tr>
                <th style="width:40px;">ID</th>
                <th>标识/名称</th>
                <th>Таблицы в базе</th>
                <th>Операции</th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach ((array)$rs as $key => $data) {
                $table  = apps::table_item($data['table']);
                $config = json_decode($data['config'],true);
                $data['url'] = iURL::router(array('forms:id',$data['id']));
              ?>
              <tr id="id<?php echo $data['id'] ; ?>">
                <td><b><?php echo $data['id'] ; ?></b></td>
                <td>
                  <b><?php echo forms::short_app($data['app']) ; ?></b>/<?php echo $data['name'] ; ?>
                  <p class="app_list_desc"><?php echo $config['info'] ; ?></p>
                  <?php if($config['iFormer']){ ?>
                    <span class="label label-info">Настраиваемые</span>
                  <?php }?>
                    <div class="solid clearfix mt5"></div>
                    <a href="<?php echo $data['url'] ; ?>" class="btn btn-small" target="_blank"><i class="fa fa-dashboard"></i>Перейти к форме</a>
                    <a href="<?php echo APP_URI; ?>&do=data&fid=<?php echo $data['id'] ; ?>" class="btn btn-small" target="_blank"><i class="fa fa-dashboard"></i> Данные</a>
                    <a href="<?php echo APP_URI; ?>&do=submit&fid=<?php echo $data['id'] ; ?>" class="btn btn-small" target="_blank"><i class="fa fa-edit"></i>Добавить</a>
                </td>
                <td>
                  <?php if(is_array($table)){ ?>
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <td>Название таблицы</td>
                        <td>Первичный ключ</td>
                        <td>关联</td>
                        <td> Имя </td>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      foreach ((array)$table as $tkey => $tval) {
                      ?>
                      <tr>
                        <td><?php echo $tval['name'] ; ?></td>
                        <td><?php echo $tval['primary'] ; ?></td>
                        <td><?php echo $tval['union'] ; ?></td>
                        <td><?php echo $tval['label'] ; ?></td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                  <?php }else{
                    echo '<span class="label">Нет привязки к таблицам</span>';
                  }
                  ?>
                </td>
                  <td>
                    <a href="<?php echo APP_URI; ?>&do=create&id=<?php echo $data['id'] ; ?>" class="btn btn-small"><i class="fa fa-edit"></i> Изменить </a>
                    <a href="<?php echo APP_URI; ?>&do=pack&id=<?php echo $data['id'] ; ?>" class="btn btn-small"><i class="fa fa-download"></i> Пакет </a>
                    <div class="clearfix mt5"></div>
                    <?php if($data['status']){?>
                    <a href="<?php echo APP_URI; ?>&do=update&_args=status:0&id=<?php echo $data['id'] ; ?>" target="iPHP_FRAME" class="btn btn-small btn-warning" onclick="return confirm('Закрытие формы не удаляет данные и саму форму, но форма не будет доступна \n Вы уверены, что хотите закрыть форму?');"><i class="fa fa-close"></i> Закрыть </a>
                    <?php }else{?>
                    <a href="<?php echo APP_URI; ?>&do=update&_args=status:1&id=<?php echo $data['id'] ; ?>" target="iPHP_FRAME" class="btn btn-small btn-success"><i class="fa fa-check"></i> Активировать</a>
                    <?php }?>
                    <a href="<?php echo APP_FURI; ?>&do=del&id=<?php echo $data['id'] ; ?>" target="iPHP_FRAME" class="del btn btn-small btn-danger" title='Удалить навсегда'  onclick="return confirm('При удалении формы все поля в базу и данные которые заполнены через форму будут также удалены\nВы действительно хотите уадлить форму?');"/><i class="fa fa-trash-o"></i> Удалить</a>
                </tr>
                <?php }  ?>
              </tbody>
              <tr>
                <td colspan="5">
                  <div class="pagination pagination-right" style="float:right;"><?php echo iPagination::$pagenav ; ?></div>
                  <div class="input-prepend input-append mt20">
                    <span class="add-on"> Выбрать все
                      <input type="checkbox" class="checkAll checkbox" data-target="#<?php echo APP_BOXID;?>" />
                    </span>
                    <div class="btn-group dropup" id="iCMS-batch">
                      <a class="btn dropdown-toggle" data-toggle="dropdown" tabindex="-1"><i class="fa fa-wrench"></i> Пакетные операции</a>
                      <a class="btn dropdown-toggle" data-toggle="dropdown" tabindex="-1"> <span class="caret"></span></a>
                      <ul class="dropdown-menu">
                        <li><a data-toggle="batch" data-action="dels"><i class="fa fa-trash-o"></i> Удалить</a></li>
                      </ul>
                    </div>
                  </div>
                </td>
              </tr>
            </table>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<div id="local_app_wrap" style="display:none;">
  <form action="<?php echo APP_FURI; ?>&do=local_forms" method="post" enctype="multipart/form-data" target="iPHP_FRAME">
    <div class="alert alert-info">
      Загрузите файл пакета формы в формате (.zip) в корень сайта.<br />
    </div>
    <div class="clearfloat mb10"></div>
    <div class="input-prepend">
      <span class="add-on">Список доступных пакетов для установки</span>
      <select name="zipfile" class="chosen-select span4" data-placeholder="Выбрать файл установочного пакета формы (.zip)...">
        <?php foreach(glob(iPATH."iCMS.FORMS.*_*.zip") as $value){ ?>
        <option value="<?php echo $value;?>"><?php echo str_replace(iPATH, '', $value);?></option>
        <?php } ?>
      </select>
    </div>
    <div class="clearfloat mb10"></div>
    <button class="btn btn-primary btn-large btn-block" type="submit"><i class="fa fa-check"></i> Установка </button>
    <div class="clearfloat mb10"></div>
  </form>
</div>
<?php admincp::foot();?>
