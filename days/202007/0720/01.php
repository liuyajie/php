<?php

$data = [
    'username' => 'test',
    'age'      => 'test',
    'sex'      => 'test',
];
$data = null;
echo json_encode($data,JSON_FORCE_OBJECT);