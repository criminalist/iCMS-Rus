<!--Редактор полей -->
<div id="iFormer-field-editor" class="hide" style="width:500px;text-align: left;">
  <form id="iFormer-field-form">
    <input type="hidden" name="id" id="iFormer-id"/>
    <input type="hidden" name="multiple" id="iFormer-multiple"/>
    <div class="input-prepend">
      <span class="add-on">Имя поля</span>
      <input type="text" name="label" class="span3" id="iFormer-label" value=""/>
    </div>
    <span class="help-inline">*Обязательно</span>
    <div class="input-prepend">
      <span class="add-on">Имя поля</span>
      <input type="text" name="name" class="span3" id="iFormer-name" value=""/>
    </div>
    <span class="help-inline">*Обязательно</span>
    <div class="clearfix"></div>
    <div class="input-prepend">
      <span class="add-on">Тип поля</span>
      <select name="field" id="iFormer-field" class="chosen-select" style="width:230px;" data-placeholder="Выберите ...">
        <optgroup label="Текстовые данные">
          <option value='VARCHAR'>Однострочное текстовое поле (VARCHAR)</option>
          <option value='TEXT'>Текстовое поле (TEXT)</option>
          <option value='MEDIUMTEXT'>Текстовое поле (MEDIUMTEXT)</option>
          <option value='LONGTEXT'>Текстовое поле (LONGTEXT)</option>
        </optgroup>
        <optgroup label="Целое">
          <option value='TINYINT'>小整数(TINYINT)</option>
          <option value='SMALLINT'>大整数(SMALLINT)</option>
          <option value='MEDIUMINT'>大整数(MEDIUMINT)</option>
          <option value='INT'>大整数(INT)</option>
          <option value='BIGINT'>极大整数(BIGINT)</option>
        </optgroup>
        <optgroup label="Число с плавающей запятой">
          <option value='DECIMAL'>Малое (DECIMAL)</option>
          <option value='FLOAT'>Точность(FLOAT)</option>
          <option value='DOUBLE'>Двойная точность(DOUBLE)</option>
        </optgroup>
      </select>
    </div>
    <span class="help-inline">* Обязательно (после сохранения изменить невозможно)</span>
    <div class="clearfix"></div>
    <div class="unsigned-wrap hide">
      <div class="input-prepend input-append">
        <span class="add-on">Целое</span>
        <span class="add-on">Unsigned
          <input type="radio" name="unsigned" class="uniform" id="iFormer-unsigned-1" value="1"/>
        </span>
        <span class="add-on">Signed
          <input type="radio" name="unsigned" class="uniform" id="iFormer-unsigned-0" value="0"/>
        </span>
      </div>
      <span class="help-inline">*Обязательно</span>
      <div class="clearfix"></div>
    </div>
    <div class="input-prepend">
      <span class="add-on">Длина поля</span>
      <input type="text" name="len" class="span3" id="iFormer-len" value=""/>
    </div>
    <span class="help-inline">*Обязательно</span>
    <div class="clearfix"></div>
    <div class="input-prepend">
      <span class="add-on">Значение по умолчанию</span>
      <input type="text" name="default" class="span3" id="iFormer-default" value=""/>
    </div>
    <span class="help-inline">Необязательные</span>
    <div class="input-prepend">
      <span class="add-on">Комментарий к полю</span>
      <input type="text" name="comment" class="span3" id="iFormer-comment" value=""/>
    </div>
    <span class="help-inline"></span>
    <div class="input-prepend">
      <span class="add-on">Тип данных</span>
      <input type="text" name="type" class="span3" id="iFormer-type" value=""/>
    </div>
    <span class="help-inline">*Обязательно (после сохранения изменить невозможно)</span>
    <div id="iFormer-option-wrap" class="hide">
      <div class="input-prepend">
        <span class="add-on">Список опций</span>
        <textarea type="text" name="option" class="span3" id="iFormer-option" disabled/></textarea>
      </div>
      <span class="help-inline">*Обязательно Формат: опция = значение;<br />
          Компьютер=pc;<br />
          Мобильный телефон=phone;<br />
          iPad;
      </span>
      <div class="clearfix"></div>
    </div>
    <hr style="margin: 10px 0px;" />
    <div class="field-tab-box">
      <ul class="nav nav-tabs" id="field-tab">
        <li class="active"><a href="#field-tab-0" data-toggle="tab"><i class="fa fa-dashboard"></i>UI</a></li>
        <li><a href="#field-tab-1" data-toggle="tab"><i class="fa fa-check-square-o"></i> Валидация данных</a></li>
        <!-- <li><a href="#field-tab-2" data-toggle="tab"><i class="fa fa-cog"></i> Обработка данных</a></li> -->
        <li><a href="#field-tab-3" data-toggle="tab"><i class="fa fa-info-circle"></i> Подсказка</a></li>
        <li><a href="#field-tab-5" data-toggle="tab"><i class="fa fa-cog"></i> Оптимизация</a></li>
        <li><a href="#field-tab-6" data-toggle="tab"><i class="fa fa-code"></i> Код</a></li>
      </ul>
      <div class="tab-content">
        <div id="field-tab-0" class="tab-pane active">
          <div class="input-prepend">
            <span class="add-on">Описание поля</span>
            <input type="text" name="help" class="span3" id="iFormer-help" value=""/>
          </div>
          <span class="help-inline">Необязательный</span>
          <div class="input-prepend">
            <span class="add-on">Оформления поля(CSS)</span>
            <input type="text" name="class" class="span3" id="iFormer-class" value=""/>
          </div>
          <span class="help-inline">Необязательные</span>
          <div class="clearfix"></div>
          <div id="iFormer-label-after-wrap" class="hide">
            <div class="input-prepend">
              <span class="add-on">扩展信息</span>
              <input type="text" name="label-after" class="span3" id="iFormer-label-after" value=""/>
            </div>
            <span class="help-inline">Необязательные</span>
            <div class="clearfix"></div>
          </div>
          <div class="clearfix"></div>
          <div class="input-prepend">
            <span class="add-on">Параметры UI интерфейса</span>
            <select id="iFormer-ui" class="chosen-select" style="width:360px;" data-placeholder="Выберите ..." multiple="multiple">
              <optgroup label="管理员">
                <option value='admincp-list'>列表显示</option>
              </optgroup>
              <optgroup label="Пользователь">
                <option value='usercp-list'>列表显示</option>
                <option value='usercp-input'>可填写</option>
              </optgroup>
            </select>
            <select multiple="multiple" class="hide" name="ui[]" id="sort-ui"></select>
          </div>
          <span class="help-inline">Необязательные</span>
        </div>
        <div id="field-tab-1" class="tab-pane">
          <span class="help-inline">Вы можете выбрать несколько методов проверки данных</span>
          <div class="clearfix mt5"></div>
          <div class="input-prepend">
            <span class="add-on">Валидация вводимых данных</span>
            <select id="iFormer-validate" class="chosen-select" style="width:360px;" data-placeholder="Выберите методы проверки данных" multiple="multiple">
              <option value='empty'>Обязателен к заполнению</option>
              <option value='number'>Только цифры</option>
              <option value='russian'>Можно вводить только русские буквы</option>
              <option value='character'>Только буквы</option>
              <option value='minmax'>Диапазон значений</option>
              <option value='count'>Кол-во символов</option>
              <option value='email'>E-Mail</option>
              <option value='url'>URL</option>
              <option value='mobphone'>Мобильный номер</option>
              <option value='telphone'>Стационарный телефон</option>
              <option value='phone'>Телефон / мобильный телефон</option>
              <option value='idcard'>Номер удостоверения личности</option>
              <option value='zipcode'>Почтовый индекс</option>
              <option value='defined'>Пользовательские регулярные выражения</option>
            </select>
            <select multiple="multiple" class="hide" name="validate[]" id="sort-validate"></select>
          </div>
          <span class="help-inline">Необязательные</span>
          <div class="clearfix"></div>
          <div id="iFormer-validate-minmax" class="hide">
            <div class="input-prepend input-append">
              <span class="add-on">Диапазон значений</span>
              <span class="add-on">Минимум</span>
              <input type="text" name="minmax[0]" class="span1" id="iFormer-minmax_0" value=""/>
              <span class="add-on">-</span>
              <input type="text" name="minmax[1]" class="span1" id="iFormer-minmax_1" value=""/>
              <span class="add-on">Максимум</span>
            </div>
            <div class="clearfix mt5"></div>
          </div>
          <div id="iFormer-validate-count" class="hide">
            <div class="input-prepend input-append">
              <span class="add-on">Кол-во символов</span>
              <span class="add-on">Минимальное количество</span>
              <input type="text" name="count[0]" class="span1" id="iFormer-count_0" value=""/>
              <span class="add-on">-</span>
              <input type="text" name="count[1]" class="span1" id="iFormer-count_1" value=""/>
              <span class="add-on">Максимальное количество</span>
            </div>
            <div class="clearfix mt5"></div>
          </div>
          <div id="iFormer-validate-defined" class="hide">
            <div class="input-prepend">
              <span class="add-on">Код</span>
              <textarea name="defined" id="iFormer-defined" class="span6" style="height:60px;"></textarea>
            </div>
            <span class="help-inline">
              可以自己填写提交时数据验证代码(javascript) <br />
              注:该代码将会包含在表单的submit事件里<br />
              <code>
                $(表单ID).submit(function(){
                  .....
                  验证代码
                  .....
                })
              </code>
            </span>
          </div>
        </div>
        <div id="field-tab-2" class="tab-pane">
          <span class="help-inline">При сохранении данных или при выполнении вы можете выбрать несколько исполнений по порядку.</span>
          <div class="clearfix mt5"></div>
          <div class="input-prepend">
            <span class="add-on">Обработка данных</span>
            <select id="iFormer-func" class="chosen-select" style="width:360px;" data-placeholder="Выберите метод обработки данных ..." multiple="multiple">
              <optgroup label="При сохранении данных">
                <option value='input:repeat'>Проверка на уникальность (дубликат)</option>
                <option value='input:pinyin'>Транслитерация</option>
                <option value='input:cleanhtml'>Очистить HTML теги</option>
                <option value='input:formathtml'>Форматирование HTML</option>
                <option value='input:strtolower'>Только строчные буквы</option>
                <option value='input:strtoupper'>Только прописные буквы</option>
                <option value='input:firstword'>Первая буква заглавная</option>
                <option value='input:md5'>md5</option>
              </optgroup>
              <optgroup label="Общего назначения">
                <option value='rand'>Генерировать случайные числа</option>
              </optgroup>
              <optgroup label="互转">
                <option value='implode' data-args="" data-title="Категорияитель символов">Массив в строку</option>
                <option value='explode'>Массив в строку</option>
                <option value='json_encode'>Кодирование в формат JSON</option>
                <option value='json_decode'>Декодирования JSON</option>
                <option value='serialize'>Сериализация объектов (serialize)</option>
                <option value='unserialize'>Сериализация объектов (unserialize)</option>
                <option value='base64_encode'>Кодирование base64</option>
                <option value='base64_decode'>Декодирования base64</option>
                <option value='rawurlencode'>URL-кодирование</option>
                <option value='rawurldecode'>URL-Декодирования</option>
              </optgroup>
              <optgroup label="展示时">
                <option value='output:md5'>md5</option>
                <option value='output:redirect'>URL редирект</option>
              </optgroup>
            </select>
            <select multiple="multiple" class="hide" name="func[]" id="sort-func"></select>
          </div>
          <span class="help-inline">Необязательные</span>
          <div class="input-prepend hide">
            <span class="add-on">Ассоциировать с приложением</span>
            <input type="text" name="app" class="span3" id="iFormer-app" value=""/>
          </div>
        </div>
        <div id="field-tab-3" class="tab-pane">
          <div class="clearfix"></div>
          <div class="input-prepend">
            <span class="add-on">Подсказка</span>
            <input type="text" name="holder" class="span3" id="iFormer-holder" value=""/>
          </div>
          <div class="clearfloat"></div>
          <div class="input-prepend">
            <span class="add-on">Сообщение об ошибке</span>
            <input type="text" name="error" class="span3" id="iFormer-error" value=""/>
          </div>
        </div>
        <div id="field-tab-5" class="tab-pane">
          <div class="input-prepend">
            <span class="add-on">Оптимизация</span>
            <select id="iFormer-db" class="chosen-select" style="width:360px;" data-placeholder="Выберите ..." multiple="multiple">
            </select>
            <select multiple="multiple" class="hide" name="db[]" id="sort-db"></select>
          </div>
          <span class="help-inline">Необязательные</span>
        </div>
        <div id="field-tab-6" class="tab-pane">
          <div class="input-prepend">
            <span class="add-on">Код</span>
            <textarea name="javascript" id="iFormer-javascript" class="span6" style="height:60px;"></textarea>
          </div>
          <span class="help-inline">Код JavaScript</span>
        </div>
      </div>
    </div>
  </form>
</div>
