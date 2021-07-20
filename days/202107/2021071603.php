<?php

require_once '../../vendor/autoload.php';

$comment = $_POST['comment'];

//$str = <<<DOC
//<a href="https://www.baidu.com">刘亚杰€</a>
//DOC;

// 初始化配置
use Medoo\Medoo;

$database = new Medoo([
    'database_type' => 'mysql',
    'database_name' => 'xiaohaizi',
    'server'        => 'localhost',
    'username'      => 'root',
    'password'      => 'root',
    'charset'       => 'utf8'
]);
$data = $database->insert('article', [
    'comment' => $comment
]);