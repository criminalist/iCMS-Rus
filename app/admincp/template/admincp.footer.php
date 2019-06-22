<?php

defined('iPHP') OR exit('Oops, something went wrong');
?>
  <div class="clearfloat"></div>
  <div class="iCMS-container footer-debug">
    <span class="label label-success">
      Память:<?php echo iFS::sizeUnit(memory_get_usage());?> Время выполнения:<?php echo iPHP::timer_stop();?> s
    </span>
  </div>
</div>
<a id="scrollUp" href="#top"></a>
<div class="iCMS-batch">
  <div id="weightBatch">
    <div class="input-prepend"><span class="add-on">Вес</span>
      <input type="text" class="span4" name="mweight"/>
    </div>
  </div>
  <div id="keywordBatch">
    <div class="input-prepend input-append"><span class="add-on">Ключевое слово</span>
      <input type="text" class="span4" name="mkeyword"/>
    </div>
    <div class="clearfloat mb10"></div>
    <div class="input-prepend input-append">
      <span class="add-on">追加<input type="radio" name="pattern" value="addto"/></span>
      <span class="add-on">Заменить<input type="radio" name="pattern" value="replace" checked/></span>
      <span class="add-on"> Удалить<input type="radio" name="pattern" value="delete"/></span>
    </div>
  </div>
  <div id="tagBatch">
    <div class="input-prepend"><span class="add-on">Теги</span>
      <input type="text" class="span4" name="mtag"/>
    </div>
    <div class="clearfloat mb10"></div>
    <div class="input-prepend input-append">
      <span class="add-on">追加<input type="radio" name="pattern" value="addto" checked/></span>
      <span class="add-on">Заменить<input type="radio" name="pattern" value="replace"/></span>
      <span class="add-on"> Удалить<input type="radio" name="pattern" value="delete"/></span>
    </div>
  </div>
</div>
</body></html>
