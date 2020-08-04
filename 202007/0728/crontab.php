<?php
require '../../vendor/autoload.php';
require '../../function/function.php';
ini_set('max_execution_time', '0');

$argv = getopt('a:');
$id = $argv['a'];
try {
    $client = new GuzzleHttp\Client();
    // 重置父类数据
    $client->request('GET', 'http://test.com/202007/0728/08.php', []);

    $uri     = 'http://test.com/202007/0728/07.php';
    for( $i=0;$i<5000;$i++ ){
        $res = $client->request('GET', $uri, [
            'query' => ['id' => $id]
        ]);
        $content = $res->getBody();
        echo $content;
        sleep(0.1);
    }
} catch (\GuzzleHttp\Exception\GuzzleException $e) {
    echo $e->getMessage();
}
