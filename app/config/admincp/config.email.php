<?php

defined('iPHP') OR exit('Oops, something went wrong');
?>

    <div class="input-prepend"> <span class="add-on">SMTP-хост</span>
        <input type="text" name="config[mail][host]" class="span3" id="mail_host" value="<?php echo $config['mail']['host']; ?>" />
    </div>
    <span class="help-inline">Например: smtp.gmail.com</span>
    <div class="clearfloat mt10"></div>
    <div class="input-prepend"> <span class="add-on">Протокол безопасности</span>
        <input type="text" name="config[mail][secure]" class="span3" id="mail_secure" value="<?php echo $config['mail']['secure']; ?>" />
    </div>
    <span class="help-inline">Протокол безопасности, используемый сервером. По умолчанию пусто. Необязательно "ssl" или "tls"</span>
    <div class="clearfloat mt10"></div>
    <div class="input-prepend"> <span class="add-on">Порт SMTP</span>
        <input type="text" name="config[mail][port]" class="span3" id="mail_port" value="<?php echo $config['mail']['port']?:'25'; ?>" />
    </div>
    <span class="help-inline">Порт сервера по умолчанию: 25</span>
    <div class="clearfloat mt10"></div>
    <div class="input-prepend"> <span class="add-on">Учетная запись SMTP</span>
        <input type="text" name="config[mail][username]" class="span3" id="mail_username" value="<?php echo $config['mail']['username']; ?>" />
    </div>
    <span class="help-inline"></span>
    <div class="clearfloat mt10"></div>
    <div class="input-prepend"> <span class="add-on">Пароль учетной записи</span>
        <input type="text" name="config[mail][password]" class="span3" id="mail_password" value="<?php echo $config['mail']['password']; ?>" />
    </div>
    <span class="help-inline"></span>
    <div class="clearfloat mt10"></div>
    <div class="input-prepend"> <span class="add-on">Email отправителя</span>
        <input type="text" name="config[mail][setfrom]" class="span3" id="mail_setfrom" value="<?php echo $config['mail']['setfrom']; ?>" />
    </div>
    <span class="help-inline">Учетная запись, используемая для отправки сообщений</span>
    <div class="clearfloat mt10"></div>
    <div class="input-prepend"> <span class="add-on">Контактный Email</span>
        <input type="text" name="config[mail][replyto]" class="span3" id="mail_replyto" value="<?php echo $config['mail']['replyto']; ?>" />
    </div>
    <span class="help-inline">Используется для ответа на электронную почту в письме</span>
    <div class="clearfloat mt10"></div>

