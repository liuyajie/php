<?php
spl_autoload_register('my_autoload');
function my_autoload($class_name)
{
    require '../../class/' . $class_name . '.php';
}