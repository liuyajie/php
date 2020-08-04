<?php
header("Content-type: text/html; charset=gbk");

$dsn = 'mysql:host=127.0.0.1;dbname=school';
$db  = new PDO($dsn, 'root', 'root');

// 超时设置 错误报告
ini_set('max_execution_time', '0');
error_reporting(0);

$url       = 'http://www.stats.gov.cn/tjsj/tjbz/tjyqhdmhcxhfdm/2019/';
$matches   = array(
    array(),
    array(11, 12, 13, 14, 15, 21, 22, 23, 31, 32, 33, 34, 35, 36, 37, 41, 42, 43, 44, 45, 46, 50, 51, 52, 53, 54, 61, 62, 63, 64, 65),
    array('北京市', '天津市', '河北省', '山西省', '内蒙古自治区', '辽宁省', '吉林省', '黑龙江省',
        '上海市', '江苏省', '浙江省', '安徽省', '福建省', '江西省', '山东省', '河南省',
        '湖北省', '湖南省', '广东省', '广西壮族自治区', '海南省', '重庆市', '四川省', '贵州省',
        '云南省', '西藏自治区', '陕西省', '甘肃省', '青海省', '宁夏回族自治区', '新疆维吾尔自治区',
    ),
);
$otherData = [
    '县'      => '',
    '市辖区'   => '',
    '省直管县区域' => ''
];

for ($i = 0, $e = count($matches[1]); $i < $e; $i++) {
    $index = file_get_contents($url . $matches[1][$i] . '.html');
    preg_match_all('/<a href=\'\d{2}\/(.{1,30}).html\'>(.{1,30})<\/a><\/td><\/tr>/', $index, $matche);

    for ($a = 0, $b = count($matche[1]); $a < $b; $a++) {
        $index = file_get_contents($url . $matches[1][$i] . '/' . $matche[1][$a] . '.html');
        preg_match_all('/<a href=\'\d{2}\/(.{1,30}).html\'>(.{1,30})</a></td><\/tr>/', $index, $match);
        for ($c = 0, $d = count($match[1]); $c < $d; $c++) {
            $aru   = substr($matche[1][$a], 2, 2);
            $index = file_get_contents($url . $matches[1][$i] . '/' . $aru . '/' . $match[1][$c] . '.html');
            preg_match_all('/<a href=\'\d{2}\/(.{1,30}).html\'>(.{1,30})<\/a><\/td><\/tr>/', $index, $matc);

            //部分省市的html和大部分的不一样，重写规则
            if (!$matc[0]) {
                preg_match_all('/<td>(.{1,30})<\/td><td>\d{1,10}<\/td><td>(.{1,30})<\/td><\/tr>/', $index, $matc);
            }

            $sql = 'REPLACE INTO position (province_id,province_name,city_id,city_name,county_id,county_name,town_id,town_name) VALUES ';
            //补0处理 11->110000
            $provinces_id  = str_pad($provinces[1][$i], 6, "0", STR_PAD_RIGHT);
            $province_name = iconv("utf-8", "gbk//ignore", $provinces[2][$i]);

            //补0处理 1101->110100
            $city_id = str_pad($city[1][$a], 6, "0", STR_PAD_RIGHT);
            for ($v = 0, $n = count($matc[1]); $v < $n; $v++) {
                $sql .= "({$provinces_id},'{$province_name}',{$city_id},'{$matche[2][$a]}',{$match[1][$c]},'{$match[2][$c]}',{$matc[1][$v]},'{$matc[2][$v]}'),";
            }
            $sql = iconv("gbk", "utf-8//ignore", $sql);
            $res = $db->query(rtrim($sql, ","));
            echo $sql . '</br> ';

        }
    }
}