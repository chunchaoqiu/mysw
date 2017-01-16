<?php
require 'vendor/autoload.php';
define('CURRENT_RUN_MODE','crontab');
define('PHPKIT_CONSOLE_CONFIG_PATH',realpath('config.php'));
define('LOG_PATH', __DIR__ . '/src/logs/' . date('Ymd') . '.log');
App\center\CenterServer::start('crontab');//开始服务



