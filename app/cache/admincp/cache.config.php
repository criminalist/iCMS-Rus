<?php

defined('iPHP') OR exit('Oops, something went wrong');
?>
<div class="input-prepend"> <span class="add-on">Кеширование</span>
  <select name="config[cache][engine]" id="cache_engine" class="chosen-select">
    <option value="file">Файловый кеш (FileCache)</option>
    <option value="memcached">Memcached</option>
    <option value="redis">Redis</option>
  </select>
</div>
<script>$(function(){iCMS.select('cache_engine',"<?php echo $config['cache']['engine'] ; ?>");});</script>
<span class="help-inline">Memcache, Redis, необходимо установить и настроить на сервере, рекомендуем использовать Redis.</span>
<div class="clearfloat mb10"></div>
<div class="input-prepend"> <span class="add-on">Конфигурация кэша</span>
  <textarea name="config[cache][host]" id="cache_host" class="span6" style="height: 150px;"><?php echo $config['cache']['host'] ; ?></textarea>
</div>
<span class="help-inline">
  <div class="file_help">
  Каталог файлового кэша (по умолчанию пуст)<hr />
  Пример:data<br />
  Не рекомендуется создавать большое количество вложений, может влиять на производительность.
  </div>
  <div class="hide memcached_help">
  Memcached <hr />
  IP-адрес сервера:порт (По одному на строку) <br />
  Пример:<br />127.0.0.1:11211<br />
  192.168.0.1:11211
  </div>
  <div class="hide redis_help">
  Redis<hr />
  Пример:<br />unix:///tmp/redis.sock@db:1 <br />
  unix:///tmp/redis.sock@db:1@password<br />
  127.0.0.1:6379@db:1<br />
  127.0.0.1:6379@db:1@password
  </div>
</span>
<script>
$('.file_help,.memcached_help,.redis_help').hide();
$('.<?php echo $config['cache']['engine'] ; ?>_help').show();

$(function(){
  $("#cache_engine").change(function(event) {
    var a = '.'+this.value+'_help';
    $('.file_help,.memcached_help,.redis_help').hide();
    $(a).show();
  });
});
</script>

<div class="clearfloat mb10"></div>
<div class="input-prepend input-append"> <span class="add-on">Время кеширования</span>
  <input type="text" name="config[cache][time]" class="span1" id="cache_time" value="<?php echo $config['cache']['time'] ; ?>"/>
  <span class="add-on">сек.</span>
</div>
<div class="clearfloat mb10"></div>
<div class="input-prepend"> <span class="add-on">Сжатие данных</span>
  <div class="switch">
    <input type="checkbox" data-type="switch" name="config[cache][compress]" id="cache_compress" <?php echo $config['cache']['compress']?'checked':''; ?>/>
  </div>
</div>
<hr />
<div class="clearfloat mb10"></div>
<div class="input-prepend input-append">
  <span class="add-on">Кеш счетчика страниц</span>
  <input type="text" name="config[cache][page_total]" class="span1" id="page_total" value="<?php echo $config['cache']['page_total']?:$config['cache']['time']; ?>"/>
  <span class="add-on">сек.</span>
</div>
<span class="help-inline">设置分页总数缓存时间,设置此项分页性能将会有极大的提高.</span>
<input type="hidden" name="config[cache][prefix]" id="cache_prefix" value="<?php echo iPHP_APP_SITE ?>"/>
