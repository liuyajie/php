<?php
header('Content-type:text/html;charset=gbk');
set_time_limit(0);
require '../../vendor/autoload.php';
require '../../function/function.php';
$id = $_GET['id'];
$redis = new Redis();
$redis->connect('127.0.0.1',6379);
try {
    $url = 'www.stats.gov.cn/tjsj/tjbz/tjyqhdmhcxhfdm/2019';
    // 获取每个省对应的镇
    $keys = $redis->keys('p-'.$id.'-*');
    foreach($keys as $key){
        // 获取这个镇的信息
        $data = $redis->hGetAll($key);

        $province_id = $data['province_id'];
        $province_name = $data['province_name'];
        $city_id = $data['city_id'];
        $city_name = $data['city_name'];
        $county_id = $data['county_id'];
        $county_name = $data['county_name'];
        $town_id = $data['town_id'];
        $town_name = $data['town_name'];
        $city_comment = $data['comment'];

        // 判断是否是重复请求
        if( $redis->sIsMember('s-'.$province_id,$town_id) ){
            continue;
        }
        if( in_array($city_id,['441900000000','442000000000','460400000000']) ){
            $queryUrl = $url . '/'.$province_id.'/' .substr($town_id, 2, 2).'/' .substr($town_id, 0, 9).'.html';
        }else{
            $queryUrl = $url . '/'.$province_id.'/' .substr($town_id, 2, 2).'/'.substr($town_id, 4, 2).'/' .substr($town_id, 0, 9).'.html';
        }
        // 开始匹配县级 // gbk编码下的汉字 , 另utf8编码下的汉字为'[\x{4e00}-\x{9fa5}]+'
        $client = new GuzzleHttp\Client();
        $jar = new GuzzleHttp\Cookie\CookieJar();
        $res      = $client->request('GET', $queryUrl,[
            'timeout' => 10,
            'headers' =>[
                'Accept'=>'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
                //'Cookie'=>'_trs_uv=kd5ec4xq_6_9l9r; AD_RS_COOKIE=20080917; wzws_cid=85c4f22586f443a72d8a28960fdbb7b8fbf91572afa611afca5e4a4b6e14681a43c39e4b18d8341b7662c377b823eb62d5c79065cb60501aacda307fff383d6e93f168f7ea5176ddcf28bf7104363236',
                'Cookie'=>'_trs_uv=kd5ec4xq_6_9l9r; __utma=207252561.323193211.1596190158.1596190158.1596190158.1; __utmz=207252561.1596190158.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none); AD_RS_COOKIE=20080917',
                'User-Agent'=>'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.80 Safari/537.36',
                'Accept-Encoding'=>'gzip, deflate',
                'Accept-Language'=>'no-cache',
                'Connection'=>'keep-alive',
                'Host'=>'www.stats.gov.cn',
                'Pragma'=>'no-cache',
                'Upgrade-Insecure-Requests'=>1,
            ],
            'cookies' => $jar
        ]);
        $content = $res->getBody();
        $chinese = '[' . chr(0xa1) . '-' . chr(0xff) . ']+';
        // 匹配出来行政编码 与 xiangzhen
        $pattern = "/" . "<td>(\d{12})<\/td>" . "<td>\d{1,}<\/td>" . "<td>(" . $chinese . ")<\/td>" . "/";
        preg_match_all($pattern, $content, $villages);

        if( count($villages[0]) ){
            for ($i = 0; $i < count($villages[0]); $i++) {
                $village_id = $villages[1][$i];
                $village_name = iconv("gbk", "utf-8//ignore", $villages[2][$i]);
                $insertData = [
                    'province_id'=>$province_id,
                    'province_name'=>$province_name,
                    'city_id'=>$city_id,
                    'city_name'=>$city_name,
                    'town_id'=>$town_id,
                    'town_name'=>$town_name,
                    'village_id'=>$village_id,
                    'village_name'=>$village_name,
                    'county_id'=>$county_id,
                    'county_name'=>$county_name,
                    'comment'=>$city_comment,
                ];
                $key1 = 'i-p-'.$province_id.'t-'.$town_id.'v-'.$village_id;
                $redis->hMSet($key1,$insertData);
            }
        }else{
            $insertData = [
                'province_id'=>$province_id,
                'province_name'=>$province_name,
                'city_id'=>$city_id,
                'city_name'=>$city_name,
                'town_id'=>$town_id,
                'town_name'=>$town_name,
                'village_id'=>'',
                'village_name'=>'',
                'county_id'=>$county_id,
                'county_name'=>$county_name,
                'comment'=>$city_comment,
            ];
            $key1 = 'i-p-'.$province_id.'t-'.$town_id.'v-'.'';
            $redis->hMSet($key1,$insertData);
        }
        // 防止重复请求
        $redis->sAdd('s-'.$province_id,$town_id);
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
echo 'done'."\r\n";