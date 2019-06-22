#!/usr/bin/env php
<?php
require dirname(__file__).'/../iCMS.php';

$publicApp = new publicApp();
$publicApp->API_crontab();
