<?php
require_once '../../../vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$log = new Logger('myApp');
$log->pushHandler(new StreamHandler('info.log'));

$log->info('this is a info message');
$log->debug('this is a debug message');
$log->warning('this is a warning message');

$log = new Logger('myApp1');
$log->pushHandler(new StreamHandler('info1.log'));

$log->info('this is a info1 message');
$log->debug('this is a debug1 message');
$log->warning('this is a warning1 message');
