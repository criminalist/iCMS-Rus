<?php

define('iPHP_DEBUG', true);
define('iPHP_WAF_POST',false);
define('iPHP_DEVICE_REDIRECT', false);
require dirname(__FILE__) . '/iCMS.php';
admincp::run();
