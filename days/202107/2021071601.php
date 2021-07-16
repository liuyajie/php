<?php
require_once '../../vendor/autoload.php';

use Medoo\Medoo;

// 初始化配置
$database = new Medoo([
    'database_type' => 'mysql',
    'database_name' => 'xiaohaizi',
    'server'        => 'localhost',
    'username'      => 'root',
    'password'      => 'root',
    'charset'       => 'utf8'
]);

$str  = file_get_contents('./1.txt');
$data = explode(PHP_EOL, $str);

$work_month = '2021-';

foreach ($data as $val) {

    $iData = explode(',', $val);

    $database->insert('salary', [
        'one_time'     => $iData[1],
        'two_time'     => $iData[2],
        'one_salary'   => $iData[3],
        'two_salary'   => $iData[4],
        'total_salary' => $iData[3] + $iData[4],
    ]);
}
