<?php
$file = './1.txt';
$dh = fopen($file, 'a+');
fwrite($dh,'test');
fclose($dh);