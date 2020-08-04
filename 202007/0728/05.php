<?php
header('Content-type:text/html;charset=gbk');
set_time_limit(0);

require '../../vendor/autoload.php';
require '../../function/function.php';

$database = new \Medoo\Medoo(['database_type' => 'mysql', 'database_name' => 'school', 'server' => 'localhost', 'username' => 'root', 'password' => 'root', 'charset' => 'utf8']);
$datas = $database->select('position_0','*',[

]);
try {
    $url = 'www.stats.gov.cn/tjsj/tjbz/tjyqhdmhcxhfdm/2019';
    $queryUrl = '';
    foreach($datas as $data){
        $province_id = $data['province_id'];
        $province_name = $data['province_name'];
        $city_id = $data['city_id'];
        $city_name = $data['city_name'];
        $city_comment = $data['city_comment'];

        $queryUrl = $url . '/' . $province_id . '/' .substr($city_id, 0, 4) . '.html';
        $isQuery = $database->count('success',[
            'url'=>$queryUrl
        ]);
        if( $isQuery ){
            continue;
        }
        // 开始匹配县级
        $client = new GuzzleHttp\Client();
        $res      = $client->request('GET', $queryUrl,['timeout' => 5]);
        $content = $res->getBody();

        // gbk编码下的汉字 , 另utf8编码下的汉字为'[\x{4e00}-\x{9fa5}]+'
        $chinese = '[' . chr(0xa1) . '-' . chr(0xff) . ']+';
        // 匹配出来行政编码 与 城市名称
        $pattern = "/" . "<td><a href='\d{1,2}\/(\d{1,6})\.html'>(\d{12})<\/a><\/td>" . "<td><a href='(\d{1,2}\/\d{1,6}\.html)'>(" . $chinese . ")<\/a><\/td>" . "/";
        preg_match_all($pattern, $content, $countys);

        if( count($countys[0]) ){
            $insertData = [];
            for ($i = 0; $i < count($countys[0]); $i++) {
                $county_id = $countys[2][$i];
                $county_name = iconv("gbk", "utf-8//ignore", $countys[4][$i]);
                $insertData[] = [
                    'province_id'=>$province_id,
                    'province_name'=>$province_name,
                    'city_id'=>$city_id,
                    'city_name'=>$city_name,
                    'county_id'=>$county_id,
                    'county_name'=>$county_name,
                    'comment'=>$city_comment,
                ];
            }
            $database->insert('position_1',$insertData);
            $database->insert('success', ['url' => $queryUrl]);
        }else{
            $insertData = [
                'province_id'=>$province_id,
                'province_name'=>$province_name,
                'city_id'=>$city_id,
                'city_name'=>$city_name,
                'county_id'=> $city_id . '00',
                'county_name'=>$city_name,
                'comment'=>$city_comment,
            ];
            $database->insert('position_1',$insertData);
            $database->insert('success', ['url' => $queryUrl]);
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