<?php
require '../vendor/autoload.php';
require '../function/function.php';
set_time_limit(0);
ini_set('memory_limit', '4096M');
$db    = new \Medoo\Medoo(['database_type' => 'mysql', 'database_name' => 'school', 'server' => 'localhost', 'username' => 'root', 'password' => 'root', 'charset' => 'utf8']);
$datas = $db->select('region', '*', [
    "village_name[~]" => "村村"
]);
foreach ($datas as $data) {
    $data['village_name']    = str_replace('村村', '村', $data['village_name']);
    $db->update('region', [
        'village_name'=>$data['village_name']
    ],[
        'id'=>$data['id']
    ]);
}
echo 'done';