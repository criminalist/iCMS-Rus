<?php

defined('iPHP') OR exit('Oops, something went wrong');
admincp::head();
?>
<script type="text/javascript">
$(function(){
  iCMS.select('sfield',"<?php echo $_GET['sfield']; ?>");
  iCMS.select('pattern',"<?php echo $_GET['pattern']; ?>");
  $("#<?php echo APP_FORMID;?>").batch();
});
</script>
<div class="iCMS-container">
  <div class="widget-box">
    <div class="widget-title">
      <span class="icon"> <i class="fa fa-search"></i> </span>
      <h5 class="brs"SEO Заголовок/h5>
      <div class="input-prepend" style="margin-top: 3px; margin-left: 5px;">
        <span class="add-on"> Форма </span>
        <select name="sssfid" id="sssfid" class="chosen-select span4"
        onchange="window.location.href='<?php echo APP_DOURI; ?>&fid='+this.value"
        data-placeholder="== Выберите форму ==">
          <?php echo $this->select();?>
        </select>
        <script>
        $(function(){
         iCMS.select('sssfid',"<?php echo (int)$_GET['fid'] ; ?>");
        })
        </script>
      </div>
    </div>
    <div class="widget-content">
      <form action="<?php echo iPHP_SELF ; ?>" method="get" class="form-inline">
        <input type="hidden" name="app" value="<?php echo admincp::$APP_NAME;?>" />
        <input type="hidden" name="do" value="data" />
        <input type="hidden" name="fid" value="<?php echo $this->fid;?>" />
        <div class="input-prepend input-append">
          <span class="add-on">На страницу</span>
          <input type="text" name="perpage" id="perpage" value="<?php echo $maxperpage ; ?>" style="width:36px;"/>
          <span class="add-on">записей</span>
        </div>
        <div class="input-prepend"> <span class="add-on">Поиск поля</span>
          <select name="sfield" id="sfield" class="span3 chosen-select">
            <option value="">Все поля</option>
            <?php foreach ((array)$fields as $fi => $field) {?>
            <option value="<?php echo $field['id']; ?>"><?php echo $field['label']; ?>[<?php echo $field['field']; ?>]</option>
            <?php } ?>
          </select>
        </div>
        <div class="input-prepend">
          <span class="add-on">Искать по </span>
          <select name="pattern" id="pattern" class="chosen-select" style="width:120px;">
            <option></option>
            <option value="=">Ровно =</option>
            <option value="!=">Не равно !=</option>
            <option value=">">Больше ></option>
            <option value="<">Меньше <</option>
            <option value="like">like</option>
          </select>
        </div>
        <div class="input-prepend input-append">
          <span class="add-on">Ключевое слово</span>
          <input type="text" name="keywords" class="span2" id="keywords" value="<?php echo $_GET['keywords'] ; ?>" />
          <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i>поиск</button>
        </div>
        <span class="help-inline">По умолчанию ищутся поля только с типом varchar</span>
      </form>
    </div>
  </div>
  <div class="widget-box" id="<?php echo APP_BOXID;?>">
    <div class="widget-title">
      <span class="icon">
        <input type="checkbox" class="checkAll" data-target="#<?php echo APP_BOXID;?>" />
      </span>
      <h5> Список </h5>
    </div>
    <div class="widget-content nopadding">
      <form action="<?php echo APP_FURI; ?>&do=batch" method="post" class="form-inline" id="<?php echo APP_FORMID;?>" target="iPHP_FRAME">
        <input type="hidden" name="fid" value="<?php echo $this->fid;?>" />
        <table class="table table-bordered table-condensed table-hover">
          <thead>
            <tr>
              <th><i class="fa fa-arrows-v"></i></th>
              <th class="span1"><?php echo strtoupper($primary); ?></th>
              <th>Содержание формы</th>
            </tr>
          </thead>
          <tbody>
          <?php
            foreach ((array)$rs as $key => $value) {
              $id = $value[$primary];
              if($b[$id] && is_array($b[$id])){
                $value+=$b[$id];
              }
          ?>
            <tr id="id<?php echo $id; ?>">
              <td><input type="checkbox" name="id[]" value="<?php echo $id ; ?>" /></td>
              <td><?php echo $id ; ?></td>
              <td>
                <table class="table table-bordered">
                  <tbody>
                    <?php foreach ($fields as $fi => $field) {?>
                    <tr>
                      <td class="span3"><?php echo $field['label'] ; ?></td>
                      <td>
                        <?php
                          $vars = former::field_output($value[$field['id']],$field);
                          // is_array($vars) && $vars = implode(',', $vars);
                          print_r($vars);
                        ?>
                      </td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
                <div class="clearfloat mb5"></div>
                <a href="<?php echo APP_URI; ?>&do=submit&fid=<?php echo $this->fid ; ?>&id=<?php echo $id ; ?>" class="btn btn-small"><i class="fa fa-edit"></i> Редактировать </a>
                <a href="<?php echo APP_FURI; ?>&do=delete&fid=<?php echo $this->fid ; ?>&id=<?php echo $id ; ?>" target="iPHP_FRAME" class="del btn btn-small btn-danger" title='Удалить навсегда'  onclick="return confirm('Вы уверены, что хотите удалить?');"/><i class="fa fa-trash-o"></i> Удалить</a>
              </td>
            </tr>
            <?php }  ?>
          </tbody>
          <tr>
            <td colspan="<?php echo count($list_fields)+3;?>">
              <div class="pagination pagination-right" style="float:right;"><?php echo iPagination::$pagenav ; ?></div>
              <div class="input-prepend input-append mt20">
                <span class="add-on"> Выбрать все
                  <input type="checkbox" class="checkAll checkbox" data-target="#<?php echo APP_BOXID;?>" />
                </span>
                <div class="btn-group dropup" id="iCMS-batch">
                  <a class="btn dropdown-toggle" data-toggle="dropdown" tabindex="-1"><i class="fa fa-wrench"></i> Пакетные операции</a>
                  <a class="btn dropdown-toggle" data-toggle="dropdown" tabindex="-1">
                    <span class="caret"></span>
                  </a>
                  <ul class="dropdown-menu">
                    <li class="divider"></li>
                    <li><a data-toggle="batch" data-action="data-dels"><i class="fa fa-trash-o"></i> Удалить</a></li>
                  </ul>
                </div>
              </div>
            </td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php admincp::foot();?>
