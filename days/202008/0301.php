<?php
require '../../vendor/autoload.php';
require '../../function/function.php';

$db = new \Medoo\Medoo(['database_type' => 'mysql', 'database_name' => 'school', 'server' => 'localhost', 'username' => 'root', 'password' => 'root', 'charset' => 'utf8']);

$datas = $db->select('position_3','*',[
    ''
]);


die;
$redis = new Redis();
$redis->connect('127.0.0.1',6379);
$redis->flushAll();

$db = new \Medoo\Medoo(['database_type' => 'mysql', 'database_name' => 'school', 'server' => 'localhost', 'username' => 'root', 'password' => 'root', 'charset' => 'utf8']);

$datas = $db->select('position_3','*',[]);
// 将父级数据存入内存当中
foreach($datas as $data){
    $key = 'p-'.$data['province_id'].'-'.$data['town_id'].'-t';
    $redis->hMSet($key,$data);
}

$datas = $database->select('position_4','*',[
    'GROUP'=>'town_id'
]);
// 将子级数据存入内存当中
foreach($datas as $data){
    if( $data['town_id'] == '' ){
        $database->delete('position_4',[
            'id'=>$data['id']
        ]);
    }
    $key = 's-'.$data['province_id'];
    $bool = $redis->sAdd($key,$data['town_id']);
    // 如果已经存在,说明已经插入过了
    if( $bool == false ){
        $database->delete('position_4',[
            'id'=>$data['id']
        ]);
    }
}
echo 'done';