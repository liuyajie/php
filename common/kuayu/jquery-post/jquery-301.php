<?php

$json = $_GET['data'];
$callback = $_GET['callback'];
exit($callback."($json)");