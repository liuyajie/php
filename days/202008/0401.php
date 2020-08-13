<?php
require '../vendor/autoload.php';
require '../function/function.php';
set_time_limit(0);
ini_set('memory_limit', '4096M');
$db    = new \Medoo\Medoo(['database_type' => 'mysql', 'database_name' => 'school', 'server' => 'localhost', 'username' => 'root', 'password' => 'root', 'charset' => 'utf8']);
$datas = $db->select('position_4', '*', [
    'province_id'=>[50,51,52,53,54,61,62,63,64,65],
    'ORDER' => [
        'province_id' => 'ASC',
        'city_id'     => 'ASC',
        'county_id'   => 'ASC',
        'town_id'     => 'ASC',
        'village_id'  => 'ASC'
    ]
]);
foreach ($datas as $data) {
    if ($data['city_name'] == '自治区直辖县级行政区划' || $data['city_name'] == '省直辖县级行政区划') {
        $data['city_comment'] = $data['city_name'];
        $data['city_name']    = $data['county_name'];
    }
    if (preg_match('/村民委员会/', $data['village_name'])) {
        $data['village_name']    = str_replace('村民委员会', '村', $data['village_name']);
        $data['village_comment'] = '村民委员会';
    } elseif (preg_match('/村委会/', $data['village_name'])) {
        $data['village_name']    = str_replace('村委会', '村', $data['village_name']);
        $data['village_comment'] = '村委会';
    } elseif (preg_match('/社区居民委员会/', $data['village_name'])) {
        $data['village_name']    = str_replace('社区居民委员会', '社区', $data['village_name']);
        $data['village_comment'] = '社区居民委员会';
    } elseif (preg_match('/街道办事处居委会/', $data['village_name'])) {
        $data['village_name']    = str_replace('街道办事处居委会', '街道', $data['village_name']);
        $data['village_comment'] = '街道办事处居委会';
    } elseif (preg_match('/社区居委会/', $data['village_name'])) {
        $data['village_name']    = str_replace('社区居委会', '社区', $data['village_name']);
        $data['village_comment'] = '社区居委会';
    }
    $db->insert('region', $data);
}
echo 'done';