<?php
header('Content-type:text/html;charset=gbk');
set_time_limit(0);
//error_reporting(0);

require '../../vendor/autoload.php';
require '../../function/function.php';
$dsn = 'mysql:host=127.0.0.1;dbname=school';
$db  = new PDO($dsn, 'root', 'root');

$data = [
    11 => '北京市',
    12 => '天津市',
    13 => '河北省',
    14 => '山西省',
    15 => '内蒙古自治区',
    21 => '辽宁省',
    22 => '吉林省',
    23 => '黑龙江省',
    31 => '上海市',
    32 => '江苏省',
    33 => '浙江省',
    34 => '安徽省',
    35 => '福建省',
    36 => '江西省',
    37 => '山东省',
    41 => '河南省',
    42 => '湖北省',
    43 => '湖南省',
    44 => '广东省',
    45 => '广西壮族自治区',
    46 => '海南省',
    50 => '重庆市',
    51 => '四川省',
    52 => '贵州省',
    53 => '云南省',
    54 => '西藏自治区',
    61 => '陕西省',
    62 => '甘肃省',
    63 => '青海省',
    64 => '宁夏回族自治区',
    65 => '新疆维吾尔自治区',
];
// 基础URL
$url = 'www.stats.gov.cn/tjsj/tjbz/tjyqhdmhcxhfdm/2019/';
try {
    // 模拟浏览器请求
    $client = new GuzzleHttp\Client();
    foreach ($data as $province_id => $province_name) {
        $province_name = iconv("utf-8", "gbk//ignore", $province_name);

        $res     = $client->request('GET', $url . $province_id . '.html');
        $content = $res->getBody();
        echo 'City';
        echo $res->getStatusCode(); // 200
        echo $res->getReasonPhrase(); // OK
        echo '<br/>';

        // gbk编码下的汉字 , 另utf8编码下的汉字为'[\x{4e00}-\x{9fa5}]+'
        $chinese = '[' . chr(0xa1) . '-' . chr(0xff) . ']+';
        // 匹配出来行政编码 与 城市名称
        $pattern = "/" . "<td><a href='\d{1,2}\/(\d{1,4})\.html'>(\d{12})<\/a><\/td>" . "<td><a href='(\d{1,2}\/\d{1,4}\.html)'>(" . $chinese . ")<\/a><\/td>" . "/";
        preg_match_all($pattern, $content, $citys);

        $sql = 'insert INTO position_0 (province_id,province_name,city_id,city_name,comment) VALUES ';
        $other_sql = '';
        for ($i = 0; $i < count($citys[0]); $i++) {
            $city_id      = $citys[2][$i];
            $city_name    = $citys[4][$i];
            $city_name_1  = iconv("gbk", "utf-8//ignore", $city_name);
            $city_comment = '';
            if ($city_name_1 == '市辖区' || $city_name_1 == '县') {
                $city_comment = $city_name;
                $city_name    = $province_name;
            }
            if ($city_name_1 == '省直辖县级行政区划') {
                $city_comment = $city_name;
            }
            $other_sql .= "({$province_id},'{$province_name}',{$city_id},'{$city_name}','{$city_comment}'),";
        }

        $other_sql = iconv("gbk", "utf-8//ignore", $other_sql);
        if ($other_sql) {
            $db->query(trim($sql . $other_sql, ','));
        }
    }
} catch (\GuzzleHttp\Exception\RequestException $e) {
    echo $e->getMessage();
} catch (\GuzzleHttp\Exception\GuzzleException $e) {
    echo $e->getMessage();
} catch (PDOException $e) {
    echo $e->getMessage();
} catch (Exception $e) {
    echo $e->getMessage();
}
echo 'done';