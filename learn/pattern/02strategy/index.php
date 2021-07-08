<?php
spl_autoload_register(function ($class_name) {
    require './' . $class_name . '.php';
});
error_reporting(E_ALL ^ E_NOTICE);

$money = 100;

$obj = new CashContext(['type'=>2,'rebate'=>0.3]);

echo $obj->getResult(300);
