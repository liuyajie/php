<?php
require '../../vendor/autoload.php';
require '../../function/function.php';

$database = new \Medoo\Medoo(['database_type' => 'mysql', 'database_name' => 'school', 'server' => 'localhost', 'username' => 'root', 'password' => 'root', 'charset' => 'utf8']);
$count1 = $database->query('select count(*) from position_3')->fetchColumn();
$count2 = $database->query('select count(*) from position_4 group by town_id')->fetchAll();

echo json_encode(['success'=>1,'msg'=>'请求成功!','count'=>$count1-count($count2)]);
