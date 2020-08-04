<?php
require '../../vendor/autoload.php';
require '../../function/function.php';
use GuzzleHttp\Client;

try {
    $client = new Client(['base_uri'=>'http://apm.hd.com']);
    $response = $client->request('POST', '/Order/listsv3', [
        'form_params' => [
            'muid' => '7',
            'pwd'   => '467736',
        ]
    ]);
    $code = $response->getStatusCode();
    $reason = $response->getReasonPhrase();
    $body = $response->getBody();
    if( $data = json_decode($body,TRUE) ) {
        dump($data);
    }else{
        dump($body->getContents());
    }
} catch (\GuzzleHttp\Exception\GuzzleException $e) {
    dump($e);
}
