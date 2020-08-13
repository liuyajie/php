<?php
require '../../vendor/autoload.php';
require '../../function/function.php';

$string = '2020-11-21';
$string = '2020/11/21';
$string = '2020/1/21';
$string = '2020/01/01';
$string = 'liuyajie刘亚杰aaaa啊啊啊加加减减浏览量aaa发发发';
$result = MyRegExp::get_chinese_chars($string);
var_dump($result);