<!-- 字段选择 -->
<div class="iFormer-design">
  <div class="widget-title">
    <span class="icon"> <i class="fa fa-cog"></i> </span>
    <h5 class="brs">Поле</h5>
    <ul class="nav nav-tabs" id="fields-tab">
      <li class="active"><a href="#fields-tab-base" data-toggle="tab"><i class="fa fa-info-circle"></i> Простое поле</a></li>
      <li><a href="#fields-tab-func" data-toggle="tab"><i class="fa fa-cog"></i> Функциональное поле</a></li>
      <li><a href="#fields-tab-addons" data-toggle="tab"><i class="fa fa-cog"></i>Дополнительное поле</a></li>
    </ul>
  </div>
  <div id="fields-tab-content" class="tab-content">
    <div id="fields-tab-base" class="tab-pane active">
      <ul>
        <li i="layout" tag="br" type="br" class="br">
          <span class="fa fa-arrows-h"></span>
          <p style="vertical-align: text-top;">Разрыв строки</p>
        </li>
        <li i="field" tag="input" type="hidden" field="VARCHAR" len="255" label="Спойлер">
          <span class="fb-icon fb-icon-input"></span>
          <p>Спойлер</p>
        </li>
        <li i="field" tag="input" type="text" field="VARCHAR" len="255" label="Однострочный текст">
          <span class="fb-icon fb-icon-input"></span>
          <p>Однострочный текст(255)</p>
        </li>
        <li i="field" tag="input" type="text" field="VARCHAR" len="5120" label="Однострочный текст">
          <span class="fb-icon fb-icon-input"></span>
          <p>Однострочный текст(5120)</p>
        </li>
        <li i="field" tag="textarea" type="textarea" field="TEXT" label="Многострочный текст">
          <span class="fb-icon fb-icon-textarea"></span>
          <p>Многострочный текст</p>
        </li>
        <li i="field" tag="input" type="json" field="VARCHAR" len="5120" label="json">
          <span class="fb-icon fb-icon-input"></span>
          <p>json</p>
        </li>
        <li i="field" tag="input" type="text" field="VARCHAR" len="255" label="Email">
          <span class="fb-icon fb-icon-mail"></span>
          <p> Электронная почта </p>
        </li>
        <li i="field" tag="input" type="date" field="INT" len="10" label="Дата">
          <span class="fb-icon fb-icon-date"></span>
          <p>Дата</p>
        </li>
        <li i="field" tag="input" type="datetime" field="INT" len="10" label="Дата и время">
          <span class="timeIcon fb-icon fb-icon-datetime"></span>
          <p>Дата и время</p>
        </li>
        <li i="field" tag="input" type="radio" field="VARCHAR" len="255" label="Переключатели">
          <span class="fb-icon fb-icon-radio"></span>
          <p>Переключатель (radio)</p>
        </li>
        <li i="field" tag="input" type="checkbox" field="VARCHAR" len="255" label="Флажки">
          <span class="fb-icon fb-icon-checkbox"></span>
          <p>Флажки(checkbox)</p>
        </li>
        <li i="field" tag="select" type="select" field="VARCHAR" len="255" label="Выпадающий список">
          <span class="fb-icon fb-icon-dropdown"></span>
          <p>Выпадающий список(select)</p>
        </li>
        <li i="field" tag="select" type="multiple" field="VARCHAR" len="255" label="Множественный выбор">
          <span class="multiselect fb-icon fb-icon-multiselect"></span>
          <p>Множественный выбор(multiselect)</p>
        </li>
        <li i="field" tag="input" type="number" field="TINYINT" len="1" label="Число">
          <span class="fb-icon fb-icon-number"></span>
          <p>Число</p>
        </li>
        <li i="field" tag="input" type="number" field="INT" len="10" label="Большие числа">
          <span class="fb-icon fb-icon-number"></span>
          <p>Большие числа</p>
        </li>
        <li i="field" tag="input" type="number" field="BIGINT" len="20" label="Негабаритные числа">
          <span class="fb-icon fb-icon-number"></span>
          <p>Негабаритные числа</p>
        </li>
        <li i="field" tag="input" type="decimal" field="DECIMAL" len="6,2" label="Десятичное число">
          <span class="fb-icon fb-icon-decimal"></span>
          <p>Десятичное число</p>
        </li>
        <li i="field" tag="input" type="percentage" field="DECIMAL" len="4,2" label="Проценты" label-after="%">
          <span class="fb-icon fb-icon-percentage"></span>
          <p>Проценты</p>
        </li>
        <li i="field" tag="input" type="currency" field="INT" len="10" label="Деньги" label-after="&#8381;">
          <span class="fb-icon fb-icon-currency"></span>
          <p>Деньги</p>
        </li>
        <li i="field" tag="input" type="text" field="VARCHAR" len="255" label="URL адрес">
          <span class="fb-icon fb-icon-url"></span>
          <p>URL адрес</p>
        </li>
        <!--                         <li i="field" fieldtype="32">
          <span class="lookupIcon fb-icon fb-icon-lookup"></span>
          <p class="lookupConent">查找</p>
        </li>
        <li i="field" fieldtype="14">
          <span class="addnotesIcon fb-icon fb-icon-addnotes"></span>
          <p class="addnotestext">添加备注</p>
        </li>
        <li i="field" fieldtype="99">
          <span class="subformIcon fb-icon fb-icon-subform"></span>
          <p class="subformText">子表单</p>
        </li>
        <li i="field" fieldtype="31">
          <span class="autonumberIcon fb-icon fb-icon-autonumber"></span>
          <p class="lookupConent">自动编号</p>
        </li>
        <li i="field" fieldtype="15">
          <span class="formulaIcon fb-icon fb-icon-formula"></span>
          <p class="formulaText">公式</p>
        </li>
        <li i="field" fieldtype="36">
          <span class="signatureIcon fb-icon fb-icon-signature"></span>
          <p class="lookupConent">签名 </p>
        </li>
        <li i="field" fieldtype="30">
          <span class="usersIcon fb-icon fb-icon-name" style="margin-top:1px;"></span>
          <p class="lookupConent">Пользователей</p>
        </li> -->
        <div class="clearfix"></div>
      </ul>
      <div class="clearfix"></div>
    </div>
    <div id="fields-tab-func" class="tab-pane">
      <ul>
        <li i="layout" tag="br" type="br" class="br">
          <span class="fa fa-arrows-h"></span>
          <p style="vertical-align: text-top;">Разрыв строки</p>
        </li>
        <li i="field" tag="tpldir" type="tpldir" field="VARCHAR" len="255" label="каталог шаблонов">
          <span class="fb-icon fb-icon-template"></span>
          <p>Поле выбора - каталог шаблонов</p>
        </li>
        <li i="field" tag="tplfile" type="tplfile" field="VARCHAR" len="255" label="Файл шаблона">
          <span class="fb-icon fb-icon-template"></span>
          <p>Файл шаблона</p>
        </li>
        <li i="field" tag="category" type="category" field="INT" len="10" label="Категория">
          <span class="multiselect fb-icon fb-icon-multiselect"></span>
          <p>Категории</p>
        </li>
        <li i="field" tag="multi_category" type="multi_category" field="VARCHAR" len="255" label="Категории (множественный выбор)">
          <span class="multiselect fb-icon fb-icon-multiselect"></span>
          <p>Категории (множественный выбор)</p>
        </li>
        <li i="field" tag="image" type="image" field="VARCHAR" len="255" label="Изображение">
          <span class="fb-icon fb-icon-image"></span>
          <p> Загрузка изображения </p>
        </li>
        <li i="field" tag="multi_image" type="multi_image" field="TEXT" label="Мультизагрузка изображений">
          <span class="fb-icon fb-icon-image"></span>
          <p>Мультизагрузка изображений</p>
        </li>
        <li i="field" tag="file" type="file" field="VARCHAR" len="255" label="Загрузка файла">
          <span class="fb-icon fb-icon-fileupload"></span>
          <p>Загрузка файла</p>
        </li>
        <li i="field" tag="multi_file" type="multi_file" field="TEXT" label="Мультизагрузка файлов">
          <span class="fb-icon fb-icon-fileupload"></span>
          <p>Мультизагрузка файлов</p>
        </li>
        <li i="field" tag="prop" type="prop" field="VARCHAR" len="255" label="Свойства">
          <span class="fb-icon fb-icon-prop"></span>
          <p>Свойства(Выпадающий список)</p>
        </li>
        <li i="field" tag="multi_prop" type="multi_prop" field="VARCHAR" len="255" label="Свойство (множественный выбор)">
          <span class="fb-icon fb-icon-prop"></span>
          <p>Свойство(множественный выбор)</p>
        </li>
        <li i="field" tag="multi_prop" type="radio_prop" field="VARCHAR" len="200" label="Свойство (чекбокс)">
          <span class="fb-icon fb-icon-prop"></span>
          <p>Свойство(чекбокс)</p>
        </li>
        <li i="field" tag="multi_prop" type="checkbox_prop" field="VARCHAR" len="255" label="Свойство(флажок)">
          <span class="fb-icon fb-icon-prop"></span>
          <p>Свойство(флажок)</p>
        </li>
        <li i="field" tag="tag" type="tag" field="VARCHAR" len="255" label="Теги" ui-class="span6">
          <span class="fb-icon fb-icon-tag"></span>
          <p>Теги</p>
        </li>
        <li i="field" tag="username" type="username" field="VARCHAR" len="255" label="Имя пользователя">
          <span class="fb-icon fb-icon-username"></span>
          <p>Имя пользователя</p>
        </li>
        <li i="field" tag="nickname" type="nickname" field="VARCHAR" len="255" label="Логин пользователя">
          <span class="fb-icon fb-icon-username"></span>
          <p>Логин пользователя</p>
        </li>
        <li i="field" tag="userid" type="userid" field="INT" len="10" label="ID пользователя">
          <span class="fb-icon fb-icon-userid"></span>
          <p>ID пользователя</p>
        </li>
        <li i="field" tag="ip" type="ip:hidden" field="VARCHAR" len="255" label="IP пользователя">
          <span class="fb-icon fb-icon-username"></span>
          <p>IP пользователя</p>
        </li>
        <li i="field" tag="referer" type="referer:hidden" field="VARCHAR" len="255" label="Реферал">
          <span class="fb-icon fb-icon-username"></span>
          <p>Реферал</p>
        </li>

        <li i="field" tag="seccode" type="seccode" len="8" label="Защитный код">
          <span class="fb-icon fb-icon-url"></span>
          <p>Защитный код</p>
        </li>
        <div class="clearfix"></div>
      </ul>
      <div class="clearfix"></div>
    </div>
    <div id="fields-tab-addons" class="tab-pane">
      <?php ?>
      <ul>
        <li i="field" tag="textarea" type="multitext" field="MEDIUMTEXT" label="Большой текст">
          <span class="fb-icon fb-icon-textarea"></span>
          <p>Большой текст</p>
        </li>
        <li i="field" tag="editor" type="editor" field="MEDIUMTEXT" label="Визуальный редактор">
          <span class="fb-icon fb-icon-richtext"></span>
          <p>Визуальный редактор</p>
        </li>
        <li i="field" tag="markdown" type="markdown" field="MEDIUMTEXT" label="Markdown редактор">
          <span class="fb-icon fb-icon-richtext"></span>
          <p>Markdown редактор</p>
        </li>
        <span class="help-inline">* Поля на этой вкладке будет создать отдельную таблицу CDATA</span>
        <div class="clearfix"></div>
      </ul>
      <div class="clearfix"></div>
    </div>
  </div>
  <div class="clearfix"></div>
</div>
