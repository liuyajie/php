<?php
	$data = [
		'status'=>1,
		'msg'=>'ok'
	];
	$json = json_encode($data);
	$callback = $_GET['callback'];
	exit($callback."($json)");