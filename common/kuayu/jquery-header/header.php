<?php
	//header('Access-Control-Allow-Origin: http://test.com');
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: *');
	header('Access-Control-Allow-Headers: *');
	header('Access-Control-Max-Age: 1000');
	$data = [
		'status'=>1,
		'msg'=>'ok',
		'username'=>$_GET['username'],
		'password'=>$_POST['password']
	];
	$json = json_encode($data);
	echo $json;