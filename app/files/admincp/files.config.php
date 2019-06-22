<?php

defined('iPHP') OR exit('Oops, something went wrong');
?>
    <div class="input-prepend"> <span class="add-on">URL каталога файлов</span>
        <!-- <textarea name="config[FS][url]" id="FS_url" class="span6" style="height: 90px;"><?php echo $config['FS']['url'] ; ?></textarea> -->
        <input type="text" name="config[FS][url]" class="span4" id="FS_url" value="<?php echo $config['FS']['url'] ; ?>" />
    </div>
    <span class="help-inline"><!-- 可填写多个,系统将随机选择.<br />格式一行一条<br /> -->Если вы не можете получить к нему доступ, настройте его самостоятельно..<br />Пожалуйста, заполните полный URL, Например: https://www.site.com/res/</span>
    <div class="clearfloat mb10"></div>
    <div class="input-prepend"> <span class="add-on">Каталог файлов</span>
        <input type="text" name="config[FS][dir]" class="span4" id="FS_dir" value="<?php echo $config['FS']['dir'] ; ?>" />
    </div>
    <span class="help-inline">По отношению к корневому каталогу</span>
    <div class="clearfloat mb10"></div>
    <div class="input-prepend input-append"> <span class="add-on">Формат</span>
        <input type="text" name="config[FS][dir_format]" class="span4" id="FS_dir_format" value="<?php echo $config['FS']['dir_format'] ; ?>" />
        <div class="btn-group">
            <a class="btn dropdown-toggle" data-toggle="dropdown" tabindex="-1"><i class="fa fa-question-circle"></i> Помощь </a>
            <ul class="dropdown-menu">
                <li>
                    <a href="Y" data-toggle="insertContent" data-target="#FS_dir_format"><span class="label label-inverse">Y</span> Год (например 2019)</a>
                </li>
                <li>
                    <a href="y" data-toggle="insertContent" data-target="#FS_dir_format"><span class="label label-inverse">y</span> 2位数年份</a>
                </li>
                <li>
                    <a href="m" data-toggle="insertContent" data-target="#FS_dir_format"><span class="label label-inverse">m</span> 月份01-12</a>
                </li>
                <li>
                    <a href="n" data-toggle="insertContent" data-target="#FS_dir_format"><span class="label label-inverse">n</span> 月份1-12</a>
                </li>
                <li>
                    <a href="d" data-toggle="insertContent" data-target="#FS_dir_format"><span class="label label-inverse">n</span> 日期01-31</a>
                </li>
                <li>
                    <a href="j" data-toggle="insertContent" data-target="#FS_dir_format"><span class="label label-inverse">j</span> 日期1-31</a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="md5:0,2/" data-toggle="insertContent" data-target="#FS_dir_format"><span class="label label-inverse">md5:0,2</span> MD5 Файла</a>
                    <a href="md5:2,3/" data-toggle="insertContent" data-target="#FS_dir_format"><span class="label label-inverse">md5:2,3</span> MD5 Файла</a>
                    <a href="date:Ymd/" data-toggle="insertContent" data-target="#FS_dir_format"><span class="label label-inverse">date:Ymd</span> 日期</a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="EXT" data-toggle="insertContent" data-target="#FS_dir_format"><span class="label label-inverse">EXT</span>Тип файла</a>
                </li>
            </ul>
        </div>
    </div>
    <span class="help-inline">Если оставить пустым, все файлы будут загружаться в один каталог, не рекомендуется для больших сайтов.</span>
    <div class="clearfloat mb10"></div>
    <div class="input-prepend"> <span class="add-on">Разрешенные типы файлов к загрузке</span>
        <input type="text" name="config[FS][allow_ext]" class="span4" id="FS_allow_ext" value="<?php echo $config['FS']['allow_ext'] ; ?>" />
    </div>
    <div class="clearfloat mb10"></div>
    <div class="input-prepend"> <span class="add-on">Проверить на дубликат</span>
      <div class="switch">
        <input type="checkbox" data-type="switch" name="config[FS][check_md5]" id="FS_check_md5" <?php echo $config['FS']['check_md5']?'checked':''; ?>/>
      </div>
    </div>
    <span class="help-inline">Проверьте, был ли загружен файл. После открытия один и тот же файл может быть загружен только один раз. Если вам необходимо повторно загрузить его, удалите его в фоновом режиме управления файлами и загрузите его снова.</span>
    <div class="clearfloat mb10"></div>
<!--
    <hr />
    <div class="input-prepend"> <span class="add-on">远程附件</span>
      <div class="switch">
        <input type="checkbox" data-type="switch" name="config[FS][remote][enable]" id="FS_remote_enable" <?php echo $config['FS']['remote']['enable']?'checked':''; ?>/>
      </div>
    </div>
    <span class="help-inline">开启后,可接受其它iCMS程序上传/删除附件</span>
    <div class="clearfloat mb10"></div>
    <div class="input-prepend">
        <span class="add-on">AccessKey</span>
        <input type="text" name="config[FS][remote][AccessKey]" class="span4" id="FS_remote_AccessKey" value="<?php echo $config['FS']['remote']['AccessKey'] ; ?>"/>
    </div>
    <span class="help-inline">该AccessKey会和接口URL中包含的Token进行比对,从而验证安全性</span>
    <div class="clearfloat mb10"></div>
    <div class="input-prepend">
        <span class="add-on">SecretKey</span>
        <input type="text" name="config[FS][remote][SecretKey]" class="span4" id="FS_remote_SecretKey" value="<?php echo $config['FS']['remote']['SecretKey'] ; ?>"/>
    </div>
    <span class="help-inline">该SecretKey会和接口URL中包含的Token进行比对,从而验证安全性</span>
-->
