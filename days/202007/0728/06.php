<?php
header('Content-type:text/html;charset=gbk');
set_time_limit(0);

require '../../vendor/autoload.php';
require '../../function/function.php';

$database = new \Medoo\Medoo(['database_type' => 'mysql', 'database_name' => 'school', 'server' => 'localhost', 'username' => 'root', 'password' => 'root', 'charset' => 'utf8']);
$datas = $database->select('position_1','*',[

]);
try {
    $url = 'www.stats.gov.cn/tjsj/tjbz/tjyqhdmhcxhfdm/2019';
    $queryUrl = '';
    foreach($datas as $data){
        $province_id = $data['province_id'];
        $province_name = $data['province_name'];
        $city_id = $data['city_id'];
        $city_name = $data['city_name'];
        $county_id = $data['county_id'];
        $county_name = $data['county_name'];
        $city_comment = $data['city_comment'];

        // 这三个市比较特殊,下边没有县,直接就是镇
        if( in_array($city_id,['441900000000','442000000000','460400000000']) ){
            $queryUrl = $url . '/'.$province_id.'/' .substr($county_id, 0, 4).'.html';
            if( in_array($city_id,['441900000000','442000000000','460400000000']) ){
                continue;
            }
        }else{
            $queryUrl = $url . '/'.$province_id.'/' .substr($county_id, 2, 2).'/' .substr($county_id, 0, 6).'.html';
            $isQuery = $database->count('success',[
                'url'=>$queryUrl
            ]);
            if( $isQuery ){
                continue;
            }
        }
        // 开始匹配县级 // gbk编码下的汉字 , 另utf8编码下的汉字为'[\x{4e00}-\x{9fa5}]+'
        $client = new GuzzleHttp\Client();
        $res      = $client->request('GET', $queryUrl,[
            'timeout' => 5,
            'headers' =>[
                'Accept'=>'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
                'Cookie'=>'_trs_uv=kd5ec4xq_6_9l9r; AD_RS_COOKIE=20080919; wzws_cid=45715d82d7119b5c68920ef784cedb3892541856b4ee43a682051acfcbf3bf05d31505b9e0c55dbac5992ca28b0ab920c81791e44bf36f32de5195c869413ea6e69fc2ec0d50e56a6332956dbf7798f0',
                'User-Agent'=>'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.80 Safari/537.36',
                'Accept-Encoding'=>'gzip, deflate',
                'Accept-Language'=>'no-cache',
                'Connection'=>'keep-alive',
                'Host'=>'www.stats.gov.cn',
                'Pragma'=>'no-cache',
                'Upgrade-Insecure-Requests'=>1,
            ]
        ]);
        $content = $res->getBody();
        $chinese = '[' . chr(0xa1) . '-' . chr(0xff) . ']+';
        // 匹配出来行政编码 与 xiangzhen
        $pattern = "/" . "<td><a href='\d{1,2}\/(\d{9,})\.html'>(\d{12})<\/a><\/td>" . "<td><a href='(\d{1,2}\/\d{9,}\.html)'>(" . $chinese . ")<\/a><\/td>" . "/";
        preg_match_all($pattern, $content, $towns);
        $insertData = [];
        for ($i = 0; $i < count($towns[0]); $i++) {
            $town_id = $towns[2][$i];
            $town_name = iconv("gbk", "utf-8//ignore", $towns[4][$i]);
            $insertData[] = [
                'province_id'=>$province_id,
                'province_name'=>$province_name,
                'city_id'=>$city_id,
                'city_name'=>$city_name,
                'town_id'=>$town_id,
                'town_name'=>$town_name,
                'county_id'=>$county_id,
                'county_name'=>$county_name,
                'comment'=>$city_comment,
            ];
        }
        $database->insert('position_2',$insertData);
        $database->insert('success', ['url' => $queryUrl]);
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