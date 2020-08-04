<?php
require '../../vendor/autoload.php';
require '../../function/function.php';
set_time_limit(0);

$redis = new Redis();
$redis->connect('127.0.0.1', 6379);

$db         = new \Medoo\Medoo(['database_type' => 'mysql', 'database_name' => 'school', 'server' => 'localhost', 'username' => 'root', 'password' => 'root', 'charset' => 'utf8']);
$startCount = $db->count('position_4');
// 获取每个省对应的村子
$keys = $redis->keys('i-p-*t-*v-*');
$i    = 0;
foreach ($keys as $key) {
    // 获取这个镇的信息
    $data = $redis->hGetAll($key);
    $db->insert('position_4', $data);
    $redis->del($key);
    $i++;
}
$endCount = $db->count('position_4');
echo json_encode([
    'success' => 1,
    'msg'     => '请求成功!',
    'total'   => $i,
    'insert'  => ($endCount - $startCount)
]);