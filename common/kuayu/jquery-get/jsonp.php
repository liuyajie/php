<?php
	$data = [
		'status'=>1,
		'msg'=>'ok',
		'username'=>$_GET['username'],
		'password'=>$_POST['password']
	];
	$json = json_encode($data);
	$callback = $_GET['callback'];
	exit($callback."($json)");