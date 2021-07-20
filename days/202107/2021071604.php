<!DOCTYPE html>
<html lang="zh">
<header>
    <meta charset="UTF-8">
    <title>Test Show Text</title>
</header>
<body>
<?php
require_once '../../vendor/autoload.php';
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
$data = $database->select('article','*',[]);


var_dump($data);

?>
</body>
</html>