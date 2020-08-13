<?php
require '../../vendor/autoload.php';
require '../../function/function.php';

$redis = new Redis();
$redis->connect('127.0.0.1',6379);

$res = $redis->sAdd('test','5');
var_dump($res);
echo '<br/>';
$res = $redis->sAdd('test','5');
var_dump($res);
echo '<br/>';
$res = $redis->sIsMember('test','10');
var_dump($res);
echo '<br/>';