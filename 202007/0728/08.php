<?php
require '../../vendor/autoload.php';
require '../../function/function.php';

$redis = new Redis();
$redis->connect('127.0.0.1',6379);

$db = new \Medoo\Medoo(['database_type' => 'mysql', 'database_name' => 'school', 'server' => 'localhost', 'username' => 'root', 'password' => 'root', 'charset' => 'utf8']);

// 将父级数据存入内存当中
if( !$redis->get('isP') ){
    $datas = $db->select('position_3','*',[]);
    foreach($datas as $data){
        $key = 'p-'.$data['province_id'].'-'.$data['town_id'].'-t';
        $redis->hMSet($key,$data);
    }
    $redis->set('isP',1);
}
echo 'done';