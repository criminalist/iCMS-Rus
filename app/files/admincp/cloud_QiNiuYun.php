<?php

defined('iPHP') OR exit('Oops, something went wrong');
?>
<div id="QiNiuYun">
    <h3 class="title">七牛云存储</h3>
    <span class="help-inline">申请地址:<a href="https://portal.qiniu.com/signup?from=iCMS" target="_blank">https://portal.qiniu.com/signup</a></span>
    <div class="clearfloat"></div>
    <div class="input-prepend">
        <span class="add-on">域名</span>
        <input type="text" name="config[sdk][QiNiuYun][domain]" class="span4" id="cloud_QiNiuYun_domain" value="<?php echo $config['sdk']['QiNiuYun']['domain'] ; ?>"/>
    </div>
    <span class="help-inline">云存储访问域名</span>
    <div class="clearfloat mb10"></div>
    <div class="input-prepend">
        <span class="add-on">Bucket</span>
        <input type="text" name="config[sdk][QiNiuYun][Bucket]" class="span4" id="cloud_QiNiuYun_Bucket" value="<?php echo $config['sdk']['QiNiuYun']['Bucket'] ; ?>"/>
    </div>
    <span class="help-inline">空间名称</span>
    <div class="clearfloat mb10"></div>
    <div class="input-prepend">
        <span class="add-on">AccessKey</span>
        <input type="text" name="config[sdk][QiNiuYun][AccessKey]" class="span4" id="cloud_QiNiuYun_AccessKey" value="<?php echo $config['sdk']['QiNiuYun']['AccessKey'] ; ?>"/>
    </div>
    <div class="clearfloat mb10"></div>
    <div class="input-prepend">
        <span class="add-on">SecretKey</span>
        <input type="text" name="config[sdk][QiNiuYun][SecretKey]" class="span4" id="cloud_QiNiuYun_SecretKey" value="<?php echo $config['sdk']['QiNiuYun']['SecretKey'] ; ?>"/>
    </div>
    <div class="clearfloat mb10"></div>
</div>
